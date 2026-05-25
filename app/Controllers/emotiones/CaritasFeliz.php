<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaritasModel;

class Caritasfeliz extends BaseController
{
    protected CaritasModel $caritasModel;

    public function __construct()
    {
        $this->caritasModel = new CaritasModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data = [
            'caritas' => $this->caritasModel->getCaritas(),
        ];

        return
            view('layouts/emotiones') .
            view('iconos/emotiones', $data);
    }

    public function excelenteAdmision()
    {
        return $this->guardarAdmision('E');
    }

    public function buenaAdmision()
    {
        return $this->guardarAdmision('B');
    }

    public function regularAdmision()
    {
        return $this->guardarAdmision('R');
    }

    public function pesimoAdmision()
    {
        return $this->guardarAdmision('P');
    }

    public function maloAdmision()
    {
        return $this->guardarAdmision('M');
    }

    private function guardarAdmision(string $tipoCarita)
    {
        $insert = $this->caritasModel->guardarAdmision([
            'tipo_carita'    => $tipoCarita,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => !empty($insert),
            'id'      => $insert,
        ]);
    }
}