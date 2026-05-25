<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAreaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_area' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre_area' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'descripcion_area' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_area', true);
        $this->forge->createTable('area', true);
    }

    public function down()
    {
        $this->forge->dropTable('area', true);
    }
}
