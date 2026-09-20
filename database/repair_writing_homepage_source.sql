-- BAMUSI homepage repair: writing must read article items, not RSS news.
-- Safe to run repeatedly; it only changes writing blocks whose current source is news.
UPDATE homepage_section_blocks hsb
INNER JOIN page_sections ps ON ps.id = hsb.section_id
SET hsb.block_data = JSON_SET(hsb.block_data, '$.source', 'article'),
    hsb.updated_at = CURRENT_TIMESTAMP
WHERE (
        COALESCE(LOWER(ps.section_key), '') = 'writing'
     OR COALESCE(LOWER(ps.render_key), '') = 'writing'
     OR COALESCE(LOWER(ps.section_name), '') = 'writing'
      )
  AND JSON_VALID(hsb.block_data)
  AND JSON_UNQUOTE(JSON_EXTRACT(hsb.block_data, '$.source')) = 'news';
