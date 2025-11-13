-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 04:31 AM
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
-- Database: `employee_directory`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `framework` varchar(100) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `joiningdate` date DEFAULT NULL,
  `retireddate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `firstname`, `lastname`, `email`, `phone`, `framework`, `salary`, `address`, `joiningdate`, `retireddate`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '1234567890', 'Ruby on Rails', '60000', '123 Main St', '2023-01-15', NULL),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '0987654321', 'Hibernate', '65000', '456 Elm St', '2022-11-01', NULL),
(3, 'Alice', 'Johnson', 'alice.johnson@example.com', '5555555555', 'Django', '70000', '789 Oak St', '2021-06-20', '2024-08-09'),
(4, 'Bob', 'Brown', 'bob.brown@example.com', '4444444444', 'ReactJs', '62000', '321 Maple St', '2020-09-14', NULL),
(5, 'Charlie', 'Davis', 'charlie.davis@example.com', '3333333333', 'JPA', '63000', '987 Pine St', '2019-03-10', '2023-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `default_address` tinyint(1) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `email`, `phone`, `address`, `city`, `country`, `zip`, `default_address`, `role`) VALUES
(1, 'john_doe', '$2a$10$7QJH1vj8FZJ9V0sMiyO4Z.n13gYz9lP1PS2POIu7F60Fg.SjJ5mNS', 'John Doe', 'john.doe@example.com', '1234567890', '123 Main St', 'New York', 'USA', '10001', 1, 'ROLE_USER'),
(2, 'jane_smith', '$2a$10$4RGzFSuHLuU0q0G9hfHGdeI2zQx9wG5mXwBqZ1P1PU/RCaRv9eL5W', 'Jane Smith', 'jane.smith@example.com', '0987654321', '456 Elm St', 'Los Angeles', 'USA', '90001', 0, 'ROLE_USER'),
(3, 'admin_user', '$2a$10$zQZkKIZQOa./eA1wPv7D3Ox/EO8rZFOA8jMP1FJCKfx3MO5x4VfJu', 'Admin User', 'admin@example.com', '1122334455', '789 Pine St', 'Chicago', 'USA', '60601', 1, 'ROLE_ADMINISTRATOR');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
