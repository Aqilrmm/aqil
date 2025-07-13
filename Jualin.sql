-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2025 at 07:17 AM
-- Server version: 8.0.42-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Jualin`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountId` char(36) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `RoleId` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountId`, `Email`, `Password`, `FullName`, `PhoneNumber`, `BirthDate`, `RoleId`, `created_at`, `updated_at`, `deleted_at`) VALUES
('fb6c5b17-ca63-4c60-8780-a559a92a9569', 'esi.mikisia567@gmail.com', '$2y$10$u2kSfFZVW6T.9O78.eu7Y.tbDRE/LfS7EjTJlwIFkn4NyAe0BG9T6', 'Mikisia Esi', '085754435164', '2001-10-02', 2, '2025-06-12 15:32:02', '2025-06-12 15:32:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `StoreId` char(36) NOT NULL,
  `AccountId` char(36) NOT NULL,
  `StoreName` varchar(255) NOT NULL,
  `StoreCategory` varchar(255) DEFAULT NULL,
  `StoreDescription` text,
  `StoreAddress` text,
  `StoreProvince` varchar(100) DEFAULT NULL,
  `StoreCity` varchar(100) DEFAULT NULL,
  `StoreZipCode` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`StoreId`, `AccountId`, `StoreName`, `StoreCategory`, `StoreDescription`, `StoreAddress`, `StoreProvince`, `StoreCity`, `StoreZipCode`, `created_at`, `updated_at`, `deleted_at`) VALUES
('13fbd769-1996-4bf5-91ad-63c4877f360f', 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'GadgedGenius', 'elektronik', 'Toko Service Handphone', 'Jalan raya sungai nipah', '61', '6171', '78351', '2025-06-12 15:32:02', '2025-06-12 15:32:02', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountId`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`StoreId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
