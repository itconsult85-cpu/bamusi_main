ALTER TABLE `page_sections`
  ADD COLUMN `vision` text NULL AFTER `content`,
  ADD COLUMN `vision_en` text NULL AFTER `vision`,
  ADD COLUMN `mission` text NULL AFTER `vision_en`,
  ADD COLUMN `mission_en` text NULL AFTER `mission`;

INSERT INTO `page_sections` (`section_key`, `section_name`, `kicker`, `kicker_en`, `title`, `title_en`, `vision`, `mission`, `published`, `sort_order`, `created_at`, `updated_at`)
SELECT 'visi', 'Visi dan Misi', 'VISI DAN MISI', 'VISION AND MISSION',
       'Menjadi rumah kebangsaan Muslim Indonesia yang progresif.',
       'Becoming the national home of progressive Indonesian Muslims.',
       (SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = 'about_vision' LIMIT 1),
       (SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = 'about_mission' LIMIT 1),
       1, 30, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `page_sections` WHERE `section_key` = 'visi');

UPDATE `page_sections` ps
JOIN `site_settings` vision_setting ON vision_setting.`setting_key` = 'about_vision'
SET ps.`vision` = CASE WHEN COALESCE(TRIM(ps.`vision`), '') = '' THEN vision_setting.`setting_value` ELSE ps.`vision` END
WHERE ps.`section_key` = 'visi';

UPDATE `page_sections` ps
JOIN `site_settings` mission_setting ON mission_setting.`setting_key` = 'about_mission'
SET ps.`mission` = CASE WHEN COALESCE(TRIM(ps.`mission`), '') = '' THEN mission_setting.`setting_value` ELSE ps.`mission` END
WHERE ps.`section_key` = 'visi';
