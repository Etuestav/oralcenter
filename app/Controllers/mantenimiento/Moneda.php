<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\MonedaModel;

class Moneda extends BaseController
{
    protected MonedaModel $monedaModel;

    public function __construct()
    {
        $this->monedaModel = new MonedaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/moneda/listarmoneda') .
            view('layouts/footer');
    }

    public function jsonMoneda()
    {
        $columns = [
            'cod_tipomoneda',
            'descripcion',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_tipomoneda',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoMoneda = $this->request->getGetPost('tipo_moneda');

        if (!empty($tipoMoneda)) {
            $data['tipo_moneda'] = $tipoMoneda;
        }

        return $this->response->setJSON(
            $this->monedaModel->getMoneda($data)
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
            view('admin/moneda/agregarmoneda') .
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
            return redirect()->to(base_url('moneda/nuevo'));
        }

        $this->monedaModel->agregarMoneda([
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Moneda registrada correctamente.');
        return redirect()->to(base_url('moneda'));
    }

    public function editar(int $id)
    {
        $data['moneda'] = $this->monedaModel->getMonedaId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/moneda/actualizarmoneda', $data) .
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

        $this->monedaModel->updateMoneda($codigo, [
            'descripcion' => $descripcion,
            'estado'      => $estado,
        ]);

        session()->setFlashdata('success', 'Moneda actualizada correctamente.');
        return redirect()->to(base_url('moneda'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->monedaModel->updateMoneda($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}