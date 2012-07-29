-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2011 at 10:20 AM
-- Server version: 5.5.14
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Importaciones`
--

-- --------------------------------------------------------

--
-- Table structure for table `AuthAssignment`
--

CREATE TABLE IF NOT EXISTS `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `AuthItem`
--

CREATE TABLE IF NOT EXISTS `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `AuthItemChild`
--

CREATE TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE IF NOT EXISTS `Item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(20) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Brand` varchar(20) NOT NULL DEFAULT '',
  `Unit` varchar(10) NOT NULL DEFAULT '',
  `Unit2` varchar(10) NOT NULL DEFAULT '',
  `Branch` varchar(45) NOT NULL DEFAULT '',
  `Line` varchar(45) NOT NULL DEFAULT '',
  `Country` varchar(45) NOT NULL DEFAULT '',
  `ArtType` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Code` (`Code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42038 ;

-- --------------------------------------------------------

--
-- Table structure for table `Request`
--

CREATE TABLE IF NOT EXISTS `Request` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `PeriodStart` date NOT NULL,
  `PeriodEnd` date NOT NULL,
  `SubPeriod` date NOT NULL,
  `Enabled` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `RequestDetail`
--

CREATE TABLE IF NOT EXISTS `RequestDetail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `request_id` bigint(20) NOT NULL,
  `item_id` int(11) NOT NULL,
  `StockTime` decimal(10,2) NOT NULL,
  `ShipTime` decimal(10,2) NOT NULL,
  `ManualQty` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `SalesByDay`
--

CREATE TABLE IF NOT EXISTS `SalesByDay` (
  `item_id` int(11) NOT NULL,
  `OperDate` date NOT NULL,
  `Unit` varchar(10) NOT NULL,
  `Qty` decimal(32,2) NOT NULL,
  KEY `item_id` (`item_id`),
  KEY `OperDate` (`OperDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE IF NOT EXISTS `Transaction` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Oper` tinyint(4) NOT NULL,
  `item_id` int(11) NOT NULL,
  `Qty` decimal(10,2) NOT NULL,
  `Unit` varchar(10) NOT NULL,
  `RowTotal` decimal(14,2) NOT NULL,
  `TransDate` date NOT NULL,
  `OperDate` date NOT NULL,
  `StockDepo` varchar(20) NOT NULL,
  `Currency` varchar(3) NOT NULL,
  `CurrencyRate` double NOT NULL,
  `AffectStock` tinyint(4) NOT NULL DEFAULT '1',
  `Enable` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `Oper` (`Oper`),
  KEY `Item_Fk` (`item_id`),
  KEY `Enable` (`Enable`),
  KEY `OperDate` (`OperDate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=352539 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `state` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthAssignment`
--
ALTER TABLE `AuthAssignment`
  ADD CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `AuthItemChild`
--
ALTER TABLE `AuthItemChild`
  ADD CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RequestDetail`
--
ALTER TABLE `RequestDetail`
  ADD CONSTRAINT `RequestDetail_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `Item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD CONSTRAINT `Transaction_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `Item` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
