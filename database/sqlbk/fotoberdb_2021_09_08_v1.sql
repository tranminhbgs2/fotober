/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.3.17-MariaDB : Database - fotoberdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fotoberdb` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `fotoberdb`;

/*Table structure for table `ads` */

DROP TABLE IF EXISTS `ads`;

CREATE TABLE `ads` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL COMMENT 'Tên của slide',
  `link` varchar(250) DEFAULT NULL COMMENT 'Đường dẫn ảnh',
  `url` varchar(250) DEFAULT NULL COMMENT 'Đường dẫn liên kết',
  `start_date` datetime DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` datetime DEFAULT NULL COMMENT 'Ngày kết thúc',
  `sort` int(2) DEFAULT NULL COMMENT 'Thứ tự của slide',
  `status` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái: 0 - tạm dừng, 1 - Hiển thị, 2 - Khóa',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `ads` */

/*Table structure for table `configs` */

DROP TABLE IF EXISTS `configs`;

CREATE TABLE `configs` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT 'Tên biến',
  `code` varchar(150) DEFAULT NULL COMMENT 'Mã biến',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `value` varchar(200) DEFAULT NULL COMMENT 'Giá trị của biến',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `configs` */

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Id KH ở bảng users',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `customers` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `group_role` */

DROP TABLE IF EXISTS `group_role`;

