<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

final class BalanceService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function adjust(int $userId, int $amount, string $type, string $remark = '', ?string $relatedType = null, ?string $relatedId = null): array
    {
        $stmt = $this->pdo->prepare('SELECT id, balance, total_recharge, total_consume FROM users WHERE id = ? LIMIT 1 FOR UPDATE');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            throw new RuntimeException('用户不存在');
        }
        $before = (int) $user['balance'];
        $after = $before + $amount;
        if ($after < 0) {
            throw new RuntimeException('余额不足');
        }

        $fields = ['balance = ?', 'updated_at = ?'];
        $params = [$after, now()];
        if (in_array($type, ['recharge', 'card_recharge'], true) && $amount > 0) {
            $fields[] = 'total_recharge = total_recharge + ?';
            $params[] = $amount;
        }
        if (in_array($type, ['order_consume'], true) && $amount < 0) {
            $fields[] = 'total_consume = total_consume + ?';
            $params[] = abs($amount);
        }
        $params[] = $userId;
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $this->pdo->prepare($sql)->execute($params);
        $this->pdo->prepare('INSERT INTO balance_ledger (user_id, change_type, amount, balance_before, balance_after, related_type, related_id, remark, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([$userId, $type, $amount, $before, $after, $relatedType, $relatedId, $remark, now()]);

        return ['before' => $before, 'after' => $after, 'amount' => $amount];
    }
}
