-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2024 at 03:37 AM
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
-- Database: `myproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verify_token` varchar(32) NOT NULL,
  `verify_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('superadmin','admin','user') NOT NULL DEFAULT 'user',
  `status` tinyint(1) DEFAULT 0,
  `attempts` int(11) DEFAULT 0,
  `lock_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `verify_token`, `verify_status`, `created_at`, `role`, `status`, `attempts`, `lock_time`) VALUES
(4, 'Andronico12', 'nucumandronico12@gmail.com', '$2y$10$sFDjXRJLy37iAssk1CN25uLbXa1h5SaCb8MTq32s00y80GoFkhasi', '', 1, '2024-09-30 08:24:15', 'superadmin', 0, 0, '2024-10-01 09:45:11'),
(9, 'Nics.Nico', 'designer.androniconucum@gmail.com', '$2y$10$F6KGD8xS2mODO4sIoCkbZuy28Q7DYdgHTdUB4ZGNbIA2FepYleTcm', 'e099180067ab88ecad52aadca3dc31ca', 1, '2024-09-30 09:46:14', 'admin', 0, 0, NULL),
(14, 'Nico.', 'nicounwntdmain@gmail.com', '$2y$10$iaacHuK/1JClWkY2Pz9XS.mI2pOYRskqSf.jCN26hXFySfSDu1Ate', 'fab20dccca1d8a5a32a6a138bee63f7e', 1, '2024-10-09 10:34:16', 'user', 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
