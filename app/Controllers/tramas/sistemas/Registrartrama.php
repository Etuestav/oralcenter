<?php

namespace App\Controllers\Tramas\Sistemas;

use App\Controllers\BaseController;
use App\Models\AreasModel;
use App\Models\UserModel;
use App\Models\RegistrartramasistemasModel;

class Registrartrama extends BaseController
{
    protected AreasModel $areasModel;
    protected UserModel $userModel;
    protected RegistrartramasistemasModel $registrarTramasModel;

    public function __construct()
    {
        $this->areasModel          = new AreasModel();
        $this->userModel           = new UserModel();
        $this->registrarTramasModel = new RegistrartramasistemasModel();

        date_default_timezone_set('America/Lima');
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'user' => $this->userModel->getUser(),
            'area' => $this->areasModel->getAreas(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/sistemas/registrartramas', $data) .
            view('layouts/footer');
    }

    public function insertar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $datos = [
            'secuencia'          => $this->request->getPost('secuencia'),
            'id_usuario'        => $this->request->getPost('id_usuario'),
            'id_area'           => $this->request->getPost('id_area'),
            'lote'              => $this->request->getPost('lote'),
            'aseguradora'       => $this->request->getPost('aseguradora'),
            'cantidad_factura'  => $this->request->getPost('cantidad_factura'),
            'fecha_llegada'     => date('Y-m-d H:i:s'),
            'fecha_solucionado' => $this->request->getPost('fecha_solucionado'),
            'observacion'       => $this->request->getPost('observacion'),
            'estado'            => 'P',
        ];

        $insert = $this->registrarTramasModel->insertar(
            'proceso_tramas',
            $datos
        );

        if (!empty($insert)) {
            return redirect()->to(base_url('tramas/sistemas/registrartrama'));
        }

        session()->setFlashdata('error', 'No se puede guardar la información');

        return redirect()->to(base_url('tramas/sistemas/registrartrama'));
    }
}