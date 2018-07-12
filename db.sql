-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: cis230.cis3scc.com
-- Generation Time: Mar 16, 2012 at 08:24 AM
-- Server version: 5.1.39
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `jlong_cis3scc_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) DEFAULT NULL,
  `city` varchar(32) NOT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(2) NOT NULL,
  `zip` int(11) DEFAULT NULL,
  `phone` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `addresses`
--


-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `author` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `author`, `date`, `content`) VALUES
(1, 'Test Article #1', 1, '2012-02-12 03:49:39', 'Test Article #1''\r\n\r\nYay! Got special characters in posts working!!!'),
(14, 'test fixed date?', 1, '2012-02-24 16:15:21', 'test, date should be 2/24/2102 ~4:15PM\r\n\r\nEDIT: It worked!2'),
(3, 'Test Article #3', 1, '2012-02-12 03:51:45', 'Test Article #3\r\n\r\nEDITED BY MODERATOR!'),
(4, 'Test Article #4', 1, '2012-02-12 03:55:45', 'Test Article #4'),
(5, 'Delete Me', 2, '2012-02-12 03:49:50', 'It''s ok, go ahead'),
(6, 'Test Article #6', 6, '2012-02-12 03:53:00', 'Test Article #6'),
(12, 'DDD', 1, '0000-00-00 00:00:00', 'adf'),
(13, 'Test using callback system', 7, '0000-00-00 00:00:00', 'test article\r\n\r\nEDIT: success\r\nDELETE: success, last post deleted fine'),
(16, 'abd', 8, '2012-03-04 19:57:08', 'ghi'),
(18, 'test', 8, '2012-03-04 20:48:17', 'test'),
(22, 'abc', 8, '2012-03-04 21:37:28', 'defwrga'),
(23, 'Blah', 8, '2012-03-04 21:39:06', 'deda'),
(24, 'Blah', 8, '2012-03-04 21:49:52', 'deda'),
(25, 'Member post', 6, '2012-03-04 23:47:26', 'test'),
(26, 'test', 6, '2012-03-06 06:06:55', 'test'),
(27, 'test', 12, '2012-03-14 10:21:41', 'test'),
(28, 'te', 1, '2012-03-14 10:28:41', 'te');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` varchar(256) NOT NULL,
  `title` varchar(512) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `created_date`, `author`, `title`, `content`) VALUES
(1, '2012-02-20 02:29:24', '1', 'My First Blog Post', 'test'),
(2, '2012-02-20 02:29:37', '1', 'Test #2', 'testnblkblkdhbrl uhg73it6ob7to nh'),
(6, '2012-03-14 11:54:37', '7', 'test', 'test'),
(5, '2012-03-13 13:30:38', '1', 'Test with new System', 'EDIT: Did the text change?\r\nEDIT 2: Yes, yes it did ...');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_of` varchar(32) NOT NULL,
  `author_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `blog_id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment_of`, `author_id`, `comment`, `rating`, `created_date`) VALUES
(1, 'products_2', 0, 'bob', 5, '2012-03-16 04:34:32'),
(2, 'products_3', 0, 'bill', 4, '2012-03-16 04:50:10'),
(4, 'products_2', 0, 'test comment', 5, '2012-03-16 04:34:32'),
(6, 'blogs_1', 0, 'test', 5, '2012-03-16 05:19:19'),
(7, 'blogs_6', 0, 'ANOTHER COMMENT', 2, '2012-03-16 07:30:49'),
(12, 'products_2', 1, 'abc321', 4, '2012-03-16 07:09:23'),
(8, 'products_6', 1, 'asd', 4, '2012-03-16 05:58:09'),
(11, 'products_5', 1, 'asddef', 2, '2012-03-16 07:12:17'),
(10, 'products_2', 1, 'asd', 4, '2012-03-16 07:09:41'),
(14, 'blogs_6', 8, 'Admin Comment', 5, '2012-03-16 07:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 = public event',
  `title` varchar(32) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `events`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(0, 'Member', 'articles_default,articles_show,articles_showall,articles_new,articles_save,products_default,products_show,products_showall,products_default_comment,products_new_comment,products_save_comment,blogs_default,blogs_show,blogs_showall,blogs_new_comment,blogs_save_comment'),
(1, 'Moderator', 'articles_default,articles_show,articles_showall,articles_new,articles_edit,articles_save,articles_update,products_default,products_show,products_showall,products_new,products_save,products_edit,products_update,products_new_comment,products_edit_comment,products_save_comment,products_update_comment,blogs_default,blogs_show,blogs_showall,blogs_new,blogs_save,blogs_edit,blogs_update,blogs_new_comment,blogs_save_comment,blogs_edit_comment_blogs_update_comment'),
(2, 'Admin', 'articles_default,articles_show,articles_showall,articles_new,articles_edit,articles_save,articles_update,articles_del,articles_email,products_default,products_show,products_showall,products_new,products_save,products_edit,products_update,products_del,blogs_default,blogs');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoice`
--


-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invoices`
--


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `qty`, `price`, `cost`, `image_path`) VALUES
(2, 'Skiis', 'Fast boards for your feet', 20, 195.95, 50.00, '/images/products/skiis.jpg'),
(3, 'Boots', 'Warm and snuggly, EDIT: and fuzzy ~@Admin', 20, 99.99, 29.99, '/images/products/boots.jpg'),
(5, 'Jacket', 'Warm and fluffy, keeps you alive in the snow!', 7, 299.99, 315.46, '/images/products/jacket.jpg'),
(6, 'Socks', 'Everyone needs them, nobody likes them...', 100, 5.99, 6.86, '/images/products/socks.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `userex_ext`
--

CREATE TABLE IF NOT EXISTS `userex_ext` (
  `user_id` int(11) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `birthday` timestamp NULL DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL COMMENT '0 = male, 1 = female',
  `mailing_list` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userex_ext`
--

INSERT INTO `userex_ext` (`user_id`, `email`, `phone`, `birthday`, `gender`, `mailing_list`) VALUES
(0, 'bob@test.com', 321, '2012-03-16 08:20:56', NULL, 0),
(8, 'bob@test.com', 321, NULL, NULL, 0),
(10, 'jlong@long-technical.com', NULL, '2012-03-16 08:20:40', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userex_int`
--

CREATE TABLE IF NOT EXISTS `userex_int` (
  `user_id` int(11) NOT NULL,
  `billing_address` int(11) DEFAULT '0',
  `shipping_address` int(11) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userex_int`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user` varchar(255) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `salt` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `user`, `pass`, `salt`) VALUES
(1, 2, 'JLong', '1e7ac66548f5cbb22fbaa6d0520e210c9efdea020e13dc1ee1805a69411be91d', 'a8e4f29b'),
(6, 0, 'Member', 'dd1413c0a37fd43796e98858af2911cd719f0109e5b253fa7bc0fef5b8f6e1fb', '93a17a82'),
(7, 1, 'Moderator', 'a6bea793edff3c1da9b87e2edabf622fa777e4ffa29e69e6ecc2c013a83bf699', '1cc18b76'),
(8, 2, 'Admin', '7cc22d3cd8faa4d26b790a9e418ef446799aea6aea18b7a5f029058f34c62f72', '30ffc94e'),
(10, 2, 'dave.jones@scc.spokane.edu', 'a132a00f671435d7c68476f9eb3b7bfeb7e7262e526027134494d2cf09125f58', '95046f53'),
(12, 0, 'Jim', 'a88431307681b955fc245ed65753d46228ac7af6a7c0314e6335d48ed8b85c8a', '18fe386d');
