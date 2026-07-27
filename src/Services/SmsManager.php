<?php
declare(strict_types=1);
namespace XiaoMiSlop\Services;
use PDO; use RuntimeException; use XiaoMiSlop\Core\Database; use XiaoMiSlop\Services\Sms\AliyunProvider; use XiaoMiSlop\Services\Sms\CustomHttpProvider; use XiaoMiSlop\Services\Sms\ProviderInterface; use XiaoMiSlop\Services\Sms\TencentCloudProvider;
final class SmsManager
{
    private PDO $pdo; public function __construct() { $this->pdo = Database::connection(); }
    public function send(string $mobile, string $templateCode, array $params, array $override = []): array
    {
        $settings = new SettingsService(); $providerName = $override['provider'] ?? $settings->get('sms_provider', 'custom_http'); $config = $override ?: $settings->getJson('sms_config', []);
        $provider = $this->provider((string) $providerName); $result = $provider->send($mobile, (string) ($config['sign_name'] ?? '系统通知'), $templateCode, $params, $config);
        $this->pdo->prepare('INSERT INTO sms_logs (target, provider, template_code, payload_json, result_text, created_at) VALUES (?, ?, ?, ?, ?, ?)')->execute([$mobile, $providerName, $templateCode, json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), now()]);
        return $result;
    }
    private function provider(string $name): ProviderInterface
    {
        return match ($name) { 'tencent' => new TencentCloudProvider(), 'aliyun' => new AliyunProvider(), 'custom_http' => new CustomHttpProvider(), default => throw new RuntimeException('未知短信渠道：' . $name), };
    }
}
