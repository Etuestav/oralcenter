<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\TipodocumentoModel;
use App\Models\Modelgeneral;

class Tipodocumento extends BaseController
{
    protected TipodocumentoModel $tipoDocumentoModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->tipoDocumentoModel = new TipodocumentoModel();
        $this->modelGeneral       = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'documentos' => $this->modelGeneral->getTable('tipo_documento'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/documento/listartipodocumento', $data) .
            view('layouts/footer');
    }

    public function jsonDocumento()
    {
        $columns = [
            'descripcion',
            'abreviatura',
            'serie',
            'inicio',
            'fin',
            'correlativo_actual',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'descripcion',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        $tipoDocumento = $this->request->getGetPost('tipo_documento');

        if (!empty($tipoDocumento)) {
            $data['tipo_documento'] = $tipoDocumento;
        }

        return $this->response->setJSON(
            $this->tipoDocumentoModel->getDocumento($data)
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
            view('admin/documento/agregardocumento') .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'nombre'      => 'required|is_unique[tipo_documento.descripcion]',
            'abreviatura' => 'required',
            'serie'       => 'required',
            'inicio'      => 'required',
            'fin'         => 'required',
            'correlativo' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos correctamente.');

            return redirect()->to(base_url('tipodocumento/nuevo'));
        }

        $this->tipoDocumentoModel->agregarDocumento([
            'descripcion'         => $this->request->getPost('nombre'),
            'abreviatura'         => $this->request->getPost('abreviatura'),
            'serie'               => $this->request->getPost('serie'),
            'inicio'              => $this->request->getPost('inicio'),
            'fin'                 => $this->request->getPost('fin'),
            'correlativo_actual'  => $this->request->getPost('correlativo'),
            'estado'              => $this->request->getPost('estado'),
        ]);

        session()->setFlashdata('success', 'Tipo de documento registrado correctamente.');

        return redirect()->to(base_url('tipodocumento'));
    }

    public function getTipoDocumento()
    {
        $id = $this->request->getGet('id');

        $documento = $this->modelGeneral->getTableWhereRow(
            'tipo_documento',
            ['cod_tipodocumento' => $id]
        );

        return $this->response->setJSON($documento);
    }

    public function editTipoDocumento()
    {
        $rules = [
            'id'          => 'required',
            'nombre'      => 'required',
            'abreviatura' => 'required',
            'serie'       => 'required',
            'inicio'      => 'required',
            'fin'         => 'required',
            'correlativo' => 'required',
            'estado'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $edit = $this->modelGeneral->editRegist(
            'tipo_documento',
            ['cod_tipodocumento' => $this->request->getPost('id')],
            [
                'descripcion'        => $this->request->getPost('nombre'),
                'abreviatura'        => $this->request->getPost('abreviatura'),
                'serie'              => $this->request->getPost('serie'),
                'inicio'             => $this->request->getPost('inicio'),
                'fin'                => $this->request->getPost('fin'),
                'correlativo_actual' => $this->request->getPost('correlativo'),
                'estado'             => $this->request->getPost('estado'),
            ]
        );

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }

    public function update()
    {
        return $this->editTipoDocumento();
    }

    public function anular()
    {
        $id = (int) $this->request->getGet('id');

        $this->tipoDocumentoModel->updateDocumento($id, [
            'estado' => '2',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}
