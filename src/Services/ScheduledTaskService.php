<?php
declare(strict_types=1);

namespace Sushua\Services;

use RuntimeException;
use Sushua\Support\Logger;

final class ScheduledTaskService
{
    private const KEY_SETTING = 'scheduled_task_api_key';
    private const KEY_LENGTH = 64;

    private SettingsService $settings;

    public function __construct()
    {
        $this->settings = new SettingsService();
    }

    public function key(): string
    {
        $key = trim((string) $this->settings->get(self::KEY_SETTING, ''));
        if (strlen($key) >= 32) {
            return $key;
        }

        $key = str_random(self::KEY_LENGTH);
        $this->settings->set(self::KEY_SETTING, $key);
        return $key;
    }

    public function resetKey(array $admin): string
    {
        $key = str_random(self::KEY_LENGTH);
        $this->settings->set(self::KEY_SETTING, $key);
        Logger::write('warning', 'scheduled_task', '管理员重置定时任务系统密钥', [
            'actor_id' => (int) ($admin['id'] ?? 0),
        ], (int) ($admin['id'] ?? 0) ?: null);
        return $key;
    }

    public function verify(string $providedKey): bool
    {
        $providedKey = trim($providedKey);
        $storedKey = trim((string) $this->settings->get(self::KEY_SETTING, ''));
        return $providedKey !== '' && strlen($storedKey) >= 32 && hash_equals($storedKey, $providedKey);
    }

    public function run(string $task, string $ip = ''): array
    {
        $startedAt = microtime(true);
        try {
            $result = match ($task) {
                'products' => (new ProductService())->syncFromUpstream(),
                'orders' => (new OrderService())->syncPendingOrders(),
                default => throw new RuntimeException('定时任务不存在'),
            };
            Logger::write('info', 'scheduled_task', '外部定时任务执行成功', [
                'task' => $task,
                'ip' => $ip,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'count' => (int) ($result['count'] ?? 0),
            ]);
            return [
                'task' => $task,
                'result' => $result,
                'executed_at' => now(),
            ];
        } catch (\Throwable $e) {
            Logger::write('error', 'scheduled_task', '外部定时任务执行失败', [
                'task' => $task,
                'ip' => $ip,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
