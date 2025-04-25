<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixForeignKeyPropietario extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Asegurar que ambas tablas usen InnoDB
        $db->query("ALTER TABLE usuarios ENGINE = InnoDB");
        $db->query("ALTER TABLE personas ENGINE = InnoDB");

        // 2. Verificar que la columna referenciada (usuarios.id) sea PRIMARY KEY
        if (!$this->isPrimaryKey('usuarios', 'id')) {
            echo "Error: usuarios.id debe ser PRIMARY KEY";
            exit;
        }

        // 3. Verificar y modificar la columna propietario_id si es necesario
        if ($db->fieldExists('propietario_id', 'personas')) {
            // Asegurar que tenga el mismo tipo que usuarios.id
            $this->forge->modifyColumn('personas', [
                'propietario_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false
                ]
            ]);

            // 4. Agregar la FK con sintaxis explícita
            $this->forge->addForeignKey(
                'propietario_id',
                'usuarios',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } else {
            echo "Error: La columna propietario_id no existe en personas";
            exit;
        }
    }

    public function down()
    {
        $this->forge->dropForeignKey('personas', 'personas_propietario_id_foreign');
    }

    // Función helper para verificar si una columna es PRIMARY KEY
    protected function isPrimaryKey($table, $column)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SHOW INDEX FROM $table WHERE Key_name = 'PRIMARY' AND Column_name = ?", [$column]);
        return $query->getNumRows() > 0;
    }
}
