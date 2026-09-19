-- BAMUSI CMS: pindahkan data Hero legacy ke Menu Section.
-- Aman dijalankan sekali; tabel hero_slides tetap dipertahankan sebagai backup.
-- Migration CodeIgniter: 2026-09-20-000038_MigrateHeroSlideToPageSection

UPDATE page_sections ps
JOIN (
    SELECT hs.*
    FROM hero_slides hs
    WHERE hs.published = 1
    ORDER BY hs.sort_order ASC, hs.id ASC
    LIMIT 1
) hs ON ps.section_key = 'hero'
SET
    ps.kicker = CASE WHEN COALESCE(TRIM(ps.kicker), '') = '' THEN hs.kicker ELSE ps.kicker END,
    ps.kicker_en = CASE WHEN COALESCE(TRIM(ps.kicker_en), '') = '' THEN hs.kicker_en ELSE ps.kicker_en END,
    ps.title = CASE WHEN COALESCE(TRIM(ps.title), '') = '' THEN hs.title ELSE ps.title END,
    ps.title_en = CASE WHEN COALESCE(TRIM(ps.title_en), '') = '' THEN hs.title_en ELSE ps.title_en END,
    ps.subtitle = CASE WHEN COALESCE(TRIM(ps.subtitle), '') = '' THEN hs.lead ELSE ps.subtitle END,
    ps.subtitle_en = CASE WHEN COALESCE(TRIM(ps.subtitle_en), '') = '' THEN hs.lead_en ELSE ps.subtitle_en END,
    ps.quote = CASE WHEN COALESCE(TRIM(ps.quote), '') = '' THEN hs.quote ELSE ps.quote END,
    ps.quote_en = CASE WHEN COALESCE(TRIM(ps.quote_en), '') = '' THEN hs.quote_en ELSE ps.quote_en END,
    ps.media_url = CASE WHEN COALESCE(TRIM(ps.media_url), '') = '' THEN hs.image_url ELSE ps.media_url END,
    ps.button_label = CASE WHEN COALESCE(TRIM(ps.button_label), '') = '' THEN hs.button_label ELSE ps.button_label END,
    ps.button_label_en = CASE WHEN COALESCE(TRIM(ps.button_label_en), '') = '' THEN hs.button_label_en ELSE ps.button_label_en END,
    ps.button_url = CASE WHEN COALESCE(TRIM(ps.button_url), '') = '' THEN hs.button_url ELSE ps.button_url END;
