<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use Sushua\Core\Database;

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
            'site_name' => '粥粥速刷系统',
            'site_keywords' => '速刷,对接,短信,充值',
            'site_description' => '支持上游对接加价售卖的现代化速刷系统',
            'site_favicon' => '',
            'site_logo' => '',
            'currency_name' => '额度',
            'admin_path' => '/admin',
            'community_group_qq' => '',
            'support_group_qq' => '',
            'icp_beian_no' => '',
            'public_security_beian_no' => '',
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
            'default_register_strategy_user' => '0',
            'default_register_strategy_agent' => '0',
            'api_condition_mode' => 'total_recharge',
            'api_condition_operator' => '>=',
            'api_condition_value' => '0',
            'invite_valid_mode' => 'total_consume',
            'invite_valid_value' => '100000',
            'invite_code_price_rules' => json_encode(['mode' => 'fixed', 'fixed' => 0, 'length_rules' => []], JSON_UNESCAPED_UNICODE),
            'seo_footer' => '',
            'custom_css' => '',
            'custom_js' => '',
            'custom_resource_urls' => json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'sms_provider' => 'custom_http',
            'sms_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'smtp_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'geetest_config' => json_encode([], JSON_UNESCAPED_UNICODE),
            'theme_config' => json_encode([
                'bg' => '#f3f7fc',
                'surface' => '#ffffff',
                'surface_soft' => '#f7fbff',
                'text' => '#172b4d',
                'muted' => '#6f7f95',
                'line' => '#d9e4f2',
                'primary' => '#1f6feb',
                'success' => '#169b62',
                'warning' => '#d78a18',
                'danger' => '#dc4c64',
                'header_bg' => 'rgba(255,255,255,0.88)',
                'header_border' => '#d9e4f2',
                'logo_text' => '#ffffff',
                'avatar_bg' => '#eef2ff',
                'captcha_bg' => '#eef2ff',
                'captcha_line' => '#8aa4d6',
                'captcha_text' => '#1f3f78',
                'button_default_bg' => '#edf1f8',
                'button_default_text' => '#334056',
                'button_primary_text' => '#ffffff',
                'button_success_bg' => '#e8f8f0',
                'button_success_text' => '#12794c',
                'button_warning_bg' => '#fff3de',
                'button_warning_text' => '#98610f',
                'button_danger_bg' => '#ffedf2',
                'button_danger_text' => '#c23c5a',
                'input_bg' => '#ffffff',
                'input_border' => '#dce4ef',
                'input_focus_ring' => 'rgba(31,111,235,0.16)',
                'sidebar_bg' => '#ffffff',
                'sidebar_border' => '#d9e4f2',
                'sidebar_title_text' => '#536177',
                'nav_text' => '#4a5870',
                'nav_active_bg' => '#edf4ff',
                'nav_active_text' => '#1f6feb',
                'nav_hover_bg' => '#f6f9fd',
                'badge_info_bg' => '#edf4ff',
                'badge_info_text' => '#1f6feb',
                'badge_success_bg' => '#e8f8f0',
                'badge_success_text' => '#12794c',
                'badge_warning_bg' => '#fff3de',
                'badge_warning_text' => '#98610f',
                'badge_danger_bg' => '#ffedf2',
                'badge_danger_text' => '#c23c5a',
                'table_head_bg' => '#f6f9fc',
                'table_head_text' => '#536177',
                'table_bg' => '#ffffff',
                'desc_bg' => '#f8faff',
                'qr_bg' => '#ffffff',
                'qr_border' => '#d8dff0',
                'tip_bg' => '#fff8e8',
                'tip_border' => '#f2ddb0',
                'tip_text' => '#8a5b0d',
                'code_item_bg' => '#fbfcff',
                'subtle_bg' => '#f8faff',
                'editor_bg' => '#fbfcff',
                'admin_note_bg' => '#eff6ff',
                'admin_note_border' => '#d5e5ff',
                'admin_note_text' => '#355481',
                'modal_bg' => '#ffffff',
                'overlay_bg' => 'rgba(16,20,31,0.54)',
                'loading_bg' => 'rgba(244,247,251,0.72)',
                'loading_card_bg' => '#ffffff',
                'spinner_track' => '#d6ddff',
                'toast_info' => '#4856e9',
                'toast_success' => '#1a9d67',
                'toast_warning' => '#d68e1d',
                'toast_danger' => '#df4d6c',
                'mono_bg' => '#0f172a',
                'mono_text' => '#d7e0ef',
                'shadow_color' => 'rgba(34,48,88,0.08)',
            ], JSON_UNESCAPED_UNICODE),
            'exchange_code_enabled' => '1',
            'exchange_code_generation_fee' => '0',
            'exchange_code_prefix' => 'XM',
            'exchange_code_random_length' => '36',
            'exchange_code_format' => '{prefix}{uid}{random}',
            'exchange_code_cookie_days' => '60',
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
