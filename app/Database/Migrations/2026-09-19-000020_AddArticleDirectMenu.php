<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArticleDirectMenu extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('cms_menu_items')) return;
        if ($this->db->table('cms_menu_items')->where('target_type', 'url')->where('target', '/artikel')->countAllResults() > 0) return;

        $this->db->table('cms_menu_items')->insert([
            'label' => 'Artikel',
            'label_en' => 'Articles',
            'target_type' => 'url',
            'target' => '/artikel',
            'active' => 1,
            'is_mega' => 0,
            'sort_order' => 5,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        if ($this->db->tableExists('cms_menu_items')) {
            $this->db->table('cms_menu_items')->where('target_type', 'url')->where('target', '/artikel')->delete();
        }
    }
}
