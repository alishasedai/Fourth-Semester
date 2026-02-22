-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 04:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `p`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `contractor_id`, `service_name`, `address`, `description`, `booking_date`, `booking_time`, `status`, `created_at`) VALUES
(1, 2, 1, 'celling design', 'Sanitnagar', 'make this', '2026-02-16', NULL, 'completed', '2026-02-16 05:26:07'),
(2, 7, 1, 'celling design', 'Sanitnagar', 'please be available', '2026-02-16', NULL, 'confirmed', '2026-02-16 08:44:54'),
(3, 2, 6, '2 by 2 works', 'kalapani lipulake', 'Please be available', '2026-02-21', NULL, 'completed', '2026-02-20 04:14:46'),
(4, 2, 6, '2 by 2 works', 'Sinamangal', '', '2026-02-21', NULL, 'pending', '2026-02-20 04:16:13'),
(5, 8, 6, '2 by 2 works', 'kalapani lipulake', 'please be available at times', '2026-02-22', NULL, 'confirmed', '2026-02-21 00:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `booking_photos`
--

CREATE TABLE `booking_photos` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_photos`
--

INSERT INTO `booking_photos` (`id`, `booking_id`, `photo_path`) VALUES
(1, 1, './uploads/customer_photos/1771219567_download__10_.jpeg'),
(2, 2, './uploads/customer_photos/1771231494_images__10_.jpeg'),
(3, 2, './uploads/customer_photos/1771231494_download__12_.jpeg'),
(4, 5, './uploads/customer_photos/1771634118_Screenshot_2026-02-16_220734.png');

-- --------------------------------------------------------

--
-- Table structure for table `contractor_details`
--

CREATE TABLE `contractor_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `services` text DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `work_photos` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractor_details`
--

INSERT INTO `contractor_details` (`id`, `user_id`, `service_name`, `description`, `services`, `experience`, `phone`, `address`, `profile_photo`, `work_photos`, `created_at`) VALUES
(1, 1, 'Luxury Design', 'Luxury design, ', 'Gypsum ', '3 years', '9818085279', 'Sinamangal', '1771144606_download (11).jpeg', '1771144606_images (11).jpeg,1771144606_images (10).jpeg', '2026-02-15 08:36:46'),
(2, 6, 'Luxury Design', 'Falsecellings', 'Gypsum, 2 by 2 cellings', '4 years', '9811111111', 'Sina', '1771229667_images (12).jpeg', '1771229667_images (10).jpeg,1771229667_download (12).jpeg', '2026-02-16 08:14:27'),
(3, 6, 'Luxury Design', 'Falsecellings', 'Gypsum, 2 by 2 cellings', '4 years', '9811111111', 'Sina', '1771229667_images (12).jpeg', '1771229667_images (10).jpeg,1771229667_download (12).jpeg', '2026-02-21 00:45:24'),
(4, 10, 'cellings Design', 'we design your interior with the best services', 'karnish, 2 by 2 works, framings', '10', '9764837064', 'udaypur', '1771758521_MBA_Admission_Open_-_Quest_International_College-thumbnail-4000x4000-70.jpg', '1771758521_viber_image_2024-03-07_17-28-09-426.jpg,1771758521_viber_image_2024-03-07_17-28-13-656.jpg', '2026-02-22 11:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `contractor_id`, `customer_id`, `rating`, `feedback`, `created_at`) VALUES
(11, 1, 1, 2, 4, 'very nice works wowww woww ✅❤️', '2026-02-21 00:23:31'),
(12, 3, 6, 2, 5, 'feedback given hehheheh', '2026-02-21 00:56:48'),
(13, 3, 6, 2, 5, 'feedback given hehheheh', '2026-02-21 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','contractor','super_admin') DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `created_at`) VALUES
(1, 'Alisha Sedai', 'alishasedai21@gmail.com', '$2y$10$lx/P6MgBNHK3ilyw22Fim.iq10ISLSyWdoz0psXpqyeMOr50EDpBW', 'contractor', '9818085279', 'Sinamangal', '2026-02-15 08:05:23'),
(2, 'Sarita Dahal', 'sarita@gmail.com', '$2y$10$zX2sG1/LnhMdJVJ99N5SHeobjcrTmdS.xDvOnd9yaHy9GOEjYtMBy', 'customer', '9849036005', 'Shantinagar', '2026-02-15 09:10:15'),
(3, 'Kishor Sedai', 'kishor@gmail.com', '$2y$10$GPF0B1rayV6I3SiqbciZguvL/RxGYPijPP0xw1GLr4/uDIQIG9k4K', 'contractor', '9849036005', 'santinagar', '2026-02-16 03:19:12'),
(4, 'Alisha ', 'alisha@gmail.com', '$2y$10$MoDDWwdFXfV9aQoAfl7n5.Nid5iN6eM83YUQ73JoUlLlCJA8POI1q', 'customer', '9849036005', 'Shantinagar', '2026-02-16 04:47:24'),
(5, 'Sanju', 'sanju@gmail.com', '$2y$10$A1pWHf62aAbwHE2DOGduKuWX3ymbpMhuh4.SfXjAhzqRsK9pgC3Ea', 'customer', '9812345678', 'Sinamangal', '2026-02-16 05:02:24'),
(6, 'Aliza', 'aliza@gmail.com', '$2y$10$p4dlDhK4U32CzCO7qZPztuCBP4.1d1/f8FfwFhSF07JlElqlj.f.m', 'contractor', '981211111', 'Sina', '2026-02-16 08:13:18'),
(7, 'Anu Dahal', 'anu@gmail.com', '$2y$10$9WnKt7mZXs.YtQlSNO9RU.Az8t6YWZ9qhYSoQ29elLoIgIWb.37A.', 'customer', '98111111', 'Sinamangal', '2026-02-16 08:42:50'),
(8, 'Alisha Sedai', 'alishasedai@gmail.com', '$2y$10$cnTi6gE5gpNja502Zt6gSOO/5Dh52Ay1LFRPV4n2Ian.ixM/ahApG', 'customer', 'vrrtft', 'santinagar', '2026-02-21 00:33:55'),
(9, 'Sangita Nepal', 'sangita@gmail.com', '$2y$10$T.JBYRfytJuwRpvV/ve7LeMY7gvr822L0T48TJvcn3TcFlhKPRz9e', 'customer', '9818085279', 'Jhapa', '2026-02-22 11:04:16'),
(10, 'Sonu Basnet', 'sonu@gmail.com', '$2y$10$37A4uHZIcXC6CUtoUV3uE.AAQglLx5.Rb/jfX1sv7LPtB9EAKE/6q', 'contractor', '9764837064', 'Udaypur', '2026-02-22 11:06:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `contractor_id` (`contractor_id`);

--
-- Indexes for table `booking_photos`
--
ALTER TABLE `booking_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `contractor_details`
--
ALTER TABLE `contractor_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `contractor_id` (`contractor_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_photos`
--
ALTER TABLE `booking_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contractor_details`
--
ALTER TABLE `contractor_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_photos`
--
ALTER TABLE `booking_photos`
  ADD CONSTRAINT `booking_photos_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contractor_details`
--
ALTER TABLE `contractor_details`
  ADD CONSTRAINT `contractor_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
