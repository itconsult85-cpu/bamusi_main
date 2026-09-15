-- BAMUSI: blok konten modular untuk inner page.
-- Aman dijalankan ulang karena hanya membuat tabel jika belum ada.
CREATE TABLE IF NOT EXISTS `page_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `block_type` varchar(40) NOT NULL,
  `block_data` longtext NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_page_blocks_page_order` (`page_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
