-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 06:26 AM
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
-- Database: `aproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `pid` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `phase` enum('design','development','testing','deployment','complete') DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `uid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`pid`, `title`, `start_date`, `end_date`, `phase`, `description`, `uid`) VALUES
(9, 'Five', '2005-05-05', '5555-05-05', 'development', 'Five and another five', 5),
(13, 'James&#039; Car', '2024-04-24', '2024-04-30', 'design', 'A Car that can run on bubbles and chocolate', 28),
(14, 'Duck&#039;s Project', '2024-04-18', '2024-05-18', 'development', 'A really cool healthy diet maker(for ducks)', 31),
(15, 'Dog', '2024-04-18', '2024-04-20', 'testing', 'Bone, BIG BONE!', 32),
(16, 'Chicken ', '2024-04-25', '2024-04-27', 'deployment', 'what came first, the chicken or the egg?', 10),
(18, 'Project Mermaid', '2024-04-17', '2024-06-17', 'testing', 'Testing of mermaid tails for people who want to live under water and make fish friends ', 34),
(20, 'Cottage House', '2024-04-19', '2025-12-19', 'design', 'A cottage to live in.', 19);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$10$pJeUqiTJCK2SO3I9UVG1X.PYYNhO7/sxam/T0r3ftSFcOnTXggEJu', 'admin@aston.ac.uk'),
(5, '5', '$2y$10$wJrTH3S6Wih3qq5jaA3sUOLDwFOzcYDbvAPlL7k/azFaXnAmavX6C', '5@five.com'),
(10, 'lgdevinett', '$2y$10$JR2TQSyRJQEqoxZYgTOW82oiBd7i7oEIvDUXtzFKQBRBYmW', 'lyd_devo@gmail.com'),
(19, 'isobel', '$2y$10$MXQd4M7oiFI38gDg2hWs2.uTjFWQL9pQeEdi0EHkYFy.3eP5Zkwxq', 'isobel@isobel.com'),
(28, 'James', '$2y$10$o0s6ytG4MMalOyRJvMhqP.wxAOA9mQ.V76k3svWDx0QtQSjAq/o26', 'James@james.com'),
(31, 'duck', '$2y$10$TxTIuqf3xoEg8XeYFnaLg.tjOeZc/4RmIg2r1UsWHVP9kiRt8urNm', 'duck@quack.com'),
(32, 'Dog', '$2y$10$NLR/rPpwoQ7FbGwgpnl6fu8OtspZtUf6PdJsu0Avj3QVp6enLPfYa', 'dog@dog.com'),
(34, 'simone', '$210$.QyoAOZH.As4fglpzOICDptQXtZDp1yo.QBZ7j6.riJcMm', 'devinett@fdeo.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `pid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
