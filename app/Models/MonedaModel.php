<?php

namespace App\Models;

use CodeIgniter\Model;

class MonedaModel extends Model
{
    protected $table      = 'tipo_moneda';
    protected $primaryKey = 'cod_tipomoneda';

    protected $returnType = 'object';

    protected $allowedFields = [
        'descripcion',
        'estado',
    ];

    public function getMoneda(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['tipo_moneda'])) {
            $builderLike->like('descripcion', $data['tipo_moneda']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['tipo_moneda'])) {
            $builder->like('descripcion', $data['tipo_moneda']);
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy(
                $data['orderCampo'],
                $data['orderDireccion'] ?? 'ASC'
            );
        }

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit(
                (int) $data['length'],
                (int) ($data['start'] ?? 0)
            );
        }

        $query = $builder->get();

        $rows = [];

        foreach ($query->getResult() as $q) {
            $estado = '';

            if ($q->estado === '1') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->estado === '2') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('moneda/editar/' . $q->cod_tipomoneda) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->cod_tipomoneda . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->cod_tipomoneda,
                $q->descripcion,
                $estado,
                $botones,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $totalRecords,
            'iTotalDisplayRecords' => $totalDisplayRecords,
            'aaData'               => $rows,
        ];
    }

    public function agregarMoneda(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getMonedaId(int $id): ?object
    {
        return $this->where('cod_tipomoneda', $id)
            ->first();
    }

    public function updateMoneda(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}