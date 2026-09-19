ALTER TABLE `pages`
  ADD COLUMN `meta_title_en` varchar(255) NULL AFTER `meta_title`,
  ADD COLUMN `meta_description_en` text NULL AFTER `meta_description`,
  ADD COLUMN `header_kicker_en` varchar(255) NULL AFTER `header_kicker`,
  ADD COLUMN `header_title_en` varchar(255) NULL AFTER `header_title`,
  ADD COLUMN `header_intro_en` text NULL AFTER `header_intro`;
