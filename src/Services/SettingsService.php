<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use XiaoMiSlop\Core\Database;

final class SettingsService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function defaults(): array
    {
        return [
            'site_name' => '小米速刷系统',
            'site_keywords' => '速刷,对接,短信,充值',
            'site_description' => '支持上游对接加价售卖的现代化速刷系统',
            'site_favicon' => '',
            'site_logo' => '',
            'currency_name' => '额度',
            'admin_path' => '/admin',
            'frontend_order_enabled' => '1',
            'api_order_enabled' => '1',
            'feed_image_mode' => 'self_proxy',
            'register_need_email' => '0',
            'register_need_mobile' => '0',
            'register_need_image_captcha' => '1',
            'register_need_geetest' => '0',
            'register_need_sms_code' => '0',
            'register_need_email_code' => '0',
            'login_need_sms' => '0',
            'login_need_email' => '0',
            'login_need_geetest' => '0',
            'login_need_image_captcha' => '0',
            'default_register_strategy_user' => '1',
            'default_register_strategy_agent' => '0',
            'api_condition_mode' => 'total_consume',
            'api_condition_operator' => '>=',
            'api_condition_value' => '0',
            'invite_valid_mode' => 'total_consume',
            'invite_valid_value' => '100000',
            'invite_code_price_rules' => json_encode(['fixed' => 0], JSON_UNESCAPED_UNICODE),
            'seo_footer' => '',
            'sms_provider' => 'custom_http',
            'sms_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'smtp_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'geetest_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'balance_downgrade_enabled' => '0',
        ];
    }

    public function seedDefaults(): void
    {
        $existing = $this->all();
        $payload = [];
        foreach ($this->defaults() as $key => $value) {
            if (!array_key_exists($key, $existing)) {
                $payload[$key] = $value;
            }
        }
        if ($payload) {
            $this->setMany($payload);
        }
    }

    public function all(): array
    {
        $rows = $this->pdo->query('SELECT `key`, `value` FROM settings')->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['key']] = $row['value'];
        }
        return array_replace($this->defaults(), $map);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->pdo->prepare('SELECT `value` FROM settings WHERE `key` = ? LIMIT 1');
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        if ($value === false) {
            $defaults = $this->defaults();
            return $defaults[$key] ?? $default;
        }
        return $value;
    }

    public function getJson(string $key, array $default = []): array
    {
        return json_array((string) $this->get($key, ''), $default);
    }

    public function set(string $key, string $value): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = VALUES(updated_at)');
        $stmt->execute([$key, $value, now(), now()]);
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $this->set((string) $key, is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }
}
