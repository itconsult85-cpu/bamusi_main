<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepairNilaiCardVisibility extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }

        $builder = $this->db->table('page_sections');
        $builder->groupStart()
            ->where('section_key', 'nilai')
            ->orWhere('render_key', 'nilai')
            ->groupEnd()
            ->update([
                'cards_visible' => 1,
                'cards_limit' => 5,
                'cards_columns' => 5,
            ]);
    }

    public function down()
    {
        // Tidak mengubah kembali konfigurasi editor yang mungkin sudah disesuaikan admin.
    }
}
