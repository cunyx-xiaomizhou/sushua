<?php
declare(strict_types=1);

namespace Sushua\Controllers;

use RuntimeException;
use Sushua\Core\Database;
use Sushua\Core\Request;
use Sushua\Core\Response;
use Sushua\Services\ApiAccessService;
use Sushua\Services\AuthService;
use Sushua\Services\BalanceService;
use Sushua\Services\CardService;
use Sushua\Services\CaptchaService;
use Sushua\Services\InviteService;
use Sushua\Services\OrderService;
use Sushua\Services\PaymentService;
use Sushua\Services\ProductExchangeCodeService;
use Sushua\Services\ProductService;
use Sushua\Services\SettingsService;
use Sushua\Services\UpstreamClient;
use Sushua\Services\UserGroupService;
use Sushua\Support\Logger;

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
        if ($path === '/proxy/image') return $this->proxyImage((string) $request->input('url', ''), (string) $request->input('fallback', ''));
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
        if ($path === '/exchange' || $path === '/exchange/') return $this->htmlShell('/exchange');
        if ($path === '/exchange/api/preview' && $request->method() === 'POST') return $this->exchangePreview($request);
        if ($path === '/exchange/api/redeem' && $request->method() === 'POST') return $this->exchangeRedeem($request);
        if ($path === '/exchange/api/orders' && $request->method() === 'GET') return $this->exchangeOrders();
        return $this->htmlShell($path);
    }

    private function htmlShell(string $path): mixed
    {
        $user = $this->auth->currentUser();
        $siteName = (string) $this->settings->get('site_name', '粥粥速刷系统');
        $siteKeywords = (string) $this->settings->get('site_keywords', '速刷,对接,短信,充值');
        $siteDescription = (string) $this->settings->get('site_description', '支持上游对接加价售卖的现代化速刷系统');
        $siteFavicon = trim((string) $this->settings->get('site_favicon', ''));
        $siteLogo = trim((string) $this->settings->get('site_logo', ''));
        $seoFooter = trim((string) $this->settings->get('seo_footer', ''));
        $adminPath = (string) $this->settings->get('admin_path', '/admin');

        $html = file_get_contents(view_path('app.php')) ?: '<!doctype html><html><body>视图缺失</body></html>';
        $publicUrl = public_url();
        $brandMarkup = $siteLogo !== ''
            ? '<span class="logo logo-image"><img src="' . htmlspecialchars($siteLogo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '"></span><span>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '</span>'
            : '<div class="logo">米</div><span>' . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . '</span>';
        $faviconTag = $siteFavicon !== ''
            ? '<link rel="icon" href="' . htmlspecialchars($siteFavicon, ENT_QUOTES, 'UTF-8') . '">'
            : '';
        $footerBlock = $seoFooter !== ''
            ? '<footer class="site-footer">' . nl2br(htmlspecialchars($seoFooter, ENT_QUOTES, 'UTF-8')) . '</footer>'
            : '';

        $boot = [
            'user' => $user,
            'adminPath' => $adminPath,
            'adminUrl' => $adminPath,
            'frontController' => front_controller_url(),
            'currency' => $this->settings->get('currency_name', '额度'),
            'routeMode' => $this->routeMode($path),
            'currentPath' => $path,
            'homeStats' => $this->homeStats(),
            'site' => [
                'name' => $siteName,
                'description' => $siteDescription,
                'keywords' => $siteKeywords,
                'favicon' => $siteFavicon,
                'logo' => $siteLogo,
                'footer' => $seoFooter,
            ],
            'settings' => $this->publicSettings(),
            'theme' => $this->themeConfig(),
        ];

        $html = str_replace('__SITE_NAME__', htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_DESCRIPTION__', htmlspecialchars($siteDescription, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_KEYWORDS__', htmlspecialchars($siteKeywords, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_FAVICON_TAG__', $faviconTag, $html);
        $html = str_replace('__PUBLIC_URL__', htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8'), $html);
        $html = str_replace('__SITE_BRAND__', $brandMarkup, $html);
        $html = str_replace('__SEO_FOOTER_BLOCK__', $footerBlock, $html);
        $html = str_replace('__BOOT__', json_encode($boot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $html);
        return Response::html($html);
    }

    private function login(Request $request): mixed
    {
        try {
            $data = $request->all();
            if (!(new CaptchaService())->verify((string) ($data['captcha'] ?? ''))) throw new RuntimeException('图片验证码错误或已过期');
            $user = $this->auth->login(trim((string) ($data['username'] ?? '')), (string) ($data['password'] ?? ''), false);
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
                'getBalance' => $this->apiResponse(200, '接口调用成功', ['amount' => (int) $user['balance']]),
                'queryGoods' => $this->apiGoods($user),
                'createOrder' => $this->apiCreateOrder($user, $request),
                'retryOrder' => $this->apiRetry($user, $request),
                'refundOrder' => $this->apiRefund($user, $request),
                'queryOrder' => $this->apiQueryOrder($user, $request),
                'orderList' => $this->apiOrderList($user, $request),
                'queryFeed' => $this->apiFeed($user, $request),
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
                'price' => (new \Sushua\Services\PricingService())->calculate($row, $this->group($user), max(1, $row['step_num']))['sell_price'],
                'price_delayed' => $row['price_cost_delayed'], 'level' => $row['upstream_level'], 'sign' => $row['upstream_sign'],
            ];
        }
        return $this->apiResponse(200, '接口调用成功', $data);
    }

    private function apiCreateOrder(array $user, Request $request): mixed
    {
        $order = (new OrderService())->create($user, $request->all(), true);
        return $this->apiResponse(200, '接口调用成功', ['orderid' => $order['order_no'] ?: ($order['upstream_order_no'] ?: ''), 'price' => $order['user_price'], 'num' => $order['quantity'], 'sign' => $order['upstream_sign'], 'feed_id' => $order['feed_id']]);
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
        return [
            'orderid' => $row['order_no'] ?: ($row['upstream_order_no'] ?: ''),
            'bid' => $row['order_no'] ?: ($row['upstream_order_no'] ?: ''),
            'upstream_order_no' => $row['upstream_order_no'] ?: null,
            'state' => $row['state'],
            'message' => $row['message'],
            'snum' => $row['start_num'],
            'nnum' => $row['current_num'],
            'enum' => $row['finish_num'],
            'stime' => $row['started_at'] ? strtotime((string) $row['started_at']) : null,
            'etime' => $row['finished_at'] ? strtotime((string) $row['finished_at']) : null,
            'last_update_time' => $row['last_sync_at'] ? strtotime((string) $row['last_sync_at']) : null,
            'price' => $row['user_price'],
            'num' => $row['quantity'],
            'sign' => $row['upstream_sign'],
            'feed_id' => $row['feed_id'],
        ];
    }

    private function userApi(Request $request, string $action): mixed
    {
        try {
            $user = $this->auth->requireUser();
            $action = trim($action, '/');
            $data = $request->all();
            $orders = new OrderService();
            $payments = new PaymentService();
            $result = match (true) {
                $action === 'products' => (new ProductService())->list($user, false),
                $action === 'orders' && $request->method() === 'GET' => $orders->list($user, false),
                $action === 'order/price' => $orders->quote($user, $data, false),
                $action === 'order/create' && $request->method() === 'POST' => $orders->create($user, $data, false),
                $action === 'order/detail' => $orders->findForActor($user, trim((string) ($data['bid'] ?? $data['order_no'] ?? '')), false),
                $action === 'order/retry' && $request->method() === 'POST' => $orders->retry($user, (int) $orders->findForActor($user, trim((string) ($data['bid'] ?? $data['order_no'] ?? '')), false)['id'], false),
                $action === 'order/refund' && $request->method() === 'POST' => $orders->refund($user, (int) $orders->findForActor($user, trim((string) ($data['bid'] ?? $data['order_no'] ?? '')), false)['id'], false, false),
                $action === 'feed' => $this->userFeed($user, $data),
                $action === 'groups' => (new UserGroupService())->all(),
                $action === 'group/claim' && $request->method() === 'POST' => $this->claimUserGroup((int) $user['id']),
                $action === 'profile' && $request->method() === 'GET' => $this->profilePayload($this->auth->currentUser() ?? $user),
                $action === 'profile/save' && $request->method() === 'POST' => $this->profilePayload($this->auth->updateProfile((int) $user['id'], $data)),
                $action === 'profile/password' && $request->method() === 'POST' => $this->auth->changePassword((int) $user['id'], (string) ($data['old_password'] ?? ''), (string) ($data['new_password'] ?? '')),
                $action === 'api-key/reset' && $request->method() === 'POST' => ['api_key' => $this->auth->resetApiKey((int) $user['id'])],
                $action === 'payments' => ['channels' => $payments->channels(), 'orders' => $this->userRechargeOrders($user)],
                $action === 'recharge/create' && $request->method() === 'POST' => $payments->createRecharge($user, (int) ($data['channel_id'] ?? 0), (string) ($data['money'] ?? ($data['money_yuan'] ?? ''))),
                $action === 'recharge/orders' => $this->userRechargeOrders($user),
                $action === 'invites' => (new InviteService())->list((int) $user['id']),
                $action === 'invite/create' && $request->method() === 'POST' => (new InviteService())->create((int) $user['id'], (int) ($data['length'] ?? 20), trim((string) ($data['code'] ?? '')) ?: null, false),
                $action === 'exchange-codes' && $request->method() === 'GET' => (new ProductExchangeCodeService())->listForUser((int) $user['id']),
                $action === 'exchange-code/create' && $request->method() === 'POST' => (new ProductExchangeCodeService())->create($user, $data),
                $action === 'exchange-code/settings' && $request->method() === 'GET' => (new ProductExchangeCodeService())->settingsSummary(),
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
                $rawImages = $item['images'] ?? ($item['img'] ?? []);
                $images = [];
                if (is_array($rawImages) && (array_key_exists('origin', $rawImages) || array_key_exists('proxy', $rawImages))) {
                    $origins = is_array($rawImages['origin'] ?? null) ? $rawImages['origin'] : [];
                    $proxies = is_array($rawImages['proxy'] ?? null) ? $rawImages['proxy'] : [];
                    $count = max(count($origins), count($proxies));
                    for ($i = 0; $i < $count; $i++) {
                        $images[] = [
                            'original' => (string) ($origins[$i] ?? ''),
                            'proxy' => (string) ($proxies[$i] ?? ''),
                        ];
                    }
                } elseif (is_array($rawImages)) {
                    $images = $rawImages;
                }
                foreach ($images as &$image) {
                    if (!is_array($image)) {
                        $image = ['original' => (string) $image, 'proxy' => ''];
                    }
                    $original = trim((string) ($image['original'] ?? ''));
                    $upstreamProxy = trim((string) ($image['proxy'] ?? ''));
                    $source = $mode === 'upstream_proxy' ? ($upstreamProxy !== '' ? $upstreamProxy : $original) : ($original !== '' ? $original : $upstreamProxy);
                    if ($source !== '' && $mode !== 'upstream_proxy') {
                        $fallback = $source === $original ? $upstreamProxy : $original;
                        $query = ['url' => $source];
                        if ($fallback !== '' && $fallback !== $source) {
                            $query['fallback'] = $fallback;
                        }
                        $image['display'] = '/proxy/image?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
                    } else {
                        $image['display'] = $source;
                    }
                }
                unset($image);
                $ctime = (int) ($item['ctime'] ?? 0);
                if ($ctime > 0) {
                    $item['time'] = date('Y-m-d H:i:s', $ctime);
                }
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
        return array_map([$this, 'normalizeRechargeRow'], $stmt->fetchAll());
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
            $payments = new PaymentService();
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
                $action === 'exchange-codes' && $request->method() === 'GET' => (new ProductExchangeCodeService())->listForAdmin(),
                $action === 'exchange-codes/logs' && $request->method() === 'GET' => (new ProductExchangeCodeService())->listLogs(),
                $action === 'orders/sync' => (new OrderService())->syncPendingOrders(),
                $action === 'orders/retry' => (new OrderService())->retry($admin, (int) ($data['id'] ?? 0), true),
                $action === 'orders/refund' => (new OrderService())->refund($admin, (int) ($data['id'] ?? 0), true, false),
                $action === 'orders/manual-refund' => (new OrderService())->refund($admin, (int) ($data['id'] ?? 0), true, true),
                $action === 'cards' && $request->method() === 'GET' => (new CardService())->list(),
                $action === 'cards/generate' => (new CardService())->generate(
                    (int) $admin['id'],
                    (int) ($data['count'] ?? 1),
                    (int) ($data['amount'] ?? 0),
                    (int) ($data['uses'] ?? 1),
                    (string) ($data['prefix'] ?? ''),
                    (string) ($data['note'] ?? ''),
                    isset($data['custom_code']) ? (string) $data['custom_code'] : null
                ),
                $action === 'cards/save' => (new CardService())->save($data),
                $action === 'cards/destroy' => (new CardService())->destroy((int) ($data['id'] ?? 0)),
                $action === 'payments' => ['merchants' => $payments->merchants(), 'channels' => $payments->channels(), 'recharge_orders' => array_map([$this, 'normalizeRechargeRow'], $payments->rechargeOrders())],
                $action === 'payments/merchant' => $payments->saveMerchant($data),
                $action === 'payments/channel' => $payments->saveChannel($data),
                $action === 'recharge-orders' => array_map([$this, 'normalizeRechargeRow'], $payments->rechargeOrders()),
                $action === 'settings' && $request->method() === 'GET' => $this->settings->all(),
                $action === 'settings/save' => $this->saveSettings($data),
                $action === 'upstream' && $request->method() === 'GET' => $pdo->query('SELECT id,name,base_url,upstream_uid,enabled,is_default,options_json,created_at,updated_at FROM upstream_accounts ORDER BY id DESC')->fetchAll(),
                $action === 'upstream/save' => $this->saveUpstream($data),
                $action === 'upstream/balance' => $this->upstreamBalance(),
                $action === 'logs' => $this->listLogs($data),
                default => throw new RuntimeException('后台接口不存在'),
            };
            return Response::success($result, '操作成功');
        } catch (\Throwable $e) {
            Logger::write('error', 'admin', $e->getMessage(), ['action' => $action]);
            return Response::error($e->getMessage(), 422);
        }
    }

    private function upstreamBalance(): array
    {
        $client = new UpstreamClient();
        $response = $client->getBalance();
        return [
            'balance' => $client->getBalanceAmount($response),
            'response' => $response,
        ];
    }

    private function dashboard(): array
    {
        $pdo = Database::connection();
        $today = date('Y-m-d 00:00:00');
        $scalar = static function (string $sql, array $params = []) use ($pdo): int {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        };
        $upstream = null;
        $upstreamError = null;
        try {
            // The upstream contract defines the displayed value as data.amount
            // from a fresh /api/getBalance request on every dashboard load.
            $upstream = (new UpstreamClient())->getBalanceAmount();
        } catch (\Throwable $e) {
            $upstreamError = $e->getMessage();
            Logger::write('warning', 'upstream', '获取上游余额失败', ['error' => $upstreamError]);
        }

        return [
            'orders_today' => $scalar('SELECT COUNT(*) FROM orders WHERE created_at >= ?', [$today]),
            'users_total' => $scalar('SELECT COUNT(*) FROM users WHERE status <> "deleted"'),
            'profit_today' => $scalar('SELECT COALESCE(SUM(profit),0) FROM orders WHERE created_at >= ?', [$today]),
            'balance_total' => $scalar('SELECT COALESCE(SUM(balance),0) FROM users WHERE status <> "deleted"'),
            'upstream_balance' => $upstream,
            'upstream_balance_error' => $upstreamError,
            'today_consume_rank' => $pdo->query('SELECT u.username,u.nickname,COALESCE(SUM(o.user_price),0) total FROM orders o JOIN users u ON u.id=o.user_id WHERE o.created_at >= "' . $today . '" GROUP BY o.user_id ORDER BY total DESC LIMIT 10')->fetchAll(),
            'total_consume_rank' => $pdo->query('SELECT username,nickname,total_consume FROM users WHERE status <> "deleted" ORDER BY total_consume DESC LIMIT 10')->fetchAll(),
            'balance_rank' => $pdo->query('SELECT username,nickname,balance FROM users WHERE status <> "deleted" ORDER BY balance DESC LIMIT 10')->fetchAll(),
            'today_recharge_rank' => $pdo->query('SELECT u.username,u.nickname,COALESCE(SUM(r.credit_amount + r.bonus_amount),0) total FROM recharge_orders r JOIN users u ON u.id=r.user_id WHERE r.status = "paid" AND COALESCE(r.paid_at, r.updated_at, r.created_at) >= "' . $today . '" GROUP BY r.user_id ORDER BY total DESC LIMIT 10')->fetchAll(),
        ];
    }

    private function saveSettings(array $data): array
    {
        unset($data['_token']);
        $allowed = array_keys($this->settings->defaults());
        $jsonKeys = ['sms_config', 'smtp_config', 'geetest_config', 'invite_code_price_rules', 'theme_config'];
        $booleanKeys = [
            'frontend_order_enabled', 'api_order_enabled', 'register_need_email', 'register_need_mobile',
            'register_need_image_captcha', 'register_need_geetest', 'register_need_sms_code', 'register_need_email_code',
            'login_need_sms', 'login_need_email', 'login_need_geetest', 'login_need_image_captcha',
            'default_register_strategy_user', 'default_register_strategy_agent', 'balance_downgrade_enabled',
        ];
        $smsProvider = (string) ($data['sms_provider'] ?? $this->settings->get('sms_provider', 'custom_http'));

        foreach ($data as $key => $value) {
            $key = (string) $key;
            if (!in_array($key, $allowed, true)) {
                continue;
            }

            if (in_array($key, $booleanKeys, true)) {
                $value = in_array((string) $value, ['1', 'true', 'on'], true) ? '1' : '0';
            } elseif (in_array($key, $jsonKeys, true)) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $value = $decoded;
                    } else {
                        throw new RuntimeException($key . ' 配置格式不正确');
                    }
                }
                if (!is_array($value)) {
                    throw new RuntimeException($key . ' 配置格式不正确');
                }
                if ($key === 'invite_code_price_rules') {
                    $value = $this->normalizeInviteCodePriceRules($value);
                } elseif ($key === 'sms_config') {
                    $value = $this->normalizeSmsConfig($smsProvider, $value);
                } elseif ($key === 'smtp_config') {
                    $value = [
                        'host' => trim((string) ($value['host'] ?? '')),
                        'port' => (int) ($value['port'] ?? 465),
                        'username' => trim((string) ($value['username'] ?? '')),
                        'password' => trim((string) ($value['password'] ?? '')),
                        'encryption' => trim((string) ($value['encryption'] ?? 'ssl')),
                        'from' => trim((string) ($value['from'] ?? '')),
                        'from_name' => trim((string) ($value['from_name'] ?? '')),
                    ];
                } elseif ($key === 'geetest_config') {
                    $value = [
                        'captcha_id' => trim((string) ($value['captcha_id'] ?? '')),
                        'captcha_key' => trim((string) ($value['captcha_key'] ?? '')),
                    ];
                }
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                $value = is_scalar($value) ? trim((string) $value) : '';
            }

            if ($key === 'login_need_image_captcha') {
                $value = '1';
            }
            if ($key === 'currency_name' && ($value === '' || mb_strlen((string) $value) > 20)) {
                throw new RuntimeException('额度名称不能为空且不能超过20个字符');
            }
            if ($key === 'admin_path') {
                $path = '/' . trim((string) preg_replace('#/+#', '/', (string) $value), '/');
                if ($path === '/' || !preg_match('#^/(?:[A-Za-z0-9_-]{1,40})(?:/[A-Za-z0-9_-]{1,40})*$#', $path)) {
                    throw new RuntimeException('后台路径格式不合法');
                }
                $value = $path;
            }
            if ($key === 'feed_image_mode' && !in_array((string) $value, ['self_proxy', 'upstream_proxy'], true)) {
                throw new RuntimeException('说说图片来源设置不合法');
            }
            if ($key === 'sms_provider' && !in_array((string) $value, ['tencent', 'aliyun', 'custom_http'], true)) {
                throw new RuntimeException('短信服务商设置不合法');
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
            if ($apiKey === '') $apiKey = (string) $current['upstream_api_key'];
        }
        if ($apiKey === '') throw new RuntimeException('上游 API Key 不能为空');

        $verification = $this->verifyUpstreamAllow($baseUrl, $upstreamUid, $apiKey);
        if ((int) ($verification['code'] ?? 500) !== 200 || !($verification['data']['allow'] ?? false)) {
            throw new RuntimeException('货源不允许你对接，保存此货源也没用');
        }

        $pdo->beginTransaction();
        try {
            if ($isDefault) $pdo->exec('UPDATE upstream_accounts SET is_default = 0');
            if ($id > 0) {
                $pdo->prepare('UPDATE upstream_accounts SET name=?,base_url=?,upstream_uid=?,upstream_api_key=?,enabled=?,is_default=?,options_json=?,updated_at=? WHERE id=?')->execute([$name, $baseUrl, $upstreamUid, $apiKey, $enabled, $isDefault, json_encode($data['options'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), now(), $id]);
            } else {
                $pdo->prepare('INSERT INTO upstream_accounts (name,base_url,upstream_uid,upstream_api_key,enabled,is_default,options_json,created_at,updated_at) VALUES (?,?,?,?,?,?,?, ?, ?)')->execute([$name, $baseUrl, $upstreamUid, $apiKey, $enabled, $isDefault, json_encode($data['options'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), now(), now()]);
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


    private function routeMode(string $path): string
    {
        $adminPath = (string) $this->settings->get('admin_path', '/admin');
        if ($path === $adminPath || str_starts_with($path, $adminPath . '/')) {
            return 'admin';
        }
        if ($path === '/login') {
            return 'login';
        }
        if ($path === '/register') {
            return 'register';
        }
        if ($path === '/exchange' || str_starts_with($path, '/exchange/')) {
            return 'exchange';
        }
        if ($path === '/user' || str_starts_with($path, '/user/')) {
            return 'user';
        }
        return 'home';
    }

    private function publicSettings(): array
    {
        $all = $this->settings->all();
        $keys = [
            'currency_name',
            'frontend_order_enabled',
            'api_order_enabled',
            'feed_image_mode',
            'register_need_email',
            'register_need_mobile',
            'register_need_image_captcha',
            'register_need_geetest',
            'register_need_sms_code',
            'register_need_email_code',
            'login_need_sms',
            'login_need_email',
            'login_need_geetest',
            'login_need_image_captcha',
            'invite_valid_mode',
            'invite_valid_value',
            'sms_provider',
            'community_group_qq',
            'support_group_qq',
            'icp_beian_no',
            'public_security_beian_no',
            'exchange_code_enabled',
            'exchange_code_generation_fee',
            'exchange_code_prefix',
            'exchange_code_random_length',
            'exchange_code_format',
            'exchange_code_cookie_days',
        ];
        $public = [];
        foreach ($keys as $key) {
            $public[$key] = $all[$key] ?? null;
        }
        $public['invite_code_price_rules'] = $this->normalizeInviteCodePriceRules(
            json_array((string) ($all['invite_code_price_rules'] ?? ''), ['mode' => 'fixed', 'fixed' => 0, 'length_rules' => []])
        );
        $public['theme_config'] = $this->themeConfig();
        return $public;
    }

    private function listLogs(array $filters): array
    {
        $sql = 'SELECT * FROM system_logs WHERE 1=1';
        $params = [];
        $level = trim((string) ($filters['level'] ?? ''));
        if ($level !== '') {
            $sql .= ' AND level = ?';
            $params[] = $level;
        }
        $channel = trim((string) ($filters['channel'] ?? ''));
        if ($channel !== '') {
            $sql .= ' AND channel = ?';
            $params[] = $channel;
        }
        $sql .= ' ORDER BY id DESC LIMIT 300';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function profilePayload(array $user): array
    {
        return [
            'user' => $user,
            'group' => $this->group($user),
            'api_access' => (new ApiAccessService())->status($user),
        ];
    }

    private function claimUserGroup(int $userId): array
    {
        (new UserGroupService())->evaluateAndUpdate($userId);
        $user = $this->auth->currentUser() ?? $this->auth->requireUser();
        return $this->profilePayload($user);
    }

    private function normalizeRechargeRow(array $row): array
    {
        $row['amount'] = (int) ($row['amount'] ?? 0);
        $row['credit_amount'] = (int) ($row['credit_amount'] ?? 0);
        $row['bonus_amount'] = (int) ($row['bonus_amount'] ?? 0);
        $row['expected_amount'] = $row['credit_amount'] + $row['bonus_amount'];
        $row['money_yuan'] = number_format($row['amount'] / 100, 2, '.', '');
        return $row;
    }

    private function normalizeInviteCodePriceRules(array $value): array
    {
        $mode = (string) ($value['mode'] ?? 'fixed');
        if (!in_array($mode, ['fixed', 'length'], true)) {
            throw new RuntimeException('邀请码价格模式不合法');
        }
        $fixed = filter_var($value['fixed'] ?? 0, FILTER_VALIDATE_INT);
        if ($fixed === false || $fixed < 0) {
            throw new RuntimeException('邀请码固定价格不能为负数');
        }
        if ($mode === 'fixed') {
            return ['mode' => 'fixed', 'fixed' => $fixed, 'length_rules' => []];
        }
        $lengthRules = [];
        $sourceRules = $value['length_rules'] ?? $value;
        if (!is_array($sourceRules)) {
            throw new RuntimeException('邀请码长度价格配置格式不正确');
        }
        foreach ($sourceRules as $length => $rule) {
            if (is_array($rule)) {
                $lengthValue = $rule['length'] ?? null;
                $priceValue = $rule['price'] ?? null;
            } else {
                if ($length === 'mode' || $length === 'fixed') {
                    continue;
                }
                $lengthValue = $length;
                $priceValue = $rule;
            }
            $expr = $this->normalizeInviteLengthExpression((string) $lengthValue);
            if ($expr === null) {
                continue;
            }
            $price = filter_var($priceValue, FILTER_VALIDATE_INT);
            if ($price === false || $price < 0) {
                throw new RuntimeException('邀请码长度价格必须是非负整数');
            }
            $lengthRules[] = ['length' => $expr, 'price' => (int) $price];
        }
        usort($lengthRules, static function (array $a, array $b): int {
            [$aMin, $aMax] = AppController::inviteLengthBounds((string) ($a['length'] ?? '6'));
            [$bMin, $bMax] = AppController::inviteLengthBounds((string) ($b['length'] ?? '6'));
            return $aMin <=> $bMin ?: $aMax <=> $bMax;
        });
        return ['mode' => 'length', 'fixed' => $fixed, 'length_rules' => $lengthRules];
    }

    private function normalizeInviteLengthExpression(string $length): ?string
    {
        $length = trim(str_replace('～', '~', $length));
        if ($length === '') {
            return null;
        }
        if (preg_match('/^(\d{1,2})$/', $length, $match)) {
            $value = (int) $match[1];
            return ($value >= 6 && $value <= 48) ? (string) $value : null;
        }
        if (preg_match('/^(\d{1,2})\s*(?:-|~)\s*(\d{1,2})$/', $length, $match)) {
            $min = (int) $match[1];
            $max = (int) $match[2];
            if ($min > $max) {
                [$min, $max] = [$max, $min];
            }
            if ($min < 6 || $max > 48) {
                return null;
            }
            return $min === $max ? (string) $min : ($min . '-' . $max);
        }
        return null;
    }

    private static function inviteLengthBounds(string $expr): array
    {
        if (preg_match('/^(\d{1,2})-(\d{1,2})$/', $expr, $match)) {
            return [(int) $match[1], (int) $match[2]];
        }
        $value = (int) $expr;
        return [$value, $value];
    }

    private function normalizeSmsConfig(string $provider, array $value): array
    {
        return match ($provider) {
            'tencent' => [
                'secret_id' => trim((string) ($value['secret_id'] ?? '')),
                'secret_key' => trim((string) ($value['secret_key'] ?? '')),
                'sdk_app_id' => trim((string) ($value['sdk_app_id'] ?? '')),
                'template_id' => trim((string) ($value['template_id'] ?? '')),
                'region' => trim((string) ($value['region'] ?? 'ap-guangzhou')),
            ],
            'aliyun' => [
                'access_key_id' => trim((string) ($value['access_key_id'] ?? '')),
                'access_key_secret' => trim((string) ($value['access_key_secret'] ?? '')),
                'template_code' => trim((string) ($value['template_code'] ?? '')),
                'region' => trim((string) ($value['region'] ?? 'cn-hangzhou')),
                'endpoint' => trim((string) ($value['endpoint'] ?? '')),
            ],
            default => [
                'url' => trim((string) ($value['url'] ?? '')),
                'method' => strtoupper(trim((string) ($value['method'] ?? 'POST'))),
                'headers' => is_array($value['headers'] ?? null) ? $value['headers'] : [],
                'query' => is_array($value['query'] ?? null) ? $value['query'] : [],
                'body' => is_array($value['body'] ?? null) ? $value['body'] : [],
                'success_field' => trim((string) ($value['success_field'] ?? '')),
                'success_value' => trim((string) ($value['success_value'] ?? '')),
            ],
        };
    }

    private function verifyUpstreamAllow(string $baseUrl, int $uid, string $apiKey): array
    {
        $url = rtrim($baseUrl, '/') . '/api/success?' . http_build_query(['uid' => $uid, 'api_key' => $apiKey]);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        apply_curl_ssl_defaults($ch);
        $body = curl_exec($ch);
        if ($body === false) {
            throw new RuntimeException('上游校验失败：' . curl_error($ch));
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $body = trim(preg_replace('/^\xEF\xBB\xBF/u', '', (string) $body) ?? (string) $body);
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('上游校验返回异常：HTTP ' . $status);
        }
        return $decoded;
    }


    private function exchangePreview(Request $request): mixed
    {
        try {
            $preview = (new ProductExchangeCodeService())->previewPublic((string) $request->input('code', ''));
            return Response::success($preview, '兑换码可用');
        } catch (\Throwable $e) {
            return Response::error($e->getMessage(), 422);
        }
    }

    private function exchangeRedeem(Request $request): mixed
    {
        try {
            $redeemer = $this->auth->currentUser();
            $service = new ProductExchangeCodeService();
            $order = $service->redeemPublic($request->all(), $request->ip(), $redeemer);
            $this->rememberExchangeOrder((string) ($order['display_order_no'] ?? $order['order_no'] ?? ''));
            return Response::success($order, '兑换成功');
        } catch (\Throwable $e) {
            return Response::error($e->getMessage(), 422);
        }
    }

    private function exchangeOrders(): mixed
    {
        try {
            $service = new ProductExchangeCodeService();
            return Response::success($service->publicOrders($this->exchangeOrderCookieList()), '操作成功');
        } catch (\Throwable $e) {
            return Response::error($e->getMessage(), 422);
        }
    }

    private function rememberExchangeOrder(string $orderNo): void
    {
        $orderNo = trim($orderNo);
        if ($orderNo === '') {
            return;
        }
        $list = $this->exchangeOrderCookieList();
        array_unshift($list, $orderNo);
        $list = array_values(array_unique(array_filter(array_map('trim', $list))));
        $list = array_slice($list, 0, 20);
        $days = min(3650, max(7, (int) $this->settings->get('exchange_code_cookie_days', '60')));
        setcookie('xm_exchange_orders', json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), [
            'expires' => time() + ($days * 86400),
            'path' => '/',
            'httponly' => false,
            'secure' => isset($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off',
            'samesite' => 'Lax',
        ]);
        $_COOKIE['xm_exchange_orders'] = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function exchangeOrderCookieList(): array
    {
        return array_values(array_filter(json_array((string) ($_COOKIE['xm_exchange_orders'] ?? '[]'))));
    }

    private function themeConfig(): array
    {
        $defaults = json_array((string) $this->settings->defaults()['theme_config'], []);
        return array_replace($defaults, $this->settings->getJson('theme_config', $defaults));
    }

    private function homeStats(): array
    {
        $pdo = Database::connection();
        $summary = [
            'product_count' => (int) ($pdo->query('SELECT COUNT(*) FROM products WHERE enabled = 1')->fetchColumn() ?: 0),
            'order_count' => (int) ($pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn() ?: 0),
            'total_quantity' => (int) ($pdo->query('SELECT COALESCE(SUM(quantity),0) FROM orders')->fetchColumn() ?: 0),
            'items' => [],
        ];

        $sql = 'SELECT p.id, p.name, COUNT(o.id) AS order_count, COALESCE(SUM(o.quantity),0) AS total_quantity, ' .
            'COALESCE(SUM(CASE WHEN o.started_at IS NOT NULL AND o.finished_at IS NOT NULL AND o.finished_at > o.started_at THEN TIMESTAMPDIFF(SECOND, o.started_at, o.finished_at) ELSE 0 END),0) AS total_seconds ' .
            'FROM products p JOIN orders o ON o.product_id = p.id ' .
            'GROUP BY p.id, p.name HAVING COUNT(o.id) > 0 ' .
            'ORDER BY total_quantity DESC, order_count DESC, p.id DESC LIMIT 24';
        foreach ($pdo->query($sql)->fetchAll() as $row) {
            $seconds = (int) ($row['total_seconds'] ?? 0);
            $hours = $seconds > 0 ? ($seconds / 3600) : 0;
            $summary['items'][] = [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'order_count' => (int) ($row['order_count'] ?? 0),
                'total_quantity' => (int) ($row['total_quantity'] ?? 0),
                'avg_speed_per_hour' => $hours > 0 ? round(((int) ($row['total_quantity'] ?? 0)) / $hours, 2) : null,
            ];
        }

        return $summary;
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

    private function proxyImage(string $url, string $fallback = ''): mixed
    {
        $url = trim($url);
        $fallback = trim($fallback);
        if (!$this->isAllowedProxyUrl($url)) {
            http_response_code(400);
            header('Content-Type: text/plain; charset=utf-8');
            exit('bad url');
        }
        if ($fallback !== '' && !$this->isAllowedProxyUrl($fallback)) {
            $fallback = '';
        }

        $cacheDir = storage_path('cache/proxy-images');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }
        $cacheKey = hash('sha256', $url);
        $bodyFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.bin';
        $metaFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
        $serve = static function (string $body, string $contentType, string $cacheState): never {
            header('Content-Type: ' . $contentType);
            header('Cache-Control: public, max-age=86400, stale-if-error=2592000');
            header('X-Image-Proxy-Cache: ' . $cacheState);
            echo $body;
            exit;
        };

        if (is_file($bodyFile) && is_file($metaFile)) {
            $meta = json_decode((string) @file_get_contents($metaFile), true);
            $cachedBody = @file_get_contents($bodyFile);
            $cachedType = strtolower(trim((string) ($meta['content_type'] ?? '')));
            if (is_string($cachedBody) && $cachedBody !== '' && str_starts_with($cachedType, 'image/')) {
                $serve($cachedBody, $cachedType, 'HIT');
            }
        }

        $lastError = 'image unavailable';
        foreach (array_values(array_unique(array_filter([$url, $fallback]))) as $candidate) {
            $result = $this->fetchProxyImage((string) $candidate);
            if (($result['ok'] ?? false) !== true) {
                $lastError = (string) ($result['error'] ?? $lastError);
                continue;
            }
            $body = (string) $result['body'];
            $contentType = (string) $result['content_type'];
            if (is_dir($cacheDir) && is_writable($cacheDir)) {
                $tmpBody = $bodyFile . '.' . bin2hex(random_bytes(4)) . '.tmp';
                $tmpMeta = $metaFile . '.' . bin2hex(random_bytes(4)) . '.tmp';
                if (@file_put_contents($tmpBody, $body, LOCK_EX) !== false
                    && @file_put_contents($tmpMeta, json_encode([
                        'content_type' => $contentType,
                        'source' => (string) ($result['effective_url'] ?? $candidate),
                        'cached_at' => now(),
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX) !== false) {
                    @rename($tmpBody, $bodyFile);
                    @rename($tmpMeta, $metaFile);
                }
                @unlink($tmpBody);
                @unlink($tmpMeta);
            }
            $serve($body, $contentType, $candidate === $url ? 'MISS' : 'FALLBACK');
        }

        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        exit($lastError !== '' ? $lastError : 'image unavailable');
    }

    private function fetchProxyImage(string $url): array
    {
        $body = '';
        $tooLarge = false;
        $responseHeaders = [];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                'Referer: https://user.qzone.qq.com/',
                'Origin: https://user.qzone.qq.com',
                'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
            ],
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
            CURLOPT_HEADERFUNCTION => static function ($handle, string $header) use (&$responseHeaders): int {
                $len = strlen($header);
                $parts = explode(':', $header, 2);
                if (count($parts) === 2) {
                    $responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
                return $len;
            },
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$body, &$tooLarge): int {
                if (strlen($body) + strlen($chunk) > 5 * 1024 * 1024) {
                    $tooLarge = true;
                    return 0;
                }
                $body .= $chunk;
                return strlen($chunk);
            },
        ]);
        apply_curl_ssl_defaults($ch);
        $ok = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = (string) (curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '');
        $effectiveUrl = (string) (curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url);
        $error = curl_error($ch);
        curl_close($ch);

        if ($tooLarge) return ['ok' => false, 'error' => 'image too large'];
        if (!$this->isAllowedProxyUrl($effectiveUrl)) return ['ok' => false, 'error' => 'bad redirect url'];
        if ($ok === false || $httpCode < 200 || $httpCode >= 300 || $body === '') {
            return ['ok' => false, 'error' => $error !== '' ? 'image unavailable: ' . $error : 'image unavailable (HTTP ' . $httpCode . ')'];
        }
        $contentType = strtolower(trim(explode(';', $contentType, 2)[0]));
        if ($contentType === '' && isset($responseHeaders['content-type'])) {
            $contentType = strtolower(trim(explode(';', $responseHeaders['content-type'], 2)[0]));
        }
        if (!str_starts_with($contentType, 'image/') && function_exists('finfo_buffer')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $detected = strtolower((string) $finfo->buffer($body));
            if (str_starts_with($detected, 'image/')) $contentType = $detected;
        }
        if (!str_starts_with($contentType, 'image/')) return ['ok' => false, 'error' => 'not an image'];
        return ['ok' => true, 'body' => $body, 'content_type' => $contentType, 'effective_url' => $effectiveUrl];
    }

    private function isAllowedProxyUrl(string $url): bool
    {
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = (string) ($parts['host'] ?? '');
        $port = isset($parts['port']) ? (int) $parts['port'] : ($scheme === 'https' ? 443 : 80);
        if ($url === '' || !in_array($scheme, ['http', 'https'], true) || $host === '' || $port <= 0 || $port > 65535) {
            return false;
        }
        return $this->hostResolvesToPublicIp($host);
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
        $html = '<!doctype html><meta charset="utf-8"><title>接口文档</title><style>body{font-family:system-ui;max-width:980px;margin:40px auto;padding:0 20px;line-height:1.7}code{padding:2px 5px;border:1px solid currentColor;border-radius:5px}</style><h1>上游对接兼容接口</h1><p>所有接口返回 <code>{code,msg,data,time}</code>，认证参数为 <code>uid</code> 与 <code>api_key</code>。</p><ul><li>/api/success</li><li>/api/getBalance</li><li>/api/queryGoods</li><li>/api/createOrder</li><li>/api/retryOrder</li><li>/api/refundOrder</li><li>/api/queryOrder</li><li>/api/orderList</li><li>/api/queryFeed</li></ul>';
        return Response::html($html);
    }
}



