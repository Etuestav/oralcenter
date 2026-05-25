<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddComprobanteToFacturacionElectronica extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('facturacion_electronica');

        if (!in_array('id_com', $fields, true)) {
            $this->forge->addColumn('facturacion_electronica', [
                'id_com' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'id_facturacion',
                ],
            ]);

            $this->forge->addKey('id_com');
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('facturacion_electronica');

        if (in_array('id_com', $fields, true)) {
            $this->forge->dropColumn('facturacion_electronica', 'id_com');
        }
    }
}
