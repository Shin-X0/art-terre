-- ============================================================
-- ART TERRE — migration: admin replies to contact messages
-- Adds reply / replied_at / replied_by to contact_messages so the
-- admin can receive a message and reply, and the signed-in sender
-- can read the answer in pages/messages.php ("My messages").
-- Safe to run more than once (procedure guards for MariaDB/MySQL).
-- Run in phpMyAdmin on art_terre, or:
-- C:\xampp12\mysql\bin\mysql.exe -u root < db\migrate_message_replies.sql
-- ============================================================

USE art_terre;

DROP PROCEDURE IF EXISTS at_add_message_replies;

DELIMITER $$

CREATE PROCEDURE at_add_message_replies()
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'reply'
  ) THEN
    ALTER TABLE contact_messages ADD COLUMN reply TEXT NULL AFTER message;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'replied_at'
  ) THEN
    ALTER TABLE contact_messages ADD COLUMN replied_at TIMESTAMP NULL AFTER reply;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'art_terre' AND TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'replied_by'
  ) THEN
    ALTER TABLE contact_messages ADD COLUMN replied_by INT UNSIGNED NULL AFTER replied_at;
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'art_terre' AND CONSTRAINT_NAME = 'fk_messages_replied_by'
  ) THEN
    ALTER TABLE contact_messages
      ADD CONSTRAINT fk_messages_replied_by
      FOREIGN KEY (replied_by) REFERENCES users (id) ON DELETE SET NULL;
  END IF;
END$$

DELIMITER ;

CALL at_add_message_replies();
DROP PROCEDURE IF EXISTS at_add_message_replies;
