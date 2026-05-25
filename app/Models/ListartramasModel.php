<?php

namespace App\Models;

use CodeIgniter\Model;

class ListartramasModel extends Model
{
    protected $table      = 'proceso_tramas';
    protected $primaryKey = 'secuencia';

    protected $returnType = 'object';

    protected $allowedFields = [
        'lote',
        'cantidad_factura',
        'id_usuario',
        'id_area',
        'fecha_llegada',
        'fecha_solucionado',
        'estado',
    ];

    public function getListar(): array
    {
        return $this->db->table('proceso_tramas p')
            ->select('
                p.secuencia,
                p.lote,
                p.cantidad_factura,
                CONCAT(u.apellidos, " ", u.nombres) AS usuario,
                a.nombre_area,
                p.fecha_llegada,
                p.fecha_solucionado,
                p.estado
            ')
            ->join('usuario u', 'p.id_usuario = u.id_usuario')
            ->join('area a', 'p.id_area = a.id_area')
            ->where('p.estado !=', 'S')
            ->orderBy('p.secuencia', 'DESC')
            ->get()
            ->getResult();
    }

    public function getListarId(int $id): ?object
    {
        return $this->where('secuencia', $id)
            ->first();
    }

    public function tramasUpdate(array $where, array $data): int
    {
        $this->db->table($this->table)
            ->where($where)
            ->update($data);

        return $this->db->affectedRows();
    }

    public function updateTramas(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}