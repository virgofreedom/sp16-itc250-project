-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `srv_Foreign`;
CREATE TABLE `srv_Foreign` (
  `CatergoryID` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Source` varchar(255) NOT NULL,
  `PublishDate` datetime NOT NULL,
  `Description` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-05-24 21:56:27