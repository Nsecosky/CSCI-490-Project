-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2017 at 07:10 AM
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
-- Table structure for table `dorm`
--

DROP TABLE IF EXISTS `dorm`;
CREATE TABLE `dorm` (
  `Unique_ID` int(11) NOT NULL,
  `Dorm_Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `Unique_ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Room` varchar(10) NOT NULL,
  `Dorm` int(11) NOT NULL,
  `Time_In` datetime NOT NULL,
  `Time_Out` datetime DEFAULT NULL,
  `DA_In` int(11) NOT NULL,
  `DA_Out` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
  `Unique_ID` int(11) NOT NULL,
  `600_Number` int(20) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Access` int(3) NOT NULL DEFAULT '0',
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
-- Indexes for table `dorm`
--
ALTER TABLE `dorm`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`),
  ADD KEY `Dorm` (`Dorm`),
  ADD KEY `owner` (`owner`),
  ADD KEY `DA_In` (`DA_In`),
  ADD KEY `DA_Out` (`DA_Out`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `ID` (`Unique_ID`),
  ADD KEY `Dorm` (`Dorm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dorm`
--
ALTER TABLE `dorm`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`Dorm`) REFERENCES `dorm` (`Unique_ID`),
  ADD CONSTRAINT `packages_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `people` (`Unique_ID`);

--
-- Constraints for table `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`Dorm`) REFERENCES `dorm` (`Unique_ID`);
