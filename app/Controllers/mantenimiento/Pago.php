<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\PagoModel;

class Pago extends BaseController
{
    protected PagoModel $pagoModel;

    public function __construct()
    {
        $this->pagoModel = new PagoModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/pago/listarpago') .
            view('layouts/footer');
    }

    public function jsonPago()
    {
        $columns = [
            'cod_tipopago',
            'descripcion',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_tipopago',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoPago = $this->request->getGetPost('tipo_pago');

        if (!empty($tipoPago)) {
            $data['tipo_pago'] = $tipoPago;
        }

        return $this->response->setJSON(
            $this->pagoModel->getPago($data)
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
            view('admin/pago/agregarpago') .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'descripcion' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');
            return redirect()->to(base_url('pago/nuevo'));
        }

        $this->pagoModel->agregarPago([
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Tipo de pago registrado correctamente.');
        return redirect()->to(base_url('pago'));
    }

    public function editar(int $id)
    {
        $data['pago'] = $this->pagoModel->getPagoId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/pago/actualizarpago', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $codigo      = (int) $this->request->getPost('codigo');
        $descripcion = $this->request->getPost('nombre');
        $estado      = $this->request->getPost('estado');

        $rules = [
            'codigo' => 'required',
            'nombre' => 'required',
            'estado' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $this->pagoModel->updatePago($codigo, [
            'descripcion' => $descripcion,
            'estado'      => $estado,
        ]);

        session()->setFlashdata('success', 'Tipo de pago actualizado correctamente.');
        return redirect()->to(base_url('pago'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->pagoModel->updatePago($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}