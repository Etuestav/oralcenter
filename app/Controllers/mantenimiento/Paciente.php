<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\PacientesModel;
use App\Models\Modelgeneral;

class Paciente extends BaseController
{
    protected PacientesModel $pacientesModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->pacientesModel = new PacientesModel();
        $this->modelGeneral = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/paciente/listarpaciente')
            . view('layouts/footer');
    }

    public function jsonPaciente()
    {
        $columns = ['codi_pac', 'NombrePaciente', 'edad_pac', 'dni_pac', 'dire_pac', 'fecha_registro', 'esta_pac'];
        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_pac',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        foreach (['desde', 'hasta', 'paciente'] as $campo) {
            $valor = $this->request->getGetPost($campo);
            if (!empty($valor)) {
                $data[$campo] = $valor;
            }
        }

        return $this->response->setJSON(
            $this->pacientesModel->getPaciente($data)
        );
    }

    public function add()
    {
        $data = [
            'pais'          => $this->modelGeneral->getTable('paises'),
            'departamentos' => $this->modelGeneral->getTable('departamento'),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/paciente/agregarpaciente', $data)
            . view('layouts/footer');
    }

    public function getProvincias()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON(
            $this->modelGeneral->getTableWhere('provincia', [
                'departamento_id' => $this->request->getGet('departamento'),
            ])
        );
    }

    public function getDistritos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON(
            $this->modelGeneral->getTableWhere('distrito', [
                'provincia_id' => $this->request->getGet('provincia'),
            ])
        );
    }

    public function guardar()
    {
        $data = $this->datosPaciente();

        $foto = $this->subirFotoPaciente();
        if ($foto !== null) {
            $data['foto_paciente'] = $foto;
        }

        $insert = $this->pacientesModel->agregarPaciente($data);

        $primary = ['codi_pac' => $insert];
        $this->modelGeneral->insertRegist('paciente_enfermedadactual', $primary);
        $this->modelGeneral->insertRegist('paciente_consulta', $primary);
        $this->modelGeneral->insertRegist('paciente_exploracion', $primary);

        return redirect()->to(base_url('paciente'));
    }

    public function editarPaciente(int $id)
    {
        $paciente = $this->pacientesModel->getPacientesId($id);

        $data = [
            'pacientes'     => $paciente,
            'departamentos' => $this->modelGeneral->getTable('departamento'),
            'provincias'    => $this->modelGeneral->getTableWhere('provincia', [
                'departamento_id' => $paciente->departamento_id ?? null,
            ]),
            'distritos'     => $this->modelGeneral->getTableWhere('distrito', [
                'provincia_id' => $paciente->provincia_id ?? null,
            ]),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/paciente/editpaciente', $data)
            . view('layouts/footer');
    }

    public function pacienteUpdate()
    {
        $id = (int) $this->request->getPost('codigo');
        $data = $this->datosPaciente();

        $foto = $this->subirFotoPaciente();
        if ($foto !== null) {
            $data['foto_paciente'] = $foto;
        }

        if ($this->pacientesModel->updatePaciente($id, $data)) {
            session()->setFlashdata('success', 'Actualizó correctamente los datos.');
            return redirect()->to(base_url('paciente'));
        }

        return $this->editarPaciente($id);
    }

    public function anularPaciente()
    {
        $edit = $this->modelGeneral->editRegist(
            'paciente',
            ['codi_pac' => $this->request->getGet('id')],
            ['esta_pac' => 'N']
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }

    private function datosPaciente(): array
    {
        return [
            'nomb_pac'         => $this->request->getPost('nombre'),
            'apel_pac'         => $this->request->getPost('apellidos'),
            'edad_pac'         => $this->request->getPost('edad'),
            'ocupacion'        => $this->request->getPost('ocupacion'),
            'estudios_pac'     => $this->request->getPost('estudios'),
            'lugar_nacimiento' => $this->request->getPost('lugarnacimiento'),
            'dire_pac'         => $this->request->getPost('direccion'),
            'telf_pac'         => $this->request->getPost('telefono'),
            'dni_pac'          => $this->request->getPost('dni'),
            'fena_pac'         => $this->request->getPost('fechanacimiento'),
            'sexo_pac'         => $this->request->getPost('sexo'),
            'civi_pac'         => $this->request->getPost('estadocivil'),
            'afil_pac'         => $this->request->getPost('afiliado'),
            'aler_pac'         => $this->request->getPost('alergia'),
            'emai_pac'         => $this->request->getPost('email'),
            'pais_id'          => $this->request->getPost('pais'),
            'departamento_id'  => $this->request->getPost('departamento'),
            'provincia_id'     => $this->request->getPost('provincia'),
            'distrito_id'      => $this->request->getPost('distrito'),
            'observacion'      => $this->request->getPost('observacion'),
            'esta_pac'         => $this->request->getPost('estado'),
        ];
    }

    private function subirFotoPaciente(): ?string
    {
        $foto = $this->request->getFile('foto_paciente');

        if (!$foto || !$foto->isValid() || $foto->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . 'vendor/uploads/pacientes/';

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