<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;
use Sushua\Support\Logger;

final class OrderService
{
    private PDO $pdo;
    private ProductService $products;
    private PricingService $pricing;
    private UpstreamClient $upstream;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->products = new ProductService();
        $this->pricing = new PricingService();
        $this->upstream = new UpstreamClient();
    }

    public function quote(array $user, array $data, bool $fromApi = false): array
    {
        $product = $this->products->findBySign((string) ($data['sign'] ?? ''));
        if ((int) $product['enabled'] !== 1) {
            throw new RuntimeException('该商品已停用');
        }
        if ($fromApi && (int) $product['allow_api'] !== 1) {
            throw new RuntimeException('该商品当前不允许对接');
        }
        if (!$fromApi && (int) $product['allow_frontend'] !== 1) {
            throw new RuntimeException('该商品当前不允许前台下单');
        }
        $quantity = (int) ($data['num'] ?? 0);
        if ($quantity < (int) $product['min_num'] || $quantity > (int) $product['max_num']) {
            throw new RuntimeException('下单数量不在允许范围内');
        }
        if ($quantity % max(1, (int) $product['step_num']) !== 0) {
            throw new RuntimeException('下单数量必须为步长整数倍');
        }
        $price = $this->pricing->calculate($product, $this->findGroup((int) $user['user_group_id']), $quantity, !empty($data['is_delayed']));
        return [
            'sign' => $product['upstream_sign'],
            'quantity' => $quantity,
            'price' => $price['sell_price'],
            'cost' => $price['base_cost'],
            'profit' => $price['profit'],
            'discount_rate' => $price['discount_rate'],
            'currency' => (new SettingsService())->get('currency_name', '额度'),
        ];
    }

    public function create(array $user, array $data, bool $fromApi = false, ?int $fixedSellPrice = null): array
    {
        $product = $this->products->findBySign((string) ($data['sign'] ?? ''));
        if ((int) $product['enabled'] !== 1) {
            throw new RuntimeException('该商品已停用');
        }
        if ($fromApi && (int) $product['allow_api'] !== 1) {
            throw new RuntimeException('该商品当前不允许对接');
        }
        if (!$fromApi && (int) $product['allow_frontend'] !== 1) {
            throw new RuntimeException('该商品当前不允许前台下单');
        }
        $settings = new SettingsService();
        if ($fromApi && $settings->get('api_order_enabled', '1') !== '1') {
            throw new RuntimeException('系统当前已关闭对接下单');
        }
        if (!$fromApi && $settings->get('frontend_order_enabled', '1') !== '1') {
            throw new RuntimeException('系统当前已关闭前台下单');
        }

        $quantity = (int) ($data['num'] ?? 0);
        $qq = trim((string) ($data['qq'] ?? ''));
        $feedId = trim((string) ($data['feed_id'] ?? ''));
        $isDelayed = !empty($data['is_delayed']);
        if ($quantity < (int) $product['min_num'] || $quantity > (int) $product['max_num']) {
            throw new RuntimeException('下单数量不在允许范围内');
        }
        if ($quantity % max(1, (int) $product['step_num']) !== 0) {
            throw new RuntimeException('下单数量必须为步长整数倍');
        }
        if ($qq === '' || !preg_match('/^[1-9][0-9]{4,14}$/', $qq)) {
            throw new RuntimeException('QQ号格式不正确');
        }

        $group = $this->findGroup((int) $user['user_group_id']);
        $price = $this->pricing->calculate($product, $group, $quantity, $isDelayed);
        if ($fixedSellPrice !== null) {
            $price['sell_price'] = max(0, $fixedSellPrice);
            $price['profit'] = max(0, (int) $price['sell_price'] - (int) $price['base_cost']);
        }
        $limit = (int) $price['base_cost'];
        if ($fromApi) {
            $limit = $this->resolveLimit($data['limit'] ?? null, (int) $price['base_cost']);
            if ((int) $price['base_cost'] > $limit) {
                throw new RuntimeException('实际成本超过限价，已阻止下单');
            }
        }

        $extra = $this->collectDeclaredInput($product, $data, $qq, $quantity, $feedId);
        $upstreamParams = $extra;
        $upstreamParams['sign'] = $product['upstream_sign'];
        $upstreamParams['qq'] = $qq;
        $upstreamParams['num'] = $quantity;
        $upstreamParams['feed_id'] = $feedId !== '' ? $feedId : null;
        $upstreamParams['is_delayed'] = $isDelayed ? 1 : 0;
        if ($fromApi) {
            $upstreamParams['limit'] = $limit;
        }

        $orderNo = 'OD' . date('YmdHis') . random_int(1000, 9999);
        $ownsCreateTransaction = !$this->pdo->inTransaction();
        if ($ownsCreateTransaction) {
            $this->pdo->beginTransaction();
        } else {
            $this->pdo->exec('SAVEPOINT order_create');
        }
        try {
            (new BalanceService())->adjust((int) $user['id'], -$price['sell_price'], 'order_consume', '速刷下单扣费', 'order', $orderNo);
            $this->pdo->prepare('INSERT INTO orders (order_no, user_id, product_id, upstream_sign, target_qq, quantity, feed_id, is_delayed, extra_input_json, order_method, state, user_price, cost_price, profit, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([
                $orderNo,
                $user['id'],
                $product['id'],
                $product['upstream_sign'],
                $qq,
                $quantity,
                $feedId !== '' ? $feedId : null,
                $isDelayed ? 1 : 0,
                json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $fromApi ? 'api' : 'web',
                '处理中',
                $price['sell_price'],
                $price['base_cost'],
                $price['profit'],
                now(),
                now(),
            ]);
            $orderId = (int) $this->pdo->lastInsertId();
            if ($ownsCreateTransaction) {
                $this->pdo->commit();
            } else {
                $this->pdo->exec('RELEASE SAVEPOINT order_create');
            }
        } catch (\Throwable $e) {
            if ($ownsCreateTransaction && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            } elseif ($this->pdo->inTransaction()) {
                $this->pdo->exec('ROLLBACK TO SAVEPOINT order_create');
                $this->pdo->exec('RELEASE SAVEPOINT order_create');
            }
            throw $e;
        }

        try {
            $upstream = $this->upstream->createOrder($upstreamParams);
            if ((int) ($upstream['code'] ?? 500) !== 200) {
                throw new RuntimeException((string) ($upstream['msg'] ?? '上游下单失败'));
            }
            $payload = (array) ($upstream['data'] ?? []);
            $this->pdo->prepare('UPDATE orders SET upstream_order_no = ?, upstream_state = ?, message = ?, last_sync_at = ?, updated_at = ? WHERE id = ?')->execute([
                $payload['orderid'] ?? null,
                '已创建',
                $upstream['msg'] ?? '',
                now(),
                now(),
                $orderId,
            ]);
        } catch (\Throwable $e) {
            // When an exchange-code redemption owns the outer transaction, let the
            // caller roll back the order and balance deduction atomically.
            if (!$ownsCreateTransaction && $this->pdo->inTransaction()) {
                throw $e;
            }
            $this->pdo->beginTransaction();
            try {
                (new BalanceService())->adjust((int) $user['id'], (int) $price['sell_price'], 'refund', '上游下单失败自动退回', 'order', $orderNo);
                $this->pdo->prepare('UPDATE orders SET state = ?, upstream_state = ?, message = ?, can_retry = 0, can_refund = 0, updated_at = ? WHERE id = ?')->execute(['失败', '创建失败', mb_substr($e->getMessage(), 0, 250), now(), $orderId]);
                $this->pdo->commit();
            } catch (\Throwable $refundError) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                $manualMessage = '上游下单失败且自动退回额度失败，请管理员人工核对处理';
                try {
                    $this->pdo->prepare("UPDATE orders SET state = '失败', upstream_state = '创建失败', refund_status = 'manual', can_retry = 0, can_refund = 0, message = ?, updated_at = ? WHERE id = ?")
                        ->execute([$manualMessage, now(), $orderId]);
                } catch (\Throwable $stateError) {
                    Logger::write('critical', 'order', '记录订单人工处理状态失败', ['order_no' => $orderNo, 'error' => $stateError->getMessage()]);
                }
                Logger::write('critical', 'order', '上游下单失败且自动退款失败', ['order_no' => $orderNo, 'error' => $refundError->getMessage(), 'message' => $manualMessage]);
            }
            throw $e;
        }

        (new UserGroupService())->evaluateAndUpdate((int) $user['id']);
        (new InviteService())->refreshValidInviteForUser((int) $user['id']);
        return $this->getById($orderId, (int) $user['id'], false);
    }

    public function list(array $user, bool $admin = false): array
    {
        if ($admin) {
            $rows = $this->pdo->query('SELECT o.*, u.username, u.nickname, p.name AS product_name FROM orders o LEFT JOIN users u ON u.id = o.user_id LEFT JOIN products p ON p.id = o.product_id ORDER BY o.id DESC LIMIT 500')->fetchAll();
            return array_map(fn (array $row): array => $this->normalize($row), $rows);
        }
        $stmt = $this->pdo->prepare('SELECT o.*, p.name AS product_name FROM orders o LEFT JOIN products p ON p.id = o.product_id WHERE o.user_id = ? ORDER BY o.id DESC LIMIT 200');
        $stmt->execute([$user['id']]);
        return array_map(fn (array $row): array => $this->normalize($row), $stmt->fetchAll());
    }

    public function getById(int $id, int $viewerId, bool $admin = false, bool $forceSync = false): array
    {
        $order = $this->fetchOrderRow($id);
        if (!$order) {
            throw new RuntimeException('订单不存在');
        }
        if (!$admin && (int) $order['user_id'] !== $viewerId) {
            throw new RuntimeException('无权查看该订单');
        }
        if (($forceSync && $this->shouldSyncOrder($order)) || $this->shouldSyncOnRead($order, $admin)) {
            return $this->syncOrder($order);
        }
        return $this->normalize($order);
    }

    public function findForActor(array $actor, string $bid, bool $admin = false, bool $forceSync = false): array
    {
        $bid = trim($bid);
        if ($bid === '') {
            throw new RuntimeException('订单号不能为空');
        }
        $sql = 'SELECT id FROM orders WHERE (upstream_order_no = ? OR order_no = ?)';
        $params = [$bid, $bid];
        if (!$admin) {
            $sql .= ' AND user_id = ?';
            $params[] = $actor['id'];
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $id = (int) $stmt->fetchColumn();
        if ($id <= 0) {
            throw new RuntimeException('订单不存在');
        }
        return $this->getById($id, (int) $actor['id'], $admin, $forceSync);
    }


    public function getByOrderNo(string $orderNo): array
    {
        $orderNo = trim($orderNo);
        if ($orderNo === '') {
            throw new RuntimeException('订单号不能为空');
        }
        $stmt = $this->pdo->prepare('SELECT o.*, p.name AS product_name FROM orders o LEFT JOIN products p ON p.id = o.product_id WHERE o.order_no = ? LIMIT 1');
        $stmt->execute([$orderNo]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('订单不存在');
        }
        return $row;
    }

    public function getPublicByOrderNo(string $orderNo): array
    {
        $order = $this->getByOrderNo($orderNo);
        if ($this->shouldSyncOrder($order)) {
            return $this->syncOrder($order);
        }
        return $this->normalize($order);
    }

    public function syncPendingOrders(): array
    {
        $rows = $this->pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 300')->fetchAll();
        $count = 0;
        foreach ($rows as $row) {
            if (!$this->shouldSyncOrder($row)) {
                continue;
            }
            $this->syncOrder($row);
            $count++;
        }
        return ['count' => $count];
    }

    public function retry(array $actor, int $orderId, bool $admin = false): array
    {
        $order = $this->getById($orderId, (int) $actor['id'], $admin);
        if ((int) $order['retry_count'] >= 1) {
            throw new RuntimeException('该订单已经补单过一次');
        }
        if (($order['state'] ?? '') !== '失败') {
            throw new RuntimeException('当前订单状态不支持补单');
        }
        if (empty($order['upstream_order_no'])) {
            throw new RuntimeException('上游订单号为空，无法补单');
        }
        $resp = $this->upstream->retryOrder((string) $order['upstream_order_no']);
        $this->pdo->prepare('INSERT INTO order_actions (order_id, action_type, result_code, result_message, payload_json, admin_user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)')
            ->execute([$orderId, 'retry', (string) ($resp['code'] ?? ''), (string) ($resp['msg'] ?? ''), json_encode($resp, JSON_UNESCAPED_UNICODE), $admin ? $actor['id'] : null, now()]);
        if ((int) ($resp['code'] ?? 500) !== 200) {
            throw new RuntimeException((string) ($resp['msg'] ?? '补单失败'));
        }
        $this->pdo->prepare('UPDATE orders SET retry_count = retry_count + 1, state = ?, upstream_state = ?, message = ?, can_retry = 0, last_sync_at = ?, updated_at = ? WHERE id = ?')
            ->execute(['补单中', '补单中', '因忘记开权限或者其他原因导致失败的，可申请补单一次，补单后还失败的将不再支持再次补单。', now(), now(), $orderId]);
        return $this->getById($orderId, (int) $actor['id'], $admin);
    }

    public function refund(array $actor, int $orderId, bool $admin = false, bool $manualOnly = false): array
    {
        $order = $this->getById($orderId, (int) $actor['id'], $admin);
        $refundStatus = (string) ($order['refund_status'] ?? 'none');
        $manualRecoveryMessage = '上游下单失败且自动退回额度失败，请管理员人工核对处理';
        $manualUpstreamMessage = '上游退款已成功，但本地返还额度失败，请管理员人工核对处理';
        $manualRecovery = $manualOnly && $refundStatus === 'manual' && (string) ($order['message'] ?? '') === $manualRecoveryMessage;
        if ($manualOnly && !$admin) {
            throw new RuntimeException('仅退款只能由管理员执行');
        }
        if (!$manualOnly && $refundStatus !== 'none') {
            throw new RuntimeException('该订单正在退款或已退款');
        }
        if ($manualOnly && $refundStatus !== 'none' && !$manualRecovery) {
            if ($refundStatus === 'manual' && (string) ($order['message'] ?? '') === $manualUpstreamMessage) {
                throw new RuntimeException('该订单已被上游受理退款，请勿重复返还额度；请先完成人工核对');
            }
            throw new RuntimeException('该订单正在退款或已退款');
        }
        if (!$manualOnly && (int) ($order['can_refund'] ?? 0) !== 1) {
            throw new RuntimeException('当前订单状态不支持退款');
        }
        if (!$manualOnly && empty($order['upstream_order_no'])) {
            throw new RuntimeException('上游订单号为空，无法退款');
        }

        $claimCondition = $manualRecovery
            ? "id = ? AND refund_status = 'manual' AND message = ?"
            : "id = ? AND refund_status = 'none'";
        $claimed = $this->pdo->prepare("UPDATE orders SET refund_status = 'processing', updated_at = ? WHERE {$claimCondition}");
        $claimParams = $manualRecovery ? [now(), $orderId, $manualRecoveryMessage] : [now(), $orderId];
        $claimed->execute($claimParams);
        if ($claimed->rowCount() !== 1) {
            throw new RuntimeException('订单退款正在处理中，请勿重复提交');
        }

        if ($manualOnly) {
            try {
                $this->pdo->beginTransaction();
                (new BalanceService())->adjust((int) $order['user_id'], (int) $order['user_price'], 'refund', '管理员仅退款', 'order', (string) $order['order_no']);
                $this->pdo->prepare("UPDATE orders SET state = '已退款', upstream_state = ?, refund_status = 'done', can_retry = 0, can_refund = 0, finished_at = ?, last_sync_at = ?, updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                    ->execute(['已退款', now(), now(), now(), $orderId]);
                $this->pdo->prepare('INSERT INTO order_actions (order_id, action_type, result_code, result_message, admin_user_id, created_at) VALUES (?, ?, ?, ?, ?, ?)')
                    ->execute([$orderId, 'manual_refund', '200', '管理员仅退款', $actor['id'], now()]);
                $this->pdo->commit();
            } catch (\Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                $resetStatus = $manualRecovery ? 'manual' : 'none';
                $this->pdo->prepare("UPDATE orders SET refund_status = ?, updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                    ->execute([$resetStatus, now(), $orderId]);
                throw $e;
            }
            return $this->getById($orderId, (int) $actor['id'], true);
        }

        $upstreamAccepted = false;
        try {
            $resp = $this->upstream->refundOrder((string) $order['upstream_order_no']);
            $this->pdo->prepare('INSERT INTO order_actions (order_id, action_type, result_code, result_message, payload_json, admin_user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)')
                ->execute([$orderId, 'refund', (string) ($resp['code'] ?? ''), (string) ($resp['msg'] ?? ''), json_encode($resp, JSON_UNESCAPED_UNICODE), $admin ? $actor['id'] : null, now()]);
            if ((int) ($resp['code'] ?? 500) !== 200) {
                $this->pdo->prepare("UPDATE orders SET refund_status = 'none', updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                    ->execute([now(), $orderId]);
                throw new RuntimeException((string) ($resp['msg'] ?? '退款失败'));
            }
            $upstreamAccepted = true;
            $this->pdo->beginTransaction();
            (new BalanceService())->adjust((int) $order['user_id'], (int) $order['user_price'], 'refund', '订单退款返还', 'order', (string) $order['order_no']);
            $this->pdo->prepare("UPDATE orders SET state = '已退款', upstream_state = ?, refund_status = 'done', can_retry = 0, can_refund = 0, finished_at = ?, last_sync_at = ?, updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                ->execute(['已退款', now(), now(), now(), $orderId]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            if ($upstreamAccepted) {
                $this->pdo->prepare("UPDATE orders SET refund_status = 'manual', can_refund = 0, message = ?, updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                    ->execute(['上游退款已成功，但本地返还额度失败，请管理员人工核对处理', now(), $orderId]);
                Logger::write('critical', 'refund', '上游退款成功但本地入账失败', ['order_id' => $orderId, 'order_no' => $order['order_no'], 'error' => $e->getMessage(), 'upstream_order_no' => $order['upstream_order_no']], (int) $actor['id']);
                throw new RuntimeException('上游退款已受理，但本地返还额度失败，已转人工处理，请联系管理员');
            }
            $this->pdo->prepare("UPDATE orders SET refund_status = 'none', updated_at = ? WHERE id = ? AND refund_status = 'processing'")
                ->execute([now(), $orderId]);
            throw $e;
        }
        return $this->getById($orderId, (int) $actor['id'], $admin);
    }

    private function syncOrder(array $order): array
    {
        if (empty($order['upstream_order_no'])) {
            return $this->normalize($order);
        }
        $resp = $this->upstream->queryOrder((string) $order['upstream_order_no']);
        if ((int) ($resp['code'] ?? 500) !== 200) {
            return $this->normalize($order);
        }

        $data = (array) ($resp['data'] ?? []);
        $remoteState = (string) ($data['state'] ?? $order['state']);
        $refundStatus = (string) ($order['refund_status'] ?? 'none');
        $state = $refundStatus === 'done' ? '已退款' : $remoteState;
        $canRetry = $state === '失败' && $refundStatus !== 'done' && (int) $order['retry_count'] < 1 ? 1 : 0;
        $canRefund = $refundStatus === 'done' ? 0 : (in_array($state, ['失败', '未开始', '处理中', '补单中'], true) ? 1 : 0);
        $message = trim((string) ($data['message'] ?? ''));
        if ($message === '') {
            $message = '无';
        }
        $this->pdo->prepare('UPDATE orders SET state=?, upstream_state=?, message=?, start_num=?, current_num=?, finish_num=?, started_at=?, finished_at=?, last_sync_at=?, can_retry=?, can_refund=?, updated_at=? WHERE id=?')->execute([
            $state,
            $remoteState,
            $message,
            $data['snum'] ?? null,
            $data['nnum'] ?? null,
            $data['enum'] ?? null,
            !empty($data['stime']) ? date('Y-m-d H:i:s', (int) $data['stime']) : null,
            !empty($data['etime']) ? date('Y-m-d H:i:s', (int) $data['etime']) : ($refundStatus === 'done' ? ($order['finished_at'] ?: now()) : null),
            now(),
            $canRetry,
            $canRefund,
            now(),
            $order['id'],
        ]);
        $fresh = $this->fetchOrderRow((int) $order['id']);
        return $this->normalize($fresh ?: $order);
    }

    private function findGroup(int $groupId): array
    {
        foreach ((new UserGroupService())->all() as $group) {
            if ((int) $group['id'] === $groupId) {
                return $group;
            }
        }
        throw new RuntimeException('用户组不存在');
    }

    private function resolveLimit(mixed $input, int $default): int
    {
        if ($input === null || $input === '') {
            return $default;
        }
        if (!is_numeric((string) $input)) {
            throw new RuntimeException('限价格式不正确');
        }
        $limit = (int) $input;
        if ($limit <= 0) {
            throw new RuntimeException('限价必须大于0');
        }
        return $limit;
    }

    private function collectDeclaredInput(array $product, array $data, string $qq, int $quantity, string $feedId): array
    {
        $extra = [];
        $declared = (array) ($product['input'] ?? []);
        foreach ($declared as $definition) {
            if (!is_array($definition) || empty($definition['name'])) {
                continue;
            }
            $name = (string) $definition['name'];
            $type = strtolower((string) ($definition['type'] ?? 'text'));
            $value = match ($type) {
                'qq' => $qq,
                'setnum', 'number', 'num' => $quantity,
                'feed' => $feedId,
                default => $data[$name] ?? null,
            };
            $required = !array_key_exists('required', $definition) || (bool) $definition['required'];
            if ($required && ($value === null || trim((string) $value) === '')) {
                throw new RuntimeException((string) ($definition['title'] ?? $name) . '不能为空');
            }
            if ($value !== null && !is_array($value) && strlen((string) $value) > 500) {
                throw new RuntimeException((string) ($definition['title'] ?? $name) . '长度过长');
            }
            if ($value !== null) {
                $extra[$name] = is_scalar($value) ? (string) $value : $value;
            }
        }
        $extra['qq'] = $qq;
        $extra['num'] = $quantity;
        if ($feedId !== '') {
            $extra['feed_id'] = $feedId;
        }
        return $extra;
    }

    private function fetchOrderRow(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT o.*, u.username, u.nickname, p.name AS product_name FROM orders o LEFT JOIN users u ON u.id = o.user_id LEFT JOIN products p ON p.id = o.product_id WHERE o.id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private function shouldSyncOnRead(array $order, bool $admin): bool
    {
        if ($admin) {
            return false;
        }
        return $this->shouldSyncOrder($order);
    }

    private function shouldSyncOrder(array $order): bool
    {
        if (empty($order['upstream_order_no'])) {
            return false;
        }
        $state = (string) ($order['state'] ?? '');
        $refundStatus = (string) ($order['refund_status'] ?? 'none');
        if ($refundStatus === 'processing') {
            return true;
        }
        if (in_array($state, ['补单中', '退款处理中'], true)) {
            return true;
        }
        if (!in_array($state, ['已完成', '失败', '已退款', '临时暂停'], true)) {
            return true;
        }
        return $state === '失败' && ((int) ($order['retry_count'] ?? 0) > 0 || $refundStatus !== 'none');
    }

    private function normalize(array $row): array
    {
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['user_id'] = (int) ($row['user_id'] ?? 0);
        $row['product_id'] = (int) ($row['product_id'] ?? 0);
        $row['quantity'] = (int) ($row['quantity'] ?? 0);
        $row['user_price'] = (int) ($row['user_price'] ?? 0);
        $row['cost_price'] = (int) ($row['cost_price'] ?? 0);
        $row['profit'] = (int) ($row['profit'] ?? 0);
        $row['retry_count'] = (int) ($row['retry_count'] ?? 0);
        $row['can_retry'] = (int) ($row['can_retry'] ?? 0);
        $row['can_refund'] = (int) ($row['can_refund'] ?? 0);
        $row['extra_input'] = json_array($row['extra_input_json'] ?? '[]');
        $row['display_order_no'] = (string) ($row['order_no'] ?? '');
        $row['latest_message'] = (string) ($row['message'] ?? '');
        return $row;
    }
}
