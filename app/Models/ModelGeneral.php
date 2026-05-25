<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelgeneral extends Model
{
    protected $DBGroup = 'default';

    public function getTable($table, $select = null, $orderCamp = null, $order = null): array
    {
        $builder = $this->db->table($table);

        if ($select !== null) {
            $builder->select($select);
        }

        if ($orderCamp !== null) {
            $builder->orderBy($orderCamp, $order);
        }

        return $builder->get()->getResult();
    }

    public function getTableLocacionesAeropuerto(): array
    {
        return $this->db->table('locaciones')
            ->join('aeropuertos', 'locaciones.AerId = aeropuertos.AerId')
            ->get()
            ->getResult();
    }

    public function getTableWhere($table, $wheres = null, $select = null): array
    {
        $builder = $this->db->table($table);

        if ($select !== null) {
            $builder->select($select);
        }

        if ($wheres !== null) {
            $builder->where($wheres);
        }

        return $builder->get()->getResult();
    }

    public function getTableWhereRow($table, $wheres = null, $select = null): ?object
    {
        $builder = $this->db->table($table);

        if ($select !== null) {
            $builder->select($select);
        }

        if ($wheres !== null) {
            $builder->where($wheres);
        }

        return $builder->get()->getRow();
    }

    public function getTableWhereRowArray($table, $wheres = null, $select = null): ?array
    {
        $builder = $this->db->table($table);

        if ($select !== null) {
            $builder->select($select);
        }

        if ($wheres !== null) {
            $builder->where($wheres);
        }

        return $builder->get()->getRowArray();
    }

    public function getTableLikeRow($table, $likes = null, $select = null): ?object
    {
        $builder = $this->db->table($table);

        if ($select !== null) {
            $builder->select($select);
        }

        if ($likes !== null) {
            $builder->like($likes);
        }

        return $builder->get()->getRow();
    }

    public function getTableLimit(
        $table,
        int $cant,
        int $indice,
        $id = null,
        $orden = null
    ): array {
        $builder = $this->db->table($table);

        if (!empty($id) && !empty($orden)) {
            $builder->orderBy($id, $orden);
        }

        return $builder
            ->limit($cant, $indice)
            ->get()
            ->getResult();
    }

    public function getTableLimitWhere(
        $table,
        int $cant,
        int $indice,
        $campo,
        $valor,
        $id = null,
        $orden = null
    ): array {
        $builder = $this->db->table($table);

        if (!empty($id) && !empty($orden)) {
            $builder->orderBy($id, $orden);
        }

        if (!empty($campo) && !empty($valor)) {
            $builder->where($campo, $valor);
        }

        return $builder
            ->limit($cant, $indice)
            ->get()
            ->getResult();
    }

    public function getTotalRows($table): int
    {
        return $this->db->table($table)->countAllResults();
    }

    public function getTotalRowsWhere($table, $campo, $valor): int
    {
        return $this->db->table($table)
            ->where($campo, $valor)
            ->countAllResults();
    }

    public function insertRegist($table, array $data): ?int
    {
        $builder = $this->db->table($table);

        $builder->insert($data);

        if ($this->db->affectedRows() === 1) {
            return $this->db->insertID();
        }

        return null;
    }

    public function editRegist($table, array $wheres, array $data): bool
    {
        $builder = $this->db->table($table);

        $builder->where($wheres);
        $builder->update($data);

        return true;
    }

    public function deleteRegist($table, array $wheres): bool
    {
        $builder = $this->db->table($table);

        $builder->where($wheres);
        $builder->delete();

        return $this->db->affectedRows() > 0;
    }

    public function getTableWhereNotIn($table, $campo, array $array): array
    {
        return $this->db->table($table)
            ->whereNotIn($campo, $array)
            ->get()
            ->getResult();
    }

    public function getAdministrador(): array
    {
        $session = session();

        return $this->db->table('usuarios')
            ->where('EmpId', $session->get('IdEmpresa'))
            ->groupStart()
                ->where('usuarios.UsrTipo', SUPER_USUARIO)
                ->orWhere('usuarios.UsrTipo', ADMINISTRADOR)
                ->orWhere('usuarios.UsrTipo', SUPERVISOR_GENERAL)
            ->groupEnd()
            ->get()
            ->getResult();
    }

    public function obtenerPermiso(string $campo)
    {
        $session = session();

        $query = $this->db->table('permisos')
            ->select($campo)
            ->where('UsrId', $session->get('IdUsuario'))
            ->get()
            ->getRow();

        return $query->$campo ?? null;
    }

    public function verificaUnico(
        string $tabla,
        string $campo,
        $valor,
        $idCampo = null,
        $idValor = null
    ): bool {
        $builder = $this->db->table($tabla);

        $builder->where($campo, $valor);

        if ($idCampo !== null) {
            $builder->where($idCampo . ' !=', $idValor);
        }

        return $builder->countAllResults() === 0;
    }
}