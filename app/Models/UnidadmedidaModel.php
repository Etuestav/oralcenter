<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadmedidaModel extends Model
{
    protected $table      = 'unidad_medida';
    protected $primaryKey = 'id_medida';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nom_medida',
        'estado',
    ];

    public function getMedida(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['unidad_medida'])) {
            $builderLike->like('nom_medida', $data['unidad_medida']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['unidad_medida'])) {
            $builder->like('nom_medida', $data['unidad_medida']);
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

            if ($q->estado === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->estado === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('medida/editar/' . $q->id_medida) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->id_medida . '"
                            class="anularmedida btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->id_medida,
                $q->nom_medida,
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

    public function agregarMedida(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getMedidaId(int $id): ?object
    {
        return $this->where('id_medida', $id)
            ->first();
    }

    public function updateMedida(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}