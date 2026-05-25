<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSedeToFacturacionElectronicaConfig extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('cod_sede', 'facturacion_config')) {
            $this->forge->addColumn('facturacion_config', [
                'cod_sede' => [
                    'type' => 'INT',
                    'null' => true,
                    'after' => 'id_facturacion_config',
                ],
            ]);
            $this->db->query('ALTER TABLE facturacion_config ADD INDEX idx_facturacion_config_sede (cod_sede)');
        }

        if (!$this->db->fieldExists('cod_sede', 'facturacion_electronica')) {
            $this->forge->addColumn('facturacion_electronica', [
                'cod_sede' => [
                    'type' => 'INT',
                    'null' => true,
                    'after' => 'id_com',
                ],
            ]);
            $this->db->query('ALTER TABLE facturacion_electronica ADD INDEX idx_facturacion_electronica_sede (cod_sede)');
        }

        $defaultSede = $this->db->table('sede')
            ->select('cod_sede')
            ->where('estado_sede', 'S')
            ->orderBy('cod_sede', 'ASC')
            ->get()
            ->getRow();

        if ($defaultSede) {
            $this->db->table('facturacion_config')
                ->where('cod_sede', null)
                ->update(['cod_sede' => (int) $defaultSede->cod_sede]);
            $this->db->table('facturacion_electronica')
                ->where('cod_sede', null)
                ->update(['cod_sede' => (int) $defaultSede->cod_sede]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('cod_sede', 'facturacion_electronica')) {
            $this->forge->dropColumn('facturacion_electronica', 'cod_sede');
        }

        if ($this->db->fieldExists('cod_sede', 'facturacion_config')) {
            $this->forge->dropColumn('facturacion_config', 'cod_sede');
        }
    }
}
