-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 06, 2025 at 01:02 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ai_marketing`
--

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `company_id` int NOT NULL,
  `suggestion` text NOT NULL,
  `source` varchar(10) DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`id`, `user_id`, `company_id`, `suggestion`, `source`, `created_at`) VALUES
(2, 1, 0, 'explain within 200 words only', 'user', '2025-05-12 10:26:32'),
(3, 1, 0, 'hello', 'user', '2025-05-14 10:54:42'),
(4, 1, 0, 'explain within 100 words only', 'user', '2025-05-14 10:55:58'),
(5, 3, 0, 'hi', 'user', '2025-05-16 13:45:30'),
(6, 4, 0, 'explain within 100 words only', 'user', '2025-05-16 15:08:21'),
(7, 4, 0, 'hi', 'user', '2025-05-16 15:13:20'),
(8, 3, 0, 'jhg', 'user', '2025-05-16 15:21:52'),
(9, 4, 0, 'explain within 100 words only', 'user', '2025-05-19 10:12:30'),
(10, 4, 0, 'explain within 280 character', 'user', '2025-05-19 10:16:32'),
(11, 4, 0, 'explain within 280 charecter', 'user', '2025-05-19 10:17:09'),
(12, 4, 0, 'explain within 280 charecter only', 'user', '2025-05-19 10:18:05'),
(13, 4, 0, 'explain the content with the limit of twitter .i am post this content into twitter so explain within that limit', 'user', '2025-05-19 10:42:34'),
(14, 4, 0, 'explain only 100 charecters', 'user', '2025-05-20 11:00:49'),
(15, 4, 0, 'romve the headings from the generated content', 'user', '2025-05-20 12:09:48'),
(16, 4, 0, 'explain within 200 words only', 'user', '2025-05-20 12:10:10'),
(17, 4, 0, 'explain within 200 charecters only', 'user', '2025-05-20 12:10:35'),
(18, 4, 0, 'romve the headings from the generated content ,explain within 200 charecters only', 'user', '2025-05-20 12:11:20'),
(19, 4, 0, 'write 180 charecter only', 'user', '2025-05-20 19:41:49'),
(20, 4, 0, 'generate the content under 180 charecter', 'user', '2025-05-21 11:17:28'),
(21, 4, 0, 'only 180 character content want to generate', 'user', '2025-05-21 11:19:02'),
(22, 6, 0, 'explain the content under 180 words', 'user', '2025-05-21 17:51:01'),
(23, 6, 0, 'explain within 180 charecter', 'user', '2025-05-21 17:58:15'),
(24, 6, 0, 'explain the generated content under 10 words only', 'user', '2025-05-21 18:11:10'),
(25, 6, 0, 'explain the content generated word under 100 words only', 'user', '2025-05-21 18:12:24'),
(26, 6, 0, 'reduce the content', 'user', '2025-05-21 18:12:51'),
(27, 6, 0, 'reduce the content', 'user', '2025-05-21 18:17:12'),
(28, 6, 0, 'reduce the content', 'user', '2025-05-21 18:28:08'),
(29, 7, 0, 'explain the content under 180 character', 'user', '2025-05-22 11:41:48'),
(30, 7, 0, 'remove the headings', 'user', '2025-05-22 11:42:12'),
(31, 7, 0, 'explain within180 characters only', 'user', '2025-05-22 11:42:35'),
(32, 7, 0, 'hi', 'user', '2025-05-26 09:51:14'),
(33, 7, 0, 'remove the headdings', 'user', '2025-05-26 09:51:27'),
(34, 7, 0, 'generate another content with fashonable', 'user', '2025-06-03 17:33:05'),
(35, 7, 0, 'give a professional content', 'user', '2025-06-03 17:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `age` int NOT NULL,
  `location` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `age`, `location`, `password`, `created_at`) VALUES
(1, 'Ashi', 'ashikasubhashidam@gmail.com', '7907716467', 0, 'kasaragod', '$2y$10$rVFkxZLG.0AxdCVwqhOz7OqJBnM/2SmaqADu2qpsK7sPAqpO5PEgS', '2025-05-12 04:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `user_credentials`
--

DROP TABLE IF EXISTS `user_credentials`;
CREATE TABLE IF NOT EXISTS `user_credentials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `platform` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varbinary(255) NOT NULL,
  `access_token` text,
  `access_secret` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_credentials`
--

INSERT INTO `user_credentials` (`id`, `user_id`, `platform`, `username`, `password`, `access_token`, `access_secret`) VALUES
(1, 1, 'Instagram', 'Ashika', 0x24327924313024484c34597364565147534e387837697239682e37774f475a6d2f79352e73746948554b73766a507a3056577a453645677438793175, NULL, NULL),
(2, 1, 'Facebook', 'Ashika', 0x24327924313024356b7742586c6c334c363835596f54694c4b55426b2e524534646c5a564358335646482e714f4a41676231516166384958616f582e, NULL, NULL),
(3, 1, 'LinkedIn', 'Ashika CA', 0x2432792431302445445038706639585443577863344f3364363338372e3566664847524f774964726f574d3432363053777a6b55354872646f422e79, NULL, NULL),
(5, 1, 'Twitter', 'ashika_ca6786', 0x243279243130246a444f6e6247476a726748645759785067362e595275316867544d46644b674564554e64454c2e6963764b556768446d7a31522f43, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `social_media` enum('instagram','linkedin','facebook','Twitter') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `business_info` text NOT NULL,
  `additional_info` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `preferred_keywords` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `project_name`, `company_name`, `contact_name`, `email`, `phone`, `social_media`, `business_info`, `additional_info`, `url`, `tagline`, `preferred_keywords`, `submitted_at`) VALUES
(1, 1, 'project1', 'Asiankids', 'ashika', 'ashikasubhashidam@gmail.com', '7907716467', 'linkedin', 'this is dance class', 'dxjjhedhe', 'asiankids', 'asiankids', 'dan', '2025-05-12 10:25:41'),
(2, 1, 'project2', 'Asiankids', 'ashika', 'ashikasubhashidam@gmail.com', '7907716467', 'facebook', 'efnnjr', 'nfckheiu', 'asiankids', 'asiankids', 'dan', '2025-05-12 10:25:41'),
(5, 1, 'p1', 'KVC', 'ashika', 'ashikasubhashidam@gmail.com', '7907716467', 'linkedin', 'ECFJEGRFYTGY', 'RDHFCGRUY', 'KVC MARKET', 'KVC', 'MARKET', '2025-05-21 12:52:25'),
(4, 1, 'project1', 'KVC', 'ashika', 'ashikasubhashidam@gmail.com', '7907716467', 'Twitter', 'ECFJEGRFYTGY', 'RDHFCGRUY', 'KVC MARKET', 'KVC', 'MARKET', '2025-05-16 10:15:43'),
(6, 1, 'HealthScan AI', 'Diagnostics', 'Rahul Sharma', 'rahul.sharma@example.com', '9876543210', 'Twitter', 'A leading diagnostic center offering advanced MRI and CT scan services in Bangalore.', 'State-of-the-art equipment, 24/7 customer support, fast report delivery.', 'https://kvcdiagnostics.com', 'Your trusted health partner', 'MRI scans Bangalore, CT scans Karnataka, diagnostic center Bangalore', '2025-05-21 17:49:20'),
(7, 1, 'danceclass', 'Asiankids', 'shyamdas', 'ashikasubhashidam@gmail.com', '7907716467', 'Twitter', 'its dance class for teaching different dances', 'they are mainly foccusing childrens and teenagers', 'https://www.instagram.com/asiankids_dancecrew_official?igsh=cXl5NWJxYTg1am81', 'dance studio', 'dance class,classical,cinematic', '2025-05-22 10:29:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
