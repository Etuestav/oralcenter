<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $db = db_connect();
        $hoy = date('Y-m-d');
        $inicioMes = date('Y-m-01');
        $finMes = date('Y-m-t');

        $data = [
            'metricas' => [
                'citas_hoy' => $db->table('cita_medica')
                    ->where('DATE(fech_cit)', $hoy)
                    ->where('esta_cit', 1)
                    ->whereNotIn('cod_citado', [2])
                    ->countAllResults(),
                'citas_pendientes' => $db->table('cita_medica')
                    ->where('fech_cit >=', date('Y-m-d H:i:s'))
                    ->whereIn('cod_citado', [3, 5, 6, 9])
                    ->where('esta_cit', 1)
                    ->countAllResults(),
                'pacientes' => $db->table('paciente')
                    ->where('esta_pac', 'S')
                    ->countAllResults(),
                'medicos' => $db->table('medico')
                    ->where('esta_med', 'S')
                    ->countAllResults(),
                'tratamientos_activos' => $db->table('tratamiento')
                    ->where('estado_tra', defined('TRATAMIENTO_ACTIVO') ? TRATAMIENTO_ACTIVO : 1)
                    ->countAllResults(),
                'por_cobrar' => (float) ($db->table('tratamiento')
                    ->selectSum('total_tra', 'total')
                    ->whereIn('estadopago_tra', [
                        defined('POR_COBRAR') ? POR_COBRAR : 1,
                        defined('PROCESO') ? PROCESO : 2,
                    ])
                    ->get()
                    ->getRow()
                    ->total ?? 0),
                'ingresos_mes' => (float) ($db->table('pago')
                    ->selectSum('monto_pago', 'total')
                    ->where('estado_pago', defined('FINALIZADO') ? FINALIZADO : 2)
                    ->where('fecharegistro_pago >=', $inicioMes)
                    ->where('fecharegistro_pago <=', $finMes)
                    ->get()
                    ->getRow()
                    ->total ?? 0),
                'cpe_pendientes' => $db->table('facturacion_electronica')
                    ->whereIn('sunat_estado', ['pendiente', 'pendiente_envio'])
                    ->countAllResults(),
            ],
            'proximasCitas' => $db->table('cita_medica')
                ->select('
                    cita_medica.codi_cit,
                    cita_medica.fech_cit,
                    paciente.nomb_pac,
                    paciente.apel_pac,
                    medico.nomb_med,
                    medico.apel_med,
                    especialidad.nombre_especialidad,
                    tipo_citado.nomb_citado
                ')
                ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac', 'left')
                ->join('medico', 'cita_medica.codi_med = medico.codi_med', 'left')
                ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad', 'left')
                ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado', 'left')
                ->where('DATE(cita_medica.fech_cit) >=', $hoy)
                ->where('cita_medica.esta_cit', 1)
                ->whereNotIn('cita_medica.cod_citado', [2])
                ->orderBy('cita_medica.fech_cit', 'ASC')
                ->limit(8)
                ->get()
                ->getResult(),
            'tratamientosRecientes' => $db->table('tratamiento')
                ->select('
                    tratamiento.codi_tra,
                    tratamiento.fecha_tra,
                    tratamiento.asunto_tra,
                    tratamiento.total_tra,
                    tratamiento.estadopago_tra,
                    CONCAT(paciente.nomb_pac, " ", paciente.apel_pac) AS paciente
                ')
                ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac', 'left')
                ->orderBy('tratamiento.codi_tra', 'DESC')
                ->limit(6)
                ->get()
                ->getResult(),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('home/inicio', $data) .
            view('layouts/footer');
    }
}
