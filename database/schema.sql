-- ============================================================
-- Sushua 业务表结构脚本
-- 本文件只包含表结构和初始数据，不创建或切换固定数据库。
-- Web 安装器会先选择数据库名输入框指定的数据库，再执行本文件。
-- 手动执行时，请先自行 CREATE DATABASE / USE 目标数据库。
-- ============================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(120) NOT NULL,
  `value` LONGTEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_settings_key (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_groups (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  group_code VARCHAR(50) NOT NULL,
  name VARCHAR(80) NOT NULL,
  description TEXT NULL,
  threshold_mode VARCHAR(30) NOT NULL DEFAULT 'none',
  threshold_value BIGINT NOT NULL DEFAULT 0,
  downgrade_on_balance TINYINT(1) NOT NULL DEFAULT 0,
  markup_mode VARCHAR(20) NOT NULL DEFAULT 'fixed',
  markup_value DECIMAL(12,4) NOT NULL DEFAULT 0,
  recharge_bonus_rate DECIMAL(12,4) NOT NULL DEFAULT 1.0000,
  allow_api_default TINYINT(1) NOT NULL DEFAULT 0,
  is_default_register TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_group_code (group_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid BIGINT UNSIGNED NOT NULL,
  username VARCHAR(64) NOT NULL,
  nickname VARCHAR(80) NOT NULL,
  qq VARCHAR(20) NOT NULL,
  email VARCHAR(120) NULL,
  mobile VARCHAR(30) NULL,
  avatar VARCHAR(255) NULL,
  password_hash VARCHAR(255) NOT NULL,
  user_group_id INT UNSIGNED NOT NULL,
  account_role VARCHAR(20) NOT NULL DEFAULT 'member',
  strategy_user TINYINT(1) NOT NULL DEFAULT 1,
  strategy_agent TINYINT(1) NOT NULL DEFAULT 0,
  api_key VARCHAR(64) NULL,
  api_key_generated_at DATETIME NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'active',
  ban_until DATETIME NULL,
  ban_reason VARCHAR(255) NULL,
  balance BIGINT NOT NULL DEFAULT 0,
  total_recharge BIGINT NOT NULL DEFAULT 0,
  total_consume BIGINT NOT NULL DEFAULT 0,
  invite_count INT NOT NULL DEFAULT 0,
  inviter_id INT UNSIGNED NULL,
  last_login_at DATETIME NULL,
  last_login_ip VARCHAR(45) NULL,
  deleted_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_users_uid (uid),
  UNIQUE KEY uniq_users_username (username),
  UNIQUE KEY uniq_users_api_key (api_key),
  KEY idx_users_group (user_group_id),
  KEY idx_users_inviter (inviter_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS upstream_accounts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  base_url VARCHAR(255) NOT NULL,
  upstream_uid BIGINT UNSIGNED NOT NULL,
  upstream_api_key VARCHAR(120) NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  is_default TINYINT(1) NOT NULL DEFAULT 1,
  options_json LONGTEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  upstream_key VARCHAR(120) NOT NULL,
  upstream_sign VARCHAR(120) NOT NULL,
  name VARCHAR(160) NOT NULL,
  min_num INT NOT NULL DEFAULT 0,
  max_num INT NOT NULL DEFAULT 0,
  step_num INT NOT NULL DEFAULT 1,
  steps_json LONGTEXT NULL,
  input_json LONGTEXT NULL,
  desc_json LONGTEXT NULL,
  min_delayed INT NULL,
  price_cost BIGINT NOT NULL DEFAULT 0,
  price_cost_delayed BIGINT NULL,
  upstream_level VARCHAR(80) NULL,
  allow_frontend TINYINT(1) NOT NULL DEFAULT 1,
  allow_api TINYINT(1) NOT NULL DEFAULT 1,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  payload_json LONGTEXT NULL,
  synced_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_products_sign (upstream_sign),
  KEY idx_products_sort (sort_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_discounts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  min_quantity INT NOT NULL,
  discount_rate DECIMAL(12,4) NOT NULL DEFAULT 1.0000,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_product_discounts_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_group_product_prices (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_group_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  fixed_price BIGINT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_group_product_price (user_group_id, product_id),
  KEY idx_group_product_prices_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_no VARCHAR(40) NOT NULL,
  upstream_order_no VARCHAR(80) NULL,
  user_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  upstream_sign VARCHAR(120) NOT NULL,
  target_qq VARCHAR(32) NOT NULL,
  quantity INT NOT NULL,
  feed_id VARCHAR(120) NULL,
  is_delayed TINYINT(1) NOT NULL DEFAULT 0,
  extra_input_json LONGTEXT NULL,
  order_method VARCHAR(20) NOT NULL DEFAULT 'web',
  state VARCHAR(40) NOT NULL DEFAULT '处理中',
  upstream_state VARCHAR(80) NULL,
  message VARCHAR(255) NULL,
  start_num VARCHAR(40) NULL,
  current_num VARCHAR(40) NULL,
  finish_num VARCHAR(40) NULL,
  user_price BIGINT NOT NULL DEFAULT 0,
  cost_price BIGINT NOT NULL DEFAULT 0,
  profit BIGINT NOT NULL DEFAULT 0,
  retry_count INT NOT NULL DEFAULT 0,
  refund_status VARCHAR(30) NOT NULL DEFAULT 'none',
  can_retry TINYINT(1) NOT NULL DEFAULT 0,
  can_refund TINYINT(1) NOT NULL DEFAULT 0,
  started_at DATETIME NULL,
  finished_at DATETIME NULL,
  last_sync_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_orders_no (order_no),
  KEY idx_orders_upstream (upstream_order_no),
  KEY idx_orders_user (user_id),
  KEY idx_orders_state (state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_actions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  action_type VARCHAR(30) NOT NULL,
  result_code VARCHAR(40) NULL,
  result_message VARCHAR(255) NULL,
  payload_json LONGTEXT NULL,
  admin_user_id INT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_order_actions_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS balance_ledger (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  change_type VARCHAR(30) NOT NULL,
  amount BIGINT NOT NULL,
  balance_before BIGINT NOT NULL,
  balance_after BIGINT NOT NULL,
  related_type VARCHAR(30) NULL,
  related_id VARCHAR(60) NULL,
  remark VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_balance_user (user_id),
  KEY idx_balance_type (change_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payment_merchants (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  endpoint VARCHAR(255) NOT NULL,
  pid VARCHAR(40) NOT NULL,
  merchant_key VARCHAR(120) NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payment_channels (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(40) NOT NULL,
  name VARCHAR(80) NOT NULL,
  pay_type VARCHAR(40) NOT NULL,
  merchant_id INT UNSIGNED NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_payment_channels_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS recharge_orders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_no VARCHAR(40) NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  channel_id INT UNSIGNED NOT NULL,
  merchant_id INT UNSIGNED NOT NULL,
  amount BIGINT NOT NULL,
  credit_amount BIGINT NOT NULL,
  bonus_amount BIGINT NOT NULL DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  pay_type VARCHAR(40) NOT NULL,
  epay_trade_no VARCHAR(80) NULL,
  raw_json LONGTEXT NULL,
  paid_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_recharge_orders_no (order_no),
  KEY idx_recharge_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS card_keys (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(64) NOT NULL,
  amount BIGINT NOT NULL,
  total_uses INT NOT NULL DEFAULT 1,
  remaining_uses INT NOT NULL DEFAULT 1,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  note VARCHAR(255) NULL,
  created_by INT UNSIGNED NULL,
  destroyed_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_card_keys_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS card_key_usages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  card_key_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  amount BIGINT NOT NULL,
  balance_before BIGINT NOT NULL,
  balance_after BIGINT NOT NULL,
  used_ip VARCHAR(45) NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_card_key_usages_key (card_key_id),
  KEY idx_card_key_usages_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS invite_codes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  code VARCHAR(64) NOT NULL,
  length INT NOT NULL DEFAULT 20,
  price_paid BIGINT NOT NULL DEFAULT 0,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  max_uses INT NOT NULL DEFAULT -1,
  used_count INT NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_invite_codes_code (code),
  KEY idx_invite_codes_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS invite_code_usages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  invite_code_id INT UNSIGNED NOT NULL,
  inviter_id INT UNSIGNED NOT NULL,
  invitee_id INT UNSIGNED NOT NULL,
  became_valid TINYINT(1) NOT NULL DEFAULT 0,
  valid_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_invite_usage_inviter (inviter_id),
  KEY idx_invite_usage_invitee (invitee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS verify_codes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  target VARCHAR(120) NOT NULL,
  channel VARCHAR(20) NOT NULL,
  purpose VARCHAR(40) NOT NULL,
  code VARCHAR(12) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_verify_codes_target (target, channel, purpose)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sms_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  target VARCHAR(40) NOT NULL,
  provider VARCHAR(40) NOT NULL,
  template_code VARCHAR(80) NULL,
  payload_json LONGTEXT NULL,
  result_text TEXT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS email_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  target VARCHAR(120) NOT NULL,
  subject VARCHAR(120) NOT NULL,
  payload_json LONGTEXT NULL,
  result_text TEXT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS system_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  level VARCHAR(20) NOT NULL,
  channel VARCHAR(40) NOT NULL,
  message VARCHAR(255) NOT NULL,
  context_json LONGTEXT NULL,
  user_id INT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_system_logs_channel (channel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE IF NOT EXISTS product_exchange_codes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(160) NOT NULL,
  creator_user_id INT UNSIGNED NOT NULL,
  creator_uid_snapshot BIGINT UNSIGNED NOT NULL,
  creator_name_snapshot VARCHAR(120) NULL,
  product_id INT UNSIGNED NOT NULL,
  product_sign_snapshot VARCHAR(120) NOT NULL,
  product_name_snapshot VARCHAR(160) NOT NULL,
  quantity INT NOT NULL,
  step_num_snapshot INT NOT NULL DEFAULT 1,
  price_snapshot BIGINT NOT NULL DEFAULT 0,
  generation_fee BIGINT NOT NULL DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'unused',
  redeemer_user_id INT UNSIGNED NULL,
  redeemer_ip VARCHAR(45) NULL,
  redeemer_order_id INT UNSIGNED NULL,
  redeemer_order_no VARCHAR(40) NULL,
  extra_json LONGTEXT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_product_exchange_code (code),
  KEY idx_exchange_creator (creator_user_id),
  KEY idx_exchange_order (redeemer_order_id),
  KEY idx_exchange_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_exchange_code_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  exchange_code_id INT UNSIGNED NOT NULL,
  action VARCHAR(40) NOT NULL,
  operator_user_id INT UNSIGNED NULL,
  ip VARCHAR(45) NULL,
  context_json LONGTEXT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_exchange_log_code (exchange_code_id),
  KEY idx_exchange_log_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
