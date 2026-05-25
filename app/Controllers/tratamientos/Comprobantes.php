<?php

namespace App\Controllers\Tratamientos;

use App\Controllers\BaseController;
use App\Models\TratamientosModel;
use App\Models\ClinicaModel;
use App\Models\Modelgeneral;
use Mpdf\Mpdf;

class Comprobantes extends BaseController
{
    protected TratamientosModel $tratamientosModel;
    protected ClinicaModel $clinicaModel;
    protected Modelgeneral $modelGeneral;
    protected $db;

    public function __construct()
    {
        $this->tratamientosModel = new TratamientosModel();
        $this->clinicaModel      = new ClinicaModel();
        $this->modelGeneral      = new Modelgeneral();
        $this->db                = db_connect();
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url(''));
        }

        $data = [
            'parametro' => $this->modelGeneral->getTableWhereRow(
                'parametro',
                ['cod_parametro' => 1]
            ),
        ];

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('admin/tratamientos/comprobantes/panel', $data) .
            view('layouts/footer');
    }

    public function jsonComprobantes()
    {
        $columns = [
            '',
            'secuencia_com',
            'fecharegistro_pago',
            'fechavencimiento_pago',
        ];

        $order = $this->request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data = [
            'start'          => $this->request->getGetPost('start'),
            'length'         => $this->request->getGetPost('length'),
            'sEcho'          => $this->request->getGetPost('_'),
            'orderCampo'     => $columns[$columnIndex] ?? 'secuencia_com',
            'orderDireccion' => $order[0]['dir'] ?? 'ASC',
            'desde'          => $this->request->getGetPost('desde'),
            'hasta'          => $this->request->getGetPost('hasta'),
            'estado'         => $this->request->getGetPost('estado') ?: 'Emitido',
        ];

        $paciente = $this->request->getGetPost('paciente');

        if (!empty($paciente)) {
            $data['paciente'] = $paciente;
        }

        return $this->response->setJSON(
            $this->tratamientosModel->getComprobantes($data)
        );
    }

    public function imprimirLista()
    {
        $data = [
            'parametro' => $this->modelGeneral->getTableWhereRow(
                'parametro',
                ['cod_parametro' => 1]
            ),
            'desde'    => $this->request->getGet('desde'),
            'hasta'    => $this->request->getGet('hasta'),
            'estado'   => $this->request->getGet('estado'),
            'paciente' => $this->request->getGet('paciente'),
        ];

        $data['clinicas'] = $this->clinicaModel->getClinica();
        $data['comprobantes'] = $this->tratamientosModel->getImprimirComprobantes($data);

        $html = view('admin/tratamientos/comprobantes/imprimir_listado', $data);
        $htmlHeader = view('admin/tratamientos/comprobantes/imprimir_header');
        $htmlFooter = view('admin/tratamientos/tratamientos/imprimir_footer');

        $cssPath = FCPATH . 'assets/styles_pdf.css';
        $css = is_file($cssPath) ? file_get_contents($cssPath) : '';

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 40,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        $mpdf->SetTitle('Comprobantes');
        $mpdf->SetHTMLHeader($htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($mpdf->Output('', 'S'));
    }

    public function anularComprobante()
    {
        $id = $this->request->getGet('id');

        $this->modelGeneral->editRegist(
            'comprobante',
            ['id_com' => $id],
            ['estado_com' => 2]
        );

        $pagos = $this->modelGeneral->getTableWhere(
            'pago_comprobante',
            ['id_com' => $id]
        );

        foreach ($pagos as $p) {
            $this->db->table('pago')
                ->where('id_pago', $p->id_pago)
                ->update(['estado_pago' => 1]);

            $this->db->table('pago_comprobante')
                ->where('id_pago', $p->id_pago)
                ->where('id_com', $id)
                ->update(['estado' => 2]);
        }

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
}
