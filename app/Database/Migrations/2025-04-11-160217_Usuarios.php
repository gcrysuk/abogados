<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'username'    => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'rol'         => ['type' => 'ENUM("admin","abogado","cliente")', 'default' => 'abogado'],
            'activo'      => ['type' => 'TINYINT', 'default' => 1],
            'creado_el'   => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
            'nombre'      => ['type' => 'VARCHAR','constraint' => 50,],
            'domicilio' => ['type' => 'VARCHAR','constraint' => 50,],
            'telefono' => ['type' => 'VARCHAR','constraint' => 15,],
            'mail' => ['type' => 'VARCHAR','constraint' => 50,]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
