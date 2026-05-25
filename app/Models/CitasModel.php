<?php

namespace App\Models;

use CodeIgniter\Model;

class CitasModel extends Model
{
    protected $DBGroup = 'default';

    public function citasDia(
        string $fecha,
        int|string $medico,
        int|string $especialidad
    ) {
        return $this->db->table('cita_medica')
            ->select('
                codi_cit,
                fech_cit,
                nomb_pac,
                apel_pac,
                paciente.codi_pac,
                obsv_cit,
                cita_medica.cod_citado,
                nomb_citado
            ')
            ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado')
            ->where('DATE(fech_cit)', $fecha)
            ->where('cita_medica.codi_med', $medico)
            ->where('cita_medica.cod_especialidad', $especialidad)
            ->whereNotIn('cita_medica.cod_citado', [2])
            ->get();
    }

    public function buscarPacientes(string $paciente): array
    {
        $paciente = trim($paciente);

        $builder = $this->db->table('paciente')
            ->select('
                codi_pac AS id,
                TRIM(CONCAT(COALESCE(apel_pac, ""), " ", COALESCE(nomb_pac, ""))) AS text
            ');

        if ($paciente !== '') {
            $builder
                ->groupStart()
                    ->like('CONCAT(COALESCE(apel_pac, ""), " ", COALESCE(nomb_pac, ""))', $paciente)
                    ->orLike('CONCAT(COALESCE(nomb_pac, ""), " ", COALESCE(apel_pac, ""))', $paciente)
                    ->orLike('dni_pac', $paciente)
                    ->orLike('codi_pac', $paciente)
                ->groupEnd();
        }

        return $builder
            ->orderBy('apel_pac', 'ASC')
            ->orderBy('nomb_pac', 'ASC')
            ->limit(50)
            ->get()
            ->getResult();
    }

    public function getCita(int|string $cita): ?object
    {
        return $this->db->table('cita_medica')
            ->select('
                cita_medica.*,
                nomb_pac,
                apel_pac,
                medico.codi_med,
                nomb_med,
                apel_med,
                especialidad.cod_especialidad,
                sexo_pac,
                emai_pac,
                nomb_citado,
                nombre_sede
            ')
            ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac')
            ->join('medico', 'cita_medica.codi_med = medico.codi_med')
            ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado')
            ->join('sede', 'cita_medica.cod_sede = sede.cod_sede')
            ->where('cita_medica.codi_cit', $cita)
            ->get()
            ->getRow();
    }

    public function getCitas(
        string $desde,
        string $hasta,
        int|string|null $medico = '',
        int|string|null $especialidad = '',
        int|string|null $estado = ''
    ): array {
        $builder = $this->db->table('cita_medica');

        $builder->select('
            cita_medica.codi_cit,
            paciente.codi_pac,
            nomb_pac,
            apel_pac,
            nomb_med,
            apel_med,
            fech_cit,
            cita_medica.cod_citado,
            nomb_citado,
            telf_pac,
            esta_cit
        ');

        $builder->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac');
        $builder->join('medico', 'cita_medica.codi_med = medico.codi_med');
        $builder->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado');

        $builder->where('DATE(fech_cit) >=', $desde);
        $builder->where('DATE(fech_cit) <=', $hasta);

        if (!empty($medico)) {
            $builder->where('medico.codi_med', $medico);
        }

        if (!empty($especialidad)) {
            $builder->where('medico.cod_especialidad', $especialidad);
        }

        if (!empty($estado)) {
            $builder->where('cita_medica.cod_citado', $estado);
        }

        return $builder
            ->orderBy('fech_cit', 'ASC')
            ->get()
            ->getResult();
    }
}
