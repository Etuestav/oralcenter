<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaritasModel;

class CaritasHospitalizacion extends BaseController
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
            'caritas_hospitalizacion' => $this->caritasModel->getCaritasHospitalizacion(),
        ];

        return
            view('layouts/emotiones') .
            view('iconos/emotiones_hospitalizacion', $data);
    }

    public function excelenteHospitalizacion()
    {
        return $this->guardarHospitalizacion('E');
    }

    public function buenaHospitalizacion()
    {
        return $this->guardarHospitalizacion('B');
    }

    public function regularHospitalizacion()
    {
        return $this->guardarHospitalizacion('R');
    }

    public function pesimoHospitalizacion()
    {
        return $this->guardarHospitalizacion('P');
    }

    public function maloHospitalizacion()
    {
        return $this->guardarHospitalizacion('M');
    }

    private function guardarHospitalizacion(string $tipoCarita)
    {
        $insert = $this->caritasModel->guardarHospitalizacion([
            'tipo_carita'    => $tipoCarita,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => !empty($insert),
            'id'      => $insert,
        ]);
    }
}