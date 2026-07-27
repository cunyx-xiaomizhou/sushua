<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

final class CardService
{
    private PDO $pdo;
    public function __construct() { $this->pdo = Database::connection(); }

    public function generate(int $adminId, int $count, int $amount, int $uses, string $prefix = '', string $note = ''): array
    {
        $count = max(1, min(500, $count));
        $amount = $this->validateAmount($amount);
        $uses = $this->validateUses($uses);
        $prefix = strtoupper(trim($prefix));
        if ($prefix !== '' && !preg_match('/^[A-Z0-9]{1,16}$/', $prefix)) {
            throw new RuntimeException('卡密前缀只能是1-16位英文或数字');
        }
        $note = trim($note);
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $code = $this->uniqueCode($prefix);
            try {
                $this->pdo->prepare('INSERT INTO card_keys (code, amount, total_uses, remaining_uses, enabled, note, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?)')->execute([$code, $amount, $uses, $uses, $note, $adminId, now(), now()]);
            } catch (\PDOException $e) {
                if ((int) $e->errorInfo[1] !== 1062) throw $e;
                $i--;
                continue;
            }
            $result[] = ['code' => $code, 'amount' => $amount, 'uses' => $uses];
        }
        return $result;
    }

    public function list(): array { return $this->pdo->query('SELECT * FROM card_keys ORDER BY id DESC LIMIT 500')->fetchAll(); }

    public function save(array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) throw new RuntimeException('卡密ID无效');
        $stmt = $this->pdo->prepare('SELECT * FROM card_keys WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $current = $stmt->fetch();
        if (!$current) throw new RuntimeException('卡密不存在');

        $code = strtoupper(trim((string) ($data['code'] ?? '')));
        if (!preg_match('/^[A-Z0-9]{8,64}$/', $code)) throw new RuntimeException('卡密必须是8-64位英文或数字');
        $amount = $this->validateAmount((int) ($data['amount'] ?? 0));
        $totalUses = $this->validateUses((int) ($data['total_uses'] ?? 0));
        $remainingUses = (int) ($data['remaining_uses'] ?? 0);
        if ($totalUses === -1) {
            if (!in_array($remainingUses, [-1, 0], true)) throw new RuntimeException('无限次卡密的剩余次数只能是-1或0');
        } elseif ($remainingUses < 0 || $remainingUses > $totalUses) {
            throw new RuntimeException('剩余次数必须在0和总次数之间');
        }
        $destroyed = !empty($data['destroyed']) || $remainingUses === 0;
        $enabled = !empty($data['enabled']) && !$destroyed ? 1 : 0;
        if ($destroyed) $remainingUses = 0;
        try {
            $this->pdo->prepare('UPDATE card_keys SET code = ?, amount = ?, total_uses = ?, remaining_uses = ?, enabled = ?, note = ?, destroyed_at = ?, updated_at = ? WHERE id = ?')->execute([$code, $amount, $totalUses, $remainingUses, $enabled, trim((string) ($data['note'] ?? '')), $destroyed ? now() : null, now(), $id]);
        } catch (\PDOException $e) {
            if ((int) $e->errorInfo[1] === 1062) throw new RuntimeException('卡密已存在');
            throw $e;
        }
        $stmt = $this->pdo->prepare('SELECT * FROM card_keys WHERE id = ?');
        $stmt->execute([$id]);
        return (array) $stmt->fetch();
    }

    public function destroy(int $id): void { $this->pdo->prepare('UPDATE card_keys SET enabled = 0, remaining_uses = 0, destroyed_at = ?, updated_at = ? WHERE id = ?')->execute([now(), now(), $id]); }

    public function redeem(int $userId, string $code, string $ip): array
    {
        $code = strtoupper(trim($code));
        if (!preg_match('/^[A-Z0-9]{8,64}$/', $code)) throw new RuntimeException('卡密格式不正确');
        $pdo = $this->pdo; $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM card_keys WHERE code = ? LIMIT 1 FOR UPDATE'); $stmt->execute([$code]); $card = $stmt->fetch();
            if (!$card || (int) $card['enabled'] !== 1) throw new RuntimeException('卡密不存在或已失效');
            if ((int) $card['remaining_uses'] == 0) throw new RuntimeException('卡密已被使用完');
            $balanceChange = (new BalanceService())->adjust($userId, (int) $card['amount'], 'card_recharge', '卡密充值', 'card_key', (string) $card['id']);
            if ((int) $card['remaining_uses'] > 0) $pdo->prepare('UPDATE card_keys SET remaining_uses = remaining_uses - 1, updated_at = ? WHERE id = ?')->execute([now(), $card['id']]);
            $pdo->prepare('INSERT INTO card_key_usages (card_key_id, user_id, amount, balance_before, balance_after, used_ip, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute([$card['id'], $userId, $card['amount'], $balanceChange['before'], $balanceChange['after'], $ip, now()]);
            (new UserGroupService())->evaluateAndUpdate($userId); (new InviteService())->refreshValidInviteForUser($userId); $pdo->commit();
            return ['card' => $card, 'balance' => $balanceChange['after']];
        } catch (\Throwable $e) { $pdo->rollBack(); throw $e; }
    }

    private function validateAmount(int $amount): int
    {
        if ($amount <= 0) throw new RuntimeException('卡密充值额度必须大于0');
        return $amount;
    }

    private function validateUses(int $uses): int
    {
        if ($uses < -1) throw new RuntimeException('卡密使用次数必须是-1或大于等于0');
        return $uses;
    }

    private function uniqueCode(string $prefix): string
    {
        for ($i = 0; $i < 5; $i++) {
            $code = $prefix . str_random(20);
            $stmt = $this->pdo->prepare('SELECT 1 FROM card_keys WHERE code = ? LIMIT 1');
            $stmt->execute([$code]);
            if (!$stmt->fetchColumn()) return $code;
        }
        throw new RuntimeException('生成卡密失败，请稍后重试');
    }
}
