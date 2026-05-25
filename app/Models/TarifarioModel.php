<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifarioModel extends Model
{
    protected $table      = 'procedimiento';
    protected $primaryKey = 'id_procedimiento';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_tipoconcepto',
        'id_medida',
        'codi_cat',
        'nombre',
        'prec_procedimiento',
        'fecha_registro',
        'estado',
    ];

    public function getProcedimiento(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->baseQuery();

        $this->aplicarFiltros($builderLike, $data);

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->baseQuery();

        $this->aplicarFiltros($builder, $data);

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

                    <a href="' . base_url('tarifario/editar/' . $q->id_procedimiento) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->id_procedimiento . '"
                            class="anularTarifario btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->id_procedimiento,
                $q->NombreConcepto,
                $q->NombreMedida,
                $q->NombreCategoria,
                $q->nombre,
                $q->prec_procedimiento,
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

    private function baseQuery()
    {
        return $this->db->table('procedimiento')
            ->select('
                procedimiento.*,
                tipo_concepto.nombre_concepto AS NombreConcepto,
                unidad_medida.nom_medida AS NombreMedida,
                categoria.nomb_cat AS NombreCategoria
            ')
            ->join('tipo_concepto', 'procedimiento.id_tipoconcepto = tipo_concepto.id_tipoconcepto')
            ->join('unidad_medida', 'procedimiento.id_medida = unidad_medida.id_medida')
            ->join('categoria', 'procedimiento.codi_cat = categoria.codi_cat');
    }

    private function aplicarFiltros($builder, array $data): void
    {
        if (!empty($data['procedimiento'])) {
            $builder->like('procedimiento.nombre', $data['procedimiento']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builder->where('procedimiento.fecha_registro >=', $data['desde']);
            $builder->where('procedimiento.fecha_registro <=', $data['hasta']);
        }

        if (!empty($data['tipo_concepto'])) {
            $builder->where('tipo_concepto.id_tipoconcepto', $data['tipo_concepto']);
        }

        if (!empty($data['unidad_medida'])) {
            $builder->where('unidad_medida.id_medida', $data['unidad_medida']);
        }

        if (!empty($data['categoria'])) {
            $builder->where('categoria.codi_cat', $data['categoria']);
        }
    }

    public function guardarProcedimiento(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getProcedimientoId(int $id): ?object
    {
        return $this->where('id_procedimiento', $id)
            ->first();
    }

    public function updateProcedimiento(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}