<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class situacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_situacion' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_situacion', true); // Clave primaria en id_situacion
        $this->forge->createTable('situacion'); // Crear la tabla
    }

    public function down()
    {
        $this->forge->dropTable('situacion'); // Eliminar la tabla
    }
}
