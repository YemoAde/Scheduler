-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 11, 2017 at 07:47 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `babara`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `report_group_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`_id`, `group_id`, `report_group_id`, `date_added`, `date_modified`) VALUES
(1, 1, 1, '2017-08-08 12:09:21', '0000-00-00 00:00:00'),
(2, 2, 4, '2017-08-08 12:09:27', '0000-00-00 00:00:00'),
(3, 3, 1, '2017-08-08 12:10:31', '0000-00-00 00:00:00'),
(4, 3, 4, '2017-08-08 12:10:31', '0000-00-00 00:00:00'),
(5, 3, 2, '2017-08-08 12:10:31', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`_id`, `group_name`, `date_added`, `date_modified`) VALUES
(1, 'GREAT', '2017-08-08 12:09:21', '0000-00-00 00:00:00'),
(2, 'NOT GREAT', '2017-08-08 12:09:27', '0000-00-00 00:00:00'),
(3, 'Multiple', '2017-08-08 12:10:31', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE `mails` (
  `_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mails`
--

INSERT INTO `mails` (`_id`, `schedule_id`, `date_added`, `date_modified`) VALUES
(1, 2, '2017-08-11 13:40:15', '0000-00-00 00:00:00'),
(5, 3, '2017-08-11 17:28:51', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `_id` int(11) NOT NULL,
  `report_title` varchar(100) NOT NULL,
  `report_group_id` int(11) NOT NULL,
  `report_file` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`_id`, `report_title`, `report_group_id`, `report_file`, `date_added`, `date_modified`) VALUES
(2, 'Mobile Alerts', 1, 'reports/How to Give a Killer Presentation.pdf', '2017-08-03 00:30:34', '2017-08-08 12:41:34'),
(3, 'Bank Alerts', 1, 'reports/How to Give a Killer Presentation.pdf', '2017-08-03 00:30:50', '2017-08-08 12:41:37'),
(4, 'Credit Balances', 1, 'reports/How to Give a Killer Presentation.pdf', '2017-08-03 00:31:04', '2017-08-08 12:41:39'),
(5, 'Refactor code', 2, 'reports/NITDA- organogram.png', '2017-08-08 11:52:38', '2017-08-11 13:34:40'),
(7, 'New', 2, 'reports/NITDA- organogram.png', '2017-08-08 12:40:58', '2017-08-11 13:34:42');

-- --------------------------------------------------------

--
-- Table structure for table `report_group`
--

CREATE TABLE `report_group` (
  `_id` int(11) NOT NULL,
  `report_group_name` varchar(150) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report_group`
--

INSERT INTO `report_group` (`_id`, `report_group_name`, `date_added`, `date_modified`) VALUES
(1, 'Test', '2017-08-08 11:38:58', '0000-00-00 00:00:00'),
(2, 'Great', '2017-08-11 13:45:46', '2017-08-11 13:46:03'),
(4, 'Refactor Code', '2017-08-08 12:06:44', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `_id` int(11) NOT NULL,
  `report_group_id` int(11) NOT NULL,
  `schedule_title` text NOT NULL,
  `frequency` varchar(10) NOT NULL,
  `start_date` date NOT NULL,
  `stop_date` date NOT NULL,
  `email_addresses` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`_id`, `report_group_id`, `schedule_title`, `frequency`, `start_date`, `stop_date`, `email_addresses`, `added_by`, `date_added`, `date_modified`) VALUES
(2, 1, 'Test', 'weekly', '2017-08-08', '2017-08-30', 'adeyemogab@gmail.com,yemocorps@outlook.com', 1, '2017-08-10 11:26:29', '2017-08-11 13:44:07'),
(3, 2, 'Final Testing Report', 'weekly', '2017-08-10', '2017-08-30', 'adeyemogab@gmail.com,yemocorps@outlook.com', 1, '2017-08-11 13:49:36', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `_id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `department` varchar(40) NOT NULL,
  `officeID` varchar(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(30) NOT NULL,
  `active` int(4) NOT NULL DEFAULT '1',
  `password` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`_id`, `fullname`, `department`, `officeID`, `group_id`, `email`, `role`, `active`, `password`, `date_added`, `date_modified`) VALUES
(1, 'Adminmm', 'Adminnn', '001', 3, 'admin@gmail.com', 'user', 1, 'admin', '2017-08-01 10:12:42', '2017-08-08 12:18:33'),
(2, 'ADE', 'Ade', '0098', 1, 'gab@system.com', 'admin', 1, 'gabriel', '2017-08-04 07:01:40', '2017-08-08 12:18:24'),
(3, 'Admin', 'Admin', '0003', 3, 'Growth', 'user', 1, 'yello', '2017-08-06 20:44:08', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `report_group`
--
ALTER TABLE `report_group`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mails`
--
ALTER TABLE `mails`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `report_group`
--
ALTER TABLE `report_group`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
