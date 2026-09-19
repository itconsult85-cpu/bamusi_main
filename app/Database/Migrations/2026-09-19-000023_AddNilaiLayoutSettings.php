<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNilaiLayoutSettings extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;

        $fields = [];
        $definitions = [
            'button_position' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'center', 'null' => false, 'after' => 'button_url'],
            'button_location' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'bottom', 'null' => false, 'after' => 'button_position'],
            'cards_visible' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'null' => false, 'after' => 'button_location'],
            'cards_limit' => ['type' => 'TINYINT', 'constraint' => 2, 'default' => 5, 'null' => false, 'after' => 'cards_visible'],
            'cards_columns' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 5, 'null' => false, 'after' => 'cards_limit'],
        ];

        foreach ($definitions as $name => $definition) {
            if (!$this->db->fieldExists($name, 'page_sections')) {
                $fields[$name] = $definition;
            }
        }
        if ($fields) $this->forge->addColumn('page_sections', $fields);
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) return;
        $fields = ['button_position', 'button_location', 'cards_visible', 'cards_limit', 'cards_columns'];
        foreach ($fields as $field) {
            if ($this->db->fieldExists($field, 'page_sections')) $this->forge->dropColumn('page_sections', $field);
        }
    }
}
