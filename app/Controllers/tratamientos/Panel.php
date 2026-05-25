<?php

namespace App\Controllers\Tratamientos;

use App\Controllers\BaseController;
use App\Models\TratamientosModel;
use App\Models\ClinicaModel;
use App\Models\FacturacionConfigModel;
use App\Models\FacturacionElectronicaModel;
use App\Models\Modelgeneral;
use Mpdf\Mpdf;

class Panel extends BaseController
{
    protected TratamientosModel $tratamientosModel;
    protected ClinicaModel $clinicaModel;
    protected FacturacionConfigModel $facturacionConfigModel;
    protected FacturacionElectronicaModel $facturacionElectronicaModel;
    protected Modelgeneral $modelGeneral;
    protected $db;

    public function __construct()
    {
        $this->tratamientosModel = new TratamientosModel();
        $this->clinicaModel      = new ClinicaModel();
        $this->facturacionConfigModel = new FacturacionConfigModel();
        $this->facturacionElectronicaModel = new FacturacionElectronicaModel();
        $this->modelGeneral      = new Modelgeneral();
        $this->db                = db_connect();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/tratamientos/tratamientos/panel')
            . view('layouts/footer');
    }

    public function jsonTratamientos()
    {
        $columns = [
            '',
            'codi_tra',
            'codi_pac',
            'NombresApellidos',
            'asunto_tra',
            'fecha_tra',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'codi_tra',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
            'desde'          => $this->request->getGetPost('desde'),
            'hasta'          => $this->request->getGetPost('hasta'),
            'estado'         => $this->request->getGetPost('estado'),
        ];

        $paciente = $this->request->getGetPost('paciente');

        if (!empty($paciente)) {
            $data['paciente'] = $paciente;
        }

        return $this->response->setJSON(
            $this->tratamientosModel->getTratamientos($data)
        );
    }

    public function nuevo()
    {
        $data = [
            'especialidades' => $this->modelGeneral->getTable('especialidad'),
            'pacientes'      => $this->modelGeneral->getTable('paciente'),
            'procedimientos' => $this->modelGeneral->getTableWhere('procedimiento', [
                'estado' => 'S',
            ]),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/tratamientos/tratamientos/nuevo', $data)
            . view('layouts/footer');
    }

    public function getProcedimiento()
    {
        $procedimiento = $this->modelGeneral->getTableWhereRow(
            'procedimiento',
            ['id_procedimiento' => $this->request->getGet('id')]
        );

        $cantidad = (float) $this->request->getGet('cant');

        $procedimiento->cant  = $cantidad;
        $procedimiento->total = number_format($cantidad * $procedimiento->prec_procedimiento, 2);

        return $this->response->setJSON($procedimiento);
    }

    public function guardarTratamiento()
    {
        $rules = [
            'especialidad' => 'required',
            'medico'       => 'required',
            'paciente'     => 'required',
            'asunto'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false]);
        }

        $insert = $this->modelGeneral->insertRegist('tratamiento', [
            'codi_pac'        => $this->request->getPost('paciente'),
            'fecha_tra'       => date('Y-m-d'),
            'codi_med'        => $this->request->getPost('medico'),
            'asunto_tra'      => $this->request->getPost('asunto'),
            'observacion_tra' => $this->request->getPost('observacion'),
        ]);

        if ($insert === null) {
            return $this->response->setJSON(['success' => false]);
        }

        $total = $this->guardarDetalleTratamiento((int) $insert);

        $this->modelGeneral->editRegist(
            'tratamiento',
            ['codi_tra' => $insert],
            ['total_tra' => $total]
        );

