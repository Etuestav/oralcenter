<?php

namespace App\Models;

use CodeIgniter\Model;

class CaritasModel extends Model
{
    protected $DBGroup = 'default';

    public function getCaritas(): array
    {
        return $this->db->table('carita_admision')
            ->get()
            ->getResultArray();
    }

    public function getCaritasEmergencia(): array
    {
        return $this->db->table('carita_emergencia')
            ->get()
            ->getResultArray();
    }

    public function getCaritasMedico(): array
    {
        return $this->db->table('carita_atencion_medico')
            ->get()
            ->getResultArray();
    }

    public function getCaritasFarmacia(): array
    {
        return $this->db->table('carita_farmacia')
            ->get()
            ->getResultArray();
    }

    public function getCaritasHospitalizacion(): array
    {
        return $this->db->table('carita_hospitalizacion')
            ->get()
            ->getResultArray();
    }

    public function guardarAdmision(array $data): int|string
    {
        $this->db->table('carita_admision')->insert($data);

        return $this->db->insertID();
    }

    public function guardarEmergencia(array $data): int|string
    {
        $this->db->table('carita_emergencia')->insert($data);

        return $this->db->insertID();
    }

    public function guardarMedico(array $data): int|string
    {
        $this->db->table('carita_atencion_medico')->insert($data);

        return $this->db->insertID();
    }

    public function guardarFarmacia(array $data): int|string
    {
        $this->db->table('carita_farmacia')->insert($data);

        return $this->db->insertID();
    }

    public function guardarHospitalizacion(array $data): int|string
    {
        $this->db->table('carita_hospitalizacion')->insert($data);

        return $this->db->insertID();
    }
}