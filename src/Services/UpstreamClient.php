<?php
declare(strict_types=1);

namespace Sushua\Services;

use PDO;
use RuntimeException;
use Sushua\Core\Database;

final class UpstreamClient
{
    private PDO $pdo;
    private ?array $account = null;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function account(): array
    {
        if ($this->account) {
            return $this->account;
        }
        $stmt = $this->pdo->query('SELECT * FROM upstream_accounts WHERE enabled = 1 ORDER BY is_default DESC, id ASC LIMIT 1');
        $account = $stmt->fetch();
        if (!$account) {
            throw new RuntimeException('未配置可用上游账户');
        }
        $this->account = $account;
        return $account;
    }

    public function request(string $path, array $params = []): array
    {
        $acc = $this->account();
        $uid = trim((string) ($acc['upstream_uid'] ?? ''));
        $apiKey = trim((string) ($acc['upstream_api_key'] ?? ''));
        if ($uid === '' || (int) $uid <= 0) {
            throw new RuntimeException('上游 UID 未配置或无效');
        }
        if ($apiKey === '') {
            throw new RuntimeException('上游 API Key 未配置');
        }

        $base = rtrim(trim((string) ($acc['base_url'] ?? '')), '/');
        if ($base === '') {
            throw new RuntimeException('上游基础地址未配置');
        }

        $requestPath = ltrim($path, '/');
        $basePath = (string) parse_url($base, PHP_URL_PATH);
        if (preg_match('~/api$~i', rtrim($basePath, '/')) && str_starts_with(strtolower($requestPath), 'api/')) {
            $requestPath = substr($requestPath, 4);
        }

        // Credentials always come from the selected upstream account and cannot
        // be overridden by endpoint-specific parameters.
        $query = array_merge($params, ['uid' => $uid, 'api_key' => $apiKey]);
        $url = $base . '/' . $requestPath . '?' . http_build_query($query);
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
            throw new RuntimeException('上游请求失败：' . curl_error($ch));
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $body = trim(preg_replace('/^\xEF\xBB\xBF/u', '', (string) $body) ?? (string) $body);
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('上游返回非JSON：HTTP ' . $status . ' ' . mb_substr($body, 0, 300));
        }
        if ($status < 200 || $status >= 300) {
            throw new RuntimeException('上游请求失败：HTTP ' . $status . '，' . (string) ($decoded['msg'] ?? '未知错误'));
        }
        return $decoded;
    }

    public function success(): array { return $this->request('api/success'); }
    public function getBalance(): array { return $this->request('api/getBalance'); }

    /**
     * The upstream contract defines the balance strictly as data.amount.
     * The amount is measured in ten-thousandths of a yuan.
     */
    public function getBalanceAmount(?array $response = null): int|float
    {
        $response ??= $this->getBalance();
        if ((int) ($response['code'] ?? 0) !== 200) {
            throw new RuntimeException('上游余额接口调用失败：' . (string) ($response['msg'] ?? '未知错误'));
        }
        $data = $response['data'] ?? null;
        if (!is_array($data) || !array_key_exists('amount', $data) || !is_numeric($data['amount'])) {
            throw new RuntimeException('上游余额接口返回缺少 data.amount');
        }
        $amount = (float) $data['amount'];
        if (!is_finite($amount)) {
            throw new RuntimeException('上游余额接口返回的 data.amount 无效');
        }
        return fmod($amount, 1.0) === 0.0 ? (int) $amount : $amount;
    }

    public function queryGoods(): array { return $this->request('api/queryGoods'); }
    public function createOrder(array $params): array { return $this->request('api/createOrder', $params); }
    public function retryOrder(string $bid): array { return $this->request('api/retryOrder', ['bid' => $bid]); }
    public function refundOrder(string $bid): array { return $this->request('api/refundOrder', ['bid' => $bid]); }
    public function queryOrder(string $bid): array { return $this->request('api/queryOrder', ['bid' => $bid]); }
    public function orderList(int $page = 1, int $limit = 20): array { return $this->request('api/orderList', ['page' => $page, 'limit' => $limit]); }
    public function queryFeed(string $uin): array { return $this->request('api/queryFeed', ['uin' => $uin]); }
}