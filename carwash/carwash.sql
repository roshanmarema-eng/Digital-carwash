-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 01:14 PM
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
-- Database: `carwash`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`) VALUES
(1, 'Roshan@carwash.com', '8765!'),
(2, 'melvinjason@carwash.com', '$2y$10$PKaAtKmteZhXF28SrTbAi.JIPB63nxgqnu9H4nw89k2qwyKDR4mSO'),
(3, 'edwingabriel@carwash.com', '$2y$10$T6edJKQbrlgSAXt/KXlJg.S2K1UaPJ8hMaDTYPh3dCPt45NvRin42');

-- --------------------------------------------------------

--
-- Table structure for table `car_wash_bookings`
--

CREATE TABLE `car_wash_bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `vehicle` varchar(100) DEFAULT NULL,
  `plate` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_wash_bookings`
--

INSERT INTO `car_wash_bookings` (`id`, `customer_id`, `vehicle`, `plate`, `status`, `created_at`, `service_type`) VALUES
(1, 3651, NULL, NULL, 'Pending', '2026-07-02 13:30:17', 'Standard Wash'),
(2, 3651, NULL, NULL, 'Pending', '2026-07-02 13:30:25', 'Premium Wash');

-- --------------------------------------------------------

--
-- Table structure for table `customerregister`
--

CREATE TABLE `customerregister` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `total_redeemed` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `vehicle` varchar(50) DEFAULT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `total_redeemed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `phone`, `points`, `vehicle`, `plate`, `password`, `total_redeemed`) VALUES
(3011, 'Roshan Archen ', '0720215527', 199, 'Sedan', 'KDM 457H', '$2y$10$ksxM2LQi3nd6Sapszx2UkupQPxIkZjPtAWRdDnpzkvoL5eAj8Xslu', 300),
(3012, 'Latte kim', '0789256290', 676, 'Pickup', 'KBZ 347G', '$2y$10$qOicaWubJOaflLRaBv9lcOFt8zl1UIe2Jw2ujyI6WJ/n6zaMhcJde', 300),
(3013, 'James Doe', '0162882684', 185, 'Mitsubishi', 'KCD 564R', '', 101),
(3014, 'Zhang Linghe', '0114237322 ', 195, 'Volkswagen', 'KDA 784Z', '', 100),
(3015, 'Milbruk est ', '0117847352 ', 56, 'Audi', 'KDX 492Y', '', 0),
(3016, 'Min Jyung', '0789234597', 0, 'mercedes', 'KBZ 480R', '', 100),
(3017, 'Sharon Marema', '0117847322 ', 53, 'Porsche ', 'KDM 306H', '$2y$10$UOt9HxXa5k9tR.LKnZ2fJeVyAp8ZWQM/r8LChgwsZdYYxj/Faism2', 400),
(3650, 'Samantha Gift', '0743540388', 599, 'Audi', 'KDZ 605S', '', 101),
(3651, 'Virginiah Gathoni', '0795255283', 512, 'Audi', 'KDG 678V', '$2y$10$c1Emohe0VDh1xGyo/4lc6Oa8oukhfi5RQeFXH5OuBQmltriCB.p.i', 200),
(3652, 'Charles Jason', '0720148382', 709, 'Toyota', 'KBL821M', '$2y$10$I3cHdp/qSausN95JaXhczuwTk02qz0yqgsrSoN.Z83gYZPTWPwnum', 200),
(3653, 'Andy Rosette', '0198123456', 339, 'mercedes', 'KBV 421G', '$2y$10$UC52J2kj9BH8Kt4nA4NqHuLC0rbztblgg6W7KAq9z0xD.XcxQ1Eje', 0);

-- --------------------------------------------------------

--
-- Table structure for table `loyal_customers`
--

CREATE TABLE `loyal_customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loyal_customers`
--

