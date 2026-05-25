<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesModel extends Model
{
    protected $table      = 'carita_admision';
    protected $returnType = 'object';

    public function years(): array
    {
        return $this->db->table($this->table)
            ->select('YEAR(fecha_registro) AS year')
            ->groupBy('YEAR(fecha_registro)')
            ->orderBy('year', 'DESC')
            ->get()
            ->getResult();
    }

    public function total(int|string $year): array
    {
        return $this->db->table($this->table)
            ->select('MONTH(fecha_registro) AS mes, COUNT(tipo_carita) AS totalcarita')
            ->where('fecha_registro >=', $year . '-01-01')
            ->where('fecha_registro <=', $year . '-12-31')
            ->groupBy('MONTH(fecha_registro)')
            ->orderBy('mes', 'ASC')
            ->get()
            ->getResult();
    }

    public function rowCount(): int
    {
        return $this->db->table($this->table)
            ->where('tipo_carita', 'E')
            ->countAllResults();
    }

    public function rowCountBueno(): int
    {
        return $this->db->table($this->table)
            ->where('tipo_carita', 'B')
            ->countAllResults();
    }

    public function rowCountRegular(): int
    {
        return $this->db->table($this->table)
            ->where('tipo_carita', 'R')
            ->countAllResults();
    }

    public function rowCountPesimo(): int
    {
        return $this->db->table($this->table)
            ->where('tipo_carita', 'P')
            ->countAllResults();
    }

    public function rowCountMalo(): int
    {
        return $this->db->table($this->table)
            ->where('tipo_carita', 'M')
            ->countAllResults();
    }
}