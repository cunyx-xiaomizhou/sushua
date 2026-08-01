<?php
declare(strict_types=1);

namespace Sushua\Services;

use RuntimeException;

final class VersionService
{
    private const FALLBACK_VERSION = 'v1.0.0';

    public function current(): array
    {
        $fallback = [
            'version' => self::FALLBACK_VERSION,
            'name' => '粥粥速刷系统',
            'features' => ['支持在线下单、接口对接、兑换码和后台管理'],
        ];
        $file = base_path('version.json');
        if (!is_file($file)) return $fallback;
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? array_replace($fallback, $data) : $fallback;
    }

    public function currentVersion(): string
    {
        return (string) ($this->current()['version'] ?? self::FALLBACK_VERSION);
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
            'can_update' => $gitAvailable,
            'checked_at' => date('Y-m-d H:i:s'),
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
            $result['message'] = '在线版本检测暂时不可用：' . $e->getMessage();
            return $result;
        }
        if ($remote === null) {
            $result['message'] = '暂时无法读取远程版本清单，请检查 .git/config 中的远程仓库配置。';
            return $result;
        }
        $result['remote'] = $remote;
        $result['has_update'] = $this->compareVersions((string) ($remote['version'] ?? ''), $this->currentVersion()) > 0;
        $result['message'] = $result['has_update']
            ? '检测到新版本：' . ($remote['version'] ?? '未知') . '，点击一键更新即可升级。'
            : '当前已经是最新版本。';
        return $result;
    }

    public function update(): array
    {
        if (!is_dir(base_path('.git'))) {
            throw new RuntimeException('项目根目录未检测到 .git，无法进行在线更新。');
        }
        $remote = $this->fetchRemoteVersion();
        if ($remote === null) {
            throw new RuntimeException('暂时无法读取远程版本清单，请检查 .git/config 中的远程仓库配置。');
        }
        $hasUpdate = $this->compareVersions((string) ($remote['version'] ?? ''), $this->currentVersion()) > 0;
        if (!$hasUpdate) {
            return ['updated' => false, 'message' => '当前已经是最新版本。'];
        }
        $root = base_path();
        $output = shell_exec('cd ' . escapeshellarg($root) . ' && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1');
        if ($output === null) {
            throw new RuntimeException('更新命令执行失败，请检查服务器 Git 配置。');
        }
        if (stripos($output, 'fatal') !== false) {
            throw new RuntimeException('更新失败：' . trim($output));
        }
        clearstatcache(true, $root . '/version.json');
        $newVersion = $this->current();
        return [
            'updated' => true,
            'message' => '更新成功，当前版本：' . ($newVersion['version'] ?? '未知'),
            'new_version' => $newVersion,
        ];
    }

    private function fetchRemoteVersion(): ?array
    {
        $remoteInfo = $this->originRemote();
        if ($remoteInfo === null) {
            throw new RuntimeException('无法从 .git/config 解析远程仓库地址，请确保配置了 origin 远程仓库。');
        }
        
        [$host, $owner, $repo, $platform] = $remoteInfo;
        $apiUrl = $this->buildApiUrl($host, $owner, $repo, $platform);
        
        $payload = $this->requestJson($apiUrl, $platform);
        if (empty($payload)) {
            throw new RuntimeException('无法连接到远程仓库 API：' . $apiUrl);
        }
        $content = base64_decode((string) ($payload['content'] ?? ''), true);
        if ($content === false) {
            throw new RuntimeException('无法解码远程 version.json 内容。');
        }
        $version = json_decode($content, true);
        if (!is_array($version)) {
            throw new RuntimeException('远程 version.json 格式无效。');
        }
        return $version;
    }

    private function originRemote(): ?array
    {
        if (!is_dir(base_path('.git'))) return null;
        $config = (string) @file_get_contents(base_path('.git/config'));
        if ($config === '') return null;
        
        foreach (['origin', 'upstream'] as $remoteName) {
            $pattern = '/\[remote "' . preg_quote($remoteName, '/') . '"\][^\[]*?\n\s*url\s*=\s*(\S+)/s';
            if (preg_match($pattern, $config, $match)) {
                $url = trim($match[1]);
                $parsed = $this->parseRemoteUrl($url);
                if ($parsed !== null) {
                    return $parsed;
                }
            }
        }
        return null;
    }

    private function parseRemoteUrl(string $url): ?array
    {
        // SSH format: git@gitee.com:owner/repo.git or git@github.com:owner/repo.git
        if (preg_match('#^git@([^:]+):([^/]+)/([^/]+?)(?:\.git)?$#i', $url, $parts)) {
            $host = $parts[1];
            $owner = $parts[2];
            $repo = $parts[3];
            $platform = $this->detectPlatform($host);
            return ['https://' . $host, $owner, $repo, $platform];
        }
        
        // HTTP/HTTPS format
        if (preg_match('#^(https?)://([^/]+)(?:/([^/]+)/([^/]+?))(?:\.git)?$#i', $url, $parts)) {
            $host = $parts[2];
            $owner = $parts[3];
            $repo = $parts[4];
            $platform = $this->detectPlatform($host);
            return [$parts[1] . '://' . $host, $owner, $repo, $platform];
        }
        
        return null;
    }

    private function detectPlatform(string $host): string
    {
        $host = strtolower($host);
        if (strpos($host, 'github.com') !== false) return 'github';
        if (strpos($host, 'gitee.com') !== false) return 'gitee';
        if (strpos($host, 'gitlab.com') !== false) return 'gitlab';
        return 'gitea'; // 默认当作 Gitea
    }

    private function buildApiUrl(string $host, string $owner, string $repo, string $platform): string
    {
        $ownerEncoded = rawurlencode($owner);
        $repoEncoded = rawurlencode($repo);
        
        switch ($platform) {
            case 'github':
                // GitHub API: https://api.github.com/repos/{owner}/{repo}/contents/{path}
                return 'https://api.github.com/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/version.json?ref=main';
                
            case 'gitee':
                // Gitee API: https://gitee.com/api/v5/repos/{owner}/{repo}/contents/{path}
                return 'https://gitee.com/api/v5/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/version.json?ref=main';
                
            case 'gitlab':
                // GitLab API: https://gitlab.com/api/v4/projects/{owner}%2F{repo}/repository/files/{path}
                return 'https://gitlab.com/api/v4/projects/' . $ownerEncoded . '%2F' . $repoEncoded . '/repository/files/version.json?ref=main';
                
            case 'gitea':
            default:
                // Gitea API: {host}/api/v1/repos/{owner}/{repo}/contents/{path}
                return $host . '/api/v1/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/version.json?ref=main';
        }
    }

    private function requestJson(string $url, string $platform = 'gitea'): array
    {
        $ch = curl_init($url);
        if ($ch === false) throw new RuntimeException('无法初始化远程版本请求');
        
        $headers = ['Accept: application/json', 'User-Agent: Sushua-Version-Checker'];
        
        // GitHub API 需要特殊的 User-Agent
        if ($platform === 'github') {
            $headers = ['Accept: application/vnd.github.v3+json', 'User-Agent: Sushua-Version-Checker'];
        }
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        apply_curl_ssl_defaults($ch);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($body === false) {
            throw new RuntimeException('cURL 请求失败：' . $error);
        }
        if ($code === 404) {
            throw new RuntimeException('远程仓库中未找到 version.json 文件，请确保远程仓库存在该文件。');
        }
        if ($code < 200 || $code >= 300) {
            throw new RuntimeException('远程服务器返回 HTTP ' . $code);
        }
        
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