<?php

namespace App\Models;

use CodeIgniter\Model;

class OcurrenciasModel extends Model
{
    protected $DBGroup = 'default';

    public function insertar(string $tabla, array $data): int|string
    {
        $this->db->table($tabla)->insert($data);

        return $this->db->insertID();
    }

    public function getArchivos(): array
    {
        return $this->db->table('ocurrencia')
            ->get()
            ->getResultArray();
    }
}