<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMenuTarget extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('pages')) {
            return;
        }

        $fields = [];
        if (!$this->db->fieldExists('menu_target_type', 'pages')) {
            $fields['menu_target_type'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'page',
                'null'       => false,
                'after'      => 'is_mega',
            ];
        }
        if (!$this->db->fieldExists('menu_target', 'pages')) {
            $fields['menu_target'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'menu_target_type',
            ];
        }

        if ($fields) {
            $this->forge->addColumn('pages', $fields);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('pages')) {
            return;
        }

        $fields = [];
        if ($this->db->fieldExists('menu_target', 'pages')) {
            $fields[] = 'menu_target';
        }
        if ($this->db->fieldExists('menu_target_type', 'pages')) {
            $fields[] = 'menu_target_type';
        }
        if ($fields) {
            $this->forge->dropColumn('pages', $fields);
        }
    }
}
