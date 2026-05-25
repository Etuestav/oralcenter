<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\AreasModel;
use App\Models\ProblemaModel;
use App\Models\TipodocumentoModel;
use App\Models\UserModel;
use App\Models\OcurrenciasModel;

class Ocurrencias extends BaseController
{
    protected AreasModel $areasModel;
    protected ProblemaModel $problemaModel;
    protected TipodocumentoModel $tipoDocumentoModel;
    protected UserModel $userModel;
    protected OcurrenciasModel $ocurrenciasModel;

    public function __construct()
    {
        $this->areasModel         = new AreasModel();
        $this->problemaModel      = new ProblemaModel();
        $this->tipoDocumentoModel = new TipodocumentoModel();
        $this->userModel          = new UserModel();
        $this->ocurrenciasModel   = new OcurrenciasModel();

        date_default_timezone_set('America/Lima');
    }

    public function index()
    {
        return
            view('layouts/header') .
            view('layouts/aside') .
            view('movimientos/concurrencias/iniciar') .
            view('layouts/footer');
    }

    public function add()
    {
        $data = [
            'user'          => $this->userModel->getUser(),
            'area'          => $this->areasModel->getAreas(),
            'problema'      => $this->problemaModel->getProblema(),
            'tipodocumento' => $this->tipoDocumentoModel->getDocumento([]),
            'archivos'      => $this->ocurrenciasModel->getArchivos(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('movimientos/concurrencias/registrar', $data) .
            view('layouts/footer');
    }

    public function addOcurrencias()
    {
        $datos = [
            'id_usuario'        => $this->request->getPost('id_usuario'),
            'id_area'           => $this->request->getPost('id_area'),
            'id_tipo_problema'  => $this->request->getPost('id_tipo_problema'),
            'id_tipo_documento' => $this->request->getPost('id_tipo_documento'),
            'fecha_registro'    => date('Y-m-d H:i:s'),
            'fecha_problema'    => $this->request->getPost('fecha_problema'),
            'fecha_finalizado'  => $this->request->getPost('fecha_finalizado'),
            'mensaje'           => $this->request->getPost('mensaje'),
            'estado'            => 2,
        ];

        $documento = $this->request->getFile('documento');

        if ($documento && $documento->isValid() && !$documento->hasMoved()) {
            $datos['nombre_documento'] = $documento->getClientName();
            $datos['documento'] = file_get_contents($documento->getTempName());
        }

        $insert = $this->ocurrenciasModel->insertar('ocurrencia', $datos);

        if (!empty($insert)) {
            return redirect()->to(base_url('mantenimiento/ocurrencias'));
        }

        session()->setFlashdata('error', 'No se puede guardar la información');

        return redirect()->to(base_url('mantenimiento/ocurrencias'));
    }
}