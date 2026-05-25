<?php

namespace App\Controllers\mantenimiento;

use App\Controllers\BaseController;
use App\Models\RecibidosModel;

class Recibidos extends BaseController
{
    protected RecibidosModel $recibidosModel;

    public function __construct()
    {
        $this->recibidosModel = new RecibidosModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'recibidos' => $this->recibidosModel->getRecibidos(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('movimientos/recibidos/list_ocurrencias', $data) .
            view('layouts/footer');
    }

    public function recibidosEdit(int $id)
    {
        $data = $this->recibidosModel->getRecibidosId($id);

        return $this->response->setJSON($data);
    }

    public function recibidoUpdate()
    {
        $data = [
            'fecha_finalizado' => $this->request->getPost('fecha_finalizado'),
            'estado'           => $this->request->getPost('estado'),
        ];

        $this->recibidosModel->recibidosUpdate(
            ['id_ocurrencia' => $this->request->getPost('id_ocurrencia')],
            $data
        );

        return $this->response->setJSON([
            'status' => true,
        ]);
    }
}
