<?php

namespace App\Models;

use CodeIgniter\Model;

class PacientesModel extends Model
{
    protected $table      = 'paciente';
    protected $primaryKey = 'codi_pac';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_pac',
        'apel_pac',
        'edad_pac',
        'dni_pac',
        'dire_pac',
        'fecha_registro',
        'esta_pac',
    ];

    public function getPaciente(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        $builderLike->select('
            paciente.*,
            CONCAT(nomb_pac, " ", apel_pac) AS NombrePaciente
        ');

        if (!empty($data['paciente'])) {
            $builderLike->like('CONCAT(nomb_pac, " ", apel_pac)', $data['paciente']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builderLike->where('fecha_registro >=', $data['desde']);
            $builderLike->where('fecha_registro <=', $data['hasta']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        $builder->select('
            paciente.*,
            CONCAT(nomb_pac, " ", apel_pac) AS NombrePaciente
        ');

        if (!empty($data['paciente'])) {
            $builder->like('CONCAT(nomb_pac, " ", apel_pac)', $data['paciente']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builder->where('fecha_registro >=', $data['desde']);
            $builder->where('fecha_registro <=', $data['hasta']);
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

            if ($q->esta_pac === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_pac === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('paciente/editar/' . $q->codi_pac) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->codi_pac . '"
                            class="anular-paciente btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_pac,
                $q->NombrePaciente,
                $q->edad_pac,
                $q->dni_pac,
                $q->dire_pac,
                $q->fecha_registro,
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

    public function getPacientesId(int $id): ?object
    {
        return $this->where('codi_pac', $id)
            ->first();
    }

    public function agregarPaciente(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function updatePaciente(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}