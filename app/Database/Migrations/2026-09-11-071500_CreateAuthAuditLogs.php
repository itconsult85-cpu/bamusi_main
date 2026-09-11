<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthAuditLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 320, 'null' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'event' => ['type' => 'VARCHAR', 'constraint' => 32],
            'ip_hash' => ['type' => 'CHAR', 'constraint' => 64, 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['event', 'created_at']);
        $this->forge->addKey(['email', 'created_at']);
        $this->forge->createTable('auth_audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('auth_audit_logs');
    }
}
