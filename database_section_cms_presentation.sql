-- BAMUSI CMS: field presentasi general dan urutan visual Menu Section.
-- Migration CodeIgniter: 2026-09-20-000039_AddSectionPresentationFields

ALTER TABLE page_sections
    ADD COLUMN kicker_color VARCHAR(7) NOT NULL DEFAULT '#e7aa6b' AFTER quote_en,
    ADD COLUMN title_color VARCHAR(7) NOT NULL DEFAULT '#ffffff' AFTER kicker_color,
    ADD COLUMN lead_color VARCHAR(7) NOT NULL DEFAULT '#d7e8dd' AFTER title_color,
    ADD COLUMN quote_color VARCHAR(7) NOT NULL DEFAULT '#e7aa6b' AFTER lead_color;

UPDATE page_sections SET sort_order = CASE section_key
    WHEN 'hero' THEN 10
    WHEN 'about' THEN 20
    WHEN 'nilai' THEN 30
    WHEN 'visi' THEN 40
    WHEN 'history' THEN 50
    WHEN 'board' THEN 60
    WHEN 'program' THEN 70
    WHEN 'agenda' THEN 80
    WHEN 'news' THEN 90
    WHEN 'writing' THEN 100
    WHEN 'social' THEN 110
    WHEN 'partners' THEN 120
    WHEN 'internship' THEN 130
    WHEN 'join' THEN 140
    WHEN 'contact' THEN 150
    WHEN 'feature' THEN 160
    WHEN 'gallery' THEN 170
    ELSE sort_order
END;
