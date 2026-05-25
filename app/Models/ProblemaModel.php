<?php

namespace App\Models;

use CodeIgniter\Model;

class ProblemaModel extends Model
{
    protected $table      = 'tipo_problema';
    protected $primaryKey = 'id_tipo_problema';

    protected $returnType = 'object';

    protected $allowedFields = [
        'tipo_nombre',
        'estado',
    ];

    public function getProblema(): array
    {
        return $this->where('estado', '1')
            ->findAll();
    }

    public function add(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function getProblemaId(int $id): ?object
    {
        return $this->where('id_tipo_problema', $id)
            ->first();
    }

    public function problemaUpdate(array $where, array $data): int
    {
        $this->db->table($this->table)
            ->where($where)
            ->update($data);

        return $this->db->affectedRows();
    }

    public function updateProblema(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}