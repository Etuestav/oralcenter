<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categoria';
    protected $primaryKey = 'codi_cat';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_cat',
        'esta_cat',
    ];

    public function getCategoria(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['categoria'])) {
            $builderLike->like('nomb_cat', $data['categoria']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['categoria'])) {
            $builder->like('nomb_cat', $data['categoria']);
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

            if ($q->esta_cat === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_cat === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('categoria/editar/' . $q->codi_cat) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->codi_cat . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_cat,
                $q->nomb_cat,
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

    public function agregarCategoria(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getCategoriaId(int $id): ?object
    {
        return $this->where('codi_cat', $id)
            ->first();
    }

    public function updateCategoria(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}