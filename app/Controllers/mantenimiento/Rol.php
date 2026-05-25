<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\Modelgeneral;

class Rol extends BaseController
{
    protected RolesModel $rolesModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->rolesModel   = new RolesModel();
        $this->modelGeneral = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'roles' => $this->modelGeneral->getTable('rol'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/rol/list_rol', $data) .
            view('layouts/footer');
    }

    public function jsonRol()
    {
        $columns = [
            'codi_rol',
            'nomb_rol',
            'esta_rol',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_rol',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $rol = $this->request->getGetPost('rol');

        if (!empty($rol)) {
            $data['rol'] = $rol;
        }

        return $this->response->setJSON(
            $this->rolesModel->getRoles($data)
        );
    }

    public function add()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/rol/add_rol') .
            view('layouts/footer');
    }

    public function rolAdd()
    {
        $data = [
            'nomb_rol' => $this->request->getPost('nomb_rol'),
            'esta_rol' => '1',
        ];

        if ($this->rolesModel->add($data)) {
            return redirect()->to(base_url('rol'));
        }

        session()->setFlashdata('error', 'No se pudo guardar el rol');

        return redirect()->to(base_url('rol/add'));
    }

    public function getRol()
    {
        $id = $this->request->getGet('id');

        $rol = $this->modelGeneral->getTableWhereRow(
            'rol',
            ['codi_rol' => $id]
        );

        return $this->response->setJSON($rol);
    }

    public function editRol()
    {
        $rules = [
            'id'     => 'required',
            'nombre' => 'required',
            'estado' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $edit = $this->modelGeneral->editRegist(
            'rol',
            ['codi_rol' => $this->request->getPost('id')],
            [
                'nomb_rol' => $this->request->getPost('nombre'),
                'esta_rol' => $this->request->getPost('estado'),
            ]
        );

        return $this->response->setJSON([
            'success' => !empty($edit),
        ]);
    }

    public function anularRol()
    {
        $edit = $this->modelGeneral->editRegist(
            'rol',
            ['codi_rol' => $this->request->getGetPost('id')],
            ['esta_rol' => 2]
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }
}
