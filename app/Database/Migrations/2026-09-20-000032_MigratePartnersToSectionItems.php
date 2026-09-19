<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigratePartnersToSectionItems extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('homepage_section_items') || !$this->db->tableExists('partners')) return;
        $items = $this->db->table('homepage_section_items');
        foreach ($this->db->table('partners')->get()->getResultArray() as $partner) {
            $key = 'partner-' . $partner['id'];
            if ($items->where(['section_key' => 'partners', 'item_key' => $key])->countAllResults() > 0) continue;
            $items->insert([
                'section_key' => 'partners', 'item_key' => $key,
                'label' => $partner['name'], 'label_en' => $partner['name_en'] ?? $partner['name'],
                'title' => $partner['name'], 'title_en' => $partner['name_en'] ?? $partner['name'],
                'url' => $partner['website_url'], 'media_url' => $partner['logo_url'],
                'sort_order' => $partner['sort_order'], 'published' => $partner['published'],
                'created_at' => $partner['created_at'], 'updated_at' => $partner['updated_at'],
            ]);
        }
    }

    public function down() {}
}
