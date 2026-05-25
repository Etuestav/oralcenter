<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturacionConfigModel extends Model
{
    protected $table      = 'facturacion_config';
    protected $primaryKey = 'id_facturacion_config';
    protected $returnType = 'object';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'ruc_emisor',
        'razon_social',
        'nombre_comercial',
        'ubigeo',
        'direccion',
        'departamento',
        'provincia',
        'distrito',
        'modo',
        'sol_usuario',
        'sol_clave',
        'certificado_path',
        'certificado_nombre',
        'certificado_clave',
        'cod_sede',
    ];

    public function obtener(?int $codSede = null): ?object
    {
        if ($codSede !== null && $codSede > 0) {
            $config = $this->where('cod_sede', $codSede)
                ->orderBy('id_facturacion_config', 'ASC')
                ->first();

            if ($config) {
                return $config;
            }
        }

        $config = $this->groupStart()
                ->where('cod_sede', null)
                ->orWhere('cod_sede', 0)
            ->groupEnd()
            ->orderBy('id_facturacion_config', 'ASC')
            ->first();

        if ($config) {
            return $config;
        }

        return $this->orderBy('id_facturacion_config', 'ASC')->first();
    }

    public function guardar(array $data, ?int $codSede = null): int|string
    {
        if ($codSede !== null && $codSede > 0) {
            $data['cod_sede'] = $codSede;
            $config = $this->where('cod_sede', $codSede)
                ->orderBy('id_facturacion_config', 'ASC')
                ->first();
        } else {
            $data['cod_sede'] = null;
            $config = $this->groupStart()
                    ->where('cod_sede', null)
                    ->orWhere('cod_sede', 0)
                ->groupEnd()
                ->orderBy('id_facturacion_config', 'ASC')
                ->first();
        }

        if ($config) {
            $this->update($config->id_facturacion_config, $data);

            return $config->id_facturacion_config;
        }

        $this->insert($data);

        return $this->getInsertID();
    }
}
