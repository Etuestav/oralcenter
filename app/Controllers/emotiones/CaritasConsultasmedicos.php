<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaritasModel;

class CaritasConsultasmedicos extends BaseController
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
            'caritas_consultasmedicos' => $this->caritasModel->getCaritasMedico(),
        ];

        return
            view('layouts/emotiones') .
            view('iconos/emotiones_medicos', $data);
    }

    public function excelenteMedico()
    {
        return $this->guardarMedico('E');
    }

    public function buenaMedico()
    {
        return $this->guardarMedico('B');
    }

    public function regularMedico()
    {
        return $this->guardarMedico('R');
    }

    public function pesimoMedico()
    {
        return $this->guardarMedico('P');
    }

    public function maloMedico()
    {
        return $this->guardarMedico('M');
    }

    private function guardarMedico(string $tipoCarita)
    {
        $data = [
            'tipo_carita'    => $tipoCarita,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ];

        $insert = $this->caritasModel->guardarMedico($data);

        return $this->response->setJSON([
            'success' => !empty($insert),
            'id'      => $insert,
        ]);
    }
}