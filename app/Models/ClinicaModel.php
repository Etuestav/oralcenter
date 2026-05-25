<?php

namespace App\Models;

use CodeIgniter\Model;

class ClinicaModel extends Model
{
    protected $table      = 'clinica';
    protected $primaryKey = 'id_clin';

    protected $returnType = 'object';

    protected $allowedFields = [
        'nomb_clin',
        'direc_clin',
        'telf_clin',
        'email_clin',
        'ruc_clin',
        'fecha_clin',
        'photo',
    ];

    protected array $columnOrder = [
        'nomb_clin',
        'direc_clin',
        'telf_clin',
        'email_clin',
        'ruc_clin',
        'fecha_clin',
        null
    ];

    protected array $columnSearch = [
        'nomb_clin'
    ];

    protected array $order = [
        'id_clin' => 'DESC'
    ];

    /**
     * Datatables Query
     */
    private function getDatatablesQuery(): void
    {
        $builder = $this->builder();

        $searchValue = $_POST['search']['value'] ?? null;

        if ($searchValue) {

            $builder->groupStart();

            foreach ($this->columnSearch as $index => $item) {

                if ($index === 0) {
                    $builder->like($item, $searchValue);
                } else {
                    $builder->orLike($item, $searchValue);
                }

            }

            $builder->groupEnd();

        }

        if (isset($_POST['order'])) {

            $columnIndex = $_POST['order'][0]['column'];
            $columnDir   = $_POST['order'][0]['dir'];

            $builder->orderBy(
                $this->columnOrder[$columnIndex],
                $columnDir
            );

        } else {

            foreach ($this->order as $key => $value) {
                $builder->orderBy($key, $value);
            }

        }
    }

    /**
     * Obtener datos DataTables
     */
    public function getDatatables(): array
    {
        $this->getDatatablesQuery();

        $builder = $this->builder();

        $length = $_POST['length'] ?? 10;
        $start  = $_POST['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResult();
    }

    /**
     * Contar registros filtrados
     */
    public function countFiltered(): int
    {
        $this->getDatatablesQuery();

        return $this->builder()
            ->countAllResults(false);
    }

    /**
     * Contar todos
     */
    public function countAllResultsData(): int
    {
        return $this->builder()
            ->countAllResults();
    }

    public function countAllClinicas(): int
    {
        return $this->countAllResultsData();
    }

    /**
     * Obtener por ID
     */
    public function getById(int $id): ?object
    {
        return $this->where('clinica.id_clin', $id)
            ->first();
    }

    /**
     * Actualizar registro
     */
    public function updateData(array $where, array $data): bool
    {
        return $this->where($where)
            ->set($data)
            ->update();
    }

    /**
     * Obtener clínica
     */
    public function getClinica(): ?object
    {
        return $this->where('clinica.id_clin', 1)
            ->first();
    }

    /**
     * Obtener planes
     */
    public function getPlanes(): ?object
    {
        return $this->select('c.*, p.nomb_plan AS planes')
            ->from('clinica c')
            ->join('planes p', 'c.cod_plan = p.cod_plan')
            ->where('c.id_clin', 1)
            ->get()
            ->getRow();
    }

    /**
     * Obtener rol del usuario
     */
    public function getUserRol(int $usuario): ?object
    {
        return $this->db->table('usuario u')
            ->select('r.codi_rol, r.nomb_rol AS nombrerol')
            ->join('rol r', 'u.codi_rol = r.codi_rol')
            ->where('u.codi_usu', $usuario)
            ->get()
            ->getRow();
    }
}
