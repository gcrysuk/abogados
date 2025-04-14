<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Carpetas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_carpeta' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'no_expediente' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'caratula' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'fecha' => [
                'type' => 'DATE',
            ],
            'radicada_juzgado' => [
                'type' => 'INT',  // FK Juzgados
                'constraint' => 5,
            ],
            'cliente' => [
                'type' => 'INT',  // FK Personas
                'constraint' => 5,
            ],
            'contraparte' => [
                'type' => 'INT',  // FK Personas
                'constraint' => 5,
            ],
            'situacion' => [
                'type' => 'INT',  // FK situacion
                'constraint' => 5,
            ],
            'objeto' => [
                'type' => 'INT',  // FK objeto
                'constraint' => 5,
            ],
            'parte' => [
                'type' => 'ENUM', // Tipo ENUM
                'constraint' => ['actor', 'demandado'], // Valores permitidos
                'default' => 'actor', // Valor por defecto
            ],
            'propietario_id' => [
                'type' => 'int',
            ],
        ]);

        $this->forge->addKey('id_carpeta', true); // Clave primaria en id_carpeta

        // CORREGIDO: Uso correcto de addUniqueKey para una clave única compuesta
        // $this->forge->addUniqueKey(['no_expediente', 'radicada_juzgado']);

        // CORREGIDO: Nombres de tablas en minúsculas y consistentes
        $this->forge->addForeignKey('radicada_juzgado', 'juzgados', 'id_juzgado', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('cliente', 'personas', 'id_persona', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('contraparte', 'personas', 'id_persona', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('situacion', 'situacion', 'id_situacion', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('objeto', 'objeto', 'id_objeto', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('propietario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->createTable('carpetas'); // Crear la tabla
    }

    public function down()
    {
        // CORREGIDO: Nombre de la tabla en minúsculas
        $this->forge->dropTable('carpetas');
    }
}
