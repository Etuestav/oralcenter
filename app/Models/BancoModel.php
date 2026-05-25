<?php

namespace App\Models;

use CodeIgniter\Model;

class BancoModel extends Model
{
    protected $table      = 'banco';
    protected $primaryKey = 'cod_banco';

    protected $returnType = 'object';

    protected $allowedFields = [
        'descripcion',
        'estado',
    ];

    public function getBanco(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['banco'])) {
            $builderLike->like('descripcion', $data['banco']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['banco'])) {
            $builder->like('descripcion', $data['banco']);
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

                    <a href="' . base_url('banco/editar/' . $q->cod_banco) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->cod_banco . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->cod_banco,
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

    public function agregarBanco(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getBancoid(int $id): ?object
    {
        return $this->where('cod_banco', $id)
            ->first();
    }

    public function updateBanco(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}