CREATE TABLE `group_role` (
  `group_id` int(11) NOT NULL COMMENT 'ID của bảng groups',
  `role_id` int(11) NOT NULL COMMENT 'ID của bảng roles',
  PRIMARY KEY (`group_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `group_role` */

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Tên của nhóm quyền',
  `code` varchar(25) NOT NULL COMMENT 'Mã nhóm',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `status` tinyint(1) DEFAULT 1 COMMENT '0: Chưa kích hoạt, 1: Đã kích hoạt, 2: Khóa',
  `created_by` int(11) DEFAULT NULL COMMENT 'Id người tạo trong bảng users',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `groups` */

insert  into `groups`(`id`,`name`,`code`,`description`,`status`,`created_by`,`created_at`,`updated_at`) values (1,'CUSTOMER','CUSTOMER','Nhóm Khách hàng',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53'),(2,'SALE','SALE','Nhóm Sale',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53'),(3,'ADMIN','ADMIN','Nhóm Admin',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53'),(4,'EDITOR','EDITOR','Nhóm Editor',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53'),(5,'QAQC','QAQC','Nhóm QA/QC',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53'),(6,'SUPER_ADMIN','SUPER_ADMIN','Nhóm QA/QC',1,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53');

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `libraries` */

DROP TABLE IF EXISTS `libraries`;

CREATE TABLE `libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL COMMENT 'Id order',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id của KH ở bảng users',
  `message_id` bigint(20) DEFAULT NULL COMMENT 'Id đoạn chat nếu có',
  `name` varchar(255) DEFAULT NULL COMMENT 'Tên file, tên link',
  `link` varchar(255) DEFAULT NULL COMMENT 'Có thể tài nguyên là link',
  `type` varchar(25) DEFAULT 'IMAGE' COMMENT 'Loại định dạng: URL/IMAGE/VIDEO/FILE',
  `ext` varchar(5) DEFAULT NULL COMMENT 'Phần mở rộng: JPG/JPEG/PNG/RAR/ZIP/MP4/...',
  `size` int(11) DEFAULT 0 COMMENT 'Kích thước file, tính bằng MB',
  `duration` int(11) DEFAULT 0 COMMENT 'Thời gian nếu là Video',
  `height` int(11) DEFAULT 0 COMMENT 'Chiều cao nếu là ảnh',
  `width` int(11) DEFAULT 0 COMMENT 'Chiều rộng nếu là ảnh',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `libraries` */

/*Table structure for table `log_actions` */

DROP TABLE IF EXISTS `log_actions`;

CREATE TABLE `log_actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `action` varchar(45) DEFAULT NULL COMMENT 'ADDED, UPDATED, DELETED, WARNING_DEV, SUPPEND_DEV, SUPPEND_APP, RESET_PASSWORD',
  `description` text DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `table` varchar(50) DEFAULT NULL,
  `record_id` bigint(20) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `log_actions` */

/*Table structure for table `log_auths` */

DROP TABLE IF EXISTS `log_auths`;

CREATE TABLE `log_auths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) DEFAULT NULL COMMENT 'ID của session login',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'ID user login',
  `action_type` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'LOGIN, LOGOUT',
  `logged_in_at` datetime DEFAULT NULL COMMENT 'Thời điểm login',
  `account_input` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tên tk login',
  `logged_out_at` datetime DEFAULT NULL COMMENT 'Thời gian logout',
  `user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Thông tin trình duyệt',
  `duration` int(11) DEFAULT 0 COMMENT 'Thời gian login hệ thống',
  `ip_address` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ ip client',
  `result` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'SUCCESS, FAILED',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

/*Data for the table `log_auths` */

insert  into `log_auths`(`id`,`session_id`,`user_id`,`action_type`,`logged_in_at`,`account_input`,`logged_out_at`,`user_agent`,`duration`,`ip_address`,`result`,`created_at`,`updated_at`) values (1,NULL,NULL,'LOGIN','2021-09-01 15:08:46','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-01 15:08:46','2021-09-01 15:08:46'),(2,NULL,NULL,'LOGIN','2021-09-03 15:41:14','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-03 15:41:14','2021-09-03 15:41:14'),(3,NULL,NULL,'LOGIN','2021-09-03 15:41:46','doanpv.sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-03 15:41:46','2021-09-03 15:41:46'),(4,NULL,NULL,'LOGIN','2021-09-03 22:48:27','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-03 22:48:27','2021-09-03 22:48:27'),(5,NULL,NULL,'LOGIN','2021-09-03 22:49:06','doanpv.sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-03 22:49:06','2021-09-03 22:49:06'),(6,NULL,NULL,'LOGIN','2021-09-03 22:49:55','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-03 22:49:55','2021-09-03 22:49:55'),(7,NULL,NULL,'LOGIN','2021-09-05 11:48:14','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-05 11:48:14','2021-09-05 11:48:14'),(8,NULL,NULL,'LOGIN','2021-09-05 15:20:30','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-05 15:20:30','2021-09-05 15:20:30'),(9,NULL,NULL,'LOGIN','2021-09-05 15:21:40','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-05 15:21:40','2021-09-05 15:21:40'),(10,NULL,NULL,'LOGIN','2021-09-06 00:32:03','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 00:32:03','2021-09-06 00:32:03'),(11,NULL,NULL,'LOGIN','2021-09-06 00:33:38','doanpv.sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 00:33:38','2021-09-06 00:33:38'),(12,NULL,NULL,'LOGIN','2021-09-06 09:16:23','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 09:16:23','2021-09-06 09:16:23'),(13,NULL,NULL,'LOGIN','2021-09-06 09:44:31','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 09:44:31','2021-09-06 09:44:31'),(14,NULL,NULL,'LOGIN','2021-09-06 09:52:54','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 09:52:54','2021-09-06 09:52:54'),(15,NULL,NULL,'LOGIN','2021-09-06 09:54:31','sale',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 09:54:31','2021-09-06 09:54:31'),(16,NULL,NULL,'LOGIN','2021-09-06 13:48:02','customer',NULL,NULL,0,NULL,'SUCCESS','2021-09-06 13:48:02','2021-09-06 13:48:02');

/*Table structure for table `log_follows` */

DROP TABLE IF EXISTS `log_follows`;

CREATE TABLE `log_follows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL COMMENT 'Mã order',
  `summary` varchar(255) DEFAULT NULL COMMENT 'Nội dung tóm tắt',
  `content` text DEFAULT NULL COMMENT 'Nội dung chi tiết',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `log_follows` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'ID của đơn hàng',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id KH lấy ở bảng users',
  `sale_id` int(11) DEFAULT NULL COMMENT 'ID của Sale, người chat với KH',
  `type` varchar(20) DEFAULT 'TEXT' COMMENT 'Kiểu chat: TEXT - chữ, ICON, LINK - Đường dẫn, IMAGE - Hình ảnh, FILE - Tài liệu',
  `content` text DEFAULT NULL COMMENT 'Nội dung chat',
  `file_name` varchar(100) DEFAULT NULL COMMENT 'tên file',
  `status` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái: 0 - Thu hồi, 1 - Hiển thị, 2 - Chỉnh sửa, 3 - Xóa',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `messages` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2021_07_19_000000_create_jobs_table',1);

