INSERT INTO `homepage_section_items` (`section_key`,`item_key`,`label`,`label_en`,`title`,`title_en`,`url`,`media_url`,`sort_order`,`published`,`created_at`,`updated_at`)
SELECT 'partners', CONCAT('partner-', p.`id`), p.`name`, COALESCE(p.`name_en`, p.`name`), p.`name`, COALESCE(p.`name_en`, p.`name`), p.`website_url`, p.`logo_url`, p.`sort_order`, p.`published`, p.`created_at`, p.`updated_at`
FROM `partners` p
WHERE NOT EXISTS (SELECT 1 FROM `homepage_section_items` i WHERE i.`section_key`='partners' AND i.`item_key`=CONCAT('partner-', p.`id`));
