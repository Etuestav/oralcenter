<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table      = 'rol';
    protected $primaryKey = 'codi_rol';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_rol',
        'esta_rol',
    ];

    public function getRoles(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['rol'])) {
            $builderLike->like('nomb_rol', $data['rol']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['rol'])) {
            $builder->like('nomb_rol', $data['rol']);
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

            if ($q->esta_rol === '1') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_rol === '2') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <button data-id="' . $q->codi_rol . '"
                            class="editar-rol btn btn-primary waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#ModalEditarRol"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </button>

                    <button data-id="' . $q->codi_rol . '"
                            class="anular-rol btn btn-danger waves-effect waves-light"
                            style="padding:2px 4px;margin:0px 2px">

                        <i class="fa fa-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_rol,
                $q->nomb_rol,
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

    public function add(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }
}