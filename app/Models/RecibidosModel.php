<?php

namespace App\Models;

use CodeIgniter\Model;

class RecibidosModel extends Model
{
    protected $table      = 'ocurrencia';
    protected $primaryKey = 'id_ocurrencia';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_usuario',
        'id_area',
        'fecha_problema',
        'fecha_registro',
        'fecha_finalizado',
        'estado',
        'mensaje',
        'id_tipo_problema',
        'id_tipo_documento',
    ];

    public function getRecibidos(): array
    {
        return $this->db->table('ocurrencia o')
            ->select('
                o.id_ocurrencia,
                CONCAT(u.apellidos, " ", u.nombres) AS usuario,
                a.nombre_area,
                o.fecha_problema,
                o.fecha_registro,
                o.fecha_finalizado,
                o.estado
            ')
            ->join('usuario u', 'o.id_usuario = u.id_usuario')
            ->join('area a', 'o.id_area = a.id_area')
            ->orderBy('o.id_ocurrencia', 'DESC')
            ->get()
            ->getResult();
    }

    public function getRecibidosId(int $id): ?object
    {
        return $this->where('id_ocurrencia', $id)
            ->first();
    }

    public function recibidosUpdate(array $where, array $data): int
    {
        $this->db->table($this->table)
            ->where($where)
            ->update($data);

        return $this->db->affectedRows();
    }

    public function updateRecibidos(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}