<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

final class ProductExchangeCodeService
{
    private PDO $pdo;
    private SettingsService $settings;
    private ProductService $products;
    private PricingService $pricing;
    private OrderService $orders;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->settings = new SettingsService();
        $this->products = new ProductService();
        $this->pricing = new PricingService();
        $this->orders = new OrderService();
        $this->ensureTables();
    }

    public function settingsSummary(): array
    {
        $format = trim((string) $this->settings->get('exchange_code_format', '{prefix}{uid}{random}'));
        $prefix = trim((string) $this->settings->get('exchange_code_prefix', 'XM'));
        $randomLength = min(256, max(8, (int) $this->settings->get('exchange_code_random_length', '36')));
        $generationFee = max(0, (int) $this->settings->get('exchange_code_generation_fee', '0'));
        $cookieDays = min(3650, max(7, (int) $this->settings->get('exchange_code_cookie_days', '60')));

        return [
            'enabled' => (int) $this->settings->get('exchange_code_enabled', '1') === 1,
            'generation_fee' => $generationFee,
            'prefix' => $prefix,
            'random_length' => $randomLength,
            'format' => $format !== '' ? $format : '{prefix}{uid}{random}',
            'cookie_days' => $cookieDays,
            'format_help' => '支持占位符：{prefix} 系统前缀、{random} 随机字符串、{uid} 用户UID。最终兑换码长度会自动补齐到 48 位以上。',
        ];
    }

    public function create(array $user, array $data): array
    {
        $settings = $this->settingsSummary();
        if (!$settings['enabled']) {
            throw new RuntimeException('商品兑换码功能当前已关闭');
        }

        $product = $this->resolveProduct($data);
        $quantity = (int) ($data['quantity'] ?? $data['num'] ?? 0);
        if ($quantity < (int) $product['min_num'] || $quantity > (int) $product['max_num']) {
            throw new RuntimeException('兑换码数量不在商品允许范围内');
        }
        if ($quantity % max(1, (int) $product['step_num']) !== 0) {
            throw new RuntimeException('兑换码数量必须为商品步长整数倍');
        }

        $group = $this->findGroup((int) $user['user_group_id']);
        $pricing = $this->pricing->calculate($product, $group, $quantity, false);
        $code = $this->buildUniqueCode($user);
        $payload = [
            'product_input' => (array) ($product['input'] ?? []),
            'product_desc' => (array) ($product['desc'] ?? []),
            'generated_by' => [
                'user_id' => (int) ($user['id'] ?? 0),
                'uid' => (int) ($user['uid'] ?? 0),
                'username' => (string) ($user['username'] ?? ''),
                'nickname' => (string) ($user['nickname'] ?? ''),
            ],
        ];

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('INSERT INTO product_exchange_codes (code, creator_user_id, creator_uid_snapshot, creator_name_snapshot, product_id, product_sign_snapshot, product_name_snapshot, quantity, step_num_snapshot, price_snapshot, generation_fee, status, extra_json, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $code,
                (int) $user['id'],
                (int) ($user['uid'] ?? 0),
                (string) ($user['nickname'] ?: ($user['username'] ?? '')),
                (int) $product['id'],
                (string) $product['upstream_sign'],
                (string) $product['name'],
                $quantity,
                (int) $product['step_num'],
                (int) $pricing['sell_price'],
                (int) $settings['generation_fee'],
                'unused',
                json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                now(),
                now(),
            ]);
            $id = (int) $this->pdo->lastInsertId();
            if ((int) $settings['generation_fee'] > 0) {
                (new BalanceService())->adjust((int) $user['id'], -(int) $settings['generation_fee'], 'exchange_code_generation_fee', '生成商品兑换码服务费', 'exchange_code', $code);
            }
            $this->log($id, 'create', (int) $user['id'], null, [
                'product_id' => (int) $product['id'],
                'product_name' => (string) $product['name'],
                'quantity' => $quantity,
                'price_snapshot' => (int) $pricing['sell_price'],
                'generation_fee' => (int) $settings['generation_fee'],
            ]);
            $this->pdo->commit();
            return $this->getById($id, false);
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function listForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT ec.*, o.state AS order_state, o.message AS order_message FROM product_exchange_codes ec LEFT JOIN orders o ON o.id = ec.redeemer_order_id WHERE ec.creator_user_id = ? ORDER BY ec.id DESC LIMIT 200');
        $stmt->execute([$userId]);
        return array_map(fn (array $row): array => $this->normalize($row, false), $stmt->fetchAll());
    }

    public function listForAdmin(): array
    {
        $sql = 'SELECT ec.*, cu.username AS creator_username, cu.nickname AS creator_nickname, ru.username AS redeemer_username, ru.nickname AS redeemer_nickname, o.state AS order_state, o.message AS order_message FROM product_exchange_codes ec LEFT JOIN users cu ON cu.id = ec.creator_user_id LEFT JOIN users ru ON ru.id = ec.redeemer_user_id LEFT JOIN orders o ON o.id = ec.redeemer_order_id ORDER BY ec.id DESC LIMIT 400';
        return array_map(fn (array $row): array => $this->normalize($row, true), $this->pdo->query($sql)->fetchAll());
    }

    public function listLogs(): array
    {
        $sql = 'SELECT l.*, ec.code, ec.product_name_snapshot FROM product_exchange_code_logs l LEFT JOIN product_exchange_codes ec ON ec.id = l.exchange_code_id ORDER BY l.id DESC LIMIT 300';
        return $this->pdo->query($sql)->fetchAll();
    }

    public function previewPublic(string $code): array
    {
        $code = trim($code);
        if ($code === '') {
            throw new RuntimeException('请输入兑换码');
        }
        $row = $this->findByCode($code, false);
        if (!$row || (string) ($row['status'] ?? '') !== 'unused') {
            throw new RuntimeException('兑换码不存在或已被使用');
        }
        $product = $this->products->get((int) $row['product_id']);
        return [
            'code' => (string) $row['code'],
            'display_code' => $this->maskCode((string) $row['code']),
            'product_id' => (int) $row['product_id'],
            'product_name' => (string) $row['product_name_snapshot'],
            'quantity' => (int) $row['quantity'],
            'step_num' => (int) $row['step_num_snapshot'],
            'price_snapshot' => (int) $row['price_snapshot'],
            'inputs' => array_values(array_filter((array) ($product['input'] ?? []), static function (mixed $item): bool {
                return is_array($item) && !in_array((string) ($item['name'] ?? ''), ['num', 'sign', 'uid', 'api_key', 'limit'], true);
            })),
            'product_desc' => (array) ($product['desc'] ?? []),
        ];
    }

    public function redeemPublic(array $payload, string $ip, ?array $redeemer = null): array
    {
        $settings = $this->settingsSummary();
        if (!$settings['enabled']) {
            throw new RuntimeException('商品兑换码功能当前已关闭');
        }

        $code = trim((string) ($payload['code'] ?? ''));
        if ($code === '') {
            throw new RuntimeException('请输入兑换码');
        }
        $qq = trim((string) ($payload['qq'] ?? ''));
        if ($qq === '' || !preg_match('/^[1-9][0-9]{4,14}$/', $qq)) {
            throw new RuntimeException('QQ号格式不正确');
        }
        $feedId = trim((string) ($payload['feed_id'] ?? ''));

        $orderNo = '';
        $this->pdo->beginTransaction();
        try {
            $row = $this->findByCode($code, true);
            if (!$row || (string) ($row['status'] ?? '') !== 'unused') {
                throw new RuntimeException('兑换码不存在或已被使用');
            }
            $creator = $this->loadCreator((int) $row['creator_user_id']);
            $product = $this->products->get((int) $row['product_id']);
            $orderPayload = [
                'sign' => (string) $row['product_sign_snapshot'],
                'qq' => $qq,
                'num' => (int) $row['quantity'],
                'feed_id' => $feedId,
            ];
            foreach ((array) ($product['input'] ?? []) as $definition) {
                if (!is_array($definition) || empty($definition['name'])) {
                    continue;
                }
                $name = (string) $definition['name'];
                if (in_array($name, ['sign', 'num', 'uid', 'api_key', 'limit'], true)) {
                    continue;
                }
                if (array_key_exists($name, $payload)) {
                    $orderPayload[$name] = $payload[$name];
                }
            }

            $order = $this->orders->create($creator, $orderPayload, false, (int) $row['price_snapshot']);
            $orderId = (int) ($order['id'] ?? 0);
            $orderNo = (string) ($order['order_no'] ?? $order['display_order_no'] ?? '');
            if ($orderId <= 0 || $orderNo === '') {
                throw new RuntimeException('兑换成功但订单号回写失败，请联系管理员核对');
            }
            $this->pdo->prepare('UPDATE orders SET order_method = ?, updated_at = ? WHERE id = ?')->execute(['exchange_code', now(), $orderId]);
            $this->pdo->prepare('UPDATE product_exchange_codes SET status = ?, redeemer_user_id = ?, redeemer_ip = ?, redeemer_order_id = ?, redeemer_order_no = ?, used_at = ?, updated_at = ? WHERE id = ?')->execute([
                'used',
                $redeemer ? (int) ($redeemer['id'] ?? 0) : null,
                $ip,
                $orderId,
                $orderNo,
                now(),
                now(),
                (int) $row['id'],
            ]);
            $this->log((int) $row['id'], 'redeem', $redeemer ? (int) ($redeemer['id'] ?? 0) : null, $ip, [
                'order_id' => $orderId,
                'order_no' => $orderNo,
                'qq' => $qq,
                'feed_id' => $feedId,
            ]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }

        return $this->orders->getPublicByOrderNo($orderNo);
    }

    public function publicOrders(array $orderNos): array
    {
        $result = [];
        foreach ($orderNos as $orderNo) {
            $orderNo = trim((string) $orderNo);
            if ($orderNo === '') {
                continue;
            }
            try {
                $result[] = $this->orders->getPublicByOrderNo($orderNo);
            } catch (\Throwable) {
            }
        }
        return $result;
    }

    public function getById(int $id, bool $admin = false): array
    {
        $stmt = $this->pdo->prepare('SELECT ec.*, o.state AS order_state, o.message AS order_message FROM product_exchange_codes ec LEFT JOIN orders o ON o.id = ec.redeemer_order_id WHERE ec.id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('兑换码不存在');
        }
        return $this->normalize($row, $admin);
    }

    private function resolveProduct(array $data): array
    {
        if (!empty($data['product_id'])) {
            return $this->products->get((int) $data['product_id']);
        }
        $sign = trim((string) ($data['sign'] ?? ''));
        if ($sign === '') {
            throw new RuntimeException('请选择商品');
        }
        return $this->products->findBySign($sign);
    }

    private function loadCreator(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.id = ? AND u.deleted_at IS NULL AND u.status <> "deleted" LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('兑换码所属用户不存在');
        }
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['uid'] = (int) ($row['uid'] ?? 0);
        $row['user_group_id'] = (int) ($row['user_group_id'] ?? 0);
        $row['strategy_user'] = (int) ($row['strategy_user'] ?? 0);
        $row['strategy_agent'] = (int) ($row['strategy_agent'] ?? 0);
        $row['balance'] = (int) ($row['balance'] ?? 0);
        $row['total_recharge'] = (int) ($row['total_recharge'] ?? 0);
        $row['total_consume'] = (int) ($row['total_consume'] ?? 0);
        $row['invite_count'] = (int) ($row['invite_count'] ?? 0);
        return $row;
    }

    private function findGroup(int $groupId): array
    {
        foreach ((new UserGroupService())->all() as $group) {
            if ((int) $group['id'] === $groupId) {
                return $group;
            }
        }
        throw new RuntimeException('用户组不存在');
    }

    private function findByCode(string $code, bool $forUpdate): ?array
    {
        $sql = 'SELECT * FROM product_exchange_codes WHERE code = ? LIMIT 1' . ($forUpdate ? ' FOR UPDATE' : '');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$code]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private function buildUniqueCode(array $user): string
    {
        for ($i = 0; $i < 8; $i++) {
            $code = $this->renderCodeTemplate($user);
            $stmt = $this->pdo->prepare('SELECT id FROM product_exchange_codes WHERE code = ? LIMIT 1');
            $stmt->execute([$code]);
            if (!$stmt->fetchColumn()) {
                return $code;
            }
        }
        throw new RuntimeException('生成兑换码失败，请稍后重试');
    }

    private function renderCodeTemplate(array $user): string
    {
        $settings = $this->settingsSummary();
        $prefix = (string) $settings['prefix'];
        $randomLength = max(8, (int) $settings['random_length']);
        $format = str_replace(
            ['[系统自定义前缀]', '[随机字符串]', '[随机字符串(系统管理员可设置长度)]', '[用户UID]'],
            ['{prefix}', '{random}', '{random}', '{uid}'],
            (string) $settings['format']
        );
        if ($format === '') {
            $format = '{prefix}{uid}{random}';
        }
        $random = str_random($randomLength);
        $code = strtr($format, [
            '{prefix}' => $prefix,
            '{random}' => $random,
            '{uid}' => (string) ((int) ($user['uid'] ?? 0)),
        ]);
        $code = preg_replace('/\s+/u', '', $code) ?? $code;
        if (strlen($code) < 48) {
            $code .= str_random(48 - strlen($code));
        }
        return $code;
    }

    private function normalize(array $row, bool $admin = false): array
    {
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['creator_user_id'] = (int) ($row['creator_user_id'] ?? 0);
        $row['creator_uid_snapshot'] = (int) ($row['creator_uid_snapshot'] ?? 0);
        $row['product_id'] = (int) ($row['product_id'] ?? 0);
        $row['quantity'] = (int) ($row['quantity'] ?? 0);
        $row['step_num_snapshot'] = (int) ($row['step_num_snapshot'] ?? 1);
        $row['price_snapshot'] = (int) ($row['price_snapshot'] ?? 0);
        $row['generation_fee'] = (int) ($row['generation_fee'] ?? 0);
        $row['redeemer_user_id'] = $row['redeemer_user_id'] === null ? null : (int) $row['redeemer_user_id'];
        $row['redeemer_order_id'] = $row['redeemer_order_id'] === null ? null : (int) $row['redeemer_order_id'];
        $row['extra'] = json_array($row['extra_json'] ?? '[]');
        $row['display_code'] = $admin ? (string) ($row['code'] ?? '') : $this->maskCode((string) ($row['code'] ?? ''));
        return $row;
    }

    private function maskCode(string $code): string
    {
        if (strlen($code) <= 12) {
            return $code;
        }
        return substr($code, 0, 8) . str_repeat('*', max(4, strlen($code) - 12)) . substr($code, -4);
    }

    private function log(int $codeId, string $action, ?int $operatorUserId, ?string $ip, array $context): void
    {
        $this->pdo->prepare('INSERT INTO product_exchange_code_logs (exchange_code_id, action, operator_user_id, ip, context_json, created_at) VALUES (?, ?, ?, ?, ?, ?)')->execute([
            $codeId,
            $action,
            $operatorUserId,
            $ip,
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            now(),
        ]);
    }

    private function ensureTables(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS product_exchange_codes (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            code VARCHAR(160) NOT NULL,
            creator_user_id INT UNSIGNED NOT NULL,
            creator_uid_snapshot BIGINT UNSIGNED NOT NULL,
            creator_name_snapshot VARCHAR(120) NULL,
            product_id INT UNSIGNED NOT NULL,
            product_sign_snapshot VARCHAR(120) NOT NULL,
            product_name_snapshot VARCHAR(160) NOT NULL,
            quantity INT NOT NULL,
            step_num_snapshot INT NOT NULL DEFAULT 1,
            price_snapshot BIGINT NOT NULL DEFAULT 0,
            generation_fee BIGINT NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'unused',
            redeemer_user_id INT UNSIGNED NULL,
            redeemer_ip VARCHAR(45) NULL,
            redeemer_order_id INT UNSIGNED NULL,
            redeemer_order_no VARCHAR(40) NULL,
            extra_json LONGTEXT NULL,
            used_at DATETIME NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_product_exchange_code (code),
            KEY idx_exchange_creator (creator_user_id),
            KEY idx_exchange_order (redeemer_order_id),
            KEY idx_exchange_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS product_exchange_code_logs (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            exchange_code_id INT UNSIGNED NOT NULL,
            action VARCHAR(40) NOT NULL,
            operator_user_id INT UNSIGNED NULL,
            ip VARCHAR(45) NULL,
            context_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_exchange_log_code (exchange_code_id),
            KEY idx_exchange_log_action (action)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}
