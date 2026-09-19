ALTER TABLE `page_sections`
  ADD COLUMN `button_position` varchar(10) NOT NULL DEFAULT 'center' AFTER `button_url`,
  ADD COLUMN `button_location` varchar(10) NOT NULL DEFAULT 'bottom' AFTER `button_position`,
  ADD COLUMN `cards_visible` tinyint(1) NOT NULL DEFAULT 1 AFTER `button_location`,
  ADD COLUMN `cards_limit` tinyint(2) NOT NULL DEFAULT 5 AFTER `cards_visible`,
  ADD COLUMN `cards_columns` tinyint(1) NOT NULL DEFAULT 5 AFTER `cards_limit`;
