-- BAMUSI footer settings
-- Idempoten: hanya menambahkan key yang belum tersedia.
-- Tidak mengubah atau menghapus data footer yang sudah ada.

INSERT INTO `site_settings`
(`setting_key`, `setting_value`, `setting_value_en`, `label`, `label_en`, `location`, `type`, `sort_order`, `created_at`, `updated_at`)
SELECT 'footer.heading', 'Kantor Pengurus<br>Pusat BAMUSI', 'Kantor Pengurus<br>Pusat BAMUSI', 'Judul Footer', 'Judul Footer', 'footer', 'textarea', 900, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `setting_key` = 'footer.heading');

INSERT INTO `site_settings`
(`setting_key`, `setting_value`, `setting_value_en`, `label`, `label_en`, `location`, `type`, `sort_order`, `created_at`, `updated_at`)
SELECT 'footer.tagline', 'Islam Nusantara yang berkemajuan untuk Indonesia Raya.', 'Islam Nusantara yang berkemajuan untuk Indonesia Raya.', 'Tagline Footer', 'Tagline Footer', 'footer', 'textarea', 900, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `setting_key` = 'footer.tagline');

INSERT INTO `site_settings`
(`setting_key`, `setting_value`, `setting_value_en`, `label`, `label_en`, `location`, `type`, `sort_order`, `created_at`, `updated_at`)
SELECT `seed`.`setting_key`, `seed`.`setting_value`, `seed`.`setting_value`, `seed`.`label`, `seed`.`label`, 'footer', `seed`.`type`, 900, NOW(), NOW()
FROM (
    SELECT 'partner_name' AS setting_key, 'PDI Perjuangan' AS setting_value, 'Nama Mitra' AS label, 'text' AS type
    UNION ALL SELECT 'partner_url', 'https://pdiperjuangan.id/', 'URL Mitra', 'url'
    UNION ALL SELECT 'contact_address_short', 'Jl. Kalibata Tengah, Kalibata, Kec. Pancoran,<br>Kota Jakarta Selatan, DKI Jakarta 12740', 'Alamat Singkat', 'textarea'
    UNION ALL SELECT 'contact_maps_url', '', 'URL Google Maps', 'url'
    UNION ALL SELECT 'contact_maps_label', 'Buka di Google Maps', 'Label Tautan Google Maps', 'text'
    UNION ALL SELECT 'whatsapp_label', '+62 878 9262 7144', 'Nomor WhatsApp yang Ditampilkan', 'text'
    UNION ALL SELECT 'whatsapp_url', '', 'URL WhatsApp', 'url'
    UNION ALL SELECT 'instagram_url', '', 'URL Instagram', 'url'
    UNION ALL SELECT 'tiktok_url', '', 'URL TikTok', 'url'
    UNION ALL SELECT 'youtube_url', '', 'URL YouTube', 'url'
    UNION ALL SELECT 'footer.copyright', 'Baitul Muslimin Indonesia. Hak cipta dilindungi.', 'Teks Copyright', 'text'
) AS `seed`
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `setting_key` = `seed`.`setting_key`);
