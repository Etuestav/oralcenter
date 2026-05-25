<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\UnidadmedidaModel;

class Medida extends BaseController
{
    protected UnidadmedidaModel $unidadMedidaModel;

    public function __construct()
    {
        $this->unidadMedidaModel = new UnidadmedidaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/medida/listarmedida') .
            view('layouts/footer');
    }

    public function jsonMedida()
    {
        $columns = [
            'id_medida',
            'nom_medida',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'id_medida',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $unidadMedida = $this->request->getGetPost('unidad_medida');

        if (!empty($unidadMedida)) {
            $data['unidad_medida'] = $unidadMedida;
        }

        return $this->response->setJSON(
            $this->unidadMedidaModel->getMedida($data)
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
            view('admin/medida/agregarmedida') .
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
            return redirect()->to(base_url('medida/nuevo'));
        }

        $this->unidadMedidaModel->agregarMedida([
            'nom_medida' => $this->request->getPost('descripcion'),
            'estado'     => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Unidad de medida registrada correctamente.');
        return redirect()->to(base_url('medida'));
    }

    public function editar(int $id)
    {
        $data['medida'] = $this->unidadMedidaModel->getMedidaId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/medida/actualizarmedida', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $codigo      = (int) $this->request->getPost('codigo');
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

        $this->unidadMedidaModel->updateMedida($codigo, [
            'nom_medida' => $descripcion,
            'estado'     => $estado,
        ]);

        session()->setFlashdata('success', 'Unidad de medida actualizada correctamente.');
        return redirect()->to(base_url('medida'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->unidadMedidaModel->updateMedida($id, [
            'estado' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}