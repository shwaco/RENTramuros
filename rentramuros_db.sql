-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 04, 2026 at 09:15 PM
-- Server version: 8.0.45-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentramuros_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `first_name`, `last_name`, `email`, `password_hash`) VALUES
(1, 'RENTramuros', 'RENTramuros', 'rentramuros@gmail.com', '$2y$10$0.6aRsz2YvyUmVoMVWElCeR8iEblcH08elahCqSnzh6uLOaIzP0/q');

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `attraction_id` int NOT NULL,
  `attraction_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attraction_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `schedule` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fee` decimal(5,2) DEFAULT NULL,
  `main_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mini_one_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mini_two_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rec_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attraction_id`, `attraction_type`, `attraction_name`, `address`, `description`, `schedule`, `fee`, `main_img`, `mini_one_img`, `mini_two_img`, `rec_img`) VALUES
(1, 'Recommended', 'Fort Santiago', 'Sta. Clara St, Intramuros, Manila, 1002 Metro Manila, Philippines', 'A historic citadel built by Spanish navigator Miguel López de Legazpi.', '7:00 AM - 7:00 PM', 100.00, 'fort_santiago/pic1_main.jpg', 'fort_santiago/pic2_mini_one.jpg', 'fort_santiago/pic3_mini_two.jpg', 'fort_santiago/pic4_rec.jpg'),
(2, 'Popular', 'Casa Manila', 'Casa Manila, Plaza San Luis Complex, General Luna cor. Real Sts., Intramuros, Manila', 'A colonial lifestyle museum in Intramuros, Manila, showcasing the19th-century, upper-class Filipino lifestyle through a meticulously designed bahay na bato reproduction. Located in the Plaza San Luis Complex, it features antique furniture, capiz windows, and a central courtyard, offering a glimpse into Spanish-era luxury. ', '8:00 AM - 8:00 PM', 100.00, 'casa_manila/pic1_main.jpg', 'casa_manila/pic2_mini_one.jpg', 'casa_manila/pic3_mini_two.jpg', 'casa_manila/pic4_rec.jpg'),
(3, 'Popular', 'San Agustin Museum ', 'General Luna Street, Intramuros, Manila 1002, Philippines', 'San Agustin Museum opened its doors in 1973 providing access to the attached 16th-century San Agustin Church, a Unesco Heritage site. Features the church\'s huge collection of religious artifacts, architecture, carvings, furniture, choir books the oldest in the Philippines.', '7:00 AM - 7:00 PM', 200.00, 'san_agustin_museum/pic1_main.jpg', 'san_agustin_museum/pic2_mini_one.jpg', 'san_agustin_museum/pic3_mini_two.jpg', 'san_agustin_museum/pic4_rec.jpg'),
(4, 'Recommended', 'San Agustin Church', 'General Luna Street, Intramuros, Manila, Metro Manila.', 'Historic church constructed starting in the 16th century with vaulted ceilings & detailed frescoes.\r\n', '8:00 AM - 8:00 PM', 0.00, 'san_agustin_church/pic1_main.jpg', 'san_agustin_church/pic2_mini_one.jpg', 'san_agustin_church/pic3_mini_two.jpg', 'san_agustin_church/pic4_rec.jpg'),
(5, 'Popular', 'Centro de Turismo Intramuros', 'Old San Ignacio Church, Arzobispo St, Intramuros, Manila, 1002 Metro Manila', 'Stands tall from what was once the remains of Iglesia de San Ignacio designed for the Jesuits by architect Félix Roxas Sr. completed in 1899, destroyed in the Battle of Manila on 1945, and now rebuilt as the central hub of tourism for the Walled City of Intramuros.', '9:00 AM - 5:00 PM', 150.00, 'centro_de_turismo/pic1_main.jpg', 'centro_de_turismo/pic2_mini_one.jpg', 'centro_de_turismo/pic3_mini_two.jpg', 'centro_de_turismo/pic4_rec.jpg'),
(6, 'Popular', 'Bambike Ecotours Intramuros', 'Bambike HQ, Plaza San Luis Complex. Real St, corner M General Luna St, Intramuros, Manila, 1002 Metr', 'Cycle around incredible historic neighborhoods and exciting destinations on bamboo bikes! Guided bike tours allow you to explore various parts of the Philippines in a safe, fun, and informative way. The tours are typically done in small groups of around 5 - 10 pax. Choose from a wide variety of Bambike designs which include our signature Ligtasin Cove beach cruisers, Victoria city bikes and other handmade bamboo bike designs as each Bambike is unique. Our guides will serve as your Bambassadors, making sure that you are well taken care of throughout your entire experience with us at Bambike Ecotours.', '9:00 AM - 5:00 PM', 100.00, 'bambike/pic1_main.jpg', 'bambike/pic2_mini_one.jpg', 'bambike/pic3_mini_two.jpg', 'bambike/pic4_rec.jpg'),
(7, 'Recommended', 'Barbara\'s Heritage Restaurant', 'Plaza San Luis Complex, General Luna St, Intramuros, Manila, 1002 Metro Manila, Philippines', 'Barbara\'s is a well-known Filipino heritage restaurant located inside the Walled City of Intramuros. The restaurants boasts the classic architecture popular in the 18th century when the Spanish colonized the Philippines.\r\n\r\nAside from the food, the restaurant also hosts a Kultura Night, wherein members of the Folklorico Filipino Dance Company would dance the Singkil of Mindanao, Tinikling of Visayas, Pandango sa Ilaw of Luzon, and other traditional dances to the beat of Filipino folk songs.', '11:30 AM - 8:30 PM', 0.00, 'barbara/pic1_main.jpg', 'barbara/pic2_mini_one.jpg', 'barbara/pic3_mini_two.jpg', 'barbara/pic4_rec.jpg'),
(8, 'Popular', 'Minor Basilica', 'Cabildo, 132 Beaterio St, Intramuros, Manila, 1002 Metro Manila, Philippines', 'The Minor Basilica and Metropolitan Cathedral of the Immaculate Conception, commonly known as the Manila Cathedral, is a Roman Catholic basilica and the cathedral of the Archdiocese of Manila. It is dedicated to the Blessed Virgin Mary of the Immaculate Conception, the principal patroness of the Philippines.', '8:00 AM - 4:30 PM', 0.00, 'minor_basilica/pic1_main.jpg', 'minor_basilica/pic2_mini_one.jpg', 'minor_basilica/pic3_mini_two.jpg', 'minor_basilica/pic4_rec.jpg'),
(9, 'Popular', 'Museo de Intramuros', 'Museo de Intramuros, Arzobispo cor. Anda Sts., Intramuros, Manila', 'The Museo de Intramuros comprises two important reconstructions: the San Ignacio Church and the Mission House of the Society of Jesus. As the name denotes, the complex now houses the vast ecclesiastical collection of the Intramuros Administration.  \r\n\r\nFirst built in 1878 by the Society of Jesus and completed in 1889, the San Ignacio Church, according to contemporaries, was said to be one of the most beautiful in old Manila.', '9:00 AM - 5:00 PM', 75.00, 'museo_de_intramuros/pic1_main.jpg', 'museo_de_intramuros/pic2_mini_one.jpg', 'museo_de_intramuros/pic3_mini_two.jpg', 'museo_de_intramuros/pic4_rec.jpg'),
(10, 'Recommeded', 'Palacio del Gobernador', 'Gen. Luna St. cor. Andres Soriano Ave., Intramuros, Manila, 1002 Metro Manila, Philippines', 'The Palacio del Gobernador was a two-storey building, with a rusticated ground floor, second-floor piano nobile, and attic topped by a tiled hip roof. It was rebuilt in 1733 and 1745. The building underwent a comprehensive renovations in the European style in 1845, although its back retained a typical bahay na bato style, with the second floor covered by capiz shells windows.', '8:00 AM - 5:00 PM', 0.00, 'palacio_del_gobernador/pic1_main.jpg', 'palacio_del_gobernador/pic2_mini_one.jpg', 'palacio_del_gobernador/pic3_mini_two.jpg', 'palacio_del_gobernador/pic4_rec.jpg'),
(11, 'Recommended', 'Puerta del Parian', '465 Muralla St, Intramuros, Manila, 1000 Metro Manila', 'Puerta Del Parian is among the eight gates that serve as an entrance to the Walled City of Intramuros. The gates were called Puerta, which is the original Spanish word for \"gate.\" Hence, the name Puerta del Parian means \"Gate of the Market,\" signifying its crucial role in Philippine history. From the Spanish colonial times to the British occupation is 1762-1764, as this was the place where many enterprising Chinese traded goods. ', '24/7 Open', 0.00, 'puerta_del_parian/pic1_main.jpg', 'puerta_del_parian/pic2_mini_one.jpg', 'puerta_del_parian/pic3_mini_two.jpg', 'puerta_del_parian/pic4_rec.jpg'),
(12, 'Recommended', 'Puerta Real Gardens', 'Gen. Luna St. cor. Muralla St., Intramuros, Manila, 1002 Metro Manila, Philippines', 'The Puerta Real was once used exclusively by governor generals for special occasions and served as the southern access between Intramuros to Ermita. Severely damaged during the 1945 Battle of Manila, the gate was restored in 1966, with the ravelin later being transformed into a garden venue in the 1980s for open-air activities. Visitors may do picnics, jog, stroll with their pets (provided pets are wearing diapers), and attend cultural events in this site.', '8:00 AM - 6:00 PM', 0.00, 'puerta_real_gardens/pic1_main.jpg', 'puerta_real_gardens/pic2_mini_one.jpg', 'puerta_real_gardens/pic3_mini_two.jpg', 'puerta_real_gardens/pic4_rec.jpg'),
(13, 'Recommended', 'Rizal Shrine', 'F. Mercado St. corner Jose P. Rizal St., Brgy. 5, Poblacion, Calamba, Laguna, Philippines', 'Located inside Fort Santiago in Intramuros, Museo ni Rizal is a museum and shrine that showcases Jose Rizal’s importance in the history of the Philippines. The building used to be a barracks built in the 1500s, but it was destroyed during the Battle of Manila in 1945. The barracks wing where Jose Rizal’s prison cell was located was reconstructed in 1953 and now serves as the museum. ', '9:00 AM - 6:00 PM', 75.00, 'rizal_shrine/pic1_main.jpg', 'rizal_shrine/pic2_mini_one.jpg', 'rizal_shrine/pic3_mini_two.jpg', 'rizal_shrine/pic4_rec.jpg'),
(14, 'Recommended', 'Rizal\'s Bagumbayan Light and Sound Museum', 'Victoria St. cor. Sta. Lucia St., Intramuros, Manila, 1002 Metro Manila, Philippines', 'Intramuros and Rizal’s Bagumbayan Light and Sound Museum is a must-visit in Manila, offering a deep dive into the history of the Philippines. The walled city houses significant sites such as Fort Santiago, Manila Cathedral, San Agustin Church, Casa Manila museum, and the Rizal Bagumbayan Light and Sound Museum.\r\n', '8:00 AM - 5:00 PM', 150.00, 'rizal_bagumbayan/pic1_main.jpg', 'rizal_bagumbayan/pic2_mini_one.jpg', 'rizal_bagumbayan/pic3_mini_two.jpg', 'rizal_bagumbayan/pic4_rec.jpg'),
(15, 'Popular', 'Silahis Art and Artifacts Inc.', '744 General Luna St, Intramuros, Manila, 1002 Metro Manila', 'Established in 1966, Silahis Arts and Artifacts is one building in which you can visit the entire archipelago and learn something of Philippine life and history.', '10:00 AM - 5:00 PM', 0.00, 'silahis_art/pic1_main.jpg', 'silahis_art/pic2_mini_one.jpg', 'silahis_art/pic3_mini_two.jpg', 'silahis_art/pic4_rec.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `booking_request_id` int NOT NULL,
  `unique_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tourist_id` int DEFAULT NULL,
  `booking_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `booking_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `booking_date` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adults_and_seniors` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `infants` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `contact_info_id` int DEFAULT NULL,
  `vehicle_id` int DEFAULT NULL,
  `number_of_vehicle` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_information`
--

CREATE TABLE `contact_information` (
  `contact_info_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int NOT NULL,
  `package_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `package_name`, `description`, `price`, `image_file`) VALUES
(1, 'Hero\'s Trail', 'Walk the paths our national hero once roamed. This package is a focused tour of the history of Jose Rizal and it\'s main historical sites. Walk through Fort Santiago and Rizal Shrine, and immerse yourself in the Rizal Bagumbayan Light and Sound Museum while using Bambike as a fun way of exploration.', 250.00, 'packages/hero_s_trail.jpg'),
(2, 'Cultural Combo', 'A showcase of the cultural heritage and colonial lifestyle of the walled city. This combo focuses on Intramuros\' social, cultural, and culinary history. The itinerary features Casa Manila, an experience at Barbara\'s, and concluding at Silahis to appreciate authentic hand-crafted souvenirs.  ', 100.00, 'packages/cultural_combo.jpg'),
(3, 'Walled City Grand Tour', 'The definitive Intramuros journey, covering the most iconic locations and highlights. It provides a comprehensive tour, being the best choice for a full-scale overview. It shows the architectural grandeur of the Minor Basilica, and San Agustin, while experiencing history in the form of Fort Santiago and Casa Manila.', 500.00, 'packages/walled_city_grand_tour.jpg'),
(4, 'Bastions and Walls', 'Find out about the engineering and structural ingenuity that defines the walled city. This itinerary guides you through the district\'s perimeter, showing key picturesque defensive points such as Puerta del Parian, Puerta Real, and Fort Santiago. It ends in Palacio del Gobernador, where you learn about the political context and administrative power that the fortifications were made to protect.', 100.00, 'packages/bastions_and_walls.jpg'),
(5, 'Sacred Route', 'A tour focused on the spiritual and architectural heart of the city. It highlights the Baroque architecture and religious legacy that is central to the identity of Intramuros. Visit and appreciate the Minor Basilica and San Agustin Church, which are two of the religious landmarks that have stood the test of time.', 200.00, 'packages/sacred_route.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `package_itinerary`
--

CREATE TABLE `package_itinerary` (
  `package_id` int DEFAULT NULL,
  `attraction_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_itinerary`
--

INSERT INTO `package_itinerary` (`package_id`, `attraction_id`) VALUES
(1, 1),
(1, 6),
(1, 13),
(1, 14),
(2, 2),
(2, 7),
(2, 15),
(3, 1),
(3, 2),
(3, 3),
(3, 8),
(4, 1),
(4, 10),
(4, 11),
(4, 12),
(5, 3),
(5, 4),
(5, 8);

-- --------------------------------------------------------

--
-- Table structure for table `request_attractions`
--

CREATE TABLE `request_attractions` (
  `booking_request_id` int NOT NULL,
  `attraction_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_packages`
--

CREATE TABLE `request_packages` (
  `booking_request_id` int NOT NULL,
  `package_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourists`
--

CREATE TABLE `tourists` (
  `tourist_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_guides`
--

CREATE TABLE `tour_guides` (
  `guide_id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `current_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Online',
  `last_active_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_dispatch_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `became_available_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `current_tourist_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_guides`
--

INSERT INTO `tour_guides` (`guide_id`, `first_name`, `last_name`, `email`, `password_hash`, `current_status`, `last_active_at`, `last_dispatch_time`, `became_available_at`, `current_tourist_id`) VALUES
(1, 'Ernesto John', 'Arevalo', 'arevaloernestojohn@gmail.com', '$2y$10$Lx7O9Qn7qmPujh7pvoXnXenruZr1uiLUHCLSUT/pXxL0sH1u0Z9dS', 'Online', '2026-05-04 02:46:19', '2026-05-04 02:46:19', '2026-05-04 02:46:19', NULL),
(2, 'Kenneth Lloyd', 'Cadayona', 'keyelmc@gmail.com', '$2y$10$hQ3MBvTezF51UTeZZvrNZOz39H27npMsXA51YaX5tatkvE/xkG0.a', 'Online', '2026-05-04 02:46:50', '2026-05-04 02:46:50', '2026-05-04 02:46:50', NULL),
(3, 'David Lloyd', 'Contreras', 'davidlloydcontreras@gmail.com', '$2y$10$fqCu1YVKitF2pb.nkEky6e29Gxfiql26TNwPYUxcj0oNxpXZ6F.AG', 'Online', '2026-05-04 02:47:37', '2026-05-04 02:47:37', '2026-05-04 02:47:37', NULL),
(4, 'Mark Ian', 'Culcul', 'ianmark376@gmail.com', '$2y$10$Y0kgBcFUobQNbbDRDQqzx.o3X2p9SRjlwK3rFCgQ0NrEo7mjoXcwy', 'Online', '2026-05-04 02:48:11', '2026-05-04 02:48:11', '2026-05-04 02:48:11', NULL),
(5, 'Lence Jericho', 'Jalimao', 'lencejeri95@gmail.com', '$2y$10$67wA6xUmHFC2YvEf2ZltbuLkisIVM3W8vA1.GRwUOWkrwzeZjJPjG', 'Online', '2026-05-04 02:49:01', '2026-05-04 02:49:01', '2026-05-04 02:49:01', NULL),
(6, 'Lee Shailan', 'Tuangco', 'leeshailan@gmail.com', '$2y$10$qeft311ZiM715IumWjlbmua0KQ1jzAr4NCBoQW3k/57TUVNm6YvLS', 'Online', '2026-05-04 02:49:35', '2026-05-04 02:49:35', '2026-05-04 02:49:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_events`
--

CREATE TABLE `upcoming_events` (
  `event_id` int NOT NULL,
  `event_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `event_time` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upcoming_events`
--

INSERT INTO `upcoming_events` (`event_id`, `event_name`, `event_date`, `event_time`, `location`, `image_file`) VALUES
(1, 'Summer Festival', 'April 25, 2026', '8:00 AM - 11:00 PM', 'Plaza Moriones, Fort Santiago', 'upcoming_events/summer_festival.png'),
(2, 'Pasig River Esplanade (Bazaar)', 'January 1, 2026 - December 31, 2026', '4:00 PM - 12:00 MN', 'Pasig River Esplanade', 'upcoming_events/esplanade_bazaar.jpg'),
(3, 'TamRun', 'May 2, 2026', '8:00 AM - 12:00 NN', 'Fort Santiago', 'upcoming_events/TamRun.jpg'),
(4, 'Philippine Eatsperience', 'January 1, 2026 - December 31, 2026', '7:00 AM - 5:00 PM', 'Baluarte Plano Luneta de Santa Isabel', 'upcoming_events/Eatsperience.jpg'),
(5, 'Centro Entablado (Ina Choral Fest)', 'May 9, 2026', '4:00 PM', 'Centro De Turismo Intramuros', 'upcoming_events/ina_coral_festival.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int NOT NULL,
  `vehicle_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `passenger_capacity` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_type`, `passenger_capacity`, `price`, `image_file`) VALUES
(1, 'TukTuk', '3-4', 1000.00, 'TukTuk.jpg'),
(2, 'Kalesa', '2-4', 1500.00, 'Kalesa.jpg'),
(3, 'Tranvia', '12-20', 2500.00, 'Tranvia.jpg');

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
  ADD UNIQUE KEY `unique_id` (`unique_id`),
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
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_itinerary`
--
ALTER TABLE `package_itinerary`
  ADD KEY `itinerary_id` (`package_id`,`attraction_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `request_attractions`
--
ALTER TABLE `request_attractions`
  ADD PRIMARY KEY (`booking_request_id`,`attraction_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `request_packages`
--
ALTER TABLE `request_packages`
  ADD PRIMARY KEY (`booking_request_id`,`package_id`),
  ADD KEY `package_id` (`package_id`);

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
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `attraction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `booking_request_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `contact_info_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tourists`
--
ALTER TABLE `tourists`
  MODIFY `tourist_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_guides`
--
ALTER TABLE `tour_guides`
  MODIFY `guide_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `upcoming_events`
--
ALTER TABLE `upcoming_events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `package_itinerary`
--
ALTER TABLE `package_itinerary`
  ADD CONSTRAINT `package_itinerary_ibfk_1` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`attraction_id`),
  ADD CONSTRAINT `package_itinerary_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`);

--
-- Constraints for table `request_attractions`
--
ALTER TABLE `request_attractions`
  ADD CONSTRAINT `request_attractions_ibfk_1` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_history` (`booking_request_id`),
  ADD CONSTRAINT `request_attractions_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`attraction_id`);

--
-- Constraints for table `request_packages`
--
ALTER TABLE `request_packages`
  ADD CONSTRAINT `request_packages_ibfk_1` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_history` (`booking_request_id`),
  ADD CONSTRAINT `request_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`);

--
-- Constraints for table `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD CONSTRAINT `tour_guides_ibfk_1` FOREIGN KEY (`current_tourist_id`) REFERENCES `tourists` (`tourist_id`),
  ADD CONSTRAINT `tour_guides_ibfk_2` FOREIGN KEY (`current_tourist_id`) REFERENCES `booking_history` (`tourist_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
