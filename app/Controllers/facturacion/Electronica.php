<?php

namespace App\Controllers\Facturacion;

use App\Controllers\BaseController;
use App\Models\FacturacionConfigModel;
use App\Models\FacturacionElectronicaModel;
use App\Models\ModelGeneral;
use DOMDocument;
use SoapClient;
use SoapFault;
use ZipArchive;

class Electronica extends BaseController
{
    protected FacturacionElectronicaModel $facturacionModel;
    protected FacturacionConfigModel $configModel;
    protected ModelGeneral $modelGeneral;

    public function __construct()
    {
        $this->facturacionModel = new FacturacionElectronicaModel();
        $this->configModel = new FacturacionConfigModel();
        $this->modelGeneral = new ModelGeneral();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $filtros = [
            'desde'            => $this->request->getGet('desde'),
            'hasta'            => $this->request->getGet('hasta'),
            'cliente'          => $this->request->getGet('cliente'),
            'tipo_comprobante' => $this->request->getGet('tipo_comprobante'),
        ];

        $data = [
            'comprobantes' => $this->facturacionModel->listar($filtros),
            'filtros'      => $filtros,
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/panel', $data)
            . view('layouts/footer');
    }

    public function nuevo()
    {
        return $this->formularioNuevo();
    }

    public function notaCredito()
    {
        return redirect()->to(base_url('facturacion/electronica/notas?tipo=07'));
    }

    public function notaDebito()
    {
        return redirect()->to(base_url('facturacion/electronica/notas?tipo=08'));
    }

    public function notas()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $tipoNota = (string) ($this->request->getGet('tipo') ?: '07');
        $tipoNota = in_array($tipoNota, ['07', '08'], true) ? $tipoNota : '07';

        $documento = trim((string) $this->request->getGet('documento'));
        $comprobante = null;

        if ($documento !== '') {
            $comprobante = $this->facturacionModel->obtenerPorNumero($documento);

            if (!$comprobante || !in_array((string) $comprobante->tipo_comprobante, ['01', '03'], true)) {
                session()->setFlashdata('error', 'No se encontro una factura o boleta electronica con ese numero.');
                $comprobante = null;
            }
        }

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/notas', [
                'tipoNota'     => $tipoNota,
                'documento'    => $documento,
                'comprobante'  => $comprobante,
            ])
            . view('layouts/footer');
    }

    private function formularioNuevo(string $tipoComprobante = '01')
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data = [
            'pacientes'        => $this->modelGeneral->getTable('paciente'),
            'sedes'            => $this->sedesFacturacion(),
            'tipoComprobante'  => $tipoComprobante,
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/nuevo', $data)
            . view('layouts/footer');
    }

    public function guardar()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $rules = [
            'tipo_comprobante'          => 'required|in_list[01,03,07,08]',
            'serie'                     => 'required',
            'fecha_emision'             => 'required',
            'tipo_documento_cliente'    => 'required',
            'numero_documento_cliente'  => 'required',
            'razon_social_cliente'      => 'required',
            'descripcion.*'             => 'required',
            'cantidad.*'                => 'required',
            'precio_unitario.*'         => 'required',
        ];

        $tipoComprobante = (string) $this->request->getPost('tipo_comprobante');
        if (in_array($tipoComprobante, ['07', '08'], true)) {
            $rules['tipo_documento_relacionado'] = 'required|in_list[01,03]';
            $rules['documento_relacionado'] = 'required';
            $rules['motivo_codigo'] = 'required';
            $rules['motivo_descripcion'] = 'required';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios.');

            return redirect()->back()->withInput();
        }

        $serie = $this->serieSunat((string) $this->request->getPost('serie'), $tipoComprobante);
        $correlativo = $this->facturacionModel->siguienteCorrelativo($tipoComprobante, $serie);
        $detalle = $this->prepararDetalle();
        $totales = $this->calcularTotales($detalle);

        $cabecera = [
            'cod_sede'                 => $this->request->getPost('cod_sede') ?: null,
            'tipo_comprobante'         => $tipoComprobante,
            'serie'                    => $serie,
            'correlativo'              => $correlativo,
            'fecha_emision'            => $this->request->getPost('fecha_emision'),
            'hora_emision'             => date('H:i:s'),
            'moneda'                   => $this->request->getPost('moneda') ?: 'PEN',
            'codi_pac'                 => $this->request->getPost('codi_pac') ?: null,
            'tipo_documento_cliente'   => $this->request->getPost('tipo_documento_cliente'),
            'numero_documento_cliente' => $this->request->getPost('numero_documento_cliente'),
            'razon_social_cliente'     => $this->request->getPost('razon_social_cliente'),
            'direccion_cliente'        => $this->request->getPost('direccion_cliente'),
            'tipo_documento_relacionado' => $this->request->getPost('tipo_documento_relacionado') ?: null,
            'documento_relacionado'     => $this->request->getPost('documento_relacionado') ?: null,
            'motivo_codigo'             => $this->request->getPost('motivo_codigo') ?: null,
            'motivo_descripcion'        => $this->request->getPost('motivo_descripcion') ?: null,
            'op_gravada'               => $totales['op_gravada'],
            'igv'                      => $totales['igv'],
            'total'                    => $totales['total'],
            'estado'                   => 'registrado',
            'sunat_estado'             => 'pendiente',
        ];

        $id = $this->facturacionModel->registrar($cabecera, $detalle);

        session()->setFlashdata('success', 'Comprobante registrado correctamente.');

        return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
    }

    public function ver(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Comprobante no encontrado');
        }

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/ver', ['comprobante' => $comprobante])
            . view('layouts/footer');
    }

    public function desdeComprobante(int $idCom)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $idFacturacion = $this->facturacionModel->registrarDesdeComprobante($idCom);

        if ($idFacturacion === null) {
            session()->setFlashdata('error', 'Solo se puede generar comprobante electronico desde comprobantes tipo Factura o Boleta.');

            return redirect()->to(base_url('tratamiento/comprobante'));
        }

        session()->setFlashdata('success', 'Comprobante electronico vinculado al pago.');

        return redirect()->to(base_url('facturacion/electronica/ver/' . $idFacturacion));
    }

    public function generarXml(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Comprobante no encontrado');
        }

        $config = $this->configuracionParaComprobante($boletas[0]);
        $comprobante = $this->generarXmlComprobante($comprobante, $config);

        if (!$comprobante) {
            session()->setFlashdata('error', 'No se pudo generar el XML del documento.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        session()->setFlashdata('success', 'XML generado. Aun falta firma digital y envio a SUNAT/OSE.');

        return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
    }

    public function imprimirTicket(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Comprobante no encontrado');
        }

        return view('admin/facturacion/electronica/ticket', [
            'comprobante' => $comprobante,
            'config' => $this->configuracionParaComprobante($comprobante),
            'items' => $this->facturacionModel->itemsTicket($comprobante),
        ]);
    }

    private function generarXmlComprobante(object $comprobante, object $config): ?object
    {
        $comprobante = $this->facturacionModel->asegurarDetalle($comprobante);
        $xml = $this->construirXml($comprobante, $config);
        $dir = WRITEPATH . 'facturacion/xml/';

        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            return null;
        }

        $nombre = $this->nombreSunat($comprobante, $config) . '.xml';
        $path = $dir . $nombre;

        if (file_put_contents($path, $xml) === false) {
            return null;
        }

        $this->facturacionModel->actualizarXml((int) $comprobante->id_facturacion, $path, hash('sha256', $xml));

        return $this->facturacionModel->obtener((int) $comprobante->id_facturacion);
    }

    public function descargarXml(int $id)
    {
        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante || empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('XML no encontrado');
        }

        return $this->response->download($comprobante->xml_path, null);
    }

    public function configuracion()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $sedes = $this->sedesFacturacion();
        $codSede = (int) ($this->request->getGet('cod_sede') ?: ($sedes[0]->cod_sede ?? 0));
        $config = $this->configModel->obtener($codSede > 0 ? $codSede : null)
            ?? $this->configDesdeClinica();
        $config->cod_sede = $codSede > 0 ? $codSede : ($config->cod_sede ?? null);

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/configuracion', [
                'config' => $config,
                'sedes' => $sedes,
                'codSedeSeleccionada' => $codSede,
            ])
            . view('layouts/footer');
    }

    public function guardarConfiguracion()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $rules = [
            'ruc_emisor'   => 'required|min_length[11]|max_length[11]',
            'razon_social' => 'required',
            'modo'         => 'required|in_list[beta,produccion]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Debe completar los datos obligatorios de SUNAT.');

            return redirect()->back()->withInput();
        }

        $codSede = (int) ($this->request->getPost('cod_sede') ?: 0);
        $actual = $this->configModel->obtener($codSede > 0 ? $codSede : null);

        $data = [
            'cod_sede'          => $codSede > 0 ? $codSede : null,
            'ruc_emisor'        => $this->request->getPost('ruc_emisor'),
            'razon_social'      => $this->request->getPost('razon_social'),
            'nombre_comercial'  => $this->request->getPost('nombre_comercial'),
            'ubigeo'            => $this->request->getPost('ubigeo'),
            'direccion'         => $this->request->getPost('direccion'),
            'departamento'      => $this->request->getPost('departamento'),
            'provincia'         => $this->request->getPost('provincia'),
            'distrito'          => $this->request->getPost('distrito'),
            'modo'              => $this->request->getPost('modo'),
            'sol_usuario'       => $this->request->getPost('sol_usuario'),
            'sol_clave'         => $this->request->getPost('sol_clave') ?: ($actual->sol_clave ?? null),
            'certificado_clave' => $this->request->getPost('certificado_clave') ?: ($actual->certificado_clave ?? null),
        ];

        $certificado = $this->request->getFile('certificado');

        if ($certificado && $certificado->isValid() && !$certificado->hasMoved()) {
            $extension = strtolower($certificado->getClientExtension());

            if (!in_array($extension, ['pfx', 'p12'], true)) {
                session()->setFlashdata('error', 'El certificado debe ser un archivo .pfx o .p12.');

                return redirect()->back()->withInput();
            }

            $dir = WRITEPATH . 'facturacion/certificados/';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $nombre = 'certificado-' . date('YmdHis') . '.' . $extension;
            $certificado->move($dir, $nombre);

            $data['certificado_path'] = $dir . $nombre;
            $data['certificado_nombre'] = $certificado->getClientName();
        }

        $this->configModel->guardar($data, $codSede > 0 ? $codSede : null);

        session()->setFlashdata('success', 'Configuracion de facturacion guardada.');

        $query = $codSede > 0 ? '?cod_sede=' . $codSede : '';

        return redirect()->to(base_url('facturacion/electronica/configuracion' . $query));
    }

    public function firmarXml(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $comprobante = $this->facturacionModel->obtener($id);
        $config = $comprobante ? $this->configuracionParaComprobante($comprobante) : null;

        if (!$comprobante) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Comprobante no encontrado');
        }

        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path) || $this->xmlDebeRegenerarse($comprobante, $config)) {
            $comprobante = $this->generarXmlComprobante($comprobante, $config) ?? $comprobante;
        }

        if (!$config || empty($config->certificado_path) || empty($config->certificado_clave)) {
            session()->setFlashdata('error', 'Debe configurar el certificado digital antes de firmar.');

            return redirect()->to(base_url('facturacion/electronica/configuracion'));
        }

        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            session()->setFlashdata('error', 'No se pudo generar el XML del documento.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        $xmlFirmado = $this->firmarDocumentoXml(
            file_get_contents($comprobante->xml_path),
            $config->certificado_path,
            $config->certificado_clave
        );

        if ($xmlFirmado === null) {
            session()->setFlashdata('error', 'No se pudo firmar el XML. Revise el certificado y su clave.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        file_put_contents($comprobante->xml_path, $xmlFirmado);

        $this->facturacionModel->update($id, [
            'estado'       => 'xml_firmado',
            'sunat_estado' => 'pendiente_envio',
            'xml_hash'     => hash('sha256', $xmlFirmado),
        ]);

        session()->setFlashdata('success', 'XML firmado correctamente.');

        return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
    }

    public function enviarSunat(int $id)
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $comprobante = $this->facturacionModel->obtener($id);
        $config = $comprobante ? $this->configuracionParaComprobante($comprobante) : $this->configDesdeClinica();

        if (!$comprobante) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Comprobante no encontrado');
        }

        if (($comprobante->sunat_estado ?? '') === 'rechazado' || $this->xmlDebeRegenerarse($comprobante, $config)) {
            $comprobante = $this->generarXmlComprobante($comprobante, $config) ?? $comprobante;
        }

        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            $comprobante = $this->generarXmlComprobante($comprobante, $config);
        }

        if (!$comprobante || empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            session()->setFlashdata('error', 'No se pudo generar el XML del documento.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        if (!class_exists(SoapClient::class) || !class_exists(ZipArchive::class)) {
            session()->setFlashdata('error', 'El servidor necesita las extensiones SOAP y ZIP de PHP para enviar a SUNAT.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? ''));
        $usuarioSol = trim((string) ($config->sol_usuario ?? ''));
        $claveSol = trim((string) ($config->sol_clave ?? ''));

        if (strlen($ruc) !== 11 || $usuarioSol === '' || $claveSol === '') {
            session()->setFlashdata('error', 'Complete RUC, usuario SOL y clave SOL en la configuracion de facturacion.');

            return redirect()->to(base_url('facturacion/electronica/configuracion'));
        }

        $xmlActual = file_get_contents($comprobante->xml_path);
        if (!$this->xmlTieneFirma((string) $xmlActual)) {
            if (empty($config->certificado_path) || empty($config->certificado_clave)) {
                session()->setFlashdata('error', 'El XML no esta firmado. Configure el certificado digital antes de enviar a SUNAT.');

                return redirect()->to(base_url('facturacion/electronica/configuracion'));
            }

            $xmlFirmado = $this->firmarDocumentoXml((string) $xmlActual, $config->certificado_path, $config->certificado_clave);

            if ($xmlFirmado === null) {
                session()->setFlashdata('error', 'No se pudo firmar automaticamente el XML. Revise el certificado y su clave.');

                return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
            }

            file_put_contents($comprobante->xml_path, $xmlFirmado);
            $this->facturacionModel->update($id, [
                'estado' => 'xml_firmado',
                'sunat_estado' => 'pendiente_envio',
                'xml_hash' => hash('sha256', $xmlFirmado),
            ]);
        }

        $baseName = $this->nombreSunat($comprobante, $config);
        $zipPath = $this->crearZipSunat($comprobante->xml_path, $baseName);

        if ($zipPath === null) {
            session()->setFlashdata('error', 'No se pudo preparar el ZIP para SUNAT.');

            return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
        }

        $endpoint = (($config->modo ?? 'beta') === 'produccion')
            ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'
            : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';

        try {
            $client = new SoapClient($endpoint . '?wsdl', [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => false,
                'exceptions' => true,
                'connection_timeout' => 30,
            ]);
            $client->__setLocation($endpoint);
            $client->__setSoapHeaders([
                $this->sunatSecurityHeader($ruc . $usuarioSol, $claveSol),
            ]);

            $response = $client->__soapCall('sendBill', [[
                'fileName' => $baseName . '.zip',
                'contentFile' => file_get_contents($zipPath),
            ]]);

            $cdrContent = $response->applicationResponse ?? null;

            if (empty($cdrContent)) {
                throw new \RuntimeException('SUNAT no devolvio CDR para el comprobante.');
            }

            $cdrPath = $this->guardarCdr($baseName, $cdrContent);
            $mensaje = $this->leerMensajeCdr($cdrPath) ?: 'CDR recibido por SUNAT.';
            $aceptado = stripos($mensaje, 'aceptad') !== false;

            $this->facturacionModel->update($id, [
                'estado' => 'enviado_sunat',
                'sunat_estado' => $aceptado ? 'aceptado' : 'enviado',
                'cdr_path' => $cdrPath,
                'sunat_mensaje' => $mensaje,
            ]);

            session()->setFlashdata('success', $mensaje);
        } catch (SoapFault|\Throwable $e) {
            $mensaje = $e->getMessage();
            $this->facturacionModel->update($id, [
                'sunat_estado' => 'rechazado',
                'sunat_mensaje' => $mensaje,
            ]);

            session()->setFlashdata('error', 'SUNAT rechazo o no recibio el comprobante: ' . $mensaje);
        }

        return redirect()->to(base_url('facturacion/electronica/ver/' . $id));
    }

    public function reenvioMasivoSunat()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $pendientes = $this->facturacionModel->pendientesSunat(50);
        $enviados = 0;
        $fallidos = 0;
        $sinXml = 0;
        $mensajes = [];

        foreach ($pendientes as $comprobante) {
            if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
                $sinXml++;
                continue;
            }

            $resultado = $this->procesarEnvioSunat($comprobante);

            if ($resultado['ok']) {
                $enviados++;
            } else {
                $fallidos++;
            }

            if (!empty($resultado['mensaje'])) {
                $mensajes[] = $comprobante->serie . '-' . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT) . ': ' . $resultado['mensaje'];
            }
        }

        if ($enviados > 0) {
            session()->setFlashdata('success', 'Reenvio masivo terminado. Enviados: ' . $enviados . ', fallidos: ' . $fallidos . ', sin XML: ' . $sinXml . '.');
        } else {
            session()->setFlashdata('error', 'No se pudo reenviar ningun documento. Fallidos: ' . $fallidos . ', sin XML: ' . $sinXml . '.');
        }

        if (!empty($mensajes)) {
            session()->setFlashdata('sunat_detalle', implode("\n", array_slice($mensajes, 0, 10)));
        }

        return redirect()->to(base_url('facturacion/electronica'));
    }

    public function resumenBoletas()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $fecha = (string) ($this->request->getGet('fecha') ?: date('Y-m-d'));

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/resumen_boletas', [
                'fecha'   => $fecha,
                'boletas' => $this->facturacionModel->boletasPendientesResumen($fecha),
            ])
            . view('layouts/footer');
    }

    public function enviarResumenBoletas()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $fecha = (string) $this->request->getPost('fecha');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            session()->setFlashdata('error', 'Fecha de resumen invalida.');

            return redirect()->to(base_url('facturacion/electronica/resumen-boletas'));
        }

        $boletas = $this->facturacionModel->boletasPendientesResumen($fecha);
        if (empty($boletas)) {
            session()->setFlashdata('error', 'No hay boletas pendientes para enviar en resumen.');

            return redirect()->to(base_url('facturacion/electronica/resumen-boletas?fecha=' . rawurlencode($fecha)));
        }

        $grupos = [];
        foreach ($boletas as $boleta) {
            $grupos[(int) ($boleta->cod_sede ?? 0)][] = $boleta;
        }

        $okGeneral = true;
        $mensajes = [];

        foreach ($grupos as $grupoBoletas) {
            $config = $this->configuracionParaComprobante($grupoBoletas[0]);
            $baseName = $this->nombreResumenSunat($config, 'RC', $fecha);
            $xml = $this->construirResumenBoletasXml($grupoBoletas, $config, $fecha, $baseName);
            $resultado = $this->firmarYEnviarResumen($baseName, $xml, $config);
            $okGeneral = $okGeneral && $resultado['ok'];
            $mensajes[] = $resultado['mensaje'];

            foreach ($grupoBoletas as $boleta) {
                $this->facturacionModel->update((int) $boleta->id_facturacion, [
                    'sunat_estado'  => $resultado['ok'] ? 'resumen_enviado' : 'rechazado',
                    'sunat_mensaje' => $resultado['mensaje'],
                ]);
            }
        }

        session()->setFlashdata($okGeneral ? 'success' : 'error', implode(' | ', array_unique($mensajes)));

        return redirect()->to(base_url('facturacion/electronica/resumen-boletas?fecha=' . rawurlencode($fecha)));
    }

    public function comunicacionBaja()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $filtros = [
            'fecha'   => (string) $this->request->getGet('fecha'),
            'cliente' => (string) $this->request->getGet('cliente'),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/comunicacion_baja', [
                'filtros'    => $filtros,
                'documentos' => $this->facturacionModel->documentosParaBaja($filtros),
            ])
            . view('layouts/footer');
    }

    public function enviarComunicacionBaja()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $id = (int) $this->request->getPost('id_facturacion');
        $motivo = trim((string) $this->request->getPost('motivo'));
        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante || $motivo === '') {
            session()->setFlashdata('error', 'Seleccione un documento y escriba el motivo de la baja.');

            return redirect()->to(base_url('facturacion/electronica/comunicacion-baja'));
        }

        $config = $this->configuracionParaComprobante($comprobante);
        $fecha = date('Y-m-d');
        $baseName = $this->nombreResumenSunat($config, 'RA', $fecha);
        $xml = $this->construirBajaXml($comprobante, $config, $fecha, $baseName, $motivo);
        $resultado = $this->firmarYEnviarResumen($baseName, $xml, $config);

        $this->facturacionModel->update($id, [
            'estado'        => 'anulado',
            'sunat_estado'  => $resultado['ok'] ? 'baja_enviada' : 'rechazado',
            'sunat_mensaje' => $resultado['mensaje'],
        ]);

        session()->setFlashdata($resultado['ok'] ? 'success' : 'error', $resultado['mensaje']);

        return redirect()->to(base_url('facturacion/electronica/comunicacion-baja'));
    }

    public function guiasRemision()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/facturacion/electronica/guias_remision')
            . view('layouts/footer');
    }

    public function descargarCdr(int $id)
    {
        $comprobante = $this->facturacionModel->obtener($id);

        if (!$comprobante || empty($comprobante->cdr_path) || !is_file($comprobante->cdr_path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('CDR no encontrado');
        }

        return $this->response->download($comprobante->cdr_path, null);
    }

    public function sunatStatus()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $config = $this->configModel->obtener() ?? $this->configDesdeClinica();
        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? ''));

        return $this->response->setJSON([
            'ruc_configurado' => strlen($ruc) === 11,
            'modo' => $config->modo ?? 'beta',
            'usuario_sol' => !empty($config->sol_usuario),
            'clave_sol' => !empty($config->sol_clave),
            'certificado' => !empty($config->certificado_path) && is_file($config->certificado_path),
            'extension_soap' => class_exists(SoapClient::class),
            'extension_zip' => class_exists(ZipArchive::class),
            'extension_openssl' => extension_loaded('openssl'),
        ]);
    }

    public function consultaRuc(string $ruc)
    {
        if (!session()->get('login')) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'No autorizado.',
            ]);
        }

        $ruc = preg_replace('/\D+/', '', $ruc);

        if (strlen($ruc) !== 11) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ingrese un RUC valido de 11 digitos.',
                'data' => $this->datosRucVacios(),
            ]);
        }

        $data = $this->consultarRucExterno($ruc);

        return $this->response->setJSON([
            'success' => $data !== null,
            'message' => $data !== null ? 'RUC encontrado.' : 'No se encontraron datos para el RUC.',
            'data' => $data ?? $this->datosRucVacios(),
        ]);
    }

    public function getPaciente(int $id)
    {
        $paciente = $this->modelGeneral->getTableWhereRow('paciente', ['codi_pac' => $id]);

        return $this->response->setJSON($paciente);
    }

    private function consultarRucExterno(string $ruc): ?array
    {
        $apiUrl = (string) (env('facturacion.rucApiUrl') ?: 'https://dniruc.apisperu.com/api/v1/ruc/{ruc}');
        $token = trim((string) (
            env('facturacion.rucApiToken')
            ?: env('FACTURACION_RUC_API_TOKEN')
            ?: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNnX3ZlbGF6Y29AaG90bWFpbC5jb20ifQ.RugrMlW0IAwCuAcnHWucHbvpwmt9QA0ebm4CIJ11rwc'
        ));
        $url = str_replace('{ruc}', rawurlencode($ruc), $apiUrl);

        if (!str_contains($url, '{ruc}') && !str_contains($url, $ruc)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'numero=' . rawurlencode($ruc);
        }

        if ($token !== '') {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'token=' . rawurlencode($token);
        }

        try {
            $client = service('curlrequest', [
                'timeout' => 12,
                'connect_timeout' => 8,
                'http_errors' => false,
            ]);

            $headers = [
                'Accept' => 'application/json',
            ];

            $response = $client->get($url, ['headers' => $headers]);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                return null;
            }

            $payload = json_decode((string) $response->getBody(), true);

            if (!is_array($payload)) {
                return null;
            }

            return $this->normalizarDatosRuc($payload);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizarDatosRuc(array $payload): ?array
    {
        $source = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;

        $razonSocial = $this->primerValor($source, [
            'razonSocial',
            'razon_social',
            'nombre_o_razon_social',
            'nombre',
            'nombreComercial',
        ]);

        $direccion = $this->primerValor($source, [
            'direccion',
            'direccionFiscal',
            'domicilioFiscal',
            'domicilio_fiscal',
        ]);

        $ubigeo = $this->primerValor($source, [
            'ubigeo',
            'ubigeoSunat',
            'ubigeo_sunat',
            'codigoUbigeo',
        ]);

        $data = [
            'razon_social' => $razonSocial,
            'nombre_comercial' => $this->primerValor($source, ['nombreComercial', 'nombre_comercial']),
            'ubigeo' => preg_replace('/\D+/', '', $ubigeo),
            'direccion' => $direccion,
            'departamento' => $this->primerValor($source, ['departamento']),
            'provincia' => $this->primerValor($source, ['provincia']),
            'distrito' => $this->primerValor($source, ['distrito']),
        ];

        $tieneDatos = implode('', array_values($data)) !== '';

        return $tieneDatos ? $data : null;
    }

    private function datosRucVacios(): array
    {
        return [
            'razon_social' => '',
            'nombre_comercial' => '',
            'ubigeo' => '',
            'direccion' => '',
            'departamento' => '',
            'provincia' => '',
            'distrito' => '',
        ];
    }

    private function primerValor(array $source, array $keys): string
    {
        foreach ($keys as $key) {
            if (isset($source[$key]) && trim((string) $source[$key]) !== '') {
                return trim((string) $source[$key]);
            }
        }

        return '';
    }

    private function procesarEnvioSunat(object $comprobante): array
    {
        $config = $this->configuracionParaComprobante($comprobante);

        if (($comprobante->sunat_estado ?? '') === 'rechazado' || $this->xmlDebeRegenerarse($comprobante, $config)) {
            $comprobante = $this->generarXmlComprobante($comprobante, $config) ?? $comprobante;
        }

        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            $comprobante = $this->generarXmlComprobante($comprobante, $config) ?? $comprobante;
        }

        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            return ['ok' => false, 'mensaje' => 'No se pudo generar el XML.'];
        }

        if (!class_exists(SoapClient::class) || !class_exists(ZipArchive::class)) {
            return ['ok' => false, 'mensaje' => 'Faltan extensiones SOAP o ZIP en PHP.'];
        }

        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? ''));
        $usuarioSol = trim((string) ($config->sol_usuario ?? ''));
        $claveSol = trim((string) ($config->sol_clave ?? ''));

        if (strlen($ruc) !== 11 || $usuarioSol === '' || $claveSol === '') {
            return ['ok' => false, 'mensaje' => 'Complete RUC, usuario SOL y clave SOL.'];
        }

        $xmlActual = file_get_contents($comprobante->xml_path);
        if (!$this->xmlTieneFirma((string) $xmlActual)) {
            if (empty($config->certificado_path) || empty($config->certificado_clave)) {
                return ['ok' => false, 'mensaje' => 'El XML no esta firmado. Configure el certificado digital.'];
            }

            $xmlFirmado = $this->firmarDocumentoXml((string) $xmlActual, $config->certificado_path, $config->certificado_clave);

            if ($xmlFirmado === null) {
                return ['ok' => false, 'mensaje' => 'No se pudo firmar automaticamente el XML.'];
            }

            file_put_contents($comprobante->xml_path, $xmlFirmado);
            $this->facturacionModel->update($comprobante->id_facturacion, [
                'estado' => 'xml_firmado',
                'sunat_estado' => 'pendiente_envio',
                'xml_hash' => hash('sha256', $xmlFirmado),
            ]);
        }

        $baseName = $this->nombreSunat($comprobante, $config);
        $zipPath = $this->crearZipSunat($comprobante->xml_path, $baseName);

        if ($zipPath === null) {
            return ['ok' => false, 'mensaje' => 'No se pudo preparar ZIP para SUNAT.'];
        }

        $endpoint = (($config->modo ?? 'beta') === 'produccion')
            ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'
            : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';

        try {
            $client = new SoapClient($endpoint . '?wsdl', [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => false,
                'exceptions' => true,
                'connection_timeout' => 30,
            ]);
            $client->__setLocation($endpoint);
            $client->__setSoapHeaders([
                $this->sunatSecurityHeader($ruc . $usuarioSol, $claveSol),
            ]);

            $response = $client->__soapCall('sendBill', [[
                'fileName' => $baseName . '.zip',
                'contentFile' => file_get_contents($zipPath),
            ]]);

            $cdrContent = $response->applicationResponse ?? null;

            if (empty($cdrContent)) {
                throw new \RuntimeException('SUNAT no devolvio CDR.');
            }

            $cdrPath = $this->guardarCdr($baseName, $cdrContent);
            $mensaje = $this->leerMensajeCdr($cdrPath) ?: 'CDR recibido por SUNAT.';
            $aceptado = stripos($mensaje, 'aceptad') !== false;

            $this->facturacionModel->update($comprobante->id_facturacion, [
                'estado' => 'enviado_sunat',
                'sunat_estado' => $aceptado ? 'aceptado' : 'enviado',
                'cdr_path' => $cdrPath,
                'sunat_mensaje' => $mensaje,
            ]);

            return ['ok' => true, 'mensaje' => $mensaje];
        } catch (SoapFault|\Throwable $e) {
            $mensaje = $e->getMessage();
            $this->facturacionModel->update($comprobante->id_facturacion, [
                'sunat_estado' => 'rechazado',
                'sunat_mensaje' => $mensaje,
            ]);

            return ['ok' => false, 'mensaje' => $mensaje];
        }
    }

    private function nombreResumenSunat(object $config, string $tipo, string $fecha): string
    {
        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '00000000000'));
        $secuencia = date('His');

        return $ruc . '-' . $tipo . '-' . str_replace('-', '', $fecha) . '-' . $secuencia;
    }

    private function firmarYEnviarResumen(string $baseName, string $xml, object $config): array
    {
        if (!class_exists(SoapClient::class) || !class_exists(ZipArchive::class)) {
            return ['ok' => false, 'mensaje' => 'El servidor necesita las extensiones SOAP y ZIP de PHP.'];
        }

        if (empty($config->certificado_path) || empty($config->certificado_clave)) {
            return ['ok' => false, 'mensaje' => 'Debe configurar el certificado digital antes de enviar a SUNAT.'];
        }

        $firmado = $this->firmarDocumentoXml($xml, $config->certificado_path, $config->certificado_clave);
        if ($firmado === null) {
            return ['ok' => false, 'mensaje' => 'No se pudo firmar el XML del resumen/baja.'];
        }

        $dir = WRITEPATH . 'facturacion/resumen/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $xmlPath = $dir . $baseName . '.xml';
        file_put_contents($xmlPath, $firmado);
        $zipPath = $this->crearZipSunat($xmlPath, $baseName);

        if ($zipPath === null) {
            return ['ok' => false, 'mensaje' => 'No se pudo preparar el ZIP para SUNAT.'];
        }

        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? ''));
        $usuarioSol = trim((string) ($config->sol_usuario ?? ''));
        $claveSol = trim((string) ($config->sol_clave ?? ''));

        if (strlen($ruc) !== 11 || $usuarioSol === '' || $claveSol === '') {
            return ['ok' => false, 'mensaje' => 'Complete RUC, usuario SOL y clave SOL en la configuracion.'];
        }

        $endpoint = (($config->modo ?? 'beta') === 'produccion')
            ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'
            : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';

        try {
            $client = new SoapClient($endpoint . '?wsdl', [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => false,
                'exceptions' => true,
                'connection_timeout' => 30,
            ]);
            $client->__setLocation($endpoint);
            $client->__setSoapHeaders([
                $this->sunatSecurityHeader($ruc . $usuarioSol, $claveSol),
            ]);

            $response = $client->__soapCall('sendSummary', [[
                'fileName' => $baseName . '.zip',
                'contentFile' => file_get_contents($zipPath),
            ]]);

            $ticket = trim((string) ($response->ticket ?? ''));

            return [
                'ok' => $ticket !== '',
                'mensaje' => $ticket !== ''
                    ? 'Resumen enviado a SUNAT. Ticket: ' . $ticket
                    : 'SUNAT recibio el resumen, pero no devolvio ticket.',
            ];
        } catch (SoapFault|\Throwable $e) {
            return ['ok' => false, 'mensaje' => 'SUNAT rechazo o no recibio el resumen/baja: ' . $e->getMessage()];
        }
    }

    private function construirResumenBoletasXml(array $boletas, object $config, string $fecha, string $baseName): string
    {
        $rucEmisor = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '00000000000'));
        $razonSocial = trim((string) ($config->razon_social ?? 'EMPRESA SIN CONFIGURAR'));
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $root = $doc->createElementNS('urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1', 'SummaryDocuments');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sac', 'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1');
        $doc->appendChild($root);

        $this->appendResumenExtensions($doc, $root);
        $this->appendText($doc, $root, 'cbc:UBLVersionID', '2.0');
        $this->appendText($doc, $root, 'cbc:CustomizationID', '1.1');
        $this->appendText($doc, $root, 'cbc:ID', preg_replace('/^\d{11}-/', '', $baseName));
        $this->appendText($doc, $root, 'cbc:ReferenceDate', $fecha);
        $this->appendText($doc, $root, 'cbc:IssueDate', date('Y-m-d'));
        $this->appendResumenSupplier($doc, $root, $rucEmisor, $razonSocial);

        foreach ($boletas as $index => $boleta) {
            $line = $doc->createElement('sac:SummaryDocumentsLine');
            $this->appendText($doc, $line, 'cbc:LineID', (string) ($index + 1));
            $this->appendText($doc, $line, 'cbc:DocumentTypeCode', '03');
            $this->appendText($doc, $line, 'cbc:ID', $this->serieSunat((string) $boleta->serie, '03') . '-' . str_pad((string) $boleta->correlativo, 8, '0', STR_PAD_LEFT));
            $this->appendText($doc, $line, 'sac:Status', '1');
            $this->appendAmount($doc, $line, 'sac:TotalAmount', (float) $boleta->total, 'PEN');
            $billing = $doc->createElement('sac:BillingPayment');
            $this->appendAmount($doc, $billing, 'cbc:PaidAmount', (float) $boleta->op_gravada, 'PEN');
            $this->appendText($doc, $billing, 'cbc:InstructionID', '01');
            $line->appendChild($billing);
            $tax = $doc->createElement('cac:TaxTotal');
            $this->appendAmount($doc, $tax, 'cbc:TaxAmount', (float) $boleta->igv, 'PEN');
            $line->appendChild($tax);
            $root->appendChild($line);
        }

        return $doc->saveXML();
    }

    private function construirBajaXml(object $comprobante, object $config, string $fecha, string $baseName, string $motivo): string
    {
        $rucEmisor = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '00000000000'));
        $razonSocial = trim((string) ($config->razon_social ?? 'EMPRESA SIN CONFIGURAR'));
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $root = $doc->createElementNS('urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1', 'VoidedDocuments');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sac', 'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1');
        $doc->appendChild($root);

        $this->appendResumenExtensions($doc, $root);
        $this->appendText($doc, $root, 'cbc:UBLVersionID', '2.0');
        $this->appendText($doc, $root, 'cbc:CustomizationID', '1.0');
        $this->appendText($doc, $root, 'cbc:ID', preg_replace('/^\d{11}-/', '', $baseName));
        $this->appendText($doc, $root, 'cbc:ReferenceDate', (string) $comprobante->fecha_emision);
        $this->appendText($doc, $root, 'cbc:IssueDate', $fecha);
        $this->appendResumenSupplier($doc, $root, $rucEmisor, $razonSocial);

        $line = $doc->createElement('sac:VoidedDocumentsLine');
        $this->appendText($doc, $line, 'cbc:LineID', '1');
        $this->appendText($doc, $line, 'cbc:DocumentTypeCode', (string) $comprobante->tipo_comprobante);
        $this->appendText($doc, $line, 'sac:DocumentSerialID', $this->serieSunat((string) $comprobante->serie, (string) $comprobante->tipo_comprobante));
        $this->appendText($doc, $line, 'sac:DocumentNumberID', (string) $comprobante->correlativo);
        $this->appendCData($doc, $line, 'sac:VoidReasonDescription', $motivo);
        $root->appendChild($line);

        return $doc->saveXML();
    }

    private function appendResumenExtensions(DOMDocument $doc, \DOMNode $root): void
    {
        $extensions = $doc->createElement('ext:UBLExtensions');
        $extension = $doc->createElement('ext:UBLExtension');
        $extension->appendChild($doc->createElement('ext:ExtensionContent'));
        $extensions->appendChild($extension);
        $root->appendChild($extensions);
    }

    private function appendResumenSupplier(DOMDocument $doc, \DOMNode $root, string $ruc, string $razonSocial): void
    {
        $supplier = $doc->createElement('cac:AccountingSupplierParty');
        $this->appendText($doc, $supplier, 'cbc:CustomerAssignedAccountID', $ruc);
        $this->appendText($doc, $supplier, 'cbc:AdditionalAccountID', '6');
        $party = $doc->createElement('cac:Party');
        $legal = $doc->createElement('cac:PartyLegalEntity');
        $this->appendCData($doc, $legal, 'cbc:RegistrationName', $razonSocial);
        $party->appendChild($legal);
        $supplier->appendChild($party);
        $root->appendChild($supplier);
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
    private function nombreSunat(object $comprobante, object $config): string
    {
        $ruc = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '00000000000'));
        $numero = str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT);

        return $ruc . '-' . $comprobante->tipo_comprobante . '-' . $this->serieSunat((string) $comprobante->serie, (string) $comprobante->tipo_comprobante) . '-' . $numero;
    }

    private function crearZipSunat(string $xmlPath, string $baseName): ?string
    {
        if (!is_file($xmlPath)) {
            return null;
        }

        $dir = WRITEPATH . 'facturacion/envio/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $zipPath = $dir . $baseName . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        $zip->addFile($xmlPath, $baseName . '.xml');
        $zip->close();

        return $zipPath;
    }

    private function xmlTieneFirma(string $xml): bool
    {
        if (trim($xml) === '') {
            return false;
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $doc->loadXML($xml);
        libxml_use_internal_errors($previous);

        if (!$loaded) {
            return false;
        }

        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');

        return $xpath->query('//ds:Signature')->length > 0;
    }

    private function sunatSecurityHeader(string $username, string $password): \SoapHeader
    {
        $xml = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">'
            . '<wsse:UsernameToken><wsse:Username>' . htmlspecialchars($username, ENT_XML1) . '</wsse:Username>'
            . '<wsse:Password>' . htmlspecialchars($password, ENT_XML1) . '</wsse:Password></wsse:UsernameToken></wsse:Security>';

        return new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', new \SoapVar($xml, XSD_ANYXML), true);
    }

    private function guardarCdr(string $baseName, string $cdrContent): string
    {
        $dir = WRITEPATH . 'facturacion/cdr/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $dir . 'R-' . $baseName . '.zip';
        file_put_contents($path, $cdrContent);

        return $path;
    }

    private function leerMensajeCdr(string $cdrZipPath): string
    {
        if (!is_file($cdrZipPath) || !class_exists(ZipArchive::class)) {
            return '';
        }

        $zip = new ZipArchive();
        if ($zip->open($cdrZipPath) !== true) {
            return '';
        }

        $xml = '';
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'xml') {
                $xml = (string) $zip->getFromIndex($i);
                break;
            }
        }
        $zip->close();

        if ($xml === '') {
            return '';
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        if (!$doc->loadXML($xml)) {
            libxml_use_internal_errors($previous);
            return '';
        }
        libxml_use_internal_errors($previous);

        $nodes = $doc->getElementsByTagName('Description');

        return $nodes->length > 0 ? trim((string) $nodes->item(0)->nodeValue) : '';
    }
    private function prepararDetalle(): array
    {
        $descripciones = $this->request->getPost('descripcion') ?? [];
        $cantidades = $this->request->getPost('cantidad') ?? [];
        $precios = $this->request->getPost('precio_unitario') ?? [];
        $codigos = $this->request->getPost('codigo_producto') ?? [];

        $detalle = [];

        foreach ($descripciones as $index => $descripcion) {
            $cantidad = round((float) ($cantidades[$index] ?? 0), 2);
            $precioUnitario = round((float) ($precios[$index] ?? 0), 2);
            $total = round($cantidad * $precioUnitario, 2);
            $valorUnitario = round($precioUnitario / 1.18, 2);
            $igv = round($total - ($total / 1.18), 2);

            if ($descripcion === '' || $cantidad <= 0 || $precioUnitario <= 0) {
                continue;
            }

            $detalle[] = [
                'codigo_producto' => $codigos[$index] ?? null,
                'descripcion'     => $descripcion,
                'unidad'          => 'NIU',
                'cantidad'        => $cantidad,
                'valor_unitario'  => $valorUnitario,
                'precio_unitario' => $precioUnitario,
                'igv'             => $igv,
                'total'           => $total,
            ];
        }

        return $detalle;
    }

    private function calcularTotales(array $detalle): array
    {
        $total = array_reduce($detalle, static fn ($carry, $item) => $carry + (float) $item['total'], 0.0);
        $opGravada = round($total / 1.18, 2);
        $igv = round($total - $opGravada, 2);

        return [
            'op_gravada' => $opGravada,
            'igv'        => $igv,
            'total'      => round($total, 2),
        ];
    }

    private function construirXml(object $comprobante, ?object $config = null): string
    {
        if (in_array((string) $comprobante->tipo_comprobante, ['07', '08'], true)) {
            return $this->construirNotaXml($comprobante, $config);
        }

        $config = $config ?? $this->configuracionParaComprobante($comprobante);
        $moneda = (string) ($comprobante->moneda ?: 'PEN');
        $serieNumero = $this->serieSunat((string) $comprobante->serie, (string) $comprobante->tipo_comprobante)
            . '-'
            . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT);
        $rucEmisor = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '')) ?: '00000000000';
        $razonSocial = trim((string) ($config->razon_social ?? 'EMPRESA SIN CONFIGURAR'));
        $nombreComercial = trim((string) ($config->nombre_comercial ?? $razonSocial));

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $invoice = $doc->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:Invoice-2', 'Invoice');
        $invoice->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $invoice->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $invoice->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $invoice->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $doc->appendChild($invoice);

        $extensions = $doc->createElement('ext:UBLExtensions');
        $extension = $doc->createElement('ext:UBLExtension');
        $extension->appendChild($doc->createElement('ext:ExtensionContent'));
        $extensions->appendChild($extension);
        $invoice->appendChild($extensions);

        $this->appendText($doc, $invoice, 'cbc:UBLVersionID', '2.1');
        $this->appendText($doc, $invoice, 'cbc:CustomizationID', '2.0');
        $this->appendText($doc, $invoice, 'cbc:ID', $serieNumero);
        $this->appendText($doc, $invoice, 'cbc:IssueDate', $comprobante->fecha_emision);
        $this->appendText($doc, $invoice, 'cbc:IssueTime', $comprobante->hora_emision ?: '00:00:00');
        $invoiceType = $this->appendTextElement($doc, $invoice, 'cbc:InvoiceTypeCode', (string) $comprobante->tipo_comprobante);
        $invoiceType->setAttribute('listID', '0101');
        $note = $this->appendCData($doc, $invoice, 'cbc:Note', $this->montoEnLetras((float) $comprobante->total));
        $note->setAttribute('languageLocaleID', '1000');
        $this->appendText($doc, $invoice, 'cbc:DocumentCurrencyCode', $moneda);

        $signature = $doc->createElement('cac:Signature');
        $this->appendText($doc, $signature, 'cbc:ID', $rucEmisor);
        $signatory = $doc->createElement('cac:SignatoryParty');
        $signatoryId = $doc->createElement('cac:PartyIdentification');
        $this->appendText($doc, $signatoryId, 'cbc:ID', $rucEmisor);
        $signatory->appendChild($signatoryId);
        $signatoryName = $doc->createElement('cac:PartyName');
        $this->appendCData($doc, $signatoryName, 'cbc:Name', $razonSocial);
        $signatory->appendChild($signatoryName);
        $signature->appendChild($signatory);
        $attachment = $doc->createElement('cac:DigitalSignatureAttachment');
        $externalReference = $doc->createElement('cac:ExternalReference');
        $this->appendText($doc, $externalReference, 'cbc:URI', '#SIGN-ORALCENTER');
        $attachment->appendChild($externalReference);
        $signature->appendChild($attachment);
        $invoice->appendChild($signature);

        $supplier = $doc->createElement('cac:AccountingSupplierParty');
        $party = $doc->createElement('cac:Party');
        $identification = $doc->createElement('cac:PartyIdentification');
        $id = $doc->createElement('cbc:ID', $rucEmisor);
        $id->setAttribute('schemeID', '6');
        $identification->appendChild($id);
        $party->appendChild($identification);

        $commercialName = $doc->createElement('cac:PartyName');
        $this->appendCData($doc, $commercialName, 'cbc:Name', $nombreComercial ?: $razonSocial);
        $party->appendChild($commercialName);

        $legalEntity = $doc->createElement('cac:PartyLegalEntity');
        $this->appendCData($doc, $legalEntity, 'cbc:RegistrationName', $razonSocial);
        $address = $doc->createElement('cac:RegistrationAddress');
        $this->appendText($doc, $address, 'cbc:ID', (string) ($config->ubigeo ?? '000000'));
        $this->appendText($doc, $address, 'cbc:AddressTypeCode', '0000');
        $this->appendCData($doc, $address, 'cbc:CitySubdivisionName', (string) ($config->urbanizacion ?? '-'));
        $this->appendText($doc, $address, 'cbc:CityName', (string) ($config->provincia ?? 'LIMA'));
        $this->appendText($doc, $address, 'cbc:CountrySubentity', (string) ($config->departamento ?? 'LIMA'));
        $this->appendText($doc, $address, 'cbc:District', (string) ($config->distrito ?? 'LIMA'));
        $line = $doc->createElement('cac:AddressLine');
        $this->appendCData($doc, $line, 'cbc:Line', (string) ($config->direccion ?? '-'));
        $address->appendChild($line);
        $country = $doc->createElement('cac:Country');
        $this->appendText($doc, $country, 'cbc:IdentificationCode', 'PE');
        $address->appendChild($country);
        $legalEntity->appendChild($address);
        $party->appendChild($legalEntity);

        if (!empty($config->telefono) || !empty($config->email)) {
            $contact = $doc->createElement('cac:Contact');
            if (!empty($config->telefono)) {
                $this->appendText($doc, $contact, 'cbc:Telephone', (string) $config->telefono);
            }
            if (!empty($config->email)) {
                $this->appendText($doc, $contact, 'cbc:ElectronicMail', (string) $config->email);
            }
            $party->appendChild($contact);
        }

        $supplier->appendChild($party);
        $invoice->appendChild($supplier);

        $customer = $doc->createElement('cac:AccountingCustomerParty');
        $customerParty = $doc->createElement('cac:Party');
        $customerIdentification = $doc->createElement('cac:PartyIdentification');
        $customerId = $doc->createElement('cbc:ID', $comprobante->numero_documento_cliente);
        $customerId->setAttribute('schemeID', $comprobante->tipo_documento_cliente);
        $customerIdentification->appendChild($customerId);
        $customerParty->appendChild($customerIdentification);
        $customerName = $doc->createElement('cac:PartyLegalEntity');
        $this->appendCData($doc, $customerName, 'cbc:RegistrationName', $comprobante->razon_social_cliente);
        if (!empty($comprobante->direccion_cliente)) {
            $customerAddress = $doc->createElement('cac:RegistrationAddress');
            $customerLine = $doc->createElement('cac:AddressLine');
            $this->appendCData($doc, $customerLine, 'cbc:Line', (string) $comprobante->direccion_cliente);
            $customerAddress->appendChild($customerLine);
            $customerCountry = $doc->createElement('cac:Country');
            $this->appendText($doc, $customerCountry, 'cbc:IdentificationCode', 'PE');
            $customerAddress->appendChild($customerCountry);
            $customerName->appendChild($customerAddress);
        }
        $customerParty->appendChild($customerName);
        $customer->appendChild($customerParty);
        $invoice->appendChild($customer);

        $paymentTerms = $doc->createElement('cac:PaymentTerms');
        $this->appendText($doc, $paymentTerms, 'cbc:ID', 'FormaPago');
        $this->appendText($doc, $paymentTerms, 'cbc:PaymentMeansID', 'Contado');
        $invoice->appendChild($paymentTerms);

        $taxTotal = $doc->createElement('cac:TaxTotal');
        $this->appendAmount($doc, $taxTotal, 'cbc:TaxAmount', (float) $comprobante->igv, $moneda);
        $taxSubtotal = $doc->createElement('cac:TaxSubtotal');
        $this->appendAmount($doc, $taxSubtotal, 'cbc:TaxableAmount', (float) $comprobante->op_gravada, $moneda);
        $this->appendAmount($doc, $taxSubtotal, 'cbc:TaxAmount', (float) $comprobante->igv, $moneda);
        $taxCategory = $doc->createElement('cac:TaxCategory');
        $taxScheme = $doc->createElement('cac:TaxScheme');
        $this->appendText($doc, $taxScheme, 'cbc:ID', '1000');
        $this->appendText($doc, $taxScheme, 'cbc:Name', 'IGV');
        $this->appendText($doc, $taxScheme, 'cbc:TaxTypeCode', 'VAT');
        $taxCategory->appendChild($taxScheme);
        $taxSubtotal->appendChild($taxCategory);
        $taxTotal->appendChild($taxSubtotal);
        $invoice->appendChild($taxTotal);

        $legalTotal = $doc->createElement('cac:LegalMonetaryTotal');
        $this->appendAmount($doc, $legalTotal, 'cbc:LineExtensionAmount', (float) $comprobante->op_gravada, $moneda);
        $this->appendAmount($doc, $legalTotal, 'cbc:TaxInclusiveAmount', (float) $comprobante->total, $moneda);
        $this->appendAmount($doc, $legalTotal, 'cbc:PayableAmount', (float) $comprobante->total, $moneda);
        $invoice->appendChild($legalTotal);

        foreach ($comprobante->detalle as $index => $item) {
            $cantidad = (float) ($item->cantidad ?? 1);
            $valorUnitario = (float) ($item->valor_unitario ?? 0);
            $precioUnitario = (float) ($item->precio_unitario ?? ($valorUnitario * 1.18));
            $lineExtension = round($cantidad * $valorUnitario, 2);
            $igvLinea = (float) ($item->igv ?? round(((float) ($item->total ?? 0)) - $lineExtension, 2));

            $line = $doc->createElement('cac:InvoiceLine');
            $this->appendText($doc, $line, 'cbc:ID', (string) ($index + 1));
            $quantity = $doc->createElement('cbc:InvoicedQuantity', $this->formatAmount($cantidad));
            $quantity->setAttribute('unitCode', (string) ($item->unidad ?: 'NIU'));
            $line->appendChild($quantity);
            $this->appendAmount($doc, $line, 'cbc:LineExtensionAmount', $lineExtension, $moneda);

            $pricingReference = $doc->createElement('cac:PricingReference');
            $alternativePrice = $doc->createElement('cac:AlternativeConditionPrice');
            $this->appendAmount($doc, $alternativePrice, 'cbc:PriceAmount', $precioUnitario, $moneda);
            $this->appendText($doc, $alternativePrice, 'cbc:PriceTypeCode', '01');
            $pricingReference->appendChild($alternativePrice);
            $line->appendChild($pricingReference);

            $lineTaxTotal = $doc->createElement('cac:TaxTotal');
            $this->appendAmount($doc, $lineTaxTotal, 'cbc:TaxAmount', $igvLinea, $moneda);
            $lineTaxSubtotal = $doc->createElement('cac:TaxSubtotal');
            $this->appendAmount($doc, $lineTaxSubtotal, 'cbc:TaxableAmount', $lineExtension, $moneda);
            $this->appendAmount($doc, $lineTaxSubtotal, 'cbc:TaxAmount', $igvLinea, $moneda);
            $lineTaxCategory = $doc->createElement('cac:TaxCategory');
            $this->appendText($doc, $lineTaxCategory, 'cbc:Percent', '18');
            $this->appendText($doc, $lineTaxCategory, 'cbc:TaxExemptionReasonCode', '10');
            $lineTaxScheme = $doc->createElement('cac:TaxScheme');
            $this->appendText($doc, $lineTaxScheme, 'cbc:ID', '1000');
            $this->appendText($doc, $lineTaxScheme, 'cbc:Name', 'IGV');
            $this->appendText($doc, $lineTaxScheme, 'cbc:TaxTypeCode', 'VAT');
            $lineTaxCategory->appendChild($lineTaxScheme);
            $lineTaxSubtotal->appendChild($lineTaxCategory);
            $lineTaxTotal->appendChild($lineTaxSubtotal);
            $line->appendChild($lineTaxTotal);

            $lineItem = $doc->createElement('cac:Item');
            $this->appendCData($doc, $lineItem, 'cbc:Description', $item->descripcion);
            if (!empty($item->codigo_producto)) {
                $sellerId = $doc->createElement('cac:SellersItemIdentification');
                $this->appendText($doc, $sellerId, 'cbc:ID', (string) $item->codigo_producto);
                $lineItem->appendChild($sellerId);
            }
            $line->appendChild($lineItem);

            $price = $doc->createElement('cac:Price');
            $this->appendAmount($doc, $price, 'cbc:PriceAmount', $valorUnitario, $moneda);
            $line->appendChild($price);
            $invoice->appendChild($line);
        }

        return $doc->saveXML();
    }

    private function construirNotaXml(object $comprobante, ?object $config = null): string
    {
        $config = $config ?? $this->configuracionParaComprobante($comprobante);
        $tipo = (string) $comprobante->tipo_comprobante;
        $moneda = (string) ($comprobante->moneda ?: 'PEN');
        $isCredito = $tipo === '07';
        $rootName = $isCredito ? 'CreditNote' : 'DebitNote';
        $lineName = $isCredito ? 'cac:CreditNoteLine' : 'cac:DebitNoteLine';
        $quantityName = $isCredito ? 'cbc:CreditedQuantity' : 'cbc:DebitedQuantity';
        $totalName = $isCredito ? 'cac:LegalMonetaryTotal' : 'cac:RequestedMonetaryTotal';
        $namespace = $isCredito
            ? 'urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2'
            : 'urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2';
        $serieNumero = $this->serieSunat((string) $comprobante->serie, $tipo)
            . '-'
            . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT);

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElementNS($namespace, $rootName);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
        $doc->appendChild($root);

        $extensions = $doc->createElement('ext:UBLExtensions');
        $extension = $doc->createElement('ext:UBLExtension');
        $extension->appendChild($doc->createElement('ext:ExtensionContent'));
        $extensions->appendChild($extension);
        $root->appendChild($extensions);

        $this->appendText($doc, $root, 'cbc:UBLVersionID', '2.1');
        $this->appendText($doc, $root, 'cbc:CustomizationID', '2.0');
        $this->appendText($doc, $root, 'cbc:ID', $serieNumero);
        $this->appendText($doc, $root, 'cbc:IssueDate', (string) $comprobante->fecha_emision);
        $this->appendText($doc, $root, 'cbc:IssueTime', (string) ($comprobante->hora_emision ?: '00:00:00'));
        $note = $this->appendCData($doc, $root, 'cbc:Note', $this->montoEnLetras((float) $comprobante->total));
        $note->setAttribute('languageLocaleID', '1000');
        $this->appendText($doc, $root, 'cbc:DocumentCurrencyCode', $moneda);

        $discrepancy = $doc->createElement('cac:DiscrepancyResponse');
        $this->appendText($doc, $discrepancy, 'cbc:ReferenceID', (string) ($comprobante->documento_relacionado ?? ''));
        $this->appendText($doc, $discrepancy, 'cbc:ResponseCode', (string) ($comprobante->motivo_codigo ?? ($isCredito ? '01' : '02')));
        $this->appendCData($doc, $discrepancy, 'cbc:Description', (string) ($comprobante->motivo_descripcion ?? ($isCredito ? 'ANULACION DE LA OPERACION' : 'AUMENTO EN EL VALOR')));
        $root->appendChild($discrepancy);

        $billingReference = $doc->createElement('cac:BillingReference');
        $invoiceReference = $doc->createElement('cac:InvoiceDocumentReference');
        $this->appendText($doc, $invoiceReference, 'cbc:ID', (string) ($comprobante->documento_relacionado ?? ''));
        $this->appendText($doc, $invoiceReference, 'cbc:DocumentTypeCode', (string) ($comprobante->tipo_documento_relacionado ?? '01'));
        $billingReference->appendChild($invoiceReference);
        $root->appendChild($billingReference);

        $this->appendFirmaEmisorCliente($doc, $root, $comprobante, $config);

        $taxTotal = $doc->createElement('cac:TaxTotal');
        $this->appendAmount($doc, $taxTotal, 'cbc:TaxAmount', (float) $comprobante->igv, $moneda);
        $taxSubtotal = $doc->createElement('cac:TaxSubtotal');
        $this->appendAmount($doc, $taxSubtotal, 'cbc:TaxableAmount', (float) $comprobante->op_gravada, $moneda);
        $this->appendAmount($doc, $taxSubtotal, 'cbc:TaxAmount', (float) $comprobante->igv, $moneda);
        $taxCategory = $doc->createElement('cac:TaxCategory');
        $taxScheme = $doc->createElement('cac:TaxScheme');
        $this->appendText($doc, $taxScheme, 'cbc:ID', '1000');
        $this->appendText($doc, $taxScheme, 'cbc:Name', 'IGV');
        $this->appendText($doc, $taxScheme, 'cbc:TaxTypeCode', 'VAT');
        $taxCategory->appendChild($taxScheme);
        $taxSubtotal->appendChild($taxCategory);
        $taxTotal->appendChild($taxSubtotal);
        $root->appendChild($taxTotal);

        $monetaryTotal = $doc->createElement($totalName);
        if ($isCredito) {
            $this->appendAmount($doc, $monetaryTotal, 'cbc:PayableAmount', (float) $comprobante->total, $moneda);
        } else {
            $this->appendAmount($doc, $monetaryTotal, 'cbc:PayableAmount', (float) $comprobante->total, $moneda);
        }
        $root->appendChild($monetaryTotal);

        foreach ($comprobante->detalle as $index => $item) {
            $cantidad = (float) ($item->cantidad ?? 1);
            $valorUnitario = (float) ($item->valor_unitario ?? 0);
            $precioUnitario = (float) ($item->precio_unitario ?? ($valorUnitario * 1.18));
            $lineExtension = round($cantidad * $valorUnitario, 2);
            $igvLinea = (float) ($item->igv ?? round(((float) ($item->total ?? 0)) - $lineExtension, 2));

            $line = $doc->createElement($lineName);
            $this->appendText($doc, $line, 'cbc:ID', (string) ($index + 1));
            $quantity = $doc->createElement($quantityName, $this->formatAmount($cantidad));
            $quantity->setAttribute('unitCode', (string) ($item->unidad ?: 'NIU'));
            $line->appendChild($quantity);
            $this->appendAmount($doc, $line, 'cbc:LineExtensionAmount', $lineExtension, $moneda);

            $pricingReference = $doc->createElement('cac:PricingReference');
            $alternativePrice = $doc->createElement('cac:AlternativeConditionPrice');
            $this->appendAmount($doc, $alternativePrice, 'cbc:PriceAmount', $precioUnitario, $moneda);
            $this->appendText($doc, $alternativePrice, 'cbc:PriceTypeCode', '01');
            $pricingReference->appendChild($alternativePrice);
            $line->appendChild($pricingReference);

            $lineTaxTotal = $doc->createElement('cac:TaxTotal');
            $this->appendAmount($doc, $lineTaxTotal, 'cbc:TaxAmount', $igvLinea, $moneda);
            $lineTaxSubtotal = $doc->createElement('cac:TaxSubtotal');
            $this->appendAmount($doc, $lineTaxSubtotal, 'cbc:TaxableAmount', $lineExtension, $moneda);
            $this->appendAmount($doc, $lineTaxSubtotal, 'cbc:TaxAmount', $igvLinea, $moneda);
            $lineTaxCategory = $doc->createElement('cac:TaxCategory');
            $this->appendText($doc, $lineTaxCategory, 'cbc:Percent', '18');
            $this->appendText($doc, $lineTaxCategory, 'cbc:TaxExemptionReasonCode', '10');
            $lineTaxScheme = $doc->createElement('cac:TaxScheme');
            $this->appendText($doc, $lineTaxScheme, 'cbc:ID', '1000');
            $this->appendText($doc, $lineTaxScheme, 'cbc:Name', 'IGV');
            $this->appendText($doc, $lineTaxScheme, 'cbc:TaxTypeCode', 'VAT');
            $lineTaxCategory->appendChild($lineTaxScheme);
            $lineTaxSubtotal->appendChild($lineTaxCategory);
            $lineTaxTotal->appendChild($lineTaxSubtotal);
            $line->appendChild($lineTaxTotal);

            $lineItem = $doc->createElement('cac:Item');
            $this->appendCData($doc, $lineItem, 'cbc:Description', (string) $item->descripcion);
            if (!empty($item->codigo_producto)) {
                $sellerId = $doc->createElement('cac:SellersItemIdentification');
                $this->appendText($doc, $sellerId, 'cbc:ID', (string) $item->codigo_producto);
                $lineItem->appendChild($sellerId);
            }
            $line->appendChild($lineItem);

            $price = $doc->createElement('cac:Price');
            $this->appendAmount($doc, $price, 'cbc:PriceAmount', $valorUnitario, $moneda);
            $line->appendChild($price);
            $root->appendChild($line);
        }

        return $doc->saveXML();
    }

    private function appendTextElement(DOMDocument $doc, \DOMNode $parent, string $name, string $value): \DOMElement
    {
        $element = $doc->createElement($name, htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $parent->appendChild($element);

        return $element;
    }

    private function appendFirmaEmisorCliente(DOMDocument $doc, \DOMNode $parent, object $comprobante, object $config): void
    {
        $rucEmisor = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? '')) ?: '00000000000';
        $razonSocial = trim((string) ($config->razon_social ?? 'EMPRESA SIN CONFIGURAR'));
        $nombreComercial = trim((string) ($config->nombre_comercial ?? $razonSocial));

        $signature = $doc->createElement('cac:Signature');
        $this->appendText($doc, $signature, 'cbc:ID', $rucEmisor);
        $signatory = $doc->createElement('cac:SignatoryParty');
        $signatoryId = $doc->createElement('cac:PartyIdentification');
        $this->appendText($doc, $signatoryId, 'cbc:ID', $rucEmisor);
        $signatory->appendChild($signatoryId);
        $signatoryName = $doc->createElement('cac:PartyName');
        $this->appendCData($doc, $signatoryName, 'cbc:Name', $razonSocial);
        $signatory->appendChild($signatoryName);
        $signature->appendChild($signatory);
        $attachment = $doc->createElement('cac:DigitalSignatureAttachment');
        $externalReference = $doc->createElement('cac:ExternalReference');
        $this->appendText($doc, $externalReference, 'cbc:URI', '#SIGN-ORALCENTER');
        $attachment->appendChild($externalReference);
        $signature->appendChild($attachment);
        $parent->appendChild($signature);

        $supplier = $doc->createElement('cac:AccountingSupplierParty');
        $party = $doc->createElement('cac:Party');
        $identification = $doc->createElement('cac:PartyIdentification');
        $id = $doc->createElement('cbc:ID', $rucEmisor);
        $id->setAttribute('schemeID', '6');
        $identification->appendChild($id);
        $party->appendChild($identification);
        $commercialName = $doc->createElement('cac:PartyName');
        $this->appendCData($doc, $commercialName, 'cbc:Name', $nombreComercial ?: $razonSocial);
        $party->appendChild($commercialName);
        $legalEntity = $doc->createElement('cac:PartyLegalEntity');
        $this->appendCData($doc, $legalEntity, 'cbc:RegistrationName', $razonSocial);
        $address = $doc->createElement('cac:RegistrationAddress');
        $this->appendText($doc, $address, 'cbc:ID', (string) ($config->ubigeo ?? '000000'));
        $this->appendText($doc, $address, 'cbc:AddressTypeCode', '0000');
        $this->appendCData($doc, $address, 'cbc:CitySubdivisionName', (string) ($config->urbanizacion ?? '-'));
        $this->appendText($doc, $address, 'cbc:CityName', (string) ($config->provincia ?? 'LIMA'));
        $this->appendText($doc, $address, 'cbc:CountrySubentity', (string) ($config->departamento ?? 'LIMA'));
        $this->appendText($doc, $address, 'cbc:District', (string) ($config->distrito ?? 'LIMA'));
        $addressLine = $doc->createElement('cac:AddressLine');
        $this->appendCData($doc, $addressLine, 'cbc:Line', (string) ($config->direccion ?? '-'));
        $address->appendChild($addressLine);
        $country = $doc->createElement('cac:Country');
        $this->appendText($doc, $country, 'cbc:IdentificationCode', 'PE');
        $address->appendChild($country);
        $legalEntity->appendChild($address);
        $party->appendChild($legalEntity);
        $supplier->appendChild($party);
        $parent->appendChild($supplier);

        $customer = $doc->createElement('cac:AccountingCustomerParty');
        $customerParty = $doc->createElement('cac:Party');
        $customerIdentification = $doc->createElement('cac:PartyIdentification');
        $customerId = $doc->createElement('cbc:ID', (string) $comprobante->numero_documento_cliente);
        $customerId->setAttribute('schemeID', (string) $comprobante->tipo_documento_cliente);
        $customerIdentification->appendChild($customerId);
        $customerParty->appendChild($customerIdentification);
        $customerName = $doc->createElement('cac:PartyLegalEntity');
        $this->appendCData($doc, $customerName, 'cbc:RegistrationName', (string) $comprobante->razon_social_cliente);
        if (!empty($comprobante->direccion_cliente)) {
            $customerAddress = $doc->createElement('cac:RegistrationAddress');
            $customerLine = $doc->createElement('cac:AddressLine');
            $this->appendCData($doc, $customerLine, 'cbc:Line', (string) $comprobante->direccion_cliente);
            $customerAddress->appendChild($customerLine);
            $customerCountry = $doc->createElement('cac:Country');
            $this->appendText($doc, $customerCountry, 'cbc:IdentificationCode', 'PE');
            $customerAddress->appendChild($customerCountry);
            $customerName->appendChild($customerAddress);
        }
        $customerParty->appendChild($customerName);
        $customer->appendChild($customerParty);
        $parent->appendChild($customer);
    }

    private function appendText(DOMDocument $doc, \DOMNode $parent, string $name, string $value): void
    {
        $this->appendTextElement($doc, $parent, $name, $value);
    }

    private function appendCData(DOMDocument $doc, \DOMNode $parent, string $name, string $value): \DOMElement
    {
        $element = $doc->createElement($name);
        $element->appendChild($doc->createCDATASection($value));
        $parent->appendChild($element);

        return $element;
    }

    private function appendAmount(DOMDocument $doc, \DOMNode $parent, string $name, float $value, string $currency): \DOMElement
    {
        $element = $doc->createElement($name, $this->formatAmount($value));
        $element->setAttribute('currencyID', $currency);
        $parent->appendChild($element);

        return $element;
    }

    private function formatAmount(float $value): string
    {
        return number_format(round($value, 2), 2, '.', '');
    }

    private function montoEnLetras(float $total): string
    {
        $entero = (int) floor($total);
        $centimos = (int) round(($total - $entero) * 100);

        if ($centimos === 100) {
            $entero++;
            $centimos = 0;
        }

        return 'SON ' . $this->numeroEnLetras($entero) . ' CON ' . str_pad((string) $centimos, 2, '0', STR_PAD_LEFT) . '/100 SOLES';
    }

    private function numeroEnLetras(int $numero): string
    {
        if ($numero === 0) {
            return 'CERO';
        }

        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $especiales = [
            10 => 'DIEZ',
            11 => 'ONCE',
            12 => 'DOCE',
            13 => 'TRECE',
            14 => 'CATORCE',
            15 => 'QUINCE',
            20 => 'VEINTE',
        ];
        $decenas = ['', '', 'VEINTI', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $convertirMenorMil = function (int $valor) use (&$convertirMenorMil, $unidades, $especiales, $decenas, $centenas): string {
            if ($valor === 0) {
                return '';
            }

            if ($valor === 100) {
                return 'CIEN';
            }

            if ($valor < 10) {
                return $unidades[$valor];
            }

            if (isset($especiales[$valor])) {
                return $especiales[$valor];
            }

            if ($valor < 20) {
                return 'DIECI' . strtolower($unidades[$valor - 10]);
            }

            if ($valor < 30) {
                return $valor === 20 ? 'VEINTE' : 'VEINTI' . strtolower($unidades[$valor - 20]);
            }

            if ($valor < 100) {
                $unidad = $valor % 10;
                $texto = $decenas[intdiv($valor, 10)];

                return $unidad > 0 ? $texto . ' Y ' . $unidades[$unidad] : $texto;
            }

            $resto = $valor % 100;
            $texto = $centenas[intdiv($valor, 100)];

            return trim($texto . ' ' . $convertirMenorMil($resto));
        };

        $millones = intdiv($numero, 1000000);
        $miles = intdiv($numero % 1000000, 1000);
        $resto = $numero % 1000;
        $partes = [];

        if ($millones > 0) {
            $partes[] = $millones === 1 ? 'UN MILLON' : $convertirMenorMil($millones) . ' MILLONES';
        }

        if ($miles > 0) {
            $partes[] = $miles === 1 ? 'MIL' : $convertirMenorMil($miles) . ' MIL';
        }

        if ($resto > 0) {
            $partes[] = $convertirMenorMil($resto);
        }

        return strtoupper(implode(' ', $partes));
    }

    private function configuracionParaComprobante(object $comprobante): object
    {
        $codSede = (int) ($comprobante->cod_sede ?? 0);
        $config = $this->configModel->obtener($codSede > 0 ? $codSede : null)
            ?? $this->configDesdeClinica();

        if ($codSede > 0 && empty($config->cod_sede)) {
            $config->cod_sede = $codSede;
        }

        return $config;
    }

    private function sedesFacturacion(): array
    {
        return $this->modelGeneral->getTableWhere('sede', ['estado_sede' => 'S'], null);
    }

    private function xmlDebeRegenerarse(object $comprobante, object $config): bool
    {
        if (empty($comprobante->xml_path) || !is_file($comprobante->xml_path)) {
            return false;
        }

        $rucConfig = preg_replace('/\D+/', '', (string) ($config->ruc_emisor ?? ''));

        if (strlen($rucConfig) !== 11) {
            return false;
        }

        $rucXml = $this->extraerRucEmisorXml((string) file_get_contents($comprobante->xml_path));

        return $rucXml !== '' && $rucXml !== $rucConfig;
    }

    private function extraerRucEmisorXml(string $xml): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $loaded = $doc->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$loaded) {
            return '';
        }

        $xpath = new \DOMXPath($doc);
        $node = $xpath->query('//*[local-name()="AccountingSupplierParty"]//*[local-name()="PartyIdentification"]/*[local-name()="ID"]')->item(0);

        if (!$node) {
            $node = $xpath->query('//*[local-name()="Signature"]//*[local-name()="SignatoryParty"]//*[local-name()="PartyIdentification"]/*[local-name()="ID"]')->item(0);
        }

        return $node ? preg_replace('/\D+/', '', (string) $node->textContent) : '';
    }

    private function configDesdeClinica(): object
    {
        $clinica = $this->modelGeneral->getTableWhereRow('clinica');

        return (object) [
            'ruc_emisor'   => $clinica->ruc_clin ?? '00000000000',
            'razon_social' => $clinica->razon_social ?? $clinica->nomb_clin ?? 'EMPRESA SIN CONFIGURAR',
            'nombre_comercial' => $clinica->nomb_clin ?? null,
            'direccion' => $clinica->direc_clin ?? '-',
            'telefono' => $clinica->telf_clin ?? null,
            'email' => $clinica->email_clin ?? null,
            'ubigeo' => '000000',
            'departamento' => 'LIMA',
            'provincia' => 'LIMA',
            'distrito' => 'LIMA',
            'modo' => 'beta',
        ];
    }

    private function firmarDocumentoXml(string $xml, string $certificadoPath, string $clave): ?string
    {
        if (!is_file($certificadoPath)) {
            return null;
        }

        $certificado = file_get_contents($certificadoPath);
        $certs = [];

        if (!openssl_pkcs12_read($certificado, $certs, $clave)) {
            return null;
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = false;
        $doc->loadXML($xml);

        $root = $doc->documentElement;
        $extensionContent = $this->asegurarExtensionFirma($doc);
        $canonicalData = $root->C14N(false, false);
        $digestValue = base64_encode(hash('sha256', $canonicalData, true));

        $ds = 'http://www.w3.org/2000/09/xmldsig#';
        $signedInfo = $doc->createElementNS($ds, 'ds:SignedInfo');
        $canonicalization = $doc->createElementNS($ds, 'ds:CanonicalizationMethod');
        $canonicalization->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        $signedInfo->appendChild($canonicalization);

        $signatureMethod = $doc->createElementNS($ds, 'ds:SignatureMethod');
        $signatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256');
        $signedInfo->appendChild($signatureMethod);

        $reference = $doc->createElementNS($ds, 'ds:Reference');
        $reference->setAttribute('URI', '');
        $transforms = $doc->createElementNS($ds, 'ds:Transforms');
        $transform = $doc->createElementNS($ds, 'ds:Transform');
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $transforms->appendChild($transform);
        $canonicalTransform = $doc->createElementNS($ds, 'ds:Transform');
        $canonicalTransform->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $transforms->appendChild($canonicalTransform);
        $reference->appendChild($transforms);
        $digestMethod = $doc->createElementNS($ds, 'ds:DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
        $reference->appendChild($digestMethod);
        $reference->appendChild($doc->createElementNS($ds, 'ds:DigestValue', $digestValue));
        $signedInfo->appendChild($reference);

        $signatureNode = $doc->createElementNS($ds, 'ds:Signature');
        $signatureNode->setAttribute('Id', 'SIGN-ORALCENTER');
        $signatureNode->appendChild($signedInfo);
        $extensionContent->appendChild($signatureNode);

        $signature = '';
        $signedInfoCanonical = $signedInfo->C14N(true, false);

        if ($signedInfoCanonical === false || !openssl_sign($signedInfoCanonical, $signature, $certs['pkey'], OPENSSL_ALGO_SHA256)) {
            return null;
        }

        if (openssl_verify($signedInfoCanonical, $signature, $certs['cert'], OPENSSL_ALGO_SHA256) !== 1) {
            return null;
        }

        $signatureNode->appendChild($doc->createElementNS($ds, 'ds:SignatureValue', base64_encode($signature)));

        $keyInfo = $doc->createElementNS($ds, 'ds:KeyInfo');
        $x509Data = $doc->createElementNS($ds, 'ds:X509Data');
        $x509Certificate = preg_replace('/-----BEGIN CERTIFICATE-----|-----END CERTIFICATE-----|\s+/', '', $certs['cert']);
        $x509Data->appendChild($doc->createElementNS($ds, 'ds:X509Certificate', $x509Certificate));
        $keyInfo->appendChild($x509Data);
        $signatureNode->appendChild($keyInfo);

        return $doc->saveXML();
    }

    private function asegurarExtensionFirma(DOMDocument $doc): \DOMElement
    {
        $root = $doc->documentElement;
        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');

        $content = $xpath->query('//ext:UBLExtensions/ext:UBLExtension/ext:ExtensionContent')->item(0);

        if ($content instanceof \DOMElement) {
            while ($content->firstChild) {
                $content->removeChild($content->firstChild);
            }

            return $content;
        }

        $extensions = $doc->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2', 'ext:UBLExtensions');
        $extension = $doc->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2', 'ext:UBLExtension');
        $content = $doc->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2', 'ext:ExtensionContent');

        $extension->appendChild($content);
        $extensions->appendChild($extension);

        if ($root->firstChild) {
            $root->insertBefore($extensions, $root->firstChild);
        } else {
            $root->appendChild($extensions);
        }

        return $content;
    }
}
