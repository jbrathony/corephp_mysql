/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.3.15-MariaDB : Database - vnv
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vnv` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `vnv`;

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `category` */

insert  into `category`(`id`,`category_name`) values (1,'BLANK'),(2,'PREMIUM'),(3,'SALT NIC'),(4,'COILS'),(5,'CBD'),(6,'HARDWARE'),(7,'DISPOSABLES'),(8,'SUPPLIES');

/*Table structure for table `location` */

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `location` */

insert  into `location`(`id`,`location`) values (1,'BACKSTOCK'),(2,'N'),(3,'AB'),(4,'TX');

/*Table structure for table `order_type` */

DROP TABLE IF EXISTS `order_type`;

CREATE TABLE `order_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `order_type` */

insert  into `order_type`(`id`,`type`) values (1,'FULFILLED'),(2,'ORDER'),(3,'DISCONTINUED'),(4,'PARTIAL');

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_username` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `qty_fulfiled` int(11) DEFAULT NULL,
  `cost` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `order_type` int(11) NOT NULL,
  `check_status` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '0',
  `error` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2882 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `orders` */

/*Table structure for table `userrole` */

DROP TABLE IF EXISTS `userrole`;

CREATE TABLE `userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `userrole` */

insert  into `userrole`(`id`,`role_name`) values (1,'Admin'),(2,'Customer');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY (`id`,`username`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`roleid`) values (15,'ADMIN','asdf',1),(27,'customer','asdf',2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
