<?php

namespace App\Models;

use CodeIgniter\Model;

class EspecialidadModel extends Model
{
    protected $table      = 'especialidad';
    protected $primaryKey = 'cod_especialidad';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nombre_especialidad',
        'descripcion_especialidad',
        'estado_especialidad',
    ];

    public function getEspecialidad(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['especialidad'])) {
            $builderLike->like('nombre_especialidad', $data['especialidad']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['especialidad'])) {
            $builder->like('nombre_especialidad', $data['especialidad']);
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

            if ($q->estado_especialidad === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->estado_especialidad === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">
                    <a href="' . base_url('especialidad/editar/' . $q->cod_especialidad) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">
                        <i class="fa fa-edit"></i>
                    </a>

                    <button data-id="' . $q->cod_especialidad . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                </div>
            ';

            $rows[] = [
                $q->cod_especialidad,
                $q->nombre_especialidad,
                $q->descripcion_especialidad,
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

    public function agregarEspecialidad(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getEspecialidadId(int $id): ?object
    {
        return $this->where('cod_especialidad', $id)
            ->first();
    }

    public function updateEspecialidad(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}