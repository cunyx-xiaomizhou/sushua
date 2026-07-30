<?php
declare(strict_types=1);
namespace Sushua\Services\Sms;
final class AliyunProvider implements ProviderInterface
{
    public function send(string $mobile, string $signName, string $templateCode, array $params, array $config): array
    {
        return ['success' => false, 'message' => '已预留阿里云短信适配器结构，请按官方 SDK/签名规范补全发送逻辑或改用自定义 HTTP 网关。', 'provider' => 'aliyun'];
    }
}
