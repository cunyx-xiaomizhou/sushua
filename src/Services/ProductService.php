<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;

final class ProductService
{
    private PDO $pdo;
    private UpstreamClient $upstream;
    private PricingService $pricing;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->upstream = new UpstreamClient();
        $this->pricing = new PricingService();
    }

    public function syncFromUpstream(): array
    {
        $response = $this->upstream->queryGoods();
        if ((int) ($response['code'] ?? 500) !== 200) {
            throw new RuntimeException((string) ($response['msg'] ?? '上游商品同步失败'));
        }
        $goods = (array) ($response['data'] ?? []);
        $count = 0;
        foreach ($goods as $key => $item) {
            if (!is_array($item)) {
                continue;
            }
            $count++;
            $sign = (string) ($item['sign'] ?? '');
            if ($sign === '') {
                continue;
            }
            $exists = $this->pdo->prepare('SELECT id FROM products WHERE upstream_sign = ? LIMIT 1');
            $exists->execute([$sign]);
            $found = $exists->fetchColumn();
            $payload = [
                (string) $key,
                $sign,
                (string) ($item['name'] ?? $sign),
                (int) ($item['min'] ?? 0),
                (int) ($item['max'] ?? 0),
                max(1, (int) ($item['step'] ?? 1)),
                json_encode($item['steps'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                json_encode($item['input'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                json_encode($item['desc'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                array_key_exists('min_delayed', $item) ? (int) $item['min_delayed'] : null,
                (int) ($item['price'] ?? 0),
                array_key_exists('price_delayed', $item) ? (int) $item['price_delayed'] : null,
                (string) ($item['level'] ?? ''),
                json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                now(),
            ];
            if ($found) {
                $sql = 'UPDATE products SET upstream_key=?, upstream_sign=?, name=?, min_num=?, max_num=?, step_num=?, steps_json=?, input_json=?, desc_json=?, min_delayed=?, price_cost=?, price_cost_delayed=?, upstream_level=?, payload_json=?, synced_at=?, updated_at=? WHERE upstream_sign=?';
                $params = $payload; $params[] = now(); $params[] = $sign;
                $this->pdo->prepare($sql)->execute($params);
            } else {
                $sql = 'INSERT INTO products (upstream_key, upstream_sign, name, min_num, max_num, step_num, steps_json, input_json, desc_json, min_delayed, price_cost, price_cost_delayed, upstream_level, allow_frontend, allow_api, enabled, payload_json, synced_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, 1, ?, ?, ?, ?)';
                $params = $payload; $params[] = now(); $params[] = now();
                $this->pdo->prepare($sql)->execute($params);
            }
        }
        return ['count' => $count];
    }

    public function list(array $user, bool $forApi = false): array
    {
        $stmt = $this->pdo->query('SELECT * FROM products WHERE enabled = 1 ORDER BY id DESC');
        $group = $this->group((int) $user['user_group_id']);
        $list = [];
        foreach ($stmt->fetchAll() as $product) {
            if ($forApi && (int) $product['allow_api'] !== 1) continue;
            if (!$forApi && (int) $product['allow_frontend'] !== 1) continue;
            $product = $this->normalize($product);
            $preview = $this->pricing->calculate($product, $group, max(1, (int) $product['step_num']), false);
            $product['preview_price'] = $preview['sell_price'];
            $product['discounts'] = $this->discounts((int) $product['id']);
            $list[] = $product;
        }
        return $list;
    }

    public function adminList(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY id DESC');
        $rows = [];
        foreach ($stmt->fetchAll() as $row) {
            $row = $this->normalize($row);
            $row['discounts'] = $this->discounts((int) $row['id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function saveProduct(array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            throw new RuntimeException('商品ID不合法');
        }
        $exists = $this->pdo->prepare('SELECT min_num, max_num FROM products WHERE id = ? LIMIT 1');
        $exists->execute([$id]);
        $product = $exists->fetch();
        if (!$product) {
            throw new RuntimeException('商品不存在');
        }

        $discounts = [];
        if (array_key_exists('discounts', $data)) {
            if (!is_array($data['discounts'])) {
                throw new RuntimeException('折扣规则格式不正确');
            }
            foreach ($data['discounts'] as $discount) {
                if (!is_array($discount)) continue;
                $minQ = filter_var($discount['min_quantity'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                if ($minQ === false || $minQ === null) {
                    throw new RuntimeException('折扣数量门槛必须是大于0的整数');
                }
                if ((int) $product['max_num'] > 0 && $minQ > (int) $product['max_num']) {
                    throw new RuntimeException('折扣数量门槛不能超过商品最大数量');
                }
                $rawRate = $discount['discount_rate'] ?? null;
                if (!is_numeric((string) $rawRate)) {
                    throw new RuntimeException('折扣力度必须是数字');
                }
                $rate = (float) $rawRate;
                if ($rate < 0.01 || $rate > 1.0) {
                    throw new RuntimeException('折扣力度必须在0.01-1.0之间');
                }
                if (isset($discounts[$minQ])) {
                    throw new RuntimeException('折扣数量门槛不能重复：' . $minQ);
                }
                $discounts[$minQ] = ['min_quantity' => $minQ, 'discount_rate' => round($rate, 4)];
            }
            ksort($discounts, SORT_NUMERIC);
        }

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare('UPDATE products SET allow_frontend = ?, allow_api = ?, enabled = ?, updated_at = ? WHERE id = ?')->execute([
                !empty($data['allow_frontend']) ? 1 : 0,
                !empty($data['allow_api']) ? 1 : 0,
                !empty($data['enabled']) ? 1 : 0,
                now(),
                $id,
            ]);
            if (array_key_exists('discounts', $data)) {
                $this->pdo->prepare('DELETE FROM product_discounts WHERE product_id = ?')->execute([$id]);
                foreach ($discounts as $discount) {
                    $this->pdo->prepare('INSERT INTO product_discounts (product_id, min_quantity, discount_rate, active, created_at, updated_at) VALUES (?, ?, ?, 1, ?, ?)')->execute([
                        $id, $discount['min_quantity'], $discount['discount_rate'], now(), now(),
                    ]);
                }
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            throw $e;
        }
        return $this->get($id);
    }

    public function findBySign(string $sign): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE upstream_sign = ? LIMIT 1');
        $stmt->execute([$sign]);
        $row = $stmt->fetch();
        if (!$row) throw new RuntimeException('商品不存在');
        return $this->normalize($row);
    }

    public function get(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) throw new RuntimeException('商品不存在');
        $row = $this->normalize($row);
        $row['discounts'] = $this->discounts($id);
        return $row;
    }

    private function discounts(int $productId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product_discounts WHERE product_id = ? ORDER BY min_quantity ASC');
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    private function group(int $groupId): array
    {
        foreach ((new UserGroupService())->all() as $group) {
            if ((int) $group['id'] === $groupId) return $group;
        }
        throw new RuntimeException('用户组不存在');
    }

    private function normalize(array $row): array
    {
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['min_num'] = (int) ($row['min_num'] ?? 0);
        $row['max_num'] = (int) ($row['max_num'] ?? 0);
        $row['step_num'] = (int) ($row['step_num'] ?? 1);
        $row['price_cost'] = (int) ($row['price_cost'] ?? 0);
        $row['price_cost_delayed'] = $row['price_cost_delayed'] === null ? null : (int) $row['price_cost_delayed'];
        $row['allow_frontend'] = (int) ($row['allow_frontend'] ?? 1);
        $row['allow_api'] = (int) ($row['allow_api'] ?? 1);
        $row['enabled'] = (int) ($row['enabled'] ?? 1);
        $row['steps'] = json_array($row['steps_json'] ?? '[]');
        $row['input'] = json_array($row['input_json'] ?? '[]');
        $row['desc'] = json_array($row['desc_json'] ?? '[]');
        $row['payload'] = json_array($row['payload_json'] ?? '{}');
        return $row;
    }
}
