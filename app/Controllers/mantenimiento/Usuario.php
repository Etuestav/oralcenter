<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RolesModel;
use App\Models\Modelgeneral;

class Usuario extends BaseController
{
    protected UserModel $userModel;
    protected RolesModel $rolesModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->rolesModel   = new RolesModel();
        $this->modelGeneral = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'user'    => $this->userModel->getUser(),
            'rol'     => $this->modelGeneral->getTable('rol'),
            'usuario' => $this->modelGeneral->getTable('usuario'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/usuario/list_usuario', $data) .
            view('layouts/footer');
    }

    public function jsonUsu()
    {
        $columns = [
            'codi_usu',
            'NombreUsuario',
            'logi_usu',
            'rol',
            'email',
            'fecha_registro',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_usu',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        foreach (['desde', 'hasta', 'usuario', 'rol'] as $campo) {
            $valor = $this->request->getGetPost($campo);

            if (!empty($valor)) {
                $data[$campo] = $valor;
            }
        }

        return $this->response->setJSON(
            $this->userModel->getUsuario($data)
        );
    }

    public function add()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'user' => $this->userModel->getUser(),
            'rol'  => $this->modelGeneral->getTable('rol'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/usuario/add', $data) .
            view('layouts/footer');
    }

    public function userAdd()
    {
        $data = [
            'apellido'       => $this->request->getPost('apellido'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'documento'      => $this->request->getPost('documento'),
            'foto'           => $this->request->getPost('foto'),
            'codi_rol'       => $this->request->getPost('codi_rol'),
            'logi_usu'       => $this->request->getPost('logi_usu'),
            'pass_usu'       => sha1((string) $this->request->getPost('pass_usu')),
            'fecha_registro' => date('Y-m-d H:i:s'),
            'esta_usu'       => $this->request->getPost('esta_usu'),
        ];

        if ($this->userModel->agregarUsuario($data)) {
            return redirect()->to(base_url('usuario'));
        }

        session()->setFlashdata('error', 'No se pudo guardar el usuario');

        return redirect()->to(base_url('usuario/add'));
    }

    public function getUsuario()
    {
        $id = $this->request->getGet('id');

        $usuario = $this->modelGeneral->getTableWhereRow(
            'usuario',
            ['codi_usu' => $id]
        );

        return $this->response->setJSON($usuario);
    }

    public function perfil()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $usuario = $this->modelGeneral->getTableWhereRow(
            'usuario',
            ['codi_usu' => session()->get('codi_usu')]
        );

        if (!$usuario) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Usuario no encontrado');
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/usuario/perfil', ['usuario' => $usuario]) .
            view('layouts/footer');
    }

    public function actualizarPerfil()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $rules = [
            'apellido'       => 'required',
            'nombre'         => 'required',
            'telefono'       => 'required',
            'direccion'      => 'required',
            'email'          => 'required|min_length[3]|valid_email',
            'tipo_documento' => 'required',
            'documento'      => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');

            return redirect()->back()->withInput();
        }

        $data = [
            'apellido'       => $this->request->getPost('apellido'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'documento'      => $this->request->getPost('documento'),
        ];

        $password = $this->request->getPost('passwoord');

        if (!empty($password)) {
            $data['pass_usu'] = sha1((string) $password);
        }

        $this->modelGeneral->editRegist(
            'usuario',
            ['codi_usu' => session()->get('codi_usu')],
            $data
        );

        session()->set([
            'apellido' => $data['apellido'],
            'nombre'   => $data['nombre'],
        ]);

        session()->setFlashdata('success', 'Perfil actualizado correctamente.');

        return redirect()->to(base_url('usuario/perfil'));
    }

    public function editUsuario()
    {
        $rules = [
            'id'             => 'required',
            'apellido'       => 'required',
            'nombre'         => 'required',
            'telefono'       => 'required',
            'direccion'      => 'required',
            'email'          => 'required|min_length[3]|valid_email',
            'tipo_documento' => 'required',
            'documento'      => 'required',
            'codigorol'      => 'required',
            'login'          => 'required',
            'estado'         => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'apellido'       => $this->request->getPost('apellido'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'documento'      => $this->request->getPost('documento'),
            'codi_rol'       => $this->request->getPost('codigorol'),
            'logi_usu'       => $this->request->getPost('login'),
            'esta_usu'       => $this->request->getPost('estado'),
        ];

        $password = $this->request->getPost('passwoord');

        if (!empty($password)) {
            $data['pass_usu'] = sha1((string) $password);
        }

        $edit = $this->modelGeneral->editRegist(
            'usuario',
            ['codi_usu' => $this->request->getPost('id')],
            $data
        );

        return $this->response->setJSON([
            'success' => !empty($edit),
        ]);
    }

    public function anularUsuario()
    {
        $edit = $this->modelGeneral->editRegist(
            'usuario',
            ['codi_usu' => $this->request->getGet('id')],
            ['esta_usu' => 2]
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }
}
