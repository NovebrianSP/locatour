-- ================================================
-- DATABASE LOCATOUR - COMPLETE SETUP
-- ================================================

-- Drop database jika sudah ada (opsional - uncomment jika perlu)
-- DROP DATABASE IF EXISTS locatour;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS locatour 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE locatour;

-- ================================================
-- 1. TABEL USERS
-- ================================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id INT PRIMARY KEY,
  location VARCHAR(255),
  age INT,
  location_clean VARCHAR(255),
  location_norm VARCHAR(255),
  location_tokens TEXT,
  age_normalized DECIMAL(15,13),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_location (location(100)),
  INDEX idx_age (age)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- 2. TABEL CATEGORY SUMMARY
-- ================================================
DROP TABLE IF EXISTS category_summary;

CREATE TABLE category_summary (
  category_id INT PRIMARY KEY,
  category_standardized VARCHAR(100) NOT NULL UNIQUE,
  jumlah INT DEFAULT 0,
  rata_rata_rating DECIMAL(3,2) DEFAULT 0,
  rata_rata_price DECIMAL(10,2) DEFAULT 0,
  total_vote INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_category (category_standardized)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- 3. TABEL TOURISM PLACES
-- ================================================
DROP TABLE IF EXISTS tourism_places;

CREATE TABLE tourism_places (
  place_id VARCHAR(50) PRIMARY KEY,
  place_name VARCHAR(255) NOT NULL,
  description TEXT,
  category VARCHAR(100),
  city VARCHAR(100),
  price INT DEFAULT 0,
  rating DECIMAL(3,2) DEFAULT 0,
  time_minutes INT DEFAULT 0,
  latitude DECIMAL(10,7),
  longitude DECIMAL(10,7),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_category (category),
  INDEX idx_rating (rating),
  INDEX idx_city (city(50)),
  INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- 4. TABEL TOURISM RATINGS
-- ================================================
DROP TABLE IF EXISTS tourism_ratings;

CREATE TABLE tourism_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  place_id VARCHAR(50) NOT NULL,
  place_ratings INT NOT NULL CHECK (place_ratings BETWEEN 1 AND 5),
  place_ratings_normalized DECIMAL(3,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (place_id) REFERENCES tourism_places(place_id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_place (place_id),
  INDEX idx_rating (place_ratings)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- 5. TABEL WISATA MERGED (LENGKAP)
-- ================================================
DROP TABLE IF EXISTS wisata_merged;

CREATE TABLE wisata_merged (
  id INT AUTO_INCREMENT PRIMARY KEY,
  source VARCHAR(50),
  source_id INT,
  name VARCHAR(255) NOT NULL,
  name_clean VARCHAR(255),
  name_norm VARCHAR(255),
  name_tokens TEXT,
  description TEXT,
  description_clean TEXT,
  description_norm TEXT,
  description_tokens TEXT,
  category VARCHAR(100),
  category_clean VARCHAR(100),
  category_norm VARCHAR(100),
  category_tokens TEXT,
  price DECIMAL(10,2) DEFAULT 0,
  rating DECIMAL(3,2) DEFAULT 0,
  time_minutes INT,
  latitude DECIMAL(10,7),
  longitude DECIMAL(10,7),
  vote_count INT DEFAULT 0,
  htm_weekday DECIMAL(10,2),
  htm_weekend DECIMAL(10,2),
  rating_normalized DECIMAL(3,2),
  price_normalized DECIMAL(15,13),
  category_standardized VARCHAR(100),
  category_id INT,
  unified_price DECIMAL(10,2),
  merged_id INT,
  indoor TINYINT(1) DEFAULT 0,
  outdoor TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_name (name(100)),
  INDEX idx_category (category_standardized),
  INDEX idx_rating (rating),
  INDEX idx_merged_id (merged_id),
  INDEX idx_price (price),
  FOREIGN KEY (category_id) REFERENCES category_summary(category_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- VERIFIKASI STRUKTUR TABEL
-- ================================================

SHOW TABLES;

-- Cek struktur setiap tabel
DESCRIBE users;
DESCRIBE category_summary;
DESCRIBE tourism_places;
DESCRIBE tourism_ratings;
DESCRIBE wisata_merged;

-- ================================================
-- SETELAH IMPORT DATA, JALANKAN QUERY INI
-- ================================================

/*
-- Cek jumlah record di setiap tabel
SELECT 'users' as tabel, COUNT(*) as jumlah FROM users
UNION ALL
SELECT 'category_summary', COUNT(*) FROM category_summary
UNION ALL
SELECT 'tourism_places', COUNT(*) FROM tourism_places
UNION ALL
SELECT 'tourism_ratings', COUNT(*) FROM tourism_ratings
UNION ALL
SELECT 'wisata_merged', COUNT(*) FROM wisata_merged;

-- Cek sample data
SELECT 'USERS:' as info;
SELECT * FROM users LIMIT 5;

SELECT 'CATEGORIES:' as info;
SELECT * FROM category_summary;

SELECT 'TOURISM PLACES:' as info;
SELECT * FROM tourism_places LIMIT 5;

SELECT 'TOURISM RATINGS:' as info;
SELECT * FROM tourism_ratings LIMIT 5;

SELECT 'WISATA MERGED:' as info;
SELECT * FROM wisata_merged LIMIT 5;
*/
