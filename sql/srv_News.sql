-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `srv_News`;
CREATE TABLE `srv_News` (
  `NewsID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryID` int(10) unsigned NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`NewsID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `srv_News_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `srv_Categories` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;