/*Table structure for table `order_types` */

DROP TABLE IF EXISTS `order_types`;

CREATE TABLE `order_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Tên loại',
  `code` varchar(25) DEFAULT NULL COMMENT 'Mã loại',
  `description` text DEFAULT NULL COMMENT 'Mô tả thêm',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `order_types` */

insert  into `order_types`(`id`,`name`,`code`,`description`,`created_at`,`updated_at`) values (1,'Flash','FLASH',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(2,'HDR','HDR',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(3,'Single','SINGLE',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(4,'Virtual Staging','VIRTUAL_STAGING',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(5,'Video editing','VIDEO_EDITING',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(6,'Day to dusk','DAY_TO_DUSK',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26'),(7,'Removal','REMOVAL',NULL,'2021-08-21 21:48:24','2021-08-21 21:48:26');

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL COMMENT 'ID của KH gắn với order này, lấy ở bảng users',
  `name` varchar(150) DEFAULT NULL COMMENT 'Tên đơn hàng',
  `code` varchar(100) DEFAULT NULL COMMENT 'Mã đơn hàng, sinh tự động sau khi thêm mới',
  `service_id` int(11) DEFAULT NULL COMMENT 'ID của bảng services',
  `options` varchar(25) DEFAULT 'LINK' COMMENT 'Tùy chọn gửi link file hay upload file: LINK/UPLOAD',
  `link` text DEFAULT NULL COMMENT 'Đường dẫn sản phẩm',
  `email_receiver` varchar(191) DEFAULT NULL COMMENT 'Email nhận chia sẻ',
  `upload_file` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file upload lên server',
  `deadline` datetime DEFAULT NULL COMMENT 'Deadline hoàn thành',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú của KH',
  `quantity` int(5) DEFAULT 1 COMMENT 'Số lượng sp',
  `discount` float(3,2) DEFAULT NULL COMMENT '% giảm giá',
  `discount_money` float(12,2) DEFAULT NULL COMMENT 'Giảm giá theo tiền mặt',
  `cost` float(12,2) DEFAULT NULL COMMENT 'Giá đơn hàng',
  `total_payment` float(12,2) DEFAULT NULL COMMENT 'Tổng tiền KH thanh toán',
  `status` tinyint(2) DEFAULT NULL COMMENT 'Trạng thái: 0 (Draft), 1 (New), 2 (Pending), 3 (Editing), 4 (Edited), 5 (Checking), 6 (Checked)- 7 (Completed), 8 (Re-do), 9 (Awaiting Payment), 10 (Paid), 11 (Deleted)',
  `created_type` varchar(15) DEFAULT 'CUSTOMER' COMMENT 'CUSTOMER (KH tạo), SALE (Sale tạo), mặc định là KH tạo',
  `created_by` int(11) DEFAULT NULL COMMENT 'Id của KH hoặc Id của Sale lấy ở bảng users',
  `assigned_sale_id` int(11) DEFAULT NULL COMMENT 'Id Sale đã nhận',
  `assigned_admin_id` int(11) DEFAULT NULL COMMENT 'Id Admin được giao',
  `assigned_editor_id` int(11) DEFAULT NULL COMMENT 'Id Editor được giao',
  `assigned_qaqc_id` int(11) DEFAULT NULL COMMENT 'Id QAQC được giao',
  `sent_sale_at` datetime DEFAULT NULL COMMENT 'Gửi y/c cho Sale lúc nào',
  `sent_admin_at` datetime DEFAULT NULL COMMENT 'Gửi cho Admin lúc nào',
  `sent_editor_at` datetime DEFAULT NULL COMMENT 'Gửi cho Editor lúc nào',
  `sent_qaqc_at` datetime DEFAULT NULL COMMENT 'Gửi cho QAQC lúc nào',
  `delivered_at` datetime DEFAULT NULL COMMENT 'Ngày bàn giao',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT 'Xóa mềm, khác null là bị đã bị xóa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `orders` */

insert  into `orders`(`id`,`customer_id`,`name`,`code`,`service_id`,`options`,`link`,`email_receiver`,`upload_file`,`deadline`,`notes`,`quantity`,`discount`,`discount_money`,`cost`,`total_payment`,`status`,`created_type`,`created_by`,`assigned_sale_id`,`assigned_admin_id`,`assigned_editor_id`,`assigned_qaqc_id`,`sent_sale_at`,`sent_admin_at`,`sent_editor_at`,`sent_qaqc_at`,`delivered_at`,`created_at`,`updated_at`,`deleted_at`) values (1,1,'Tạo album','ODR001',1,'LINK',NULL,NULL,NULL,'2021-09-21 23:38:02',NULL,1,NULL,NULL,NULL,NULL,0,'CUSTOMER',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 00:26:36','2021-08-29 16:55:46',NULL),(2,1,'Tạo album 2','ODR002',1,'LINK',NULL,NULL,NULL,'2021-09-07 23:37:51',NULL,1,NULL,NULL,450.00,450.00,7,'CUSTOMER',1,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 00:26:36','2021-09-03 15:41:32',NULL),(3,1,'Tạo album 3','ODR003',1,'LINK',NULL,NULL,NULL,'2021-09-29 23:37:40',NULL,1,NULL,NULL,NULL,NULL,2,'CUSTOMER',1,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 00:26:36','2021-09-03 15:41:31',NULL),(4,1,'Tạo album 4','ODR004',1,'LINK',NULL,NULL,NULL,'2021-09-05 23:37:29',NULL,1,NULL,NULL,NULL,NULL,3,'CUSTOMER',1,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 02:26:36','2021-09-03 15:41:26',NULL),(5,1,'Tạo album 5','ODR005',1,'LINK',NULL,NULL,NULL,'2021-09-04 23:37:20',NULL,1,NULL,NULL,NULL,NULL,4,'CUSTOMER',1,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 02:26:36','2021-09-03 15:41:25',NULL),(6,1,'Tạo album 6','ODR006',1,'LINK',NULL,NULL,NULL,'2021-08-31 23:37:10',NULL,1,NULL,NULL,NULL,NULL,0,'CUSTOMER',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-08-19 02:26:36','2021-08-28 23:37:16',NULL),(7,10,'Thiết kế biệt thự','ORD-20210906-61357B63E6643',6,'LINK','https://vnexpress.net/','doan281@gmail.com',NULL,'2021-09-25 09:21:42','Thiết kế biệt thự gấp để ở',1,NULL,NULL,6000.00,6000.00,7,'SALE',2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-09-06 09:22:27','2021-09-06 09:23:06',NULL);

/*Table structure for table `outputs` */

DROP TABLE IF EXISTS `outputs`;

CREATE TABLE `outputs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'Id đơn hàng',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id khách hàng',
  `link` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn link sản phẩm',
  `file` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file sản phẩm',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `outputs` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `payment_detail` */

