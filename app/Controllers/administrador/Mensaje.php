<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\Modelgeneral;

class Mensaje extends BaseController
{
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->modelGeneral = new Modelgeneral();
    }

    public function index(): string
    {
        $data['mensaje'] = $this->modelGeneral->getTableWhereRow(
            'mensaje',
            ['id' => 1]
        );

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/mensaje/formulario', $data) .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'titulo'    => 'required',
            'contenido' => 'required',
            'activo'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'titulo'    => $this->request->getPost('titulo'),
            'contenido' => $this->request->getPost('contenido'),
            'activo'    => $this->request->getPost('activo'),
        ];

        $edit = $this->modelGeneral->editRegist(
            'mensaje',
            ['id' => 1],
            $data
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }
}