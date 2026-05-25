<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\AreasModel;

class Area extends BaseController
{
    protected AreasModel $areasModel;

    public function __construct()
    {
        $this->areasModel = new AreasModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data = [
            'area' => $this->areasModel->getAreas(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/area/list', $data) .
            view('layouts/footer');
    }

    public function areaAdd()
    {
        if (!$this->validate([
            'nombre_area' => 'required',
        ])) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'nombre_area'      => $this->request->getPost('nombre_area'),
            'descripcion_area' => $this->request->getPost('descripcion_area'),
            'estado'           => 1,
        ];

        $this->areasModel->add($data);

        return $this->response->setJSON([
            'status' => true,
        ]);
    }

    public function areaEdit(int $id)
    {
        $data = $this->areasModel->getAreaId($id);

        return $this->response->setJSON($data);
    }

    public function areaUpdate()
    {
        if (!$this->validate([
            'id_area'     => 'required',
            'nombre_area' => 'required',
        ])) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'nombre_area'      => $this->request->getPost('nombre_area'),
            'descripcion_area' => $this->request->getPost('descripcion_area'),
        ];

        $this->areasModel->areaUpdate(
            ['id_area' => $this->request->getPost('id_area')],
            $data
        );

        return $this->response->setJSON([
            'status' => true,
        ]);
    }

    public function delete(int $id)
    {
        $this->areasModel->updateArea($id, [
            'estado' => '0',
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => true,
            ]);
        }

        return redirect()->to(base_url('area'));
    }

    public function jsonArea()
    {
        return $this->response->setJSON($this->areasModel->getAreas());
    }
}
