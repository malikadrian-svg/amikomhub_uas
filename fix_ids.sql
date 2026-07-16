USE `eventtiket_db_24.12.3296`;
SET FOREIGN_KEY_CHECKS=0;

-- Merapikan id categories
UPDATE categories SET id = 3 WHERE id = 9;
UPDATE events SET category_id = 3 WHERE category_id = 9;

UPDATE categories SET id = 4 WHERE id = 10;
UPDATE events SET category_id = 4 WHERE category_id = 10;

UPDATE categories SET id = 5 WHERE id = 11;
UPDATE events SET category_id = 5 WHERE category_id = 11;

ALTER TABLE categories AUTO_INCREMENT = 6;

-- Merapikan id events
UPDATE events SET id = id - 14;
ALTER TABLE events AUTO_INCREMENT = 7;

SET FOREIGN_KEY_CHECKS=1;
