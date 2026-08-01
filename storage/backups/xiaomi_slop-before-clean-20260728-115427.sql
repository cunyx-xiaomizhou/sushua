-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: xiaomi_slop
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `xiaomi_slop`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `xiaomi_slop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `xiaomi_slop`;

--
-- Table structure for table `balance_ledger`
--

DROP TABLE IF EXISTS `balance_ledger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `balance_ledger` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `change_type` varchar(30) NOT NULL,
  `amount` bigint NOT NULL,
  `balance_before` bigint NOT NULL,
  `balance_after` bigint NOT NULL,
  `related_type` varchar(30) DEFAULT NULL,
  `related_id` varchar(60) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_balance_user` (`user_id`),
  KEY `idx_balance_type` (`change_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `balance_ledger`
--

LOCK TABLES `balance_ledger` WRITE;
/*!40000 ALTER TABLE `balance_ledger` DISABLE KEYS */;
INSERT INTO `balance_ledger` VALUES (1,1,'admin_adjustment',30000,0,30000,'admin_adjustment','1:1','管理员调整用户余额','2026-07-27 17:30:34'),(2,1,'order_consume',-1000,30000,29000,'order','OD202607271731437632','速刷下单扣费','2026-07-27 17:31:43'),(3,1,'order_consume',-1000,29000,28000,'order','OD202607271757248919','速刷下单扣费','2026-07-27 17:57:24'),(4,1,'refund',1000,28000,29000,'order','OD202607271757248919','订单退款返还','2026-07-27 17:57:48'),(5,1,'recharge',3000,29000,32000,'recharge_order','RC202607271835355140','在线充值到账','2026-07-27 18:36:27');
/*!40000 ALTER TABLE `balance_ledger` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_key_usages`
--

DROP TABLE IF EXISTS `card_key_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_key_usages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `card_key_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `amount` bigint NOT NULL,
  `balance_before` bigint NOT NULL,
  `balance_after` bigint NOT NULL,
  `used_ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_card_key_usages_key` (`card_key_id`),
  KEY `idx_card_key_usages_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_key_usages`
--

LOCK TABLES `card_key_usages` WRITE;
/*!40000 ALTER TABLE `card_key_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_key_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_keys`
--

DROP TABLE IF EXISTS `card_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_keys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `amount` bigint NOT NULL,
  `total_uses` int NOT NULL DEFAULT '1',
  `remaining_uses` int NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `note` varchar(255) DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `destroyed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_card_keys_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_keys`
--

LOCK TABLES `card_keys` WRITE;
/*!40000 ALTER TABLE `card_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(120) NOT NULL,
  `subject` varchar(120) NOT NULL,
  `payload_json` longtext,
  `result_text` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invite_code_usages`
--

DROP TABLE IF EXISTS `invite_code_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invite_code_usages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invite_code_id` int unsigned NOT NULL,
  `inviter_id` int unsigned NOT NULL,
  `invitee_id` int unsigned NOT NULL,
  `became_valid` tinyint(1) NOT NULL DEFAULT '0',
  `valid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_invite_usage_inviter` (`inviter_id`),
  KEY `idx_invite_usage_invitee` (`invitee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invite_code_usages`
--

LOCK TABLES `invite_code_usages` WRITE;
/*!40000 ALTER TABLE `invite_code_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `invite_code_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invite_codes`
--

DROP TABLE IF EXISTS `invite_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invite_codes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `code` varchar(64) NOT NULL,
  `length` int NOT NULL DEFAULT '20',
  `price_paid` bigint NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `max_uses` int NOT NULL DEFAULT '-1',
  `used_count` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_invite_codes_code` (`code`),
  KEY `idx_invite_codes_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invite_codes`
--

LOCK TABLES `invite_codes` WRITE;
/*!40000 ALTER TABLE `invite_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `invite_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_actions`
--

DROP TABLE IF EXISTS `order_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_actions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `action_type` varchar(30) NOT NULL,
  `result_code` varchar(40) DEFAULT NULL,
  `result_message` varchar(255) DEFAULT NULL,
  `payload_json` longtext,
  `admin_user_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_actions_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_actions`
--

LOCK TABLES `order_actions` WRITE;
/*!40000 ALTER TABLE `order_actions` DISABLE KEYS */;
INSERT INTO `order_actions` VALUES (1,2,'refund','200','退款成功，余额已返还','{\"code\":200,\"msg\":\"退款成功，余额已返还\",\"data\":{\"uid\":1,\"bid\":\"XMZ202607271757259631ed426312d1\"},\"time\":1785146268}',1,'2026-07-27 17:57:48'),(2,2,'retry','400','该订单已退款，无法补单','{\"code\":400,\"msg\":\"该订单已退款，无法补单\",\"data\":[],\"time\":1785146287}',NULL,'2026-07-27 17:58:07'),(3,2,'retry','400','该订单已退款，无法补单','{\"code\":400,\"msg\":\"该订单已退款，无法补单\",\"data\":[],\"time\":1785146299}',1,'2026-07-27 17:58:19'),(4,2,'retry','400','该订单已退款，无法补单','{\"code\":400,\"msg\":\"该订单已退款，无法补单\",\"data\":[],\"time\":1785146326}',NULL,'2026-07-27 17:58:46'),(5,2,'retry','400','该订单已退款，无法补单','{\"code\":400,\"msg\":\"该订单已退款，无法补单\",\"data\":[],\"time\":1785146374}',1,'2026-07-27 17:59:34');
/*!40000 ALTER TABLE `order_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(40) NOT NULL,
  `upstream_order_no` varchar(80) DEFAULT NULL,
  `user_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `upstream_sign` varchar(120) NOT NULL,
  `target_qq` varchar(32) NOT NULL,
  `quantity` int NOT NULL,
  `feed_id` varchar(120) DEFAULT NULL,
  `is_delayed` tinyint(1) NOT NULL DEFAULT '0',
  `extra_input_json` longtext,
  `order_method` varchar(20) NOT NULL DEFAULT 'web',
  `state` varchar(40) NOT NULL DEFAULT '处理中',
  `upstream_state` varchar(80) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `start_num` varchar(40) DEFAULT NULL,
  `current_num` varchar(40) DEFAULT NULL,
  `finish_num` varchar(40) DEFAULT NULL,
  `user_price` bigint NOT NULL DEFAULT '0',
  `cost_price` bigint NOT NULL DEFAULT '0',
  `profit` bigint NOT NULL DEFAULT '0',
  `retry_count` int NOT NULL DEFAULT '0',
  `refund_status` varchar(30) NOT NULL DEFAULT 'none',
  `can_retry` tinyint(1) NOT NULL DEFAULT '0',
  `can_refund` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `last_sync_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_orders_no` (`order_no`),
  KEY `idx_orders_upstream` (`upstream_order_no`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'OD202607271731437632','XMZ202607271731442e7e8b06141834',1,1,'gid:1','1623108348',100,NULL,0,'{\"qq\":\"1623108348\",\"num\":100}','web','已完成','已完成','','33990','34090','34090',1000,1000,0,0,'none',0,0,'2026-07-27 17:31:45','2026-07-27 17:31:57','2026-07-27 17:39:17','2026-07-27 17:31:43','2026-07-27 17:39:17'),(2,'OD202607271757248919','XMZ202607271757259631ed426312d1',1,1,'gid:1','2996849867',100,NULL,0,'{\"qq\":\"2996849867\",\"num\":100}','web','失败','失败','资料卡异常','','','',1000,1000,0,0,'done',1,0,NULL,NULL,'2026-07-27 17:57:39','2026-07-27 17:57:24','2026-07-27 17:57:48');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_channels`
--

DROP TABLE IF EXISTS `payment_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_channels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `pay_type` varchar(40) NOT NULL,
  `merchant_id` int unsigned NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_payment_channels_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_channels`
--

LOCK TABLES `payment_channels` WRITE;
/*!40000 ALTER TABLE `payment_channels` DISABLE KEYS */;
INSERT INTO `payment_channels` VALUES (1,'xmz','小米粥支付宝','alipay',1,1,0,'2026-07-27 18:34:16','2026-07-27 18:34:23');
/*!40000 ALTER TABLE `payment_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_merchants`
--

DROP TABLE IF EXISTS `payment_merchants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_merchants` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `pid` varchar(40) NOT NULL,
  `merchant_key` varchar(120) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_merchants`
--

LOCK TABLES `payment_merchants` WRITE;
/*!40000 ALTER TABLE `payment_merchants` DISABLE KEYS */;
INSERT INTO `payment_merchants` VALUES (1,'小米粥科技','http://upea.yzxmz.cn','1000','5rR9fhlV5x6Qp2Z959vz0uN595608l5l',1,'2026-07-27 18:33:55','2026-07-27 18:35:21');
/*!40000 ALTER TABLE `payment_merchants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_discounts`
--

DROP TABLE IF EXISTS `product_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_discounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `min_quantity` int NOT NULL,
  `discount_rate` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_product_discounts_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_discounts`
--

LOCK TABLES `product_discounts` WRITE;
/*!40000 ALTER TABLE `product_discounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `upstream_key` varchar(120) NOT NULL,
  `upstream_sign` varchar(120) NOT NULL,
  `name` varchar(160) NOT NULL,
  `min_num` int NOT NULL DEFAULT '0',
  `max_num` int NOT NULL DEFAULT '0',
  `step_num` int NOT NULL DEFAULT '1',
  `steps_json` longtext,
  `input_json` longtext,
  `desc_json` longtext,
  `min_delayed` int DEFAULT NULL,
  `price_cost` bigint NOT NULL DEFAULT '0',
  `price_cost_delayed` bigint DEFAULT NULL,
  `upstream_level` varchar(80) DEFAULT NULL,
  `allow_frontend` tinyint(1) NOT NULL DEFAULT '1',
  `allow_api` tinyint(1) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `payload_json` longtext,
  `synced_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_products_sign` (`upstream_sign`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'mpz','gid:1','名片赞',100,1000000,100,'[100,1000,10000,100000]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}]','[\"请开启“允许陌生人点赞”，因设置不当导致的未到账不退单不补单\",\"本商品下单范围100~100w，下单数量必须为100的整数倍\"]',2000,1000,600,'站长亲爹',1,1,1,'{\"name\":\"名片赞\",\"min\":100,\"max\":1000000,\"step\":100,\"steps\":[100,1000,10000,100000],\"sign\":\"gid:1\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}],\"desc\":[\"请开启“允许陌生人点赞”，因设置不当导致的未到账不退单不补单\",\"本商品下单范围100~100w，下单数量必须为100的整数倍\"],\"min_delayed\":2000,\"price\":1000,\"level\":\"站长亲爹\",\"price_delayed\":600}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(2,'randomMpz','gid:6','[随机数量]名片赞',1,1000,1,'[1,10,100,500]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量（份数）\",\"placeholder\":\"\"}]','[\"请开启“允许陌生人点赞”，因设置不当导致的未到账不退单不补单\",\"随机到账500~2500赞，买多份可叠加，例如单次购买5份就是2500~12500赞（纯看脸，不支持因数量少而发起售后）\",\"最高日到账30万\"]',NULL,20000,NULL,'基础等级',1,1,1,'{\"name\":\"[随机数量]名片赞\",\"min\":1,\"max\":1000,\"step\":1,\"steps\":[1,10,100,500],\"sign\":\"gid:6\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量（份数）\",\"placeholder\":\"\"}],\"desc\":[\"请开启“允许陌生人点赞”，因设置不当导致的未到账不退单不补单\",\"随机到账500~2500赞，买多份可叠加，例如单次购买5份就是2500~12500赞（纯看脸，不支持因数量少而发起售后）\",\"最高日到账30万\"],\"price\":20000,\"level\":\"基础等级\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(3,'zoneVistor','gid:2','空间访客',100,1000000,100,'[100,1000,10000,100000]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}]','[\"请在空间设置中开启“允许所有人访问”，因设置不当导致的未到账不退单不补单\",\"本商品下单范围100~100w，下单数量必须为100的整数倍，否则下单失败\"]',NULL,3221,NULL,'高级合作商',1,1,1,'{\"name\":\"空间访客\",\"min\":100,\"max\":1000000,\"step\":100,\"steps\":[100,1000,10000,100000],\"sign\":\"gid:2\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}],\"desc\":[\"请在空间设置中开启“允许所有人访问”，因设置不当导致的未到账不退单不补单\",\"本商品下单范围100~100w，下单数量必须为100的整数倍，否则下单失败\"],\"price\":3221,\"level\":\"高级合作商\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(4,'feedZan','gid:3','空间说说赞',10,10000,10,'[10,100,1000]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"},{\"name\":\"feed_id\",\"type\":\"feed\",\"title\":\"指定说说ID\",\"placeholder\":\"输入下单说说ID\"}]','[\"请在权限设置中将目标说说查看权限设为公开，否则不退单不补单\",\"本商品下单范围10~1w，下单数量必须是10的倍数\",\"由于部分原因限制，本单非秒到类产品，实际速度平均约为22.8秒/个，无法接受请不要下单，下单不退\"]',NULL,11250,NULL,'顶级代理',1,1,1,'{\"name\":\"空间说说赞\",\"min\":10,\"max\":10000,\"step\":10,\"steps\":[10,100,1000],\"sign\":\"gid:3\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"},{\"name\":\"feed_id\",\"type\":\"feed\",\"title\":\"指定说说ID\",\"placeholder\":\"输入下单说说ID\"}],\"desc\":[\"请在权限设置中将目标说说查看权限设为公开，否则不退单不补单\",\"本商品下单范围10~1w，下单数量必须是10的倍数\",\"由于部分原因限制，本单非秒到类产品，实际速度平均约为22.8秒/个，无法接受请不要下单，下单不退\"],\"price\":11250,\"level\":\"顶级代理\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(5,'feedViews','gid:4','说说浏览量',100,1000000,100,'[100,1000,10000,100000]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"},{\"name\":\"feed_id\",\"type\":\"feed\",\"title\":\"指定说说ID\",\"placeholder\":\"输入下单说说ID\"}]','[\"请在权限设置中将目标说说查看权限设为公开，否则不退单不补单\",\"本商品下单范围100~100w，下单数量必须是100的整数倍\",\"浏览量/热度 需要在QQ空间的[设置]->[更多设置]->[隐私设置]中打开允许别人查看浏览量，否则只有自己才能看到\"]',NULL,5000,NULL,'基础等级',1,1,1,'{\"name\":\"说说浏览量\",\"min\":100,\"max\":1000000,\"step\":100,\"steps\":[100,1000,10000,100000],\"sign\":\"gid:4\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"},{\"name\":\"feed_id\",\"type\":\"feed\",\"title\":\"指定说说ID\",\"placeholder\":\"输入下单说说ID\"}],\"desc\":[\"请在权限设置中将目标说说查看权限设为公开，否则不退单不补单\",\"本商品下单范围100~100w，下单数量必须是100的整数倍\",\"浏览量/热度 需要在QQ空间的[设置]->[更多设置]->[隐私设置]中打开允许别人查看浏览量，否则只有自己才能看到\"],\"price\":5000,\"level\":\"基础等级\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(6,'special','special','特殊下单',1,1,1,'[1]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}]','[\"本商品下单后无法自动发货处理，需要联系站长手动处理\"]',NULL,1,NULL,'基础等级',1,1,1,'{\"name\":\"特殊下单\",\"min\":1,\"max\":1,\"step\":1,\"steps\":[1],\"sign\":\"special\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}],\"desc\":[\"本商品下单后无法自动发货处理，需要联系站长手动处理\"],\"price\":1,\"level\":\"基础等级\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37'),(7,'tagZan','gid:7','个性标签赞(拉圈圈)',1,1,1,'[1]','[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}]','[\"请先确认已将QQ的『个性名片』装扮改为可查看圈圈赞的模式，然后开始编辑『个性标签』\",\"将需要被拉满99+的标签提前设置好(最高可设置7个)，下单后再修改的标签不会被执行\",\"本商品不支持任何条件的退单，若出现很长时间未到账，可进入官方群或联系销售渠道售后\",\"执行效率：下单秒刷秒到\"]',NULL,40000,NULL,'基础等级',1,1,1,'{\"name\":\"个性标签赞(拉圈圈)\",\"min\":1,\"max\":1,\"step\":1,\"steps\":[1],\"sign\":\"gid:7\",\"input\":[{\"name\":\"qq\",\"type\":\"qq\",\"title\":\"请输入下单QQ号\",\"placeholder\":\"请输入下单QQ号\"},{\"name\":\"num\",\"type\":\"setNum\",\"title\":\"请设置下单数量\",\"placeholder\":\"\"}],\"desc\":[\"请先确认已将QQ的『个性名片』装扮改为可查看圈圈赞的模式，然后开始编辑『个性标签』\",\"将需要被拉满99+的标签提前设置好(最高可设置7个)，下单后再修改的标签不会被执行\",\"本商品不支持任何条件的退单，若出现很长时间未到账，可进入官方群或联系销售渠道售后\",\"执行效率：下单秒刷秒到\"],\"price\":40000,\"level\":\"基础等级\"}','2026-07-28 09:39:37','2026-07-27 17:29:52','2026-07-28 09:39:37');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recharge_orders`
--

DROP TABLE IF EXISTS `recharge_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recharge_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(40) NOT NULL,
  `user_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  `merchant_id` int unsigned NOT NULL,
  `amount` bigint NOT NULL,
  `credit_amount` bigint NOT NULL,
  `bonus_amount` bigint NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `pay_type` varchar(40) NOT NULL,
  `epay_trade_no` varchar(80) DEFAULT NULL,
  `raw_json` longtext,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_recharge_orders_no` (`order_no`),
  KEY `idx_recharge_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recharge_orders`
--

LOCK TABLES `recharge_orders` WRITE;
/*!40000 ALTER TABLE `recharge_orders` DISABLE KEYS */;
INSERT INTO `recharge_orders` VALUES (1,'RC202607271834375652',1,1,1,30,3000,0,'failed','alipay',NULL,'{\"error\":\"支付网关请求失败：SSL certificate OpenSSL verify result: self-signed certificate in certificate chain (19)\"}',NULL,'2026-07-27 18:34:37','2026-07-27 18:34:37'),(2,'RC202607271834448064',1,1,1,30,3000,0,'failed','alipay',NULL,'{\"error\":\"支付网关请求失败：SSL certificate OpenSSL verify result: self-signed certificate in certificate chain (19)\"}',NULL,'2026-07-27 18:34:44','2026-07-27 18:34:44'),(3,'RC202607271835355140',1,1,1,30,3000,0,'paid','alipay','2026072718353614650','{\"pid\":\"1000\",\"trade_no\":\"2026072718353614650\",\"out_trade_no\":\"RC202607271835355140\",\"type\":\"alipay\",\"name\":\"product\",\"money\":\"0.3\",\"trade_status\":\"TRADE_SUCCESS\",\"param\":\"1\",\"sign\":\"f6696f9d140b2e45311d198ea8cec3ac\",\"sign_type\":\"MD5\"}','2026-07-27 18:36:27','2026-07-27 18:35:35','2026-07-27 18:36:27');
/*!40000 ALTER TABLE `recharge_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) NOT NULL,
  `value` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_settings_key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'admin_path','/admin','2026-07-27 17:16:36','2026-07-27 17:42:20'),(2,'site_name','小米速刷系统','2026-07-27 17:16:36','2026-07-27 17:42:20'),(4,'site_keywords','速刷,对接,短信,充值','2026-07-27 17:42:20','2026-07-27 17:42:20'),(5,'site_description','支持上游对接加价售卖的现代化速刷系统','2026-07-27 17:42:20','2026-07-27 17:42:20'),(6,'site_favicon','','2026-07-27 17:42:20','2026-07-27 17:42:20'),(7,'site_logo','','2026-07-27 17:42:20','2026-07-27 17:42:20'),(8,'seo_footer','','2026-07-27 17:42:20','2026-07-27 17:42:20'),(9,'currency_name','速刷币','2026-07-27 17:42:20','2026-07-27 17:42:20'),(11,'feed_image_mode','self_proxy','2026-07-27 17:42:20','2026-07-27 17:42:20'),(12,'frontend_order_enabled','1','2026-07-27 17:42:20','2026-07-27 17:42:20'),(13,'api_order_enabled','1','2026-07-27 17:42:20','2026-07-27 17:42:20'),(14,'login_need_image_captcha','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(15,'login_need_sms','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(16,'login_need_email','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(17,'login_need_geetest','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(18,'register_need_email','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(19,'register_need_mobile','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(20,'register_need_image_captcha','1','2026-07-27 17:42:20','2026-07-27 17:42:20'),(21,'register_need_sms_code','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(22,'register_need_email_code','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(23,'default_register_strategy_user','1','2026-07-27 17:42:20','2026-07-27 17:42:20'),(24,'default_register_strategy_agent','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(25,'balance_downgrade_enabled','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(26,'api_condition_mode','total_consume','2026-07-27 17:42:20','2026-07-27 17:42:20'),(27,'api_condition_operator','>=','2026-07-27 17:42:20','2026-07-27 17:42:20'),(28,'api_condition_value','0','2026-07-27 17:42:20','2026-07-27 17:42:20'),(29,'invite_valid_mode','total_consume','2026-07-27 17:42:20','2026-07-27 17:42:20'),(30,'invite_valid_value','100000','2026-07-27 17:42:20','2026-07-27 17:42:20'),(31,'sms_provider','custom_http','2026-07-27 17:42:20','2026-07-27 17:42:20'),(32,'smtp_config','[]','2026-07-27 17:42:20','2026-07-27 17:42:20'),(33,'sms_config','[]','2026-07-27 17:42:20','2026-07-27 17:42:20'),(34,'geetest_config','[]','2026-07-27 17:42:20','2026-07-27 17:42:20'),(35,'invite_code_price_rules','{\"mode\":\"fixed\",\"fixed\":0}','2026-07-27 17:42:20','2026-07-27 17:42:20'),(36,'register_need_geetest','0','2026-07-27 17:42:20','2026-07-27 17:42:20');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_logs`
--

DROP TABLE IF EXISTS `sms_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(40) NOT NULL,
  `provider` varchar(40) NOT NULL,
  `template_code` varchar(80) DEFAULT NULL,
  `payload_json` longtext,
  `result_text` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_logs`
--

LOCK TABLES `sms_logs` WRITE;
/*!40000 ALTER TABLE `sms_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(20) NOT NULL,
  `channel` varchar(40) NOT NULL,
  `message` varchar(255) NOT NULL,
  `context_json` longtext,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_system_logs_channel` (`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
INSERT INTO `system_logs` VALUES (1,'error','admin','上游请求失败：SSL certificate OpenSSL verify result: self-signed certificate in certificate chain (19)','{\"action\":\"products/sync\"}',NULL,'2026-07-27 17:25:54'),(2,'error','admin','上游请求失败：SSL certificate OpenSSL verify result: self-signed certificate in certificate chain (19)','{\"action\":\"products/sync\"}',NULL,'2026-07-27 17:25:59'),(3,'error','admin','上游请求失败：SSL certificate OpenSSL verify result: self-signed certificate in certificate chain (19)','{\"action\":\"products/sync\"}',NULL,'2026-07-27 17:26:51'),(4,'info','balance','管理员调整用户余额','{\"actor_id\":1,\"user_id\":1,\"before\":0,\"after\":30000,\"delta\":30000}',1,'2026-07-27 17:30:34'),(5,'info','settings','管理员更新系统设置','{\"keys\":[\"site_name\",\"site_keywords\",\"site_description\",\"site_favicon\",\"site_logo\",\"seo_footer\",\"currency_name\",\"admin_path\",\"feed_image_mode\",\"frontend_order_enabled\",\"api_order_enabled\",\"login_need_image_captcha\",\"login_need_sms\",\"login_need_email\",\"login_need_geetest\",\"register_need_email\",\"register_need_mobile\",\"register_need_image_captcha\",\"register_need_sms_code\",\"register_need_email_code\",\"default_register_strategy_user\",\"default_register_strategy_agent\",\"balance_downgrade_enabled\",\"api_condition_mode\",\"api_condition_operator\",\"api_condition_value\",\"invite_valid_mode\",\"invite_valid_value\",\"sms_provider\",\"smtp_config\",\"sms_config\",\"geetest_config\",\"invite_code_price_rules\",\"register_need_geetest\"]}',NULL,'2026-07-27 17:42:20'),(6,'error','admin','该订单已退款，无法补单','{\"action\":\"orders/retry\"}',NULL,'2026-07-27 17:58:19'),(7,'error','admin','该订单正在退款或已退款','{\"action\":\"orders/manual-refund\"}',NULL,'2026-07-27 17:58:33'),(8,'error','admin','该订单已退款，无法补单','{\"action\":\"orders/retry\"}',NULL,'2026-07-27 17:59:34'),(9,'error','api','uid 与 api_key 不对应，鉴权失败','{\"action\":\"query_order\"}',NULL,'2026-07-27 18:22:36'),(10,'error','api','uid 与 api_key 不对应，鉴权失败','{\"action\":\"query_order\"}',NULL,'2026-07-27 18:22:45'),(11,'error','admin','支付通道编码格式不正确','{\"action\":\"payments/channel\"}',NULL,'2026-07-27 18:33:31'),(12,'error','admin','上游地址必须是有效的 http/https URL','{\"action\":\"upstream/save\"}',NULL,'2026-07-28 09:11:32');
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upstream_accounts`
--

DROP TABLE IF EXISTS `upstream_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upstream_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `base_url` varchar(255) NOT NULL,
  `upstream_uid` bigint unsigned NOT NULL,
  `upstream_api_key` varchar(120) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `options_json` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upstream_accounts`
--

LOCK TABLES `upstream_accounts` WRITE;
/*!40000 ALTER TABLE `upstream_accounts` DISABLE KEYS */;
INSERT INTO `upstream_accounts` VALUES (1,'小米粥速刷','http://qqzan.yzxmz.cn',1,'05f162e20cd3475e4bbbe2ec9b903edf9bea6f30fffef6dfa4c35dfb0c551e13',1,0,'[]','2026-07-27 17:25:46','2026-07-27 17:29:26'),(2,'小米粥科技','http://qqzan.yzxmz.cn',1,'05f162e20cd3475e4bbbe2ec9b903edf9bea6f30fffef6dfa4c35dfb0c551e13',1,0,'[]','2026-07-28 09:11:21','2026-07-28 09:11:21'),(3,'小米粥科技','http://qqzan.yzxmz.cn',1,'05f162e20cd3475e4bbbe2ec9b903edf9bea6f30fffef6dfa4c35dfb0c551e13',1,0,'[]','2026-07-28 09:11:25','2026-07-28 09:11:25'),(4,'小米粥科技','http://qqzan.yzxmz.cn',1,'05f162e20cd3475e4bbbe2ec9b903edf9bea6f30fffef6dfa4c35dfb0c551e13',1,0,'[]','2026-07-28 09:11:45','2026-07-28 09:11:45'),(5,'小米粥科技','http://qqzan.yzxmz.cn',1,'05f162e20cd3475e4bbbe2ec9b903edf9bea6f30fffef6dfa4c35dfb0c551e13',1,1,'[]','2026-07-28 09:11:51','2026-07-28 09:11:51');
/*!40000 ALTER TABLE `upstream_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_code` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` text,
  `threshold_mode` varchar(30) NOT NULL DEFAULT 'none',
  `threshold_value` bigint NOT NULL DEFAULT '0',
  `downgrade_on_balance` tinyint(1) NOT NULL DEFAULT '0',
  `markup_mode` varchar(20) NOT NULL DEFAULT 'fixed',
  `markup_value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `recharge_bonus_rate` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `allow_api_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_register` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_group_code` (`group_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'default','普通用户','默认注册用户组','none',0,0,'fixed',0.0000,1.0000,1,1,0,'2026-07-27 17:16:36','2026-07-27 18:26:27');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(80) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_group_id` int unsigned NOT NULL,
  `account_role` varchar(20) NOT NULL DEFAULT 'member',
  `strategy_user` tinyint(1) NOT NULL DEFAULT '1',
  `strategy_agent` tinyint(1) NOT NULL DEFAULT '0',
  `api_key` varchar(64) DEFAULT NULL,
  `api_key_generated_at` datetime DEFAULT NULL,
  `api_enabled_override` tinyint DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `ban_until` datetime DEFAULT NULL,
  `ban_reason` varchar(255) DEFAULT NULL,
  `balance` bigint NOT NULL DEFAULT '0',
  `total_recharge` bigint NOT NULL DEFAULT '0',
  `total_consume` bigint NOT NULL DEFAULT '0',
  `invite_count` int NOT NULL DEFAULT '0',
  `inviter_id` int unsigned DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_uid` (`uid`),
  UNIQUE KEY `uniq_users_username` (`username`),
  UNIQUE KEY `uniq_users_api_key` (`api_key`),
  KEY `idx_users_group` (`user_group_id`),
  KEY `idx_users_inviter` (`inviter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,68931884,'SteakJam','站长','10000',NULL,NULL,NULL,'$2y$10$rkXKzdyWl3oWsQYEfeLVmeisr24O3niP8l88qFIuzMuUWQ8NeotwW',1,'owner',1,1,'PAEt5AXR7k6tnYTjQNpQbA2e9YgY4gwYRL8w2F5Z',NULL,NULL,'active',NULL,NULL,32000,3000,2000,0,NULL,'2026-07-27 19:33:40','223.104.113.249',NULL,'2026-07-27 17:16:36','2026-07-27 19:33:40');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verify_codes`
--

DROP TABLE IF EXISTS `verify_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verify_codes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(120) NOT NULL,
  `channel` varchar(20) NOT NULL,
  `purpose` varchar(40) NOT NULL,
  `code` varchar(12) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_verify_codes_target` (`target`,`channel`,`purpose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_codes`
--

LOCK TABLES `verify_codes` WRITE;
/*!40000 ALTER TABLE `verify_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `verify_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'xiaomi_slop'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-28 11:54:36
