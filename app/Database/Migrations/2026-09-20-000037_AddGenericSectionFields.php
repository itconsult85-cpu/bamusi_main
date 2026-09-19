<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGenericSectionFields extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('page_sections')) return;
        $fields = [
            'label' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'section_name'],
            'label_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'label'],
            'label_size' => ['type' => 'INT', 'constraint' => 11, 'null' => false, 'default' => 96, 'after' => 'label_en'],
            'title_size' => ['type' => 'INT', 'constraint' => 11, 'null' => false, 'default' => 56, 'after' => 'label_size'],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'page_sections')) $this->forge->addColumn('page_sections', [$name => $definition]);
        }
        if ($this->db->tableExists('website_texts')) {
            $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_label' SET ps.label = wt.value, ps.label_en = NULL WHERE ps.section_key = 'history' AND (ps.label IS NULL OR ps.label = '')");
            $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_label_size' SET ps.label_size = CAST(wt.value AS UNSIGNED) WHERE ps.section_key = 'history' AND wt.value REGEXP '^[0-9]+$'");
            $this->db->query("UPDATE page_sections ps JOIN website_texts wt ON wt.text_key = 'home.history_title_size' SET ps.title_size = CAST(wt.value AS UNSIGNED) WHERE ps.section_key = 'history' AND wt.value REGEXP '^[0-9]+$'");
            $this->db->table('website_texts')
                ->whereIn('text_key', ['home.history_label', 'home.history_label_size', 'home.history_title_size'])
                ->delete();
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('page_sections')) return;
        foreach (['title_size', 'label_size', 'label_en', 'label'] as $field) {
            if ($this->db->fieldExists($field, 'page_sections')) $this->forge->dropColumn('page_sections', $field);
        }
    }
}
