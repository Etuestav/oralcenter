<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class DocsApi extends BaseController
{
    public function usuarios()
    {
        $path = ROOTPATH . 'docs/openapi-usuarios.json';

        if (!is_file($path)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Documento OpenAPI no encontrado.']);
        }

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setBody(file_get_contents($path));
    }

    public function pacientes()
    {
        $path = ROOTPATH . 'docs/openapi-pacientes.json';

        if (!is_file($path)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Documento OpenAPI no encontrado.']);
        }

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setBody(file_get_contents($path));
    }
}
