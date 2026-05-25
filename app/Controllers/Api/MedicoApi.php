<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class MedicoApi extends BaseController
{
    private const HORA_INICIO = '07:00';
    private const HORA_FIN = '20:00';
    private const INTERVALO_MINUTOS = 15;

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function agenda()
    {
        $usuario = $this->currentUsuario();

        if (!$usuario) {
            return $this->unauthorized();
        }

        $medico = $this->medicoForUsuario($usuario);

        if (!$medico) {
            return $this->failJson('El usuario autenticado no tiene un medico asociado.', [], 403);
        }

        $desde = $this->request->getGet('desde') ?: date('Y-m-d');
        $hasta = $this->request->getGet('hasta') ?: date('Y-m-d', strtotime('+30 days'));

        $citas = array_map(
            fn ($row) => $this->formatCita($row),
            $this->baseCitasQuery()
                ->where('cita_medica.codi_med', $medico->codi_med)
                ->where('DATE(cita_medica.fech_cit) >=', $desde)
                ->where('DATE(cita_medica.fech_cit) <=', $hasta)
                ->orderBy('cita_medica.fech_cit', 'ASC')
                ->get()
                ->getResult()
        );

        return $this->okJson([
            'medico' => $this->formatMedico($medico),
            'citas' => $citas,
            'pacientes' => $this->pacientesForMedico((int) $medico->codi_med),
        ]);
    }

    public function pacientes()
    {
        $usuario = $this->currentUsuario();

        if (!$usuario) {
            return $this->unauthorized();
        }

        $medico = $this->medicoForUsuario($usuario);

        if (!$medico) {
            return $this->failJson('El usuario autenticado no tiene un medico asociado.', [], 403);
        }

        return $this->okJson(['pacientes' => $this->pacientesForMedico((int) $medico->codi_med)]);
    }

    public function disponibilidad()
    {
        $usuario = $this->currentUsuario();

        if (!$usuario) {
            return $this->unauthorized();
        }

        $medico = $this->medicoForUsuario($usuario);

        if (!$medico) {
            return $this->failJson('El usuario autenticado no tiene un medico asociado.', [], 403);
        }

        $fecha = $this->request->getGet('fecha') ?: date('Y-m-d');

        return $this->okJson([
            'fecha' => $fecha,
            'medico' => (int) $medico->codi_med,
            'especialidad' => (int) $medico->cod_especialidad,
            'horas' => $this->availableSlots($fecha, (int) $medico->codi_med, (int) $medico->cod_especialidad),
        ]);
    }

    public function cambiarEstado(int $id)
    {
        $usuario = $this->currentUsuario();

        if (!$usuario) {
            return $this->unauthorized();
        }

        $medico = $this->medicoForUsuario($usuario);

        if (!$medico) {
            return $this->failJson('El usuario autenticado no tiene un medico asociado.', [], 403);
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $estado = $data['estado'] ?? '';
        $estadoId = $this->estadoId($estado);

        if ($estadoId === null) {
            return $this->failJson('Estado no permitido.', ['estado' => 'Use pendiente, confirmado, atendido o cancelado.'], 422);
        }

        $cita = $this->baseCitasQuery()
            ->where('cita_medica.codi_cit', $id)
            ->where('cita_medica.codi_med', $medico->codi_med)
            ->get()
            ->getRow();

        if (!$cita) {
            return $this->failJson('Cita no encontrada para este medico.', [], 404);
        }

        $this->db->table('cita_medica')
            ->where('codi_cit', $id)
            ->where('codi_med', $medico->codi_med)
            ->update([
                'cod_citado' => $estadoId,
                'obsv_cit' => trim(($cita->obsv_cit ?? '') . ' Actualizado desde app medico.'),
            ]);

        return $this->okJson([
            'message' => 'Estado de cita actualizado.',
            'cita' => $this->formatCita(
                $this->baseCitasQuery()
                    ->where('cita_medica.codi_cit', $id)
                    ->where('cita_medica.codi_med', $medico->codi_med)
                    ->get()
                    ->getRow()
            ),
        ]);
    }

    private function currentUsuario(): ?object
    {
        $token = $this->bearerToken();

        if ($token === null) {
            return null;
        }

        $tokenRow = $this->db->table('usuario_api_tokens')
            ->where('token_hash', hash('sha256', $token))
            ->where('revoked_at IS NULL')
            ->groupStart()
                ->where('expires_at IS NULL')
                ->orWhere('expires_at >=', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->get()
            ->getRow();

        if (!$tokenRow) {
            return null;
        }

        $this->db->table('usuario_api_tokens')
            ->where('id_token', $tokenRow->id_token)
            ->update(['last_used_at' => date('Y-m-d H:i:s')]);

        return $this->db->table('usuario')
            ->select('usuario.*, rol.nomb_rol')
            ->join('rol', 'usuario.codi_rol = rol.codi_rol', 'left')
            ->where('usuario.codi_usu', $tokenRow->codi_usu)
            ->where('usuario.esta_usu', 1)
            ->get()
            ->getRow();
    }

    private function medicoForUsuario(object $usuario): ?object
    {
        return $this->db->table('medico')
            ->select('medico.*, especialidad.nombre_especialidad')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad', 'left')
            ->where('medico.codi_usu', $usuario->codi_usu)
            ->get()
            ->getRow();
    }

    private function baseCitasQuery()
    {
        return $this->db->table('cita_medica')
            ->select('
                cita_medica.*,
                paciente.nomb_pac,
                paciente.apel_pac,
                paciente.telf_pac,
                paciente.emai_pac,
                paciente.dni_pac,
                medico.nomb_med,
                medico.apel_med,
                especialidad.nombre_especialidad,
                tipo_citado.nomb_citado,
                sede.nombre_sede
            ')
            ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac', 'left')
            ->join('medico', 'cita_medica.codi_med = medico.codi_med', 'left')
            ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad', 'left')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado', 'left')
            ->join('sede', 'cita_medica.cod_sede = sede.cod_sede', 'left')
            ->where('cita_medica.esta_cit', 1);
    }

    private function pacientesForMedico(int $medicoId): array
    {
        $rows = $this->db->table('cita_medica')
            ->select('
                paciente.codi_pac AS id,
                paciente.nomb_pac AS nombres,
                paciente.apel_pac AS apellidos,
                paciente.telf_pac AS telefono,
                paciente.emai_pac AS email,
                paciente.dni_pac AS dni,
                MAX(cita_medica.fech_cit) AS ultima_cita
            ')
            ->join('paciente', 'cita_medica.codi_pac = paciente.codi_pac', 'left')
            ->where('cita_medica.codi_med', $medicoId)
            ->where('cita_medica.esta_cit', 1)
            ->groupBy('paciente.codi_pac, paciente.nomb_pac, paciente.apel_pac, paciente.telf_pac, paciente.emai_pac, paciente.dni_pac')
            ->orderBy('ultima_cita', 'DESC')
            ->get()
            ->getResult();

        return array_map(static function ($row): array {
            return [
                'id' => (int) $row->id,
                'nombres' => $row->nombres,
                'apellidos' => $row->apellidos,
                'telefono' => $row->telefono,
                'email' => $row->email,
                'dni' => $row->dni,
                'ultima_cita' => $row->ultima_cita,
            ];
        }, $rows);
    }

    private function availableSlots(string $fecha, int $medico, int $especialidad): array
    {
        $taken = $this->db->table('cita_medica')
            ->select('TIME_FORMAT(fech_cit, "%H:%i") AS hora')
            ->where('DATE(fech_cit)', $fecha)
            ->where('codi_med', $medico)
            ->where('cod_especialidad', $especialidad)
            ->where('esta_cit', 1)
            ->whereNotIn('cod_citado', [2])
            ->get()
            ->getResult();

        $occupied = [];
        foreach ($taken as $row) {
            $occupied[$row->hora] = true;
        }

        $slots = [];
        $hora = self::HORA_INICIO;

        while ($hora <= self::HORA_FIN) {
            $slots[] = [
                'hora' => $hora,
                'disponible' => empty($occupied[$hora]),
                'ocupada' => !empty($occupied[$hora]),
            ];

            $hora = date('H:i', strtotime('+' . self::INTERVALO_MINUTOS . ' minutes', strtotime($hora)));
        }

        return $slots;
    }

    private function formatMedico(object $medico): array
    {
        return [
            'id' => (int) $medico->codi_med,
            'nombres' => $medico->nomb_med,
            'apellidos' => $medico->apel_med,
            'especialidad_id' => (int) $medico->cod_especialidad,
            'especialidad' => $medico->nombre_especialidad,
        ];
    }

    private function formatCita(?object $cita): ?array
    {
        if (!$cita) {
            return null;
        }

        return [
            'id' => (int) $cita->codi_cit,
            'fecha' => date('Y-m-d', strtotime($cita->fech_cit)),
            'hora' => date('H:i', strtotime($cita->fech_cit)),
            'motivo' => $cita->motivo_consult,
            'observacion' => $cita->obsv_cit,
            'estado' => [
                'id' => (int) $cita->cod_citado,
                'nombre' => $cita->nomb_citado,
            ],
            'paciente' => [
                'id' => (int) $cita->codi_pac,
                'nombre' => trim(($cita->nomb_pac ?? '') . ' ' . ($cita->apel_pac ?? '')),
                'telefono' => $cita->telf_pac,
                'email' => $cita->emai_pac,
                'dni' => $cita->dni_pac,
            ],
            'medico' => [
                'id' => (int) $cita->codi_med,
                'nombre' => trim(($cita->nomb_med ?? '') . ' ' . ($cita->apel_med ?? '')),
            ],
            'especialidad' => [
                'id' => (int) $cita->cod_especialidad,
                'nombre' => $cita->nombre_especialidad,
            ],
            'sede' => [
                'id' => (int) ($cita->cod_sede ?? 0),
                'nombre' => $cita->nombre_sede,
            ],
        ];
    }

    private function estadoId(string $estado): ?int
    {
        return match (strtolower(trim($estado))) {
            'atendido', 'atendida' => 1,
            'cancelado', 'cancelada' => 2,
            'pendiente', 'solicitada', 'solicitado' => 3,
            'confirmado', 'confirmada' => 6,
            default => null,
        };
    }

    private function bearerToken(): ?string
    {
        $authorization = $this->request->getHeaderLine('Authorization');

        if (preg_match('/Bearer\s+(.+)/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function okJson(array $data, int $status = 200)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON(['success' => true] + $data);
    }

    private function failJson(string $message, array $errors = [], int $status = 400)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ]);
    }

    private function unauthorized()
    {
        return $this->failJson('Token invalido o expirado.', [], 401);
    }
}