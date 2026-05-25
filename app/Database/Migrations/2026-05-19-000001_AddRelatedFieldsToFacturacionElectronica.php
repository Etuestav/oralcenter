<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRelatedFieldsToFacturacionElectronica extends Migration
{
    public function up()
    {
        $fields = [
            'tipo_documento_relacionado' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'null'       => true,
                'after'      => 'direccion_cliente',
            ],
            'documento_relacionado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'tipo_documento_relacionado',
            ],
            'motivo_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'null'       => true,
                'after'      => 'documento_relacionado',
            ],
            'motivo_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
                'after'      => 'motivo_codigo',
            ],
        ];

        $this->forge->addColumn('facturacion_electronica', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('facturacion_electronica', [
            'tipo_documento_relacionado',
            'documento_relacionado',
            'motivo_codigo',
            'motivo_descripcion',
        ]);
    }
}
