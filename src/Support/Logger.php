<?php
declare(strict_types=1);

namespace XiaoMiSlop\Support;

use XiaoMiSlop\Core\Database;

final class Logger
{
    public static function write(string $level, string $channel, string $message, array $context = [], ?int $userId = null): void
    {
        $line = sprintf('[%s] %s.%s %s %s', now(), strtoupper($level), $channel, $message, json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        @file_put_contents(storage_path('logs/app.log'), $line . PHP_EOL, FILE_APPEND);

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare('INSERT INTO system_logs (`level`, channel, message, context_json, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$level, $channel, $message, json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $userId, now()]);
        } catch (\Throwable) {
        }
    }
}
