# Sushua 上游加价售卖系统

基于 **PHP 8.3 + MySQL 5.6+ + Vue 3** 的上游商品加价售卖/代理对接系统。系统内部所有余额、价格、充值和流水统一使用“额度”，不在业务页面显示其他货币单位。

## 功能概览

- 上游账户配置、商品同步、商品前台上架/对接开关
- 用户组门槛、固定/百分比加价、充值赠送、默认注册组、余额降级策略
- User / Agent / Owner / Admin 共用用户表，Agent 才能生成 API Key
- 普通下单、对接下单、动态商品参数、QQ 头像预览、说说列表与图片代理
- 订单状态同步、订单详情、上游补单、上游退单、管理员仅退款
- 每笔订单保存用户花费、上游成本和利润；所有资金变化写入 `balance_ledger`
- 易支付多商户、多支付通道、异步回调验签和重复回调防护
- 一次性卡密、多次通兑卡密（`-1` 表示无限次，`0` 表示不可兑换）
- SMTP 发信、腾讯云/阿里云短信、自定义 HTTP 短信扩展接口
- 邀请码、有效邀请、SEO、图片/极验/短信/邮件登录开关、可自定义后台路径
- 安装向导、系统日志和资金流水日志

## 目录

```text
default.php                       Web 入口
router.php                        PHP 内置服务器路由
install/index.php                 安装入口
src/                              核心代码、控制器和服务
views/app.php                     Vue 前台/管理后台单页
database/schema.sql               MySQL 建表脚本
database/create_local_user.sql    本地 MySQL 专用账号初始化脚本（root 执行一次）
scripts/sync_products.php         CLI 商品同步
scripts/sync_orders.php           CLI 订单状态同步
start-dev.sh                      Linux 开发服务器启动脚本
deploy/nginx/                     Linux Nginx + PHP-FPM 配置示例
storage/                          配置、安装锁和运行日志
系统对接接口文档.txt              上游接口规范
支付文档.html                     易支付接口规范
```

## PHP 环境要求

项目仓库不再包含 PHP 运行环境。部署或本地开发前，请自行安装 **PHP 8.3 或更高版本**，并确保 `php` 命令已加入系统 `PATH`。

必须启用以下扩展：

- `curl`
- `mbstring`
- `mysqli`
- `openssl`
- `pdo_mysql`

建议设置：

```ini
date.timezone = Asia/Shanghai
```

常用命令：

```bash
php -v
php -m
chmod +x ./start-dev.sh
./start-dev.sh
```

启动脚本使用服务器上已安装的系统 PHP，不会安装、停止或修改其他项目的 PHP/FPM 进程。可通过 `PORT=3401 ./start-dev.sh` 临时指定监听端口。
## Web 服务器路由

首页、登录、注册、用户后台、管理后台和 API 都通过 `default.php` 统一分发。生产环境必须把不存在的文件或目录重写到该入口，否则 `/login`、`/register`、`/user` 等按钮会出现 404。

- Nginx + PHP-FPM：复制并按服务器实际路径修改 `deploy/nginx/sushua.conf.example`，确认 `root` 与 `fastcgi_pass` 指向当前站点和 PHP 8.3 FPM socket，然后只重载该站点所属的 Nginx 配置。
- Apache：仓库根目录保留 `.htaccess` 兼容配置，需要启用 `mod_rewrite` 并允许目录级重写（`AllowOverride All`）。
- 无法立即配置重写时：页面按钮、验证码和前端 API 会自动回退为 `/default.php?route=...`，避免由 Web 服务器直接返回 404；生产环境仍建议启用上述 Nginx/Apache 配置，以支持手工访问 `/login` 等简洁地址。

PHP 内置服务器请使用 `./start-dev.sh`，不要直接省略 `router.php`。
## 安装

1. 确保 `storage/` 可写。
2. 准备 MySQL 5.6 或更高版本。
3. 若数据库与应用部署在同一台服务器，推荐先使用 `root` 执行：

