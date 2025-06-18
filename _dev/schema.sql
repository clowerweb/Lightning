-- Drop old tables if they exist for a clean slate
DROP TABLE IF EXISTS `entries`;
DROP TABLE IF EXISTS `entry_types`;
DROP TABLE IF EXISTS `users`;

-- Defines the different types of content (e.g., "Page", "Blog Post")
CREATE TABLE `entry_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL, -- e.g., "Page", "Blog Post"
  `handle` VARCHAR(255) NOT NULL, -- e.g., "page", "blogPost"
  `field_config` JSON NOT NULL, -- Defines the fields and their UI for the admin panel
  PRIMARY KEY (`id`),
  UNIQUE KEY `handle` (`handle`),
  CHECK (JSON_VALID(`field_config`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A robust users table
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A generic table for all content entries
CREATE TABLE `entries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `entry_type_id` INT(11) NOT NULL,
  `author_id` INT(11) DEFAULT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'enabled', -- e.g., enabled, disabled, archived
  `content` JSON NOT NULL,
  `title_generated` VARCHAR(255) AS (JSON_UNQUOTE(JSON_EXTRACT(content, '$.title'))) STORED,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `entry_type_id` (`entry_type_id`),
  KEY `author_id` (`author_id`),
  KEY `idx_title` (`title_generated`),
  CHECK (JSON_VALID(`content`)),
  CONSTRAINT `fk_entry_type` FOREIGN KEY (`entry_type_id`) REFERENCES `entry_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;