DROP TABLE IF EXISTS `payment_detail`;

CREATE TABLE `payment_detail` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'ID của orders',
  `payment_id` int(11) DEFAULT NULL COMMENT 'ID của payments',
  `order_name` varchar(200) DEFAULT NULL COMMENT 'Tên của orders',
  `quantity` int(5) DEFAULT NULL COMMENT 'Số lượng sản phẩm',
  `price` float(12,2) DEFAULT NULL COMMENT 'Giá đơn hàng',
  `amount` float(12,2) DEFAULT NULL COMMENT 'Thành tiền',
  `description` text DEFAULT NULL COMMENT 'Mô tả về đơn hàng',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `payment_detail` */

insert  into `payment_detail`(`id`,`order_id`,`payment_id`,`order_name`,`quantity`,`price`,`amount`,`description`,`created_at`,`updated_at`) values (1,2,1,NULL,1,100.00,100.00,'Item 01','2021-09-03 16:38:55','2021-09-03 16:38:57'),(2,2,1,NULL,1,150.00,150.00,'Item 02','2021-09-03 16:38:55','2021-09-03 16:38:57'),(4,4,3,NULL,12,10.00,120.00,'Thiết kế tổng quan','2021-09-06 00:34:09','2021-09-06 00:34:09'),(5,4,3,NULL,15,20.00,300.00,'Thiết kế chi tiết','2021-09-06 00:34:28','2021-09-06 00:34:28'),(6,7,4,NULL,1,1000.00,1000.00,'Thiết kế tổng quan','2021-09-06 09:22:55','2021-09-06 09:22:55'),(7,7,4,NULL,1,5000.00,5000.00,'Thiết kế chi tiết','2021-09-06 09:23:05','2021-09-06 09:23:05');

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'Id đơn hàng cần thanh toán',
  `customer_id` int(11) DEFAULT NULL COMMENT 'ID của KH',
  `amount` float(12,2) DEFAULT 0.00 COMMENT 'Tiền thanh toán',
  `date_request` datetime DEFAULT NULL COMMENT 'Ngày gửi yêu cầu thanh toán',
  `date_success` datetime DEFAULT NULL COMMENT 'Thời gian hoàn thành thanh toán',
  `method` varchar(50) DEFAULT 'PAYPAL' COMMENT 'Phương thức thanh toán: PAYPAL,CREDIT',
  `email_paypal` varchar(191) DEFAULT NULL COMMENT 'Email tài khoản paypal',
  `paypal_id` varchar(100) DEFAULT NULL COMMENT 'id của invoice paypal',
  `link_payment` text DEFAULT NULL COMMENT 'Đường dẫn thanh toán đơn hàng',
  `status` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái thanh toán: 0 - Mới tạo, 1 - PENDING, 2 - DONE, 3 - FAILED',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo, thường là sale',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `payments` */