```sql
SOURCE database/create_local_user.sql;
```

该脚本会：

- 创建数据库：`sushua`
- 创建本地专用账号：`sushua`
- 仅授权：`sushua.*`
- 推荐安装连接参数：`127.0.0.1:3306`

4. 启动开发服务器：

```bash
chmod +x ./start-dev.sh
./start-dev.sh
```

5. 浏览器打开：

```text
http://服务器IP:3400/install
```

6. 安装向导依次完成：环境检查 → 数据库配置 → 站长账号配置。
   - 安装器会使用数据库配置页填写的数据库名，不会强制切换到 `sushua`。
7. 安装成功后会生成：
   - `storage/config.php`
   - `storage/install.lock`

已安装状态下访问 `/install` 会被拒绝。如需重新安装，必须先手动删除 `storage/install.lock`，并按需删除/重建数据库；不要在生产环境直接开放安装入口。

## 手动执行 SQL 时的注意事项

如果在 MySQL Workbench 或其他 SQL 编辑器中直接打开 `database/schema.sql`，请先选择数据库，否则会出现 `Error Code: 1046. No database selected`。

推荐做法：

1. 先用 root 执行 `database/create_local_user.sql`；该脚本最后会执行 `USE sushua`。
2. 在左侧 `SCHEMAS` 中双击 `sushua`，让它变成默认数据库。
3. 再执行 `database/schema.sql`。

也可以先手动执行：

```sql
CREATE DATABASE IF NOT EXISTS `sushua` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sushua`;
```

MySQL 命令行可以使用辅助脚本：

```sql
SOURCE database/import_local.sql;
```

## 推荐数据库连接参数

如果你直接使用仓库中提供的本地专用账号脚本，安装页建议填写：

```text
数据库主机：127.0.0.1
数据库端口：3306
数据库名：sushua
数据库用户名：sushua
数据库密码：sushua123
```

> 生产环境请务必改成你自己的强密码。

## 首次配置建议

1. 在后台“上游配置”填写上游 Base URL、上游 UID、上游 API Key。
2. 点击“商品同步”，检查商品成本、动态 input 字段和 `sign`。
3. 在“商品管理”控制是否前台上架、是否允许 API 对接。
4. 创建用户组并设置加价模式：
   - `fixed`：按计价单位固定增加额度
   - `percent`：按比例增加；输入 `0.7` 表示增加 70%，输入 `70` 也会按 70% 处理
5. 在“系统设置”设置额度名称、前台下单/对接总开关、说说图片来源和验证码策略。
6. 在“对接设置”设置 API Key 生成条件；只有具备 Agent 策略的用户才会生成/重置 API Key。

## 易支付

### 商户

在后台配置多个易支付商户：商户名称、网关地址、商户 ID、商户密钥和启用状态。网关地址填写站点根地址，例如 `https://pay.example.com`，系统会请求其 `/mapi.php`。

### 通道

每个支付通道绑定一个易支付商户。比如：

- `wxpay` → 微信 → 1 号商户
- `alipay` → 支付宝 → 2 号商户

创建充值订单时，系统会按通道绑定的商户发起请求，并使用 MD5 签名。充值回调地址为：

```text
/internal/recharge/notify
```

回调只有 `trade_status=TRADE_SUCCESS` 才会到账，重复回调不会重复增加余额。易支付网关下单失败时，充值订单会被标记为 `failed`，不会产生余额流水。

系统内部换算规则固定为：

```text
网关金额 = 充值额度 / 10000
```

界面和数据库仍只展示/保存额度。

## 卡密

管理员可批量生成卡密并指定每张卡的额度和使用次数：

- `1`：一次性普通卡密；兑换后变为 `0`
- `-1`：无限次通兑卡密；每次兑换都记录使用人
- `N > 1`：最多兑换 N 次，每次兑换减 1
- `0`：不可兑换

所有兑换均写入卡密兑换记录和用户余额流水。后台删除采用软删除/停用方式，不破坏历史记录。

## 定时任务

### Linux cron

