-- ============================================================
-- ART TERRE — migration: orders + order_items tables
-- For EXISTING installs. Run in phpMyAdmin on art_terre, or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\migrate_orders.sql
-- Safe to run more than once (IF NOT EXISTS guards).
-- ============================================================

USE art_terre;

CREATE TABLE IF NOT EXISTS orders (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_no       VARCHAR(20) NOT NULL,
  user_id        INT UNSIGNED NULL,
  name           VARCHAR(100) NOT NULL,
  email          VARCHAR(190) NOT NULL,
  address        VARCHAR(255) NOT NULL,
  city           VARCHAR(100) NULL,
  zip            VARCHAR(20) NULL,
  payment_method ENUM('card','cod') NOT NULL DEFAULT 'cod',
  status         ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  total          DECIMAL(10,2) NOT NULL,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_orders_no (order_no),
  KEY idx_orders_user (user_id),
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id   INT UNSIGNED NOT NULL,
  artwork_id INT UNSIGNED NULL,
  artist_id  INT UNSIGNED NULL,
  title      VARCHAR(150) NOT NULL,
  artist     VARCHAR(100) NULL,
  price      DECIMAL(10,2) NOT NULL,
  qty        INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  KEY idx_order_items_order (order_id),
  KEY idx_order_items_artist (artist_id),
  KEY idx_order_items_artwork (artwork_id),
  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_artwork
    FOREIGN KEY (artwork_id) REFERENCES artworks (id) ON DELETE SET NULL,
  CONSTRAINT fk_order_items_artist
    FOREIGN KEY (artist_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;