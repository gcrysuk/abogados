<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Juzgados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_juzgados' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'direccion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'fuero' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('id_juzgados', true); // Clave primaria en id
        $this->forge->createTable('Juzgados');
    }

    public function down()
    {
        $this->forge->dropTable('Juzgados');
    }
}
