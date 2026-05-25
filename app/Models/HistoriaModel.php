<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriaModel extends Model
{
    protected $DBGroup = 'default';

    public function getHistoria(array $data): array
    {
        $medico = session()->get('medico');
        $rol    = session()->get('rol');

        $desde = $data['desde'] ?? null;
        $hasta = $data['hasta'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */
        $builderLike = $this->db->table('cita_medica')
            ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac')
            ->whereNotIn('cita_medica.cod_citado', [2]);

        if (!empty($desde)) {
            $builderLike->where('DATE(cita_medica.fech_cit) >=', $desde);
        }

        if (!empty($hasta)) {
            $builderLike->where('DATE(cita_medica.fech_cit) <=', $hasta);
        }

        if ($rol == 1) {
            $builderLike->where('cita_medica.codi_med', $medico);
        }

        if (!empty($data['nombresApellidos'])) {
            $builderLike->like('CONCAT(paciente.nomb_pac, " ", paciente.apel_pac)', $data['nombresApellidos']);
        }

        $total = $builderLike->countAllResults();

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */
        $builder = $this->db->table('cita_medica');

        $builder->select('
            cita_medica.codi_pac,
            CONCAT(paciente.nomb_pac, " ", paciente.apel_pac) AS NombresApellidos,
            paciente.edad_pac,
            paciente.dni_pac,
            DATE(cita_medica.fech_cit) AS fecha_cita,
            TIME(cita_medica.fech_cit) AS hora_cita,
            paciente.esta_pac
        ');

        $builder->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac')
            ->whereNotIn('cita_medica.cod_citado', [2]);

        if (!empty($desde)) {
            $builder->where('DATE(cita_medica.fech_cit) >=', $desde);
        }

        if (!empty($hasta)) {
            $builder->where('DATE(cita_medica.fech_cit) <=', $hasta);
        }

        if ($rol == 1) {
            $builder->where('cita_medica.codi_med', $medico);
        }

        if (!empty($data['nombresApellidos'])) {
            $builder->like('CONCAT(paciente.nomb_pac, " ", paciente.apel_pac)', $data['nombresApellidos']);
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $estado = '';

            if ($q->esta_pac === 'S') {
                $estado = '<label class="label label-success">Activo</label>';
            } elseif ($q->esta_pac === 'N') {
                $estado = '<label class="label label-info" style="text-align:center;">Inactivo</label>';
            }

            $opciones = '
                <div class="btn-footer text-center">
                    <a href="' . base_url('historia/ver/' . $q->codi_pac) . '"
                    class="btn btn-info btn-xs"
                    style="text-align:center">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
            ';

            $row[] = [
                $q->codi_pac,
                $q->NombresApellidos,
                $q->edad_pac,
                $q->dni_pac,
                $q->fecha_cita,
                $q->hora_cita,
                $estado,
                $opciones,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getHistoriaListado(array $data): array
    {
        $medico = session()->get('medico');
        $rol    = session()->get('rol');

        $builder = $this->db->table('cita_medica');

        $builder->select('
            cita_medica.codi_pac,
            CONCAT(paciente.nomb_pac, " ", paciente.apel_pac) AS paciente,
            paciente.edad_pac,
            paciente.dni_pac,
            paciente.dire_pac,
            DATE(cita_medica.fech_cit) AS fecha_cita,
            TIME(cita_medica.fech_cit) AS hora_cita,
            paciente.esta_pac
        ');

        $builder->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac')
            ->whereNotIn('cita_medica.cod_citado', [2]);

        if (!empty($data['desde'])) {
            $builder->where('DATE(cita_medica.fech_cit) >=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $builder->where('DATE(cita_medica.fech_cit) <=', $data['hasta']);
        }

        if ($rol == 1) {
            $builder->where('cita_medica.codi_med', $medico);
        }

        if (!empty($data['nombresApellidos'])) {
            $builder->like('CONCAT(paciente.nomb_pac, " ", paciente.apel_pac)', $data['nombresApellidos']);
        }

        return $builder->orderBy('cita_medica.fech_cit', 'DESC')
            ->get()
            ->getResult();
    }

    public function getAlergias(array $data): array
    {
        $builderLike = $this->db->table('paciente_alergia')
            ->join('alergia', 'paciente_alergia.cod_ale = alergia.cod_ale')
            ->where('codi_pac', $data['paciente'])
            ->where('pacale_estado', 1);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('paciente_alergia')
            ->join('alergia', 'paciente_alergia.cod_ale = alergia.cod_ale')
            ->where('codi_pac', $data['paciente'])
            ->where('pacale_estado', 1);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $boton = '
                <div class="btn-footer text-center">
                    <button data-id="' . $q->pacale_id . '"
                            class="editar-alergia btn btn-warning btn-xs"
                            data-toggle="modal"
                            data-target="#ModalEditarAlergia">
                        Editar
                    </button>

                    <button data-id="' . $q->pacale_id . '"
                            class="anular-alergia btn btn-danger btn-xs">
                        Anular
                    </button>
                </div>
            ';

            $row[] = [
                $q->pacale_id,
                $q->nombre_ale,
                $q->pacale_observacion,
                $boton,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getDiagnostico(array $data): array
    {
        $builderLike = $this->db->table('paciente_diagnostico')
            ->where('codi_pac', $data['paciente'])
            ->where('pacdiag_estado', 1);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('paciente_diagnostico');

        $builder->select('pacdiag_id, pacdiag_fecha, codi_enf01, a.desc_enf AS diagnostico01')
            ->join('enfermedad AS a', 'paciente_diagnostico.codi_enf01 = a.codi_enf', 'left')
            ->where('codi_pac', $data['paciente'])
            ->where('pacdiag_estado', 1);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $boton = '
                <div class="btn-footer text-center">
                    <button data-id="' . $q->pacdiag_id . '"
                            class="editar-diagnostico btn btn-warning btn-xs"
                            data-toggle="modal"
                            data-target="#ModalEditarDiagnostico">
                        Editar
                    </button>

                    <button data-id="' . $q->pacdiag_id . '"
                            class="anular-diagnostico btn btn-danger btn-xs">
                        Anular
                    </button>
                </div>
            ';

            $row[] = [
                $q->pacdiag_fecha,
                $q->codi_enf01,
                $q->diagnostico01,
                $boton,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getPlacas(array $data): array
    {
        $builderLike = $this->db->table('paciente_placa')
            ->where('codi_pac', $data['paciente'])
            ->where('pla_estado', 1);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('paciente_placa')
            ->where('codi_pac', $data['paciente'])
            ->where('pla_estado', 1);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $boton = '
                <div class="btn-footer text-center">
                    <button data-id="' . $q->pla_id . '"
                            class="anular-placa btn btn-danger btn-xs">
                        Anular
                    </button>

                    <button data-id="' . $q->pla_id . '"
                            class="editar-placa btn btn-warning btn-xs">
                        Editar
                    </button>
                </div>
            ';

            $archivo = '
                <a data-fancybox="gallery"
                   href="' . base_url('vendor/uploads/placas/' . $q->pla_archivo) . '">
                    <i class="fa fa-image"></i> Ver placa
                </a>
            ';

            $row[] = [
                $q->pla_fecha,
                $q->pla_nombre,
                $q->pla_notas,
                $archivo,
                $boton,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getHistoriaImprimir(int|string $id): ?object
    {
        $paciente = $this->db->table('paciente')
            ->select("
                paciente.*,
                CONCAT(apel_pac, ' ', nomb_pac) AS paciente,
                dni_pac AS dni,
                CASE sexo_pac WHEN 'M' THEN 'Masculino' ELSE 'Femenino' END AS sexo,
                telf_pac AS telefono,
                dire_pac AS direccion,
                CASE estudios_pac
                    WHEN 'S' THEN 'SECUNDARIA COMPLETA'
                    WHEN 'U' THEN 'SUPERIOR'
                    WHEN 'P' THEN 'PRIMARIA COMPLETA'
                    WHEN 'N' THEN 'NO ESPECIFICA'
                    ELSE COALESCE(estudios_pac, '')
                END AS estudios,
                CASE civi_pac
                    WHEN 'C' THEN 'Casado(a)'
                    WHEN 'S' THEN 'Soltero(a)'
                    WHEN 'V' THEN 'Viudo(a)'
                    WHEN 'D' THEN 'Divorciado(a)'
                    ELSE COALESCE(civi_pac, '')
                END AS civil,
                civi_pac AS estadocivil,
                edad_pac AS edad,
                emai_pac AS email,
                paises.nombre AS pais,
                lugar_nacimiento AS lugarnacimiento,
                fena_pac AS fechanacimiento,
                departamento.departamento_nombre AS departamento,
                provincia.provincia_nombre AS provincia,
                distrito.distrito_nombre AS distrito,
                paciente_enfermedadactual.motivo_enfact AS motivo,
                paciente_enfermedadactual.tiempo_enfact AS enfermedad,
                CASE paciente_enfermedadactual.medicam_enfact WHEN 1 THEN 'Si' ELSE 'No' END AS medicamento,
                paciente_enfermedadactual.nommedicam_enfact AS nombmedi,
                paciente_enfermedadactual.antecper_enfact AS antecpersonales,
                paciente_enfermedadactual.antecfam_enfact AS antecfamiliares,
                CASE paciente_consulta.ortod_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consulortodoncia,
                paciente_consulta.ortodtexto_paccon AS respuesta1,
                CASE paciente_consulta.medic_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consutmedicamento,
                paciente_consulta.medictexto_paccon AS respuesta2,
                CASE paciente_consulta.alergico_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consulalergico,
                paciente_consulta.alergicotexto_paccon AS respuesta3,
                CASE paciente_consulta.hosp_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consulhospi,
                paciente_consulta.hosptexto_paccon AS respuesta4,
                CASE paciente_consulta.trans_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consultranstorno,
                paciente_consulta.transtexto_paccon AS respuesta5,
                paciente_consulta.padece_paccon AS padece,
                CASE paciente_consulta.cepilla_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consulcepilla,
                paciente_consulta.cepillatexto_paccon AS respuesta6,
                CASE paciente_consulta.presion_paccon WHEN 1 THEN 'Si' ELSE 'No' END AS consulpresion,
                paciente_consulta.presiontexto_paccon AS respuesta7,
                paciente_exploracion.pa_exp AS exploracion,
                paciente_exploracion.pulso_exp AS pulso,
                paciente_exploracion.temperat_exp AS temperatura,
                paciente_exploracion.fc_exp AS fcardiaca,
                paciente_exploracion.frec_exp AS frespiratoria,
                paciente_exploracion.peso_exp AS peso,
                paciente_exploracion.talla_exp AS talla,
                paciente_exploracion.masa_exp AS imc,
                paciente_exploracion.clinico_exp AS exmclinico,
                paciente_exploracion.complement_exp AS exmcomplet,
                paciente_exploracion.odontoesto_exp AS exmodonto
            ")
            ->join('paises', 'paciente.pais_id = paises.id')
            ->join('departamento', 'paciente.departamento_id = departamento.departamento_id')
            ->join('provincia', 'paciente.provincia_id = provincia.provincia_id')
            ->join('distrito', 'paciente.distrito_id = distrito.distrito_id')
            ->join('paciente_enfermedadactual', 'paciente_enfermedadactual.codi_pac = paciente.codi_pac')
            ->join('paciente_consulta', 'paciente_consulta.codi_pac = paciente.codi_pac')
            ->join('paciente_exploracion', 'paciente_exploracion.codi_pac = paciente.codi_pac')
            ->where('paciente.codi_pac', $id)
            ->get()
            ->getRow();

        if (!$paciente) {
            return null;
        }

        $paciente->estudios = $paciente->estudios ?? '';
        $paciente->civil    = $paciente->civil ?? '';
        $paciente->entero   = $paciente->entero ?? '';

        $paciente->alergias = $this->db->table('paciente_alergia')
            ->join('alergia', 'paciente_alergia.cod_ale = alergia.cod_ale')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        $paciente->odinicial = $this->db->table('paciente_odontograma')
            ->join('hallazgos', 'paciente_odontograma.id_hal = hallazgos.id_hal')
            ->join('dientes', 'paciente_odontograma.numero_die = dientes.numero_die')
            ->where('paciente_odontograma.pacodo_tipo', 'Inicial')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        $paciente->evolucionado = $this->db->table('paciente_odontograma')
            ->join('hallazgos', 'paciente_odontograma.id_hal = hallazgos.id_hal')
            ->join('dientes', 'paciente_odontograma.numero_die = dientes.numero_die')
            ->where('paciente_odontograma.pacodo_tipo', 'Evolucion')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        $paciente->evolucion = $this->db->table('paciente_evolucion')
            ->join('especialidad', 'paciente_evolucion.cod_especialidad = especialidad.cod_especialidad')
            ->join('medico', 'paciente_evolucion.codi_med = medico.codi_med')
            ->where('paciente_evolucion.pacevol_estado', '1')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        $paciente->receta = $this->db->table('paciente_receta')
            ->where('paciente_receta.pacrec_estado', '1')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        $paciente->pacdiagnostico = $this->db->table('paciente_diagnostico')
            ->join('enfermedad', 'paciente_diagnostico.codi_enf01 = enfermedad.codi_enf')
            ->where('paciente_diagnostico.pacdiag_estado', '1')
            ->where('codi_pac', $id)
            ->get()
            ->getResult();

        return $paciente;
    }

    public function getEvolucion(array $data): array
    {
        $builderLike = $this->db->table('paciente_evolucion')
            ->join('medico', 'paciente_evolucion.codi_med = medico.codi_med')
            ->join('especialidad', 'paciente_evolucion.cod_especialidad = especialidad.cod_especialidad')
            ->where('codi_pac', $data['paciente'])
            ->where('pacevol_estado', 1);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('paciente_evolucion');

        $builder->select('
                paciente_evolucion.*,
                fecha_evolucion,
                pacevol_descripcion,
                CONCAT(nomb_med, " ", apel_med) AS medico,
                nombre_especialidad
            ')
            ->join('medico', 'paciente_evolucion.codi_med = medico.codi_med')
            ->join('especialidad', 'paciente_evolucion.cod_especialidad = especialidad.cod_especialidad')
            ->where('codi_pac', $data['paciente'])
            ->where('pacevol_estado', 1);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $boton = '
                <div class="btn-footer text-center">
                    <button data-id="' . $q->pacevol_id . '"
                            class="editar-evolucion btn btn-warning btn-xs"
                            data-toggle="modal"
                            data-target="#ModalEditarEvolucion">
                        Editar
                    </button>

                    <button data-id="' . $q->pacevol_id . '"
                            class="anular-evolucion btn btn-danger btn-xs">
                        Anular
                    </button>
                </div>
            ';

            $row[] = [
                $q->fecha_evolucion,
                $q->pacevol_descripcion,
                $q->medico,
                $q->nombre_especialidad,
                $boton,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getListadoCitas(array $data): array
    {
        $builderLike = $this->db->table('cita_medica')
            ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad')
            ->join('medico', 'cita_medica.codi_med = medico.codi_med')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado')
            ->where('codi_pac', $data['paciente']);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('cita_medica');

        $builder->select('
                cita_medica.*,
                codi_cit,
                fech_cit,
                nombre_especialidad,
                CONCAT(nomb_med, " ", apel_med) AS medico,
                nomb_citado
            ')
            ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad')
            ->join('medico', 'cita_medica.codi_med = medico.codi_med')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado')
            ->where('codi_pac', $data['paciente']);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $botones = '
                <div class="btn-footer text-center">
                    <button data-id="' . $q->codi_cit . '"
                            class="editar-citahistoria btn btn-warning btn-xs"
                            data-toggle="modal"
                            data-target="#ModalEditarCitaHistoria">
                        Editar
                    </button>
                </div>
            ';

            $row[] = [
                $q->codi_cit,
                $q->fech_cit,
                $q->nombre_especialidad,
                $q->medico,
                $q->nomb_citado,
                $botones,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }

    public function getTrataHistoria(array $data): array
    {
        $builderLike = $this->db->table('tratamiento')
            ->where('codi_pac', $data['paciente'])
            ->where('estado_tra', 1);

        $total = $builderLike->countAllResults();

        $builder = $this->db->table('tratamiento');

        $builder->select('tratamiento.*, codi_tra, asunto_tra, fecha_tra, total_tra, estadopago_tra')
            ->where('codi_pac', $data['paciente'])
            ->where('estado_tra', 1);

        if (!empty($data['estado']) && $data['estado'] === 'Activo') {
            $builder->where('estado_tra', TRATAMIENTO_ACTIVO);
        } elseif (!empty($data['estado']) && $data['estado'] === 'Anulado') {
            $builder->where('estado_tra', TRATAMIENTO_ANULADO);
        }

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        $query = $builder->get();

        $row = [];

        foreach ($query->getResult() as $q) {
            $estado = '';

            if ($q->estadopago_tra == POR_COBRAR) {
                $estado = '<label class="label label-warning">Por Cobrar</label>';
            } elseif ($q->estadopago_tra == PROCESO) {
                $estado = '<label class="label label-info">Proceso</label>';
            } elseif ($q->estadopago_tra == COBRADO) {
                $estado = '<label class="label label-success">Cobrado</label>';
            } elseif ($q->estadopago_tra == ANULADO) {
                $estado = '<label class="label label-danger">Anulado</label>';
            }

            $opciones = '
                <a href="' . base_url('tratamiento/imprimirTratamiento/' . $q->codi_tra) . '"
                   target="_blank">
                    <i class="fa fa-print" aria-hidden="true"></i>
                </a>
            ';

            $row[] = [
                $q->codi_tra,
                $q->asunto_tra,
                $q->fecha_tra,
                $q->total_tra,
                $estado,
                $opciones,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $row,
        ];
    }
}
