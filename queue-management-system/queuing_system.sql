-- 1. Upgrade the Tourists table for the Queue System
ALTER TABLE `tourists`
ADD COLUMN `queue_number` varchar(50) DEFAULT NULL,
ADD COLUMN `service_type` varchar(50) DEFAULT 'Standard Kalesa',
ADD COLUMN `status` varchar(50) DEFAULT 'waiting',
ADD COLUMN `created_at` datetime DEFAULT current_timestamp(),
ADD COLUMN `called_at` datetime DEFAULT NULL,
ADD COLUMN `completed_at` datetime DEFAULT NULL;

-- 2. Upgrade the Tour Guides table for Auto-Dispatch
ALTER TABLE `tour_guides`
ADD COLUMN `current_tourist_id` int(11) DEFAULT NULL,
ADD COLUMN `became_available_at` datetime DEFAULT current_timestamp();

-- 3. Link the Guide to the Tourist safely (Foreign Key)
ALTER TABLE `tour_guides`
ADD CONSTRAINT `fk_current_tourist_queue` 
FOREIGN KEY (`current_tourist_id`) REFERENCES `tourists` (`customer_id`) ON DELETE SET NULL;