insert  into `payments`(`id`,`order_id`,`customer_id`,`amount`,`date_request`,`date_success`,`method`,`email_paypal`,`paypal_id`,`link_payment`,`status`,`created_by`,`created_at`,`updated_at`) values (1,2,1,450.00,NULL,NULL,NULL,NULL,NULL,'https://www.paypal.com/invoice/p/#INV2-NSUD-4R6U-4TE8-BJCC',1,NULL,'2021-09-03 16:38:15','2021-09-06 09:17:19'),(2,3,1,0.00,NULL,NULL,'PAYPAL',NULL,NULL,NULL,0,NULL,'2021-09-06 00:32:42','2021-09-06 00:32:42'),(3,4,1,0.00,NULL,NULL,'PAYPAL',NULL,NULL,NULL,0,NULL,'2021-09-06 00:32:44','2021-09-06 00:32:44'),(4,7,10,6000.00,NULL,NULL,NULL,NULL,NULL,'https://www.paypal.com/invoice/p/#INV2-7B2N-XFJC-URCK-SQ3G',1,NULL,'2021-09-06 09:22:37','2021-09-06 09:50:02'),(5,5,1,0.00,NULL,NULL,'PAYPAL','doan281@gmail.com',NULL,NULL,0,NULL,'2021-09-06 09:55:58','2021-09-06 09:55:58');

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT 'ID của role',
  `name` varchar(100) NOT NULL COMMENT 'Tên action',
  `code` varchar(100) NOT NULL COMMENT 'Mã action',
  `description` text DEFAULT NULL COMMENT 'Mô tả action',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái: 0 - Khóa, 1 - Hiển thị',
  `default` tinyint(1) DEFAULT 0 COMMENT 'Mặc định: 0 - Không, 1 - có',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `permissions` */

/*Table structure for table `requirements` */

DROP TABLE IF EXISTS `requirements`;

