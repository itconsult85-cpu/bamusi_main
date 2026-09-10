<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectionLinks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'section_key'   => ['type' => 'VARCHAR', 'constraint' => 50], // 'about', 'board', dll
            'label'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'label_en'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'sublabel'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'sublabel_en'   => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'url'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order'    => ['type' => 'INT', 'default' => 0],
            'published'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('section_key');
        $this->forge->createTable('section_links');
    }

    public function down()
    {
        $this->forge->dropTable('section_links');
    }
}
