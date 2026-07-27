<?php
declare(strict_types=1);
namespace XiaoMiSlop\Services\Sms;
final class TencentCloudProvider implements ProviderInterface
{
    public function send(string $mobile, string $signName, string $templateCode, array $params, array $config): array
    {
        return ['success' => false, 'message' => '已预留腾讯云短信适配器结构，请按官方 SDK/签名规范补全签名发送逻辑或改用自定义 HTTP 网关。', 'provider' => 'tencent'];
    }
}
