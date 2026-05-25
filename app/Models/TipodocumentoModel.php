<?php

namespace App\Models;

use CodeIgniter\Model;

class TipodocumentoModel extends Model
{
    protected $table      = 'tipo_documento';
    protected $primaryKey = 'cod_tipodocumento';

    protected $returnType = 'object';

    protected $allowedFields = [
        'descripcion',
        'abreviatura',
        'serie',
        'inicio',
        'fin',
        'correlativo_actual',
        'estado',
    ];

    public function getDocumento(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table($this->table);

        if (!empty($data['tipo_documento'])) {
            $builderLike->like('descripcion', $data['tipo_documento']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table($this->table);

        if (!empty($data['tipo_documento'])) {
            $builder->like('descripcion', $data['tipo_documento']);
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

            if ((int) $q->estado === 1) {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ((int) $q->estado === 2) {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <button data-id="' . $q->cod_tipodocumento . '"
                            class="editar-tipodocumento btn btn-primary waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#ModalEditarTipoDocumento"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </button>

                    <button data-id="' . $q->cod_tipodocumento . '"
                            class="anular btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->descripcion,
                $q->abreviatura,
                $q->serie,
                $q->inicio,
                $q->fin,
                $q->correlativo_actual,
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

    public function agregarDocumento(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getDocumentoId(int $id): ?object
    {
        return $this->where('cod_tipodocumento', $id)
            ->first();
    }

    public function updateDocumento(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}