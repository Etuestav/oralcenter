<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Ubigeo extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        return view('admin/paciente/agregarpaciente');
    }

    public function getDepartamento()
    {
        $result = $this->db
            ->table('departamento')
            ->get()
            ->getResult();

        $json = [];

        foreach ($result as $r) {
            $json[] = [
                'value' => $r->departamento_id,
                'label' => $r->departamento_nombre,
            ];
        }

        return $this->response->setJSON($json);
    }

    public function getProvincia()
    {
        $departamentoId = $this->request->getPost('provincia_id');

        $result = $this->db
            ->table('provincia')
            ->where('departamento_id', $departamentoId)
            ->get()
            ->getResult();

        $json = [];

        foreach ($result as $r) {
            $json[] = [
                'value' => $r->provincia_id,
                'label' => $r->provincia_nombre,
            ];
        }

        return $this->response->setJSON($json);
    }

    public function getDistrito()
    {
        $provinciaId = $this->request->getPost('distrito_id');

        $result = $this->db
            ->table('distrito')
            ->where('provincia_id', $provinciaId)
            ->get()
            ->getResult();

        $json = [];

        foreach ($result as $r) {
            $json[] = [
                'value' => $r->distrito_id,
                'label' => $r->distrito_nombre,
            ];
        }

        return $this->response->setJSON($json);
    }
}