-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 09:39 AM
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
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `attraction_id` int(11) NOT NULL,
  `attraction_type` varchar(50) DEFAULT NULL,
  `attraction_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `schedule` varchar(50) DEFAULT NULL,
  `fee` decimal(5,2) DEFAULT NULL,
  `main_img` varchar(255) DEFAULT NULL,
  `mini_one_img` varchar(255) DEFAULT NULL,
  `mino_two_img` varchar(255) DEFAULT NULL,
  `rec_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attraction_id`, `attraction_type`, `attraction_name`, `description`, `schedule`, `fee`, `main_img`, `mini_one_img`, `mino_two_img`, `rec_img`) VALUES
(1, 'Recommended', 'Fort Santiago', 'A historic citadel built by Spanish navigator Miguel López de Legazpi.', '7:00 AM - 7:00 PM', 100.00, 'asset/img/6156791707530366599.jpg', 'asset/img/6156791707530366599.jpg', 'asset/img/6156791707530366599.jpg', 'asset/img/6156791707530366599.jpg'),
(2, 'Popular', 'Casa Manila', 'a colonial lifestyle museum in Intramuros, Manila, showcasing the19th-century, upper-class Filipino lifestyle through a meticulously designed bahay na bato reproduction. Located in the Plaza San Luis Complex, it features antique furniture, capiz windows, and a central courtyard, offering a glimpse into Spanish-era luxury. ', '8:00 AM - 8:00 PM', 100.00, 'asset/img/6154443154988404817.jpg', 'asset/img/6154443154988404817.jpg', 'asset/img/6154443154988404817.jpg', 'asset/img/6154443154988404817.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking_request`
--

CREATE TABLE `booking_request` (
  `booking_request_id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `booking_time` time DEFAULT current_timestamp(),
  `booking_date` date DEFAULT current_timestamp(),
  `adults_and_seniors` int(50) DEFAULT NULL,
  `children` int(50) DEFAULT NULL,
  `infants` int(50) DEFAULT NULL,
  `contact_info_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `number_of_vehicle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_request`
--

INSERT INTO `booking_request` (`booking_request_id`, `tourist_id`, `status`, `booking_time`, `booking_date`, `adults_and_seniors`, `children`, `infants`, `contact_info_id`, `vehicle_id`, `number_of_vehicle`) VALUES
(1, 1, 'Pending', '15:22:38', '2026-04-25', 3, 2, 0, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_information`
--

CREATE TABLE `contact_information` (
  `contact_info_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_information`
--

INSERT INTO `contact_information` (`contact_info_id`, `full_name`, `email_address`, `phone_number`) VALUES
(1, 'Lence Jericho Jalimao', 'lencejeri95@gmail.com', '09082970380'),
(2, 'David Lloyd Contreras', 'davidlloydcontreras@gmail.com', '09081314196');

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

--
-- Dumping data for table `upcoming_events`
--

INSERT INTO `upcoming_events` (`event_id`, `event_name`, `event_date`, `event_time`, `location`, `image_file`) VALUES
(1, 'Summer Festival', '2026-04-25', '08:00:00', 'Plaza Moriones, Fort Santiago', 'asset/img/2026-Intramuros-Summer-Festival.png'),
(2, 'Summer Festival', '2026-04-25', '08:00:00', 'Plaza Moriones, Fort Santiago', 'asset/img/2026-Intramuros-Summer-Festival.png');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `passenger_capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_type`, `passenger_capacity`) VALUES
(1, 'TukTuk', 5),
(2, 'Kalesa', 6),
(3, 'Tranvia', 20);

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
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`attraction_id`);

--
-- Indexes for table `booking_request`
--
ALTER TABLE `booking_request`
  ADD PRIMARY KEY (`booking_request_id`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `contact_info_id` (`contact_info_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `contact_information`
--
ALTER TABLE `contact_information`
  ADD PRIMARY KEY (`contact_info_id`);

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
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `attraction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_request`
--
ALTER TABLE `booking_request`
  MODIFY `booking_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `contact_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_request`
--
ALTER TABLE `booking_request`
  ADD CONSTRAINT `booking_request_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`tourist_id`),
  ADD CONSTRAINT `booking_request_ibfk_2` FOREIGN KEY (`contact_info_id`) REFERENCES `contact_information` (`contact_info_id`),
  ADD CONSTRAINT `booking_request_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
