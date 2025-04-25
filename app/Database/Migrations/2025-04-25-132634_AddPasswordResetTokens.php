<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '64'
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'expires_at' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('token');
        $this->forge->addKey('expires_at');
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('password_reset_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('password_reset_tokens');
    }
}
