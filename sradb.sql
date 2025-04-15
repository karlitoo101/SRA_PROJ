-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 12:15 PM
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
-- Database: `sradb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `password`, `is_admin`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'Admin', 'mockadmin@test.com', '$2y$10$uKx6lwB9QoAADMDfbQVI/e4c5HMUQ2h9SpcP0h8KY8z7qkRX5SQVa', 1, NULL, NULL),
(2, 'Admin', 'sadfas@1123', '$2y$10$vtVwh1KirZJU66vvJSdmwe6tC5kmh7hrES8AHNX5RIbr0mUbli2oy', 1, NULL, NULL),
(3, 'Admin', 'usermock@test.com', '$2y$10$b/6TSwb7ymJn5LtjtlBPd.sy3PyvEDR2fCBqx/.J/DHhiFkWmfpMi', 0, NULL, NULL),
(4, 'Admin', 'asd@asss', '$2y$10$4MCNG/Pvbn6ZjYaUe1Y3m.pIzYITFTzQ06jqJrJCMswyt3Zp1MAzm', 0, NULL, NULL),
(5, 'Admin', 'nini@admin.com', '$2y$10$25u.1oWtlCUc8xb6CdccyeSNjqpPNBYopGRL7KVFFu2G5uAl0ydHW', 1, NULL, NULL),
(6, 'Admin', '1234@gasd', '$2y$10$1cEtshTiNnfdMzlaWrucYuvWC6eH7RkIVvJVks5.ks5k15QgH5lWq', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
