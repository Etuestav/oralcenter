<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\MedicoModel;
use App\Models\Modelgeneral;

class Medico extends BaseController
{
    protected MedicoModel $medicoModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->medicoModel = new MedicoModel();
        $this->modelGeneral = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data['especialidades'] = $this->modelGeneral->getTable('especialidad');

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/medico/listar', $data) .
            view('layouts/footer');
    }

    public function jsonMedicos()
    {
        $columns = [
            'codi_med',
            'NombresApellidos',
            'NombreEspecialidad',
            'dni_med',
            'coleg_med',
            'fecha_registro',
            'esta_med',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_med',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        foreach (['desde', 'hasta', 'medico', 'especialidad'] as $campo) {
            $valor = $this->request->getGetPost($campo);
            if (!empty($valor)) {
                $data[$campo] = $valor;
            }
        }

        return $this->response->setJSON(
            $this->medicoModel->getMedico($data)
        );
    }

    public function nuevo()
    {
        $data['especialidades'] = $this->modelGeneral->getTable('especialidad');

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/medico/registrar', $data) .
            view('layouts/footer');
    }

    public function validaEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setBody('false');
        }

        $email = $this->request->getGet('emai_med');

        $verifica = $this->modelGeneral->verificaUnico(
            'medico',
            'emai_med',
            $email
        );

        return $this->response->setBody($verifica ? 'true' : 'false');
    }

    public function guardar()
    {
        $rules = [
            'especialidad'    => 'required',
            'nombre'          => 'required',
            'apellidos'       => 'required',
            'dni'             => 'required',
            'colegiatura'     => 'required',
            'telefono'        => 'required',
            'direccion'       => 'required',
            'fechanacimiento' => 'required',
            'sexo'            => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->nuevo();
        }

        $dataUsuario = [
            'apellido'       => $this->request->getPost('apellidos'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipoDocumento'),
            'documento'      => $this->request->getPost('dni'),
            'codi_rol'       => 1,
            'logi_usu'       => $this->request->getPost('usuarioMedico'),
            'pass_usu'       => sha1((string) $this->request->getPost('passwordMedico')),
            'fecha_registro' => date('Y-m-d H:i:s'),
            'esta_usu'       => 1,
        ];

        $foto = $this->subirFotoUsuario();

        if ($foto !== null) {
            $dataUsuario['foto'] = $foto;
        }

        $idUsuario = $this->modelGeneral->insertRegist('usuario', $dataUsuario);

        $dataMedico = [
            'cod_especialidad' => $this->request->getPost('especialidad'),
            'nomb_med'         => $this->request->getPost('nombre'),
            'apel_med'         => $this->request->getPost('apellidos'),
            'dni_med'          => $this->request->getPost('dni'),
            'ruc_med'          => $this->request->getPost('ruc'),
            'coleg_med'        => $this->request->getPost('colegiatura'),
            'telf_med'         => $this->request->getPost('telefono'),
            'cel_med'          => $this->request->getPost('celular'),
            'dire_med'         => $this->request->getPost('direccion'),
            'emai_med'         => $this->request->getPost('email'),
            'fena_med'         => $this->request->getPost('fechanacimiento'),
            'sexo_med'         => $this->request->getPost('sexo'),
            'fecha_registro'   => date('Y-m-d H:i:s'),
            'esta_med'         => $this->request->getPost('estado'),
            'codi_usu'         => $idUsuario,
        ];

        if ($this->medicoModel->guardarMedico($dataMedico)) {
            session()->setFlashdata('success', 'Médico registrado correctamente.');
            return redirect()->to(base_url('medico'));
        }

        session()->setFlashdata('error', 'No se pudo guardar la información.');
        return redirect()->to(base_url('medico/nuevo'));
    }

    public function editar(int $id)
    {
        $medico = $this->medicoModel->getMedicoId($id);

        $data = [
            'medicos'        => $medico,
            'usuario'        => $this->modelGeneral->getTableWhereRow('usuario', [
                'codi_usu' => $medico->codi_usu ?? null,
            ]),
            'especialidades' => $this->modelGeneral->getTable('especialidad'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/medico/editar', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $idMedico = (int) $this->request->getPost('codigo');

        $rules = [
            'codigo'          => 'required',
            'especialidad'    => 'required',
            'nombre'          => 'required',
            'apellidos'       => 'required',
            'dni'             => 'required',
            'colegiatura'     => 'required',
            'telefono'        => 'required',
            'direccion'       => 'required',
            'fechanacimiento' => 'required',
            'sexo'            => 'required',
            'estado'          => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($idMedico);
        }

        $dataMedico = [
            'cod_especialidad' => $this->request->getPost('especialidad'),
            'nomb_med'         => $this->request->getPost('nombre'),
            'apel_med'         => $this->request->getPost('apellidos'),
            'dni_med'          => $this->request->getPost('dni'),
            'ruc_med'          => $this->request->getPost('ruc'),
            'coleg_med'        => $this->request->getPost('colegiatura'),
            'telf_med'         => $this->request->getPost('telefono'),
            'cel_med'          => $this->request->getPost('celular'),
            'dire_med'         => $this->request->getPost('direccion'),
            'emai_med'         => $this->request->getPost('email'),
            'fena_med'         => $this->request->getPost('fechanacimiento'),
            'sexo_med'         => $this->request->getPost('sexo'),
            'fecha_registro'   => date('Y-m-d H:i:s'),
            'esta_med'         => $this->request->getPost('estado'),
        ];

        $dataUsuario = [
            'apellido'       => $this->request->getPost('apellidos'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'tipo_documento' => $this->request->getPost('tipoDocumento'),
            'documento'      => $this->request->getPost('dni'),
            'logi_usu'       => $this->request->getPost('usuarioMedico'),
        ];

        $password = $this->request->getPost('passwordMedico');

        if (!empty($password)) {
            $dataUsuario['pass_usu'] = sha1((string) $password);
        }

        $foto = $this->subirFotoUsuario();

        if ($foto !== null) {
            $dataUsuario['foto'] = $foto;
        }

        $this->modelGeneral->editRegist(
            'usuario',
            ['codi_usu' => $this->request->getPost('usuario')],
            $dataUsuario
        );

        if ($this->medicoModel->updateMedico($idMedico, $dataMedico)) {
            session()->setFlashdata('success', 'Actualizó correctamente los datos.');
            return redirect()->to(base_url('publi/medico'));
        }

        return $this->editar($idMedico);
    }

    public function anularMedico()
    {
        $id = $this->request->getGet('id');

        $edit = $this->modelGeneral->editRegist(
            'medico',
            ['codi_med' => $id],
            ['esta_med' => 'N']
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }

    private function subirFotoUsuario(): ?string
    {
        $foto = $this->request->getFile('foto');

        if (!$foto || !$foto->isValid() || $foto->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . 'assets/uploads/usuarios/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $allowed = ['image/png', 'image/jpg', 'image/jpeg'];

        if (!in_array($foto->getMimeType(), $allowed, true)) {
            return null;
        }

        $fileName = $foto->getRandomName();
        $foto->move($uploadPath, $fileName);

        return $fileName;
    }
}