<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDynamicSectionIdentity extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) {
            return;
        }
        if (!$this->db->fieldExists('render_key', 'page_sections')) {
            $this->forge->addColumn('page_sections', [
                'render_key' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true, 'after' => 'section_key'],
            ]);
        }
        $this->db->query("UPDATE page_sections SET render_key = section_key WHERE render_key IS NULL OR render_key = ''");
    }

    public function down()
    {
        if ($this->db->tableExists('page_sections') && $this->db->fieldExists('render_key', 'page_sections')) {
            $this->forge->dropColumn('page_sections', 'render_key');
        }
    }
}
