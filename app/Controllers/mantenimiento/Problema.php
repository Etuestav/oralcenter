<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\ProblemaModel;

class Problema extends BaseController
{
    protected ProblemaModel $problemaModel;

    public function __construct()
    {
        $this->problemaModel = new ProblemaModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'problema' => $this->problemaModel->getProblema(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/tipo_problema/list_problema', $data) .
            view('layouts/footer');
    }

    public function problemaAdd()
    {
        $data = [
            'tipo_nombre' => $this->request->getPost('tipo_nombre'),
            'estado'      => 1,
        ];

        $this->problemaModel->add($data);

        return $this->response->setJSON([
            'status' => true,
        ]);
    }

    public function problemaEdit(int $id)
    {
        $data = $this->problemaModel->getProblemaId($id);

        return $this->response->setJSON($data);
    }

    public function problemaUpdate()
    {
        $id = (int) $this->request->getPost('id_tipo_problema');

        $data = [
            'tipo_nombre' => $this->request->getPost('tipo_nombre'),
        ];

        $this->problemaModel->problemaUpdate(
            ['id_tipo_problema' => $id],
            $data
        );

        return $this->response->setJSON([
            'status' => true,
        ]);
    }

    public function delete(int $id)
    {
        $this->problemaModel->updateProblema($id, [
            'estado' => '0',
        ]);

        return redirect()->to(base_url('mantenimiento/problema'));
    }
}