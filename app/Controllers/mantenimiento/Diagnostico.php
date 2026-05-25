<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\DiagnosticoModel;

class Diagnostico extends BaseController
{
    protected DiagnosticoModel $diagnosticoModel;

    public function __construct()
    {
        $this->diagnosticoModel = new DiagnosticoModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/diagnostico/listardiagnostico') .
            view('layouts/footer');
    }

    public function jsonDiagnostico()
    {
        $columns = [
            'codi_enf',
            'desc_enf',
            'esta_enf',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_enf',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $enfermedad = $this->request->getGetPost('enfermedad');

        if (!empty($enfermedad)) {
            $data['enfermedad'] = $enfermedad;
        }

        return $this->response->setJSON(
            $this->diagnosticoModel->getDiagnostico($data)
        );
    }

    public function nuevo()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/diagnostico/agregardiagnostico') .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'cie10'       => 'required',
            'descripcion' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');
            return redirect()->to(base_url('diagnostico/nuevo'));
        }

        $this->diagnosticoModel->agregarDiagnostico([
            'codi_enf' => $this->request->getPost('cie10'),
            'desc_enf' => $this->request->getPost('descripcion'),
            'esta_enf' => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Diagnóstico registrado correctamente.');
        return redirect()->to(base_url('diagnostico'));
    }

    public function editar(string $id)
    {
        $data['diagnostico'] = $this->diagnosticoModel->getDiagnosticoId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/diagnostico/actualizardiagnostico', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $codigo      = $this->request->getPost('codigo');
        $descripcion = $this->request->getPost('descripcion');
        $estado      = $this->request->getPost('estado');

        $rules = [
            'codigo'      => 'required',
            'descripcion' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $this->diagnosticoModel->updateDiagnostico($codigo, [
            'desc_enf' => $descripcion,
            'esta_enf' => $estado,
        ]);

        session()->setFlashdata('success', 'Diagnóstico actualizado correctamente.');
        return redirect()->to(base_url('diagnostico'));
    }

    public function anular()
    {
        $id = $this->request->getGet('id');

        $this->diagnosticoModel->updateDiagnostico($id, [
            'esta_enf' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}