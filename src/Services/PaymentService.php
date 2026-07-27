<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

final class PaymentService
{
    private PDO $pdo;
    public function __construct() { $this->pdo = Database::connection(); }
    public function merchants(): array
    {
        $rows = $this->pdo->query('SELECT id, name, endpoint, pid, enabled, created_at, updated_at FROM payment_merchants ORDER BY id DESC')->fetchAll();
        foreach ($rows as &$row) $row['merchant_key_set'] = 1;
        unset($row);
        return $rows;
    }
    public function channels(): array { return $this->pdo->query('SELECT c.*, m.name AS merchant_name FROM payment_channels c LEFT JOIN payment_merchants m ON m.id = c.merchant_id ORDER BY c.sort_order ASC, c.id ASC')->fetchAll(); }

    public function saveMerchant(array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        $name = trim((string) ($data['name'] ?? ''));
        $endpoint = rtrim(trim((string) ($data['endpoint'] ?? '')), '/');
        $pid = trim((string) ($data['pid'] ?? ''));
        $merchantKey = trim((string) ($data['merchant_key'] ?? ''));
        if ($name === '' || mb_strlen($name) > 80) throw new RuntimeException('易支付商户名称不能为空且不能超过80个字符');
        $parts = parse_url($endpoint);
        if (!$parts || !in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true) || empty($parts['host'])) throw new RuntimeException('易支付地址必须是有效的 http/https URL');
        if ($pid === '' || !preg_match('/^[A-Za-z0-9_-]{1,40}$/', $pid)) throw new RuntimeException('商户ID格式不正确');
        if ($id > 0) {
            if ($merchantKey === '') {
                $stmt = $this->pdo->prepare('SELECT merchant_key FROM payment_merchants WHERE id = ? LIMIT 1');
                $stmt->execute([$id]);
                $merchantKey = (string) $stmt->fetchColumn();
            }
            if ($merchantKey === '') throw new RuntimeException('商户密钥不能为空');
            $this->pdo->prepare('UPDATE payment_merchants SET name=?, endpoint=?, pid=?, merchant_key=?, enabled=?, updated_at=? WHERE id=?')->execute([$name, $endpoint, $pid, $merchantKey, !empty($data['enabled']) ? 1 : 0, now(), $id]);
        } else {
            if ($merchantKey === '') throw new RuntimeException('商户密钥不能为空');
            $this->pdo->prepare('INSERT INTO payment_merchants (name, endpoint, pid, merchant_key, enabled, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute([$name, $endpoint, $pid, $merchantKey, !empty($data['enabled']) ? 1 : 0, now(), now()]);
            $id = (int) $this->pdo->lastInsertId();
        }
        $stmt = $this->pdo->prepare('SELECT id, name, endpoint, pid, enabled, created_at, updated_at FROM payment_merchants WHERE id = ?');
        $stmt->execute([$id]);
        $row = (array) $stmt->fetch();
        $row['merchant_key_set'] = 1;
        return $row;
    }

