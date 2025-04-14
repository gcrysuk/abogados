<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CarpetaPermisos extends Migration

{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'id_carpeta'     => ['type' => 'INT', 'unsigned' => true],
            'usuario_id'   => ['type' => 'INT', 'unsigned' => true],
            'permiso'      => ['type' => 'ENUM', 'constraint' => ['ver', 'editar', 'seguimiento']],
            'otorgado_por' => ['type' => 'INT', 'unsigned' => true],
            'creado_el'    => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_carpeta', 'carpetas', 'id_carpeta', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('otorgado_por', 'usuarios', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('carpeta_permisos');
    }

    public function down()
    {
        $this->forge->dropTable('carpeta_permisos');
    }
}
