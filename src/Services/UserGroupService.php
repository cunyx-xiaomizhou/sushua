<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;

final class UserGroupService
{
    private PDO $pdo;
    private static bool $schemaReady = false;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->ensureSchema();
    }

    public function all(bool $includeProductPrices = false): array
    {
        $rows = $this->pdo->query('SELECT * FROM user_groups ORDER BY sort_order ASC, id ASC')->fetchAll();
        $groups = array_map([$this, 'normalize'], $rows);
        if (!$includeProductPrices || !$groups) return $groups;

        $index = [];
        foreach ($groups as $position => $group) {
            $groups[$position]['product_prices'] = [];
            $index[(int) $group['id']] = $position;
        }
        $prices = $this->pdo->query('SELECT user_group_id, product_id, fixed_price FROM user_group_product_prices ORDER BY product_id ASC')->fetchAll();
        foreach ($prices as $price) {
            $groupId = (int) ($price['user_group_id'] ?? 0);
            if (!array_key_exists($groupId, $index)) continue;
            $groups[$index[$groupId]]['product_prices'][(int) $price['product_id']] = (int) $price['fixed_price'];
        }
        return $groups;
    }

    public function save(array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        $payload = [
            'group_code' => trim((string) ($data['group_code'] ?? 'group_' . str_random(6))),
            'name' => trim((string) ($data['name'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'threshold_mode' => trim((string) ($data['threshold_mode'] ?? 'none')),
            'threshold_value' => (int) ($data['threshold_value'] ?? 0),
            'downgrade_on_balance' => !empty($data['downgrade_on_balance']) ? 1 : 0,
            'markup_mode' => trim((string) ($data['markup_mode'] ?? 'fixed')),
            'markup_value' => (float) ($data['markup_value'] ?? 0),
            'recharge_bonus_rate' => max(0.01, (float) ($data['recharge_bonus_rate'] ?? 1)),
            'allow_api_default' => !empty($data['allow_api_default']) ? 1 : 0,
            'is_default_register' => !empty($data['is_default_register']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
        if ($payload['name'] === '') {
            throw new RuntimeException('用户组名称不能为空');
        }
        if (!in_array($payload['markup_mode'], ['fixed', 'percent'], true)) {
            throw new RuntimeException('用户组加价模式不正确');
        }

        $hasProductPrices = array_key_exists('product_prices', $data);
        $productPrices = $hasProductPrices ? $this->normalizeProductPrices($data['product_prices']) : [];

        $this->pdo->beginTransaction();
        try {
            if ($payload['is_default_register'] === 1) {
                $this->pdo->exec('UPDATE user_groups SET is_default_register = 0');
            }

            if ($id > 0) {
                $stmt = $this->pdo->prepare('UPDATE user_groups SET group_code=?, name=?, description=?, threshold_mode=?, threshold_value=?, downgrade_on_balance=?, markup_mode=?, markup_value=?, recharge_bonus_rate=?, allow_api_default=?, is_default_register=?, sort_order=?, updated_at=? WHERE id=?');
                $stmt->execute([
                    $payload['group_code'], $payload['name'], $payload['description'], $payload['threshold_mode'], $payload['threshold_value'], $payload['downgrade_on_balance'],
                    $payload['markup_mode'], $payload['markup_value'], $payload['recharge_bonus_rate'], $payload['allow_api_default'], $payload['is_default_register'], $payload['sort_order'], now(), $id,
                ]);
                if ($stmt->rowCount() === 0) {
                    $exists = $this->pdo->prepare('SELECT id FROM user_groups WHERE id = ? LIMIT 1');
                    $exists->execute([$id]);
                    if (!$exists->fetchColumn()) throw new RuntimeException('用户组不存在');
                }
            } else {
                $stmt = $this->pdo->prepare('INSERT INTO user_groups (group_code, name, description, threshold_mode, threshold_value, downgrade_on_balance, markup_mode, markup_value, recharge_bonus_rate, allow_api_default, is_default_register, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $payload['group_code'], $payload['name'], $payload['description'], $payload['threshold_mode'], $payload['threshold_value'], $payload['downgrade_on_balance'],
                    $payload['markup_mode'], $payload['markup_value'], $payload['recharge_bonus_rate'], $payload['allow_api_default'], $payload['is_default_register'], $payload['sort_order'], now(), now(),
                ]);
                $id = (int) $this->pdo->lastInsertId();
            }

            if ($hasProductPrices) {
                $this->pdo->prepare('DELETE FROM user_group_product_prices WHERE user_group_id = ?')->execute([$id]);
                $insert = $this->pdo->prepare('INSERT INTO user_group_product_prices (user_group_id, product_id, fixed_price, created_at, updated_at) VALUES (?, ?, ?, ?, ?)');
                foreach ($productPrices as $productId => $fixedPrice) {
                    $insert->execute([$id, $productId, $fixedPrice, now(), now()]);
                }
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            throw $e;
        }

        foreach ($this->all(true) as $group) {
            if ((int) $group['id'] === $id) return $group;
        }
        throw new RuntimeException('用户组不存在');
    }

    public function setDefault(int $groupId): void
    {
        $this->pdo->exec('UPDATE user_groups SET is_default_register = 0');
        $this->pdo->prepare('UPDATE user_groups SET is_default_register = 1, updated_at = ? WHERE id = ?')->execute([now(), $groupId]);
    }

    public function evaluateAndUpdate(int $userId): void
    {
        $userStmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();
        if (!$user) return;

        $groups = $this->all();
        if (!$groups) return;
        $current = null;
        foreach ($groups as $group) {
            if ((int) $group['id'] === (int) $user['user_group_id']) { $current = $group; break; }
        }
        $matched = array_values(array_filter($groups, fn (array $group): bool => $this->matches($group, $user)));
        if (!$matched) return;
        usort($matched, static function (array $a, array $b): int {
            $sort = (int) ($b['sort_order'] ?? 0) <=> (int) ($a['sort_order'] ?? 0);
            if ($sort !== 0) return $sort;
            $threshold = (int) ($b['threshold_value'] ?? 0) <=> (int) ($a['threshold_value'] ?? 0);
            return $threshold !== 0 ? $threshold : ((int) $b['id'] <=> (int) $a['id']);
        });
        $best = $matched[0];
        $currentId = (int) ($current['id'] ?? 0);
        $bestId = (int) $best['id'];
        if ($currentId <= 0) {
            $this->pdo->prepare('UPDATE users SET user_group_id = ?, updated_at = ? WHERE id = ?')->execute([$bestId, now(), $userId]);
            return;
        }
        if ($bestId === $currentId) return;

        $currentRank = (int) ($current['sort_order'] ?? 0);
        $bestRank = (int) ($best['sort_order'] ?? 0);
        $isUpgrade = $bestRank > $currentRank || ($bestRank === $currentRank && (int) $best['threshold_value'] >= (int) ($current['threshold_value'] ?? 0));
        $allowDowngrade = (string) (new SettingsService())->get('balance_downgrade_enabled', '0') === '1'
            || ((string) ($current['threshold_mode'] ?? '') === 'balance' && (int) ($current['downgrade_on_balance'] ?? 0) === 1);
        if ($isUpgrade || $allowDowngrade) {
            $this->pdo->prepare('UPDATE users SET user_group_id = ?, updated_at = ? WHERE id = ?')->execute([$bestId, now(), $userId]);
        }
    }

    private function ensureSchema(): void
    {
        if (self::$schemaReady) return;
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS user_group_product_prices (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_group_id INT UNSIGNED NOT NULL,
            product_id INT UNSIGNED NOT NULL,
            fixed_price BIGINT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_group_product_price (user_group_id, product_id),
            KEY idx_group_product_prices_product (product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        self::$schemaReady = true;
    }

    private function normalizeProductPrices(mixed $input): array
    {
        if (!is_array($input)) throw new RuntimeException('商品固定价格格式不正确');
        $prices = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $productId = (int) ($value['product_id'] ?? 0);
                $fixedPrice = $value['fixed_price'] ?? null;
            } else {
                $productId = (int) $key;
                $fixedPrice = $value;
            }
            if ($fixedPrice === null || $fixedPrice === '') continue;
            $validated = filter_var($fixedPrice, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
            if ($productId <= 0 || $validated === false) {
                throw new RuntimeException('商品固定价格必须是大于等于 0 的整数额度');
            }
            $prices[$productId] = (int) $validated;
        }
        if (!$prices) return [];

        $ids = array_keys($prices);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare('SELECT id FROM products WHERE id IN (' . $placeholders . ')');
        $stmt->execute($ids);
        $existing = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
        foreach ($ids as $productId) {
            if (!in_array((int) $productId, $existing, true)) {
                throw new RuntimeException('商品不存在，无法设置用户组固定价格');
            }
        }
        return $prices;
    }

    private function matches(array $group, array $user): bool
    {
        $mode = $group['threshold_mode'] ?? 'none';
        $value = (int) ($group['threshold_value'] ?? 0);
        return match ($mode) {
            'total_recharge' => (int) $user['total_recharge'] >= $value,
            'total_consume' => (int) $user['total_consume'] >= $value,
            'invite_count' => (int) $user['invite_count'] >= $value,
            'balance' => (int) $user['balance'] >= $value,
            default => true,
        };
    }

    private function normalize(array $row): array
    {
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['threshold_value'] = (int) ($row['threshold_value'] ?? 0);
        $row['markup_value'] = (float) ($row['markup_value'] ?? 0);
        $row['recharge_bonus_rate'] = (float) ($row['recharge_bonus_rate'] ?? 1);
        $row['allow_api_default'] = (int) ($row['allow_api_default'] ?? 0);
        $row['is_default_register'] = (int) ($row['is_default_register'] ?? 0);
        $row['sort_order'] = (int) ($row['sort_order'] ?? 0);
        return $row;
    }
}
