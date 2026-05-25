<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\TarjetaModel;

class Tarjeta extends BaseController
{
    protected TarjetaModel $tarjetaModel;

    public function __construct()
    {
        $this->tarjetaModel = new TarjetaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/tarjeta/listartarjeta') .
            view('layouts/footer');
    }

    public function jsonTarjeta()
    {
        $columns = [
            'cod_tarjeta',
            'descripcion',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_tarjeta',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoTarjeta = $this->request->getGetPost('tipo_tarjeta');

        if (!empty($tipoTarjeta)) {
            $data['tipo_tarjeta'] = $tipoTarjeta;
        }

        return $this->response->setJSON(
            $this->tarjetaModel->getTarjeta($data)
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
            view('admin/tarjeta/agregartarjeta') .
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
            return redirect()->to(base_url('tarjeta/nuevo'));
        }

        $this->tarjetaModel->agregarTarjeta([
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Tarjeta registrada correctamente.');
        return redirect()->to(base_url('tarjeta'));
    }

    public function editar(int $id)
    {
        $data['tarjeta'] = $this->tarjetaModel->getTarjetaId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/tarjeta/actualizar', $data) .
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

        $this->tarjetaModel->updateTarjeta($codigo, [
            'descripcion' => $descripcion,
            'estado'      => $estado,
        ]);

        session()->setFlashdata('success', 'Tarjeta actualizada correctamente.');
        return redirect()->to(base_url('tarjeta'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->tarjetaModel->updateTarjeta($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}