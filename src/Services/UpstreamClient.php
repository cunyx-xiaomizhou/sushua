<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use PDO;
use RuntimeException;
use XiaoMiSlop\Core\Database;

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
        $base = rtrim((string) $acc['base_url'], '/');
        $query = array_merge(['uid' => $acc['upstream_uid'], 'api_key' => $acc['upstream_api_key']], $params);
        $url = $base . '/' . ltrim($path, '/') . '?' . http_build_query($query);
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
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $decoded = json_decode((string) $body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('上游返回非JSON：HTTP ' . $status . ' ' . mb_substr((string) $body, 0, 300));
        }
        return $decoded;
    }

    public function success(): array { return $this->request('api/success'); }
    public function getBalance(): array { return $this->request('api/getBalance'); }
    public function queryGoods(): array { return $this->request('api/queryGoods'); }
    public function createOrder(array $params): array { return $this->request('api/createOrder', $params); }
    public function retryOrder(string $bid): array { return $this->request('api/retryOrder', ['bid' => $bid]); }
    public function refundOrder(string $bid): array { return $this->request('api/refundOrder', ['bid' => $bid]); }
    public function queryOrder(string $bid): array { return $this->request('api/queryOrder', ['bid' => $bid]); }
    public function orderList(int $page = 1, int $limit = 20): array { return $this->request('api/orderList', ['page' => $page, 'limit' => $limit]); }
    public function queryFeed(string $uin): array { return $this->request('api/queryFeed', ['uin' => $uin]); }
}
