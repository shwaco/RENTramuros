-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 04:40 PM
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
  `mini_two_img` varchar(255) DEFAULT NULL,
  `rec_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attraction_id`, `attraction_type`, `attraction_name`, `description`, `schedule`, `fee`, `main_img`, `mini_one_img`, `mini_two_img`, `rec_img`) VALUES
(1, 'Recommended', 'Fort Santiago', 'A historic citadel built by Spanish navigator Miguel López de Legazpi.', '7:00 AM - 7:00 PM', 100.00, '6156791707530366599.jpg', '6156791707530366599.jpg', '6156791707530366599.jpg', '6156791707530366599.jpg'),
(2, 'Popular', 'Casa Manila', 'a colonial lifestyle museum in Intramuros, Manila, showcasing the19th-century, upper-class Filipino lifestyle through a meticulously designed bahay na bato reproduction. Located in the Plaza San Luis Complex, it features antique furniture, capiz windows, and a central courtyard, offering a glimpse into Spanish-era luxury. ', '8:00 AM - 8:00 PM', 100.00, '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg'),
(3, 'Popular', 'San Agustin Museum ', 'San Agustin Museum opened its doors in 1973 providing access to the attached 16th-century San Agustin Church, a Unesco Heritage site. Features the church\'s huge collection of religious artifacts, architecture, carvings, furniture, choir books the oldest in the Philippines.', '7:00 AM - 7:00 PM', 200.00, '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg'),
(4, 'Recommended', 'San Agustin Church', 'Historic church constructed starting in the 16th century with vaulted ceilings & detailed frescoes.\r\n', '8:00 AM - 8:00 PM', 0.00, '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg'),
(5, 'Popular', 'Centro de Turismo Intramuros', 'Stands tall from what was once the remains of Iglesia de San Ignacio designed for the Jesuits by architect Félix Roxas Sr. completed in 1899, destroyed in the Battle of Manila on 1945, and now rebuilt as the central hub of tourism for the Walled City of Intramuros.', '9:00 AM - 5:00 PM', 150.00, '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `booking_request_id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `booking_type` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `booking_time` time DEFAULT current_timestamp(),
  `booking_date` date DEFAULT current_timestamp(),
  `adults_and_seniors` int(50) DEFAULT NULL,
  `children` int(50) DEFAULT NULL,
  `infants` int(50) DEFAULT NULL,
  `contact_info_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `number_of_vehicle` int(11) DEFAULT NULL,
  `guide_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_history`
--

INSERT INTO `booking_history` (`booking_request_id`, `tourist_id`, `booking_type`, `status`, `booking_time`, `booking_date`, `adults_and_seniors`, `children`, `infants`, `contact_info_id`, `vehicle_id`, `number_of_vehicle`, `guide_id`) VALUES
(1, 1, 'Package', 'Done', '15:22:38', '2026-04-25', 3, 2, 0, 1, 2, 1, NULL),
(3, 1, 'Package', 'Cancel', '15:38:52', '2026-04-26', 2, 1, 0, 11, NULL, 0, NULL),
(4, 1, 'Attraction', 'PENDING', '15:42:45', '2026-04-26', 2, 1, 0, 12, NULL, 0, NULL),
(7, 1, 'Attraction', 'PENDING', '15:57:15', '2026-04-26', 2, 1, 0, 13, NULL, 0, NULL),
(8, 1, 'Attraction', 'PENDING', '16:02:24', '2026-04-26', 2, 1, 0, 14, NULL, 0, NULL),
(9, 1, 'Attraction', 'Pending', '17:20:57', '2026-04-26', 2, 1, 0, 15, NULL, 0, NULL),
(10, 1, 'Attraction', 'Pending', '17:59:02', '2026-04-26', 2, 1, 1, 16, NULL, 0, NULL),
(11, 1, 'Package', 'Pending', '22:49:31', '2026-04-26', 4, 0, 0, 17, NULL, 1, NULL),
(12, 1, 'Package', 'Pending', '22:51:13', '2026-04-26', 4, 0, 0, 18, 1, 1, NULL),
(13, 1, 'Package', 'Pending', '22:52:44', '2026-04-26', 4, 0, 0, 19, 1, 1, NULL),
(14, 1, 'Package', 'Pending', '10:57:14', '2026-04-27', 4, 0, 0, 20, 1, 1, NULL),
(15, 1, 'Package', 'Pending', '14:08:53', '2026-04-27', 4, 0, 0, 21, 3, 1, NULL),
(16, 1, 'Package', 'Pending', '14:10:15', '2026-04-27', 4, 0, 0, 22, 3, 1, NULL),
(17, 1, 'Package', 'Pending', '14:11:40', '2026-04-27', 4, 0, 0, 23, 3, 1, NULL),
(18, 1, 'Package', 'Pending', '14:13:43', '2026-04-27', 4, 0, 0, 24, 3, 1, NULL),
(19, 1, 'Package', 'Pending', '14:14:17', '2026-04-27', 4, 0, 0, 25, 3, 1, NULL),
(20, 1, 'Package', 'Pending', '14:52:32', '2026-04-27', 4, 0, 0, 26, 3, 1, NULL),
(21, 1, 'Attractions', 'Pending', '21:53:27', '2026-04-27', 6, 2, 0, NULL, 2, 3, NULL),
(22, 1, 'Attractions', 'Pending', '22:01:49', '2026-04-27', 6, 2, 0, 27, 2, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_information`
--

CREATE TABLE `contact_information` (
  `contact_info_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_information`
--

INSERT INTO `contact_information` (`contact_info_id`, `first_name`, `last_name`, `email_address`, `phone_number`) VALUES
(1, 'Lence Jericho', 'Jalimao', 'lencejeri95@gmail.com', '09082970380'),
(2, 'David Lloyd ', 'Contreras', 'davidlloydcontreras@gmail.com', '09081314196'),
(11, 'Lence Jericho ', 'Jalimao', 'lence@example.com', '09123456789'),
(12, 'Styles ', 'Bou', 'misskonasiya101@example.com', '09220086212'),
(13, 'Spider ', 'Milez', 'lambe@example.com', '09221186121'),
(14, 'Spider ', 'Milez', 'lambe@example.com', '09221186121'),
(15, 'Spider ', 'Mondragon', 'lambing@example.com', '09343486121'),
(16, 'Spider ', 'Mondragon', 'lambing@example.com', '09343486121'),
(17, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(18, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(19, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(20, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(21, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(22, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(23, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(24, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(25, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(26, 'Jinalimao ', 'Contreras', 'arawdavid123@example.com', '09123456787'),
(27, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341');

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
  `price` decimal(10,2) NOT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `package_name`, `description`, `price`, `image_file`) VALUES
(1, 'Hero\'s Trail', '- Fort Santiago entrance ticket \r\n- Rizal Shrine \r\n- Rizal\'s Bagumbayan Light and Sound Museum', 676.67, '6154443154988404815.jpg'),
(2, 'Cultural Combo', ' - Casa Manila\r\n - Barbara\'s Heritage Restaurant\r\n - Silahis Center ', 767.67, '6154443154988404815.jpg'),
(3, 'Walled City Grand Tour', ' - Fort Santiago tour\r\n - San Agustin Museum \r\n - Minor Basilica\r\n - Casa Manila\r\n - Bambike', 100.00, '6154443154988404815.jpg'),
(4, 'Bastions and Walls', ' - Puerta Del Parian\r\n - Fort Santiago\r\n - Palacio del Gobernador\r\n - Puerta Real', 200.00, '6154443154988404815.jpg'),
(5, 'Sacred Route', ' - Minor Basilica \r\n - San Agustin Church', 100.00, '6154443154988404815.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `request_attractions`
--

CREATE TABLE `request_attractions` (
  `booking_request_id` int(11) NOT NULL,
  `attraction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_attractions`
--

INSERT INTO `request_attractions` (`booking_request_id`, `attraction_id`) VALUES
(20, 1),
(20, 2),
(21, 2),
(21, 4),
(21, 5),
(22, 2),
(22, 4),
(22, 5);

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
(1, 'Summer Festival', '2026-04-25', '08:00:00', 'Plaza Moriones, Fort Santiago', '2026-Intramuros-Summer-Festival.png'),
(2, 'Summer Festival', '2026-04-25', '08:00:00', 'Plaza Moriones, Fort Santiago', '2026-Intramuros-Summer-Festival.png'),
(3, 'TamRun', '2026-05-02', '08:00:00', 'Fort Santiago', 'TamRun.jpg'),
(4, 'Philippine Eatsperience', '2026-12-29', '08:00:00', 'Fort Santiago', 'Eatsperience.jpg'),
(5, 'Mystery Manila', '2026-10-31', '08:00:00', 'Fort Santiago', 'MysteryManila.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `passenger_capacity` varchar(20) NOT NULL,
  `image_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_type`, `passenger_capacity`, `image_file`) VALUES
(1, 'TukTuk', '3-4', 'TukTuk.jpg'),
(2, 'Kalesa', '2-4', 'Kalesa.jpg'),
(3, 'Tranvia', '12-20', 'Tranvia.jpg');

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
-- Indexes for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD PRIMARY KEY (`booking_request_id`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `contact_info_id` (`contact_info_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `fk_guide` (`guide_id`);

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
-- Indexes for table `request_attractions`
--
ALTER TABLE `request_attractions`
  ADD PRIMARY KEY (`booking_request_id`,`attraction_id`),
  ADD KEY `attraction_id` (`attraction_id`);

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
  MODIFY `attraction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `booking_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `contact_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD CONSTRAINT `booking_history_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`tourist_id`),
  ADD CONSTRAINT `booking_history_ibfk_2` FOREIGN KEY (`contact_info_id`) REFERENCES `contact_information` (`contact_info_id`),
  ADD CONSTRAINT `booking_history_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`),
  ADD CONSTRAINT `fk_guide` FOREIGN KEY (`guide_id`) REFERENCES `tour_guides` (`guide_id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `request_attractions`
--
ALTER TABLE `request_attractions`
  ADD CONSTRAINT `request_attractions_ibfk_1` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_history` (`booking_request_id`),
  ADD CONSTRAINT `request_attractions_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`attraction_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
