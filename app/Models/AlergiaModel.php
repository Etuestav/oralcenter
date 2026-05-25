<?php

namespace App\Models;

use CodeIgniter\Model;

class AlergiaModel extends Model
{
    protected $table      = 'alergia';
    protected $primaryKey = 'cod_ale';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nombre_ale',
    ];

    public function getAlergia(array $data): array
    {
        $builderTotal = $this->db->table($this->table);
        $totalRecords = $builderTotal->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['alergia'])) {
            $builderLike->like('nombre_ale', $data['alergia']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['alergia'])) {
            $builder->like('nombre_ale', $data['alergia']);
        }

        if (isset($data['orderCampo'])) {
            $builder->orderBy(
                $data['orderCampo'],
                $data['orderDireccion'] ?? 'ASC'
            );
        }

        if (isset($data['length']) && $data['length'] != -1) {
            $builder->limit(
                (int) $data['length'],
                (int) ($data['start'] ?? 0)
            );
        }

        $query = $builder->get();

        $rows = [];

        foreach ($query->getResult() as $q) {

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('alergia/editar/' . $q->cod_ale) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->cod_ale . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->cod_ale,
                $q->nombre_ale,
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

    public function agregarAlergia(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getAlergiaid(int $id): ?object
    {
        return $this->where('cod_ale', $id)
            ->first();
    }

    public function updateAlergia(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}