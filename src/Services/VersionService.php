<?php
declare(strict_types=1);

namespace Sushua\Services;

use RuntimeException;

final class VersionService
{
    public const CURRENT_VERSION = 'v1.0.0';

    public function current(): array
    {
        $fallback = [
            'version' => self::CURRENT_VERSION,
            'name' => '粥粥速刷系统',
            'features' => ['支持在线下单、接口对接、兑换码和后台管理'],
        ];
        $file = base_path('version.json');
        if (!is_file($file)) return $fallback;
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? array_replace($fallback, $data) : $fallback;
    }

    public function check(): array
    {
        $current = $this->current();
        $gitAvailable = is_dir(base_path('.git'));
        $result = [
            'current' => $current,
            'remote' => null,
            'has_update' => false,
            'git_available' => $gitAvailable,
            'can_update' => false,
            'checked_at' => date(DATE_ATOM),
            'message' => $gitAvailable
                ? '正在读取远程版本清单。'
                : '项目根目录未检测到 .git，在线版本检测不可用。',
        ];
        if (!$gitAvailable) return $result;
        if (!function_exists('curl_init')) {
            $result['message'] = '服务器未安装 cURL 扩展，在线版本检测不可用。';
            return $result;
        }
        try {
            $remote = $this->fetchRemoteVersion();
        } catch (\Throwable $e) {
            $result['message'] = '在线版本检测暂时不可用。';
            return $result;
        }
        if ($remote === null) {
            $result['message'] = '暂时无法读取远程版本清单。';
            return $result;
        }
        $result['remote'] = $remote;
        $result['has_update'] = $this->compareVersions((string) ($remote['version'] ?? ''), (string) ($current['version'] ?? self::CURRENT_VERSION)) > 0;
        $result['message'] = $result['has_update']
            ? '检测到新版本，请先备份数据库和配置，再通过 Git 拉取更新。'
            : '当前已经是最新版本。';
        return $result;
    }

    private function fetchRemoteVersion(): ?array
    {
        $remote = $this->originRemote();
        if ($remote === null) return null;
        [$host, $owner, $repo] = $remote;
        $apiUrl = $host . '/api/v1/repos/' . rawurlencode($owner) . '/' . rawurlencode($repo) . '/contents/version.json?ref=main';
        $payload = $this->requestJson($apiUrl);
        $content = base64_decode((string) ($payload['content'] ?? ''), true);
        if ($content === false) return null;
        $version = json_decode($content, true);
        return is_array($version) ? $version : null;
    }

    private function originRemote(): ?array
    {
        if (!is_dir(base_path('.git'))) return null;
        $config = (string) @file_get_contents(base_path('.git/config'));
        if ($config === '' || !preg_match('/\[remote "origin"\][^\[]*?\n\s*url\s*=\s*(\S+)/s', $config, $match)) return null;
        $url = trim($match[1]);
        if (!preg_match('#^(https?)://([^/]+)(?:/([^/]+)/([^/]+?))(?:\.git)?$#i', $url, $parts)) return null;
        return [$parts[1] . '://' . $parts[2], $parts[3], $parts[4]];
    }

    private function requestJson(string $url): array
    {
        $ch = curl_init($url);
        if ($ch === false) throw new RuntimeException('无法初始化远程版本请求');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'User-Agent: Sushua-Version-Checker'],
        ]);
        apply_curl_ssl_defaults($ch);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($body === false || $code < 200 || $code >= 300) return [];
        $data = json_decode((string) $body, true);
        return is_array($data) ? $data : [];
    }

    private function compareVersions(string $left, string $right): int
    {
        $normalize = static function (string $value): array {
            preg_match_all('/\d+/', $value, $matches);
            return array_pad(array_map('intval', array_slice($matches[0] ?? [], 0, 4)), 4, 0);
        };
        return $normalize($left) <=> $normalize($right);
    }
}
