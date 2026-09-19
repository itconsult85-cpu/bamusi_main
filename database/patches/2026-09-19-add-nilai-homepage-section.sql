INSERT INTO `page_sections`
  (`section_key`, `section_name`, `kicker`, `kicker_en`, `title`, `title_en`, `subtitle`, `subtitle_en`, `button_label`, `button_label_en`, `button_url`, `published`, `sort_order`, `created_at`, `updated_at`)
SELECT
  'nilai', '5. Lima Nilai Utama', 'NILAI-NILAI BAMUSI', 'BAMUSI VALUES',
  'Lima Nilai Utama', 'Five Core Values',
  'Demokratis, gotong royong, moderat, toleran, dan nasionalis Soekarnois menjadi nilai yang menuntun langkah BAMUSI.',
  'Democratic, mutual cooperation, moderate, tolerant, and Soekarnoist nationalist values guide BAMUSI.',
  'Baca Selengkapnya ↗', 'Read More ↗', '/lima-nilai-utama', 1, 45, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `page_sections` WHERE `section_key` = 'nilai'
);