    public function saveChannel(array $data): array
    {
        $id = (int) ($data['id'] ?? 0);
        $code = trim((string) ($data['code'] ?? ''));
        $name = trim((string) ($data['name'] ?? ''));
        $payType = trim((string) ($data['pay_type'] ?? ''));
        $merchantId = (int) ($data['merchant_id'] ?? 0);
        if ($code === '' || !preg_match('/^[A-Za-z0-9_-]{1,40}$/', $code)) throw new RuntimeException('支付通道编码格式不正确');
        if ($name === '' || $payType === '' || $merchantId <= 0) throw new RuntimeException('支付通道信息不完整');
        $merchant = $this->pdo->prepare('SELECT id FROM payment_merchants WHERE id = ? AND enabled = 1 LIMIT 1');
        $merchant->execute([$merchantId]);
        if (!$merchant->fetchColumn()) throw new RuntimeException('支付商户不存在或未启用');
        if ($id > 0) {
            $this->pdo->prepare('UPDATE payment_channels SET code=?, name=?, pay_type=?, merchant_id=?, enabled=?, sort_order=?, updated_at=? WHERE id=?')->execute([$code, $name, $payType, $merchantId, !empty($data['enabled']) ? 1 : 0, (int) ($data['sort_order'] ?? 0), now(), $id]);
        } else {
            $this->pdo->prepare('INSERT INTO payment_channels (code, name, pay_type, merchant_id, enabled, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute([$code, $name, $payType, $merchantId, !empty($data['enabled']) ? 1 : 0, (int) ($data['sort_order'] ?? 0), now(), now()]);
            $id = (int) $this->pdo->lastInsertId();
        }
        $stmt = $this->pdo->prepare('SELECT * FROM payment_channels WHERE id = ?'); $stmt->execute([$id]); return (array) $stmt->fetch();
    }

    public function createRecharge(array $user, int $channelId, int $creditAmount): array
    {
        if ($creditAmount <= 0) throw new RuntimeException('充值额度必须大于0');
        $channel = $this->channel($channelId); $merchant = $this->merchant((int) $channel['merchant_id']);
        if ((int) $merchant['enabled'] !== 1 || (int) $channel['enabled'] !== 1) throw new RuntimeException('支付通道未启用');
        $group = null; foreach ((new UserGroupService())->all() as $candidate) if ((int) $candidate['id'] === (int) $user['user_group_id']) $group = $candidate;
        $bonusRate = (float) ($group['recharge_bonus_rate'] ?? 1); $bonusAmount = max(0, (int) round($creditAmount * max(0, $bonusRate - 1)));
        $orderNo = 'RC' . date('YmdHis') . random_int(1000, 9999); $money = number_format($creditAmount / 10000, 2, '.', '');
        $payload = ['pid' => $merchant['pid'], 'type' => $channel['pay_type'], 'out_trade_no' => $orderNo, 'notify_url' => $this->baseUrl() . '/internal/recharge/notify', 'return_url' => $this->baseUrl() . '/?tab=recharge', 'name' => '额度充值', 'money' => $money, 'clientip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', 'device' => 'pc', 'param' => (string) $user['id']];
        $payload['sign'] = $this->sign($payload, (string) $merchant['merchant_key']); $payload['sign_type'] = 'MD5';
        $this->pdo->prepare('INSERT INTO recharge_orders (order_no, user_id, channel_id, merchant_id, amount, credit_amount, bonus_amount, status, pay_type, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([$orderNo, $user['id'], $channelId, $merchant['id'], (int) round((float) $money * 100), $creditAmount, $bonusAmount, 'pending', $channel['pay_type'], now(), now()]);
        try {
            $gateway = $this->postGateway(rtrim((string) $merchant['endpoint'], '/') . '/mapi.php', $payload);
        } catch (\Throwable $e) {
            $this->markGatewayFailure($orderNo, ['error' => $e->getMessage()]);
            throw $e;
        }
        if ((string) ($gateway['code'] ?? '') !== '1') {
            $message = trim((string) ($gateway['msg'] ?? '易支付网关创建订单失败'));
            $this->markGatewayFailure($orderNo, $gateway);
            throw new RuntimeException($message !== '' ? $message : '易支付网关创建订单失败');
        }
        return ['order_no' => $orderNo, 'credit_amount' => $creditAmount, 'bonus_amount' => $bonusAmount, 'gateway' => [
            'code' => 1,
            'msg' => (string) ($gateway['msg'] ?? ''),
            'trade_no' => $gateway['trade_no'] ?? null,
            'payurl' => $gateway['payurl'] ?? null,
            'qrcode' => $gateway['qrcode'] ?? null,
            'urlscheme' => $gateway['urlscheme'] ?? null,
        ]];
    }

    public function handleNotify(array $data): array
    {
        $orderNo = (string) ($data['out_trade_no'] ?? ''); $stmt = $this->pdo->prepare('SELECT * FROM recharge_orders WHERE order_no = ? LIMIT 1'); $stmt->execute([$orderNo]); $order = $stmt->fetch(); if (!$order) throw new RuntimeException('充值订单不存在');
        $merchant = $this->merchant((int) $order['merchant_id']); if (!$this->verifySign($data, (string) $merchant['merchant_key'])) throw new RuntimeException('签名校验失败');
        if (($data['trade_status'] ?? '') !== 'TRADE_SUCCESS') return ['ok' => true, 'status' => 'ignored'];
        $callbackCents = $this->moneyToCents((string) ($data['money'] ?? ''));
        if ($callbackCents === null || $callbackCents !== (int) $order['amount']) {
            throw new RuntimeException('回调金额与充值订单不一致');
        }
        if ($order['status'] === 'paid') return ['ok' => true, 'status' => 'duplicate'];
        $pdo = $this->pdo; $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM recharge_orders WHERE id = ? FOR UPDATE'); $stmt->execute([$order['id']]); $locked = $stmt->fetch();
            if ($locked['status'] !== 'paid') {
                (new BalanceService())->adjust((int) $locked['user_id'], (int) $locked['credit_amount'] + (int) $locked['bonus_amount'], 'recharge', '在线充值到账', 'recharge_order', (string) $locked['order_no']);
                $pdo->prepare('UPDATE recharge_orders SET status = ?, epay_trade_no = ?, raw_json = ?, paid_at = ?, updated_at = ? WHERE id = ?')->execute(['paid', $data['trade_no'] ?? null, json_encode($data, JSON_UNESCAPED_UNICODE), now(), now(), $locked['id']]);
                (new UserGroupService())->evaluateAndUpdate((int) $locked['user_id']); (new InviteService())->refreshValidInviteForUser((int) $locked['user_id']);
            }
            $pdo->commit();
        } catch (\Throwable $e) { $pdo->rollBack(); throw $e; }
        return ['ok' => true, 'status' => 'paid'];
    }

    public function rechargeOrders(): array
    {
        $sql = 'SELECT r.*, u.username, c.name AS channel_name, m.name AS merchant_name FROM recharge_orders r LEFT JOIN users u ON u.id = r.user_id LEFT JOIN payment_channels c ON c.id = r.channel_id LEFT JOIN payment_merchants m ON m.id = r.merchant_id ORDER BY r.id DESC LIMIT 500';
        return $this->pdo->query($sql)->fetchAll();
    }

    private function sign(array $data, string $key): string
    {
        unset($data['sign'], $data['sign_type']); ksort($data); $pieces = [];
        foreach ($data as $k => $v) if ($v !== '' && $v !== null) $pieces[] = $k . '=' . $v;
        return md5(implode('&', $pieces) . $key);
    }
    private function verifySign(array $data, string $key): bool { return strtolower((string) ($data['sign'] ?? '')) === strtolower($this->sign($data, $key)); }

    private function moneyToCents(string $money): ?int
    {
        $money = trim($money);
        if (!preg_match('/^(?:0|[1-9]\d*)(?:\.\d{1,2})?$/', $money)) return null;
        [$whole, $fraction] = array_pad(explode('.', $money, 2), 2, '');
        $fraction = str_pad($fraction, 2, '0');
        $value = (int) $whole * 100 + (int) $fraction;
        return $value >= 0 ? $value : null;
    }

    private function postGateway(string $url, array $data): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data, '', '&', PHP_QUERY_RFC3986),
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded; charset=UTF-8'],
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            $message = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('支付网关请求失败：' . $message);
        }
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $decoded = json_decode((string) $body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('支付网关返回了无效 JSON（HTTP ' . $httpCode . '）');
        }
        $decoded['_http_code'] = $httpCode;
        return $decoded;
    }

    private function markGatewayFailure(string $orderNo, array $payload): void
    {
        $this->pdo->prepare('UPDATE recharge_orders SET status = ?, raw_json = ?, updated_at = ? WHERE order_no = ? AND status = ?')
            ->execute(['failed', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), now(), $orderNo, 'pending']);
    }

    private function merchant(int $id): array { $stmt = $this->pdo->prepare('SELECT * FROM payment_merchants WHERE id = ? LIMIT 1'); $stmt->execute([$id]); $row = $stmt->fetch(); if (!$row) throw new RuntimeException('支付商户不存在'); return $row; }
    private function channel(int $id): array { $stmt = $this->pdo->prepare('SELECT * FROM payment_channels WHERE id = ? LIMIT 1'); $stmt->execute([$id]); $row = $stmt->fetch(); if (!$row) throw new RuntimeException('支付通道不存在'); return $row; }
    private function baseUrl(): string { $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'; return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:3400'); }
}
