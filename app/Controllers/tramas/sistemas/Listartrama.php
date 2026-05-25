<?php

namespace App\Controllers\Tramas\Sistemas;

use App\Controllers\BaseController;
use App\Models\ListartramasModel;

class Listartrama extends BaseController
{
    protected ListartramasModel $listarTramasModel;

    public function __construct()
    {
        $this->listarTramasModel = new ListartramasModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'listar' => $this->listarTramasModel->getListar(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/sistemas/listartramas', $data) .
            view('layouts/footer');
    }

    public function listarEdit(int $id)
    {
        $data = $this->listarTramasModel->getListarId($id);

        return $this->response->setJSON($data);
    }

    public function listarUpdate()
    {
        $data = [
            'cantidad_factura' => $this->request->getPost('cantidad_factura'),
            'fecha_solucionado' => $this->request->getPost('fecha_solucionado'),
            'estado' => $this->request->getPost('estado'),
        ];

        $this->listarTramasModel->tramasUpdate(
            ['secuencia' => $this->request->getPost('secuencia')],
            $data
        );

        return $this->response->setJSON([
            'status' => true,
        ]);
    }
}
