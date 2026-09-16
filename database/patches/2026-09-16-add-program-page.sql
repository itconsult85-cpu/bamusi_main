-- BAMUSI: halaman landing Program agar metadata/header dapat diedit dari admin/pages.
INSERT INTO `pages` (`slug`, `title`, `title_en`, `excerpt`, `excerpt_en`, `published`, `show_in_menu`, `is_mega`, `menu_label`, `menu_label_en`, `sort_order`, `meta_title`, `meta_description`, `header_kicker`, `header_title`, `header_intro`, `header_show_logo`, `header_show_intro`, `header_show_back`, `created_at`, `updated_at`)
SELECT 'program', 'Program BAMUSI', 'BAMUSI Programs', 'Ruang khidmah dan program BAMUSI untuk Indonesia.', 'BAMUSI programs and service spaces for Indonesia.', 1, 1, 0, 'Program', 'Programs', 30, 'Program BAMUSI', 'Daftar program dan ruang khidmah Baitul Muslimin Indonesia.', 'BAMUSI / PROGRAM', 'Ruang khidmah yang nyata.', 'Jelajahi seluruh program BAMUSI berdasarkan bidang dan kebutuhan.', 0, 1, 1, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `pages` WHERE `slug` = 'program');
