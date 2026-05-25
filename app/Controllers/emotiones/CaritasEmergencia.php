<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaritasModel;

class CaritasEmergencia extends BaseController
{
    protected CaritasModel $caritasModel;

    public function __construct()
    {
        $this->caritasModel = new CaritasModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'caritas_emergencia' => $this->caritasModel->getCaritasEmergencia(),
        ];

        return
            view('layouts/emotiones') .
            view('iconos/emotiones_emergencia', $data);
    }

    public function excelenteEmergencia()
    {
        return $this->guardarEmergencia('E');
    }

    public function buenaEmergencia()
    {
        return $this->guardarEmergencia('B');
    }

    public function regularEmergencia()
    {
        return $this->guardarEmergencia('R');
    }

    public function pesimoEmergencia()
    {
        return $this->guardarEmergencia('P');
    }

    public function maloEmergencia()
    {
        return $this->guardarEmergencia('M');
    }

    private function guardarEmergencia(string $tipoCarita)
    {
        $insert = $this->caritasModel->guardarEmergencia([
            'tipo_carita'    => $tipoCarita,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => !empty($insert),
            'id'      => $insert,
        ]);
    }
}