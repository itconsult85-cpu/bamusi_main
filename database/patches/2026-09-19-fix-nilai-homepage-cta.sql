UPDATE `page_sections`
SET
  `button_label` = CASE WHEN COALESCE(TRIM(`button_label`), '') = '' THEN 'Baca Selengkapnya ↗' ELSE `button_label` END,
  `button_label_en` = CASE WHEN COALESCE(TRIM(`button_label_en`), '') = '' THEN 'Read More ↗' ELSE `button_label_en` END,
  `button_url` = CASE WHEN COALESCE(TRIM(`button_url`), '') = '' THEN '/lima-nilai-utama' ELSE `button_url` END,
  `updated_at` = NOW()
WHERE `section_key` = 'nilai';
