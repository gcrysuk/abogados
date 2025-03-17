<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Estados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_estado' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'estado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_estado', true);
        $this->forge->createTable('Estados', true, ['ENGINE' => 'InnoDB']); // Mejor rendimiento
    }

    public function down()
    {
        $this->forge->dropTable('Estados');
    }
}
