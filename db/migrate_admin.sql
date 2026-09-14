-- ============================================================
-- ART TERRE — migration: admin role + admin account
--              + contact_messages table (contact form inbox).
-- For EXISTING installs. Run in phpMyAdmin on art_terre, or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\migrate_admin.sql
-- Safe to run more than once (IF NOT EXISTS / ON DUPLICATE KEY).
-- ============================================================

USE art_terre;

ALTER TABLE users
  MODIFY COLUMN role ENUM('collector','artist','admin') NOT NULL DEFAULT 'collector';

CREATE TABLE IF NOT EXISTS contact_messages (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NULL,                       /* NULL when a guest writes */
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(190) NOT NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_messages_user (user_id),
  CONSTRAINT fk_messages_user
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Site admin account: admin@artterre.com / admin12345 (change it!)
INSERT INTO users (name, email, password_hash, dob, gender, role)
VALUES ('Site Admin', 'admin@artterre.com',
        '$2y$12$gbJo.w8ao2iF2X.e865DsemXvjmsQMNePXnCOJ/qR1dsWj2vWRZzi',
        '1990-01-01', 'prefer-not', 'admin')
ON DUPLICATE KEY UPDATE
  role          = 'admin',
  password_hash = VALUES(password_hash);