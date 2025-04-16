<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Usuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ],
            'rol' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'abogado', 'cliente'],
                'default'    => 'abogado'
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1
            ],
            'creado_el' => [
                'type'    => 'DATETIME',  // Cambiado a DATETIME
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50
            ],
            'domicilio' => [
                'type'       => 'VARCHAR',
                'constraint' => 50
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 15
            ],
            'mail' => [
                'type'       => 'VARCHAR',
                'constraint' => 50
            ]
        ]);

        $this->forge->addKey('id', true); // Clave primaria
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
