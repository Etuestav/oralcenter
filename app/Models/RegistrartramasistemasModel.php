<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrartramasistemasModel extends Model
{
    protected $returnType = 'object';

    /**
     * Insertar registros dinámicamente
     */
    public function insertar(string $tabla, array $data): int|string
    {
        $this->db->table($tabla)->insert($data);

        return $this->db->insertID();
    }

    /*
    // Ejemplo si deseas recuperar registros posteriormente

    public function getArchivos(): array
    {
        return $this->db->table('ocurrencia')
            ->get()
            ->getResultArray();
    }
    */
}