-- Section contact homepage legacy sudah digantikan oleh footer global.
-- Data section dan block tidak dihapus; hanya disembunyikan dari homepage.

UPDATE `page_sections`
SET `published` = 0,
    `updated_at` = NOW()
WHERE `section_key` = 'contact';
