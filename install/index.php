<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/bootstrap.php';

use Sushua\Core\Config;
use Sushua\Core\Response;
use Sushua\Services\SettingsService;

if (Config::isInstalled()) {
    Response::redirect(route_url('/'));
}

$environmentChecks = [
    'php' => version_compare(PHP_VERSION, '8.3.0', '>='),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'curl' => extension_loaded('curl'),
    'storage' => is_writable(storage_path()),
];

$assertEnvironment = static function () use ($environmentChecks): void {
    if (!$environmentChecks['php']) {
        throw new RuntimeException('安装要求 PHP 8.3 或更高版本');
    }
    if (!$environmentChecks['pdo_mysql']) {
        throw new RuntimeException('安装要求启用 pdo_mysql 扩展');
    }
    if (!$environmentChecks['curl']) {
        throw new RuntimeException('安装要求启用 curl 扩展');
    }
    if (!$environmentChecks['storage']) {
        throw new RuntimeException('storage 目录不可写，请先修复权限');
    }
};

$error = '';
$success = '';
$step = (int) ($_POST['step'] ?? 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($step === 2) {
            $assertEnvironment();
            $host = trim((string) ($_POST['db_host'] ?? '127.0.0.1'));
            $port = (int) ($_POST['db_port'] ?? 3306);
            $database = trim((string) ($_POST['db_name'] ?? ''));
            $username = trim((string) ($_POST['db_user'] ?? ''));
            $password = (string) ($_POST['db_password'] ?? '');
            if ($database === '' || $username === '') {
                throw new RuntimeException('数据库名和用户名不能为空');
            }
            if (!preg_match('/^[\p{L}\p{N}_$-]{1,64}$/u', $database)) {
                throw new RuntimeException('数据库名只能包含字母、数字、下划线、短横线或美元符号，长度为 1-64 个字符');
            }

            $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $databaseIdentifier = '`' . str_replace('`', '``', $database) . '`';
            try {
                $pdo->exec("USE {$databaseIdentifier}");
            } catch (PDOException $exception) {
                $driverCode = (int) ($exception->errorInfo[1] ?? 0);
                if ($driverCode !== 1049) {
                    throw $exception;
                }
                $pdo->exec("CREATE DATABASE IF NOT EXISTS {$databaseIdentifier} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE {$databaseIdentifier}");
            }

            $sqlPath = dirname(__DIR__) . '/database/schema.sql';
            $sql = file_get_contents($sqlPath);
            if ($sql === false) {
                throw new RuntimeException('无法读取数据库结构文件：' . $sqlPath);
            }
            $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
            foreach (array_filter(array_map('trim', preg_split('/;\s*(?:\r?\n|$)/', $sql))) as $statement) {
                if ($statement !== '') {
                    $pdo->exec($statement);
                }
            }
            $config = [
                'installed' => false,
                'app' => [
                    'name' => trim((string) ($_POST['site_name'] ?? '粥粥速刷系统')),
                    'timezone' => 'Asia/Shanghai',
                    'debug' => true,
                ],
                'database' => [
                    'host' => $host,
                    'port' => $port,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8mb4',
                ],
            ];
            file_put_contents(storage_path('config.pending.php'), '<?php return ' . var_export($config, true) . ';');
            $success = '数据库连接成功，结构已导入。';
            $step = 3;
        } elseif ($step === 3) {
            $assertEnvironment();
            $pending = storage_path('config.pending.php');
            if (!file_exists($pending)) {
                throw new RuntimeException('请先完成数据库配置');
            }
            $config = require $pending;
            $account = trim((string) ($_POST['owner_username'] ?? ''));
            $password = (string) ($_POST['owner_password'] ?? '');
            $ownerQq = trim((string) ($_POST['owner_qq'] ?? ''));
            $adminPathInput = trim((string) ($_POST['admin_path'] ?? '/admin'));
            $adminPath = '/' . trim((string) preg_replace('#/+#', '/', $adminPathInput), '/');

            if (!preg_match('/^[A-Za-z0-9]{4,32}$/', $account)) {
                throw new RuntimeException('站长账号只能使用4-32位英文数字');
            }
            if (strlen($password) < 8) {
                throw new RuntimeException('站长密码至少8位');
            }
            if (!preg_match('/^[1-9][0-9]{4,14}$/', $ownerQq)) {
                throw new RuntimeException('请填写有效的站长 QQ 号');
            }
            if ($adminPath === '/' || !preg_match('#^/(?:[A-Za-z0-9_-]{1,40})(?:/[A-Za-z0-9_-]{1,40})*$#', $adminPath)) {
                throw new RuntimeException('后台路径可包含多级目录，每段只能使用 1-40 位英文、数字、下划线或中划线');
            }

            $config['installed'] = true;
            file_put_contents(storage_path('config.php'), '<?php return ' . var_export($config, true) . ';');
            @unlink($pending);
            \Sushua\Core\Config::reset();
            $pdo = \Sushua\Core\Database::connection();
            $now = date('Y-m-d H:i:s');
            $pdo->prepare('INSERT INTO user_groups (group_code,name,description,threshold_mode,threshold_value,markup_mode,markup_value,recharge_bonus_rate,allow_api_default,is_default_register,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)')
                ->execute(['default', '普通用户', '默认注册用户组', 'none', 0, 'fixed', 0, 1, 0, 1, 0, $now, $now]);
            $groupId = (int) $pdo->lastInsertId();
            $uid = random_int(10000000, 99999999);
            $avatar = 'https://q1.qlogo.cn/g?b=qq&nk=' . $ownerQq . '&s=100';
            $pdo->prepare('INSERT INTO users (uid,username,nickname,qq,avatar,password_hash,user_group_id,account_role,strategy_user,strategy_agent,api_key,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([$uid, $account, '站长', $ownerQq, $avatar, password_hash($password, PASSWORD_DEFAULT), $groupId, 'owner', 0, 0, null, 'active', $now, $now]);
            $settings = new SettingsService();
            $settings->seedDefaults();
            $settings->set('admin_path', $adminPath);
            $settings->set('site_name', (string) $config['app']['name']);
            file_put_contents(storage_path('install.lock'), 'installed at ' . $now . PHP_EOL);
            $success = '安装完成，即将进入系统。';
            $step = 4;
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>安装 · 粥粥速刷系统</title>
<style>
:root{--bg:#f4f7fb;--ink:#132238;--muted:#63738a;--primary:#5b5cf0;--card:#fff}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,"Microsoft Yahei",sans-serif;background:radial-gradient(circle at 20% 0,#e9e8ff,transparent 35%),var(--bg);color:var(--ink);min-height:100vh;display:grid;place-items:center;padding:24px}.wrap{width:min(1060px,100%);background:#ffffffd9;border:1px solid #e5eaf4;backdrop-filter:blur(18px);border-radius:28px;box-shadow:0 30px 60px rgba(24,37,71,.12);display:grid;grid-template-columns:300px 1fr;overflow:hidden}.side{padding:36px 30px;background:linear-gradient(180deg,#1d2450,#4c50d8 60%,#7d77ff);color:#fff}.side h1{margin:0 0 12px;font-size:34px}.side p{margin:0;color:#e7eaff;line-height:1.7}.steps{margin-top:28px;display:flex;flex-direction:column;gap:12px}.steps div{padding:14px 15px;border-radius:15px;background:#ffffff14;border:1px solid #ffffff18;display:flex;align-items:center;gap:12px}.steps .num{width:30px;height:30px;border-radius:50%;display:grid;place-items:center;background:#ffffff24;font-weight:800}.steps .active{background:#fff;color:#24325b}.main{padding:34px 38px}.eyebrow{display:inline-flex;padding:6px 12px;border-radius:999px;background:#ecedff;color:#5455df;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.main h2{margin:18px 0 8px;font-size:30px}.muted{color:var(--muted)}.alert{padding:13px 15px;border-radius:12px;margin:20px 0;background:#fff4e5;color:#a65000}.success{background:#ecfdf3;color:#137a43}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.field{margin:14px 0}.field label{display:block;font-size:13px;font-weight:700;margin-bottom:7px}.field input{width:100%;padding:13px 14px;border:1px solid #dce2ed;border-radius:12px;font:inherit;outline:none}.field input:focus{border-color:var(--primary);box-shadow:0 0 0 4px #5b5cf018}.actions{display:flex;justify-content:space-between;gap:12px;margin-top:26px}.btn{border:0;border-radius:12px;padding:13px 18px;font-weight:700;cursor:pointer;background:#eef0f8;color:#26324a;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.btn.primary{background:var(--primary);color:#fff}.check{padding:18px;border:1px solid #e4e9f2;border-radius:16px;background:#fafbff;margin:22px 0}.check div{display:flex;justify-content:space-between;padding:7px 0}.ok{color:#168852}.bad{color:#b42318}.hint{font-size:13px;color:#63738a;line-height:1.7;margin-top:6px}@media(max-width:720px){.wrap{grid-template-columns:1fr}.side{padding:24px}.steps{display:none}.main{padding:28px}.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
    <aside class="side">
        <h1>粥粥速刷</h1>
        <p>安装上游对接、加价售卖与额度管理系统。</p>
        <div class="steps">
            <div class="<?= $step===1?'active':'' ?>"><span class="num">1</span>环境检查</div>
            <div class="<?= $step===2?'active':'' ?>"><span class="num">2</span>数据库配置</div>
            <div class="<?= $step===3?'active':'' ?>"><span class="num">3</span>站长账号</div>
            <div class="<?= $step===4?'active':'' ?>"><span class="num">4</span>完成安装</div>
        </div>
    </aside>
    <main class="main">
        <div class="eyebrow">Installer</div>
        <h2><?= $step===1?'准备安装':($step===2?'数据库配置':($step===3?'创建站长账号':'安装完成')) ?></h2>
        <p class="muted"><?= $step===1?'先确认运行环境满足 PHP 8.3、PDO、CURL 与可写目录要求。':($step===2?'配置 MySQL 5.6 或更高版本数据库。':($step===3?'设置后台路径、站长账号、密码与 QQ 头像来源。':'系统已就绪，可以开始使用。')) ?></p>

        <?php if($error): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if($step===1): ?>
            <div class="check">
                <div><span>PHP 版本</span><strong class="<?= $environmentChecks['php']?'ok':'bad' ?>"><?= PHP_VERSION ?></strong></div>
                <div><span>PDO MySQL</span><strong class="<?= $environmentChecks['pdo_mysql']?'ok':'bad' ?>"><?= $environmentChecks['pdo_mysql']?'可用':'缺少' ?></strong></div>
                <div><span>CURL</span><strong class="<?= $environmentChecks['curl']?'ok':'bad' ?>"><?= $environmentChecks['curl']?'可用':'缺少' ?></strong></div>
                <div><span>storage 可写</span><strong class="<?= $environmentChecks['storage']?'ok':'bad' ?>"><?= $environmentChecks['storage']?'可用':'不可写' ?></strong></div>
            </div>
            <form method="post">
                <input type="hidden" name="step" value="2">
                <div class="actions"><span class="muted">安装不会覆盖已有数据。</span><button class="btn primary">开始配置</button></div>
            </form>
        <?php elseif($step===2): ?>
            <form method="post">
                <input type="hidden" name="step" value="2">
                <div class="grid">
                    <div class="field"><label>站点名称</label><input name="site_name" value="粥粥速刷系统" required></div>
                    <div class="field"><label>数据库主机</label><input name="db_host" value="127.0.0.1" required></div>
                    <div class="field"><label>数据库端口</label><input name="db_port" value="3306" required></div>
                    <div class="field"><label>数据库名</label><input name="db_name" value="<?= htmlspecialchars((string) ($_POST['db_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="请输入数据库名" required></div>
                    <div class="field"><label>数据库用户名</label><input name="db_user" value="<?= htmlspecialchars((string) ($_POST['db_user'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="请输入数据库用户名" required></div>
                    <div class="field"><label>数据库密码</label><input type="password" name="db_password"></div>
                </div>
                <div class="actions"><span class="muted">支持 MySQL 5.6+，同机部署建议使用 127.0.0.1:3306，可先执行 database/create_local_user.sql 创建专用账号。</span><button class="btn primary">导入数据库并继续</button></div>
            </form>
        <?php elseif($step===3): ?>
            <form method="post">
                <input type="hidden" name="step" value="3">
                <div class="field"><label>后台路径</label><input name="admin_path" value="/admin" pattern="/?[A-Za-z0-9_-]{1,40}(/[A-Za-z0-9_-]{1,40})*" placeholder="/admin 或 /admin/system" required></div>
                <div class="grid">
                    <div class="field"><label>站长账号</label><input name="owner_username" pattern="[A-Za-z0-9]{4,32}" required></div>
                    <div class="field"><label>站长密码</label><input type="password" name="owner_password" minlength="8" required></div>
                    <div class="field"><label>站长 QQ</label><input name="owner_qq" pattern="[1-9][0-9]{4,14}" required></div>
                </div>
                <div class="hint">站长 QQ 将用于默认头像地址生成，用户安装完成后也可以在个人资料里重新修改 QQ 和头像。</div>
                <div class="actions"><span class="muted">后台登录强制图片验证码。</span><button class="btn primary">完成安装</button></div>
            </form>
        <?php else: ?>
            <div class="check">
                <div><span>安装锁</span><strong class="ok">已生成</strong></div>
                <div><span>站点名称</span><strong><?= htmlspecialchars((string) (require storage_path('config.php'))['app']['name'], ENT_QUOTES, 'UTF-8') ?></strong></div>
            </div>
            <div class="actions"><a class="btn primary" href="<?= htmlspecialchars(route_url('/'), ENT_QUOTES, 'UTF-8') ?>">进入系统</a></div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
