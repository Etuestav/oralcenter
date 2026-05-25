<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturacionElectronicaModel extends Model
{
    protected $table      = 'facturacion_electronica';
    protected $primaryKey = 'id_facturacion';
    protected $returnType = 'object';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'id_com',
        'cod_sede',
        'tipo_comprobante',
        'serie',
        'correlativo',
        'fecha_emision',
        'hora_emision',
        'moneda',
        'codi_pac',
        'tipo_documento_cliente',
        'numero_documento_cliente',
        'razon_social_cliente',
        'direccion_cliente',
        'tipo_documento_relacionado',
        'documento_relacionado',
        'motivo_codigo',
        'motivo_descripcion',
        'op_gravada',
        'igv',
        'total',
        'estado',
        'sunat_estado',
        'xml_path',
        'xml_hash',
        'cdr_path',
        'sunat_mensaje',
    ];

    public function listar(array $filtros = []): array
    {
        $builder = $this->select('
                facturacion_electronica.*,
                paciente.nomb_pac,
                paciente.apel_pac,
                comprobante.id_com,
                comprobante.serie_com,
                comprobante.secuencia_com,
                sede.nombre_sede
            ')
            ->join('paciente', 'facturacion_electronica.codi_pac = paciente.codi_pac', 'left')
            ->join('comprobante', 'facturacion_electronica.id_com = comprobante.id_com', 'left')
            ->join('sede', 'facturacion_electronica.cod_sede = sede.cod_sede', 'left');

        if (!empty($filtros['desde'])) {
            $builder->where('fecha_emision >=', $filtros['desde']);
        }

        if (!empty($filtros['hasta'])) {
            $builder->where('fecha_emision <=', $filtros['hasta']);
        }

        if (!empty($filtros['cliente'])) {
            $builder->groupStart()
                ->like('razon_social_cliente', $filtros['cliente'])
                ->orLike('numero_documento_cliente', $filtros['cliente'])
                ->groupEnd();
        }

        if (!empty($filtros['tipo_comprobante'])) {
            $builder->where('tipo_comprobante', $filtros['tipo_comprobante']);
        }

        return $builder->orderBy('id_facturacion', 'DESC')
            ->findAll();
    }

    public function obtener(int|string $id): ?object
    {
        $comprobante = $this->where('id_facturacion', $id)->first();

        if (!$comprobante) {
            return null;
        }

        $comprobante->detalle = $this->db->table('facturacion_electronica_detalle')
            ->where('id_facturacion', $id)
            ->orderBy('id_facturacion_detalle', 'ASC')
            ->get()
            ->getResult();

        return $comprobante;
    }

    public function obtenerPorNumero(string $numero): ?object
    {
        $numero = strtoupper(trim($numero));
        $numero = str_replace(' ', '', $numero);

        if (!preg_match('/^([A-Z0-9]+)-0*([0-9]+)$/', $numero, $matches)) {
            return null;
        }

        $serie = $matches[1];
        $correlativo = (int) $matches[2];

        $comprobante = $this->where('serie', $serie)
            ->where('correlativo', $correlativo)
            ->first();

        if (!$comprobante) {
            return null;
        }

        return $this->obtener((int) $comprobante->id_facturacion);
    }

    public function siguienteCorrelativo(string $tipoComprobante, string $serie): int
    {
        $row = $this->selectMax('correlativo')
            ->where('tipo_comprobante', $tipoComprobante)
            ->where('serie', $serie)
            ->first();

        return ((int) ($row->correlativo ?? 0)) + 1;
    }

    public function registrar(array $cabecera, array $detalle): int
    {
        $this->db->transStart();

        $this->insert($cabecera);
        $id = (int) $this->getInsertID();

        foreach ($detalle as $item) {
            $item['id_facturacion'] = $id;
            $this->db->table('facturacion_electronica_detalle')->insert($this->normalizarDetalleInsert($item));
        }

        $this->db->transComplete();

        return $id;
    }

    public function actualizarXml(int|string $id, string $path, string $hash): bool
    {
        return $this->update($id, [
            'xml_path'     => $path,
            'xml_hash'     => $hash,
            'estado'       => 'xml_generado',
            'sunat_estado' => 'pendiente_envio',
        ]);
    }

    public function pendientesSunat(int $limit = 50): array
    {
        return $this->groupStart()
                ->whereIn('sunat_estado', ['pendiente', 'pendiente_envio', 'rechazado', 'error'])
                ->orWhere('sunat_estado', null)
                ->groupEnd()
            ->groupStart()
                ->where('cdr_path', null)
                ->orWhere('cdr_path', '')
                ->groupEnd()
            ->where('xml_path IS NOT NULL', null, false)
            ->where('xml_path !=', '')
            ->orderBy('id_facturacion', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    public function boletasPendientesResumen(string $fecha): array
    {
        if ($fecha === '') {
            $fecha = date('Y-m-d');
        }

        return $this->where('tipo_comprobante', '03')
            ->where('fecha_emision', $fecha)
            ->groupStart()
                ->whereIn('sunat_estado', ['pendiente', 'pendiente_envio', 'rechazado', 'error'])
                ->orWhere('sunat_estado', null)
                ->orWhere('sunat_estado', '')
            ->groupEnd()
            ->orderBy('serie', 'ASC')
            ->orderBy('correlativo', 'ASC')
            ->findAll();
    }

    public function documentosParaBaja(array $filtros = []): array
    {
        $builder = $this->whereIn('tipo_comprobante', ['01', '03', '07', '08']);

        if (!empty($filtros['fecha'])) {
            $builder->where('fecha_emision', $filtros['fecha']);
        }

        if (!empty($filtros['cliente'])) {
            $builder->groupStart()
                ->like('razon_social_cliente', $filtros['cliente'])
                ->orLike('numero_documento_cliente', $filtros['cliente'])
                ->groupEnd();
        }

        return $builder
            ->whereNotIn('sunat_estado', ['baja_enviada', 'baja_aceptada', 'anulado'])
            ->orderBy('id_facturacion', 'DESC')
            ->findAll();
    }

    public function obtenerPorComprobante(int|string $idCom): ?object
    {
        return $this->where('id_com', $idCom)->first();
    }

    public function registrarDesdeComprobante(int|string $idCom): ?int
    {
        $existente = $this->obtenerPorComprobante($idCom);

        if ($existente) {
            $this->asegurarDetalle($existente);

            return (int) $existente->id_facturacion;
        }

        $comprobante = $this->obtenerComprobanteInterno($idCom);

        if (!$comprobante) {
            return null;
        }

        $tipoComprobante = $this->tipoComprobanteSunat($comprobante);

        if ($tipoComprobante === null) {
            return null;
        }

        $serie = $this->serieSunat((string) $comprobante->serie_com, $tipoComprobante);
        $correlativo = (int) ltrim((string) $comprobante->secuencia_com, '0');
        $correlativo = $correlativo > 0
            ? $correlativo
            : $this->siguienteCorrelativo($tipoComprobante, $serie);

        $existentePorNumero = $this->where('tipo_comprobante', $tipoComprobante)
            ->where('serie', $serie)
            ->where('correlativo', $correlativo)
            ->first();

        if ($existentePorNumero) {
            if (empty($existentePorNumero->id_com)) {
                $this->update($existentePorNumero->id_facturacion, ['id_com' => $idCom]);
                $existentePorNumero->id_com = $idCom;
            }

            $this->asegurarDetalle($existentePorNumero);

            return (int) $existentePorNumero->id_facturacion;
        }

        $total = round((float) ($comprobante->total_comp ?? 0), 2);
        $opGravada = round($total / 1.18, 2);
        $igv = round($total - $opGravada, 2);
        $cliente = trim(($comprobante->nomb_pac ?? '') . ' ' . ($comprobante->apel_pac ?? ''));

        $cabecera = [
            'id_com'                    => $idCom,
            'cod_sede'                  => $this->defaultSede(),
            'tipo_comprobante'          => $tipoComprobante,
            'serie'                     => $serie,
            'correlativo'               => $correlativo,
            'fecha_emision'             => $comprobante->fecha_com ?? date('Y-m-d'),
            'hora_emision'              => date('H:i:s'),
            'moneda'                    => 'PEN',
            'codi_pac'                  => $comprobante->codi_pac ?? null,
            'tipo_documento_cliente'    => $tipoComprobante === '01' ? '6' : '1',
            'numero_documento_cliente'  => $tipoComprobante === '01'
                ? (string) ($comprobante->ruc_pac ?? $comprobante->dni_pac ?? '00000000')
                : (string) ($comprobante->dni_pac ?? '00000000'),
            'razon_social_cliente'      => $cliente !== '' ? $cliente : 'CLIENTE VARIOS',
            'direccion_cliente'         => $comprobante->dire_pac ?? null,
            'op_gravada'                => $opGravada,
            'igv'                       => $igv,
            'total'                     => $total,
            'estado'                    => 'registrado',
            'sunat_estado'              => 'pendiente',
        ];

        $detalle = $this->detalleParaComprobante($comprobante, $total, $opGravada, $igv);

        return $this->registrar($cabecera, $detalle);
    }

    public function asegurarDetalle(object $comprobante): object
    {
        $idFacturacion = (int) ($comprobante->id_facturacion ?? 0);

        if ($idFacturacion <= 0) {
            return $comprobante;
        }

        $existeDetalle = $this->db->table('facturacion_electronica_detalle')
            ->where('id_facturacion', $idFacturacion)
            ->countAllResults();

        if ($existeDetalle > 0) {
            return $this->obtener($idFacturacion) ?? $comprobante;
        }

        $detalle = [];
        $interno = null;

        if (!empty($comprobante->id_com)) {
            $interno = $this->obtenerComprobanteInterno($comprobante->id_com);
        }

        $total = round((float) ($comprobante->total ?? $interno->total_comp ?? 0), 2);
        $opGravada = round($total / 1.18, 2);
        $igv = round($total - $opGravada, 2);
        $detalle = $interno
            ? $this->detalleParaComprobante($interno, $total, $opGravada, $igv)
            : $this->detallePagoGenerico($comprobante, $total, $opGravada, $igv);

        foreach ($detalle as $item) {
            $item['id_facturacion'] = $idFacturacion;
            $this->db->table('facturacion_electronica_detalle')->insert($this->normalizarDetalleInsert($item));
        }

        return $this->obtener($idFacturacion) ?? $comprobante;
    }

    private function normalizarDetalleInsert(array $item): array
    {
        return [
            'id_facturacion'  => $item['id_facturacion'],
            'codigo_producto' => $item['codigo_producto'] ?? null,
            'descripcion'     => $item['descripcion'] ?? 'Servicio odontologico',
            'unidad'          => $item['unidad'] ?? 'NIU',
            'cantidad'        => $item['cantidad'] ?? 1,
            'valor_unitario'  => $item['valor_unitario'] ?? 0,
            'precio_unitario' => $item['precio_unitario'] ?? 0,
            'igv'             => $item['igv'] ?? 0,
            'total'           => $item['total'] ?? 0,
        ];
    }

    private function detalleParaComprobante(object $comprobante, float $total, float $opGravada, float $igv): array
    {
        $detalle = [];

        if (!empty($comprobante->codi_tra)) {
            $detalle = $this->detalleDesdeTratamiento((int) $comprobante->codi_tra);
            $totalDetalle = round(array_reduce($detalle, static fn ($carry, $item) => $carry + (float) ($item['total'] ?? 0), 0.0), 2);

            if (!empty($detalle) && abs($totalDetalle - $total) <= 0.02) {
                return $detalle;
            }
        }

        return $this->detallePagoGenerico($comprobante, $total, $opGravada, $igv);
    }

    private function detallePagoGenerico(object $comprobante, float $total, float $opGravada, float $igv): array
    {
        $tratamiento = $comprobante->codi_tra ?? $comprobante->id_com ?? 'SERV';
        $asunto = trim((string) ($comprobante->asunto_tra ?? ''));

        return [[
            'codigo_producto' => 'TRAT-' . $tratamiento,
            'descripcion'     => $asunto !== '' ? 'Pago de tratamiento: ' . $asunto : 'Pago de tratamiento',
            'unidad'          => 'NIU',
            'cantidad'        => 1,
            'valor_unitario'  => $opGravada,
            'precio_unitario' => $total,
            'igv'             => $igv,
            'total'           => $total,
        ]];
    }

    public function itemsTicket(object $comprobante): array
    {
        if (!empty($comprobante->id_com)) {
            $interno = $this->obtenerComprobanteInterno($comprobante->id_com);
            if ($interno && !empty($interno->codi_tra)) {
                $items = $this->detalleDesdeTratamiento((int) $interno->codi_tra);
                if (!empty($items)) {
                    return array_map(static fn ($item) => (object) $item, $items);
                }
            }
        }

        return $comprobante->detalle ?? [];
    }

    private function detalleDesdeTratamiento(int $idTratamiento): array
    {
        $rows = $this->db->table('tratamiento_detalle')
            ->select('
                tratamiento_detalle.*,
                procedimiento.nombre
            ')
            ->join('procedimiento', 'tratamiento_detalle.id_procedimiento = procedimiento.id_procedimiento')
            ->where('tratamiento_detalle.codi_tra', $idTratamiento)
            ->get()
            ->getResult();

        $detalle = [];

        foreach ($rows as $row) {
            $cantidad = round((float) ($row->cant_tradet ?? 1), 2);
            $precioUnitario = round((float) ($row->preciounit_tradet ?? 0), 2);
            $total = round((float) ($row->subtotal_tradet ?? ($cantidad * $precioUnitario)), 2);
            $precioFinal = $cantidad > 0 ? round($total / $cantidad, 2) : $precioUnitario;
            $valorUnitario = round($precioFinal / 1.18, 2);
            $igv = round($total - ($total / 1.18), 2);

            $detalle[] = [
                'codigo_producto' => 'PROC-' . ($row->id_procedimiento ?? ''),
                'descripcion' => (string) ($row->nombre ?? 'Procedimiento odontologico'),
                'unidad' => 'NIU',
                'cantidad' => $cantidad,
                'valor_unitario' => $valorUnitario,
                'precio_unitario' => $precioFinal,
                'igv' => $igv,
                'total' => $total,
            ];
        }

        return $detalle;
    }
    private function obtenerComprobanteInterno(int|string $idCom): ?object
    {
        return $this->db->table('comprobante')
            ->select('
                comprobante.*,
                tipo_documento.descripcion,
                tipo_documento.abreviatura,
                tratamiento.codi_tra,
                tratamiento.asunto_tra,
                tratamiento.codi_pac,
                paciente.nomb_pac,
                paciente.apel_pac,
                paciente.dni_pac,
                paciente.dire_pac
            ')
            ->join('tipo_documento', 'comprobante.cod_tipodocumento = tipo_documento.cod_tipodocumento', 'left')
            ->join('pago_comprobante', 'comprobante.id_com = pago_comprobante.id_com', 'left')
            ->join('pago', 'pago_comprobante.id_pago = pago.id_pago', 'left')
            ->join('tratamiento', 'pago.codi_tra = tratamiento.codi_tra', 'left')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac', 'left')
            ->where('comprobante.id_com', $idCom)
            ->groupBy('comprobante.id_com')
            ->get()
            ->getRow();
    }

    private function defaultSede(): ?int
    {
        $sede = $this->db->table('sede')
            ->select('cod_sede')
            ->where('estado_sede', 'S')
            ->orderBy('cod_sede', 'ASC')
            ->get()
            ->getRow();

        return $sede ? (int) $sede->cod_sede : null;
    }

    private function serieSunat(string $serie, string $tipoComprobante): string
    {
        $serie = strtoupper(trim($serie));
        $numero = preg_replace('/\D+/', '', $serie);

        if ($tipoComprobante === '01' && !str_starts_with($serie, 'F')) {
            return 'F' . str_pad($numero !== '' ? $numero : $serie, 3, '0', STR_PAD_LEFT);
        }

        if ($tipoComprobante === '03' && !str_starts_with($serie, 'B')) {
            return 'B' . str_pad($numero !== '' ? $numero : $serie, 3, '0', STR_PAD_LEFT);
        }

        return $serie;
    }
    private function tipoComprobanteSunat(object $comprobante): ?string
    {
        $descripcion = strtoupper((string) ($comprobante->descripcion ?? ''));
        $abreviatura = strtoupper((string) ($comprobante->abreviatura ?? ''));
        $texto = $descripcion . ' ' . $abreviatura;

        if (str_contains($texto, 'FACTURA')) {
            return '01';
        }

        if (str_contains($texto, 'BOLETA')) {
            return '03';
        }

        return null;
    }
}
