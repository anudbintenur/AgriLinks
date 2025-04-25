-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 02:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrilinks`
--

-- --------------------------------------------------------

--
-- Table structure for table `graded_batch`
--

CREATE TABLE `graded_batch` (
  `graded_batch_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `inspection_date` date NOT NULL,
  `freshness` int(11) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `color` varchar(50) NOT NULL,
  `taste` varchar(50) NOT NULL,
  `shelf_life` int(11) NOT NULL,
  `grade` enum('S','A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `graded_batch`
--

INSERT INTO `graded_batch` (`graded_batch_id`, `batch_id`, `inspector_id`, `inspection_date`, `freshness`, `weight`, `color`, `taste`, `shelf_life`, `grade`) VALUES
(1, 1, 4, '2025-01-20', 9, 1500.00, 'Red', 'Sweet', 30, 'A'),
(2, 2, 4, '2025-02-14', 8, 1600.00, 'Orange', 'Sour', 25, 'B'),
(3, 3, 4, '2025-03-05', 7, 1440.00, 'Yellow', 'Sweet', 20, 'C'),
(4, 4, 4, '2025-03-27', 9, 2700.00, 'Red', 'Sweet', 15, 'A'),
(5, 5, 4, '2025-04-10', 6, 5000.00, 'Brown', 'Earthy', 40, 'B'),
(6, 6, 4, '2025-04-22', 8, 1440.00, 'Orange', 'Sweet', 30, 'A'),
(7, 7, 4, '2025-05-07', 9, 1080.00, 'Green', 'Fresh', 20, 'A'),
(8, 8, 4, '2025-05-18', 7, 5000.00, 'Brown', 'Tart', 35, 'C'),
(9, 9, 4, '2025-06-08', 8, 6000.00, 'Green', 'Sweet', 45, 'A'),
(10, 10, 4, '2025-06-15', 6, 1000.00, 'Red', 'Tart', 20, 'D'),
(11, 11, 4, '2025-06-18', 9, 1125.00, 'Red', 'Sweet', 30, 'A'),
(12, 12, 4, '2025-06-25', 8, 1800.00, 'Orange', 'Sweet', 25, 'B'),
(13, 13, 4, '2025-07-02', 7, 1320.00, 'Yellow', 'Sweet', 30, 'C'),
(14, 14, 4, '2025-07-05', 8, 3240.00, 'Red', 'Sweet', 40, 'A'),
(15, 15, 4, '2025-07-08', 7, 1800.00, 'Orange', 'Sour', 30, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `grading_criteria`
--

CREATE TABLE `grading_criteria` (
  `grading_id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `crop_type` varchar(100) NOT NULL,
  `expected_color` varchar(50) NOT NULL,
  `expected_freshness` int(11) NOT NULL,
  `expected_shelf_life` int(11) NOT NULL,
  `expected_weight` decimal(10,2) NOT NULL,
  `expected_taste` varchar(50) NOT NULL,
  `grade_criteria` enum('S','A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grading_criteria`
--

INSERT INTO `grading_criteria` (`grading_id`, `crop_name`, `crop_type`, `expected_color`, `expected_freshness`, `expected_shelf_life`, `expected_weight`, `expected_taste`, `grade_criteria`) VALUES
(1, 'Apple', 'Fruit', 'Red', 7, 30, 150.00, 'Sweet', 'A'),
(2, 'Orange', 'Fruit', 'Orange', 10, 20, 200.00, 'Tangy', 'A'),
(3, 'Banana', 'Fruit', 'Yellow', 5, 10, 120.00, 'Sweet', 'B'),
(4, 'Tomato', 'Vegetable', 'Red', 3, 7, 180.00, 'Sour', 'A'),
(5, 'Potato', 'Vegetable', 'Brown', 15, 90, 250.00, 'Neutral', 'B'),
(6, 'Carrot', 'Vegetable', 'Orange', 10, 30, 100.00, 'Sweet', 'A'),
(7, 'Cucumber', 'Vegetable', 'Green', 7, 30, 120.00, 'Neutral', 'B'),
(8, 'Pineapple', 'Fruit', 'Yellow', 7, 25, 1000.00, 'Sweet', 'A'),
(9, 'Watermelon', 'Fruit', 'Green', 5, 20, 2000.00, 'Sweet', 'S'),
(10, 'Strawberry', 'Fruit', 'Red', 3, 10, 50.00, 'Sweet', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `harvested_batch`
--

CREATE TABLE `harvested_batch` (
  `batch_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `crop_type` varchar(100) NOT NULL,
  `batch_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `grading_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harvested_batch`
--

INSERT INTO `harvested_batch` (`batch_id`, `farmer_id`, `crop_name`, `crop_type`, `batch_date`, `quantity`, `weight`, `grading_id`) VALUES
(1, 1, 'Apple', 'Fruit', '2025-01-15', 100, 1500.00, 1),
(2, 1, 'Orange', 'Fruit', '2025-02-10', 80, 1600.00, 2),
(3, 1, 'Banana', 'Fruit', '2025-02-28', 120, 1440.00, 3),
(4, 1, 'Tomato', 'Vegetable', '2025-03-05', 150, 2700.00, 4),
(5, 1, 'Potato', 'Vegetable', '2025-03-20', 200, 5000.00, 5),
(6, 1, 'Carrot', 'Vegetable', '2025-04-01', 120, 1440.00, 6),
(7, 1, 'Cucumber', 'Vegetable', '2025-04-15', 90, 1080.00, 7),
(8, 1, 'Pineapple', 'Fruit', '2025-05-10', 50, 5000.00, 8),
(9, 1, 'Watermelon', 'Fruit', '2025-05-20', 30, 6000.00, 9),
(10, 1, 'Strawberry', 'Fruit', '2025-06-10', 200, 1000.00, 10),
(11, 1, 'Apple', 'Fruit', '2025-06-12', 75, 1125.00, 1),
(12, 1, 'Orange', 'Fruit', '2025-06-20', 90, 1800.00, 2),
(13, 1, 'Banana', 'Fruit', '2025-06-25', 110, 1320.00, 3),
(14, 1, 'Tomato', 'Vegetable', '2025-06-28', 180, 3240.00, 4),
(15, 1, 'Carrot', 'Vegetable', '2025-07-02', 150, 1800.00, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_dataal`
--

CREATE TABLE `user_dataal` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_dataal`
--

INSERT INTO `user_dataal` (`id`, `username`, `password`, `role`, `name`, `email`, `dob`, `address`, `created_at`) VALUES
(1, 'rafin_farmer', '11111', 'Farmer', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34'),
(2, 'rafin_driver', '11111', 'Driver', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34'),
(3, 'rafin_seller', '11111', 'Seller', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34'),
(4, 'rafin_inspector', '11111', 'Inspector', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34'),
(5, 'rafin_packer', '11111', 'Packer', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34'),
(6, 'rafin_admin', '11111', 'Admin', 'Muntasir Rafin', 'mun.rafin@gmail.com', '2002-10-10', 'Dhaka', '2025-04-25 09:52:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `graded_batch`
--
ALTER TABLE `graded_batch`
  ADD PRIMARY KEY (`graded_batch_id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `grading_criteria`
--
ALTER TABLE `grading_criteria`
  ADD PRIMARY KEY (`grading_id`);

--
-- Indexes for table `harvested_batch`
--
ALTER TABLE `harvested_batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `grading_id` (`grading_id`);

--
-- Indexes for table `user_dataal`
--
ALTER TABLE `user_dataal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `graded_batch`
--
ALTER TABLE `graded_batch`
  MODIFY `graded_batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `grading_criteria`
--
ALTER TABLE `grading_criteria`
  MODIFY `grading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `harvested_batch`
--
ALTER TABLE `harvested_batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_dataal`
--
ALTER TABLE `user_dataal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `graded_batch`
--
ALTER TABLE `graded_batch`
  ADD CONSTRAINT `graded_batch_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `harvested_batch` (`batch_id`),
  ADD CONSTRAINT `graded_batch_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `user_dataal` (`id`);

--
-- Constraints for table `harvested_batch`
--
ALTER TABLE `harvested_batch`
  ADD CONSTRAINT `harvested_batch_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `user_dataal` (`id`),
  ADD CONSTRAINT `harvested_batch_ibfk_2` FOREIGN KEY (`grading_id`) REFERENCES `grading_criteria` (`grading_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
