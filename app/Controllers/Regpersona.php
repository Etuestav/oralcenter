<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\Modelgeneral;
use CodeIgniter\Email\Email;

class Regpersona extends BaseController
{
    protected UserModel $userModel;
    protected Modelgeneral $modelGeneral;
    protected Email $email;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->modelGeneral = new Modelgeneral();
        $this->email        = service('email');
    }

    public function index()
    {
        $data = [
            'roles' => $this->modelGeneral->getTable('rol'),
        ];

        return view('admin/usuario/registrar', $data);
    }

    public function validaEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setBody('false');
        }

        $email = $this->request->getGet('email');

        $verifica = $this->modelGeneral->verificaUnico(
            'usuario',
            'email',
            $email
        );

        return $this->response->setBody($verifica ? 'true' : 'false');
    }

    public function guardarUsuario()
    {
        $rules = [
            'apellidos'      => 'required',
            'nombres'        => 'required',
            'tipo_documento' => 'required',
            'documento'      => 'required',
            'tipo_usuario'   => 'required',
            'email'          => 'required|valid_email',
            'usuario'        => 'required',
            'password'       => 'required',
            'passconf'       => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'apellido'       => $this->request->getPost('apellidos'),
            'nombre'         => $this->request->getPost('nombres'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'documento'      => $this->request->getPost('documento'),
            'codi_rol'       => $this->request->getPost('tipo_usuario'),
            'logi_usu'       => $this->request->getPost('usuario'),
            'pass_usu'       => sha1((string) $this->request->getPost('password')),
            'fecha_registro' => date('Y-m-d H:i:s'),
            'esta_usu'       => 1,
        ];

        $insert = $this->modelGeneral->insertRegist('usuario', $data);

        if ($insert === null) {
            return $this->response->setJSON([
                'success' => false,
            ]);
        }

        $dataMedico = [
            'codi_usu'       => $insert,
            'cod_especialidad' => 1,
            'nomb_med'       => $this->request->getPost('nombres'),
            'apel_med'       => $this->request->getPost('apellidos'),
            'dni_med'        => $this->request->getPost('documento'),
            'ruc_med'        => '111',
            'coleg_med'      => '111',
            'telf_med'       => '111',
            'cel_med'        => '111',
            'dire_med'       => 'direccion',
            'emai_med'       => $this->request->getPost('email'),
            'fena_med'       => date('Y-m-d'),
            'sexo_med'       => 'M',
            'fecha_registro' => date('Y-m-d H:i:s'),
        ];

        $this->modelGeneral->insertRegist('medico', $dataMedico);

        if ($this->request->getPost('notificar') === 'on') {
            $this->enviarNotificacion((int) $insert);
        }

        session()->setFlashdata('success', 'Usuario registrado correctamente.');

        return redirect()->to(base_url('auth'));
    }

    public function enviarNotificacion(int $id): bool
    {
        $data = [
            'usuarios' => $this->modelGeneral->getTableWhereRow(
                'usuario',
                ['codi_usu' => $id]
            ),
        ];

        $mensaje = view('admin/usuario/notificacion', $data);

        $this->email->setMailType('html');
        $this->email->setFrom('sysdentalsac@gmail.com', 'Sistema Dental');
        $this->email->setTo($data['usuarios']->email);
        $this->email->setSubject('Notificación de Clínica Dental');
        $this->email->setMessage($mensaje);

        return $this->email->send();
    }
}