<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPartnerEnglishName extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('partners') || $this->db->fieldExists('name_en', 'partners')) return;
        $this->forge->addColumn('partners', ['name_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'name']]);
    }

    public function down()
    {
        if ($this->db->fieldExists('name_en', 'partners')) $this->forge->dropColumn('partners', 'name_en');
    }
}
