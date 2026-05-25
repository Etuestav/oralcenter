<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSunatConfigToFacturacionConfig extends Migration
{
    public function up()
    {
        $fields = [
            'sol_usuario' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
                'after'      => 'modo',
            ],
            'sol_clave' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'sol_usuario',
            ],
            'certificado_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'sol_clave',
            ],
            'certificado_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
                'null'       => true,
                'after'      => 'certificado_path',
            ],
            'certificado_clave' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'certificado_nombre',
            ],
        ];

        $this->forge->addColumn('facturacion_config', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('facturacion_config', [
            'sol_usuario',
            'sol_clave',
            'certificado_path',
            'certificado_nombre',
            'certificado_clave',
        ]);
    }
}
