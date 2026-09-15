-- BAMUSI: daftar halaman mandiri Berita agar dapat dikelola dari admin/pages.
-- Idempotent: aman dijalankan ulang dan tidak mengubah data pages yang sudah ada.
INSERT INTO `pages` (
  `parent_id`, `slug`, `title`, `title_en`, `excerpt`, `excerpt_en`, `published`,
  `show_in_menu`, `is_mega`, `menu_label`, `menu_label_en`, `sort_order`,
  `meta_title`, `meta_description`, `created_at`, `updated_at`, `header_kicker`,
  `header_title`, `header_intro`, `header_show_logo`, `header_show_intro`, `header_show_back`
)
SELECT
  NULL, 'berita', 'Berita', 'News', 'Kabar BAMUSI untuk Indonesia.',
  'BAMUSI news for Indonesia.', 1, 1, 0, 'Berita', 'News', 50,
  'Berita BAMUSI', 'Pemberitaan terbaru tentang Baitul Muslimin Indonesia.',
  NOW(), NOW(), '05 / 05 · Ruang Berita', 'Kabar BAMUSI untuk Indonesia.',
  'Kurasi pemberitaan publik tentang Baitul Muslimin Indonesia dari sumber nasional.',
  0, 1, 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'berita');
