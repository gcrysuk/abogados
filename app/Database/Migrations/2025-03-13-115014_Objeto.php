<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class objeto extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_objeto' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('id_objeto', true); // Clave primaria en id_objeto
        $this->forge->createTable('objeto'); // Crear la tabla
    }

    public function down()
    {
        $this->forge->dropTable('objeto'); // Eliminar la tabla
    }
}
