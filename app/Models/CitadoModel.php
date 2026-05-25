<?php

namespace App\Models;

use CodeIgniter\Model;

class CitadoModel extends Model
{
    protected $table      = 'tipo_citado';
    protected $primaryKey = 'cod_citado';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_citado',
        'esta_citado',
    ];

    public function getCitado(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['tipo_citado'])) {
            $builderLike->like('nomb_citado', $data['tipo_citado']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['tipo_citado'])) {
            $builder->like('nomb_citado', $data['tipo_citado']);
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

            if ($q->esta_citado === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_citado === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('citado/editar/' . $q->cod_citado) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->cod_citado . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->cod_citado,
                $q->nomb_citado,
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

    public function agregarCitado(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getCitadoId(int $id): ?object
    {
        return $this->where('cod_citado', $id)
            ->first();
    }

    public function updateCitado(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}