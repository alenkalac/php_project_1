-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generation Time: Apr 28, 2016 at 02:46 AM
-- Server version: 10.1.9-MariaDB-log
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_project_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_barcode` int(11) NOT NULL,
  `date` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_barcode`, `date`) VALUES
(3, 999888, 1460581555),
(14, 999888, 1460655827),
(15, 999888, 1460709281),
(16, 999887, 1460709088),
(17, 999888, 1460972737),
(18, 999887, 1460974415),
(19, 999888, 1460974418),
(20, 999887, 1461008948),
(21, 999888, 1461769346),
(22, 999888, 1461789790);

-- --------------------------------------------------------

--
-- Table structure for table `belts`
--

CREATE TABLE `belts` (
  `belt_id` int(11) NOT NULL,
  `belt_color` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belts`
--

INSERT INTO `belts` (`belt_id`, `belt_color`) VALUES
(1, 'White Belt'),
(2, 'Yellow Tag'),
(3, 'Yellow Belt'),
(4, 'Green Tag'),
(5, 'Green Belt'),
(6, 'Blue Tag'),
(7, 'Blue Belt'),
(8, 'Red Tag'),
(9, 'Red Belt'),
(10, 'Black Belt');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `barcode` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `dob` varchar(25) NOT NULL,
  `belt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `barcode`, `name`, `surname`, `dob`, `belt`) VALUES
(1, 999888, 'Alen', 'Kalac', '12-12-1987', 1),
(2, 999887, 'test33', 'Test6', '10-12-1981', 2);

-- --------------------------------------------------------

--
-- Table structure for table `techniques`
--

CREATE TABLE `techniques` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `belt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `techniques`
--

INSERT INTO `techniques` (`id`, `name`, `belt`) VALUES
(1, 'Fore-fist Middle Punch', 1),
(2, 'Outer Forearm Low Block', 1),
(8, 'Inner Forearm Middle Block', 1),
(9, 'Outer Forearm Rising Block', 1),
(11, 'Middle Front Snap Kick', 1),
(12, 'Test 1', 2),
(13, 'test 2', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(1, 'sample', '$2y$10$BLSUrPjeVr99qpthLDAjne.SRQuqRkTBHTzPgxFJ4clMV1e0lWZRK', 1),
(2, 'admin', '$2y$10$THTfnlofCV1KIw1rO3LmPeOc7RaWCOwuf5qpeUAUwHGD33lCyidh.', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `belts`
--
ALTER TABLE `belts`
  ADD PRIMARY KEY (`belt_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `belt` (`belt`) USING BTREE;

--
-- Indexes for table `techniques`
--
ALTER TABLE `techniques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belt` (`belt`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `belts`
--
ALTER TABLE `belts`
  MODIFY `belt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `techniques`
--
ALTER TABLE `techniques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_keys` FOREIGN KEY (`belt`) REFERENCES `belts` (`belt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `techniques`
--
ALTER TABLE `techniques`
  ADD CONSTRAINT `fk_techbelt` FOREIGN KEY (`belt`) REFERENCES `belts` (`belt_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
