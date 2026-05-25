<?php

namespace App\Models;

use CodeIgniter\Model;

class BackendModel extends Model
{
    protected $DBGroup = 'default';

    /**
     * Obtener menú por link
     */
    public function getID(string $link): ?object
    {
        return $this->db->table('menus')
            ->like('link', $link)
            ->get()
            ->getRow();
    }

    /**
     * Obtener permisos por menú y rol
     */
    public function getPermisos(
        int $menu,
        int $rol
    ): ?object {
        return $this->db->table('permisos')
            ->where('id_menu', $menu)
            ->where('codi_rol', $rol)
            ->get()
            ->getRow();
    }
}