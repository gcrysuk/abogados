<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Peritos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_perito' => [ // CORREGIDO: Nombre del campo en singular
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'materia' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'domicilio_electronico' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('id_perito', true); // CORREGIDO: Clave primaria en id_perito
        $this->forge->addUniqueKey('domicilio_electronico'); // Asegura que sea único, pero no clave primaria

        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->createTable('peritos'); // Crear la tabla
    }

    public function down()
    {
        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->dropTable('peritos');
    }
}
