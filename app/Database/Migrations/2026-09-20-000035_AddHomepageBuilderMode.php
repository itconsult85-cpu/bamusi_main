<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHomepageBuilderMode extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;

        $fields = [];
        if (!$this->db->fieldExists('layout_mode', 'page_sections')) {
            $fields['layout_mode'] = ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'legacy', 'after' => 'media_url'];
        }
        if (!$this->db->fieldExists('layout_options', 'page_sections')) {
            $fields['layout_options'] = ['type' => 'LONGTEXT', 'null' => true, 'after' => 'layout_mode'];
        }
        if ($fields) $this->forge->addColumn('page_sections', $fields);
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) return;
        foreach (['layout_options', 'layout_mode'] as $field) {
            if ($this->db->fieldExists($field, 'page_sections')) $this->forge->dropColumn('page_sections', $field);
        }
    }
}
