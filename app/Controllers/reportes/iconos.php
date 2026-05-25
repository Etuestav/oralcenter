<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportesModel;

class Iconos extends BaseController
{
    protected ReportesModel $reportesModel;

    public function __construct()
    {
        $this->reportesModel = new ReportesModel();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'years'     => $this->reportesModel->years(),
            'excelente' => $this->reportesModel->rowCount(),
            'bueno'     => $this->reportesModel->rowCountBueno(),
            'regular'   => $this->reportesModel->rowCountRegular(),
            'pesimo'    => $this->reportesModel->rowCountPesimo(),
            'malo'      => $this->reportesModel->rowCountMalo(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('reportes/iconos', $data) .
            view('layouts/footer');
    }

    public function getData()
    {
        $year = $this->request->getPost('year');

        return $this->response->setJSON(
            $this->reportesModel->total($year)
        );
    }
}