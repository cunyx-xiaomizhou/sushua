-- ============================================================
-- 创建 XiaoMiSlop 本地专用数据库和数据库用户（请使用 root 执行一次）
-- 应用和 MySQL 部署在同一台服务器时，服务端地址固定使用 127.0.0.1:3306
-- 请按需修改数据库名、用户名和密码后再执行到生产环境
-- ============================================================

-- 1. 创建业务数据库
CREATE DATABASE IF NOT EXISTS `xiaomi_slop`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- 2. 创建本地数据库用户（如已存在则跳过）
CREATE USER IF NOT EXISTS 'xiaomi_slop'@'localhost' IDENTIFIED BY 'xiaomi_slop123';
CREATE USER IF NOT EXISTS 'xiaomi_slop'@'127.0.0.1' IDENTIFIED BY 'xiaomi_slop123';

-- 3. 仅授权 xiaomi_slop 库的全部权限（不能访问其他库）
GRANT ALL PRIVILEGES ON `xiaomi_slop`.* TO 'xiaomi_slop'@'localhost';
GRANT ALL PRIVILEGES ON `xiaomi_slop`.* TO 'xiaomi_slop'@'127.0.0.1';

-- 4. 刷新权限
FLUSH PRIVILEGES;

-- 5. 选中业务数据库，后续可在同一个 SQL 会话中继续执行 schema.sql
USE `xiaomi_slop`;
