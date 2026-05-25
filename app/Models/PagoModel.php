<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table      = 'tipo_pago';
    protected $primaryKey = 'cod_tipopago';

    protected $returnType = 'object';

    protected $allowedFields = [
        'descripcion',
        'estado',
    ];

    public function getPago(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['tipo_pago'])) {
            $builderLike->like('descripcion', $data['tipo_pago']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['tipo_pago'])) {
            $builder->like('descripcion', $data['tipo_pago']);
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

                    <a href="' . base_url('pago/editar/' . $q->cod_tipopago) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->cod_tipopago . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->cod_tipopago,
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

    public function agregarPago(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getPagoId(int $id): ?object
    {
        return $this->where('cod_tipopago', $id)
            ->first();
    }

    public function updatePago(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}