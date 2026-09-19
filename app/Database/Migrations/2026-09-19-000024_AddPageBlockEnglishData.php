<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPageBlockEnglishData extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_blocks')) return;
        if (!$this->db->fieldExists('block_data_en', 'page_blocks')) {
            $this->forge->addColumn('page_blocks', [
                'block_data_en' => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'block_data'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('page_blocks') && $this->db->fieldExists('block_data_en', 'page_blocks')) {
            $this->forge->dropColumn('page_blocks', 'block_data_en');
        }
    }
}
