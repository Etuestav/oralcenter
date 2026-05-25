<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class Categoria extends BaseController
{
    protected CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/categoria/listarcategoria') .
            view('layouts/footer');
    }

    public function jsonCategoria()
    {
        $columns = [
            'codi_cat',
            'nomb_cat',
            'esta_cat',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_cat',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $categoria = $this->request->getGetPost('categoria');

        if (!empty($categoria)) {
            $data['categoria'] = $categoria;
        }

        return $this->response->setJSON(
            $this->categoriaModel->getCategoria($data)
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
            view('admin/categoria/agregarcategoria') .
            view('layouts/footer');
    }

    public function guardar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $rules = [
            'categoria' => 'required',
            'estado'    => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');

            return redirect()->to(base_url('categoria/nuevo'));
        }

        $insert = $this->categoriaModel->agregarCategoria([
            'nomb_cat' => $this->request->getPost('categoria'),
            'esta_cat' => $this->request->getPost('estado'),
        ]);

        if (!empty($insert)) {
            session()->setFlashdata('success', 'Categoría registrada correctamente.');
        }

        return redirect()->to(base_url('categoria'));
    }

    public function editar(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data['categoria'] = $this->categoriaModel->getCategoriaId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/categoria/actualizarcategoria', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $codigo      = (int) $this->request->getPost('codigo');
        $descripcion = $this->request->getPost('categoria');
        $estado      = $this->request->getPost('estado');

        $rules = [
            'codigo'    => 'required',
            'categoria' => 'required',
            'estado'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $data = [
            'nomb_cat' => $descripcion,
            'esta_cat' => $estado,
        ];

        if ($this->categoriaModel->updateCategoria($codigo, $data)) {
            session()->setFlashdata('success', 'Categoría actualizada correctamente.');

            return redirect()->to(base_url('categoria'));
        }

        session()->setFlashdata('error', 'No se pudo actualizar la categoría.');

        return redirect()->to(base_url('categoria/editar/' . $codigo));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->categoriaModel->updateCategoria($id, [
            'esta_cat' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}