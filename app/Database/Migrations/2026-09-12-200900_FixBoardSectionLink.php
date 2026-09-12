<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixBoardSectionLink extends Migration
{
    public function up()
    {
        $this->db->table('page_sections')
            ->where('section_key', 'board')
            ->update([
                'button_url'   => '/struktur-pengurus',
                'button_label' => 'Lihat semua pengurus',
                'button_label_en' => 'View all management',
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
    }

    public function down()
    {
        $this->db->table('page_sections')
            ->where('section_key', 'board')
            ->update([
                'button_url'   => '#kontak',
                'button_label' => 'Informasi sekretariat ↗',
                'button_label_en' => 'Secretariat information ↗',
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
    }
}
