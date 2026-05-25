<?php

namespace App\Controllers\Citas;

use App\Controllers\BaseController;
use App\Models\Modelgeneral;
use App\Models\CitasModel;

class Registrar extends BaseController
{
    protected Modelgeneral $modelGeneral;
    protected CitasModel $citasModel;

    public function __construct()
    {
        $this->modelGeneral = new Modelgeneral();
        $this->citasModel   = new CitasModel();

        helper('general');
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        $data = [
            'especialidad' => db_connect()
                ->table('especialidad')
                ->orderBy('nombre_especialidad', 'ASC')
                ->get()
                ->getResult(),

            'sedes'       => $this->modelGeneral->getTable('sede'),
            'tipo_citado' => $this->modelGeneral->getTable('tipo_citado'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/citas/registrar', $data) .
            view('layouts/footer');
    }

    public function getMedicos()
    {
        $especialidad = $this->request->getPost('especialidad');

        $medicos = db_connect()
            ->table('medico')
            ->where('cod_especialidad', $especialidad)
            ->get()
            ->getResult();

        return $this->response->setJSON($medicos);
    }

    public function getCitaDia()
    {
        $hora = '07:00';
        $arrayHoras = [];

        for ($i = 0; $hora !== '20:15'; $i++) {
            $arrayHoras[$i]['hora'] = $hora;

            $hora = date('H:i', strtotime('+15 minute', strtotime($hora)));
        }

        $fecha        = $this->request->getGet('fecha');
        $medico       = $this->request->getGet('medico');
        $especialidad = $this->request->getGet('especialidad');

        $dia = $this->citasModel->citasDia($fecha, $medico, $especialidad);

        foreach ($dia->getResult() as $d) {
            $horaCita = date('H:i', strtotime($d->fech_cit));

            foreach ($arrayHoras as $key => $value) {
                if ($horaCita === $value['hora']) {
                    $arrayHoras[$key]['id_cita']     = $d->codi_cit;
                    $arrayHoras[$key]['id_paciente'] = $d->codi_pac;
                    $arrayHoras[$key]['paciente']    = $d->nomb_pac . ' ' . $d->apel_pac;
                    $arrayHoras[$key]['observacion'] = $d->obsv_cit;
                    $arrayHoras[$key]['estado']      = $d->nomb_citado;
                    $arrayHoras[$key]['fila']        = filaEstadoCita($d->cod_citado);

                    $arrayHoras[$key]['btn-editar'] = '
                        <button class="editar btn btn-xs btn-warning"
                                data-id="' . $d->codi_cit . '">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                }
            }
        }

        $queryMedico = $this->modelGeneral->getTableWhereRow(
            'medico',
            ['codi_med' => $medico]
        );

        $ocupados = count($dia->getResult());

        return $this->response->setJSON([
            'horas'         => $arrayHoras,
            'especialidad'  => $especialidad,
            'medico'        => $medico,
            'medico_nombre' => ($queryMedico->nomb_med ?? '') . ' ' . ($queryMedico->apel_med ?? ''),
            'fecha'         => $fecha,
            'ocupados'      => $ocupados,
            'disponibles'   => 52 - $ocupados,
        ]);
    }

    public function getPacientes()
    {
        $q = $this->request->getGet('q') ?? '';

        $pacientes = $this->citasModel->buscarPacientes($q);

        return $this->response->setJSON($pacientes);
    }

    public function guardarCita()
    {
        $rules = [
            'hora'         => 'required',
            'fecha'        => 'required',
            'medico'       => 'required',
            'especialidad' => 'required',
            'paciente'     => 'required',
            'motivo'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'codi_pac'         => $this->request->getPost('paciente'),
            'codi_med'         => $this->request->getPost('medico'),
            'cod_especialidad' => $this->request->getPost('especialidad'),
            'motivo_consult'   => $this->request->getPost('motivo'),
            'cod_sede'         => $this->request->getPost('sede'),
            'cod_citado'       => $this->request->getPost('codigo'),
            'fech_cit'         => $this->request->getPost('fecha') . ' ' . $this->request->getPost('hora') . ':00',
            'obsv_cit'         => $this->request->getPost('observacion'),
            'esta_cit'         => 1,
        ];

        $insert = $this->modelGeneral->insertRegist('cita_medica', $data);

        if ($insert !== null && $this->request->getPost('notificar') === 'on') {
            // $this->enviarNotificacion($insert);
        }

        return $this->response->setJSON([
            'success' => $insert !== null,
        ]);
    }

    public function validaPacienteUnico()
    {
        $paciente = $this->request->getPost('paciente');
        $medico   = $this->request->getPost('medico');
        $fecha    = $this->request->getPost('fecha');

        $count = db_connect()
            ->table('cita_medica')
            ->where('codi_pac', $paciente)
            ->where('codi_med', $medico)
            ->where('esta_cit', 1)
            ->where('DATE(fech_cit)', $fecha)
            ->whereNotIn('cita_medica.cod_citado', [2])
            ->countAllResults();

        return $this->response->setBody($count === 0 ? 'true' : 'false');
    }

    public function editarCita()
    {
        $rules = [
            'id'                 => 'required',
            'hora'               => 'required',
            'fecha'              => 'required',
            'medicoEditar'       => 'required',
            'especialidadEditar' => 'required',
            'motivo'             => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'codi_med'         => $this->request->getPost('medicoEditar'),
            'cod_especialidad' => $this->request->getPost('especialidadEditar'),
            'motivo_consult'   => $this->request->getPost('motivo'),
            'cod_sede'         => $this->request->getPost('sede'),
            'cod_citado'       => $this->request->getPost('codigo'),
            'fech_cit'         => $this->request->getPost('fecha') . ' ' . $this->request->getPost('hora') . ':00',
            'obsv_cit'         => $this->request->getPost('observacion'),
        ];

        $where = [
            'codi_cit' => $this->request->getPost('id'),
        ];

        $edit = $this->modelGeneral->editRegist(
            'cita_medica',
            $where,
            $data
        );

        if ($edit && $this->request->getPost('notificar') === 'on') {
            // $this->enviarNotificacion($this->request->getPost('id'));
        }

        return $this->response->setJSON([
            'success' => (bool) $edit,
        ]);
    }
}
