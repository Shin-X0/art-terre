-- ============================================================
-- ART TERRE — migration: date of birth + gender + role (users)
--              and the artworks table (artist uploads).
-- For EXISTING installs. Run in phpMyAdmin on art_terre, or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\migrate_users_artworks.sql
-- Safe to run more than once (IF NOT EXISTS guards).
-- ============================================================

USE art_terre;

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS dob   DATE NULL AFTER password_hash,
  ADD COLUMN IF NOT EXISTS gender VARCHAR(20) NULL AFTER dob,
  ADD COLUMN IF NOT EXISTS role   ENUM('collector','artist') NOT NULL DEFAULT 'collector' AFTER gender;

CREATE TABLE IF NOT EXISTS artworks (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  artist_id  INT UNSIGNED NOT NULL,
  title      VARCHAR(150) NOT NULL,
  category   VARCHAR(30) NOT NULL DEFAULT 'painting',
  story      TEXT NULL,
  price      DECIMAL(10,2) NOT NULL,
  image_path VARCHAR(255) NULL,
  status     ENUM('available','sold') NOT NULL DEFAULT 'available',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_artworks_artist (artist_id),
  CONSTRAINT fk_artworks_artist
    FOREIGN KEY (artist_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;