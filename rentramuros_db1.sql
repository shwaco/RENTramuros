-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 12:52 AM
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
DROP TABLE IF EXISTS `admins`;

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
(5, 'Popular', 'Centro de Turismo Intramuros', 'Stands tall from what was once the remains of Iglesia de San Ignacio designed for the Jesuits by architect Félix Roxas Sr. completed in 1899, destroyed in the Battle of Manila on 1945, and now rebuilt as the central hub of tourism for the Walled City of Intramuros.', '9:00 AM - 5:00 PM', 150.00, '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg', '6154443154988404817.jpg'),
(6, 'Popular', 'Bambike Ecotours Intramuros', 'Cycle around incredible historic neighborhoods and exciting destinations on bamboo bikes! Guided bike tours allow you to explore various parts of the Philippines in a safe, fun, and informative way. The tours are typically done in small groups of around 5 - 10 pax. Choose from a wide variety of Bambike designs which include our signature Ligtasin Cove beach cruisers, Victoria city bikes and other handmade bamboo bike designs as each Bambike is unique. Our guides will serve as your Bambassadors, making sure that you are well taken care of throughout your entire experience with us at Bambike Ecotours.', '9:00 AM - 5:00 PM', 100.00, 'Bambike.jpg', 'Bambike.jpg', 'Bambike.jpg', 'Bambike.jpg'),
(7, 'Recommended', 'Barbara\'s Heritage Restaurant', 'Barbara\'s is a well-known Filipino heritage restaurant located inside the Walled City of Intramuros. The restaurants boasts the classic architecture popular in the 18th century when the Spanish colonized the Philippines.\r\n\r\nAside from the food, the restaurant also hosts a Kultura Night, wherein members of the Folklorico Filipino Dance Company would dance the Singkil of Mindanao, Tinikling of Visayas, Pandango sa Ilaw of Luzon, and other traditional dances to the beat of Filipino folk songs.', '11:30 AM - 8:30 PM', 0.00, 'Barbara.jpg', 'Barbara.jpg', 'Barbara.jpg', 'Barbara.jpg'),
(8, 'Popular', 'Minor Basilica', 'The Minor Basilica and Metropolitan Cathedral of the Immaculate Conception, commonly known as the Manila Cathedral, is a Roman Catholic basilica and the cathedral of the Archdiocese of Manila. It is dedicated to the Blessed Virgin Mary of the Immaculate Conception, the principal patroness of the Philippines.', '8:00 AM - 4:30 PM', 0.00, 'Basilica.jpg', 'Basilica.jpg', 'Basilica.jpg', 'Basilica.jpg'),
(9, 'Popular', 'Museo de Intramuros', 'The Museo de Intramuros comprises two important reconstructions: the San Ignacio Church and the Mission House of the Society of Jesus. As the name denotes, the complex now houses the vast ecclesiastical collection of the Intramuros Administration.  \r\n\r\nFirst built in 1878 by the Society of Jesus and completed in 1889, the San Ignacio Church, according to contemporaries, was said to be one of the most beautiful in old Manila.', '9:00 AM - 5:00 PM', 75.00, 'Museo.jpg', 'Museo.jpg', 'Museo.jpg', 'Museo.jpg'),
(10, 'Recommeded', 'Palacio del Gobernador', 'The Palacio del Gobernador was a two-storey building, with a rusticated ground floor, second-floor piano nobile, and attic topped by a tiled hip roof. It was rebuilt in 1733 and 1745. The building underwent a comprehensive renovations in the European style in 1845, although its back retained a typical bahay na bato style, with the second floor covered by capiz shells windows.', '8:00 AM - 5:00 PM', 0.00, 'Palacio.jpg', 'Palacio.jpg', 'Palacio.jpg', 'Palacio.jpg'),
(11, 'Recommended', 'Puerta del Parian', 'Puerta Del Parian is among the eight gates that serve as an entrance to the Walled City of Intramuros. The gates were called Puerta, which is the original Spanish word for \"gate.\" Hence, the name Puerta del Parian means \"Gate of the Market,\" signifying its crucial role in Philippine history. From the Spanish colonial times to the British occupation is 1762-1764, as this was the place where many enterprising Chinese traded goods. ', '24/7 Open', 0.00, 'Parian.jpg', 'Parian.jpg', 'Parian.jpg', 'Parian.jpg'),
(12, 'Recommended', 'Puerta Real Gardens', 'The Puerta Real was once used exclusively by governor generals for special occasions and served as the southern access between Intramuros to Ermita. Severely damaged during the 1945 Battle of Manila, the gate was restored in 1966, with the ravelin later being transformed into a garden venue in the 1980s for open-air activities. Visitors may do picnics, jog, stroll with their pets (provided pets are wearing diapers), and attend cultural events in this site.', '8:00 AM - 6:00 PM', 0.00, 'Real-Gardens.jpg', 'Real-Gardens.jpg', 'Real-Gardens.jpg', 'Real-Gardens.jpg'),
(13, 'Recommended', 'Rizal Shrine', 'Located inside Fort Santiago in Intramuros, Museo ni Rizal is a museum and shrine that showcases Jose Rizal’s importance in the history of the Philippines. The building used to be a barracks built in the 1500s, but it was destroyed during the Battle of Manila in 1945. The barracks wing where Jose Rizal’s prison cell was located was reconstructed in 1953 and now serves as the museum. ', '9:00 AM - 6:00 PM', 75.00, 'Shrine.jpg', 'Shrine.jpg', 'Shrine.jpg', 'Shrine.jpg'),
(14, 'Recommended', 'Rizal\'s Bagumbayan Light and Sound Museum', 'Intramuros and Rizal’s Bagumbayan Light and Sound Museum is a must-visit in Manila, offering a deep dive into the history of the Philippines. The walled city houses significant sites such as Fort Santiago, Manila Cathedral, San Agustin Church, Casa Manila museum, and the Rizal Bagumbayan Light and Sound Museum.\r\n', '8:00 AM - 5:00 PM', 150.00, 'Bagumbayan.jpg', 'Bagumbayan.jpg', 'Bagumbayan.jpg', 'Bagumbayan.jpg'),
(15, 'Popular', 'Silahis Art and Artifacts Inc.', 'Established in 1966, Silahis Arts and Artifacts is one building in which you can visit the entire archipelago and learn something of Philippine life and history.', '10:00 AM - 5:00 PM', 0.00, 'Silahis.jpg', 'Silahis.jpg', 'Silahis.jpg', 'Silahis.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `booking_request_id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `booking_type` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `booking_time` varchar(100) DEFAULT NULL,
  `booking_date` varchar(100) DEFAULT NULL,
  `adults_and_seniors` int(50) DEFAULT NULL,
  `children` int(50) DEFAULT NULL,
  `infants` int(50) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `contact_info_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `number_of_vehicle` int(11) DEFAULT NULL,
  `guide_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_history`
--

INSERT INTO `booking_history` (`booking_request_id`, `tourist_id`, `booking_type`, `status`, `booking_time`, `booking_date`, `adults_and_seniors`, `children`, `infants`, `package_id`, `contact_info_id`, `vehicle_id`, `number_of_vehicle`, `guide_id`) VALUES
(1, 1, 'Package', 'Done', '15:22:38', '2026-04-25', 3, 2, 0, NULL, 1, 2, 1, NULL),
(3, 1, 'Package', 'Cancel', '15:38:52', '2026-04-26', 2, 1, 0, NULL, 11, NULL, 0, NULL),
(4, 1, 'Attraction', 'PENDING', '15:42:45', '2026-04-26', 2, 1, 0, NULL, 12, NULL, 0, NULL),
(7, 1, 'Attraction', 'PENDING', '15:57:15', '2026-04-26', 2, 1, 0, NULL, 13, NULL, 0, NULL),
(8, 1, 'Attraction', 'PENDING', '16:02:24', '2026-04-26', 2, 1, 0, NULL, 14, NULL, 0, NULL),
(9, 1, 'Attraction', 'Pending', '17:20:57', '2026-04-26', 2, 1, 0, NULL, 15, NULL, 0, NULL),
(10, 1, 'Attraction', 'Pending', '17:59:02', '2026-04-26', 2, 1, 1, NULL, 16, NULL, 0, NULL),
(11, 1, 'Package', 'Pending', '22:49:31', '2026-04-26', 4, 0, 0, NULL, 17, NULL, 1, NULL),
(12, 1, 'Package', 'Pending', '22:51:13', '2026-04-26', 4, 0, 0, NULL, 18, 1, 1, NULL),
(13, 1, 'Package', 'Pending', '22:52:44', '2026-04-26', 4, 0, 0, NULL, 19, 1, 1, NULL),
(14, 1, 'Package', 'Pending', '10:57:14', '2026-04-27', 4, 0, 0, NULL, 20, 1, 1, NULL),
(15, 1, 'Package', 'Pending', '14:08:53', '2026-04-27', 4, 0, 0, NULL, 21, 3, 1, NULL),
(16, 1, 'Package', 'Pending', '14:10:15', '2026-04-27', 4, 0, 0, NULL, 22, 3, 1, NULL),
(17, 1, 'Package', 'Pending', '14:11:40', '2026-04-27', 4, 0, 0, NULL, 23, 3, 1, NULL),
(18, 1, 'Package', 'Pending', '14:13:43', '2026-04-27', 4, 0, 0, NULL, 24, 3, 1, NULL),
(19, 1, 'Package', 'Pending', '14:14:17', '2026-04-27', 4, 0, 0, NULL, 25, 3, 1, NULL),
(20, 1, 'Package', 'Pending', '14:52:32', '2026-04-27', 4, 0, 0, NULL, 26, 3, 1, NULL),
(21, 1, 'Attractions', 'Pending', '21:53:27', '2026-04-27', 6, 2, 0, NULL, NULL, 2, 3, NULL),
(22, 1, 'Attractions', 'Pending', '22:01:49', '2026-04-27', 6, 2, 0, NULL, 27, 2, 3, NULL),
(54, 1, 'Package', 'Pending', '05:16:36', '2026-04-28', 6, 2, 0, NULL, NULL, 2, 3, NULL),
(55, NULL, '', 'Pending', '05:16:36', '2026-04-28', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(56, 1, 'Package', 'Pending', '05:17:08', '2026-04-28', 6, 2, 0, NULL, NULL, 2, 3, NULL),
(57, NULL, '', 'Pending', '05:17:08', '2026-04-28', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(58, 1, 'Package', 'Pending', '05:24:45', '2026-04-28', 6, 2, 0, 1, NULL, 2, 3, NULL),
(59, 1, 'Package', 'Pending', '05:27:47', '2026-04-28', 6, 2, 0, 1, 28, 2, 3, NULL),
(60, 1, 'Attractions', 'Pending', '05:29:34', '2026-04-28', 6, 2, 0, NULL, 30, 2, 3, NULL),
(61, 1, 'Attractions', 'Pending', '05:29:53', '2026-04-28', 6, 2, 0, NULL, 31, 2, 3, NULL),
(62, 1, 'Attractions', 'Pending', '05:30:57', '2026-04-28', 6, 2, 0, NULL, 32, 2, 3, NULL),
(63, 1, 'Attractions', 'Pending', '05:32:34', '2026-04-28', 6, 2, 0, NULL, 33, 2, 3, NULL),
(64, 1, 'Attractions', 'Pending', '05:34:49', '2026-04-28', 6, 2, 0, NULL, 34, 2, 3, NULL),
(65, 1, 'Attractions', 'Pending', '05:41:22', '2026-04-28', 6, 2, 0, NULL, 35, 2, 3, NULL),
(66, 1, 'Attractions', 'Pending', '05:41:50', '2026-04-28', 6, 2, 0, NULL, 36, 2, 3, NULL),
(67, 1, 'Package', 'Pending', '06:01:27', '2026-04-28', 6, 2, 0, 2, 37, 2, 3, NULL),
(68, 1, 'Attractions', 'Pending', '06:06:24', '2026-04-28', 2, 0, 0, NULL, 38, 1, 1, NULL),
(69, 1, 'Attractions', 'Pending', '06:07:36', '2026-04-28', 2, 0, 0, NULL, 39, 1, 1, NULL),
(70, 1, 'Attractions', 'Pending', '06:09:10', '2026-04-28', 2, 0, 0, NULL, 40, 1, 1, NULL),
(71, 1, 'Attractions', 'Pending', '06:11:32', '2026-04-28', 2, 0, 0, NULL, 41, 1, 1, NULL),
(72, 1, 'Attractions', 'Pending', '06:34:12', '2026-04-28', 2, 0, 0, NULL, NULL, 2, 0, NULL),
(73, 1, 'Attractions', 'Pending', '06:35:07', '2026-04-28', 2, 0, 0, NULL, NULL, 2, 0, NULL),
(74, 1, 'Attractions', 'Pending', NULL, NULL, 2, 0, 0, NULL, NULL, 2, 0, NULL),
(75, 1, 'Attractions', 'Pending', '08:00 AM', 'November 12, 2026', 2, 0, 0, NULL, NULL, 2, 0, NULL);

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
(27, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(28, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(30, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(31, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(32, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(33, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(34, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(35, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(36, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(37, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(38, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(39, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(40, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341'),
(41, 'Princess Rola', 'Motus', 'PRM2005@example.com', '098875872341');

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
(22, 5),
(71, 2),
(71, 3),
(71, 6),
(73, 1),
(73, 2),
(73, 5),
(73, 8),
(74, 1),
(74, 2),
(74, 5),
(74, 8),
(75, 1),
(75, 2),
(75, 5),
(75, 8);

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
  `last_active_at` datetime DEFAULT current_timestamp(),
  `last_dispatch_time` datetime DEFAULT current_timestamp(),
  `became_available_at` datetime DEFAULT current_timestamp(),
  `current_tourist_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_guides`
--

INSERT INTO `tour_guides` (`guide_id`, `first_name`, `last_name`, `email`, `password_hash`, `current_status`, `last_active_at`, `last_dispatch_time`, `became_available_at`, `current_tourist_id`) VALUES
(2, 'David Lloyd', 'Contreras', 'davidlloydcontreras@gmail.com', '$2y$10$Crc8OGKov5r37gRAkLQZtOu57utVBQUGUFVv3Vy4OBCR.bAxiOFRa', 'Available', '2026-04-27 23:18:03', '2026-03-23 23:30:00', '2026-04-27 23:22:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_events`
--

CREATE TABLE `upcoming_events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
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
  ADD KEY `fk_guide` (`guide_id`),
  ADD KEY `package_id` (`package_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `current_tourist_id` (`current_tourist_id`);

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
  MODIFY `attraction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `booking_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `contact_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
  ADD CONSTRAINT `booking_history_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
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

--
-- Constraints for table `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD CONSTRAINT `tour_guides_ibfk_1` FOREIGN KEY (`current_tourist_id`) REFERENCES `tourists` (`tourist_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
