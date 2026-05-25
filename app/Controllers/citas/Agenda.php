<?php

namespace App\Controllers\Citas;

use App\Controllers\BaseController;
use App\Models\Modelgeneral;
use App\Models\CitasModel;
use APP\Helpers\General_helper;

class Agenda extends BaseController
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
            return redirect()->to(base_url(''));
        }

        $data = [
            'especialidad' => db_connect()
                ->table('especialidad')
                ->orderBy('nombre_especialidad', 'ASC')
                ->get()
                ->getResult(),
                
            'medicos' => db_connect()
                ->table('medico')
                ->orderBy('nomb_med', 'ASC')
                ->get()
                ->getResult(),

            'estados' => $this->modelGeneral->getTable('tipo_citado'),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/citas/agenda', $data) .
            view('layouts/footer');
    }

    public function calendario()
    {
        if (!session()->get('login')) {
            return $this->response->setJSON([
                'success' => 0,
                'message' => 'No autorizado',
            ]);
        }

        $from = (int) $this->request->getGet('from');
        $to   = (int) $this->request->getGet('to');

        $desde = date('Y-m-d', $from / 1000);

        $hastaTimestamp = strtotime('-1 day', (int) ($to / 1000));
        $hasta = date('Y-m-d', $hastaTimestamp);

        $medico       = $this->request->getGet('medico');
        $especialidad = $this->request->getGet('especialidad');
        $estado       = $this->request->getGet('estado');

        $citas = $this->citasModel->getCitas(
            $desde,
            $hasta,
            $medico,
            $especialidad,
            $estado
        );

        $semana = $this->citasDeSemana($citas);
        $result = [];

        foreach ($citas as $c) {
            $inicio = strtotime($c->fech_cit);
            $fin    = strtotime('+15 minute', $inicio);

            $result[] = [
                'id'       => $c->codi_cit,
                'start'    => $inicio * 1000,
                'end'      => $fin * 1000,
                'class'    => 'event-' . classAgendaCita($c->cod_citado),
                'title'    => $c->nomb_pac . ' ' . $c->apel_pac,
                'url'      => base_url('cita/getCita/' . $c->codi_cit),
                'paciente' => $c->codi_pac,
                'estado_cuenta_url' => base_url('cita/estadoCuenta/' . $c->codi_pac),
                'inicio'   => date('H:i:s', $inicio),
                'fin'      => date('H:i:s', $fin),
                'telefono' => $c->telf_pac,
                'medico'   => $c->nomb_med . ' ' . $c->apel_med,
                'estado'   => estadoCita($c->cod_citado),
            ];

            $semana[date('H:i', $inicio)][date('N', $inicio)] =
                $c->nomb_pac . ' ' . $c->apel_pac;
        }

        return $this->response->setJSON([
            'success' => 1,
            'semana'  => $semana,
            'result'  => $result,
            'medico'  => $medico,
            'espe'    => $especialidad,
        ]);
    }

    private function citasDeSemana(array $citas): array
    {
        $horas = [];

        foreach ($citas as $c) {
            $hora = date('H:i', strtotime($c->fech_cit));

            if (!in_array($hora, $horas, true)) {
                $horas[] = $hora;
            }
        }

        $semana = [];

        foreach ($horas as $hora) {
            $semana[$hora] = [
                'Hora' => $hora,
                1 => '',
                2 => '',
                3 => '',
                4 => '',
                5 => '',
                6 => '',
                7 => '',
            ];
        }

        return $semana;
    }

    public function getCita(int $id): string
    {
        if (!session()->get('login')) {
            return '';
        }

        $data['cita'] = $this->citasModel->getCita($id);

        return view('admin/citas/modal_calendario', $data);
    }

    public function estadoCuenta(int $pacienteId)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $db = db_connect();

        $paciente = $db->table('paciente')
            ->where('codi_pac', $pacienteId)
            ->get()
            ->getRow();

        if (!$paciente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Paciente no encontrado');
        }

        $tratamientos = $db->table('tratamiento')
            ->select('
                tratamiento.*,
                medico.nomb_med,
                medico.apel_med
            ')
            ->join('medico', 'tratamiento.codi_med = medico.codi_med', 'left')
            ->where('tratamiento.codi_pac', $pacienteId)
            ->orderBy('tratamiento.fecha_tra', 'DESC')
            ->get()
            ->getResult();

        $totalTratamientos = 0.0;
        $totalPagado = 0.0;

        foreach ($tratamientos as $tratamiento) {
            $tratamiento->pagos = $db->table('pago')
                ->select('
                    pago.*,
                    comprobante.serie_com,
                    comprobante.secuencia_com,
                    tipo_documento.abreviatura
                ')
                ->join('pago_comprobante', 'pago.id_pago = pago_comprobante.id_pago', 'left')
                ->join('comprobante', 'pago_comprobante.id_com = comprobante.id_com', 'left')
                ->join('tipo_documento', 'comprobante.cod_tipodocumento = tipo_documento.cod_tipodocumento', 'left')
                ->where('pago.codi_tra', $tratamiento->codi_tra)
                ->orderBy('pago.num_pago', 'ASC')
                ->get()
                ->getResult();

            $totalTratamientos += (float) ($tratamiento->total_tra ?? 0);

            foreach ($tratamiento->pagos as $pago) {
                if ((int) ($pago->estado_pago ?? 0) === (defined('FINALIZADO') ? FINALIZADO : 2)) {
                    $totalPagado += (float) ($pago->monto_pago ?? 0);
                }
            }
        }

        $data = [
            'paciente' => $paciente,
            'tratamientos' => $tratamientos,
            'totalTratamientos' => $totalTratamientos,
            'totalPagado' => $totalPagado,
            'saldo' => $totalTratamientos - $totalPagado,
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/citas/estado_cuenta', $data) .
            view('layouts/footer');
    }
}
