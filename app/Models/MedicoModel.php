<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicoModel extends Model
{
    protected $table      = 'medico';
    protected $primaryKey = 'codi_med';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_med',
        'apel_med',
        'dni_med',
        'coleg_med',
        'fecha_registro',
        'cod_especialidad',
        'esta_med',
        'codi_usu',
    ];

    public function getMedico(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table('medico');

        $builderLike->select('
                medico.*,
                CONCAT(nomb_med, " ", apel_med) AS NombresApellidos,
                nombre_especialidad AS NombreEspecialidad
            ')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad');

        if (!empty($data['medico'])) {
            $builderLike->like('CONCAT(nomb_med, " ", apel_med)', $data['medico']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builderLike->where('fecha_registro >=', $data['desde']);
            $builderLike->where('fecha_registro <=', $data['hasta']);
        }

        if (!empty($data['especialidad'])) {
            $builderLike->where('especialidad.cod_especialidad', $data['especialidad']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table('medico');

        $builder->select('
                medico.*,
                CONCAT(nomb_med, " ", apel_med) AS NombresApellidos,
                nombre_especialidad AS NombreEspecialidad
            ')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad');

        if (!empty($data['medico'])) {
            $builder->like('CONCAT(nomb_med, " ", apel_med)', $data['medico']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builder->where('fecha_registro >=', $data['desde']);
            $builder->where('fecha_registro <=', $data['hasta']);
        }

        if (!empty($data['especialidad'])) {
            $builder->where('especialidad.cod_especialidad', $data['especialidad']);
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

            if ($q->esta_med === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_med === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('medico/editar/' . $q->codi_med) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->codi_med . '"
                            class="anular-medico btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_med,
                $q->NombresApellidos,
                $q->NombreEspecialidad,
                $q->dni_med,
                $q->coleg_med,
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

    public function guardarMedico(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getMedicoId(int $id): ?object
    {
        return $this->where('codi_med', $id)
            ->first();
    }

    public function updateMedico(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}