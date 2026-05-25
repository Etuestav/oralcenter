<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\PermisosModel;
use App\Models\Modelgeneral;

class Permisos extends BaseController
{
    protected PermisosModel $permisosModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->permisosModel = new PermisosModel();
        $this->modelGeneral  = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'permisos' => $this->permisosModel->getPermisos(),
            'rol'      => $this->modelGeneral->getTable('rol'),
            'menus'    => $this->modelGeneral->getTable('menus'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/permisos/listar', $data) .
            view('layouts/footer');
    }

    public function jsonPermisos()
    {
        if (!session()->get('login')) {
            return $this->response->setJSON([
                'error' => 'No autorizado',
            ]);
        }

        $data = [];

        $data['start']  = $this->request->getGetPost('start');
        $data['length'] = $this->request->getGetPost('length');
        $data['sEcho']  = $this->request->getGetPost('_');

        $columns = [
            'id_permiso',
            'NombreMenu',
            'NombreRol',
        ];

        $order = $this->request->getGetPost('order');

        if (!empty($order)) {
            $columnIndex = (int) $order[0]['column'];

            $data['orderCampo'] = $columns[$columnIndex] ?? 'id_permiso';
            $data['orderDireccion'] = $order[0]['dir'] ?? 'ASC';
        }

        $menus = $this->request->getGetPost('menus');
        $rol   = $this->request->getGetPost('rol');

        if (!empty($menus)) {
            $data['menus'] = $menus;
        }

        if (!empty($rol)) {
            $data['rol'] = $rol;
        }

        $datos = $this->permisosModel->getPerm($data);

        return $this->response->setJSON($datos);
    }

    public function add()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data = [
            'roles' => $this->modelGeneral->getTable('rol'),
            'menus' => $this->permisosModel->getMenus(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/permisos/add', $data) .
            view('layouts/footer');
    }

    public function store()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $rol = $this->request->getPost('rol');
        $menu = $this->request->getPost('menu');

        if (empty($rol) || empty($menu)) {
            session()->setFlashdata('error', 'Seleccione un rol y un menu.');

            return redirect()->to(base_url('administrador/permisos/add'))->withInput();
        }

        $data = [
            'id_menu'  => (int) $menu,
            'codi_rol' => (int) $rol,
            'read'     => (int) ($this->request->getPost('read') ?? 0),
            'insert'   => (int) ($this->request->getPost('insert') ?? 0),
            'update'   => (int) ($this->request->getPost('update') ?? 0),
            'delete'   => (int) ($this->request->getPost('delete') ?? 0),
        ];

        $existente = $this->permisosModel->getPermisoPorRolMenu($data['codi_rol'], $data['id_menu']);

        if ($existente) {
            $this->permisosModel->updatePermiso((int) $existente->id_permiso, $data);
            session()->setFlashdata('success', 'Permiso actualizado correctamente.');

            return redirect()->to(base_url('administrador/permisos'));
        }

        if ($this->permisosModel->savePermiso($data)) {
            session()->setFlashdata('success', 'Permiso guardado correctamente.');

            return redirect()->to(base_url('administrador/permisos'));
        }

        session()->setFlashdata(
            'error',
            'No se pudo guardar la información'
        );

        return redirect()->to(base_url('administrador/permisos/add'));
    }

    public function edit(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'roles'   => $this->modelGeneral->getTable('rol'),
            'menus'   => $this->permisosModel->getMenus(),
            'permiso' => $this->permisosModel->getPermiso($id),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/permisos/edit', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $idPermiso = $this->request->getPost('idpermiso');

        $data = [
            'read'   => $this->request->getPost('read') ?? 0,
            'insert' => $this->request->getPost('insert') ?? 0,
            'update' => $this->request->getPost('update') ?? 0,
            'delete' => $this->request->getPost('delete') ?? 0,
        ];

        if ($this->permisosModel->updatePermiso((int) $idPermiso, $data)) {
            return redirect()->to(base_url('administrador/permisos'));
        }

        session()->setFlashdata(
            'error',
            'No se pudo guardar la información'
        );

        return redirect()->to(base_url('administrador/permisos/edit/' . $idPermiso));
    }

    public function delete()
    {
        if (!session()->get('login')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $id = (int) $this->request->getGetPost('id');

        if ($id <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permiso invalido']);
        }

        return $this->response->setJSON([
            'success' => $this->permisosModel->deletePermiso($id),
        ]);
    }
}
