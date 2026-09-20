-- Repair card Nilai yang tersembunyi karena cards_visible tersimpan 0.
-- Migration CodeIgniter: 2026-09-20-000042_RepairNilaiCardVisibility
UPDATE page_sections
SET cards_visible = 1,
    cards_limit = COALESCE(NULLIF(cards_limit, 0), 5),
    cards_columns = COALESCE(NULLIF(cards_columns, 0), 5)
WHERE section_key = 'nilai'
   OR render_key = 'nilai';
