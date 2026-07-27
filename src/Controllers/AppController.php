<?php
declare(strict_types=1);

namespace XiaoMiSlop\Controllers;

use RuntimeException;
use XiaoMiSlop\Core\Database;
use XiaoMiSlop\Core\Request;
use XiaoMiSlop\Core\Response;
use XiaoMiSlop\Services\ApiAccessService;
use XiaoMiSlop\Services\AuthService;
use XiaoMiSlop\Services\BalanceService;
use XiaoMiSlop\Services\CardService;
use XiaoMiSlop\Services\CaptchaService;
use XiaoMiSlop\Services\InviteService;
use XiaoMiSlop\Services\OrderService;
use XiaoMiSlop\Services\PaymentService;
use XiaoMiSlop\Services\ProductService;
use XiaoMiSlop\Services\SettingsService;
use XiaoMiSlop\Services\UpstreamClient;
use XiaoMiSlop\Services\UserGroupService;
use XiaoMiSlop\Support\Logger;

final class AppController
{
    private AuthService $auth;
    private SettingsService $settings;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->settings = new SettingsService();
    }

    public function dispatch(Request $request): mixed
    {
        $path = $request->path();
        if ($path === '/captcha/image') return $this->captcha();
        if ($path === '/proxy/image') return $this->proxyImage((string) $request->input('url', ''));
        if ($path === '/internal/recharge/notify') return $this->rechargeNotify($request);
        if (str_starts_with($path, '/api/')) return $this->api($request, substr($path, 5));
        if (str_starts_with($path, '/user/api/')) return $this->userApi($request, substr($path, 9));
        if ($path === '/card/redeem' && $request->method() === 'POST') return $this->redeemCard($request);
        $adminPath = trim((string) $this->settings->get('admin_path', '/admin'), '/');
        if ($adminPath !== '') {
            $adminPrefix = '/' . $adminPath . '/api/';
            if (str_starts_with($path, $adminPrefix)) return $this->adminApi($request, substr($path, strlen($adminPrefix)));
        }
        if ($path === '/auth/login' && $request->method() === 'POST') return $this->login($request);
        if ($path === '/auth/register' && $request->method() === 'POST') return $this->register($request);
        if ($path === '/auth/logout') { $this->auth->logout(); return Response::success(null, '已退出登录'); }
        if ($path === '/api-docs') return $this->docs();
        return $this->htmlShell();
    }

    private function htmlShell(): mixed
    {
        $user = $this->auth->currentUser();
        $siteName = (string) $this->settings->get('site_name', '小米速刷系统');
        $siteKeywords = (string) $this->settings->get('site_keywords', '速刷,对接,短信,充值');
        $siteDescription = (string) $this->settings->get('site_description', '支持上游对接加价售卖的现代化速刷系统');
        $siteFavicon = trim((string) $this->settings->get('site_favicon', ''));
        $siteLogo = trim((string) $this->settings->get('site_logo', ''));
        $seoFooter = trim((string) $this->settings->get('seo_footer', ''));

        $html = file_get_contents(view_path('app.php')) ?: '<!doctype html><html><body>视图缺失</body></html>';
        $brandMarkup = $siteLogo !== ''
            ? '<span class="logo logo-image"><img src="' . htmlspecialchars($siteLogo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '"></span><span>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '</span>'
            : '<div class="logo">米</div><span>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '</span>';
        $faviconTag = $siteFavicon !== ''
            ? '<link rel="icon" href="' . htmlspecialchars($siteFavicon, ENT_QUOTES, 'UTF-8') . '">'
            : '';
        $footerBlock = $seoFooter !== ''
            ? '<footer class="site-footer">' . nl2br(htmlspecialchars($seoFooter, ENT_QUOTES, 'UTF-8')) . '</footer>'
            : '';

        $html = str_replace('__SITE_NAME__', htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_DESCRIPTION__', htmlspecialchars($siteDescription, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_KEYWORDS__', htmlspecialchars($siteKeywords, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_FAVICON_TAG__', $faviconTag, $html);
        $html = str_replace('__SITE_BRAND__', $brandMarkup, $html);
        $html = str_replace('__SEO_FOOTER_BLOCK__', $footerBlock, $html);
        $html = str_replace('__BOOT__', json_encode([
            'user' => $user,
            'adminPath' => $this->settings->get('admin_path', '/admin'),
            'currency' => $this->settings->get('currency_name', '额度'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $html);
        return Response::html($html);
    }

    private function login(Request $request): mixed
    {
        try {
            $data = $request->all();
            $admin = !empty($data['admin']);
            if ($admin || $this->settings->get('login_need_image_captcha', '0') === '1') {
                if (!(new CaptchaService())->verify((string) ($data['captcha'] ?? ''))) throw new RuntimeException('图片验证码错误或已过期');
            }
            $user = $this->auth->login(trim((string) ($data['username'] ?? '')), (string) ($data['password'] ?? ''), $admin);
            return Response::success($user, '登录成功');
        } catch (\Throwable $e) { return Response::error($e->getMessage(), 422); }
    }

    private function register(Request $request): mixed
    {
        try {
            $data = $request->all();
            if ($this->settings->get('register_need_image_captcha', '1') === '1' && !(new CaptchaService())->verify((string) ($data['captcha'] ?? ''))) {
                throw new RuntimeException('图片验证码错误或已过期');
            }
            $user = $this->auth->register($data);
            return Response::success($user, '注册成功');
        } catch (\Throwable $e) { return Response::error($e->getMessage(), 422); }
    }

    private function captcha(): mixed
    {
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo (new CaptchaService())->svg();
        exit;
    }

    private function api(Request $request, string $action): mixed
    {
        try {
            $user = $this->apiUser($request);
            $access = (new ApiAccessService())->status($user);
            $action = trim($action, '/');
            if ($action === 'success') return $this->apiResponse(200, '接口调用成功', ['allow' => $access['allow']]);
            if (!$access['allow']) return $this->apiResponse(403, '当前账号不允许对接', []);
            return match ($action) {
                'getBalance', 'get_balance' => $this->apiResponse(200, '接口调用成功', ['amount' => (int) $user['balance']]),
                'queryGoods', 'query_goods' => $this->apiGoods($user),
                'createOrder', 'create_order' => $this->apiCreateOrder($user, $request),
                'retryOrder', 'retry_order' => $this->apiRetry($user, $request),
                'refundOrder', 'refund_order' => $this->apiRefund($user, $request),
                'queryOrder', 'query_order' => $this->apiQueryOrder($user, $request),
                'orderList', 'order_list' => $this->apiOrderList($user, $request),
                'queryFeed', 'query_feed' => $this->apiFeed($user, $request),
                default => $this->apiResponse(404, '接口不存在', []),
            };
        } catch (\Throwable $e) {
            Logger::write('error', 'api', $e->getMessage(), ['action' => $action]);
            return $this->apiResponse(500, $e->getMessage(), []);
        }
    }

    private function apiUser(Request $request): array
    {
        $uid = (int) $request->input('uid', 0);
        $key = trim((string) $request->input('api_key', ''));
        if ($uid <= 0 || $key === '') throw new RuntimeException('uid 与 api_key 不对应，鉴权失败');
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT u.*, g.name AS group_name FROM users u LEFT JOIN user_groups g ON g.id = u.user_group_id WHERE u.uid = ? AND u.api_key = ? AND u.status <> "deleted" LIMIT 1');
        $stmt->execute([$uid, $key]);
        $user = $stmt->fetch();
        if (!$user) throw new RuntimeException('uid 与 api_key 不对应，鉴权失败');
        return $user;
    }

    private function apiResponse(int $code, string $msg, mixed $data): mixed
    {
        Response::json(['code' => $code, 'msg' => $msg, 'data' => $data, 'time' => time()], $code === 200 ? 200 : 200);
    }

    private function apiGoods(array $user): mixed
    {
        $rows = (new ProductService())->list($user, true);
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'name' => $row['name'], 'min' => $row['min_num'], 'max' => $row['max_num'], 'step' => $row['step_num'],
                'steps' => $row['steps'], 'input' => $row['input'], 'desc' => $row['desc'], 'min_delayed' => $row['min_delayed'],
                'price' => (new \XiaoMiSlop\Services\PricingService())->calculate($row, $this->group($user), max(1, $row['step_num']))['sell_price'],
                'price_delayed' => $row['price_cost_delayed'], 'level' => $row['upstream_level'], 'sign' => $row['upstream_sign'],
            ];
        }
        return $this->apiResponse(200, '接口调用成功', $data);
    }

    private function apiCreateOrder(array $user, Request $request): mixed
    {
        $order = (new OrderService())->create($user, $request->all(), true);
        return $this->apiResponse(200, '接口调用成功', ['orderid' => $order['upstream_order_no'] ?: $order['order_no'], 'price' => $order['user_price'], 'num' => $order['quantity'], 'sign' => $order['upstream_sign'], 'feed_id' => $order['feed_id']]);
    }

    private function apiRetry(array $user, Request $request): mixed
    {
        $bid = trim((string) $request->input('bid', ''));
        $order = $this->findOrderForUser($user, $bid);
        (new OrderService())->retry($user, (int) $order['id']);
        return $this->apiResponse(200, '补单申请成功', ['bid' => $bid]);
    }

    private function apiRefund(array $user, Request $request): mixed
    {
        $bid = trim((string) $request->input('bid', ''));
        $order = $this->findOrderForUser($user, $bid);
        (new OrderService())->refund($user, (int) $order['id']);
        return $this->apiResponse(200, '退款申请成功', ['bid' => $bid]);
    }

    private function apiQueryOrder(array $user, Request $request): mixed
    {
        $bid = trim((string) $request->input('bid', ''));
        $order = $this->findOrderForUser($user, $bid);
        $row = (new OrderService())->getById((int) $order['id'], (int) $user['id']);
        return $this->apiResponse(200, '接口调用成功', $this->orderPayload($row));
    }

    private function apiOrderList(array $user, Request $request): mixed
    {
        $rows = (new OrderService())->list($user);
        $data = array_map(fn (array $row) => $this->orderPayload($row), $rows);
        return $this->apiResponse(200, '接口调用成功', $data);
    }

    private function apiFeed(array $user, Request $request): mixed
    {
        $qq = trim((string) ($request->input('uin', $request->input('qq', ''))));
        if ($qq === '') throw new RuntimeException('缺少QQ号');
        return $this->apiResponse(200, '接口调用成功', (new UpstreamClient())->queryFeed($qq)['data'] ?? []);
    }

    private function findOrderForUser(array $user, string $bid): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE user_id = ? AND (upstream_order_no = ? OR order_no = ?) LIMIT 1');
        $stmt->execute([$user['id'], $bid, $bid]);
        $row = $stmt->fetch();
        if (!$row) throw new RuntimeException('订单不存在');
        return $row;
    }

    private function orderPayload(array $row): array
    {
        return ['orderid' => $row['upstream_order_no'] ?: $row['order_no'], 'bid' => $row['upstream_order_no'] ?: $row['order_no'], 'state' => $row['state'], 'message' => $row['message'], 'snum' => $row['start_num'], 'nnum' => $row['current_num'], 'enum' => $row['finish_num'], 'stime' => $row['started_at'] ? strtotime((string) $row['started_at']) : null, 'etime' => $row['finished_at'] ? strtotime((string) $row['finished_at']) : null, 'price' => $row['user_price'], 'num' => $row['quantity'], 'sign' => $row['upstream_sign'], 'feed_id' => $row['feed_id']];
    }

    private function userApi(Request $request, string $action): mixed
    {
        try {
            $user = $this->auth->requireUser();
            $action = trim($action, '/');
            $data = $request->all();
            $orders = new OrderService();
            $result = match (true) {
                $action === 'products' => (new ProductService())->list($user, false),
                $action === 'orders' && $request->method() === 'GET' => $orders->list($user, false),
                $action === 'order/price' => $orders->quote($user, $data, false),
                $action === 'order/create' && $request->method() === 'POST' => $orders->create($user, $data, false),
                $action === 'order/detail' => $orders->findForActor($user, trim((string) ($data['bid'] ?? $data['order_no'] ?? '')), false),
                $action === 'order/retry' && $request->method() === 'POST' => $orders->retry($user, (int) $orders->findForActor($user, trim((string) ($data['bid'] ?? '')), false)['id'], false),
                $action === 'order/refund' && $request->method() === 'POST' => $orders->refund($user, (int) $orders->findForActor($user, trim((string) ($data['bid'] ?? '')), false)['id'], false, false),
                $action === 'feed' => $this->userFeed($user, $data),
                $action === 'groups' => (new UserGroupService())->all(),
                $action === 'payments' => ['channels' => (new PaymentService())->channels()],
                $action === 'recharge/create' && $request->method() === 'POST' => (new PaymentService())->createRecharge($user, (int) ($data['channel_id'] ?? 0), (int) ($data['credit_amount'] ?? 0)),
                $action === 'recharge/orders' => $this->userRechargeOrders($user),
                $action === 'invites' => (new InviteService())->list((int) $user['id']),
                $action === 'invite/create' && $request->method() === 'POST' => (new InviteService())->create((int) $user['id'], (int) ($data['length'] ?? 20), trim((string) ($data['code'] ?? '')) ?: null, false),
                default => throw new RuntimeException('用户接口不存在'),
            };
            return Response::success($result, '操作成功');
        } catch (\Throwable $e) { return Response::error($e->getMessage(), 422); }
    }

    private function userFeed(array $user, array $data): array
    {
        $qq = trim((string) ($data['qq'] ?? $data['uin'] ?? $user['qq'] ?? ''));
        if ($qq === '') throw new RuntimeException('缺少QQ号');
        $feed = (new UpstreamClient())->queryFeed($qq)['data'] ?? [];
        $mode = (string) $this->settings->get('feed_image_mode', 'self_proxy');
        if (is_array($feed)) {
            $feed = array_map(function (mixed $item) use ($mode): mixed {
                if (!is_array($item)) return $item;
                $images = (array) ($item['images'] ?? []);
                foreach ($images as &$image) {
                    if (!is_array($image)) continue;
                    $source = $mode === 'upstream_proxy' ? ($image['proxy'] ?? $image['original'] ?? '') : ($image['original'] ?? $image['proxy'] ?? '');
                    $image['display'] = $source !== '' && $mode !== 'upstream_proxy' ? '/proxy/image?url=' . rawurlencode((string) $source) : $source;
                }
                unset($image);
                $item['images'] = $images;
                $item['is_possible_repost'] = trim((string) ($item['content'] ?? '')) === '' && count($images) === 0;
                return $item;
            }, $feed);
        }
        return (array) $feed;
    }

    private function userRechargeOrders(array $user): array
    {
        $stmt = Database::connection()->prepare('SELECT r.*, c.name AS channel_name, m.name AS merchant_name FROM recharge_orders r LEFT JOIN payment_channels c ON c.id = r.channel_id LEFT JOIN payment_merchants m ON m.id = r.merchant_id WHERE r.user_id = ? ORDER BY r.id DESC LIMIT 100');
        $stmt->execute([$user['id']]);
        return $stmt->fetchAll();
    }

    private function redeemCard(Request $request): mixed
    {
        try {
            $user = $this->auth->requireUser();
            $result = (new CardService())->redeem((int) $user['id'], trim((string) ($request->input('code', ''))), $request->ip());
            return Response::success($result, '卡密充值成功');
        } catch (\Throwable $e) { return Response::error($e->getMessage(), 422); }
    }

    private function adminApi(Request $request, string $action): mixed
    {
        try {
            $admin = $this->auth->requireAdmin();
            $action = trim($action, '/');
            $data = $request->all();
            $pdo = Database::connection();
            $result = match (true) {
                $action === 'dashboard' => $this->dashboard(),
                $action === 'products' && $request->method() === 'GET' => (new ProductService())->adminList(),
                $action === 'products/sync' => (new ProductService())->syncFromUpstream(),
                $action === 'products/save' => (new ProductService())->saveProduct($data),
                $action === 'groups' && $request->method() === 'GET' => (new UserGroupService())->all(),
                $action === 'groups/save' => (new UserGroupService())->save($data),
                $action === 'groups/default' => (new UserGroupService())->setDefault((int) ($data['id'] ?? 0)),
                $action === 'users' => (new AuthService())->listUsers($data),
                $action === 'users/save' => (new AuthService())->saveUser($admin, $data),
                $action === 'users/delete' => (new AuthService())->softDeleteUser((int) ($data['id'] ?? 0)),
                $action === 'users/reset-key' => ['api_key' => (new AuthService())->resetApiKey((int) ($data['id'] ?? 0))],
                $action === 'orders' => (new OrderService())->list($admin, true),
                $action === 'orders/sync' => (new OrderService())->syncPendingOrders(),
                $action === 'orders/retry' => (new OrderService())->retry($admin, (int) ($data['id'] ?? 0), true),
                $action === 'orders/refund' => (new OrderService())->refund($admin, (int) ($data['id'] ?? 0), true, false),
                $action === 'orders/manual-refund' => (new OrderService())->refund($admin, (int) ($data['id'] ?? 0), true, true),
                $action === 'cards' && $request->method() === 'GET' => (new CardService())->list(),
                $action === 'cards/generate' => (new CardService())->generate((int) $admin['id'], (int) ($data['count'] ?? 1), (int) ($data['amount'] ?? 0), (int) ($data['uses'] ?? 1), (string) ($data['prefix'] ?? ''), (string) ($data['note'] ?? '')),
                $action === 'cards/save' => (new CardService())->save($data),
                $action === 'cards/destroy' => (new CardService())->destroy((int) ($data['id'] ?? 0)),
                $action === 'payments' => ['merchants' => (new PaymentService())->merchants(), 'channels' => (new PaymentService())->channels()],
                $action === 'payments/merchant' => (new PaymentService())->saveMerchant($data),
                $action === 'payments/channel' => (new PaymentService())->saveChannel($data),
                $action === 'settings' && $request->method() === 'GET' => $this->settings->all(),
                $action === 'settings/save' => $this->saveSettings($data),
                $action === 'upstream' && $request->method() === 'GET' => $pdo->query('SELECT id,name,base_url,upstream_uid,enabled,is_default,options_json,created_at,updated_at FROM upstream_accounts ORDER BY id DESC')->fetchAll(),
                $action === 'upstream/save' => $this->saveUpstream($data),
                $action === 'upstream/balance' => (new UpstreamClient())->getBalance(),
                $action === 'logs' => $pdo->query('SELECT * FROM system_logs ORDER BY id DESC LIMIT 300')->fetchAll(),
                default => throw new RuntimeException('后台接口不存在'),
            };
            return Response::success($result, '操作成功');
        } catch (\Throwable $e) {
            Logger::write('error', 'admin', $e->getMessage(), ['action' => $action]);
            return Response::error($e->getMessage(), 422);
        }
    }

    private function dashboard(): array
    {
        $pdo = Database::connection();
        $today = date('Y-m-d 00:00:00');
        $scalar = static function (string $sql, array $params = []) use ($pdo): int { $stmt = $pdo->prepare($sql); $stmt->execute($params); return (int) $stmt->fetchColumn(); };
        $upstream = null;
        try { $upstream = (new UpstreamClient())->getBalance()['data']['amount'] ?? null; } catch (\Throwable) {}
        return ['orders_today' => $scalar('SELECT COUNT(*) FROM orders WHERE created_at >= ?', [$today]), 'users_total' => $scalar('SELECT COUNT(*) FROM users WHERE status <> "deleted"'), 'profit_today' => $scalar('SELECT COALESCE(SUM(profit),0) FROM orders WHERE created_at >= ?', [$today]), 'balance_total' => $scalar('SELECT COALESCE(SUM(balance),0) FROM users WHERE status <> "deleted"'), 'upstream_balance' => $upstream, 'today_consume_rank' => $pdo->query('SELECT u.username,u.nickname,COALESCE(SUM(o.user_price),0) total FROM orders o JOIN users u ON u.id=o.user_id WHERE o.created_at >= "'.date('Y-m-d 00:00:00').'" GROUP BY o.user_id ORDER BY total DESC LIMIT 10')->fetchAll(), 'total_consume_rank' => $pdo->query('SELECT username,nickname,total_consume FROM users WHERE status <> "deleted" ORDER BY total_consume DESC LIMIT 10')->fetchAll(), 'balance_rank' => $pdo->query('SELECT username,nickname,balance FROM users WHERE status <> "deleted" ORDER BY balance DESC LIMIT 10')->fetchAll()];
    }

    private function saveSettings(array $data): array
    {
        unset($data['_token']);
        $allowed = array_keys($this->settings->defaults());
        $jsonKeys = ['sms_config', 'smtp_config', 'geetest_config', 'invite_code_price_rules'];
        $booleanKeys = [
            'frontend_order_enabled', 'api_order_enabled', 'register_need_email', 'register_need_mobile',
            'register_need_image_captcha', 'register_need_geetest', 'register_need_sms_code', 'register_need_email_code',
            'login_need_sms', 'login_need_email', 'login_need_geetest', 'login_need_image_captcha',
            'default_register_strategy_user', 'default_register_strategy_agent', 'balance_downgrade_enabled',
        ];
        foreach ($data as $key => $value) {
            $key = (string) $key;
            if (!in_array($key, $allowed, true)) continue;
            if (in_array($key, $booleanKeys, true)) {
                $value = in_array((string) $value, ['1', 'true', 'on'], true) ? '1' : '0';
            } elseif (in_array($key, $jsonKeys, true)) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                        throw new RuntimeException($key . ' 必须是有效的 JSON 对象');
                    }
                    $value = $decoded;
                }
                if (!is_array($value)) {
                    throw new RuntimeException($key . ' 配置格式不正确');
                }
                if ($key === 'invite_code_price_rules') {
                    $mode = (string) ($value['mode'] ?? 'fixed');
                    if (!in_array($mode, ['fixed', 'length'], true)) throw new RuntimeException('邀请码价格模式不合法');
                    if ($mode === 'fixed') {
                        $fixed = filter_var($value['fixed'] ?? 0, FILTER_VALIDATE_INT);
                        if ($fixed === false || $fixed < 0) throw new RuntimeException('邀请码固定价格不能为负数');
                        $value = ['mode' => 'fixed', 'fixed' => $fixed];
                    } else {
                        $normalized = ['mode' => 'length'];
                        foreach ($value as $length => $price) {
                            if ($length === 'mode') continue;
                            if (!preg_match('/^(?:[6-9]|[1-3][0-9]|4[0-8])$/', (string) $length)) continue;
                            $price = filter_var($price, FILTER_VALIDATE_INT);
                            if ($price === false || $price < 0) throw new RuntimeException('邀请码长度价格必须是非负整数');
                            $normalized[(string) $length] = $price;
                        }
                        $value = $normalized;
                    }
                }
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                $value = is_scalar($value) ? trim((string) $value) : '';
            }
            if ($key === 'currency_name') {
                if ($value === '' || mb_strlen((string) $value) > 20) throw new RuntimeException('额度名称不能为空且不能超过20个字符');
            }
            if ($key === 'admin_path') {
                $path = '/' . trim((string) $value, '/');
                if ($path === '/' || !preg_match('#^/[A-Za-z0-9_-]{2,40}$#', $path)) throw new RuntimeException('后台路径格式不合法');
                $value = $path;
            }
            if ($key === 'feed_image_mode' && !in_array((string) $value, ['self_proxy', 'upstream_proxy'], true)) {
                throw new RuntimeException('说说图片来源设置不合法');
            }
            $this->settings->set($key, (string) $value);
        }
        Logger::write('info', 'settings', '管理员更新系统设置', ['keys' => array_values(array_intersect(array_keys($data), $allowed))]);
        return $this->settings->all();
    }

    private function saveUpstream(array $data): array
    {
        $pdo = Database::connection();
        $id = (int) ($data['id'] ?? 0);
        $name = trim((string) ($data['name'] ?? '默认上游'));
        $baseUrl = rtrim(trim((string) ($data['base_url'] ?? '')), '/');
        $upstreamUid = (int) ($data['upstream_uid'] ?? 0);
        $apiKey = trim((string) ($data['upstream_api_key'] ?? ''));
        $enabled = !empty($data['enabled']) ? 1 : 0;
        $isDefault = !empty($data['is_default']) ? 1 : 0;
        if ($name === '' || mb_strlen($name) > 80) throw new RuntimeException('上游名称不能为空且不能超过80个字符');
        $parts = parse_url($baseUrl);
        if (!$parts || !in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true) || empty($parts['host'])) {
            throw new RuntimeException('上游地址必须是有效的 http/https URL');
        }
        if ($upstreamUid <= 0) throw new RuntimeException('上游 UID 必须大于0');

        if ($id > 0) {
            $currentStmt = $pdo->prepare('SELECT * FROM upstream_accounts WHERE id = ? LIMIT 1');
            $currentStmt->execute([$id]);
            $current = $currentStmt->fetch();
            if (!$current) throw new RuntimeException('上游账号不存在');
            // 前端留空代表不修改密钥，避免管理员编辑普通字段时误清空凭证。
            if ($apiKey === '') $apiKey = (string) $current['upstream_api_key'];
        }
        if ($apiKey === '') throw new RuntimeException('上游 API Key 不能为空');

        $pdo->beginTransaction();
        try {
            if ($isDefault) $pdo->exec('UPDATE upstream_accounts SET is_default = 0');
            if ($id > 0) {
                $pdo->prepare('UPDATE upstream_accounts SET name=?,base_url=?,upstream_uid=?,upstream_api_key=?,enabled=?,is_default=?,options_json=?,updated_at=? WHERE id=?')->execute([$name, $baseUrl, $upstreamUid, $apiKey, $enabled, $isDefault, json_encode($data['options'] ?? [], JSON_UNESCAPED_UNICODE), now(), $id]);
            } else {
                $pdo->prepare('INSERT INTO upstream_accounts (name,base_url,upstream_uid,upstream_api_key,enabled,is_default,options_json,created_at,updated_at) VALUES (?,?,?,?,?,?,?, ?, ?)')->execute([$name, $baseUrl, $upstreamUid, $apiKey, $enabled, $isDefault, json_encode($data['options'] ?? [], JSON_UNESCAPED_UNICODE), now(), now()]);
                $id = (int) $pdo->lastInsertId();
            }
            $default = $pdo->query('SELECT id FROM upstream_accounts WHERE is_default = 1 AND enabled = 1 ORDER BY id ASC LIMIT 1')->fetchColumn();
            if (!$default) {
                $default = $pdo->query('SELECT id FROM upstream_accounts WHERE enabled = 1 ORDER BY id ASC LIMIT 1')->fetchColumn();
            }
            if (!$default) {
                $default = $pdo->query('SELECT id FROM upstream_accounts ORDER BY id ASC LIMIT 1')->fetchColumn();
            }
            if ($default) {
                $pdo->exec('UPDATE upstream_accounts SET is_default = 0');
                $pdo->prepare('UPDATE upstream_accounts SET is_default = 1 WHERE id = ?')->execute([(int) $default]);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
        $stmt = $pdo->prepare('SELECT id,name,base_url,upstream_uid,enabled,is_default,options_json,created_at,updated_at FROM upstream_accounts WHERE id=?');
        $stmt->execute([$id]);
        return (array) $stmt->fetch();
    }

    private function group(array $user): array
    {
        foreach ((new UserGroupService())->all() as $group) if ((int) $group['id'] === (int) $user['user_group_id']) return $group;
        return [];
    }

    private function rechargeNotify(Request $request): mixed
    {
        try { (new PaymentService())->handleNotify($request->all()); echo 'success'; exit; } catch (\Throwable $e) { http_response_code(400); echo 'fail: '.$e->getMessage(); exit; }
    }

    private function proxyImage(string $url): mixed
    {
        $url = trim($url);
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = (string) ($parts['host'] ?? '');
        $port = isset($parts['port']) ? (int) $parts['port'] : ($scheme === 'https' ? 443 : 80);
        if ($url === '' || !in_array($scheme, ['http', 'https'], true) || $host === '' || !in_array($port, [80, 443], true) || !$this->hostResolvesToPublicIp($host)) {
            http_response_code(400);
            exit('bad url');
        }

        $body = '';
        $tooLarge = false;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body, &$tooLarge): int {
                if (strlen($body) + strlen($chunk) > 5 * 1024 * 1024) {
                    $tooLarge = true;
                    return 0;
                }
                $body .= $chunk;
                return strlen($chunk);
            },
        ]);
        $ok = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = (string) (curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '');
        $error = curl_error($ch);
        curl_close($ch);
        if ($tooLarge) { http_response_code(413); exit('image too large'); }
        if ($ok === false || $httpCode < 200 || $httpCode >= 300 || $body === '') { http_response_code(404); exit($error !== '' ? 'image unavailable' : ''); }
        $contentType = strtolower(trim(explode(';', $contentType, 2)[0]));
        if (!str_starts_with($contentType, 'image/')) { http_response_code(415); exit('not an image'); }
        header('Content-Type: ' . $contentType);
        header('Cache-Control: public, max-age=3600');
        echo $body;
        exit;
    }

    private function hostResolvesToPublicIp(string $host): bool
    {
        $host = trim($host, '[]');
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return (bool) filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        }
        if (!preg_match('/^[A-Za-z0-9.-]+$/', $host)) return false;
        $records = @dns_get_record($host, DNS_A | DNS_AAAA);
        if (!is_array($records) || $records === []) return false;
        foreach ($records as $record) {
            $ip = (string) ($record['ip'] ?? $record['ipv6'] ?? '');
            if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return false;
        }
        return true;
    }

    private function docs(): mixed
    {
        $html = '<!doctype html><meta charset="utf-8"><title>接口文档</title><style>body{font-family:system-ui;max-width:980px;margin:40px auto;padding:0 20px;line-height:1.7}code{background:#f3f4f6;padding:2px 5px;border-radius:5px}</style><h1>上游对接兼容接口</h1><p>所有接口返回 <code>{code,msg,data,time}</code>，认证参数为 <code>uid</code> 与 <code>api_key</code>。</p><ul><li>/api/success</li><li>/api/getBalance</li><li>/api/queryGoods</li><li>/api/createOrder</li><li>/api/retryOrder</li><li>/api/refundOrder</li><li>/api/queryOrder</li><li>/api/orderList</li><li>/api/queryFeed</li></ul>';
        return Response::html($html);
    }
}



