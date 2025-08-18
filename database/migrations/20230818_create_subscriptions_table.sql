-- Create subscriptions table if it doesn't exist
CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) DEFAULT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `token` VARCHAR(100) NOT NULL,
    `source` VARCHAR(50) DEFAULT 'website',
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT,
    `meta` JSON,
    UNIQUE KEY `unique_email` (`email`),
    KEY `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
