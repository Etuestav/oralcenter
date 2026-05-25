<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\ConceptoModel;

class Concepto extends BaseController
{
    protected ConceptoModel $conceptoModel;

    public function __construct()
    {
        $this->conceptoModel = new ConceptoModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/concepto/listarconcepto') .
            view('layouts/footer');
    }

    public function jsonConcepto()
    {
        $columns = [
            'id_tipoconcepto',
            'nombre_concepto',
            'estado_tipo',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'id_tipoconcepto',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoConcepto = $this->request->getGetPost('tipo_concepto');

        if (!empty($tipoConcepto)) {
            $data['tipo_concepto'] = $tipoConcepto;
        }

        return $this->response->setJSON(
            $this->conceptoModel->getConcepto($data)
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
            view('admin/concepto/agregarconcepto') .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'concepto' => 'required',
            'estado'   => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');
            return redirect()->to(base_url('concepto/nuevo'));
        }

        $this->conceptoModel->agregarConcepto([
            'nombre_concepto' => $this->request->getPost('concepto'),
            'estado_tipo'     => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Concepto registrado correctamente.');
        return redirect()->to(base_url('concepto'));
    }

    public function editar(int $id)
    {
        $data['concepto'] = $this->conceptoModel->getConceptoId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/concepto/actualizarconcepto', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $codigo = (int) $this->request->getPost('codigo');

        $rules = [
            'codigo'   => 'required',
            'concepto' => 'required',
            'estado'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $this->conceptoModel->updateConcepto($codigo, [
            'nombre_concepto' => $this->request->getPost('concepto'),
            'estado_tipo'     => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Concepto actualizado correctamente.');
        return redirect()->to(base_url('concepto'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->conceptoModel->updateConcepto($id, [
            'estado_tipo' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}