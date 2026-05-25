<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
    protected $DBGroup = 'default';

    public function years(): array
    {
        return $this->db->table('tratamiento')
            ->select('YEAR(fecha_tra) AS year')
            ->groupBy('YEAR(fecha_tra)')
            ->orderBy('year', 'DESC')
            ->get()
            ->getResult();
    }

    public function getTrataCobrado(int|string $year): array
    {
        return $this->db->table('tratamiento')
            ->select('MONTH(fecha_tra) AS mes, SUM(total_tra) AS montos')
            ->where('fecha_tra >=', $year . '-01-01')
            ->where('fecha_tra <=', $year . '-12-31')
            ->where('estadopago_tra', '3')
            ->where('estado_tra', '1')
            ->groupBy('MONTH(fecha_tra)')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResult();
    }

    public function getTrataPorCobrar(int|string $year): array
    {
        return $this->db->table('tratamiento')
            ->select('MONTH(fecha_tra) AS mes, SUM(total_tra) AS cobrar')
            ->where('fecha_tra >=', $year . '-01-01')
            ->where('fecha_tra <=', $year . '-12-31')
            ->where('estadopago_tra', '1')
            ->where('estado_tra', '1')
            ->groupBy('MONTH(fecha_tra)')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResult();
    }

    public function rowCountPacientes(): int
    {
        return $this->db->table('paciente')
            ->where('esta_pac', 'S')
            ->countAllResults();
    }

    public function rowCountTratamientos(): int
    {
        return $this->db->table('tratamiento')
            ->where('estadopago_tra', '3')
            ->countAllResults();
    }

    public function rowCountTratamientosCobrar(): int
    {
        return $this->db->table('tratamiento')
            ->where('estadopago_tra', '1')
            ->countAllResults();
    }

    public function rowCountMedicos(): int
    {
        return $this->db->table('medico')
            ->where('esta_med', 'S')
            ->countAllResults();
    }

    public function rowCountBueno(): int
    {
        return $this->db->table('carita_admision')
            ->where('tipo_carita', 'B')
            ->countAllResults();
    }

    public function rowCountRegular(): int
    {
        return $this->db->table('carita_admision')
            ->where('tipo_carita', 'R')
            ->countAllResults();
    }

    public function rowCountPesimo(): int
    {
        return $this->db->table('carita_admision')
            ->where('tipo_carita', 'P')
            ->countAllResults();
    }

    public function rowCountMalo(): int
    {
        return $this->db->table('carita_admision')
            ->where('tipo_carita', 'M')
            ->countAllResults();
    }
}