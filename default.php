<?php
declare(strict_types=1);

require_once __DIR__ . '/src/bootstrap.php';

use Sushua\Core\Config;

if (!Config::isInstalled()) {
    define('SUSHUA_INSTALL_GATEWAY', true);
    require __DIR__ . '/install/index.php';
    exit;
}

$entryRepairMessage = '';
$applicationEntry = __DIR__ . '/index.php';
if (!is_file($applicationEntry)) {
    $template = __DIR__ . '/install/application.stub';
    $contents = file_get_contents($template);
    if ($contents !== false && trim($contents) !== '' && is_writable(__DIR__) && file_put_contents($applicationEntry, $contents, LOCK_EX) !== false) {
        @chmod($applicationEntry, 0644);
        $entryRepairMessage = '检测到这是从旧版本升级的已安装站点，系统已补充生成根目录 index.php。现在刷新网站首页即可正常进入系统。';
    } else {
        $entryRepairMessage = '根目录 index.php 不存在且自动生成失败，请确认项目根目录可写，并检查 install/application.stub 是否完整。';
    }
}

http_response_code(409);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>系统已安装</title>
<style>
:root{color-scheme:light}*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:#f4f7fb;color:#172033;font-family:Inter,system-ui,"Microsoft Yahei",sans-serif}.box{width:min(720px,100%);padding:32px;border:1px solid #e2e8f0;border-radius:24px;background:#fff;box-shadow:0 20px 60px rgba(23,32,51,.12)}h1{margin:0 0 14px;font-size:30px}p{margin:10px 0;color:#526176;line-height:1.8}code{display:block;margin:18px 0;padding:14px 16px;border-radius:12px;background:#f1f5f9;color:#b42318;overflow-wrap:anywhere}.hint{padding:14px 16px;border-radius:12px;background:#fff7ed;color:#9a4b0b}
</style>
</head>
<body>
<div class="box">
    <h1>系统已经安装完成</h1>
    <p><strong>default.php 仅用于安装前引导，不再承担任何系统页面或接口的路由分发。</strong></p>
    <?php if ($entryRepairMessage !== ''): ?>
        <p class="hint"><?= htmlspecialchars($entryRepairMessage, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <p class="hint">如需重新安装，请先备份数据库与配置，然后手动删除下面的安装锁文件。删除后再次访问 default.php，安装入口才会重新生效。</p>
    <code><?= htmlspecialchars(storage_path('install.lock'), ENT_QUOTES, 'UTF-8') ?></code>
    <p>正常访问系统时请使用网站首页地址，不要访问 default.php。</p>
</div>
</body>
</html>