        return $this->response->setJSON([
            'success'  => true,
            'redirect' => 'tratamiento/',
        ]);
    }

    public function editar(int $id)
    {
        $tratamiento = $this->tratamientosModel->getTratamiento($id);

        $data = [
            'especialidades' => $this->modelGeneral->getTable('especialidad'),
            'pacientes'      => $this->modelGeneral->getTable('paciente'),
            'procedimientos' => $this->modelGeneral->getTable('procedimiento'),
            'tratamiento'    => $tratamiento,
            'medicos'        => $this->modelGeneral->getTableWhere('medico', [
                'cod_especialidad' => $tratamiento->cod_especialidad ?? null,
            ]),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/tratamientos/tratamientos/editar', $data)
            . view('layouts/footer');
    }

    public function editarGuardarTratamiento()
    {
        $rules = [
            'especialidad'    => 'required',
            'medico'          => 'required',
            'paciente'        => 'required',
            'asunto'          => 'required',
            'id_tratamiento'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false]);
        }

        $idTratamiento = $this->request->getPost('id_tratamiento');

        $edit = $this->modelGeneral->editRegist(
            'tratamiento',
            ['codi_tra' => $idTratamiento],
            [
                'codi_pac'        => $this->request->getPost('paciente'),
                'codi_med'        => $this->request->getPost('medico'),
                'asunto_tra'      => $this->request->getPost('asunto'),
                'observacion_tra' => $this->request->getPost('observacion'),
            ]
        );

        if (!$edit) {
            return $this->response->setJSON(['success' => false]);
        }

        $this->modelGeneral->deleteRegist('tratamiento_detalle', [
            'codi_tra' => $idTratamiento,
        ]);

        $total = $this->guardarDetalleTratamiento((int) $idTratamiento);

        $this->modelGeneral->editRegist(
            'tratamiento',
            ['codi_tra' => $idTratamiento],
            ['total_tra' => $total]
        );

        return $this->response->setJSON([
            'success'  => true,
            'redirect' => 'tratamientos/panel',
        ]);
    }

    private function guardarDetalleTratamiento(int $idTratamiento): float
    {
        $ids   = $this->request->getPost('id') ?? [];
        $cants = $this->request->getPost('cant') ?? [];
        $descs = $this->request->getPost('desc') ?? [];

        $total = 0;

        foreach ($ids as $key => $idProcedimiento) {
            $procedimiento = $this->modelGeneral->getTableWhereRow(
                'procedimiento',
                ['id_procedimiento' => $idProcedimiento]
            );

            if (!$procedimiento) {
                continue;
            }

            $cantidad  = (float) ($cants[$key] ?? 0);
            $descuento = (float) str_replace('%', '', $descs[$key] ?? 0);
            $subtotalBase = $cantidad * (float) $procedimiento->prec_procedimiento;
            $subtotal = $subtotalBase - (($subtotalBase * $descuento) / 100);

            $this->modelGeneral->insertRegist('tratamiento_detalle', [
                'codi_tra'            => $idTratamiento,
                'id_procedimiento'    => $procedimiento->id_procedimiento,
                'preciounit_tradet'   => $procedimiento->prec_procedimiento,
                'cant_tradet'         => $cantidad,
                'descuento_tradet'    => $descuento,
                'subtotal_tradet'     => $subtotal,
            ]);

            $total += $subtotal;
        }

        return $total;
    }

    public function anularTratamiento()
    {
        $id = $this->request->getGet('id');

        $comprobantes = $this->db->table('pago_comprobante')
            ->join('pago', 'pago_comprobante.id_pago = pago.id_pago')
            ->where('codi_tra', $id)
            ->where('pago_comprobante.estado', 1)
            ->countAllResults();

        if ($comprobantes > 0) {
            return $this->response->setJSON([
                'success'              => false,
                'numero_comprobantes'  => $comprobantes,
            ]);
        }

        $this->db->table('tratamiento')
            ->where('codi_tra', $id)
            ->update([
                'estado_tra'     => defined('TRATAMIENTO_ANULADO') ? TRATAMIENTO_ANULADO : 2,
                'estadopago_tra' => defined('ANULADO') ? ANULADO : 4,
            ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function nuevoPago(int $id)
    {
        $tratamiento = $this->tratamientosModel->getTratamiento($id);

        $data = [
            'tratamiento'    => $tratamiento,
            'sin_descuento'  => $this->calcularSinDescuento($tratamiento),
            'tipo_documento' => $this->modelGeneral->getTableWhere('tipo_documento', ['estado' => 1]),
            'tipo_pago'      => $this->modelGeneral->getTableWhere('tipo_pago', ['estado' => 1]),
            'tipo_tarjeta'   => $this->modelGeneral->getTableWhere('tipo_tarjeta', ['estado' => 1]),
            'parametro'      => $this->modelGeneral->getTableWhereRow('parametro', ['cod_parametro' => 1]),
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/tratamientos/pagos/nuevo_pago', $data)
            . view('layouts/footer');
    }

    public function calcularSinDescuento($tratamiento): float
    {
        $precioReal = 0;

        foreach ($tratamiento->detalle ?? [] as $t) {
            $precioReal += $t->preciounit_tradet * $t->cant_tradet;
        }

        return $precioReal;
    }

    public function getPagoCuotas()
    {
        return $this->response->setJSON(
            $this->calcularPagoCuotas([
                'tratamiento'   => $this->request->getGet('tratamiento'),
                'peridiocidad'  => $this->request->getGet('peridiocidad'),
                'numCuotas'     => $this->request->getGet('numCuotas'),
                'fechaCuota'    => $this->request->getGet('fechaCuota'),
            ])
        );
    }

    public function calcularPagoCuotas(array $data): array
    {
        $periodo = match ($data['peridiocidad']) {
            'Semanal'   => '+7 day',
            'Quincenal' => '+14 day',
            'Mensual'   => '+28 day',
            default     => '+30 day',
        };

        $tratamiento = $this->tratamientosModel->getTratamiento($data['tratamiento']);
        $monto = $tratamiento->total_tra / (int) $data['numCuotas'];
        $fechaCuota = $data['fechaCuota'];
        $cuotas = [];

        for ($i = 1; $i <= (int) $data['numCuotas']; $i++) {
            if ($i > 1) {
                $fechaCuota = date('Y-m-d', strtotime($periodo, strtotime($fechaCuota)));
            }

            $cuotas[$i] = [
                'marcar'            => '<input type="checkbox" name="pago[]" value="' . $i . '">',
                'num'               => $i,
                'fecha_registro'    => date('Y-m-d'),
                'fecha_vencimiento' => $fechaCuota,
                'monto'             => round($monto, 2),
            ];
        }

        return $cuotas;
    }

    public function getParametro()
    {
        return $this->response->setJSON(
            $this->modelGeneral->getTableWhereRow('parametro', ['cod_parametro' => 1])
        );
    }

    public function guardarPago()
    {
        $condicion = $this->request->getPost('condicionPago');

        if ($condicion === 'Contado') {
            $this->guardarPagoContado();
        }

        if ($condicion === 'Cuotas') {
            $this->guardarPagoCuotas();
        }

        return $this->response->setJSON([
            'success'  => true,
            'redirect' => 'tratamiento',
        ]);
    }

    private function guardarPagoContado(): void
    {
        $tratamiento = $this->request->getPost('tratamiento');

        $this->modelGeneral->editRegist('tratamiento', ['codi_tra' => $tratamiento], [
            'condpago_tra'   => defined('CONTADO') ? CONTADO : 1,
            'estadopago_tra' => defined('COBRADO') ? COBRADO : 3,
        ]);

        $idPago = $this->modelGeneral->insertRegist('pago', [
            'codi_tra'              => $tratamiento,
            'num_pago'              => 1,
            'estado_pago'           => defined('FINALIZADO') ? FINALIZADO : 2,
            'fecharegistro_pago'    => date('Y-m-d'),
            'fechavencimiento_pago' => date('Y-m-d'),
            'monto_pago'            => $this->request->getPost('total'),
        ]);

        $idCom = $this->guardarComprobante();

        $this->modelGeneral->insertRegist('pago_comprobante', [
            'id_pago' => $idPago,
            'id_com'  => $idCom,
        ]);

        $this->registrarFacturacionElectronica($idCom);
    }

    private function guardarPagoCuotas(): void
    {
        $tratamiento = $this->request->getPost('tratamiento');

        $this->modelGeneral->editRegist('tratamiento', ['codi_tra' => $tratamiento], [
            'condpago_tra'   => defined('CUOTAS') ? CUOTAS : 2,
            'estadopago_tra' => defined('PROCESO') ? PROCESO : 2,
        ]);

        $cuotas = $this->calcularPagoCuotas([
            'tratamiento'  => $tratamiento,
            'peridiocidad' => $this->request->getPost('peridiocidad'),
            'numCuotas'    => $this->request->getPost('numCuotas'),
            'fechaCuota'   => $this->request->getPost('fechaCuota'),
        ]);

        $idCom = $this->guardarComprobante();

        foreach ($cuotas as $key => $value) {
            $this->modelGeneral->insertRegist('pago', [
                'codi_tra'              => $tratamiento,
                'num_pago'              => $key,
                'estado_pago'           => defined('PENDIENTE') ? PENDIENTE : 1,
                'fecharegistro_pago'    => $value['fecha_registro'],
                'fechavencimiento_pago' => $value['fecha_vencimiento'],
                'monto_pago'            => $value['monto'],
            ]);
        }

        foreach ($this->request->getPost('pago') ?? [] as $value) {
            $wherePago = [
                'codi_tra'  => $tratamiento,
                'num_pago'  => $value,
            ];

            $this->modelGeneral->editRegist('pago', $wherePago, [
                'estado_pago' => defined('FINALIZADO') ? FINALIZADO : 2,
            ]);

            $registroPago = $this->modelGeneral->getTableWhereRow('pago', $wherePago);

            $this->modelGeneral->insertRegist('pago_comprobante', [
                'id_pago' => $registroPago->id_pago,
                'id_com'  => $idCom,
            ]);
        }

        $this->registrarFacturacionElectronica($idCom);
    }

    private function guardarComprobante(): int|string|null
    {
        $parametro = $this->modelGeneral->getTableWhereRow('parametro', ['cod_parametro' => 1]);
        $documento = $this->getDocumento($this->request->getPost('documento'));

        $igv = (float) $this->request->getPost('IGV');

        $dataComp = [
            'fecha_com'          => date('Y-m-d'),
            'serie_com'          => $documento['serie'],
            'secuencia_com'      => $documento['secuencia'],
            'cod_tipodocumento'  => $this->request->getPost('documento'),
            'cod_tipopago'       => $this->request->getPost('tipoPago'),
            'recibido_com'       => $this->request->getPost('montoRecibido'),
            'vuelto_com'         => $this->request->getPost('vuelto'),
            'subtotal_comp'      => $this->request->getPost('subtotal'),
            'igv_com'            => $igv,
            'igvparam_com'       => $igv > 0 ? ($parametro->igv ?? 0) : 0,
            'total_comp'         => $this->request->getPost('total'),
        ];

        if ($this->request->getPost('tipoTarjeta')) {
            $dataComp['cod_tarjeta'] = $this->request->getPost('tipoTarjeta');
        }

        $idCom = $this->modelGeneral->insertRegist('comprobante', $dataComp);

        $this->incrementarDocumento($this->request->getPost('documento'));

        return $idCom;
    }

    public function pagarCuota(int $id)
    {
        $tratamiento = $this->tratamientosModel->getTratamiento($id);

        $data = [
            'tratamiento'    => $tratamiento,
            'sin_descuento'  => $this->calcularSinDescuento($tratamiento),
            'tipo_documento' => $this->modelGeneral->getTableWhere('tipo_documento', ['estado' => 1]),
            'tipo_pago'      => $this->modelGeneral->getTableWhere('tipo_pago', ['estado' => 1]),
            'tipo_tarjeta'   => $this->modelGeneral->getTableWhere('tipo_tarjeta', ['estado' => 1]),
            'parametro'      => $this->modelGeneral->getTableWhereRow('parametro', ['cod_parametro' => 1]),
            'cuotas'         => $this->tratamientosModel->getPagosCuotas($id),
            'monto_pago'     => $this->modelGeneral->getTableWhereRow('pago', ['codi_tra' => $id])->monto_pago ?? 0,
        ];

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/tratamientos/pagos/pagar_cuota', $data)
            . view('layouts/footer');
    }

    public function pagarCuotaGuardar()
    {
        $idCom = $this->guardarComprobante();
        $tratamiento = $this->request->getPost('tratamiento');

        foreach ($this->request->getPost('pago') ?? [] as $value) {
            $wherePago = [
                'codi_tra' => $tratamiento,
                'num_pago' => $value,
            ];

            $this->modelGeneral->editRegist('pago', $wherePago, [
                'estado_pago' => defined('FINALIZADO') ? FINALIZADO : 2,
            ]);

            $registroPago = $this->modelGeneral->getTableWhereRow('pago', $wherePago);

            $this->modelGeneral->insertRegist('pago_comprobante', [
                'id_pago' => $registroPago->id_pago,
                'id_com'  => $idCom,
            ]);
        }

        $this->registrarFacturacionElectronica($idCom);

        $pendientes = $this->db->table('pago')
            ->where('codi_tra', $tratamiento)
            ->where('estado_pago', defined('PENDIENTE') ? PENDIENTE : 1)
            ->countAllResults();

        if ($pendientes === 0) {
            $this->modelGeneral->editRegist(
                'tratamiento',
                ['codi_tra' => $tratamiento],
                ['estadopago_tra' => defined('COBRADO') ? COBRADO : 3]
            );
        }

        return $this->response->setJSON([
            'success'  => true,
            'redirect' => 'tratamiento',
        ]);
    }

    public function getDocumento($doc = null)
    {
        $json = false;

        if ($doc === null) {
            $doc = $this->request->getGet('documento');
            $json = true;
        }

        $documento = $this->modelGeneral->getTableWhereRow(
            'tipo_documento',
            ['cod_tipodocumento' => $doc]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDAR SI EXISTE
        |--------------------------------------------------------------------------
        */
        if (!$documento) {

            $resp = [
                'status'     => false,
                'message'    => 'No existe configuración para el tipo de documento.',
                'serie'      => '',
                'secuencia'  => '0000000',
            ];

            return $json
                ? $this->response->setJSON($resp)
                : $resp;
        }

        $resp = [
            'status'     => true,
            'serie'      => $documento->serie ?? '',
            'secuencia'  => str_pad($documento->correlativo_actual ?? 0, 7, '0', STR_PAD_LEFT),
        ];

        return $json
            ? $this->response->setJSON($resp)
            : $resp;
    }

    public function incrementarDocumento($doc): void
    {
        $documento = $this->modelGeneral->getTableWhereRow(
            'tipo_documento',
            ['cod_tipodocumento' => $doc]
        );

        $this->db->table('tipo_documento')
            ->where('cod_tipodocumento', $doc)
            ->update([
                'correlativo_actual' => ((int) $documento->correlativo_actual) + 1,
            ]);
    }

    private function registrarFacturacionElectronica(int|string|null $idCom): void
    {
        if ($idCom === null || $idCom === '') {
            return;
        }

        $this->facturacionElectronicaModel->registrarDesdeComprobante($idCom);
    }

    public function imprimirListaTratamientos()
    {
        $data = [
            'desde'        => $this->request->getGet('desde'),
            'hasta'        => $this->request->getGet('hasta'),
            'paciente'     => $this->request->getGet('paciente'),
            'estado'       => $this->request->getGet('estado'),
        ];

        $data['clinicas'] = $this->clinicaModel->getClinica();
        $data['tratamientos'] = $this->tratamientosModel->getTratamientosImprimir($data);

        return $this->generarPdf(
            'Tratamientos',
            'admin/tratamientos/tratamientos/imprimir_listado',
            $data,
            'A4-L',
            'admin/tratamientos/tratamientos/imprimir_header',
            'admin/tratamientos/tratamientos/imprimir_footer'
        );
    }

    public function imprimirTratamiento(int $id)
    {
        return $this->generarPdf(
            'Tratamiento',
            'admin/tratamientos/tratamientos/imprimir',
            ['tratamiento' => $this->tratamientosModel->getTratamiento($id)]
        );
    }

    public function imprimirComprobante(int $id)
    {
        $data = [
            'comprobante' => $this->tratamientosModel->getComprobante($id),
            'clinicas'    => $this->clinicaModel->getClinica(),
            'facturacion' => $this->facturacionElectronicaModel->obtenerPorComprobante($id),
            'configFacturacion' => $this->facturacionConfigModel->obtener(),
        ];

        return $this->generarPdf(
            'Comprobante',
            'admin/tratamientos/pagos/imprimir_comprobante',
            $data
        );
    }

    private function generarPdf(
        string $titulo,
        string $vista,
        array $data,
        string $formato = 'A4',
        ?string $headerView = null,
        ?string $footerView = null
    ) {
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => $formato,
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => $headerView !== null ? 38 : 10,
            'margin_bottom' => $footerView !== null ? 14 : 10,
            'margin_header' => 8,
            'margin_footer' => 6,
        ]);

        $cssPath = ROOTPATH . 'vendor/styles_pdf.css';
        $css = is_file($cssPath) ? file_get_contents($cssPath) : '';
        $css .= '
            body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
            .pdf-header-table { border-bottom: 1px solid #3c8dbc; padding-bottom: 5px; }
            .pdf-header-logo { vertical-align: top; }
            .pdf-header-center { text-align: center; vertical-align: top; font-size: 8px; line-height: 1.25; }
            .pdf-header-report { text-align: right; vertical-align: top; font-size: 8px; line-height: 1.35; }
            .pdf-clinic-title { font-size: 10px; font-weight: bold; }
            .pdf-clinic-name { font-size: 11px; font-weight: bold; margin-bottom: 2px; }
            .pdf-treatment-table { border-collapse: collapse; margin-bottom: 10px; page-break-inside: avoid; }
            .pdf-treatment-table th,
            .pdf-treatment-table td { border: 1px solid #d6dde2; padding: 4px 5px; vertical-align: top; }
            .pdf-treatment-table th { font-weight: bold; }
            .pdf-head-dark { background: #222d32; color: #fff; }
            .pdf-head-blue { background: #3c8dbc; color: #fff; }
            .pdf-total-empty { border-left-color: #fff; border-bottom-color: #fff; }
            .invoice-page { color: #073f8f; font-size: 9px; line-height: 1.2; }
            .invoice-header,
            .client-table,
            .items-table,
            .footer-table { width: 100%; border-collapse: collapse; }
            .brand-logo { width: 155px; max-height: 84px; }
            .issuer-name { color: #073f8f; font-size: 17px; font-weight: bold; text-align: center; letter-spacing: 0; }
            .issuer-meta { color: #073f8f; font-size: 7px; text-align: center; line-height: 1.3; }
            .document-box-table { width: 175px; border-collapse: collapse; border: 2px solid #1239b5; }
            .document-box-table td { border: 1px solid #1239b5; color: #073f8f; text-align: center; font-weight: bold; padding: 2px 6px; line-height: 1.2; }
            .document-ruc { font-size: 8px; }
            .document-title { font-size: 10px; font-weight: normal; }
            .document-number { font-size: 11px; }
            .client-table td,
            .items-table th,
            .items-table td { border: 1px solid #1239b5; }
            .label-cell,
            .items-table th,
            .amount-label,
            .amount-total-label { background: #073f8f; color: #fff; font-weight: bold; }
            .label-cell { width: 86px; padding: 3px 5px; }
            .value-cell { padding: 3px 5px; color: #073f8f; }
            .items-table th { padding: 4px 4px; text-align: center; font-size: 8px; }
            .items-table td { padding: 4px 4px; color: #111; vertical-align: top; }
            .items-filler td { height: 355px; }
            .num { text-align: right; }
            .center { text-align: center; }
            .amount-label,
            .amount-value,
            .amount-total-label,
            .amount-total-value { border: 1px solid #1239b5; padding: 2px 4px; }
            .amount-value,
            .amount-total-value { color: #073f8f; text-align: right; }
            .amount-total-label,
            .amount-total-value { font-weight: bold; }
            .son { background: #073f8f; color: #fff; font-weight: bold; padding: 5px; border: 1px solid #1239b5; }
            .legal { color: #073f8f; font-size: 6.5px; line-height: 1.25; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        ';

        $mpdf->SetTitle($titulo);

        if ($headerView !== null) {
            $mpdf->SetHTMLHeader(view($headerView, $data));
        }

        if ($footerView !== null) {
            $mpdf->SetHTMLFooter(view($footerView, $data));
        }

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML(view($vista, $data), \Mpdf\HTMLParserMode::HTML_BODY);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($mpdf->Output('', 'S'));
    }
}
