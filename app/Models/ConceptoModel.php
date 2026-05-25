<?php

namespace App\Models;

use CodeIgniter\Model;

class ConceptoModel extends Model
{
    protected $table      = 'tipo_concepto';
    protected $primaryKey = 'id_tipoconcepto';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nombre_concepto',
        'estado_tipo',
    ];

    public function getConcepto(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['tipo_concepto'])) {
            $builderLike->like('nombre_concepto', $data['tipo_concepto']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['tipo_concepto'])) {
            $builder->like('nombre_concepto', $data['tipo_concepto']);
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

            if ($q->estado_tipo === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->estado_tipo === 'N') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('concepto/editar/' . $q->id_tipoconcepto) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->id_tipoconcepto . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->id_tipoconcepto,
                $q->nombre_concepto,
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

    public function agregarConcepto(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getConceptoId(int $id): ?object
    {
        return $this->where('id_tipoconcepto', $id)
            ->first();
    }

    public function updateConcepto(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}