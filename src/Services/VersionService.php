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
        
        $indexPath = base_path('version/index.json');
        if (!is_file($indexPath)) return $fallback;
        
        $index = json_decode((string) file_get_contents($indexPath), true);
        if (!is_array($index) || empty($index['latest'])) return $fallback;
        
        return $this->getVersionData($index['latest']) ?? $fallback;
    }

    public function currentVersion(): string
    {
        return (string) ($this->current()['version'] ?? self::FALLBACK_VERSION);
    }

    public function check(): array
    {
        $current = $this->current();
        $gitAvailable = is_dir(base_path('.git'));
        $updateMethod = $this->detectUpdateMethod();
        $canUpdate = $gitAvailable && $updateMethod !== 'none';
        
        $result = [
            'current' => $current,
            'remote' => null,
            'has_update' => false,
            'git_available' => $gitAvailable,
            'can_update' => $canUpdate,
            'update_method' => $updateMethod,
            'checked_at' => date('Y-m-d H:i:s'),
            'message' => '',
            'manual_command' => '',
        ];
        
        if (!$gitAvailable) {
            $result['message'] = '项目根目录未检测到 .git，在线版本检测不可用。';
            return $result;
        }
        
        if ($updateMethod === 'none') {
            $result['message'] = 'proc_open 和 exec 函数均不可用，无法使用一键更新。请解禁其中一个函数，或使用下方命令手动更新。';
            $result['manual_command'] = 'cd ' . base_path() . ' && git pull origin main';
            return $result;
        }
        
        if (!function_exists('curl_init')) {
            $result['message'] = '服务器未安装 cURL 扩展，在线版本检测不可用。';
            return $result;
        }
        
        $result['message'] = '正在读取远程版本清单。';
        
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
        $result['has_update'] = $this->compareVersions((string) ($remote['latest'] ?? ''), $this->currentVersion()) > 0;
        
        if ($result['has_update']) {
            $remoteFeatures = $this->getRemoteVersionData((string) ($remote['latest'] ?? ''));
            $result['remote_features'] = $remoteFeatures ? ($remoteFeatures['features'] ?? []) : [];
            $result['message'] = '检测到新版本：' . ($remote['latest'] ?? '未知') . '，点击一键更新即可升级。';
        } else {
            $result['message'] = '当前已经是最新版本。';
        }
        
        return $result;
    }

    public function update(): array
    {
        if (!is_dir(base_path('.git'))) {
            throw new RuntimeException('项目根目录未检测到 .git，无法进行在线更新。');
        }
        
        $updateMethod = $this->detectUpdateMethod();
        if ($updateMethod === 'none') {
            $manualCommand = 'cd ' . base_path() . ' && git pull origin main';
            throw new RuntimeException('proc_open 和 exec 函数均不可用，无法使用一键更新。请手动执行：' . $manualCommand);
        }
        
        $remote = $this->fetchRemoteVersion();
        if ($remote === null) {
            throw new RuntimeException('暂时无法读取远程版本清单，请检查 .git/config 中的远程仓库配置。');
        }
        
        $hasUpdate = $this->compareVersions((string) ($remote['latest'] ?? ''), $this->currentVersion()) > 0;
        if (!$hasUpdate) {
            return ['updated' => false, 'message' => '当前已经是最新版本。'];
        }
        
        $root = base_path();
        
        try {
            if ($updateMethod === 'proc_open') {
                $this->updateViaProcess($root);
            } else {
                $this->updateViaExec($root);
            }
        } catch (\Throwable $e) {
            if ($e instanceof RuntimeException) {
                throw $e;
            }
            throw new RuntimeException('更新命令执行失败：' . $e->getMessage());
        }
        
        clearstatcache(true, $root . '/version/index.json');
        $newVersion = $this->current();
        
        return [
            'updated' => true,
            'message' => '更新成功，当前版本：' . ($newVersion['version'] ?? '未知'),
            'new_version' => $newVersion,
        ];
    }

    private function detectUpdateMethod(): string
    {
        // 优先检查 proc_open
        if ($this->canUseProcOpen()) {
            return 'proc_open';
        }
        
        // 其次检查 exec
        if ($this->canUseExec()) {
            return 'exec';
        }
        
        return 'none';
    }

    private function canUseProcOpen(): bool
    {
        // 检查 proc_open 是否被禁用
        if (!function_exists('proc_open')) {
            return false;
        }
        
        // 检查 disable_functions 配置
        $disabledFunctions = array_map('trim', explode(',', ini_get('disable_functions')));
        if (in_array('proc_open', $disabledFunctions, true)) {
            return false;
        }
        
        // 尝试加载 Composer autoloader
        $autoloadFile = base_path('vendor/autoload.php');
        if (file_exists($autoloadFile) && !class_exists('Symfony\Component\Process\Process')) {
            require_once $autoloadFile;
        }
        
        return class_exists('Symfony\Component\Process\Process');
    }

    private function canUseExec(): bool
    {
        if (!function_exists('exec')) {
            return false;
        }
        
        $disabledFunctions = array_map('trim', explode(',', ini_get('disable_functions')));
        return !in_array('exec', $disabledFunctions, true);
    }

    private function updateViaProcess(string $root): void
    {
        $processClass = 'Symfony\Component\Process\Process';
        
        // 执行 git fetch
        $fetchProcess = new $processClass(['git', 'fetch', 'origin', 'main'], $root);
        $fetchProcess->setTimeout(120);
        $fetchProcess->run();
        
        if (!$fetchProcess->isSuccessful()) {
            throw new RuntimeException('git fetch 失败：' . $fetchProcess->getErrorOutput());
        }
        
        // 执行 git reset
        $resetProcess = new $processClass(['git', 'reset', '--hard', 'origin/main'], $root);
        $resetProcess->setTimeout(120);
        $resetProcess->run();
        
        if (!$resetProcess->isSuccessful()) {
            throw new RuntimeException('git reset 失败：' . $resetProcess->getErrorOutput());
        }
    }

    private function updateViaExec(string $root): void
    {
        // 执行 git fetch
        exec('cd ' . escapeshellarg($root) . ' && git fetch origin main 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new RuntimeException('git fetch 失败：' . implode("\n", $output));
        }
        
        // 执行 git reset
        exec('cd ' . escapeshellarg($root) . ' && git reset --hard origin/main 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new RuntimeException('git reset 失败：' . implode("\n", $output));
        }
    }

    private function getVersionData(string $version): ?array
    {
        $file = base_path('version/' . $version . '.json');
        if (!is_file($file)) return null;
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? $data : null;
    }

    private function getRemoteVersionData(string $version): ?array
    {
        $remoteInfo = $this->originRemote();
        if ($remoteInfo === null) return null;
        
        [$host, $owner, $repo, $platform] = $remoteInfo;
        $apiUrl = $this->buildApiUrl($host, $owner, $repo, $platform, $version);
        
        try {
            $payload = $this->requestJson($apiUrl, $platform);
            if (empty($payload)) return null;
            $content = base64_decode((string) ($payload['content'] ?? ''), true);
            if ($content === false) return null;
            $data = json_decode($content, true);
            return is_array($data) ? $data : null;
        } catch (\Throwable $e) {
            return null;
        }
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
            throw new RuntimeException('无法解码远程 version/index.json 内容。');
        }
        $version = json_decode($content, true);
        if (!is_array($version)) {
            throw new RuntimeException('远程 version/index.json 格式无效。');
        }
        return $version;
    }

    private function originRemote(): ?array
    {
        if (!is_dir(base_path('.git'))) return null;
        $config = (string) @file_get_contents(base_path('.git/config'));
        if ($config === '') return null;
        
        foreach (['origin', 'upstream'] as $remoteName) {
            $pattern = '/\[remote "' . preg_quote($remoteName, '/') . '"\]\[^\[]*?\n\s*url\s*=\s*(\S+)/s';
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
        if (preg_match('#^git@([^:]+):([^/]+)/([^/]+?)(?:\.git)?$#i', $url, $parts)) {
            $host = $parts[1];
            $owner = $parts[2];
            $repo = $parts[3];
            $platform = $this->detectPlatform($host);
            return ['https://' . $host, $owner, $repo, $platform];
        }
        
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
        return 'gitea';
    }

    private function buildApiUrl(string $host, string $owner, string $repo, string $platform, string $version = ''): string
    {
        $ownerEncoded = rawurlencode($owner);
        $repoEncoded = rawurlencode($repo);
        
        $path = $version ? 'version/' . rawurlencode($version) . '.json' : 'version/index.json';
        
        switch ($platform) {
            case 'github':
                return 'https://api.github.com/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/' . $path . '?ref=main';
            case 'gitee':
                return 'https://gitee.com/api/v5/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/' . $path . '?ref=main';
            case 'gitlab':
                $encodedPath = str_replace('/', '%2F', $path);
                return 'https://gitlab.com/api/v4/projects/' . $ownerEncoded . '%2F' . $repoEncoded . '/repository/files/' . $encodedPath . '?ref=main';
            case 'gitea':
            default:
                return $host . '/api/v1/repos/' . $ownerEncoded . '/' . $repoEncoded . '/contents/' . $path . '?ref=main';
        }
    }

    private function requestJson(string $url, string $platform = 'gitea'): array
    {
        $ch = curl_init($url);
        if ($ch === false) throw new RuntimeException('无法初始化远程版本请求');
        
        $headers = ['Accept: application/json', 'User-Agent: Sushua-Version-Checker'];
        
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
            throw new RuntimeException('远程仓库中未找到版本文件。');
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