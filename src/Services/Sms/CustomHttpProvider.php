<?php
declare(strict_types=1);
namespace XiaoMiSlop\Services\Sms;
use RuntimeException;
final class CustomHttpProvider implements ProviderInterface
{
    public function send(string $mobile, string $signName, string $templateCode, array $params, array $config): array
    {
        if (empty($config['endpoint'])) throw new RuntimeException('自定义短信接口 endpoint 未配置');
        $payload = ['mobile' => $mobile, 'sign_name' => $signName, 'template_code' => $templateCode, 'params' => $params, 'token' => $config['token'] ?? ''];
        $ch = curl_init((string) $config['endpoint']);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), CURLOPT_HTTPHEADER => ['Content-Type: application/json'], CURLOPT_TIMEOUT => 15]);
        $body = curl_exec($ch); if ($body === false) throw new RuntimeException('自定义短信接口调用失败：' . curl_error($ch)); curl_close($ch);
        $json = json_decode((string) $body, true); return is_array($json) ? $json : ['success' => true, 'raw' => $body];
    }
}
