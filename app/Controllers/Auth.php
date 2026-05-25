<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\ClinicaModel;
use App\Models\ModelGeneral;

class Auth extends BaseController
{
    protected UsuariosModel $usuariosModel;
    protected ClinicaModel $clinicaModel;
    protected ModelGeneral $modelGeneral;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
        $this->clinicaModel = new ClinicaModel();
        $this->modelGeneral = new ModelGeneral();
    }

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('login')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('admin/login');
    }

    public function login(): \CodeIgniter\HTTP\RedirectResponse
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('paswoord');

        $res = $this->usuariosModel->login(
            $username,
            sha1($password)
        );

        if (!$res) {

            session()->setFlashdata(
                'error',
                'El usuario y/o contraseña son incorrectos'
            );

            return redirect()->to(base_url(''));

        }

        $logo = $this->clinicaModel->getclinica();
        $plan = $this->clinicaModel->getplanes();
        $roles = $this->clinicaModel->getUserRol($res->codi_usu);

        $medico = $this->modelGeneral->getTableWhereRow(
            'medico',
            ['codi_usu' => $res->codi_usu]
        );

        $data = [

            'codi_usu'       => $res->codi_usu,
            'apellido'       => $res->apellido,
            'nombre'         => $res->nombre,
            'rol'            => $res->codi_rol,
            'nombrerol'      => $roles->nombrerol ?? '',
            'tipo_documento' => $res->tipo_documento,
            'logi_usu'       => $res->logi_usu,

            'foto'           => $logo->photo ?? '',
            'direccion'      => $logo->direc_clin ?? '',
            'clinica'        => $logo->nomb_clin ?? '',
            'telefono'       => $logo->telf_clin ?? '',
            'plan'           => $plan->planes ?? '',
            'email'          => $logo->email_clin ?? '',

            'medico'         => $medico->codi_med ?? '',

            'login'          => true,

        ];

        session()->set($data);

        return redirect()->to(base_url('dashboard'));
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();

        return redirect()->to(base_url(''));
    }
}