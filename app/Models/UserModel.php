<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'codi_usu';

    protected $returnType = 'object';

    protected $allowedFields = [
        'apellido',
        'nombre',
        'logi_usu',
        'email',
        'pass_usu',
        'codi_rol',
        'esta_usu',
        'fecha_registro',
    ];

    public function getUsuario(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->baseUsuarioQuery();

        $this->aplicarFiltrosUsuario($builderLike, $data);

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->baseUsuarioQuery();

        $this->aplicarFiltrosUsuario($builder, $data);

        if (!empty($data['orderCampo'])) {
            $builder->orderBy(
                $data['orderCampo'],
                $data['orderDireccion'] ?? 'ASC'
            );
        }

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit(
                (int) $data['length'],
                (int) ($data['start'] ?? 0)
            );
        }

        $query = $builder->get();

        $rows = [];

        foreach ($query->getResult() as $q) {
            $estado = '';

            if ($q->esta_usu === '1') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_usu === '2') {
                $estado = '<label class="label label-info">Inactivo</label>';
            }

            $botones = '
                <div class="btn-footer text-center">

                    <button data-id="' . $q->codi_usu . '"
                            class="editar-usuario btn btn-primary waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#ModalEditarUsuario"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </button>

                    <button data-id="' . $q->codi_usu . '"
                            class="anular-usuario btn btn-danger waves-effect waves-light"
                            style="padding:2px 4px;margin:0px 2px">

                        <i class="fa fa-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->codi_usu,
                $q->NombreUsuario,
                $q->logi_usu,
                $q->rol,
                $q->email,
                $q->fecha_registro,
                $estado,
                $botones,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $totalRecords,
            'iTotalDisplayRecords' => $totalDisplayRecords,
            'aaData'               => $rows,
        ];
    }

    private function baseUsuarioQuery()
    {
        return $this->db->table('usuario')
            ->select('
                usuario.*,
                usuario.codi_usu,
                CONCAT(apellido, " ", nombre) AS NombreUsuario,
                logi_usu,
                nomb_rol AS rol,
                email,
                fecha_registro
            ')
            ->join('rol', 'usuario.codi_rol = rol.codi_rol');
    }

    private function aplicarFiltrosUsuario($builder, array $data): void
    {
        if (!empty($data['usuario'])) {
            $builder->like('CONCAT(apellido, " ", nombre)', $data['usuario']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builder->where('fecha_registro >=', $data['desde']);
            $builder->where('fecha_registro <=', $data['hasta']);
        }

        if (!empty($data['rol'])) {
            $builder->where('rol.codi_rol', $data['rol']);
        }
    }

    public function getUser(): array
    {
        return $this->db->table('usuario u')
            ->select('u.*, r.nomb_rol AS rol')
            ->join('rol r', 'u.codi_rol = r.codi_rol')
            ->get()
            ->getResult();
    }

    public function agregarUsuario(array $data): int|string
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }

    public function userUpdate(array $where, array $data): int
    {
        $this->db->table($this->table)
            ->where($where)
            ->update($data);

        return $this->db->affectedRows();
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function verificaLogin(string $email, string $password): object|false
    {
        $usuario = $this->where('email', $email)
            ->where('pass_usu', $password)
            ->first();

        return $usuario ?: false;
    }
}