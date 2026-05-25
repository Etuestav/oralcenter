<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\CitadoModel;
use App\Models\Modelgeneral;

class Citado extends BaseController
{
    protected CitadoModel $citadoModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->citadoModel = new CitadoModel();
        $this->modelGeneral = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/citado/listarcitado') .
            view('layouts/footer');
    }

    public function jsonCitado()
    {
        $columns = [
            'cod_citado',
            'nomb_citado',
            'esta_citado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_citado',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoCitado = $this->request->getGetPost('tipo_citado');

        if (!empty($tipoCitado)) {
            $data['tipo_citado'] = $tipoCitado;
        }

        return $this->response->setJSON(
            $this->citadoModel->getCitado($data)
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
            view('admin/citado/agregarcitado') .
            view('layouts/footer');
    }

    public function validaCitado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setBody('false');
        }

        $citado = $this->request->getGet('nomb_citado');

        $verifica = $this->modelGeneral->verificaUnico(
            'tipo_citado',
            'nomb_citado',
            $citado
        );

        return $this->response->setBody($verifica ? 'true' : 'false');
    }

    public function guardar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $rules = [
            'nombre' => 'required|is_unique[tipo_citado.nomb_citado]',
            'estado' => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos correctamente.');

            return redirect()->to(base_url('citado/nuevo'));
        }

        $insert = $this->citadoModel->agregarCitado([
            'nomb_citado'  => $this->request->getPost('nombre'),
            'esta_citado' => $this->request->getPost('estado'),
        ]);

        if (!empty($insert)) {
            session()->setFlashdata('success', 'Tipo citado registrado correctamente.');
        }

        return redirect()->to(base_url('citado'));
    }

    public function editar(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data['citado'] = $this->citadoModel->getCitadoId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/citado/actualizarcitado', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $codigo = (int) $this->request->getPost('codigo');
        $nombre = $this->request->getPost('nombre');
        $estado = $this->request->getPost('estado');

        $actual = $this->citadoModel->getCitadoId($codigo);

        $rules = [
            'codigo' => 'required',
            'estado' => 'required',
        ];

        if ($actual && $nombre === $actual->nomb_citado) {
            $rules['nombre'] = 'required';
        } else {
            $rules['nombre'] = 'required|is_unique[tipo_citado.nomb_citado]';
        }

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $data = [
            'nomb_citado'  => $nombre,
            'esta_citado' => $estado,
        ];

        if ($this->citadoModel->updateCitado($codigo, $data)) {
            session()->setFlashdata('success', 'Tipo citado actualizado correctamente.');

            return redirect()->to(base_url('citado'));
        }

        session()->setFlashdata('error', 'No se pudo actualizar el tipo citado.');

        return redirect()->to(base_url('citado/editar/' . $codigo));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->citadoModel->updateCitado($id, [
            'esta_citado' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}