INSERT INTO `page_sections` (`section_key`,`section_name`,`kicker`,`kicker_en`,`title`,`title_en`,`subtitle`,`subtitle_en`,`published`,`sort_order`,`created_at`,`updated_at`)
SELECT 'partners','Mitra Homepage','Mitra Kerja Sama','Working Partners','Bertumbuh melalui\njejaring dan kolaborasi.','Growing through\nnetworks and collaboration.','Klik logo untuk mengunjungi situs resmi masing-masing lembaga.','Click on the logo to visit the official website of each institution.',1,90,NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `page_sections` WHERE `section_key`='partners');
