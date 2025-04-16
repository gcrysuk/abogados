<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CarpetaPermisos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_carpeta' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'usuario_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'permiso' => [
                'type' => 'ENUM',
                'constraint' => ['ver', 'editar', 'seguimiento'],
                'null' => false
            ],
            'otorgado_por' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true  // Debe ser true para que funcione SET NULL
            ],
            'creado_el' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
        ]);

        $this->forge->addKey('id', true);

        // Asegúrate que estos nombres de tabla y columnas son exactos
        //$this->forge->addForeignKey('id_carpeta', 'carpetas', 'id_carpeta', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('otorgado_por', 'usuarios', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('carpeta_permisos', true, ['engine' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('carpeta_permisos');
    }
}
