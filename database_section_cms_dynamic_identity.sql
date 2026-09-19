-- BAMUSI CMS: identitas dinamis section.
-- Migration CodeIgniter: 2026-09-20-000041_AddDynamicSectionIdentity

ALTER TABLE page_sections
    ADD COLUMN render_key VARCHAR(80) NULL AFTER section_key;

UPDATE page_sections
SET render_key = section_key
WHERE render_key IS NULL OR render_key = '';
