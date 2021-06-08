# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.30)
# Database: gotit_db
# Generation Time: 2021-06-04 08:36:08 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table gifts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gifts`;

CREATE TABLE `gifts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `img_src` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `gifts` WRITE;
/*!40000 ALTER TABLE `gifts` DISABLE KEYS */;

INSERT INTO `gifts` (`id`, `name`, `quantity`, `img_src`, `created_at`, `updated_at`)
VALUES
	(1,'Điện thoại iphone 12 Pro Max',2,'img/iphone-12-pro-max.jpeg',NULL,NULL),
	(2,'1 triệu đồng tiền mặt',35,'img/1-mil.jpeg',NULL,NULL),
	(3,'Ba Lô size L',63,'img/balo.jpeg',NULL,NULL);

/*!40000 ALTER TABLE `gifts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lotteries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lotteries`;

CREATE TABLE `lotteries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `codes` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `lotteries` WRITE;
/*!40000 ALTER TABLE `lotteries` DISABLE KEYS */;

INSERT INTO `lotteries` (`id`, `user_phone`, `point`, `codes`, `store_id`, `created_at`, `updated_at`)
VALUES
	(1,'0915111111',100,'131,132,133,134,135,136,137',1,NULL,NULL),
	(2,'0915111112',5,'123',2,NULL,NULL),
	(3,'0915111113',20,'124,125',3,NULL,NULL);

/*!40000 ALTER TABLE `lotteries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lottery_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lottery_logs`;

CREATE TABLE `lottery_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lottery_code` int(11) DEFAULT NULL,
  `gift_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_resets_table',1),
	(3,'2019_08_19_000000_create_failed_jobs_table',1),
	(4,'2021_06_04_024303_create_permission_tables',2);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
VALUES
	(1,'admin','web',NULL,NULL),
	(2,'manager','web',NULL,NULL),
	(5,'user','web',NULL,NULL);

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `manager_id`, `role_id`)
VALUES
	(1,'user01','user01@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K','GktnatOPGfoIyUAz9nz1NdLH5ltrh2KfT8VwDba8WFZTMd4zYekvuT47QVVO',NULL,'2021-06-04 07:03:50','0915111111',4,3),
	(2,'user02','user02@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K','D2Lz7y0QHWhklX4demcTSNBO0DvJJeQ8vUEZeTpI4IsJU0vKlBS52dXqgt5T',NULL,NULL,'0915111112',4,3),
	(3,'user03','user03@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K','jGubhPFnzZEad2MR41nsHUQgF5MbQJuZtxZa0cumsMGe8SlzDQgdAQxYlP8b',NULL,NULL,'0915111113',5,3),
	(4,'quanly01','quanly01@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K','S7CyWlVwTUGCvxbkBU6JDjGxKKKvnYPpFvOQ1ns7hWFdHJBicHQmDPKX1LAc',NULL,'2021-06-04 07:04:23','0915111121',NULL,2),
	(5,'quanly02','quanly02@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K',NULL,NULL,NULL,'0915111122',NULL,2),
	(99,'admin','admin@gmail.com',NULL,'$2y$10$/kLj/9qknC9uHInNA6mV2OYuDojPOxClJoQYbgZw9ggB7C5o1Wm7K',NULL,NULL,NULL,NULL,NULL,1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
