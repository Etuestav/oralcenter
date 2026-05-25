<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'codi_usu';

    protected $returnType = 'object';

    public function login(string $username, string $password): object|false
    {
        $resultado = $this->where('logi_usu', $username)
            ->where('pass_usu', $password)
            ->where('esta_usu', '1')
            ->first();

        return $resultado ?: false;
    }
}