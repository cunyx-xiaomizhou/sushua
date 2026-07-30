<?php
declare(strict_types=1);

namespace Sushua\Services;

use RuntimeException;

final class SmtpMailer
{
    public function send(array $config, string $to, string $subject, string $html): array
    {
        if (empty($config['host']) || empty($config['username']) || empty($config['password'])) {
            throw new RuntimeException('SMTP 配置不完整');
        }

        $encryption = strtolower(trim((string) ($config['encryption'] ?? '')));
        if ($encryption === '') {
            if (!empty($config['ssl'])) {
                $encryption = 'ssl';
            } elseif (!empty($config['tls'])) {
                $encryption = 'tls';
            } else {
                $encryption = 'none';
            }
        }
        if (!in_array($encryption, ['ssl', 'tls', 'none'], true)) {
            $encryption = 'none';
        }

        $defaultPort = match ($encryption) {
            'ssl' => 465,
            'tls' => 587,
            default => 25,
        };
        $port = (int) ($config['port'] ?? 0);
        if ($port <= 0) {
            $port = $defaultPort;
        }
        $scheme = $encryption === 'ssl' ? 'ssl://' : '';

        $fp = fsockopen($scheme . $config['host'], $port, $errno, $errstr, 15);
        if (!$fp) {
            throw new RuntimeException('SMTP连接失败：' . $errstr);
        }
        stream_set_timeout($fp, 15);

        $read = static function ($fp): string {
            $data = '';
            while (!feof($fp)) {
                $line = fgets($fp, 515);
                if ($line === false) {
                    break;
                }
                $data .= $line;
                if (preg_match('/^[0-9]{3} /', $line)) {
                    break;
                }
            }
            return $data;
        };
        $write = static function ($fp, string $cmd) use ($read): string {
            fwrite($fp, $cmd . "\r\n");
            return $read($fp);
        };

        $read($fp);
        $write($fp, 'EHLO localhost');
        if ($encryption === 'tls') {
            $tlsResponse = $write($fp, 'STARTTLS');
            if (!str_starts_with(trim($tlsResponse), '220')) {
                throw new RuntimeException('SMTP STARTTLS 失败：' . trim($tlsResponse));
            }
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('SMTP TLS 握手失败');
            }
            $write($fp, 'EHLO localhost');
        }

        $write($fp, 'AUTH LOGIN');
        $write($fp, base64_encode((string) $config['username']));
        $write($fp, base64_encode((string) $config['password']));

        $from = trim((string) ($config['from'] ?? $config['from_email'] ?? $config['username']));
        $fromName = (string) ($config['from_name'] ?? 'Sushua');
        $write($fp, 'MAIL FROM:<' . $from . '>');
        $write($fp, 'RCPT TO:<' . $to . '>');
        $write($fp, 'DATA');

        $headers = [
            'From: ' . sprintf('=?UTF-8?B?%s?= <%s>', base64_encode($fromName), $from),
            'To: <' . $to . '>',
            'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
        ];
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $html . "\r\n.";
        fwrite($fp, $message . "\r\n");
        $result = $read($fp);
        $write($fp, 'QUIT');
        fclose($fp);

        return ['success' => str_starts_with(trim($result), '250'), 'message' => trim($result)];
    }
}
