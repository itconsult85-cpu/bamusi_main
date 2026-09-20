<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepairStableSectionRenderKeys extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections') || !$this->db->fieldExists('render_key', 'page_sections')) {
            return;
        }

        $map = [
            'Hero Banner' => 'hero',
            'Tentang BAMUSI' => 'about',
            '5. Lima Nilai Utama' => 'nilai',
            'Visi dan Misi' => 'visi',
            'Sejarah BAMUSI' => 'history',
            'Pengurus BAMUSI' => 'board',
            'Mitra Homepage' => 'partners',
        ];
        foreach ($map as $name => $renderKey) {
            $this->db->table('page_sections')->where('section_name', $name)->update(['render_key' => $renderKey]);
        }
    }

    public function down()
    {
        // Tidak menghapus identitas layout yang sudah diperbaiki.
    }
}
