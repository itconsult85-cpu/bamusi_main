<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHomepageSectionBlocks extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('homepage_section_blocks')) return;

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'section_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'section_key' => ['type' => 'VARCHAR', 'constraint' => 80],
            'block_type' => ['type' => 'VARCHAR', 'constraint' => 50],
            'block_data' => ['type' => 'LONGTEXT', 'null' => true],
            'block_data_en' => ['type' => 'LONGTEXT', 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'published' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['section_id', 'sort_order']);
        $this->forge->addKey(['section_key', 'published', 'sort_order']);
        $this->forge->createTable('homepage_section_blocks', true);
    }

    public function down()
    {
        $this->forge->dropTable('homepage_section_blocks', true);
    }
}
