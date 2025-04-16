<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Carpetas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_carpeta' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_expediente' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'caratula' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'fecha' => [
                'type' => 'DATE',
            ],
            'radicada_juzgado' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => true, // Si puede ser NULL
            ],
            'cliente' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => false,
            ],
            'contraparte' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => true, // Si puede ser NULL
            ],
            'situacion' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => false,
            ],
            'objeto' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => false,
            ],
            'parte' => [
                'type'       => 'ENUM',
                'constraint' => ['actor', 'demandado'],
                'default'    => 'actor',
            ],
            'propietario_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'      => false,
            ],
        ]);

        $this->forge->addKey('id_carpeta', true); // Clave primaria

        // Asegúrate de que estas tablas existan antes de ejecutar esta migración
        $this->forge->addForeignKey('radicada_juzgado', 'juzgados', 'id_juzgados', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('cliente', 'personas', 'id_personas', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('contraparte', 'personas', 'id_personas', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('situacion', 'situacion', 'id_situacion', 'CASCADE', 'CASCADE'); // ¿Se llama 'situacion' o 'situaciones'?
        $this->forge->addForeignKey('objeto', 'objeto', 'id_objeto', 'CASCADE', 'CASCADE'); // ¿Se llama 'objeto' o 'objetos'?
        $this->forge->addForeignKey('propietario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('carpetas', true, ['engine' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('carpetas');
    }
}
