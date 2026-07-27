<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

final class InviteService
{
    private PDO $pdo;
    private SettingsService $settings;
    public function __construct() { $this->pdo = Database::connection(); $this->settings = new SettingsService(); }

    public function ensureDefaultCode(int $userId): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM invite_codes WHERE user_id = ? AND is_default = 1 LIMIT 1');
        $stmt->execute([$userId]);
        if (!$stmt->fetchColumn()) $this->create($userId, 20, null, true);
    }

    public function create(int $userId, int $length, ?string $customCode = null, bool $isDefault = false): array
    {
        if ($customCode !== null) {
            $customCode = strtoupper(trim($customCode));
            if (!preg_match('/^[A-Z0-9]{6,48}$/', $customCode)) throw new RuntimeException('自定义邀请码必须是6~48位英文数字');
            $code = $customCode; $length = strlen($customCode);
        } else {
            $length = max(6, min(48, $length));
            $code = $this->uniqueCode($length);
        }
        $exists = $this->pdo->prepare('SELECT 1 FROM invite_codes WHERE code = ? LIMIT 1');
        $exists->execute([$code]);
        if ($exists->fetchColumn()) throw new RuntimeException('邀请码已存在，请更换内容');
        $price = $isDefault ? 0 : $this->priceForLength($length);
        if (!$isDefault && $price > 0) {
            $pdo = $this->pdo; $pdo->beginTransaction();
            try {
                (new BalanceService())->adjust($userId, -$price, 'invite_code_purchase', '购买自定义邀请码', 'invite_code', $code);
                $this->insertCode($userId, $code, $length, $price, 0);
                $pdo->commit();
            } catch (\Throwable $e) { $pdo->rollBack(); throw $e; }
        } else {
            $this->insertCode($userId, $code, $length, $price, $isDefault ? 1 : 0);
        }
        $stmt = $this->pdo->prepare('SELECT * FROM invite_codes WHERE code = ? LIMIT 1'); $stmt->execute([$code]); return (array) $stmt->fetch();
    }

    public function list(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM invite_codes WHERE user_id = ? ORDER BY is_default DESC, id DESC'); $stmt->execute([$userId]);
        $stmt2 = $this->pdo->prepare('SELECT iu.*, u.username AS invitee_username, u.nickname AS invitee_nickname FROM invite_code_usages iu LEFT JOIN users u ON u.id = iu.invitee_id WHERE iu.inviter_id = ? ORDER BY iu.id DESC'); $stmt2->execute([$userId]);
        return ['codes' => $stmt->fetchAll(), 'records' => $stmt2->fetchAll()];
    }

    public function refreshValidInviteForUser(int $userId): void
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1'); $stmt->execute([$userId]); $user = $stmt->fetch(); if (!$user) return;
        $usageStmt = $this->pdo->prepare('SELECT * FROM invite_code_usages WHERE invitee_id = ? AND became_valid = 0 LIMIT 1'); $usageStmt->execute([$userId]); $usage = $usageStmt->fetch(); if (!$usage) return;
        $mode = (string) $this->settings->get('invite_valid_mode', 'total_consume'); $value = (int) $this->settings->get('invite_valid_value', '100000');
        $ok = match ($mode) {
            'total_recharge' => (int) $user['total_recharge'] >= $value,
            'invite_count' => (int) $user['invite_count'] >= $value,
            'balance' => (int) $user['balance'] >= $value,
            default => (int) $user['total_consume'] >= $value,
        };
        if ($ok) {
            $this->pdo->prepare('UPDATE invite_code_usages SET became_valid = 1, valid_at = ? WHERE id = ?')->execute([now(), $usage['id']]);
            $this->pdo->prepare('UPDATE users SET invite_count = invite_count + 1, updated_at = ? WHERE id = ?')->execute([now(), $usage['inviter_id']]);
        }
    }

    private function priceForLength(int $length): int
    {
        $rules = $this->settings->getJson('invite_code_price_rules', ['mode' => 'fixed', 'fixed' => 0]);
        $mode = (string) ($rules['mode'] ?? '');
        if ($mode === 'fixed') return max(0, (int) ($rules['fixed'] ?? 0));
        if ($mode === 'length') return max(0, (int) ($rules[(string) $length] ?? 0));
        // 兼容旧配置：fixed 大于 0 时按固定价格，否则回退到长度规则。
        $fixed = (int) ($rules['fixed'] ?? 0);
        return $fixed > 0 ? $fixed : max(0, (int) ($rules[(string) $length] ?? 0));
    }

    private function uniqueCode(int $length): string
    {
        for ($i = 0; $i < 8; $i++) {
            $code = str_random($length);
            $stmt = $this->pdo->prepare('SELECT 1 FROM invite_codes WHERE code = ? LIMIT 1');
            $stmt->execute([$code]);
            if (!$stmt->fetchColumn()) return $code;
        }
        throw new RuntimeException('生成邀请码失败，请稍后重试');
    }
    private function insertCode(int $userId, string $code, int $length, int $price, int $isDefault): void
    {
        $this->pdo->prepare('INSERT INTO invite_codes (user_id, code, length, price_paid, is_default, max_uses, used_count, active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, -1, 0, 1, ?, ?)')->execute([$userId, $code, $length, $price, $isDefault, now(), now()]);
    }
}
