-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 08:52 PM
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
-- Database: `rentramuros_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `first_name`, `last_name`, `email`, `password_hash`) VALUES
(3, 'Lence', 'Jalimao', 'lencejeri95@gmail.com', '$2y$10$GufPJjz96hkvpiHs7V7A0.QqpKfd.QnGHAZVXngNdbmU/r4PsbVx2');

-- --------------------------------------------------------

--
-- Table structure for table `attraction_bookings`
--

CREATE TABLE `attraction_bookings` (
  `attraction_booking_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `attraction_id` int(11) DEFAULT NULL,
  `visit_date` date NOT NULL,
  `ticket_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `image_main` varchar(255) NOT NULL,
  `image_sub` varchar(255) NOT NULL,
  `image_mini1` varchar(255) NOT NULL,
  `image_mini2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price_per_person` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_bookings`
--

CREATE TABLE `package_bookings` (
  `package_booking_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `tour_date` date NOT NULL,
  `passenger_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_itinerary`
--

CREATE TABLE `package_itinerary` (
  `package_itinerary_id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `attraction_id` int(11) DEFAULT NULL,
  `visit_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `linked_payment_id` int(11) DEFAULT NULL,
  `processed_by_admin_id` int(11) DEFAULT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popular_attractions`
--

CREATE TABLE `popular_attractions` (
  `attraction_id` int(11) NOT NULL,
  `attraction_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entrance_fee` decimal(10,2) NOT NULL,
  `operating_hours` varchar(100) DEFAULT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popular_attractions`
--

INSERT INTO `popular_attractions` (`attraction_id`, `attraction_name`, `description`, `entrance_fee`, `operating_hours`, `image_file`) VALUES
(1, 'Fort Santiago', 'A historic citadel built by Spanish navigator Miguel López de Legazpi.', 6767.00, '7:00 AM - 7:00 PM', 'asset/img/6156791707530366599.jpg'),
(2, 'Casa Manila', 'a colonial lifestyle museum in Intramuros, Manila, showcasing the19th-century, upper-class Filipino lifestyle through a meticulously designed bahay na bato reproduction. Located in the Plaza San Luis Complex, it features antique furniture, capiz windows, and a central courtyard, offering a glimpse into Spanish-era luxury. ', 6767.00, '8:00 AM - 8:00 PM', 'asset/img/6154443154988404817.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `recommened_attractions`
--

CREATE TABLE `recommened_attractions` (
  `pop_attraction_id` int(11) NOT NULL,
  `attraction_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `entrance_fee` varchar(50) NOT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `booking_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `booking_type` varchar(50) NOT NULL,
  `created_by_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourists`
--

CREATE TABLE `tourists` (
  `tourist_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `otp_code` varchar(6) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourists`
--

INSERT INTO `tourists` (`tourist_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone_number`, `otp_code`, `is_verified`, `otp_expiry`) VALUES
(1, 'Lence', 'Jalimao', 'lencejeric12@gmail.com', '$2y$10$bS2AHXPrjrXESG.71twNy.qavNMAhgtKktNHaMxUweUk1mleAzJFK', '09082970380', '0', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tour_guides`
--

CREATE TABLE `tour_guides` (
  `guide_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `current_status` varchar(20) DEFAULT 'Available',
  `last_dispatch_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_guides`
--

INSERT INTO `tour_guides` (`guide_id`, `first_name`, `last_name`, `email`, `password_hash`, `current_status`, `last_dispatch_time`) VALUES
(2, 'David Lloyd', 'Contreras', 'davidlloydcontreras@gmail.com', '$2y$10$Crc8OGKov5r37gRAkLQZtOu57utVBQUGUFVv3Vy4OBCR.bAxiOFRa', 'Available', '2026-03-23 23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_events`
--

CREATE TABLE `upcoming_events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_date` date DEFAULT current_timestamp(),
  `event_time` time DEFAULT current_timestamp(),
  `location` varchar(255) NOT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `passenger_capacity` int(11) NOT NULL,
  `current_status` varchar(20) DEFAULT 'Available',
  `last_dispatch_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_type`, `passenger_capacity`, `current_status`, `last_dispatch_time`) VALUES
(1, 'E-Tricycle', 6, 'Available', '2026-03-24 02:11:17'),
(2, 'E-Tricycle', 6, 'Available', '2026-03-24 02:11:24'),
(3, 'E-Tricycle', 6, 'Available', '2026-03-24 02:11:30'),
(4, 'Kalesa', 6, 'Available', '2026-03-24 02:11:36'),
(5, 'Kalesa', 6, 'Available', '2026-03-24 02:11:42'),
(6, 'Kalesa', 6, 'Available', '2026-03-24 02:11:47'),
(7, 'Tranvia', 20, 'Available', '2026-03-24 02:11:53'),
(8, 'Tranvia', 20, 'Available', '2026-03-24 02:11:58'),
(9, 'Tranvia', 20, 'Available', '2026-03-24 02:12:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attraction_bookings`
--
ALTER TABLE `attraction_bookings`
  ADD PRIMARY KEY (`attraction_booking_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_bookings`
--
ALTER TABLE `package_bookings`
  ADD PRIMARY KEY (`package_booking_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `package_itinerary`
--
ALTER TABLE `package_itinerary`
  ADD PRIMARY KEY (`package_itinerary_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `processed_by_admin_id` (`processed_by_admin_id`),
  ADD KEY `linked_payment_id` (`linked_payment_id`),
  ADD KEY `tourist_id` (`tourist_id`) USING BTREE;

--
-- Indexes for table `popular_attractions`
--
ALTER TABLE `popular_attractions`
  ADD PRIMARY KEY (`attraction_id`);

--
-- Indexes for table `recommened_attractions`
--
ALTER TABLE `recommened_attractions`
  ADD PRIMARY KEY (`pop_attraction_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `created_by_admin_id` (`created_by_admin_id`),
  ADD KEY `tourist_id` (`tourist_id`) USING BTREE;

--
-- Indexes for table `tourists`
--
ALTER TABLE `tourists`
  ADD PRIMARY KEY (`tourist_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD PRIMARY KEY (`guide_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `upcoming_events`
--
ALTER TABLE `upcoming_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attraction_bookings`
--
ALTER TABLE `attraction_bookings`
  MODIFY `attraction_booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_bookings`
--
ALTER TABLE `package_bookings`
  MODIFY `package_booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_itinerary`
--
ALTER TABLE `package_itinerary`
  MODIFY `package_itinerary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `popular_attractions`
--
ALTER TABLE `popular_attractions`
  MODIFY `attraction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recommened_attractions`
--
ALTER TABLE `recommened_attractions`
  MODIFY `pop_attraction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tourists`
--
ALTER TABLE `tourists`
  MODIFY `tourist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour_guides`
--
ALTER TABLE `tour_guides`
  MODIFY `guide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `upcoming_events`
--
ALTER TABLE `upcoming_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attraction_bookings`
--
ALTER TABLE `attraction_bookings`
  ADD CONSTRAINT `attraction_bookings_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `attraction_bookings_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `popular_attractions` (`attraction_id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `package_bookings`
--
ALTER TABLE `package_bookings`
  ADD CONSTRAINT `package_bookings_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `package_bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
  ADD CONSTRAINT `package_bookings_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`),
  ADD CONSTRAINT `package_bookings_ibfk_4` FOREIGN KEY (`guide_id`) REFERENCES `tour_guides` (`guide_id`);

--
-- Constraints for table `package_itinerary`
--
ALTER TABLE `package_itinerary`
  ADD CONSTRAINT `package_itinerary_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
  ADD CONSTRAINT `package_itinerary_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `popular_attractions` (`attraction_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`tourist_id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`processed_by_admin_id`) REFERENCES `admins` (`admin_id`),
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`linked_payment_id`) REFERENCES `payments` (`payment_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`tourist_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`created_by_admin_id`) REFERENCES `admins` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
