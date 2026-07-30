<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;

final class NotificationService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function sendCode(string $channel, string $target, string $purpose = 'auth'): array
    {
        $code = (string) random_int(100000, 999999);
        $expires = date('Y-m-d H:i:s', time() + 300);
        $this->pdo->prepare('INSERT INTO verify_codes (target, channel, purpose, code, expires_at, created_at) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$target, $channel, $purpose, $code, $expires, now()]);

        if ($channel === 'email') {
            $config = (new SettingsService())->getJson('smtp_config', []);
            $result = (new SmtpMailer())->send($config, $target, '验证码', '<p>您的验证码是：<strong>' . $code . '</strong>，5分钟内有效。</p>');
            $this->pdo->prepare('INSERT INTO email_logs (target, subject, payload_json, result_text, created_at) VALUES (?, ?, ?, ?, ?)')
                ->execute([$target, '验证码', json_encode(['code' => $code]), json_encode($result, JSON_UNESCAPED_UNICODE), now()]);
            return $result;
        }
        if ($channel === 'sms') {
            return (new SmsManager())->send($target, 'LOGIN_CODE', ['code' => $code]);
        }
        throw new RuntimeException('不支持的验证码通道');
    }

    public function verify(string $channel, string $target, string $purpose, string $code): bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM verify_codes WHERE target = ? AND channel = ? AND purpose = ? AND code = ? AND used_at IS NULL ORDER BY id DESC LIMIT 1');
        $stmt->execute([$target, $channel, $purpose, $code]);
        $row = $stmt->fetch();
        if (!$row || strtotime((string) $row['expires_at']) < time()) {
            return false;
        }
        $this->pdo->prepare('UPDATE verify_codes SET used_at = ? WHERE id = ?')->execute([now(), $row['id']]);
        return true;
    }
}
