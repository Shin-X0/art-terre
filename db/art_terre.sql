-- ============================================================
-- ART TERRE — database schema
-- Import via phpMyAdmin (http://localhost/phpmyadmin) or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\art_terre.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS art_terre
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE art_terre;

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(100) NOT NULL,
  email         VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  dob           DATE NULL,                                        /* date of birth (calendar picker)      */
  gender        VARCHAR(20) NULL,                                 /* female/male/non-binary/other/prefer-not */
  role          ENUM('collector','artist','admin') NOT NULL DEFAULT 'collector',
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

/* Contact-form messages (readable + replyable by admins in admin/messages.php).
   reply/replied_at/replied_by hold the admin's answer, shown back to the
   signed-in sender in pages/messages.php ("My messages"). */
CREATE TABLE IF NOT EXISTS contact_messages (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NULL,                       /* NULL when a guest writes */
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(190) NOT NULL,
  message    TEXT NOT NULL,
  reply      TEXT NULL,                               /* admin's reply */
  replied_at TIMESTAMP NULL,                          /* when the admin replied */
  replied_by INT UNSIGNED NULL,                       /* which admin replied */
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_messages_user (user_id),
  CONSTRAINT fk_messages_user
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
  CONSTRAINT fk_messages_replied_by
    FOREIGN KEY (replied_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

/* Artworks uploaded by artists to sell (see upload-artwork.php) */
CREATE TABLE IF NOT EXISTS artworks (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  artist_id  INT UNSIGNED NOT NULL,
  title      VARCHAR(150) NOT NULL,
  category   VARCHAR(30) NOT NULL DEFAULT 'painting',           /* painting | photography */
  story      TEXT NULL,
  price      DECIMAL(10,2) NOT NULL,
  image_path VARCHAR(255) NULL,                                 /* e.g. images/uploads/artworks/xx.jpg */
  status     ENUM('available','sold') NOT NULL DEFAULT 'available',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_artworks_artist (artist_id),
  CONSTRAINT fk_artworks_artist
    FOREIGN KEY (artist_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

/* Placed orders (checkout.php) — payment: card or cash on delivery */
CREATE TABLE IF NOT EXISTS orders (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_no       VARCHAR(20) NOT NULL,
  user_id        INT UNSIGNED NULL,                              /* NULL when guest checkout */
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

/* Line items for each order (title/artist snapshot at purchase time).
   artwork_id/artist_id pin each line to the real artwork + seller, so ONLY
   that seller (or an admin) can update the order's status — the buyer is
   view-only. NULL for legacy rows / catalogue placeholder cards. */
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

-- Demo account: demo@artterre.com / password123
INSERT INTO users (name, email, password_hash, dob, gender, role)
VALUES ('Demo Artist', 'demo@artterre.com', '$2y$12$6Jbi1qtDGDi5l8..i6TPsO71.TbHssQ6IcMkwPP3pkzlM8Qg1Gfey',
        '1990-06-15', 'male', 'artist')
ON DUPLICATE KEY UPDATE
  name          = VALUES(name),
  password_hash = VALUES(password_hash);

-- Site admin account: admin@artterre.com / admin12345 (change it!)
INSERT INTO users (name, email, password_hash, dob, gender, role)
VALUES ('Site Admin', 'admin@artterre.com',
        '$2y$12$gbJo.w8ao2iF2X.e865DsemXvjmsQMNePXnCOJ/qR1dsWj2vWRZzi',
        '1990-01-01', 'prefer-not', 'admin')
ON DUPLICATE KEY UPDATE
  role          = 'admin',
  password_hash = VALUES(password_hash);

-- A couple of demo artworks so the page is not empty after a fresh import
INSERT INTO artworks (artist_id, title, category, story, price, image_path)
SELECT u.id, 'Morning Dusk', 'painting',
       'Uploaded from the demo artist account — replace it with your own work.',
       3.50, NULL
FROM users u
WHERE u.email = 'demo@artterre.com'
  AND NOT EXISTS (SELECT 1 FROM artworks a WHERE a.title = 'Morning Dusk');

-- Updating an existing install? Run db/migrate_users_artworks.sql instead
-- of re-importing this whole file (it keeps your current users).