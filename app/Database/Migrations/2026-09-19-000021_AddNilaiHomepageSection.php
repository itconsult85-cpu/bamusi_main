<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNilaiHomepageSection extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;
        if ($this->db->table('page_sections')->where('section_key', 'nilai')->countAllResults() > 0) return;

        $this->db->table('page_sections')->insert([
            'section_key' => 'nilai',
            'section_name' => '5. Lima Nilai Utama',
            'kicker' => 'NILAI-NILAI BAMUSI',
            'kicker_en' => 'BAMUSI VALUES',
            'title' => 'Lima Nilai Utama',
            'title_en' => 'Five Core Values',
            'subtitle' => 'Demokratis, gotong royong, moderat, toleran, dan nasionalis Soekarnois menjadi nilai yang menuntun langkah BAMUSI.',
            'subtitle_en' => 'Democratic, mutual cooperation, moderate, tolerant, and Soekarnoist nationalist values guide BAMUSI.',
            'button_label' => 'Baca Selengkapnya ↗',
            'button_label_en' => 'Read More ↗',
            'button_url' => '/lima-nilai-utama',
            'published' => 1,
            'sort_order' => 45,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        if ($this->db->tableExists('page_sections')) {
            $this->db->table('page_sections')->where('section_key', 'nilai')->delete();
        }
    }
}
