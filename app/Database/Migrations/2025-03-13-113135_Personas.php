<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Personas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_personas' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dni_cuit' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'domicilio' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'mail' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'observaciones' => [
                'type' => 'VARCHAR',
                'constraint' => 255, // Mejor que TINYTEXT
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_personas', true); // Clave primaria en id
        $this->forge->addUniqueKey('dni_cuit'); // Asegura que dni sea único, pero no clave primaria
        $this->forge->createTable('Personas');
    }

    public function down()
    {
        $this->forge->dropTable('Personas');
    }
}
