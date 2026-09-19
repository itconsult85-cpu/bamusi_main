-- BAMUSI CMS: migrate history content to page_sections and remove obsolete global keys.
-- Run once if migrations are not used.
ALTER TABLE page_sections ADD COLUMN label VARCHAR(255) NULL AFTER section_name, ADD COLUMN label_en VARCHAR(255) NULL AFTER label, ADD COLUMN label_size INT NOT NULL DEFAULT 96 AFTER label_en, ADD COLUMN title_size INT NOT NULL DEFAULT 56 AFTER label_size;
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_label' SET ps.label = COALESCE(NULLIF(ps.label, ''), wt.value) WHERE ps.section_key = 'history';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_label_size' SET ps.label_size = CAST(wt.value AS UNSIGNED) WHERE ps.section_key = 'history' AND wt.value REGEXP '^[0-9]+$';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_title_size' SET ps.title_size = CAST(wt.value AS UNSIGNED) WHERE ps.section_key = 'history' AND wt.value REGEXP '^[0-9]+$';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_title' SET ps.title = COALESCE(NULLIF(ps.title, ''), wt.value) WHERE ps.section_key = 'history';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_body' SET ps.subtitle = COALESCE(NULLIF(ps.subtitle, ''), wt.value) WHERE ps.section_key = 'history';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_link' SET ps.button_label = COALESCE(NULLIF(ps.button_label, ''), wt.value) WHERE ps.section_key = 'history';
UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_kicker' SET ps.kicker = COALESCE(NULLIF(ps.kicker, ''), wt.value) WHERE ps.section_key = 'history';
DELETE FROM website_texts WHERE text_key IN ('home.history_title','home.history_body','home.history_link','home.history_label','home.history_kicker','home.history_label_size','home.history_title_size') OR text_key LIKE 'reference.hero_%';