CREATE TABLE `requirements` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT 'ID của orders',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Id của KH, người y/c order',
  `name` varchar(255) DEFAULT NULL COMMENT 'Tên của yêu cầu',
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết yêu cầu',
  `status` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái: 0 (new), 1 (doing), 2 (done), 3 (cancel)',
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo, thường là sale',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `requirements` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Tên chức năng',
  `code` varchar(25) DEFAULT NULL COMMENT 'Mã chức năng',
  `description` text DEFAULT NULL COMMENT 'Mô tả  chức năng',
  `default` tinyint(1) DEFAULT 0 COMMENT 'Mặc định: 0 - Không, 1 - Có',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Kích hoạt, 2: Khóa',
  `created_by` int(11) DEFAULT NULL COMMENT 'ID của người tạo',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `roles` */

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL COMMENT 'Tên dịch vụ',
  `code` varchar(25) NOT NULL COMMENT 'Mã dịch vụ',
  `image` varchar(255) NOT NULL COMMENT 'Đường dẫn ảnh đại diện',
  `description` text DEFAULT NULL COMMENT 'Mô tả về dịch vụ',
  `link` varchar(200) DEFAULT NULL COMMENT 'Ảnh đại diện của dịch vụ',
  `status` tinyint(1) DEFAULT NULL COMMENT 'Trạng thái: 0 - Tạm dừng, 1 - Hiển thị, 2 - Khóa',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

/*Data for the table `services` */

