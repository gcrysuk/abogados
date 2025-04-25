<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogAccesosTable extends Migration
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
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'login, logout, password_reset'
            ],
            'exitoso' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'detalles' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'fecha' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('usuario_id');
        $this->forge->addKey('tipo');
        $this->forge->addKey('fecha');

        // Si quieres relación con la tabla usuarios (opcional)
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('log_accesos');
    }

    public function down()
    {
        $this->forge->dropTable('log_accesos');
    }
}
