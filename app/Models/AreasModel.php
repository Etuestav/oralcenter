<?php

namespace App\Models;

use CodeIgniter\Model;

class AreasModel extends Model
{
    protected $table      = 'area';
    protected $primaryKey = 'id_area';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nombre_area',
        'descripcion_area',
        'estado',
    ];

    protected $useTimestamps = true;

    public function getAreas(): array
    {
        return $this->where('estado', '1')
            ->findAll();
    }

    public function getAreaId(int $id): ?object
    {
        return $this->where('id_area', $id)
            ->first();
    }

    public function areaUpdate(array $where, array $data): int
    {
        $this->where($where)
            ->set($data)
            ->update();

        return $this->db->affectedRows();
    }

    public function updateArea(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function add(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }
}
