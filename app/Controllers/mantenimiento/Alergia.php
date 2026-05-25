<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\AlergiaModel;

class Alergia extends BaseController
{
    protected AlergiaModel $alergiaModel;

    public function __construct()
    {
        $this->alergiaModel = new AlergiaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/alergia/listar') .
            view('layouts/footer');
    }

    public function jsonAlergia()
    {
        $columns = [
            'cod_ale',
            'nombre_ale',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_ale',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $alergia = $this->request->getGetPost('alergia');

        if (!empty($alergia)) {
            $data['alergia'] = $alergia;
        }

        return $this->response->setJSON(
            $this->alergiaModel->getAlergia($data)
        );
    }

    public function nuevo()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return
            view('layouts/header').
            view('layouts/aside').
            view('admin/alergia/agregar').
            view('layouts/footer');
    }

    public function guardar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $rules = [
            'descripcion' => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe ingresar la descripción.');

            return redirect()->to(base_url('alergia/nuevo'));
        }

        $insert = $this->alergiaModel->agregarAlergia([
            'nombre_ale' => $this->request->getPost('descripcion'),
        ]);

        if (!empty($insert)) {
            session()->setFlashdata('success', 'Alergia registrada correctamente.');
        }

        return redirect()->to(base_url('alergia'));
    }

    public function editar(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data['alergia'] = $this->alergiaModel->getAlergiaid($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/alergia/actualizar', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $codigo      = (int) $this->request->getPost('codigo');
        $descripcion = $this->request->getPost('nombre');

        $rules = [
            'codigo' => 'required',
            'nombre' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $data = [
            'nombre_ale' => $descripcion,
        ];

        if ($this->alergiaModel->updateAlergia($codigo, $data)) {
            session()->setFlashdata('success', 'Alergia actualizada correctamente.');

            return redirect()->to(base_url('alergia'));
        }

        session()->setFlashdata('error', 'No se pudo actualizar la alergia.');

        return redirect()->to(base_url('alergia/editar/' . $codigo));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->alergiaModel->updateAlergia($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}