ALTER TABLE `tourists` 
ADD COLUMN `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `customer_id`,
ADD COLUMN `queue_number` VARCHAR(50) DEFAULT NULL AFTER `is_verified`,
ADD COLUMN `service_type` VARCHAR(50) DEFAULT 'Standard Kalesa' AFTER `queue_number`,
ADD COLUMN `status` ENUM('waiting', 'active', 'completed') DEFAULT 'waiting' AFTER `service_type`,
ADD COLUMN `called_at` DATETIME DEFAULT NULL AFTER `status`,
ADD COLUMN `completed_at` DATETIME DEFAULT NULL AFTER `called_at`,
ADD COLUMN `guide_id` INT(11) DEFAULT NULL AFTER `completed_at`,
ADD COLUMN `adult_count` INT(11) DEFAULT 0 AFTER `guide_id`,
ADD COLUMN `children_count` INT(11) DEFAULT 0 AFTER `adult_count`,
ADD COLUMN `infant_count` INT(11) DEFAULT 0 AFTER `children_count`,
ADD COLUMN `package_id` INT(11) DEFAULT NULL AFTER `infant_count`,
ADD COLUMN `vehicle_count` INT(11) DEFAULT 0 AFTER `package_id`,
ADD COLUMN `destinations` TEXT DEFAULT NULL AFTER `vehicle_count`;

ALTER TABLE `tour_guides` 
ADD COLUMN `became_available_at` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `last_dispatch_time`,
ADD COLUMN `current_tourist_id` INT(11) DEFAULT NULL AFTER `became_available_at`;

ALTER TABLE `tour_guides`
ADD CONSTRAINT `fk_guide_active_tourist` 
FOREIGN KEY (`current_tourist_id`) REFERENCES `tourists` (`customer_id`) ON DELETE SET NULL;

ALTER TABLE `tourists`
ADD CONSTRAINT `fk_tourist_assigned_guide` 
FOREIGN KEY (`guide_id`) REFERENCES `tour_guides` (`guide_id`) ON DELETE SET NULL;