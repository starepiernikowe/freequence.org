-- Create 'users' first (no dependencies)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `registration_date` DATETIME NOT NULL
) ENGINE=InnoDB;

-- Create 'templates' next (no dependencies)
CREATE TABLE IF NOT EXISTS `templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `description` TEXT,
    `is_system` TINYINT
) ENGINE=InnoDB;

-- Create 'callsigns' next (depends on 'users')
CREATE TABLE IF NOT EXISTS `callsigns` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `callsign_id` VARCHAR(255) UNIQUE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Create 'entry_groups' next (depends on 'users')
CREATE TABLE IF NOT EXISTS `entry_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `group_name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Create 'entry_subgroups' next (depends on 'entry_groups')
CREATE TABLE IF NOT EXISTS `entry_subgroups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT,
    `subgroup_name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    FOREIGN KEY (`group_id`) REFERENCES `entry_groups`(`id`)
) ENGINE=InnoDB;

-- Create 'entries' next (depends on 'users' and 'templates')
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
    `var_description` VARCHAR(255) NULL,
    `status` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`)
) ENGINE=InnoDB;

-- Create 'parameters' (depends on 'templates').  Must be *before* entry_details
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

-- Finally, create 'entry_details' (depends on 'entries', 'entry_subgroups', and 'parameters')
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