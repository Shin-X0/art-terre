-- ============================================================
-- ART TERRE — migration: order-item ownership (seller control)
-- Links each order line to the artwork + seller who owns it, so
-- ONLY the seller (or an admin) can update that order's status
-- while the buyer stays view-only. Sellers are sell-only and
-- cannot place orders themselves (enforced in checkout.php).
-- Safe to run more than once (procedure guards for MariaDB/MySQL).
-- Run in phpMyAdmin on art_terre, or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\migrate_order_ownership.sql
-- ============================================================

USE art_terre;

DROP PROCEDURE IF EXISTS at_add_order_ownership;

DELIMITER $$

CREATE PROCEDURE at_add_order_ownership()
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'artwork_id'
  ) THEN
    ALTER TABLE order_items ADD COLUMN artwork_id INT UNSIGNED NULL AFTER order_id;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'artist_id'
  ) THEN
    ALTER TABLE order_items ADD COLUMN artist_id INT UNSIGNED NULL AFTER artwork_id;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'order_items' AND INDEX_NAME = 'idx_order_items_artist'
  ) THEN
    ALTER TABLE order_items ADD INDEX idx_order_items_artist (artist_id);
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'order_items' AND INDEX_NAME = 'idx_order_items_artwork'
  ) THEN
    ALTER TABLE order_items ADD INDEX idx_order_items_artwork (artwork_id);
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'art_terre' AND CONSTRAINT_NAME = 'fk_order_items_artwork'
  ) THEN
    ALTER TABLE order_items
      ADD CONSTRAINT fk_order_items_artwork
      FOREIGN KEY (artwork_id) REFERENCES artworks (id) ON DELETE SET NULL;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'art_terre' AND CONSTRAINT_NAME = 'fk_order_items_artist'
  ) THEN
    ALTER TABLE order_items
      ADD CONSTRAINT fk_order_items_artist
      FOREIGN KEY (artist_id) REFERENCES users (id) ON DELETE SET NULL;
  END IF;
END$$

DELIMITER ;

CALL at_add_order_ownership();
DROP PROCEDURE IF EXISTS at_add_order_ownership;
