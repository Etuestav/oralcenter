<?php

namespace App\Controllers\Mantenimiento;

use App\Controllers\BaseController;
use App\Models\TarifarioModel;
use App\Models\Modelgeneral;

class Tarifario extends BaseController
{
    protected TarifarioModel $tarifarioModel;
    protected Modelgeneral $modelGeneral;

    public function __construct()
    {
        $this->tarifarioModel = new TarifarioModel();
        $this->modelGeneral   = new Modelgeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'tipo_conceptos'   => $this->modelGeneral->getTable('tipo_concepto'),
            'unidad_medidades' => $this->modelGeneral->getTable('unidad_medida'),
            'categorias'       => $this->modelGeneral->getTable('categoria'),
            'permisos'         => (object) ['insert' => 1],
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/procedimiento/listarprocedimiento', $data) .
            view('layouts/footer');
    }

    public function jsonTarifario()
    {
        $columns = [
            'id_procedimiento',
            'NombreProcedimiento',
            'NombreMedida',
            'NombreCategoria',
            'nombre',
            'prec_procedimiento',
            'fecha_registro',
            'estado',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'id_procedimiento',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
        ];

        foreach (['desde', 'hasta', 'procedimiento'] as $campo) {
            $valor = $this->request->getGetPost($campo);

            if (!empty($valor)) {
                $data[$campo] = $valor;
            }
        }

        return $this->response->setJSON(
            $this->tarifarioModel->getProcedimiento($data)
        );
    }

    public function nuevo()
    {
        $data = [
            'tipo_conceptos' => $this->modelGeneral->getTable('tipo_concepto'),
            'unidad_medidas' => $this->modelGeneral->getTable('unidad_medida'),
            'categorias'     => $this->modelGeneral->getTable('categoria'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/procedimiento/agregar', $data) .
            view('layouts/footer');
    }

    public function guardar()
    {
        $rules = [
            'concepto'  => 'required',
            'medida'    => 'required',
            'categoria' => 'required',
            'nombre'    => 'required',
            'precio'    => 'required',
            'estado'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->nuevo();
        }

        $data = [
            'id_tipoconcepto'    => $this->request->getPost('concepto'),
            'id_medida'          => $this->request->getPost('medida'),
            'codi_cat'           => $this->request->getPost('categoria'),
            'nombre'             => $this->request->getPost('nombre'),
            'prec_procedimiento' => $this->request->getPost('precio'),
            'fecha_registro'     => date('Y-m-d H:i:s'),
            'estado'             => $this->request->getPost('estado'),
        ];

        if ($this->tarifarioModel->guardarProcedimiento($data)) {
            session()->setFlashdata('success', 'Registro guardado correctamente.');
            return redirect()->to(base_url('tarifario'));
        }

        session()->setFlashdata('error', 'No se pudo guardar la información.');
        return redirect()->to(base_url('tarifario/nuevo'));
    }

    public function editar(int $id)
    {
        $data = [
            'tarifario'      => $this->tarifarioModel->getProcedimientoId($id),
            'tipo_conceptos' => $this->modelGeneral->getTable('tipo_concepto'),
            'unidad_medidas' => $this->modelGeneral->getTable('unidad_medida'),
            'categorias'     => $this->modelGeneral->getTable('categoria'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/procedimiento/actualizar', $data) .
            view('layouts/footer');
    }

    public function update()
    {
        $idProcedimiento = (int) $this->request->getPost('id_procedimiento');

        $actual = $this->tarifarioModel->getProcedimientoId($idProcedimiento);

        $nombreRule = 'required';

        if (!$actual || $this->request->getPost('nombre') !== $actual->nombre) {
            $nombreRule .= '|is_unique[procedimiento.nombre]';
        }

        $rules = [
            'id_procedimiento' => 'required',
            'concepto'         => 'required',
            'medida'           => 'required',
            'categoria'        => 'required',
            'nombre'           => $nombreRule,
            'precio'           => 'required',
            'estado'           => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->editar($idProcedimiento);
        }

        $data = [
            'id_tipoconcepto'    => $this->request->getPost('concepto'),
            'id_medida'          => $this->request->getPost('medida'),
            'codi_cat'           => $this->request->getPost('categoria'),
            'nombre'             => $this->request->getPost('nombre'),
            'prec_procedimiento' => $this->request->getPost('precio'),
            'fecha_registro'     => date('Y-m-d H:i:s'),
            'estado'             => $this->request->getPost('estado'),
        ];

        if ($this->tarifarioModel->updateProcedimiento($idProcedimiento, $data)) {
            session()->setFlashdata('success', 'Procedimiento actualizado correctamente.');
            return redirect()->to(base_url('tarifario'));
        }

        return redirect()->to(base_url('tarifario/editar/' . $idProcedimiento));
    }

    public function anularPago()
    {
        $id = (int) $this->request->getGet('id');

        $this->tarifarioModel->updateProcedimiento($id, [
            'estado' => 'N',
        ]);

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}
