-- BAMUSI CMS: salin data berulang legacy ke homepage_section_items.
-- Jalankan setelah tabel homepage_section_items tersedia.
-- Tabel legacy tidak dihapus. Migration CodeIgniter: 2026-09-20-000040_MigrateLegacyHomepageItems

INSERT INTO homepage_section_items
(section_key, item_key, label, label_en, title, title_en, body, body_en, url, media_url, options_json, sort_order, published, created_at, updated_at)
SELECT 'program', p.slug, p.name, p.name_en, p.name, p.name_en, p.description, p.description_en, NULL, p.image_url,
       JSON_OBJECT('slug', p.slug, 'division', p.division), p.sort_order, p.published, NOW(), NOW()
FROM programs p
LEFT JOIN homepage_section_items i ON i.section_key = 'program' AND i.item_key = p.slug
WHERE i.id IS NULL;

INSERT INTO homepage_section_items
(section_key, item_key, label, label_en, title, title_en, body, body_en, media_url, options_json, sort_order, published, created_at, updated_at)
SELECT 'nilai', CONCAT('value-', v.id), v.label, v.label_en, v.label, v.label_en, v.description, v.description_en, NULL, NULL, v.sort_order, v.published, NOW(), NOW()
FROM about_values v
LEFT JOIN homepage_section_items i ON i.section_key = 'nilai' AND i.item_key = CONCAT('value-', v.id)
WHERE i.id IS NULL;

INSERT INTO homepage_section_items
(section_key, item_key, label, label_en, title, title_en, body, body_en, url, media_url, options_json, sort_order, published, created_at, updated_at)
SELECT CASE WHEN c.kind = 'agenda' THEN 'agenda' ELSE 'writing' END, CONCAT(c.kind, '-', c.id), c.title, c.title_en, c.title, c.title_en,
       COALESCE(c.summary, c.body), COALESCE(c.summary_en, c.body_en),
       COALESCE(c.url, CASE WHEN c.kind = 'article' THEN CONCAT('/artikel/', c.id) ELSE CONCAT('/agenda/', c.id) END), c.image_url,
       JSON_OBJECT('event_date', c.event_date, 'category', c.category), 0, c.published, NOW(), NOW()
FROM cms_items c
LEFT JOIN homepage_section_items i ON i.item_key = CONCAT(c.kind, '-', c.id)
WHERE c.kind IN ('agenda', 'article') AND i.id IS NULL;

INSERT INTO homepage_section_items
(section_key, item_key, label, label_en, title, title_en, body, body_en, url, media_url, sort_order, published, created_at, updated_at)
SELECT 'partners', CONCAT('partner-', p.id), p.name, p.name_en, p.name, p.name_en, NULL, NULL, p.website_url, p.logo_url, p.sort_order, p.published, NOW(), NOW()
FROM partners p
LEFT JOIN homepage_section_items i ON i.section_key = 'partners' AND i.item_key = CONCAT('partner-', p.id)
WHERE i.id IS NULL;

INSERT INTO homepage_section_items
(section_key, item_key, label, label_en, title, title_en, body, body_en, url, sort_order, published, created_at, updated_at)
SELECT 'about_links', CONCAT('link-', l.id), l.label, l.label_en, l.label, l.label_en, l.sublabel, l.sublabel_en, l.url, l.sort_order, l.published, NOW(), NOW()
FROM section_links l
LEFT JOIN homepage_section_items i ON i.section_key = 'about_links' AND i.item_key = CONCAT('link-', l.id)
WHERE l.section_key = 'about' AND i.id IS NULL;
