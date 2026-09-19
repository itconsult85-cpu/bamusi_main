-- Target menu: halaman biasa, section homepage, atau URL custom.
-- Jalankan hanya jika kolom belum tersedia pada tabel pages.
ALTER TABLE `pages`
  ADD COLUMN `menu_target_type` varchar(20) NOT NULL DEFAULT 'page' AFTER `is_mega`,
  ADD COLUMN `menu_target` varchar(255) DEFAULT NULL AFTER `menu_target_type`;
