-- BAMUSI homepage repair: keep Program Unggulan items separate from Ruang Khidmah.
-- Safe to run more than once. No rows are deleted.

UPDATE homepage_section_blocks b
JOIN page_sections s ON s.id = b.section_id
SET b.block_type = 'cards',
    b.block_data = JSON_SET(
        COALESCE(NULLIF(b.block_data, ''), '{}'),
        '$.source', 'feature',
        '$.template_variant', 'about_feature'
    ),
    b.published = 1,
    b.updated_at = NOW()
WHERE LOWER(TRIM(s.section_key)) = 'feature'
   OR LOWER(s.section_name) LIKE '%program unggulan%';

-- Verification query:
SELECT b.id, s.section_key, s.section_name, b.block_type, b.block_data
FROM homepage_section_blocks b
JOIN page_sections s ON s.id = b.section_id
WHERE LOWER(TRIM(s.section_key)) = 'feature'
   OR LOWER(s.section_name) LIKE '%program unggulan%';

-- No database structure was changed.
-- The application reads homepage_section_items.section_key = 'feature'
-- for Program Unggulan and section_key = 'program' for Ruang Khidmah.
