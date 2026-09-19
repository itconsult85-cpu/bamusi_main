-- BAMUSI Homepage Section Builder
-- Alternatif manual untuk migration 000034 dan 000035.
-- Jalankan setelah migration 000033, pada database yang sudah memiliki page_sections.

ALTER TABLE `page_sections`
    ADD COLUMN `layout_mode` VARCHAR(30) NOT NULL DEFAULT 'legacy' AFTER `media_url`,
    ADD COLUMN `layout_options` LONGTEXT NULL AFTER `layout_mode`;

CREATE TABLE IF NOT EXISTS `homepage_section_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int(10) unsigned NOT NULL,
  `section_key` varchar(80) NOT NULL,
  `block_type` varchar(50) NOT NULL,
  `block_data` longtext DEFAULT NULL,
  `block_data_en` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_sort_order` (`section_id`, `sort_order`),
  KEY `section_published_sort_order` (`section_key`, `published`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
