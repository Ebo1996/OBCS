-- Run this in phpMyAdmin on the birth_certificate_database

-- 1. Feedback table (used on the homepage for community testimonials)
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Fix: applications_support was referenced in ALTER TABLE but created as application_support
--    Create the correctly named table if it doesn't exist
CREATE TABLE IF NOT EXISTS applications_support (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('not resolved', 'resolved') DEFAULT 'not resolved',
    admin_reply TEXT,
    reply_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
