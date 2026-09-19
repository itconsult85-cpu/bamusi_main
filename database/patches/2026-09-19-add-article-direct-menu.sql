INSERT INTO `cms_menu_items`
  (`label`, `label_en`, `target_type`, `target`, `active`, `is_mega`, `sort_order`, `created_at`, `updated_at`)
SELECT 'Artikel', 'Articles', 'url', '/artikel', 1, 0, 5, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `cms_menu_items` WHERE `target_type` = 'url' AND `target` = '/artikel'
);
