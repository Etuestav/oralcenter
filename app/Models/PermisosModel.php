<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisosModel extends Model
{
    protected $table      = 'permisos';
    protected $primaryKey = 'id_permiso';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_menu',
        'codi_rol',
        'read',
        'insert',
        'update',
        'delete',
    ];

    public function getPerm(array $data): array
    {
        $totalRecords = $this->db
            ->table($this->table)
            ->countAllResults();

        $builderLike = $this->db->table('permisos');

        $builderLike->select('
                permisos.*,
                menus.nombre AS NombreMenu,
                rol.nomb_rol AS NombreRol
            ')
            ->join('menus', 'permisos.id_menu = menus.id_menu')
            ->join('rol', 'permisos.codi_rol = rol.codi_rol');

        if (!empty($data['menus'])) {
            $builderLike->where('menus.id_menu', $data['menus']);
        }

        if (!empty($data['rol'])) {
            $builderLike->where('rol.codi_rol', $data['rol']);
        }

        $totalDisplayRecords = $builderLike->countAllResults();

        $builder = $this->db->table('permisos');

        $builder->select('
                permisos.*,
                menus.nombre AS NombreMenu,
                rol.nomb_rol AS NombreRol
            ')
            ->join('menus', 'permisos.id_menu = menus.id_menu')
            ->join('rol', 'permisos.codi_rol = rol.codi_rol');

        if (!empty($data['menus'])) {
            $builder->where('menus.id_menu', $data['menus']);
        }

        if (!empty($data['rol'])) {
            $builder->where('rol.codi_rol', $data['rol']);
        }

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
            $estadoread = ((int) $q->read === 1)
                ? '<span style="color:#47A728;" class="fa fa-check"></span>'
                : '<span style="color:#F80C0C;" class="fa fa-times"></span>';

            $estadoinsert = ((int) $q->insert === 1)
                ? '<span style="color:#47A728;" class="fa fa-check"></span>'
                : '<span style="color:#F80C0C;" class="fa fa-times"></span>';

            $estadoupdate = ((int) $q->update === 1)
                ? '<span style="color:#47A728;" class="fa fa-check"></span>'
                : '<span style="color:#F80C0C; text-align:center;" class="fa fa-times"></span>';

            $estadodelete = ((int) $q->delete === 1)
                ? '<span style="color:#47A728;" class="fa fa-check"></span>'
                : '<span style="color:#F80C0C;" class="fa fa-times"></span>';

            $botones = '
                <div class="btn-footer text-center">

                    <a href="' . base_url('administrador/permisos/edit/' . $q->id_permiso) . '"
                       class="btn btn-primary"
                       style="padding:2px 5px;margin:0px 2px">

                        <i class="fa fa-edit"></i>

                    </a>

                    <button data-id="' . $q->id_permiso . '"
                            class="anular-permiso btn btn-danger"
                            style="padding:2px 5px;margin:0px 2px">

                        <i class="glyphicon glyphicon-trash"></i>

                    </button>

                </div>
            ';

            $rows[] = [
                $q->id_permiso,
                $q->NombreMenu,
                $q->NombreRol,
                $estadoread,
                $estadoinsert,
                $estadoupdate,
                $estadodelete,
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

    public function getPermisos(): array
    {
        return $this->db->table('permisos p')
            ->select('p.*, m.nombre AS menu, r.nomb_rol AS roles')
            ->join('rol r', 'p.codi_rol = r.codi_rol')
            ->join('menus m', 'p.id_menu = m.id_menu')
            ->get()
            ->getResult();
    }

    public function getMenus(): array
    {
        return $this->db->table('menus')
            ->get()
            ->getResult();
    }

    public function savePermiso(array $data): bool
    {
        return $this->insert($data) !== false;
    }

    public function getPermisoPorRolMenu(int|string $rol, int|string $menu): ?object
    {
        return $this->where('codi_rol', $rol)
            ->where('id_menu', $menu)
            ->first();
    }

    public function getPermiso(int $id): ?object
    {
        return $this->where('id_permiso', $id)
            ->first();
    }

    public function updatePermiso(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deletePermiso(int $id): bool
    {
        return $this->delete($id);
    }
}
