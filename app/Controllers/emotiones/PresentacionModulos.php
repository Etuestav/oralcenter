<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PresentacionModulos extends BaseController
{
    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/emotiones') .
            view('iconos/tipo_modulos') .
            view('layouts/footer');
    }
}