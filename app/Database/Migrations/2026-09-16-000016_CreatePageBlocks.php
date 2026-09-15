<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePageBlocks extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('page_blocks')) return;

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'page_id'    => ['type' => 'INT', 'unsigned' => true],
            'block_type' => ['type' => 'VARCHAR', 'constraint' => 40],
            'block_data' => ['type' => 'LONGTEXT'],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'published'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['page_id', 'sort_order']);
        $this->forge->createTable('page_blocks');
    }

    public function down()
    {
        if ($this->db->tableExists('page_blocks')) $this->forge->dropTable('page_blocks');
    }
}
