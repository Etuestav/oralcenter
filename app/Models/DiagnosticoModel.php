<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagnosticoModel extends Model
{
    protected $table      = 'enfermedad';
    protected $primaryKey = 'codi_enf';

    protected $returnType = 'object';

    protected $allowedFields = [
        'desc_enf',
        'esta_enf',
    ];

    public function getDiagnostico(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->where('esta_enf', 'S')
            ->countAllResults();

        $builderLike = $this->db->table($this->table)
            ->where('esta_enf', 'S');

        if (!empty($data['enfermedad'])) {
            $builderLike->like('desc_enf', $data['enfermedad']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table)
            ->where('esta_enf', 'S');

        if (!empty($data['enfermedad'])) {
            $builder->like('desc_enf', $data['enfermedad']);
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

            if ($q->esta_enf === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_enf === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('diagnostico/editar/' . $q->codi_enf) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->codi_enf . '"
                            class="anularcie10 btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_enf,
                $q->desc_enf,
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

    public function agregarDiagnostico(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getDiagnosticoId(int $id): ?object
    {
        return $this->where('codi_enf', $id)
            ->first();
    }

    public function updateDiagnostico(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}