-- Create 'users' table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `registration_date` DATETIME NOT NULL,
    `last_login_date` DATETIME NULL
) ENGINE=InnoDB;

-- Create 'templates' table
CREATE TABLE IF NOT EXISTS `templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `description` TEXT,
    `user_id` INT NULL,
    `is_active` TINYINT DEFAULT 1,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Create 'callsigns' table
CREATE TABLE IF NOT EXISTS `callsigns` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `callsign_id` VARCHAR(255) UNIQUE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Create 'entry_groups' table (Currently unused)
CREATE TABLE IF NOT EXISTS `entry_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `group_name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Create 'entry_subgroups' table (Currently unused)
CREATE TABLE IF NOT EXISTS `entry_subgroups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT,
    `subgroup_name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    FOREIGN KEY (`group_id`) REFERENCES `entry_groups`(`id`)
) ENGINE=InnoDB;

-- Create 'entries' table
CREATE TABLE IF NOT EXISTS `entries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `template_id` INT,
    `entry_name` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME NOT NULL,
    `expiration_date` DATETIME,
    `location_lat` DECIMAL(10, 8) NOT NULL,
    `location_lng` DECIMAL(11, 8) NOT NULL,
    `comment` TEXT,
    `description` VARCHAR(255) NULL,
    `entry_type` ENUM('single', 'multi') NOT NULL,
    `parent_entry_id` INT NULL,
    `is_active` TINYINT DEFAULT 1,
    `status` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`),
    FOREIGN KEY (`parent_entry_id`) REFERENCES `entries`(`id`)
) ENGINE=InnoDB;

-- Create 'parameters' table
CREATE TABLE IF NOT EXISTS `parameters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `data_type` ENUM('text', 'integer', 'float', 'boolean', 'enum'),
    `default_value` TEXT NULL,
    `options` TEXT NULL,
    `template_id` INT,
    `sort_order` INT,
    FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`)
) ENGINE=InnoDB;

-- Create 'entry_details' table
CREATE TABLE IF NOT EXISTS `entry_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entry_id` INT NULL,
    `subgroup_id` INT NULL,
    `parameter_id` INT NULL,
    `custom_parameter_name` VARCHAR(255) NULL,
    `value` TEXT NULL,
    `is_option` TINYINT NULL,
    FOREIGN KEY (`entry_id`) REFERENCES `entries`(`id`),
    FOREIGN KEY (`subgroup_id`) REFERENCES `entry_subgroups`(`id`),
    FOREIGN KEY (`parameter_id`) REFERENCES `parameters`(`id`)
) ENGINE=InnoDB;

-- Insert "system" template
INSERT INTO `templates` (`name`, `description`, `user_id`, `is_active`) VALUES
('System', 'System template', NULL, 1);

-- Insert "system" templates (DMR446, PMR446)
INSERT INTO `templates` (`name`, `description`, `user_id`, `is_active`) VALUES
('DMR446', 'Digital Mobile Radio 446 MHz', NULL, 1),
('PMR446', 'Private Mobile Radio 446 MHz', NULL, 1);

-- Insert parameters for DMR446 (template_id = 2)
INSERT INTO `parameters` (`name`, `data_type`, `default_value`, `options`, `template_id`, `sort_order`) VALUES
('Channel', 'float', '', NULL, 2, 1),
('Talkgroup', 'text', '', NULL, 2, 2),
('Color Code', 'integer', '', NULL, 2, 3),
('Timeslot', 'integer', '', NULL, 2, 4);

-- Insert parameters for PMR446 (template_id = 3)
INSERT INTO `parameters` (`name`, `data_type`, `default_value`, `options`, `template_id`, `sort_order`) VALUES
('Channel', 'integer', '', NULL, 3, 1),
('CTCSS / DCS', 'text', '', NULL, 3, 2);