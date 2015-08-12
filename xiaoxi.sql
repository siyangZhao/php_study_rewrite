-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2015 at 06:56 PM
-- Server version: 5.5.40
-- PHP Version: 5.6.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xiaoxi`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `stick` enum('true','false') NOT NULL DEFAULT 'false',
  `excellent` enum('true','false') NOT NULL DEFAULT 'false',
  `pass` enum('true','false') NOT NULL DEFAULT 'true',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `module_id` (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `content`, `user_id`, `class_id`, `stick`, `excellent`, `pass`, `created_at`) VALUES
(14, '棋盘山一日游', '班级集体活动，棋盘山，不险，很快爬到了山顶，上面的景色让人觉得很舒服，美好的记忆', 4, 0, 'false', 'false', 'true', '2015-08-12 10:46:44'),
(17, '致青春影评', '为青春永垂不朽干杯！', 4, 2, 'false', 'false', 'true', '2015-08-12 10:39:08'),
(18, '有一个地方只有我们知道', '徐静蕾新作，吴亦凡处女作，没有狗血的剧情，没有白莲花的女主，只是一个平凡的爱情故事，就像发生在你我身边，可触可感', 4, 2, 'false', 'false', 'true', '2015-08-12 10:40:47'),
(19, '大圣归来影评', '属于中国人的超级英雄，齐天大圣孙悟空，回来了', 11, 2, 'false', 'false', 'true', '2015-08-12 10:42:32'),
(20, '心情', '论坛基本写完啦，第一个php网页，小小的激动和骄傲', 11, 1, 'false', 'false', 'true', '2015-08-12 10:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `describe` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `describe`) VALUES
(0, '游记', ''),
(1, '微小说', ''),
(2, '影评', '');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `article_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `content`, `user_id`, `article_id`, `created_at`) VALUES
(13, '努力的人运气总不会太差，加油呀啊啊啊！！', 4, 20, '2015-08-12 10:45:20'),
(14, '国产动漫崛起之作！', 4, 19, '2015-08-12 10:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL,
  `password` varchar(62) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `head_portrait` varchar(255) NOT NULL,
  `level` enum('admin','common','blocked','') NOT NULL DEFAULT 'common',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `mobile`, `created_at`, `head_portrait`, `level`) VALUES
(3, 'admin', '9cca5a1657b0d8dada6a19a74477e763', 'admin@example.com', '13386851888', '2015-06-17 03:12:19', '', 'admin'),
(4, 'zhaosiyang', '9cca5a1657b0d8dada6a19a74477e763', 'xiaoxi_elaine@sina.com', '18240441074', '2015-06-19 20:11:33', '1438697309_zhaosiyang.jpg', 'common'),
(5, 'yangliyuan', '9cca5a1657b0d8dada6a19a74477e763', '123456789@qq.com', '12345678901', '2015-07-29 09:00:32', '', 'common'),
(6, 'wangheran', '9a6355ce6c8bdb5d7dfd82257e1c0891', '987654321@qq.com', '13998230650', '2015-07-29 16:37:31', '', 'common'),
(7, 'zhangyibai', '9a6355ce6c8bdb5d7dfd82257e1c0891', '123456789@qq.com', '18804023477', '2015-07-29 17:00:46', '', 'common'),
(8, 'chengxin', '9cca5a1657b0d8dada6a19a74477e763', '123456789@qq.com', '13889364539', '2015-07-31 17:04:37', '', 'common'),
(9, 'helloworld', '9cca5a1657b0d8dada6a19a74477e763', '456789012@qq.com', '13912345678', '2015-08-02 21:05:54', '', 'common'),
(13, 'zhaosiyang_elaine', '9cca5a1657b0d8dada6a19a74477e763', 'xiaoxi_elaine@sina.cn', '10293847561', '2015-08-06 14:48:00', 'origin_head_portrait.jpg', 'common'),
(11, 'xiaoxi_elaine', '9cca5a1657b0d8dada6a19a74477e763', '935782498@qq.com', '13923456789', '2015-08-03 00:15:19', '', 'common'),
(12, 'xiaoxi_helen', '9cca5a1657b0d8dada6a19a74477e763', 'xiaoxi_helen@sina.com', '13323456789', '2015-08-04 14:17:21', '1438752865_xiaoxi_helen.jpg', 'common');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
