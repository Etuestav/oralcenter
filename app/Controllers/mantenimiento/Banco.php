<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\BancoModel;

class Banco extends BaseController
{
    protected BancoModel $bancoModel;

    public function __construct()
    {
        $this->bancoModel = new BancoModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/banco/listarbanco') .
            view('layouts/footer');
    }

    public function jsonBanco()
    {
        $columns = [
            'cod_banco',
            'descripcion',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_banco',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $banco = $this->request->getGetPost('banco');

        if (!empty($banco)) {
            $data['banco'] = $banco;
        }

        return $this->response->setJSON(
            $this->bancoModel->getBanco($data)
        );
    }

    public function nuevo()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/banco/agregarbanco') .
            view('layouts/footer');
    }

    public function guardar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $rules = [
            'descripcion' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');

            return redirect()->to(base_url('banco/nuevo'));
        }

        $insert = $this->bancoModel->agregarBanco([
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => $this->request->getPost('estado'),
        ]);

        if (!empty($insert)) {
            session()->setFlashdata('success', 'Banco registrado correctamente.');
        }

        return redirect()->to(base_url('banco'));
    }

    public function editar(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data['banco'] = $this->bancoModel->getBancoid($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/banco/actualizarbanco', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

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

        $data = [
            'descripcion' => $descripcion,
            'estado'      => $estado,
        ];

        if ($this->bancoModel->updateBanco($codigo, $data)) {
            session()->setFlashdata('success', 'Banco actualizado correctamente.');

            return redirect()->to(base_url('banco'));
        }

        session()->setFlashdata('error', 'No se pudo actualizar el banco.');

        return redirect()->to(base_url('banco/editar/' . $codigo));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->bancoModel->updateBanco($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}