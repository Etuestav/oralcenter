<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportesEmergModel;

class Icoemergencia extends BaseController
{
    protected ReportesEmergModel $reportesEmergModel;

    public function __construct()
    {
        $this->reportesEmergModel = new ReportesEmergModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'years'     => $this->reportesEmergModel->years(),
            'excelente' => $this->reportesEmergModel->rowCount(),
            'bueno'     => $this->reportesEmergModel->rowCountBueno(),
            'regular'   => $this->reportesEmergModel->rowCountRegular(),
            'pesimo'    => $this->reportesEmergModel->rowCountPesimo(),
            'malo'      => $this->reportesEmergModel->rowCountMalo(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('reportes/emergencia', $data) .
            view('layouts/footer');
    }

    public function getData()
    {
        $year = $this->request->getPost('year');

        $resultados = $this->reportesEmergModel->total($year);

        return $this->response->setJSON($resultados);
    }
}