<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;

class UsuarioApi extends BaseController
{
    private const TOKEN_DAYS = 30;

    protected UsuariosModel $usuariosModel;
    protected $db;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
        $this->db = db_connect();
    }

    public function login()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $errors = $this->requiredErrors($data, ['username', 'password']);

        if ($errors !== []) {
            return $this->failJson('Datos incompletos.', $errors, 422);
        }

        $usuario = $this->usuariosModel->login(
            (string) $data['username'],
            sha1((string) $data['password'])
        );

        if (!$usuario) {
            return $this->failJson('Usuario o contraseña incorrectos.', [], 401);
        }

        return $this->issueToken($usuario, $data['device_name'] ?? null);
    }

    public function me()
    {
        $usuario = $this->currentUsuario();

        if (!$usuario) {
            return $this->unauthorized();
        }

        return $this->okJson(['usuario' => $this->formatUsuario($usuario)]);
    }

    public function logout()
    {
        $token = $this->bearerToken();

        if ($token !== null) {
            $this->db->table('usuario_api_tokens')
                ->where('token_hash', hash('sha256', $token))
                ->update(['revoked_at' => date('Y-m-d H:i:s')]);
        }

        return $this->okJson(['message' => 'Sesion cerrada.']);
    }

    private function issueToken(object $usuario, ?string $deviceName = null)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_DAYS . ' days'));

        $this->db->table('usuario_api_tokens')->insert([
            'codi_usu' => $usuario->codi_usu,
            'token_hash' => hash('sha256', $token),
            'device_name' => $deviceName,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->okJson([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_at' => $expiresAt,
            'usuario' => $this->formatUsuario($usuario),
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

    private function formatUsuario(object $usuario): array
    {
        $medico = $this->db->table('medico')
            ->select('codi_med, cod_especialidad')
            ->where('codi_usu', $usuario->codi_usu)
            ->get()
            ->getRow();

        return [
            'id' => (int) $usuario->codi_usu,
            'username' => $usuario->logi_usu,
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'email' => $usuario->email,
            'telefono' => $usuario->telefono,
            'documento' => [
                'tipo' => $usuario->tipo_documento,
                'numero' => $usuario->documento,
            ],
            'rol' => [
                'id' => (int) $usuario->codi_rol,
                'nombre' => $usuario->nomb_rol ?? null,
            ],
            'medico' => $medico ? [
                'id' => (int) $medico->codi_med,
                'especialidad_id' => (int) $medico->cod_especialidad,
            ] : null,
        ];
    }

    private function bearerToken(): ?string
    {
        $authorization = $this->request->getHeaderLine('Authorization');

        if (preg_match('/Bearer\s+(.+)/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        return null;
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
