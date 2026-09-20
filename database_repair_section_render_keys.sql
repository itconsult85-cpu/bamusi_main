-- Repair render_key agar section_key boleh diubah tanpa memutus template homepage.
UPDATE page_sections SET render_key = 'hero' WHERE section_name = 'Hero Banner';
UPDATE page_sections SET render_key = 'about' WHERE section_name = 'Tentang BAMUSI';
UPDATE page_sections SET render_key = 'nilai' WHERE section_name = '5. Lima Nilai Utama';
UPDATE page_sections SET render_key = 'visi' WHERE section_name = 'Visi dan Misi';
UPDATE page_sections SET render_key = 'history' WHERE section_name = 'Sejarah BAMUSI';
UPDATE page_sections SET render_key = 'board' WHERE section_name = 'Pengurus BAMUSI';
UPDATE page_sections SET render_key = 'partners' WHERE section_name = 'Mitra Homepage';
