-- Terapkan template visual lama pada homepage blocks existing.
-- Jalankan setelah database_migrate_homepage_to_blocks.sql.

UPDATE homepage_section_blocks hsb
JOIN page_sections ps ON ps.id = hsb.section_id
SET hsb.block_type = CASE
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
    hsb.block_data = JSON_SET(
        JSON_SET(
            COALESCE(hsb.block_data, JSON_OBJECT()),
            '$.template_variant', CASE
            WHEN LOWER(ps.section_name) LIKE '%hero banner%' THEN 'hero_split'
            WHEN LOWER(ps.section_name) LIKE '%nilai utama%' THEN 'nilai_cards'
            WHEN LOWER(ps.section_name) LIKE '%visi dan misi%' THEN 'visi_split'
            WHEN LOWER(ps.section_name) LIKE '%sejarah bamusi%' THEN 'history_editorial'
            WHEN LOWER(ps.section_name) LIKE '%pengurus bamusi%' THEN 'board_cards'
            WHEN LOWER(ps.section_name) LIKE '%mitra homepage%' THEN 'partners_grid'
            WHEN ps.section_key = 'about' THEN 'about_sidebar'
            WHEN ps.section_key = 'program' THEN 'program_cards'
            WHEN ps.section_key = 'feature' THEN 'about_feature'
            WHEN ps.section_key = 'agenda' THEN 'agenda_cards'
            WHEN ps.section_key = 'news' THEN 'news_cards'
            WHEN ps.section_key = 'writing' THEN 'writing_cards'
            WHEN ps.section_key = 'social' THEN 'social_cards'
            WHEN ps.section_key IN ('join', 'internship') THEN 'join_form'
            ELSE NULL
            END
        ),
        '$.source', CASE
            WHEN ps.section_key = 'about' THEN 'about_links'
            WHEN ps.section_key IN ('join', 'internship') THEN 'join_interest'
            ELSE JSON_UNQUOTE(JSON_EXTRACT(COALESCE(hsb.block_data, JSON_OBJECT()), '$.source'))
        END
    ),
    hsb.section_key = ps.section_key,
    hsb.published = 1,
    hsb.updated_at = NOW();
