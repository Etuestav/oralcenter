<?php

namespace App\Controllers\Reportes;

use App\Controllers\BaseController;
use App\Models\ReportsModel;

class Redashboard extends BaseController
{
    protected ReportsModel $reportsModel;

    public function __construct()
    {
        $this->reportsModel = new ReportsModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'cantpacientes'      => $this->reportsModel->rowCountPacientes(),
            'cantratamientopag'  => $this->reportsModel->rowCountTratamientos(),
            'cantratamientocob'  => $this->reportsModel->rowCountTratamientosCobrar(),
            'cantmedicos'        => $this->reportsModel->rowCountMedicos(),
            'years'              => $this->reportsModel->years(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('reportes/grafictrat', $data) .
            view('layouts/footer');
    }

    public function getData()
    {
        $year = $this->request->getPost('year');

        return $this->response->setJSON(
            $this->reportsModel->getTrataCobrado($year)
        );
    }
}