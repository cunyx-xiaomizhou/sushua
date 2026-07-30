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

    /**
     * Extract the upstream account balance from a provider response.
     * Different providers use different envelopes and field names, so this
     * supports common aliases and nested data without treating status codes
     * or unrelated metadata as a balance.
     */
    public static function extractBalance(mixed $payload): int|float|null
    {
        $preferredKeys = [
            'balance', 'amount', 'money', 'credit', 'credits',
            'available_balance', 'availablebalance', 'account_balance',
            'accountbalance', 'funds', 'cash', 'remain', 'remaining', 'left',
        ];

        $find = static function (mixed $value, int $depth = 0) use (&$find, $preferredKeys): int|float|null {
            if ($depth > 8 || $value === null || is_bool($value)) return null;

            if (is_string($value)) {
                $trimmed = trim($value);
                if ($trimmed !== '' && is_numeric($trimmed)) {
                    $number = (float) $trimmed;
                    if (is_finite($number)) return fmod($number, 1.0) === 0.0 ? (int) $number : $number;
                }
                $decoded = json_decode($trimmed, true);
                return is_array($decoded) ? $find($decoded, $depth + 1) : null;
            }

            if (is_int($value) || is_float($value)) {
                return is_finite((float) $value)
                    ? (fmod((float) $value, 1.0) === 0.0 ? (int) $value : (float) $value)
                    : null;
            }
            if (!is_array($value)) return null;

            $normalized = [];
            foreach ($value as $key => $item) {
                $normalizedKey = strtolower(preg_replace('/[^a-z0-9]/i', '', (string) $key) ?? '');
                $normalized[$normalizedKey] = $item;
            }
            foreach ($preferredKeys as $key) {
                $normalizedKey = strtolower(preg_replace('/[^a-z0-9]/i', '', $key) ?? '');
                if (!array_key_exists($normalizedKey, $normalized)) continue;
                $candidate = $find($normalized[$normalizedKey], $depth + 1);
                if ($candidate !== null) return $candidate;
            }
            foreach ($value as $item) {
                if (!is_array($item) && !is_string($item)) continue;
                $candidate = $find($item, $depth + 1);
                if ($candidate !== null) return $candidate;
            }
            return null;
        };

        return $find($payload);
    }
}
