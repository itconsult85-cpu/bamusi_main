<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPageHeaderEnglishFields extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('pages')) return;
        $definitions = [
            'meta_title_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'meta_title'],
            'meta_description_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'meta_description'],
            'header_kicker_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'header_kicker'],
            'header_title_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'header_title'],
            'header_intro_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'header_intro'],
        ];
        $fields = [];
        foreach ($definitions as $name => $definition) {
            if (!$this->db->fieldExists($name, 'pages')) $fields[$name] = $definition;
        }
        if ($fields) $this->forge->addColumn('pages', $fields);
    }

    public function down()
    {
        if (!$this->db->tableExists('pages')) return;
        foreach (['meta_title_en', 'meta_description_en', 'header_kicker_en', 'header_title_en', 'header_intro_en'] as $field) {
            if ($this->db->fieldExists($field, 'pages')) $this->forge->dropColumn('pages', $field);
        }
    }
}
