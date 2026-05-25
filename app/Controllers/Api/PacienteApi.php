<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class PacienteApi extends BaseController
{
    private const TOKEN_DAYS = 90;
    private const HORA_INICIO = '07:00';
    private const HORA_FIN = '20:00';
    private const INTERVALO_MINUTOS = 15;

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function register()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $required = ['nombres', 'apellidos', 'dni', 'fecha_nacimiento', 'telefono'];
        $errors = $this->requiredErrors($data, $required);

        if ($errors !== []) {
            return $this->failJson('Datos incompletos.', $errors, 422);
        }

        $exists = $this->db->table('paciente')
            ->where('dni_pac', $data['dni'])
            ->countAllResults();

        if ($exists > 0) {
            return $this->failJson('Ya existe un paciente registrado con ese DNI.', [], 409);
        }

        $location = $this->defaultLocation();
        $birthDate = $data['fecha_nacimiento'];

        $paciente = [
            'nomb_pac'         => $data['nombres'],
            'apel_pac'         => $data['apellidos'],
            'edad_pac'         => $this->calculateAge($birthDate),
            'ocupacion'        => $data['ocupacion'] ?? '',
            'lugar_nacimiento' => $data['lugar_nacimiento'] ?? '',
            'dire_pac'         => $data['direccion'] ?? '',
            'telf_pac'         => $data['telefono'],
            'dni_pac'          => $data['dni'],
            'fena_pac'         => $birthDate,
            'sexo_pac'         => $data['sexo'] ?? '',
            'civi_pac'         => $data['estado_civil'] ?? '',
            'afil_pac'         => $data['afiliado'] ?? '',
            'aler_pac'         => $data['alergia'] ?? '',
            'emai_pac'         => $data['email'] ?? '',
            'titu_pac'         => $data['titulo'] ?? '',
            'pais_id'          => $location['pais_id'],
            'departamento_id'  => $location['departamento_id'],
            'provincia_id'     => $location['provincia_id'],
            'distrito_id'      => $location['distrito_id'],
            'observacion'      => $data['observacion'] ?? '',
            'esta_pac'         => 'S',
        ];

        $this->db->transStart();
        $this->db->table('paciente')->insert($paciente);
        $pacienteId = (int) $this->db->insertID();

        foreach (['paciente_enfermedadactual', 'paciente_consulta', 'paciente_exploracion'] as $table) {
            $this->db->table($table)->insert(['codi_pac' => $pacienteId]);
        }

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return $this->failJson('No se pudo registrar el paciente.', [], 500);
        }

        return $this->issueToken($this->getPacienteById($pacienteId), $data['device_name'] ?? null, 201);
    }

    public function login()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $errors = $this->requiredErrors($data, ['dni', 'fecha_nacimiento']);

        if ($errors !== []) {
            return $this->failJson('Datos incompletos.', $errors, 422);
        }

        $paciente = $this->db->table('paciente')
            ->where('dni_pac', $data['dni'])
            ->where('fena_pac', $data['fecha_nacimiento'])
            ->where('esta_pac', 'S')
            ->get()
            ->getRow();

        if (!$paciente) {
            return $this->failJson('Credenciales invalidas.', [], 401);
        }

        return $this->issueToken($paciente, $data['device_name'] ?? null);
    }

    public function logout()
    {
        $token = $this->bearerToken();

        if ($token !== null) {
            $this->db->table('paciente_api_tokens')
                ->where('token_hash', hash('sha256', $token))
                ->update(['revoked_at' => date('Y-m-d H:i:s')]);
        }

        return $this->okJson(['message' => 'Sesion cerrada.']);
    }

    public function perfil()
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        return $this->okJson(['paciente' => $this->formatPaciente($paciente)]);
    }

    public function actualizarPerfil()
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $allowed = [
            'telefono' => 'telf_pac',
            'email' => 'emai_pac',
            'direccion' => 'dire_pac',
            'alergia' => 'aler_pac',
            'observacion' => 'observacion',
        ];

        $update = [];

        foreach ($allowed as $apiField => $dbField) {
            if (array_key_exists($apiField, $data)) {
                $update[$dbField] = $data[$apiField];
            }
        }

        if ($update !== []) {
            $this->db->table('paciente')
                ->where('codi_pac', $paciente->codi_pac)
                ->update($update);
        }

        return $this->okJson([
            'message' => 'Perfil actualizado.',
            'paciente' => $this->formatPaciente($this->getPacienteById((int) $paciente->codi_pac)),
        ]);
    }

    public function especialidades()
    {
        $rows = $this->db->table('especialidad')
            ->select('cod_especialidad AS id, nombre_especialidad AS nombre, descripcion_especialidad AS descripcion')
            ->where('estado_especialidad', 'S')
            ->orderBy('nombre_especialidad', 'ASC')
            ->get()
            ->getResult();

        return $this->okJson(['especialidades' => $rows]);
    }

    public function medicos()
    {
        $builder = $this->db->table('medico')
            ->select('
                medico.codi_med AS id,
                medico.cod_especialidad,
                medico.nomb_med AS nombres,
                medico.apel_med AS apellidos,
                medico.coleg_med AS colegiatura,
                especialidad.nombre_especialidad AS especialidad
            ')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad', 'left')
            ->where('medico.esta_med', 'S')
            ->orderBy('medico.nomb_med', 'ASC');

        $especialidad = $this->request->getGet('especialidad');

        if (!empty($especialidad)) {
            $builder->where('medico.cod_especialidad', $especialidad);
        }

        return $this->okJson(['medicos' => $builder->get()->getResult()]);
    }

    public function disponibilidad()
    {
        $data = $this->request->getGet();
        $errors = $this->requiredErrors($data, ['fecha', 'medico', 'especialidad']);

        if ($errors !== []) {
            return $this->failJson('Datos incompletos.', $errors, 422);
        }

        return $this->okJson([
            'fecha' => $data['fecha'],
            'medico' => (int) $data['medico'],
            'especialidad' => (int) $data['especialidad'],
            'horas' => $this->availableSlots($data['fecha'], (int) $data['medico'], (int) $data['especialidad']),
        ]);
    }

    public function citas()
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        $scope = $this->request->getGet('scope') ?: 'proximas';
        $builder = $this->baseCitasQuery()
            ->where('cita_medica.codi_pac', $paciente->codi_pac);

        if ($scope === 'historial') {
            $builder->where('cita_medica.fech_cit <', date('Y-m-d H:i:s'));
        } else {
            $builder->where('cita_medica.fech_cit >=', date('Y-m-d H:i:s'));
        }

        $citas = array_map(
            fn ($row) => $this->formatCita($row),
            $builder->orderBy('cita_medica.fech_cit', $scope === 'historial' ? 'DESC' : 'ASC')->get()->getResult()
        );

        return $this->okJson(['citas' => $citas]);
    }

    public function crearCita()
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $errors = $this->requiredErrors($data, ['fecha', 'hora', 'medico', 'especialidad', 'motivo']);

        if ($errors !== []) {
            return $this->failJson('Datos incompletos.', $errors, 422);
        }

        $validation = $this->validateSlot($data, (int) $paciente->codi_pac);

        if ($validation !== null) {
            return $validation;
        }

        $cita = [
            'codi_pac'         => $paciente->codi_pac,
            'codi_med'         => $data['medico'],
            'cod_especialidad' => $data['especialidad'],
            'cod_sede'         => $data['sede'] ?? $this->defaultSede(),
            'motivo_consult'   => $data['motivo'],
            'cod_citado'       => 3,
            'fech_cit'         => $data['fecha'] . ' ' . $data['hora'] . ':00',
            'obsv_cit'         => $data['observacion'] ?? 'Solicitado desde app Android',
            'esta_cit'         => 1,
        ];

        $this->db->table('cita_medica')->insert($cita);
        $id = (int) $this->db->insertID();

        return $this->okJson([
            'message' => 'Cita solicitada correctamente.',
            'cita' => $this->formatCita($this->getCitaPaciente($id, (int) $paciente->codi_pac)),
        ], 201);
    }

    public function actualizarCita(int $id)
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        $cita = $this->getCitaPaciente($id, (int) $paciente->codi_pac);

        if (!$cita) {
            return $this->failJson('Cita no encontrada.', [], 404);
        }

        if (strtotime($cita->fech_cit) < time()) {
            return $this->failJson('No se puede modificar una cita pasada.', [], 409);
        }

        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $merged = [
            'fecha' => $data['fecha'] ?? date('Y-m-d', strtotime($cita->fech_cit)),
            'hora' => $data['hora'] ?? date('H:i', strtotime($cita->fech_cit)),
            'medico' => $data['medico'] ?? $cita->codi_med,
            'especialidad' => $data['especialidad'] ?? $cita->cod_especialidad,
            'motivo' => $data['motivo'] ?? $cita->motivo_consult,
        ];

        $validation = $this->validateSlot($merged, (int) $paciente->codi_pac, $id);

        if ($validation !== null) {
            return $validation;
        }

        $this->db->table('cita_medica')
            ->where('codi_cit', $id)
            ->where('codi_pac', $paciente->codi_pac)
            ->update([
                'codi_med' => $merged['medico'],
                'cod_especialidad' => $merged['especialidad'],
                'motivo_consult' => $merged['motivo'],
                'fech_cit' => $merged['fecha'] . ' ' . $merged['hora'] . ':00',
                'obsv_cit' => $data['observacion'] ?? $cita->obsv_cit,
                'cod_citado' => 3,
            ]);

        return $this->okJson([
            'message' => 'Cita actualizada.',
            'cita' => $this->formatCita($this->getCitaPaciente($id, (int) $paciente->codi_pac)),
        ]);
    }

    public function cancelarCita(int $id)
    {
        $paciente = $this->currentPaciente();

        if (!$paciente) {
            return $this->unauthorized();
        }

        $cita = $this->getCitaPaciente($id, (int) $paciente->codi_pac);

        if (!$cita) {
            return $this->failJson('Cita no encontrada.', [], 404);
        }

        if (strtotime($cita->fech_cit) < time()) {
            return $this->failJson('No se puede cancelar una cita pasada.', [], 409);
        }

        $this->db->table('cita_medica')
            ->where('codi_cit', $id)
            ->where('codi_pac', $paciente->codi_pac)
            ->update([
                'cod_citado' => 2,
                'obsv_cit' => trim(($cita->obsv_cit ?? '') . ' Cancelada desde app Android.'),
            ]);

        return $this->okJson(['message' => 'Cita cancelada.']);
    }

    private function issueToken(object $paciente, ?string $deviceName = null, int $status = 200)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_DAYS . ' days'));

        $this->db->table('paciente_api_tokens')->insert([
            'codi_pac' => $paciente->codi_pac,
            'token_hash' => hash('sha256', $token),
            'device_name' => $deviceName,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->okJson([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_at' => $expiresAt,
            'paciente' => $this->formatPaciente($paciente),
        ], $status);
    }

    private function currentPaciente(): ?object
    {
        $token = $this->bearerToken();

        if ($token === null) {
            return null;
        }

        $tokenRow = $this->db->table('paciente_api_tokens')
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

        $this->db->table('paciente_api_tokens')
            ->where('id_token', $tokenRow->id_token)
            ->update(['last_used_at' => date('Y-m-d H:i:s')]);

        return $this->getPacienteById((int) $tokenRow->codi_pac);
    }

    private function bearerToken(): ?string
    {
        $authorization = $this->request->getHeaderLine('Authorization');

        if (preg_match('/Bearer\s+(.+)/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function validateSlot(array $data, int $pacienteId, ?int $ignoreCita = null)
    {
        $fecha = $data['fecha'] ?? '';
        $hora = $data['hora'] ?? '';

        if (!$this->isValidDate($fecha) || !$this->isValidTime($hora)) {
            return $this->failJson('Fecha u hora invalida.', [], 422);
        }

        $dateTime = strtotime($fecha . ' ' . $hora . ':00');

        if ($dateTime === false || $dateTime <= time()) {
            return $this->failJson('La cita debe ser en una fecha futura.', [], 409);
        }

        $available = $this->availableSlots($fecha, (int) $data['medico'], (int) $data['especialidad'], $ignoreCita);
        $slot = array_values(array_filter($available, static fn ($item) => $item['hora'] === $hora))[0] ?? null;

        if (!$slot || !$slot['disponible']) {
            return $this->failJson('El horario seleccionado no esta disponible.', [], 409);
        }

        $sameDay = $this->db->table('cita_medica')
            ->where('codi_pac', $pacienteId)
            ->where('DATE(fech_cit)', $fecha)
            ->whereNotIn('cod_citado', [2])
            ->where('esta_cit', 1);

        if ($ignoreCita !== null) {
            $sameDay->where('codi_cit !=', $ignoreCita);
        }

        if ($sameDay->countAllResults() > 0) {
            return $this->failJson('Ya tienes una cita activa para ese dia.', [], 409);
        }

        return null;
    }

    private function availableSlots(string $fecha, int $medico, int $especialidad, ?int $ignoreCita = null): array
    {
        $taken = $this->db->table('cita_medica')
            ->select('codi_cit, TIME_FORMAT(fech_cit, "%H:%i") AS hora')
            ->where('DATE(fech_cit)', $fecha)
            ->where('codi_med', $medico)
            ->where('cod_especialidad', $especialidad)
            ->where('esta_cit', 1)
            ->whereNotIn('cod_citado', [2]);

        if ($ignoreCita !== null) {
            $taken->where('codi_cit !=', $ignoreCita);
        }

        $occupied = [];

        foreach ($taken->get()->getResult() as $row) {
            $occupied[$row->hora] = true;
        }

        $slots = [];
        $hora = self::HORA_INICIO;

        while ($hora <= self::HORA_FIN) {
            $slotTime = strtotime($fecha . ' ' . $hora . ':00');
            $slots[] = [
                'hora' => $hora,
                'disponible' => empty($occupied[$hora]) && $slotTime > time(),
            ];

            $hora = date('H:i', strtotime('+' . self::INTERVALO_MINUTOS . ' minutes', strtotime($hora)));
        }

        return $slots;
    }

    private function baseCitasQuery()
    {
        return $this->db->table('cita_medica')
            ->select('
                cita_medica.*,
                medico.nomb_med,
                medico.apel_med,
                especialidad.nombre_especialidad,
                tipo_citado.nomb_citado,
                sede.nombre_sede
            ')
            ->join('medico', 'cita_medica.codi_med = medico.codi_med', 'left')
            ->join('especialidad', 'cita_medica.cod_especialidad = especialidad.cod_especialidad', 'left')
            ->join('tipo_citado', 'cita_medica.cod_citado = tipo_citado.cod_citado', 'left')
            ->join('sede', 'cita_medica.cod_sede = sede.cod_sede', 'left')
            ->where('cita_medica.esta_cit', 1);
    }

    private function getCitaPaciente(int $id, int $pacienteId): ?object
    {
        return $this->baseCitasQuery()
            ->where('cita_medica.codi_cit', $id)
            ->where('cita_medica.codi_pac', $pacienteId)
            ->get()
            ->getRow();
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

    private function formatPaciente(object $paciente): array
    {
        return [
            'id' => (int) $paciente->codi_pac,
            'nombres' => $paciente->nomb_pac,
            'apellidos' => $paciente->apel_pac,
            'dni' => $paciente->dni_pac,
            'fecha_nacimiento' => $paciente->fena_pac,
            'telefono' => $paciente->telf_pac,
            'email' => $paciente->emai_pac,
            'direccion' => $paciente->dire_pac,
            'estado' => $paciente->esta_pac,
        ];
    }

    private function getPacienteById(int $id): ?object
    {
        return $this->db->table('paciente')
            ->where('codi_pac', $id)
            ->get()
            ->getRow();
    }

    private function defaultSede(): ?int
    {
        return $this->db->table('sede')
            ->select('cod_sede')
            ->where('estado_sede', 'S')
            ->orderBy('cod_sede', 'ASC')
            ->get()
            ->getRow()
            ->cod_sede ?? null;
    }

    private function defaultLocation(): array
    {
        return [
            'pais_id' => $this->firstId('paises', 'id') ?? 1,
            'departamento_id' => $this->firstId('departamento', 'departamento_id') ?? 1,
            'provincia_id' => $this->firstId('provincia', 'provincia_id') ?? 1,
            'distrito_id' => $this->firstId('distrito', 'distrito_id') ?? 1,
        ];
    }

    private function firstId(string $table, string $field): ?int
    {
        if (!$this->db->tableExists($table) || !in_array($field, $this->db->getFieldNames($table), true)) {
            return null;
        }

        $row = $this->db->table($table)
            ->select($field)
            ->orderBy($field, 'ASC')
            ->get(1)
            ->getRow();

        return $row ? (int) $row->{$field} : null;
    }

    private function requiredErrors(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = 'El campo es obligatorio.';
            }
        }

        return $errors;
    }

    private function calculateAge(string $birthDate): string
    {
        $birth = strtotime($birthDate);

        if ($birth === false) {
            return '0';
        }

        return (string) date_diff(date_create(date('Y-m-d', $birth)), date_create(date('Y-m-d')))->y;
    }

    private function isValidDate(string $date): bool
    {
        $parsed = date_create_from_format('Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function isValidTime(string $time): bool
    {
        return (bool) preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time);
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
