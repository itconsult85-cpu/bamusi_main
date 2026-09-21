<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ArchiveLegacyContactSection extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }

        // Footer global sekarang menjadi sumber informasi kontak utama.
        // Data section dan block tidak dihapus agar rollback tetap aman.
        $this->db->table('page_sections')
            ->where('section_key', 'contact')
            ->update(['published' => 0]);
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }

        $this->db->table('page_sections')
            ->where('section_key', 'contact')
            ->update(['published' => 1]);
    }
}
