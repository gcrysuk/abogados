<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Movimientos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_movimiento' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_carpeta' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'fecha_carga' => [
                'type' => 'DATE',
            ],
            'fecha_movimiento' => [
                'type' => 'DATE',
            ],
            'fecha_vencimiento' => [
                'type' => 'DATE',
            ],
            'tiempo_trabajo' => [
                'type' => 'DECIMAL',
                'constraint' => '4,1',
                'null' => false,
                'default' => 0.0,
            ],
            'id_otro_movimiento' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
            ],
            'id_estado' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tarea_pendiente' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'desc_tarea_pend' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notificacion' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ]
        ]);

        $this->forge->addPrimaryKey('id_movimiento');
        $this->forge->addKey('id_carpeta');
        $this->forge->addKey('id_estado');
        $this->forge->addKey('id_otro_movimiento');

        // Crear la tabla primero
        $this->forge->createTable('movimientos', true, ['engine' => 'InnoDB']);

        // Luego añadir las claves foráneas
        $this->forge->addForeignKey('id_carpeta', 'carpetas', 'id_carpeta', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_estado', 'estados', 'id_estado', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_otro_movimiento', 'movimientos', 'id_movimiento', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('movimientos');
    }
}
