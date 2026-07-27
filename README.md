# XiaoMiSlop 上游加价售卖系统

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
runtime/php83/                    项目内置 PHP 8.3 运行时
start-dev.cmd                     Windows 一键启动开发服务器
start-dev.ps1                     PowerShell 启动开发服务器
php.cmd                           使用项目内置 PHP 执行任意命令
storage/                          配置、安装锁和运行日志
系统对接接口文档.txt              上游接口规范
支付文档.html                     易支付接口规范
```

## 项目内置 PHP

本项目已内置 **Windows x64 NTS 版 PHP 8.3**，无需依赖系统环境变量。

当前可执行文件：

```text
B:\Project Library\Project Software\XiaoMiSlop\runtime\php83\php.exe
```

已配置：

- `date.timezone = Asia/Shanghai`
- `extension_dir = "ext"`
- 已启用扩展：`curl`、`mbstring`、`mysqli`、`openssl`、`pdo_mysql`

常用命令：

```bat
php.cmd -v
php.cmd -m
php.cmd -S 127.0.0.1:3400 router.php
```

也可以直接双击或执行：

```bat
start-dev.cmd
```

或：

```powershell
.\start-dev.ps1
```

## 安装

1. 确保 `storage/` 可写。
2. 准备 MySQL 5.6 或更高版本。
3. 若数据库与应用部署在同一台服务器，推荐先使用 `root` 执行：

```sql
SOURCE database/create_local_user.sql;
```

该脚本会：

- 创建数据库：`xiaomi_slop`
- 创建本地专用账号：`xiaomi_slop`
- 仅授权：`xiaomi_slop.*`
- 推荐安装连接参数：`127.0.0.1:3306`

4. 启动开发服务器：

```bat
start-dev.cmd
```

5. 浏览器打开：

```text
http://127.0.0.1:3400/install
```

6. 安装向导依次完成：环境检查 → 数据库配置 → 站长账号配置。
7. 安装成功后会生成：
   - `storage/config.php`
   - `storage/install.lock`

已安装状态下访问 `/install` 会被拒绝。如需重新安装，必须先手动删除 `storage/install.lock`，并按需删除/重建数据库；不要在生产环境直接开放安装入口。

## 推荐数据库连接参数

如果你直接使用仓库中提供的本地专用账号脚本，安装页建议填写：

```text
数据库主机：127.0.0.1
数据库端口：3306
数据库名：xiaomi_slop
数据库用户名：xiaomi_slop
数据库密码：xiaomi_slop123
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
*/5 * * * * cd /path/to/XiaoMiSlop && /usr/bin/php scripts/sync_products.php >> storage/cron-products.log 2>&1
```

每分钟同步处理中订单：

```cron
* * * * * cd /path/to/XiaoMiSlop && /usr/bin/php scripts/sync_orders.php >> storage/cron-orders.log 2>&1
```

### Windows 计划任务

程序填写项目内置 PHP：

```text
B:\Project Library\Project Software\XiaoMiSlop\runtime\php83\php.exe
```

参数填写：

```text
B:\Project Library\Project Software\XiaoMiSlop\scripts\sync_products.php
B:\Project Library\Project Software\XiaoMiSlop\scripts\sync_orders.php
```

工作目录设置为项目根目录。脚本成功会输出 JSON；失败返回非 0 状态码。

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
curl "http://127.0.0.1:3400/api/queryGoods?uid=10000001&api_key=YOUR_KEY"
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

## 安全与上线检查

- 生产环境关闭 `storage/config.php` 中的 debug。
- `storage/` 不应被 Web 服务器直接下载。
- 为 `/install` 增加访问限制，安装完成后删除或拦截安装入口。
- 使用 HTTPS，尤其是登录、API Key、支付回调和 SMTP 配置。
- 上游和易支付 endpoint 必须使用可信 HTTPS 地址；不要把真实密钥提交到版本库。
- 定期备份数据库，重点保留 `balance_ledger`、`order_actions`、`card_redemptions`、`system_logs`。

## 当前验证方式

当前仓库已完成 PHP 语法级静态检查。现在可以直接使用项目内置 PHP 继续做端到端测试：注册、管理员强制验证码、商品同步、普通/对接下单、订单同步/补单/退款、卡密兑换和易支付回调。
