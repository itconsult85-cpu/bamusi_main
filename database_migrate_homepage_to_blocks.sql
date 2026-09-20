-- Migrasi manual homepage ke homepage_section_blocks.
-- Jalankan setelah tabel homepage_section_blocks tersedia.

INSERT INTO homepage_section_blocks
    (section_id, section_key, block_type, block_data, block_data_en, sort_order, published, created_at, updated_at)
SELECT
    ps.id,
    ps.section_key,
    CASE
        WHEN LOWER(ps.section_name) LIKE '%hero banner%' THEN 'hero_slider'
        WHEN LOWER(ps.section_name) LIKE '%nilai utama%' THEN 'cards'
        WHEN LOWER(ps.section_name) LIKE '%pengurus%' THEN 'cards'
        WHEN ps.section_key IN ('program', 'feature') THEN 'cards'
        WHEN ps.section_key = 'agenda' THEN 'cards'
        WHEN ps.section_key IN ('news', 'writing') THEN 'collection'
        WHEN ps.section_key = 'partners' THEN 'logo_grid'
        WHEN ps.section_key IN ('join', 'internship') THEN 'join_form'
        ELSE 'rich_text'
    END,
    CASE
        WHEN LOWER(ps.section_name) LIKE '%hero banner%' THEN JSON_OBJECT('source', 'section')
        WHEN LOWER(ps.section_name) LIKE '%nilai utama%' THEN JSON_OBJECT('source', 'about_values', 'variant', 'dark', 'columns', 5, 'limit', 5)
        WHEN LOWER(ps.section_name) LIKE '%pengurus%' THEN JSON_OBJECT('source', 'board', 'columns', 4, 'limit', 12)
        WHEN ps.section_key IN ('program', 'feature') THEN JSON_OBJECT('source', 'program', 'columns', 3, 'limit', 12)
        WHEN ps.section_key = 'agenda' THEN JSON_OBJECT('source', 'agenda', 'columns', 4, 'limit', 4)
        WHEN ps.section_key IN ('news', 'writing') THEN JSON_OBJECT('source', 'news', 'columns', 4, 'limit', 4)
        WHEN ps.section_key = 'partners' THEN JSON_OBJECT('source', 'partners', 'columns', 5, 'limit', 24)
        ELSE JSON_OBJECT('source', 'section')
    END,
    JSON_OBJECT(),
    0,
    1,
    NOW(),
    NOW()
FROM page_sections ps
LEFT JOIN homepage_section_blocks existing ON existing.section_id = ps.id
WHERE existing.id IS NULL;

UPDATE page_sections
SET layout_mode = 'builder'
WHERE id IN (SELECT section_id FROM homepage_section_blocks);

-- Verifikasi hasil.
SELECT ps.id, ps.section_key, ps.section_name, ps.layout_mode,
       hsb.block_type, hsb.block_data, hsb.published
FROM page_sections ps
LEFT JOIN homepage_section_blocks hsb ON hsb.section_id = ps.id
ORDER BY ps.sort_order, hsb.sort_order, hsb.id;
