<?php
declare(strict_types=1);
namespace XiaoMiSlop\Services\Sms;
interface ProviderInterface { public function send(string $mobile, string $signName, string $templateCode, array $params, array $config): array; }
