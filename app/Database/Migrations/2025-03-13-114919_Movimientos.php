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
                'type' => 'INT',  // FK carpeta
                'constraint' => 5,
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
                'type'       => 'DECIMAL', // Tipo DECIMAL
                'constraint' => [2, 1],   // Precisión de 2 dígitos, 1 decimal
                'null'       => false,    // No permite valores nulos
                'default'    => 0.0,     // CORREGIDO: Valor por defecto válido
            ],
            'id_otro_movimiento' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'id_estado' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tarea_pendiente' => [
                'type'    => 'BOOLEAN', // Tipo BOOLEAN
                'default' => FALSE,     // Valor por defecto (FALSE = no hay)
            ],
            'desc_tarea_pend' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_movimiento', true); // Clave primaria en id_movimiento

        // CORREGIDO: Nombres de tablas en minúsculas y consistentes
        $this->forge->addForeignKey('id_carpeta', 'carpetas', 'id_carpeta', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('id_otro_movimiento', 'movimientos', 'id_movimiento', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_estado', 'estados', 'id_estado', 'CASCADE', 'CASCADE');

        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->createTable('movimientos'); // Crear la tabla
    }

    public function down()
    {
        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->dropTable('movimientos');
    }
}
