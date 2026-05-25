<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacturacionElectronicaTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_facturacion_config' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ruc_emisor' => [
                'type'       => 'VARCHAR',
                'constraint' => 11,
            ],
            'razon_social' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'nombre_comercial' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'ubigeo' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
            ],
            'departamento' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'provincia' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'distrito' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'modo' => [
                'type'       => 'ENUM',
                'constraint' => ['beta', 'produccion'],
                'default'    => 'beta',
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

        $this->forge->addKey('id_facturacion_config', true);
        $this->forge->createTable('facturacion_config', true);

        $this->forge->addField([
            'id_facturacion' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tipo_comprobante' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'default'    => '01',
            ],
            'serie' => [
                'type'       => 'VARCHAR',
                'constraint' => 4,
            ],
            'correlativo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'fecha_emision' => [
                'type' => 'DATE',
            ],
            'hora_emision' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'moneda' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
                'default'    => 'PEN',
            ],
            'codi_pac' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tipo_documento_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'default'    => '1',
            ],
            'numero_documento_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'razon_social_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'direccion_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
            ],
            'op_gravada' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'igv' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'registrado',
            ],
            'sunat_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pendiente',
            ],
            'xml_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'xml_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],
            'cdr_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sunat_mensaje' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('id_facturacion', true);
        $this->forge->addUniqueKey(['tipo_comprobante', 'serie', 'correlativo'], 'uq_facturacion_numero');
        $this->forge->addKey('codi_pac');
        $this->forge->createTable('facturacion_electronica', true);

        $this->forge->addField([
            'id_facturacion_detalle' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_facturacion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'codigo_producto' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'unidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
                'default'    => 'NIU',
            ],
            'cantidad' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 1,
            ],
            'valor_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'precio_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'igv' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
        ]);

        $this->forge->addKey('id_facturacion_detalle', true);
        $this->forge->addKey('id_facturacion');
        $this->forge->createTable('facturacion_electronica_detalle', true);
    }

    public function down()
    {
        $this->forge->dropTable('facturacion_electronica_detalle', true);
        $this->forge->dropTable('facturacion_electronica', true);
        $this->forge->dropTable('facturacion_config', true);
    }
}
