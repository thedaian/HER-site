-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 23, 2010 at 08:07 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cards`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `clientID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `altemail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `cell` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `direct` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `office` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `other` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `phone_order` text COLLATE utf8_unicode_ci NOT NULL,
  `website` text COLLATE utf8_unicode_ci NOT NULL,
  `slogan` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) unsigned NOT NULL,
  PRIMARY KEY (`clientID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=114 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`clientID`, `email`, `password`, `name`, `title`, `altemail`, `address`, `cell`, `fax`, `direct`, `office`, `other`, `phone_order`, `website`, `slogan`, `last_activity`) VALUES
(1, 'thedaian@gmail.com', 'd27681d3c50ca0cd4185027289005bc4', 'Todd Barchok', 'e.g. Realtor', '', '123 fake st\r\ncolumbus oh', '(xxx) xxx-xxxx', '(xxx) xxx-xxxx', '123-4567', '(xxx) xxx-xxxx', '(xxx) xxx-xxxx', '', 'example.com', '', 0),
(3, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '', '', '', '', '', '', '', '', '', '', '', '', 1264110947),
(108, '', 'd41d8cd98f00b204e9800998ecf8427e', '', '0', '', '', '', '', '', '', '', '', '', '', 1264270527),
(109, 'example', '9f9d51bc70ef21ca5c14f307980a29d8', '', '0', '', '', '', '', '', '', '', '', '', '', 1264273517),
(110, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '', '0', '', 'Split this up into sections', '', '', '', '', '', '', '', '', 1264276242),
(111, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '', '0', '', 'Split this up into sections', '', '', '', '', '', '', '', '', 1264276315),
(112, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '', '0', '', 'Split this up into sections', '', '', '', '', '', '', '', '', 1264276331),
(113, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '', '0', '', 'Split this up into sections', '', '', '', '', '', '', '', '', 1264276370);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cardAmount` smallint(5) unsigned NOT NULL,
  `printingType` smallint(5) unsigned NOT NULL,
  `backMethod` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `subtotal` int(10) unsigned NOT NULL,
  `tax` mediumint(8) unsigned NOT NULL,
  `time_placed` int(10) unsigned NOT NULL,
  `special_instructions` text COLLATE utf8_unicode_ci NOT NULL,
  `clientID` int(10) unsigned NOT NULL,
  `templateID` mediumint(8) unsigned NOT NULL,
  `backTemplateID` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `cardAmount`, `printingType`, `backMethod`, `subtotal`, `tax`, `time_placed`, `special_instructions`, `clientID`, `templateID`, `backTemplateID`) VALUES
(1, 500, 0, '', 55, 4, 1264106528, 'Please use this space to list any special instructions, awards, designations, or design considerations for your business cards.', 1, 10, 0),
(2, 500, 0, '', 55, 4, 1264106625, 'Please use this space to list any special instructions, awards, designations, or design considerations for your business cards.', 1, 10, 0),
(3, 500, 0, '', 55, 4, 1264112956, '', 1, 11, 0),
(4, 500, 0, '', 55, 4, 1264176365, '', 1, 13, 9),
(5, 250, 0, '', 34, 2, 1264270581, '', 3, 13, 21),
(6, 250, 0, '', 34, 2, 1264270683, '', 3, 13, 21),
(7, 250, 0, '', 34, 2, 1264270877, '', 3, 13, 21),
(8, 0, 0, '', 0, 0, 1264276813, '', 3, 13, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `priceID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `quantity` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`priceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`priceID`, `quantity`, `product`, `price`) VALUES
(1, 250, 1, 25),
(2, 500, 1, 50),
(3, 1000, 1, 100),
(4, 1500, 1, 150),
(5, 2000, 1, 200),
(6, 2500, 1, 250),
(7, 3000, 1, 300),
(8, 5000, 1, 500),
(9, 250, 2, 50),
(10, 500, 2, 100),
(11, 1000, 2, 200),
(12, 1500, 2, 300),
(13, 2000, 2, 400),
(14, 2500, 2, 500),
(15, 3000, 2, 600),
(16, 5000, 2, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `templateID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templateNum` int(10) unsigned NOT NULL,
  `catagory` tinyint(4) NOT NULL,
  `fileName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cost` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`templateID`),
  KEY `templateNum` (`templateNum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`templateID`, `templateNum`, `catagory`, `fileName`, `cost`) VALUES
(5, 6, 1, '6 Blk text.jpg', 0),
(6, 6, 1, '6 white text.jpg', 0),
(7, 10, 0, '10 Back Blk Text.jpg', 0),
(8, 10, 0, '10 Back Wht Text.jpg', 0),
(9, 2, 0, '2 back.jpg', 0),
(10, 1, 0, '1 back.jpg', 0),
(11, 4, 1, '4 Modified-RedBG.jpg', 0),
(12, 2, 1, '2.jpg', 0),
(13, 3, 1, '3 Mod.jpg', 0),
(14, 3, 1, '3.jpg', 0),
(15, 4, 1, '4 Mod.jpg', 0),
(16, 4, 1, '4 Modified redBG.jpg', 0),
(17, 4, 1, '4.jpg', 0),
(18, 12, 2, 'Group 1.jpg', 0),
(19, 12, 2, 'Group 2.jpg', 0),
(20, 6, 0, '6 back.jpg', 0),
(21, 12, 0, '12 OSU fb schedule back.jpg', 0);
