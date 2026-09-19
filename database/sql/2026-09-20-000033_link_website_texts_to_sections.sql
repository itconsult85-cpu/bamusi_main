-- BAMUSI: Hubungkan teks homepage ke section CMS.
-- Aman dijalankan satu kali pada database yang sudah memiliki tabel website_texts.
-- Migration CodeIgniter yang setara: 2026-09-20-000033_LinkWebsiteTextsToSections.php

ALTER TABLE `website_texts`
    ADD COLUMN `section_key` VARCHAR(80) NULL AFTER `location`;

ALTER TABLE `website_texts`
    ADD INDEX `section_published_sort_order` (`section_key`, `published`, `sort_order`);

UPDATE `website_texts` SET `section_key` = 'hero' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.hero_%' OR `text_key` LIKE 'reference.hero_%');
UPDATE `website_texts` SET `section_key` = 'about' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.about_%' OR `text_key` LIKE 'reference.about_%');
UPDATE `website_texts` SET `section_key` = 'board' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.board_%' OR `text_key` LIKE 'home.member_%');
UPDATE `website_texts` SET `section_key` = 'agenda' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.agenda_%';
UPDATE `website_texts` SET `section_key` = 'gallery' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.gallery_%';
UPDATE `website_texts` SET `section_key` = 'social' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.social_%' OR `text_key` LIKE 'home.instagram_%' OR `text_key` LIKE 'home.tiktok_%' OR `text_key` LIKE 'home.youtube_%');
UPDATE `website_texts` SET `section_key` = 'contact' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.contact_%';
UPDATE `website_texts` SET `section_key` = 'program' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.program_%';
UPDATE `website_texts` SET `section_key` = 'feature' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.feature_%';
UPDATE `website_texts` SET `section_key` = 'writing' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.writing_%';
UPDATE `website_texts` SET `section_key` = 'join' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.join_%' OR `text_key` LIKE 'reference.join_%');
UPDATE `website_texts` SET `section_key` = 'internship' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.internship_%';
UPDATE `website_texts` SET `section_key` = 'program' WHERE `section_key` IS NULL AND `text_key` LIKE 'reference.khidmah_%';
UPDATE `website_texts` SET `section_key` = 'history' WHERE `section_key` IS NULL AND `text_key` LIKE 'home.history_%';
UPDATE `website_texts` SET `section_key` = 'navigation' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.rail_%' OR `text_key` LIKE 'home.bar_%');
UPDATE `website_texts` SET `section_key` = 'visi' WHERE `section_key` IS NULL AND (`text_key` LIKE 'home.vision_%' OR `text_key` LIKE 'home.mission_%');