INSERT INTO `loyal_customers` (`id`, `fullname`, `phone`, `vehicle`, `plate`, `points`) VALUES
(1, 'Alex Mercer', '+1 (555) 234-5678', 'SUV', 'KBC 123X', 454),
(2, 'Sarah Jenkins', '+1 (555) 876-5432', 'Sedan', 'KAA 789Y', 1205),
(3, 'David Kim', '+1 (555) 432-1098', 'Truck', 'KCD 456Z', 313),
(4, 'Elena Rostova', '+1 (555) 901-2345', 'Motorcycle', 'KMCA 890', 85),
(5, 'Marcus Vance', '+1 (555) 345-6789', 'SUV', 'KBY 555M', 645);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) DEFAULT 'Completed',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `customer_id`, `amount`, `payment_method`, `payment_status`, `payment_date`) VALUES
(1, 7, 4500.00, 'M-Pesa', 'Completed', '2026-06-09 13:34:16'),
(2, 3011, 2000.00, 'M-Pesa', 'Completed', '2026-06-09 18:49:03'),
(3, 3649, 3500.00, 'M-Pesa', 'Completed', '2026-06-17 17:58:19'),
(4, 3649, 20000.00, 'Card', 'Completed', '2026-06-18 13:33:45'),
(5, 3651, 6700.00, 'M-Pesa', 'Completed', '2026-07-01 09:51:16'),
(6, 3652, 1500.00, 'M-Pesa', 'Completed', '2026-07-04 17:17:15'),
(7, 3654, 5500.00, 'M-Pesa', 'Completed', '2026-07-07 10:19:36'),
(8, 3653, 2500.00, 'Bank Transfer', 'Completed', '2026-07-07 18:15:31'),
(9, 3013, 3400.00, 'M-Pesa', 'Completed', '2026-07-07 18:22:51'),
(10, 3012, 3400.00, 'M-Pesa', 'Completed', '2026-07-07 18:30:47'),
(11, 3012, 3400.00, 'M-Pesa', 'Completed', '2026-07-07 18:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `points_history`
--

CREATE TABLE `points_history` (
  `customer_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `points_history`
--

INSERT INTO `points_history` (`customer_id`, `points`, `type`, `description`, `created_at`) VALUES
(3011, 60, 'REDEEMED', 'Redeemed points', '2026-07-02 13:59:47'),
(3652, 80, 'REDEEMED', 'Redeemed points', '2026-07-04 17:27:18'),
(3017, 25, 'REDEEMED', 'Redeemed points', '2026-07-06 14:04:31'),
(3654, 20, 'REDEEMED', 'Redeemed points', '2026-07-07 10:27:24'),
(3653, 59, 'REDEEMED', 'Redeemed points', '2026-07-07 18:18:50'),
(3012, 170, 'EARNED', 'Earned 170 point(s) for payment of KES 3400', '2026-07-07 18:30:47'),
(3012, 170, 'EARNED', 'Earned 170 point(s) for payment of KES 3400', '2026-07-07 18:31:08'),
(3012, 200, 'REDEEMED', 'Redeemed points', '2026-07-07 18:32:46'),
(3017, 30, 'REDEEMED', 'Redeemed points', '2026-07-09 13:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `point_redeem_history`
--

CREATE TABLE `point_redeem_history` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `points_redeemed` int(11) NOT NULL,
  `redeemed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `point_redeem_history`
--

INSERT INTO `point_redeem_history` (`id`, `customer_id`, `points_redeemed`, `redeemed_at`) VALUES
(1, 3651, 60, '2026-07-02 12:30:00'),
(2, 3651, 20, '2026-07-02 12:41:55'),
(3, 3651, 98, '2026-07-02 13:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `customer_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `total_redeemed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`customer_id`, `points`, `total_redeemed`) VALUES
(3011, 0, 0),
(3012, 0, 0),
(3013, 0, 0),
(3014, 0, 0),
(3015, 56, 0),
(3016, 0, 0),
(3017, 0, 0),
(3649, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rewards_log`
--

CREATE TABLE `rewards_log` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_type` enum('earned','redeemed') NOT NULL,
  `points_changed` int(11) NOT NULL,
  `service_rendered` varchar(100) NOT NULL,
  `amount_spent` decimal(10,2) DEFAULT 0.00,
  `processed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rewards_log`
--

INSERT INTO `rewards_log` (`id`, `customer_id`, `transaction_type`, `points_changed`, `service_rendered`, `amount_spent`, `processed_at`) VALUES
(13, 1, 'earned', 1, 'Basic Wash & Wheel Clean', 15.00, '2026-06-17 13:45:07'),
(14, 2, 'earned', 5, 'Full Executive Detailing', 55.00, '2026-06-17 13:45:07'),
(15, 3, 'earned', 3, 'Interior & Exterior Polish', 30.00, '2026-06-17 13:45:07'),
(16, 1, 'earned', 3, 'Interior & Exterior Polish', 30.00, '2026-06-17 13:45:07'),
(17, 5, 'earned', 5, 'Full Executive Detailing', 55.00, '2026-06-17 13:45:07');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `vehicle_model` varchar(100) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `vehicle_model`, `vehicle_type`, `color`, `customer_name`, `created_at`) VALUES
(1, 'ABC-1234', 'Toyota Vios', 'Sedan', 'Silver', 'John Doe', '2026-06-02 09:43:14'),
(2, 'XYZ-5678', 'Ford Ranger', 'Pickup Truck', 'Black', 'Jane Smith', '2026-06-02 09:43:14'),
(3, 'AAA-9999', 'Honda Civic', 'Hatchback', 'Red', 'Bob Johnson', '2026-06-02 09:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `wash_queue`
--

CREATE TABLE `wash_queue` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `priority_level` varchar(30) DEFAULT 'Standard',
  `status` varchar(30) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wash_queue`
--

INSERT INTO `wash_queue` (`id`, `customer_id`, `service_type`, `priority_level`, `status`, `created_at`) VALUES
(1, 2, 'Engine Clean', 'Express', 'Completed', '2026-06-09 14:16:05'),
(2, 3, 'Full Wash', 'Express', 'Completed', '2026-06-09 14:19:09'),
(3, 3013, 'Full Wash', 'Express', 'Completed', '2026-06-09 18:50:59'),
(4, 3649, 'Interior Detail', 'VIP', 'Completed', '2026-06-17 17:59:25'),
(5, 3650, 'Full Wash', 'VIP', 'Completed', '2026-06-18 13:34:48'),
(6, 3650, 'Full Wash', 'VIP', 'Completed', '2026-06-18 13:35:29'),
(7, 3651, 'Full Wash', 'Standard', 'Completed', '2026-07-01 09:53:06'),
(8, 3652, 'Interior Detail', 'Standard', 'In Progress', '2026-07-04 17:16:27'),
(9, 3654, 'Full Wash', 'Standard', 'In Progress', '2026-07-07 10:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `wash_records`
--

CREATE TABLE `wash_records` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wash_records`
--

INSERT INTO `wash_records` (`id`, `customer_id`, `service`, `amount`, `date`) VALUES
(1, 3011, 'fullwash', 30.00, '2026-06-16 11:11:26'),
(2, 3013, 'engine wash', 3.00, '2026-06-16 11:12:25'),
(3, 3015, 'engine wash', 5600.00, '2026-06-17 12:02:54'),
(4, 3011, 'engine wash', 599.00, '2026-06-22 13:05:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `car_wash_bookings`
--
ALTER TABLE `car_wash_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customerregister`
--
ALTER TABLE `customerregister`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `plate` (`plate`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyal_customers`
--
ALTER TABLE `loyal_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_redeem_history`
--
ALTER TABLE `point_redeem_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `rewards_log`
--
ALTER TABLE `rewards_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wash_queue`
--
ALTER TABLE `wash_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wash_records`
--
ALTER TABLE `wash_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `car_wash_bookings`
--
ALTER TABLE `car_wash_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customerregister`
--
ALTER TABLE `customerregister`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3655;

--
-- AUTO_INCREMENT for table `loyal_customers`
--
ALTER TABLE `loyal_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `point_redeem_history`
--
ALTER TABLE `point_redeem_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rewards_log`
--
ALTER TABLE `rewards_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wash_queue`
--
ALTER TABLE `wash_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wash_records`
--
ALTER TABLE `wash_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rewards_log`
--
ALTER TABLE `rewards_log`
  ADD CONSTRAINT `rewards_log_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `loyal_customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wash_records`
--
ALTER TABLE `wash_records`
  ADD CONSTRAINT `wash_records_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
