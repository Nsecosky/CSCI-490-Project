-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2017 at 03:04 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `packagesystem`
--
CREATE DATABASE IF NOT EXISTS `packagesystem` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `packagesystem`;

-- --------------------------------------------------------

--
-- Table structure for table `dorms`
--

DROP TABLE IF EXISTS `dorms`;
CREATE TABLE IF NOT EXISTS `dorms` (
  `Unique_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Dorm_Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  PRIMARY KEY (`Unique_ID`),
  UNIQUE KEY `Unique_ID` (`Unique_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dorms`
--

INSERT INTO `dorms` (`Unique_ID`, `Dorm_Name`, `Address`) VALUES
(1, 'Dorm 1', 'ADRESSSSS'),
(2, 'Dorm 2', '999 Some Rd');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `Unique_ID` int(11) NOT NULL AUTO_INCREMENT,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Dorm` int(11) NOT NULL,
  `Time_In` datetime NOT NULL,
  `Time_Out` datetime DEFAULT NULL,
  `DA_In` int(11) NOT NULL,
  `DA_Out` int(11) DEFAULT NULL,
  PRIMARY KEY (`Unique_ID`),
  UNIQUE KEY `Unique_ID` (`Unique_ID`),
  KEY `Dorm` (`Dorm`),
  KEY `owner` (`owner`),
  KEY `DA_In` (`DA_In`),
  KEY `DA_Out` (`DA_Out`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`Unique_ID`, `First_Name`, `Last_Name`, `owner`, `Description`, `Room`, `Dorm`, `Time_In`, `Time_Out`, `DA_In`, `DA_Out`) VALUES
(2, 'test', 'person', 1, 'A Small Package', '001', 1, '2017-02-14 00:04:18', NULL, 1, NULL),
(3, 'test', 'person', 1, 'A Large Package', '001', 1, '2017-02-14 00:15:13', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `Unique_ID` int(11) NOT NULL AUTO_INCREMENT,
  `600_Number` int(20) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Access` int(3) NOT NULL DEFAULT '0',
  `Email` varchar(255) NOT NULL,
  `Phone_Number` int(9) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `Room_Number` varchar(10) NOT NULL,
  `Dorm` int(11) NOT NULL,
  PRIMARY KEY (`Unique_ID`),
  UNIQUE KEY `ID` (`Unique_ID`),
  KEY `Dorm` (`Dorm`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`Unique_ID`, `600_Number`, `First_Name`, `Last_Name`, `Access`, `Email`, `Phone_Number`, `Active`, `Room_Number`, `Dorm`) VALUES
(1, 0, 'Person', 'One', 0, 'email', NULL, 1, '000', 1),
(2, 124134, 'Person', 'Two', 0, '', NULL, 1, '10', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`Dorm`) REFERENCES `dorms` (`Unique_ID`),
  ADD CONSTRAINT `packages_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `people` (`Unique_ID`),
  ADD CONSTRAINT `packages_ibfk_3` FOREIGN KEY (`DA_Out`) REFERENCES `people` (`Unique_ID`),
  ADD CONSTRAINT `packages_ibfk_4` FOREIGN KEY (`DA_In`) REFERENCES `people` (`Unique_ID`),
  ADD CONSTRAINT `packages_ibfk_5` FOREIGN KEY (`DA_Out`) REFERENCES `people` (`Unique_ID`);

--
-- Constraints for table `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`Dorm`) REFERENCES `dorms` (`Unique_ID`);
