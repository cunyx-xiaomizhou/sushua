<?php
declare(strict_types=1);

require_once __DIR__ . '/src/bootstrap.php';

use XiaoMiSlop\Controllers\AppController;
use XiaoMiSlop\Core\Config;
use XiaoMiSlop\Core\Request;
use XiaoMiSlop\Core\Response;

$path = request_path();
if ($path === '/install' || str_starts_with($path, '/install/')) {
    require __DIR__ . '/install/index.php';
    exit;
}
if (!Config::isInstalled()) {
    Response::redirect('/install');
}

try {
    (new AppController())->dispatch(new Request());
} catch (Throwable $e) {
    $path = request_path();
    $isApi = str_starts_with($path, '/api/') || preg_match('#^/[^/]+/api/#', $path) === 1;
    if ($isApi) {
        Response::error($e->getMessage(), 500);
    }
    http_response_code(500);
    echo '<!doctype html><meta charset="utf-8"><title>系统错误</title><style>body{font-family:system-ui;background:#f8fafc;padding:40px;color:#172033}.box{max-width:720px;margin:auto;background:white;border-radius:20px;padding:28px;box-shadow:0 12px 40px #17203318}code{display:block;background:#f1f5f9;padding:12px;border-radius:12px;white-space:pre-wrap}</style><div class="box"><h1>系统暂时不可用</h1><p>请检查数据库配置和日志文件。</p><code>'.htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8').'</code></div>';
}
