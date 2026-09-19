<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsMenuItems extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('cms_menu_items')) return;

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'parent_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'label' => ['type' => 'VARCHAR', 'constraint' => 150],
            'label_en' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'target_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'section'],
            'target' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'description_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_mega' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['parent_id', 'active', 'sort_order']);
        $this->forge->createTable('cms_menu_items');
    }

    public function down()
    {
        $this->forge->dropTable('cms_menu_items', true);
    }
}
