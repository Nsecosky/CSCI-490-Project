SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `packagesystem` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `packagesystem`;

DROP TABLE IF EXISTS `dorms`;
CREATE TABLE `dorms` (
  `Unique_ID` int(11) NOT NULL,
  `Dorm_Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE TABLE `dorms`;
INSERT INTO `dorms` (`Unique_ID`, `Dorm_Name`, `Address`) VALUES
(1, 'Bunting', '1280 Cannell Ave'),
(2, 'Garfield', '1600 Cannell Ave'),
(3, 'Grand Mesa', '1200 Houston Ave'),
(4, 'Monument', '1102 Elm Ave'),
(5, 'Piñon', '1159 Mesa Ave'),
(6, 'Mary Rait', '1115 Texas Ave'),
(7, 'Jay Tolman', '1140 Texas Ave'),
(8, 'Wingate', '1601 Cannell Ave'),
(9, 'North Avenue', '936 North Ave'),
(10, 'Orchard Avenue', '1062 Orchard Ave'),
(11, 'Walnut Ridge', '1120 Texas Ave'),;

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `Unique_ID` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Time_In` datetime NOT NULL,
  `Time_Out` datetime DEFAULT NULL,
  `DA_In` int(11) NOT NULL,
  `DA_Out` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
  `Unique_ID` int(11) NOT NULL,
  `600_Number` varchar(20) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Access` int(3) NOT NULL DEFAULT '0',
  `Email` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `Room_Number` varchar(10) NOT NULL,
  `Dorm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `dorms`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`);

ALTER TABLE `packages`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `Unique_ID` (`Unique_ID`),
  ADD KEY `owner` (`owner`),
  ADD KEY `DA_In` (`DA_In`),
  ADD KEY `DA_Out` (`DA_Out`);

ALTER TABLE `people`
  ADD PRIMARY KEY (`Unique_ID`),
  ADD UNIQUE KEY `ID` (`Unique_ID`),
  ADD KEY `Dorm` (`Dorm`);


ALTER TABLE `dorms`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
ALTER TABLE `packages`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `people`
  MODIFY `Unique_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `people` (`Unique_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `packages_ibfk_3` FOREIGN KEY (`DA_Out`) REFERENCES `people` (`Unique_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `packages_ibfk_4` FOREIGN KEY (`DA_In`) REFERENCES `people` (`Unique_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`Dorm`) REFERENCES `dorms` (`Unique_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