insert  into `services`(`id`,`name`,`code`,`image`,`description`,`link`,`status`,`created_at`,`updated_at`) values (1,'Image Enhancement','SERVICE_001','uploads/services/image-enhancement.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(2,'Virtual Staging','SERVICE_002','uploads/services/virtual-staging.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(3,'Virtual Renovation','SERVICE_003','c',NULL,NULL,NULL,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(4,'Day To Dusk','SERVICE_004','uploads/services/day-to-dusk.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(5,'Item Removal','SERVICE_005','uploads/services/item-removal.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(6,'Floor Plan Redraw','SERVICE_006','uploads/services/floor-plan-redraw.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(7,'Video Editing','SERVICE_007','uploads/services/video-editing.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(8,'360 Image Enhancement','SERVICE_008','uploads/services/360-image-enhancement.jpg',NULL,NULL,1,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(9,'Rendering','SERVICE_009','c',NULL,NULL,NULL,'2021-08-21 22:01:02','2021-08-21 22:01:08'),(10,'Custom Job','SERVICE_010','c',NULL,NULL,NULL,'2021-08-21 22:01:02','2021-08-21 22:01:08');

/*Table structure for table `user_permission` */

DROP TABLE IF EXISTS `user_permission`;

CREATE TABLE `user_permission` (
  `user_id` int(11) DEFAULT NULL COMMENT 'ID của users',
  `permission_id` int(11) DEFAULT NULL COMMENT 'ID của permission'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_permission` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL COMMENT 'ID của bảng groups',
  `account_type` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tài khoản: CUSTOMER,SALE,ADMIN,EDITOR,QAQC,SUPER_ADMIN',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tài khoản',
  `salt` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ và tên người dùng',
  `birthday` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngày sinh',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh đại diện',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email của người dùng',
  `email_paypal` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email tài khoản paypal',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `gender` tinyint(1) DEFAULT NULL COMMENT 'Giới tính: 1 - Male, 2 - Female',
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'website KH',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian gửi xác nhận email',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú về tài khoản này nếu có',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0 COMMENT '1: Là admin, 0: Là user',
  `total_order` int(5) DEFAULT NULL COMMENT 'Tổng số đơn hàng',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Trạng thái: 0 (Tài khoản chưa được kích hoạt), 1 (Tài khoản đang hoạt động), 2 (Tài khoản đang tạm khóa), 3 (Tài khoản đã bị khóa)',
  `manager_by` int(11) DEFAULT NULL COMMENT 'ID của sale quản lý',
  `last_login` datetime DEFAULT NULL COMMENT 'Đăng nhập lần cuối',
  `last_logout` datetime DEFAULT NULL COMMENT 'Đăng xuất lần cuối',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`group_id`,`account_type`,`username`,`salt`,`password`,`fullname`,`birthday`,`address`,`avatar`,`email`,`email_paypal`,`phone`,`gender`,`website`,`email_verified_at`,`notes`,`remember_token`,`is_admin`,`total_order`,`status`,`manager_by`,`last_login`,`last_logout`,`created_at`,`updated_at`,`deleted_at`) values (1,1,'CUSTOMER','customer',NULL,'$2y$10$.eZX1nj4Op9rSMzbIAr9HubgG8onkAsrLrFbG84zUtHHzfM0TwkYC','Đoan Phạm','1991-09-11','Hà Nội, Việt Nam',NULL,'customer@gmail.com','doan281@gmail.com',NULL,1,NULL,NULL,NULL,'FLfK5sHTKSLYpkNhj5emCyeKTEgAbMunfzsPoPzTDV3uNeeDO2sZDVVGlixj',0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(2,2,'SALE','sale',NULL,'$2y$10$DyszWjBh3AhMl2PEnJLRUOgWdSMerGStdMXTSaVyDcQXDo5b5Kuda','Sale Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'sale@gmail.com',NULL,NULL,1,NULL,NULL,NULL,'3eRe6jae9jQ4ZP9WafcrL4QbewmJR5PZc5Eo4EBfXUqPFWLw5GdXgHoAGkTG',1,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(3,3,'ADMIN','admin',NULL,'$2y$10$Xrv7G5LrUgaWF/lw55qrzOyW9YpjMRn/1PdW9W2XIgEkjkxmmQ0F2','Admin Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'admin@gmail.com',NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(4,4,'EDITOR','editor',NULL,'$2y$10$R.zyH7P6WCcRgC/o20F60OmlWuuesfGENtYicj.e6TLG04p9pge8i','Editor Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'editor@gmail.com',NULL,NULL,1,NULL,NULL,NULL,'9k8yY4PmISUwuO1uen1i4M9c8BQL8OrRfjpGDxtSIxUIks5ZpctfhbfR6lxb',0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(5,5,'QAQC','qaqc',NULL,'$2y$10$C66J0tZagPxokDhOCPV3ruzhCLeDwGua48saZH9sDG1t.sTRhn6ye','QAQC Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'qaqc@gmail.com',NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(6,6,'SUPER_ADMIN','superadmin',NULL,'$2y$10$t7nR9Z.2WjFr3rBujPJKfe/8coW4dEP0ySRaoxEPBOgamIXZE98cm','SuperAdmin Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'superadmin@gmail.com',NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(8,2,'SALE','doanpv.sale',NULL,'$2y$10$DyszWjBh3AhMl2PEnJLRUOgWdSMerGStdMXTSaVyDcQXDo5b5Kuda','Sale Đoan','1991-09-11','Hà Nội, Việt Nam',NULL,'doanpv.sale@gmail.com',NULL,NULL,1,NULL,NULL,NULL,'GtJ3LqJ2iyn4wk4TdLAnfLHRr4JrdQvRboBhlgAEVPhF0TVA3UFfrPwQGxkE',0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(9,2,'SALE','minh.sale',NULL,'$2y$10$DyszWjBh3AhMl2PEnJLRUOgWdSMerGStdMXTSaVyDcQXDo5b5Kuda','Sale Minh','1991-09-11','Hà Nội, Việt Nam',NULL,'minh.sale@gmail.com',NULL,NULL,1,NULL,NULL,NULL,'wnBjD3mrU3XFT0JKAJzEz4MgP0t36RAqofMlCM3rTsYy927W9Tokl35Dc8aA',0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL),(10,1,'CUSTOMER','hoangdang',NULL,'$2y$10$.eZX1nj4Op9rSMzbIAr9HubgG8onkAsrLrFbG84zUtHHzfM0TwkYC','Johny Đặng','1991-09-11','Hà Nội, Việt Nam',NULL,'hoangdang@gmail.com','invoice@fotober.com',NULL,1,NULL,NULL,NULL,'MvLD8AAewXa7aEjiB8TY4GoiSz8YuzKqvZK4ne1QhZCjKEjiel34OPZ9feTF',0,0,1,NULL,NULL,NULL,'2021-08-15 12:35:53','2021-08-15 12:35:53',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