每 5 分钟同步商品：

```cron
*/5 * * * * cd /path/to/Sushua && /usr/bin/php scripts/sync_products.php >> storage/cron-products.log 2>&1
```

每分钟同步处理中订单：

```cron
* * * * * cd /path/to/Sushua && /usr/bin/php scripts/sync_orders.php >> storage/cron-orders.log 2>&1
```

## SMTP 与短信扩展

### SMTP

在系统设置中填写 SMTP host、port、username、password、encryption、from_email、from_name。发信服务位于：

```text
src/Services/SmtpMailer.php
```

### 短信

内置：

- `TencentCloudProvider`：腾讯云短信
- `AliyunProvider`：阿里云短信
- `CustomHttpProvider`：自定义 HTTP 短信

统一接口：

```php
interface ProviderInterface
{
    public function send(string $mobile, string $templateCode, array $params = []): array;
}
```

新增短信商时，在 `src/Services/Sms/` 实现 `ProviderInterface`，然后在 `SmsManager` 中注册 provider 名称。配置内容保存在 `settings` 表的 `sms_config` JSON 中。

## API 对接

所有公开对接接口都使用 `uid` 和 `api_key` 参数，只有满足后台设置、用户组默认值或用户单独覆盖条件时才允许调用。

兼容路径包括：

```text
/api/success
/api/getBalance
/api/queryGoods
/api/createOrder
/api/retryOrder
/api/refundOrder
/api/queryOrder
/api/orderList
/api/queryFeed
```

同时提供下划线命名兼容路径，例如 `/api/get_balance`、`/api/create_order`。

示例：

```bash
curl "http://服务器IP:3400/api/queryGoods?uid=10000001&api_key=YOUR_KEY"
```

统一返回结构：

```json
{
  "code": 200,
  "msg": "接口调用成功",
  "data": [],
  "time": 1720000000
}
```

商品的 `input` 字段由上游声明。系统只会把商品声明的字段传回上游，`qq`、`num`、`feed_id`、`sign` 等标准字段由系统覆盖，避免客户端通过同名参数篡改。

## 订单规则

- 商品必须同时满足系统开关、商品启用状态和对应下单方式开关。
- 订单保存成本、用户花费、利润和订单操作记录。
- 失败订单最多允许补单一次。页面会显示醒目提示：

> 因忘记开权限或者其他原因导致失败的，可申请补单一次，补单后还失败的将不再支持再次补单。

- 退款会按订单实际用户花费返还额度，并写入余额流水。
- 管理员“仅退款”只处理站内余额，不向上游发起退单。

## 外网访问说明

默认启动脚本现在监听 `0.0.0.0:3400`，表示允许其他机器访问当前服务器。实际能否从局域网或外网访问，还取决于：

- Linux 防火墙（如 ufw/firewalld）是否放行 `3400` 端口
- 云服务器安全组是否放行 `3400` 端口
- 是否使用正确的服务器 IP / 域名访问

访问示例：

```text
http://服务器IP:3400/install
http://服务器IP:3400/
```

> 注意：`0.0.0.0` 只是监听地址，不是浏览器访问地址。浏览器里请使用实际服务器 IP 或域名。

## 安全与上线检查

- 生产环境关闭 `storage/config.php` 中的 debug。
- `storage/` 不应被 Web 服务器直接下载。
- 为 `/install` 增加访问限制，安装完成后删除或拦截安装入口。
- 使用 HTTPS，尤其是登录、API Key、支付回调和 SMTP 配置。
- 上游和易支付 endpoint 必须使用可信 HTTPS 地址；不要把真实密钥提交到版本库。
- 定期备份数据库，重点保留 `balance_ledger`、`order_actions`、`card_redemptions`、`system_logs`。

## 当前验证方式

当前仓库已完成 PHP 语法级静态检查。安装并配置系统 PHP 后，可继续做端到端测试：注册、管理员强制验证码、商品同步、普通/对接下单、订单同步/补单/退款、卡密兑换和易支付回调。
