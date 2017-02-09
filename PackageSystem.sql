-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2017 at 08:21 AM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PackageSystem`
--
CREATE DATABASE IF NOT EXISTS `PackageSystem` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `PackageSystem`;

-- --------------------------------------------------------

--
-- Table structure for table `Dorm`
--

DROP TABLE IF EXISTS `Dorm`;
CREATE TABLE `Dorm` (
  `Unique_ID` int(11) NOT NULL,
  `Dorm_Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Packages`
--

DROP TABLE IF EXISTS `Packages`;
CREATE TABLE `Packages` (
  `Unique_ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Dorm` varchar(255) NOT NULL,
  `Time_In` datetime NOT NULL,
  `Time_Out` datetime NOT NULL,
  `DA_In` int(11) NOT NULL,
  `DA_Out` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `People`
--

DROP TABLE IF EXISTS `People`;
CREATE TABLE `People` (
  `Unique_ID` int(11) NOT NULL,
  `600_Number` int(20) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Access` int(3) NOT NULL DEFAULT '0' COMMENT '1=Student, 2=DA, 3=Coordinator',
  `Email` varchar(255) NOT NULL,
  `Phone_Number` int(9) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `Room_Number` varchar(10) NOT NULL,
  `Dorm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Dorm`
--
ALTER TABLE `Dorm`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`);

--
-- Indexes for table `Packages`
--
ALTER TABLE `Packages`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`);

--
-- Indexes for table `People`
--
ALTER TABLE `People`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `ID` (`Unique_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Dorm`
--
ALTER TABLE `Dorm`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Packages`
--
ALTER TABLE `Packages`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `People`
--
ALTER TABLE `People`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
