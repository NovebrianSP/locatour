-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 09:44 AM
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
-- Database: `locatour`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_summary`
--

CREATE TABLE `category_summary` (
  `id` int(11) NOT NULL,
  `category_standardized` varchar(100) NOT NULL,
  `jumlah` int(11) DEFAULT 0,
  `rata_rata_rating` decimal(3,2) DEFAULT 0.00,
  `rata_rata_price` decimal(10,2) DEFAULT 0.00,
  `total_vote` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourism`
--

CREATE TABLE `tourism` (
  `place_id` varchar(50) NOT NULL,
  `place_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `time_minutes` int(11) DEFAULT 0,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourism_ratings`
--

CREATE TABLE `tourism_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_id` varchar(50) NOT NULL,
  `place_ratings` int(11) NOT NULL CHECK (`place_ratings` between 1 and 5),
  `place_ratings_normalized` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location_clean` varchar(255) DEFAULT NULL,
  `location_norm` varchar(255) DEFAULT NULL,
  `location_tokens` text DEFAULT NULL,
  `age_normalized` decimal(10,9) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wisata_merged`
--

CREATE TABLE `wisata_merged` (
  `id` int(11) NOT NULL,
  `source` varchar(50) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `name_clean` varchar(255) DEFAULT NULL,
  `name_norm` varchar(255) DEFAULT NULL,
  `name_tokens` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_clean` text DEFAULT NULL,
  `description_norm` text DEFAULT NULL,
  `description_tokens` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `category_clean` varchar(100) DEFAULT NULL,
  `category_norm` varchar(100) DEFAULT NULL,
  `category_tokens` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `rating` decimal(3,2) DEFAULT 0.00,
  `time_minutes` int(11) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `vote_count` int(11) DEFAULT 0,
  `htm_weekday` decimal(10,2) DEFAULT NULL,
  `htm_weekend` decimal(10,2) DEFAULT NULL,
  `rating_normalized` decimal(3,2) DEFAULT NULL,
  `price_normalized` decimal(15,13) DEFAULT NULL,
  `category_standardized` varchar(100) DEFAULT NULL,
  `unified_price` decimal(10,2) DEFAULT NULL,
  `merged_id` int(11) DEFAULT NULL,
  `indoor` tinyint(1) DEFAULT 0,
  `outdoor` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_summary`
--
ALTER TABLE `category_summary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_standardized` (`category_standardized`),
  ADD KEY `idx_category` (`category_standardized`);

--
-- Indexes for table `tourism`
--
ALTER TABLE `tourism`
  ADD PRIMARY KEY (`place_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_city` (`city`);

--
-- Indexes for table `tourism_ratings`
--
ALTER TABLE `tourism_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_place` (`place_id`),
  ADD KEY `idx_rating` (`place_ratings`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_location` (`location`),
  ADD KEY `idx_age` (`age`);

--
-- Indexes for table `wisata_merged`
--
ALTER TABLE `wisata_merged`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_category` (`category_standardized`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_merged_id` (`merged_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_summary`
--
ALTER TABLE `category_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tourism_ratings`
--
ALTER TABLE `tourism_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wisata_merged`
--
ALTER TABLE `wisata_merged`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tourism_ratings`
--
ALTER TABLE `tourism_ratings`
  ADD CONSTRAINT `tourism_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tourism_ratings_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `tourism` (`place_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
