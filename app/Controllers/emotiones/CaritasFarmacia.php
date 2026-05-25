<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaritasModel;

class CaritasFarmacia extends BaseController
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
            'caritas_farmacia' => $this->caritasModel->getCaritasFarmacia(),
        ];

        return
            view('layouts/emotiones') .
            view('iconos/emotiones_farmacia', $data);
    }

    public function excelenteFarmacia()
    {
        return $this->guardarFarmacia('E');
    }

    public function buenaFarmacia()
    {
        return $this->guardarFarmacia('B');
    }

    public function regularFarmacia()
    {
        return $this->guardarFarmacia('R');
    }

    public function pesimoFarmacia()
    {
        return $this->guardarFarmacia('P');
    }

    public function maloFarmacia()
    {
        return $this->guardarFarmacia('M');
    }

    private function guardarFarmacia(string $tipoCarita)
    {
        $insert = $this->caritasModel->guardarFarmacia([
            'tipo_carita'    => $tipoCarita,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => !empty($insert),
            'id'      => $insert,
        ]);
    }
}