<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\EspecialidadModel;

class Especialidad extends BaseController
{
    protected EspecialidadModel $especialidadModel;

    public function __construct()
    {
        $this->especialidadModel = new EspecialidadModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/especialidad/listarespecialidad') .
            view('layouts/footer');
    }

    public function jsonEspecialidad()
    {
        $columns = [
            'cod_especialidad',
            'nombre_especialidad',
            'descripcion_especialidad',
            'estado_especialidad',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'cod_especialidad',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $especialidad = $this->request->getGetPost('especialidad');

        if (!empty($especialidad)) {
            $data['especialidad'] = $especialidad;
        }

        return $this->response->setJSON(
            $this->especialidadModel->getEspecialidad($data)
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
            view('admin/especialidad/agregarespecialidad') .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'nombre' => 'required',
            'estado' => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');
            return redirect()->to(base_url('especialidad/nuevo'));
        }

        $this->especialidadModel->agregarEspecialidad([
            'nombre_especialidad'      => $this->request->getPost('nombre'),
            'descripcion_especialidad' => $this->request->getPost('descripcion'),
            'estado_especialidad'      => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Especialidad registrada correctamente.');
        return redirect()->to(base_url('especialidad'));
    }

    public function editar(int $id)
    {
        $data['especialidad'] = $this->especialidadModel->getEspecialidadId($id);

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/especialidad/actualizarespecialidad', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $codigo      = (int) $this->request->getPost('codigo');
        $nombre      = $this->request->getPost('nombre_especialidad');
        $descripcion = $this->request->getPost('descripcion');
        $estado      = $this->request->getPost('estado');

        $actual = $this->especialidadModel->getEspecialidadId($codigo);

        $rules = [
            'codigo' => 'required',
            'estado' => 'required',
        ];

        if ($actual && $nombre === $actual->nombre_especialidad) {
            $rules['nombre_especialidad'] = 'required';
        } else {
            $rules['nombre_especialidad'] = 'required|is_unique[especialidad.nombre_especialidad]';
        }

        if (!$this->validate($rules)) {
            return $this->editar($codigo);
        }

        $this->especialidadModel->updateEspecialidad($codigo, [
            'nombre_especialidad'      => $nombre,
            'descripcion_especialidad' => $descripcion,
            'estado_especialidad'      => $estado,
        ]);

        session()->setFlashdata('success', 'Especialidad actualizada correctamente.');
        return redirect()->to(base_url('especialidad'));
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->especialidadModel->updateEspecialidad($id, [
            'estado_especialidad' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}