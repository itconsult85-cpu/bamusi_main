<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPartnersHomepageSection extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;
        if ($this->db->table('page_sections')->where('section_key', 'partners')->countAllResults() > 0) return;
        $this->db->table('page_sections')->insert([
            'section_key' => 'partners',
            'section_name' => 'Mitra Homepage',
            'kicker' => 'Mitra Kerja Sama',
            'kicker_en' => 'Working Partners',
            'title' => 'Bertumbuh melalui\njejaring dan kolaborasi.',
            'title_en' => 'Growing through\nnetworks and collaboration.',
            'subtitle' => 'Klik logo untuk mengunjungi situs resmi masing-masing lembaga.',
            'subtitle_en' => 'Click on the logo to visit the official website of each institution.',
            'published' => 1,
            'sort_order' => 90,
        ]);
    }

    public function down()
    {
        $this->db->table('page_sections')->where('section_key', 'partners')->delete();
    }
}
