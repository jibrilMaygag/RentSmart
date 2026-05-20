-- ============================================================
-- RentSmart Database Schema (PHP 8+ / MySQL 5.7+)
-- ============================================================

CREATE DATABASE IF NOT EXISTS rentsmart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rentsmart;

-- USERS
CREATE TABLE IF NOT EXISTS users (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    full_name      VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    role           ENUM('renter', 'landlord', 'admin') NOT NULL DEFAULT 'renter',
    phone          VARCHAR(20),
    avatar         VARCHAR(255),
    bio            TEXT,
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    remember_token VARCHAR(64),
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROPERTIES
CREATE TABLE IF NOT EXISTS properties (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    landlord_id   INT NOT NULL,
    title         VARCHAR(200) NOT NULL,
    description   TEXT NOT NULL,
    price         DECIMAL(12,2) NOT NULL,
    listing_type  ENUM('rent', 'sale') NOT NULL DEFAULT 'rent',
    property_type ENUM('apartment', 'house', 'villa', 'studio', 'office', 'land') NOT NULL DEFAULT 'apartment',
    status        ENUM('available', 'rented', 'sold', 'pending') NOT NULL DEFAULT 'available',
    bedrooms      TINYINT UNSIGNED NOT NULL DEFAULT 1,
    bathrooms     TINYINT UNSIGNED NOT NULL DEFAULT 1,
    area_sqm      DECIMAL(8,2),
    address       VARCHAR(255) NOT NULL,
    city          VARCHAR(100) NOT NULL,
    sub_city      VARCHAR(100),
    latitude      DECIMAL(10,8),
    longitude     DECIMAL(11,8),
    is_featured   TINYINT(1) NOT NULL DEFAULT 0,
    views         INT NOT NULL DEFAULT 0,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (landlord_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_city (city),
    INDEX idx_type (property_type),
    INDEX idx_listing_type (listing_type),
    INDEX idx_price (price),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_landlord (landlord_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROPERTY IMAGES
CREATE TABLE IF NOT EXISTS property_images (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_path  VARCHAR(255) NOT NULL,
    is_primary  TINYINT(1) NOT NULL DEFAULT 0,
    sort_order  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_property (property_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AMENITIES
CREATE TABLE IF NOT EXISTS amenities (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'fa-check'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROPERTY AMENITIES (M:M)
CREATE TABLE IF NOT EXISTS property_amenities (
    property_id INT NOT NULL,
    amenity_id  INT NOT NULL,
    PRIMARY KEY (property_id, amenity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FAVORITES
CREATE TABLE IF NOT EXISTS favorites (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    property_id INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, property_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_property (property_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- MESSAGES
CREATE TABLE IF NOT EXISTS messages (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    sender_id    INT NOT NULL,
    recipient_id INT NOT NULL,
    subject      VARCHAR(200),
    body         TEXT NOT NULL,
    is_read      TINYINT(1) NOT NULL DEFAULT 0,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_recipient (recipient_id),
    INDEX idx_sender (sender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CONTACTS
CREATE TABLE IF NOT EXISTS contacts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    phone      VARCHAR(20),
    message    TEXT NOT NULL,
    is_read    TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
