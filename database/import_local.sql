-- ============================================================
-- 本机 MySQL 一键导入辅助脚本
-- 请在项目根目录执行，或将下面内容复制到 MySQL 客户端执行。
-- 需要使用具备 CREATE DATABASE / 建表权限的账号执行。
-- ============================================================

CREATE DATABASE IF NOT EXISTS `xiaomi_slop`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `xiaomi_slop`;

-- MySQL 命令行客户端支持 SOURCE；Workbench 中可直接打开并执行 schema.sql，
-- 或先在左侧 SCHEMAS 双击 xiaomi_slop，再执行 schema.sql。
SOURCE database/schema.sql;
