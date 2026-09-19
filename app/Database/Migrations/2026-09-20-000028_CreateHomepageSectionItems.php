<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHomepageSectionItems extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('homepage_section_items')) return;
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'section_key' => ['type' => 'VARCHAR', 'constraint' => 80],
            'item_key' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'label' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'label_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'title_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'body' => ['type' => 'TEXT', 'null' => true],
            'body_en' => ['type' => 'TEXT', 'null' => true],
            'url' => ['type' => 'TEXT', 'null' => true],
            'media_url' => ['type' => 'TEXT', 'null' => true],
            'options_json' => ['type' => 'LONGTEXT', 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'published' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['section_key', 'sort_order']);
        $this->forge->createTable('homepage_section_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('homepage_section_items', true);
    }
}
