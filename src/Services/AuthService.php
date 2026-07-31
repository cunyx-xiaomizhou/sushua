<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;
use Sushua\Core\Session;
use Sushua\Support\Logger;

final class AuthService
{
    private PDO $pdo;
    private SettingsService $settings;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->settings = new SettingsService();
    }

    public function currentUser(): ?array
    {
        $id = Session::get('user_id');
        if (!$id) {
            return null;
        }
        $stmt = $this->pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.id = ? LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if (!$user || $user['status'] === 'deleted') {
            return null;
        }
        return $this->sanitizeUser($user);
    }

    public function requireUser(): array
    {
        $user = $this->currentUser();
        if (!$user) {
            throw new RuntimeException('请先登录');
        }
        if ($user['status'] === 'banned' && (!$user['ban_until'] || strtotime((string) $user['ban_until']) > time())) {
            throw new RuntimeException('账号已被封禁：' . ($user['ban_reason'] ?: '请联系管理员'));
        }
        return $user;
    }

    public function requireAdmin(): array
    {
        $user = $this->requireUser();
        if (!in_array($user['account_role'], ['owner', 'admin'], true)) {
            throw new RuntimeException('无管理员权限');
        }
        return $user;
    }

    public function login(string $username, string $password, bool $adminOnly = false): array
    {
        $stmt = $this->pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if (!$user || $user['status'] === 'deleted') {
            throw new RuntimeException('账号不存在');
        }
        if ($adminOnly && !in_array($user['account_role'], ['owner', 'admin'], true)) {
            throw new RuntimeException('该账号不是后台管理员');
        }
        if (!password_verify($password, (string) $user['password_hash'])) {
            throw new RuntimeException('密码错误');
        }
        if ($user['status'] === 'banned' && (!$user['ban_until'] || strtotime((string) $user['ban_until']) > time())) {
            throw new RuntimeException('账号已封禁：' . ($user['ban_reason'] ?: '请联系管理员'));
        }
        Session::put('user_id', (int) $user['id']);
        $stmt = $this->pdo->prepare('UPDATE users SET last_login_at = ?, last_login_ip = ?, updated_at = ? WHERE id = ?');
        $stmt->execute([now(), $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', now(), $user['id']]);
        return $this->sanitizeUser($user);
    }

    public function logout(): void
    {
        Session::flush();
    }

    public function register(array $data): array
    {
        $username = trim((string) ($data['username'] ?? ''));
        $nickname = trim((string) ($data['nickname'] ?? ''));
        $qq = trim((string) ($data['qq'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $email = trim((string) ($data['email'] ?? ''));
        $mobile = trim((string) ($data['mobile'] ?? ''));
        $inviteCode = trim((string) ($data['invite_code'] ?? ''));

        if (!preg_match('/^[A-Za-z0-9]{4,32}$/', $username)) {
            throw new RuntimeException('用户名必须是4-32位纯英文数字');
        }
        if ($nickname === '' || mb_strlen($nickname) < 2 || mb_strlen($nickname) > 30) {
            throw new RuntimeException('昵称长度需为2-30位');
        }
        if (!preg_match('/^[1-9][0-9]{4,14}$/', $qq)) {
            throw new RuntimeException('QQ号格式不正确');
        }
        if (strlen($password) < 6) {
            throw new RuntimeException('密码至少6位');
        }
        if ($this->settings->get('register_need_email', '0') === '1' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('当前注册必须填写有效邮箱');
        }
        if ($this->settings->get('register_need_mobile', '0') === '1' && $mobile === '') {
            throw new RuntimeException('当前注册必须填写手机号');
        }

        $this->assertUsernameAvailable($username);
        $defaultGroupId = $this->defaultGroupId();
        $inviterId = null;
        $inviteCodeId = null;
        $defaultFlags = $this->resolveConnectPolicyFlags(
            null,
            $this->settings->get('default_register_strategy_user', '0'),
            $this->settings->get('default_register_strategy_agent', '0')
        );
        $strategyUser = $defaultFlags['strategy_user'];
        $strategyAgent = $defaultFlags['strategy_agent'];
        $avatar = $this->qqAvatarUrl($qq);

        $this->pdo->beginTransaction();
        try {
            if ($inviteCode !== '') {
                $stmt = $this->pdo->prepare('SELECT * FROM invite_codes WHERE code = ? AND active = 1 LIMIT 1 FOR UPDATE');
                $stmt->execute([$inviteCode]);
                $invite = $stmt->fetch();
                if (!$invite) {
                    throw new RuntimeException('邀请码不存在或已失效');
                }
                if ((int) $invite['max_uses'] !== -1 && (int) $invite['used_count'] >= (int) $invite['max_uses']) {
                    throw new RuntimeException('邀请码已达到使用上限');
                }
                $inviterId = (int) $invite['user_id'];
                $inviteCodeId = (int) $invite['id'];
            }

            $uid = $this->generateUid();
            $apiKey = $strategyAgent ? str_random(40) : null;
            $stmt = $this->pdo->prepare('INSERT INTO users (uid, username, nickname, qq, email, mobile, avatar, password_hash, user_group_id, account_role, strategy_user, strategy_agent, api_key, api_key_generated_at, status, balance, total_recharge, total_consume, invite_count, inviter_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, ?, ?, ?)');
            $stmt->execute([
                $uid,
                $username,
                $nickname,
                $qq,
                $email ?: null,
                $mobile ?: null,
                $avatar,
                password_hash($password, PASSWORD_DEFAULT),
                $defaultGroupId,
                'member',
                $strategyUser,
                $strategyAgent,
                $apiKey,
                $apiKey ? now() : null,
                'active',
                $inviterId,
                now(),
                now(),
            ]);

            $userId = (int) $this->pdo->lastInsertId();
            if ($inviteCodeId) {
                $this->pdo->prepare('UPDATE invite_codes SET used_count = used_count + 1, updated_at = ? WHERE id = ?')->execute([now(), $inviteCodeId]);
                $this->pdo->prepare('INSERT INTO invite_code_usages (invite_code_id, inviter_id, invitee_id, created_at) VALUES (?, ?, ?, ?)')->execute([$inviteCodeId, $inviterId, $userId, now()]);
            }
            (new InviteService())->ensureDefaultCode($userId);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
        return $this->login($username, $password);
    }

    public function resetApiKey(int $userId): string
    {
        $check = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $check->execute([$userId]);
        $target = $check->fetch();
        if (!$target || ($target['status'] ?? '') === 'deleted') {
            throw new RuntimeException('用户不存在或已被删除');
        }
        $access = (new ApiAccessService())->status($target);
        if (!(bool) ($access['can_generate_key'] ?? false)) {
            throw new RuntimeException('当前账号暂未满足 API Key 生成条件');
        }
        $new = str_random(40);
        $stmt = $this->pdo->prepare('UPDATE users SET api_key = ?, api_key_generated_at = ?, updated_at = ? WHERE id = ?');
        $stmt->execute([$new, now(), now(), $userId]);
        return $new;
    }

    public function saveUser(array $actor, array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        $isCreate = $id <= 0;
        $nickname = trim((string) ($data['nickname'] ?? ''));
        $username = trim((string) ($data['username'] ?? ''));
        $qq = trim((string) ($data['qq'] ?? '0'));
        $password = trim((string) ($data['password'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $mobile = trim((string) ($data['mobile'] ?? ''));
        $status = (string) ($data['status'] ?? 'active');
        $groupId = (int) ($data['user_group_id'] ?? $this->defaultGroupId());
        $flags = $this->resolveConnectPolicyFlags(
            array_key_exists('connect_policy', $data) ? (string) $data['connect_policy'] : null,
            $data['strategy_user'] ?? null,
            $data['strategy_agent'] ?? null
        );
        $strategyUser = $flags['strategy_user'];
        $strategyAgent = $flags['strategy_agent'];
        $role = (string) ($data['account_role'] ?? 'member');
        $balance = (int) ($data['balance'] ?? 0);
        $apiOverride = array_key_exists('api_enabled_override', $data) && $data['api_enabled_override'] !== '' ? (int) $data['api_enabled_override'] : null;
        $allowedRoles = ['member', 'agent', 'admin', 'owner'];
        $allowedStatuses = ['active', 'banned'];

        if (!in_array($role, $allowedRoles, true)) {
            throw new RuntimeException('用户角色不合法');
        }
        if (!in_array($status, $allowedStatuses, true)) {
            throw new RuntimeException('用户状态不合法');
        }
        if (!preg_match('/^[A-Za-z0-9]{4,32}$/', $username)) {
            throw new RuntimeException('用户名必须是4-32位英文数字');
        }
        if ($nickname === '' || mb_strlen($nickname) > 30) {
            throw new RuntimeException('昵称不能为空且不能超过30位');
        }
        if ($qq !== '' && !preg_match('/^[1-9][0-9]{4,14}$/', $qq)) {
            throw new RuntimeException('QQ号格式不正确');
        }
        if ($balance < 0) {
            throw new RuntimeException('余额不能为负数');
        }
        if (in_array($role, ['admin', 'owner'], true)) {
            $strategyUser = 0;
            $strategyAgent = 0;
            $apiOverride = null;
        }
        if ($role !== 'member' && $role !== 'agent' && $actor['account_role'] !== 'owner') {
            throw new RuntimeException('只有站长才能创建或修改后台账号');
        }
        $target = null;
        if (!$isCreate) {
            $targetStmt = $this->pdo->prepare('SELECT id, account_role, qq FROM users WHERE id = ? LIMIT 1');
            $targetStmt->execute([$id]);
            $target = $targetStmt->fetch();
            if (!$target) {
                throw new RuntimeException('用户不存在');
            }
            $targetRole = (string) ($target['account_role'] ?? '');
            if ($targetRole === 'owner') {
                if ($role !== 'owner') {
                    throw new RuntimeException('站长身份不可更改');
                }
                if ((int) $actor['id'] !== $id) {
                    throw new RuntimeException('站长账号不允许在此处被他人修改');
                }
            } elseif ($role === 'owner') {
                throw new RuntimeException('不允许将其他用户修改为站长');
            }
        } elseif ($role === 'owner') {
            throw new RuntimeException('不允许直接创建站长账号');
        }
        if ($actor['account_role'] !== 'owner' && $id > 0) {
            if (in_array((string) ($target['account_role'] ?? ''), ['owner', 'admin'], true)) {
                throw new RuntimeException('管理员不能修改站长或管理员账号');
            }
        }

        $this->assertUsernameAvailable($username, $isCreate ? 0 : $id);
        $avatarValue = $qq !== '' ? $this->qqAvatarUrl($qq) : (($target['qq'] ?? '') !== '' ? $this->qqAvatarUrl((string) $target['qq']) : null);

        if ($isCreate) {
            if ($password === '') {
                throw new RuntimeException('新增用户必须设置密码');
            }
            if ($balance < 0) {
                throw new RuntimeException('初始余额不能为负数');
            }
            $this->pdo->beginTransaction();
            try {
                $stmt = $this->pdo->prepare('INSERT INTO users (uid, username, nickname, qq, email, mobile, avatar, password_hash, user_group_id, account_role, strategy_user, strategy_agent, api_key, api_key_generated_at, api_enabled_override, status, balance, total_recharge, total_consume, invite_count, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, ?, ?)');
                $stmt->execute([
                    $this->generateUid(),
                    $username,
                    $nickname,
                    $qq,
                    $email ?: null,
                    $mobile ?: null,
                    $avatarValue,
                    password_hash($password, PASSWORD_DEFAULT),
                    $groupId,
                    $role,
                    $strategyUser,
                    $strategyAgent,
                    $strategyAgent ? str_random(40) : null,
                    $strategyAgent ? now() : null,
                    $apiOverride,
                    $status,
                    now(),
                    now(),
                ]);
                $id = (int) $this->pdo->lastInsertId();
                if ($balance > 0) {
                    (new BalanceService())->adjust($id, $balance, 'admin_adjustment', '管理员创建用户时设置初始余额', 'admin_adjustment', (string) $actor['id'] . ':' . $id);
                }
                (new InviteService())->ensureDefaultCode($id);
                $this->pdo->commit();
                Logger::write('info', 'user', '管理员创建用户', ['actor_id' => (int) $actor['id'], 'user_id' => $id, 'initial_balance' => $balance], (int) $actor['id']);
            } catch (\Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                throw $e;
            }
        } else {
            $this->pdo->beginTransaction();
            try {
                $sets = ['username = ?', 'nickname = ?', 'qq = ?', 'email = ?', 'mobile = ?', 'avatar = ?', 'user_group_id = ?', 'strategy_user = ?', 'strategy_agent = ?', 'api_enabled_override = ?', 'status = ?', 'ban_until = ?', 'ban_reason = ?', 'updated_at = ?'];
                $params = [$username, $nickname, $qq, $email ?: null, $mobile ?: null, $avatarValue, $groupId, $strategyUser, $strategyAgent, $apiOverride, $status, $data['ban_until'] ?: null, $data['ban_reason'] ?: null, now()];
                if ($strategyAgent === 0) {
                    $sets[] = 'api_key = ?';
                    $params[] = null;
                    $sets[] = 'api_key_generated_at = ?';
                    $params[] = null;
                } else {
                    $keyStmt = $this->pdo->prepare('SELECT api_key FROM users WHERE id = ? LIMIT 1 FOR UPDATE');
                    $keyStmt->execute([$id]);
                    if (!$keyStmt->fetchColumn()) {
                        $sets[] = 'api_key = ?';
                        $params[] = str_random(40);
                        $sets[] = 'api_key_generated_at = ?';
                        $params[] = now();
                    }
                }
                if ($actor['account_role'] === 'owner' && (string) ($target['account_role'] ?? '') !== 'owner') {
                    $sets[] = 'account_role = ?';
                    $params[] = $role;
                }
                if ($password !== '') {
                    $sets[] = 'password_hash = ?';
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }
                $requestedBalance = null;
                if (array_key_exists('balance', $data)) {
                    if (!is_numeric((string) $data['balance']) || (int) $data['balance'] < 0) {
                        throw new RuntimeException('余额必须是大于等于0的整数');
                    }
                    $requestedBalance = (int) $data['balance'];
                }
                $params[] = $id;
                $sql = 'UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = ?';
                $this->pdo->prepare($sql)->execute($params);
                if ($requestedBalance !== null) {
                    $lock = $this->pdo->prepare('SELECT balance FROM users WHERE id = ? LIMIT 1 FOR UPDATE');
                    $lock->execute([$id]);
                    $currentBalance = $lock->fetchColumn();
                    if ($currentBalance === false) {
                        throw new RuntimeException('用户不存在');
                    }
                    $delta = $requestedBalance - (int) $currentBalance;
                    if ($delta !== 0) {
                        (new BalanceService())->adjust($id, $delta, 'admin_adjustment', '管理员调整用户余额', 'admin_adjustment', (string) $actor['id'] . ':' . $id);
                        Logger::write('info', 'balance', '管理员调整用户余额', ['actor_id' => (int) $actor['id'], 'user_id' => $id, 'before' => (int) $currentBalance, 'after' => $requestedBalance, 'delta' => $delta], (int) $actor['id']);
                    }
                }
                $this->pdo->commit();
            } catch (\Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                throw $e;
            }
        }

        $stmt = $this->pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $this->sanitizeUser((array) $stmt->fetch());
    }

    public function updateProfile(int $userId, array $data): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? AND status <> ? LIMIT 1');
        $stmt->execute([$userId, 'deleted']);
        $user = $stmt->fetch();
        if (!$user) {
            throw new RuntimeException('用户不存在');
        }

        $nickname = trim((string) ($data['nickname'] ?? $user['nickname'] ?? ''));
        $qq = trim((string) ($data['qq'] ?? $user['qq'] ?? ''));
        $email = trim((string) ($data['email'] ?? $user['email'] ?? ''));
        $mobile = trim((string) ($data['mobile'] ?? $user['mobile'] ?? ''));
        if ($nickname === '' || mb_strlen($nickname) < 2 || mb_strlen($nickname) > 30) {
            throw new RuntimeException('昵称长度需为2-30位');
        }
        if (!preg_match('/^[1-9][0-9]{4,14}$/', $qq)) {
            throw new RuntimeException('QQ号格式不正确');
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('邮箱格式不正确');
        }
        if ($mobile !== '' && !preg_match('/^[0-9+\-\s]{6,30}$/', $mobile)) {
            throw new RuntimeException('手机号格式不正确');
        }

        $avatarValue = $this->qqAvatarUrl($qq);
        $this->pdo->prepare('UPDATE users SET nickname = ?, qq = ?, email = ?, mobile = ?, avatar = ?, updated_at = ? WHERE id = ?')
            ->execute([$nickname, $qq, $email ?: null, $mobile ?: null, $avatarValue, now(), $userId]);

        $stmt = $this->pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.id = ? LIMIT 1');
        $stmt->execute([$userId]);
        return $this->sanitizeUser((array) $stmt->fetch());
    }

    public function changePassword(int $userId, string $old, string $new): array
    {
        $old = (string) $old;
        $new = (string) $new;
        if (strlen($new) < 6) {
            throw new RuntimeException('新密码至少6位');
        }
        $stmt = $this->pdo->prepare('SELECT password_hash FROM users WHERE id = ? AND status <> ? LIMIT 1');
        $stmt->execute([$userId, 'deleted']);
        $hash = $stmt->fetchColumn();
        if (!$hash) {
            throw new RuntimeException('用户不存在');
        }
        if (!password_verify($old, (string) $hash)) {
            throw new RuntimeException('旧密码错误');
        }
        $this->pdo->prepare('UPDATE users SET password_hash = ?, updated_at = ? WHERE id = ?')
            ->execute([password_hash($new, PASSWORD_DEFAULT), now(), $userId]);
        return ['changed' => true];
    }

    public function softDeleteUser(array $actor, int $id): void
    {
        $stmt = $this->pdo->prepare('SELECT account_role FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $targetRole = (string) $stmt->fetchColumn();
        if ($targetRole === '') {
            throw new RuntimeException('用户不存在');
        }
        if ($targetRole === 'owner') {
            throw new RuntimeException('站长账号不允许删除');
        }
        if ($targetRole === 'admin' && (string) ($actor['account_role'] ?? '') !== 'owner') {
            throw new RuntimeException('只有站长可以删除管理员账号');
        }
        $stmt = $this->pdo->prepare('UPDATE users SET status = ?, deleted_at = ?, updated_at = ? WHERE id = ?');
        $stmt->execute(['deleted', now(), now(), $id]);
    }

    public function listUsers(array $filters = []): array
    {
        $sql = 'SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.status <> "deleted"';
        $params = [];
        if (!empty($filters['keyword'])) {
            $sql .= ' AND (u.username LIKE ? OR u.nickname LIKE ? OR u.qq LIKE ? OR CAST(u.uid AS CHAR) LIKE ?)';
            $kw = '%' . $filters['keyword'] . '%';
            array_push($params, $kw, $kw, $kw, $kw);
        }
        $sql .= " ORDER BY CASE u.account_role WHEN 'owner' THEN 0 WHEN 'admin' THEN 1 ELSE 2 END, u.id DESC LIMIT 200";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return array_map(fn (array $row) => $this->sanitizeUser($row), $stmt->fetchAll());
    }

    private function defaultGroupId(): int
    {
        $stmt = $this->pdo->query('SELECT id FROM user_groups WHERE is_default_register = 1 ORDER BY id ASC LIMIT 1');
        $id = $stmt->fetchColumn();
        if ($id) {
            return (int) $id;
        }
        $stmt = $this->pdo->query('SELECT id FROM user_groups ORDER BY id ASC LIMIT 1');
        $id = $stmt->fetchColumn();
        if ($id) {
            return (int) $id;
        }
        throw new RuntimeException('尚未配置默认用户组');
    }

    private function generateUid(): int
    {
        do {
            $uid = random_int(10000000, 99999999);
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE uid = ? LIMIT 1');
            $stmt->execute([$uid]);
            $exists = $stmt->fetchColumn();
        } while ($exists);
        return $uid;
    }

    private function resolveConnectPolicyFlags(?string $policy, mixed $strategyUser, mixed $strategyAgent): array
    {
        $policy = $policy !== null ? strtolower(trim($policy)) : '';
        if ($policy === 'agent') {
            return ['strategy_user' => 0, 'strategy_agent' => 1, 'connect_policy' => 'agent'];
        }
        if ($policy === 'user') {
            return ['strategy_user' => 1, 'strategy_agent' => 0, 'connect_policy' => 'user'];
        }
        if ($policy === 'default') {
            return ['strategy_user' => 0, 'strategy_agent' => 0, 'connect_policy' => 'default'];
        }

        $agent = in_array((string) $strategyAgent, ['1', 'true', 'on'], true) ? 1 : (int) (!!$strategyAgent);
        $user = in_array((string) $strategyUser, ['1', 'true', 'on'], true) ? 1 : (int) (!!$strategyUser);
        if ($agent === 1) {
            return ['strategy_user' => 0, 'strategy_agent' => 1, 'connect_policy' => 'agent'];
        }
        if ($user === 1) {
            return ['strategy_user' => 1, 'strategy_agent' => 0, 'connect_policy' => 'user'];
        }
        return ['strategy_user' => 0, 'strategy_agent' => 0, 'connect_policy' => 'default'];
    }

    private function qqAvatarUrl(string $qq): string
    {
        return 'https://q1.qlogo.cn/g?b=qq&nk=' . rawurlencode($qq) . '&s=100';
    }

    private function assertUsernameAvailable(string $username, int $ignoreId = 0): void
    {
        $sql = 'SELECT id FROM users WHERE username = ?';
        $params = [$username];
        if ($ignoreId > 0) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetchColumn()) {
            throw new RuntimeException('用户名已存在');
        }
    }

    private function sanitizeUser(array $user): array
    {
        unset($user['password_hash']);
        $user['strategy_user'] = (int) ($user['strategy_user'] ?? 0);
        $user['strategy_agent'] = (int) ($user['strategy_agent'] ?? 0);
        $user['balance'] = (int) ($user['balance'] ?? 0);
        $user['total_recharge'] = (int) ($user['total_recharge'] ?? 0);
        $user['total_consume'] = (int) ($user['total_consume'] ?? 0);
        $user['invite_count'] = (int) ($user['invite_count'] ?? 0);
        $user['uid'] = (int) ($user['uid'] ?? 0);
        $user['id'] = (int) ($user['id'] ?? 0);
        $user['connect_policy'] = $this->resolveConnectPolicyFlags(null, $user['strategy_user'], $user['strategy_agent'])['connect_policy'];
        $user['role_label'] = match ((string) ($user['account_role'] ?? 'member')) {
            'owner' => '站长',
            'admin' => '管理员',
            'agent' => '代理',
            default => '用户',
        };
        return $user;
    }
}
