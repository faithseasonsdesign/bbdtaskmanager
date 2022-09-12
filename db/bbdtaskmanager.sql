-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2022 at 08:21 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbdtaskmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `adminId` int(11) NOT NULL,
  `adminUsername` varchar(255) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminFullname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `departmentName` varchar(255) NOT NULL,
  `departmentStatus` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`departmentName`, `departmentStatus`) VALUES
('Web Design', 'inactive'),
('Graphic Design', 'active'),
('Hiring Department (HR)', 'active'),
('Software Engineering', 'active'),
('User Interface Design', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `empId` int(11) NOT NULL,
  `empFullname` varchar(255) NOT NULL,
  `empEmail` varchar(255) NOT NULL,
  `empNumber` varchar(255) NOT NULL,
  `empPassword` varchar(255) NOT NULL,
  `empStatus` varchar(255) NOT NULL,
  `empImg` varchar(255) NOT NULL,
  `empDepartment` varchar(255) NOT NULL,
  `empRole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`empId`, `empFullname`, `empEmail`, `empNumber`, `empPassword`, `empStatus`, `empImg`, `empDepartment`, `empRole`) VALUES
(1, 'Faith Matlaba', 'faith@bbd.com', '0740440045', 'faith@bbd', 'active', 'uploads/6315cdc594d312.17278840.jpg', 'Web Design', 'Director'),
(2, 'Karabo Thako', 'karabo@bbd.com', '0731221239', 'faith@bbd', 'inactive', 'uploads/6315cefe210aa2.45054067.jpg', 'Web Design', 'Graphic Designer'),
(3, 'Tshidi Lekena', 'tshidi@bbd.com', '0741451549', 'tshidi@bbd', 'active', 'uploads/6315d00c3181a2.50689806.jpg', 'Hiring Department (HR)', 'Hiring Manager'),
(4, 'Thuto  Matlawe', 'thutomatlawe@bbd.com', '0740000056', 'thuto@bbd', 'active', 'uploads/631b55647f03e7.56082238.jpg', 'Graphic Design', 'Graphic Designer');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskId` int(11) NOT NULL,
  `taskTitle` varchar(255) NOT NULL,
  `taskDescription` varchar(255) NOT NULL,
  `taskDepartment` varchar(255) NOT NULL,
  `assignedUser` varchar(255) NOT NULL,
  `taskStatus` varchar(255) NOT NULL,
  `taskDate` varchar(255) NOT NULL,
  `assignedUserEmail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `taskTitle`, `taskDescription`, `taskDepartment`, `assignedUser`, `taskStatus`, `taskDate`, `assignedUserEmail`) VALUES
(1, 'Develop a website for Amazon', 'I want you to develop a website for BBD using React JS and Node JS', 'Web Design', 'Faith Matlaba', 'complete', '2022-09-09', 'faith@bbd.com'),
(2, 'Design a website for BBD', 'Design a really visually appealing website for BBD using Figma or Adobe XD the design must be responsive for testing before we can take it to the developers.', 'Web Design', 'Karabo Thako', 'pending', '2022-09-30', 'karabo@bbd.com'),
(3, 'Check out CV for new candidates', 'we received a huge amount of cv for the web designer position we would like you to review them and reflect back with top 3 candidates', 'Web Design', 'Tshidi Lekena', 'pending', '2022-09-30', 'tshidi@bbd.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`empId`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `empId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
