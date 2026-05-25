<?php

namespace App\Controllers\Historia;

use App\Controllers\BaseController;
use App\Models\HistoriaModel;
use App\Models\CitasModel;
use App\Models\ClinicaModel;
use App\Models\ModelGeneral;
use Mpdf\Mpdf;

class Movimiento extends BaseController
{
    protected $permisos;
    protected $historiaModel;
    protected $citasModel;
    protected $clinicaModel;
    protected $modelGeneral;
    protected $db;

    public function __construct()
    {
        helper(['url', 'form']);

        $this->db = db_connect();

        $this->historiaModel = new HistoriaModel();
        $this->citasModel    = new CitasModel();
        $this->clinicaModel  = new ClinicaModel();
        $this->modelGeneral  = new ModelGeneral();

        if (!session()->get('login')) {
            redirect()->to(base_url())->send();
            exit;
        }

        // Ajusta esto si tu librería backend_lib ya fue migrada a CI4
        // $this->permisos = service('backendLib')->control();
        $this->permisos = [];
    }

    public function index()
    {
        $data['permisos'] = $this->permisos;

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/historia/movimiento/panel', $data)
            . view('layouts/footer');
    }

    public function jsonHistoriaClinica()
    {
        $request = $this->request;

        $data['start']  = $request->getGetPost('start');
        $data['length'] = $request->getGetPost('length');
        $data['sEcho']  = $request->getGetPost('_');

        $columns = ['codi_pac', 'NombresApellidos'];

        $order = $request->getGetPost('order');
        $columnIndex = $order[0]['column'] ?? 0;

        $data['orderCampo'] = $columns[$columnIndex] ?? 'codi_pac';
        $data['orderDireccion'] = $order[0]['dir'] ?? 'asc';

        $data['desde'] = $request->getGetPost('desde');
        $data['hasta'] = $request->getGetPost('hasta');

        $nombresApellidos = $request->getGetPost('nombresApellidos');

        if ($nombresApellidos !== '') {
            $data['nombresApellidos'] = $nombresApellidos;
        }

        $datos = $this->historiaModel->getHistoria($data);

        return $this->response->setJSON($datos);
    }

    public function historia($id)
    {
        $data['paciente'] = $this->modelGeneral->getTableWhereRow('paciente', [
            'codi_pac' => $id
        ]);

        $data['departamentos'] = $this->modelGeneral->getTable('departamento');

        $data['provincias'] = [];

        if (!empty($data['paciente']->departamento_id)) {
            $data['provincias'] = $this->modelGeneral->getTableWhere('provincia', [
                'departamento_id' => $data['paciente']->departamento_id
            ]);
        }

        $data['distritos'] = [];

        if (!empty($data['paciente']->provincia_id)) {
            $data['distritos'] = $this->modelGeneral->getTableWhere('distrito', [
                'provincia_id' => $data['paciente']->provincia_id
            ]);
        }

        $data['alergias']     = $this->modelGeneral->getTable('alergia');
        $data['enfermedad']   = $this->modelGeneral->getTableWhereRow('paciente_enfermedadactual', ['codi_pac' => $id]);
        $data['consulta']     = $this->modelGeneral->getTableWhereRow('paciente_consulta', ['codi_pac' => $id]);
        $data['exploracion']  = $this->modelGeneral->getTableWhereRow('paciente_exploracion', ['codi_pac' => $id]);
        $data['paises']       = $this->modelGeneral->getTable('paises');
        $data['diagnosticos'] = $this->modelGeneral->getTableWhere('enfermedad', ['esta_enf' => 'S']);
        $data['especialidad'] = $this->modelGeneral->getTable('especialidad');
        $data['sedes']        = $this->modelGeneral->getTable('sede');
        $data['tipo_citado']  = $this->modelGeneral->getTable('tipo_citado');

        $data['medicos'] = $this->modelGeneral->getTable('medico');

        return view('layouts/header')
            . view('layouts/aside')
            . view('admin/historia/movimiento/historia', $data)
            . view('layouts/footer');
    }

    public function guardarPacienteEnfermedad()
    {
        $post = $this->request->getPost();

        $data = [
            'tiempo_enfact'      => $post['tiempoEnfermedad'] ?? null,
            'motivo_enfact'      => $post['motivoConsulta'] ?? null,
            'signo_enfact'       => $post['signosSintomas'] ?? null,
            'antecper_enfact'    => $post['antecedentesPersonales'] ?? null,
            'antecfam_enfact'    => $post['antecedentesFamiliares'] ?? null,
            'nommedicam_enfact'  => $post['nombreMedicamento'] ?? null,
            'motivomedi_enfact'  => $post['motivoUso'] ?? null,
            'dosis_enfact'       => $post['dosis'] ?? null,
        ];

        if (isset($post['tomandoMedicamento'])) {
            $data['medicam_enfact'] = $post['tomandoMedicamento'];
        }

        $where = [
            'codi_pac' => $post['paciente'] ?? null
        ];

        $edit = $this->modelGeneral->editRegist('paciente_enfermedadactual', $where, $data);

        return $this->response->setJSON([
            'success' => (bool) $edit
        ]);
    }

    public function guardarDatosPaciente()
    {
        $post = $this->request->getPost();

        $data = [
            'nomb_pac'        => $post['nombres'] ?? null,
            'apel_pac'        => $post['apellidos'] ?? null,
            'fena_pac'        => $post['fechaNacimiento'] ?? null,
            'edad_pac'        => $post['edad'] ?? null,
            'dni_pac'         => $post['dni'] ?? null,
            'dire_pac'        => $post['direccion'] ?? null,
            'sexo_pac'        => $post['genero'] ?? null,
            'telf_pac'        => $post['telefono'] ?? null,
            'ocupacion'       => $post['ocupacion'] ?? null,
            'estudios_pac'    => $post['estudios'] ?? null,
            'civi_pac'        => $post['estadoCivil'] ?? null,
            'emai_pac'        => $post['email'] ?? null,
            'pais_id'         => $post['pais'] ?? null,
            'departamento_id' => $post['departamento'] ?? null,
            'provincia_id'    => $post['provincia'] ?? null,
            'distrito_id'     => $post['distrito'] ?? null,
        ];

        $where = [
            'codi_pac' => $post['paciente'] ?? null
        ];

        $edit = $this->modelGeneral->editRegist('paciente', $where, $data);

        return $this->response->setJSON([
            'success' => (bool) $edit
        ]);
    }

    public function guardarPacienteConsulta()
    {
        $post = $this->request->getPost();

        $data = [
            'ortodtexto_paccon'    => $post['algunaVezMedicamentoTexto'] ?? null,
            'medictexto_paccon'    => $post['tomandoMedicamentoTexto'] ?? null,
            'alergicotexto_paccon' => $post['alergicoAnestesicoTexto'] ?? null,
            'hosptexto_paccon'     => $post['hospitalizadoCirugiaTexto'] ?? null,
            'transtexto_paccon'    => $post['transtornoNerviosoEmocionalTexto'] ?? null,
            'cepillatexto_paccon'  => $post['cepillaDientesTexto'] ?? null,
            'presiontexto_paccon'  => $post['presionArterialTexto'] ?? null,
        ];

        if (isset($post['algunaVezMedicamento'])) {
            $data['ortod_paccon'] = $post['algunaVezMedicamento'];
        }

        if (isset($post['tomandoMedicamentoConsulta'])) {
            $data['medic_paccon'] = $post['tomandoMedicamentoConsulta'];
        }

        if (isset($post['alergicoAnestesico'])) {
            $data['alergico_paccon'] = $post['alergicoAnestesico'];
        }

        if (isset($post['hospitalizadoCirugia'])) {
            $data['hosp_paccon'] = $post['hospitalizadoCirugia'];
        }

        if (isset($post['transtornoNerviosoEmocional'])) {
            $data['trans_paccon'] = $post['transtornoNerviosoEmocional'];
        }

        if (isset($post['padeceEnfermedad'])) {
            $data['padece_paccon'] = $post['padeceEnfermedad'];
        }

        if (isset($post['cepillaDientes'])) {
            $data['cepilla_paccon'] = $post['cepillaDientes'];
        }

        if (isset($post['presionArterial'])) {
            $data['presion_paccon'] = $post['presionArterial'];
        }

        $where = [
            'codi_pac' => $post['paciente'] ?? null
        ];

        $edit = $this->modelGeneral->editRegist('paciente_consulta', $where, $data);

        return $this->response->setJSON([
            'success' => (bool) $edit
        ]);
    }

    public function guardarPacienteExploracion()
{
    $post = $this->request->getPost();

    $data = [
        'pa_exp'         => $post['PA'] ?? null,
        'pulso_exp'      => $post['pulso'] ?? null,
        'temperat_exp'   => $post['temperatura'] ?? null,
        'fc_exp'         => $post['FC'] ?? null,
        'frec_exp'       => $post['frecRep'] ?? null,
        'peso_exp'       => $post['peso'] ?? null,
        'talla_exp'      => $post['talla'] ?? null,
        'masa_exp'       => $post['masa'] ?? null,
        'clinico_exp'    => $post['examenClinicoGeneral'] ?? null,
        'complement_exp' => $post['examenComplementario'] ?? null,
        'odontoesto_exp' => $post['odontoestomatologico'] ?? null,
    ];

    $where = [
        'codi_pac' => $post['paciente'] ?? null
    ];

    $edit = $this->modelGeneral->editRegist('paciente_exploracion', $where, $data);

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function jsonAlergias()
{
    $data = $this->getDataTableParams([
        'pacale_id',
        'nombre_ale'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getAlergias($data);

    return $this->response->setJSON($datos);
}

public function jsonCitasHistoria()
{
    $data = $this->getDataTableParams([
        'codi_cit',
        'fech_cit',
        'nombre_especialidad',
        'medico',
        'nomb_citado'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getListadoCitas($data);

    return $this->response->setJSON($datos);
}

public function jsonEvolucion()
{
    $data = $this->getDataTableParams([
        'fecha_evolucion',
        'pacevol_descripcion',
        'medico',
        'nombre_especialidad'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getEvolucion($data);

    return $this->response->setJSON($datos);
}

public function agregarEvolucion()
{
    $rules = [
        'paciente'     => 'required',
        'especialidad' => 'required',
        'medico'       => 'required',
        'evolucion'    => 'required',
        'fecha'        => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $post = $this->request->getPost();

    $data = [
        'codi_pac'            => $post['paciente'],
        'cod_especialidad'    => $post['especialidad'],
        'codi_med'            => $post['medico'],
        'pacevol_descripcion' => $post['evolucion'],
        'fecha_evolucion'     => $post['fecha'],
    ];

    $insert = $this->modelGeneral->insertRegist('paciente_evolucion', $data);

    return $this->response->setJSON([
        'success' => !is_null($insert)
    ]);
}

public function getEvolucion()
{
    $id = $this->request->getGet('id');

    $evolucion = $this->modelGeneral->getTableWhereRow('paciente_evolucion', [
        'pacevol_id' => $id
    ]);

    if ($evolucion) {
        $evolucion->especialidades = $this->db->table('especialidad')
            ->select('cod_especialidad as id, nombre_especialidad as text')
            ->get()
            ->getResult();

        $evolucion->medicos = $this->db->table('medico')
            ->select('codi_med as id, CONCAT(COALESCE(nomb_med, ""), " ", COALESCE(apel_med, "")) AS text')
            ->where('cod_especialidad', $evolucion->cod_especialidad)
            ->get()
            ->getResult();
    }

    return $this->response->setJSON($evolucion);
}

public function getMedicos()
{
    $especialidad = $this->request->getGetPost('especialidad');

    $resp = $this->db->table('medico')
        ->select('codi_med as id, CONCAT(COALESCE(nomb_med, ""), " ", COALESCE(apel_med, "")) AS text')
        ->where('cod_especialidad', $especialidad)
        ->get()
        ->getResult();

    return $this->response->setJSON($resp);
}

public function editarEvolucion()
{
    $rules = [
        'id'           => 'required',
        'paciente'     => 'required',
        'especialidad' => 'required',
        'medico'       => 'required',
        'evolucion'    => 'required',
        'fecha'        => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $post = $this->request->getPost();

    $data = [
        'codi_pac'            => $post['paciente'],
        'cod_especialidad'    => $post['especialidad'],
        'codi_med'            => $post['medico'],
        'pacevol_descripcion' => $post['evolucion'],
        'fecha_evolucion'     => $post['fecha'],
    ];

    $where = [
        'pacevol_id' => $post['id']
    ];

    $edit = $this->modelGeneral->editRegist('paciente_evolucion', $where, $data);

    return $this->response->setJSON([
        'success' => !is_null($edit)
    ]);
}

public function anularEvolucion()
{
    $where = [
        'pacevol_id' => $this->request->getGet('id')
    ];

    $data = [
        'pacevol_estado' => 2
    ];

    $edit = $this->modelGeneral->editRegist('paciente_evolucion', $where, $data);

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function agregarAlergia()
{
    $rules = [
        'paciente' => 'required',
        'alergia'  => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $post = $this->request->getPost();

    $data = [
        'codi_pac'           => $post['paciente'],
        'cod_ale'            => $post['alergia'],
        'pacale_observacion' => $post['observacion'] ?? null,
    ];

    $insert = $this->modelGeneral->insertRegist('paciente_alergia', $data);

    return $this->response->setJSON([
        'success' => !is_null($insert)
    ]);
}

public function getAlergia()
{
    $id = $this->request->getGet('id');

    $alergia = $this->modelGeneral->getTableWhereRow('paciente_alergia', [
        'pacale_id' => $id
    ]);

    return $this->response->setJSON($alergia);
}

public function editarAlergia()
{
    $rules = [
        'id'       => 'required',
        'paciente' => 'required',
        'alergia'  => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $post = $this->request->getPost();

    $data = [
        'cod_ale'            => $post['alergia'],
        'pacale_observacion' => $post['observacion'] ?? null,
    ];

    $where = [
        'pacale_id' => $post['id']
    ];

    $edit = $this->modelGeneral->editRegist('paciente_alergia', $where, $data);

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function anularAlergia()
{
    $where = [
        'pacale_id' => $this->request->getGet('id')
    ];

    $data = [
        'pacale_estado' => 2
    ];

    $edit = $this->modelGeneral->editRegist('paciente_alergia', $where, $data);

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function jsonDiagnostico()
{
    $data = $this->getDataTableParams([
        'pacdiag_estado',
        'codi_enf01',
        'diagnostico01'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getDiagnostico($data);

    return $this->response->setJSON($datos);
}

public function agregarDiagnostico()
{
    $diagnostico01 = $this->request->getPost('diagnostico01');

    $data = [
        'codi_pac'      => $this->request->getPost('paciente'),
        'pacdiag_fecha' => date('Y-m-d'),
        'codi_enf01'    => $diagnostico01 !== '' ? $diagnostico01 : null,
    ];

    $insert = $this->modelGeneral->insertRegist('paciente_diagnostico', $data);

    return $this->response->setJSON([
        'success' => !is_null($insert)
    ]);
}

public function getDiagnosticos()
{
    $id = $this->request->getGet('id');

    $diagnostico = $this->modelGeneral->getTableWhereRow(
        'paciente_diagnostico',
        ['pacdiag_id' => $id]
    );

    return $this->response->setJSON($diagnostico);
}

public function editarDiagnostico()
{
    $diagnostico01 = $this->request->getPost('diagnostico01');

    $data = [
        'codi_enf01' => $diagnostico01 !== '' ? $diagnostico01 : null,
    ];

    $where = [
        'pacdiag_id' => $this->request->getPost('id')
    ];

    $edit = $this->modelGeneral->editRegist(
        'paciente_diagnostico',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function anularDiagnostico()
{
    $data = [
        'pacdiag_estado' => 2
    ];

    $where = [
        'pacdiag_id' => $this->request->getGet('id')
    ];

    $edit = $this->modelGeneral->editRegist(
        'paciente_diagnostico',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function jsonPlacas()
{
    $data = $this->getDataTableParams([
        'pla_fecha',
        'pla_nombre'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getPlacas($data);

    return $this->response->setJSON($datos);
}

public function subir()
{
    $file = $this->request->getFile('placaArchivo');

    if (!$file || !$file->isValid()) {
        return $this->response->setJSON([
            'success' => false,
            'error'   => 'Archivo inválido'
        ]);
    }

    $uploadPath = FCPATH . 'assets/uploads/placas/';
    $thumbPath  = FCPATH . 'assets/uploads/placas/thumbs/';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    if (!is_dir($thumbPath)) {
        mkdir($thumbPath, 0777, true);
    }

    $newName = $file->getRandomName();

    $file->move($uploadPath, $newName);

    service('image')
        ->withFile($uploadPath . $newName)
        ->fit(100, 100, 'center')
        ->save($thumbPath . $newName);

    return $this->response->setJSON([
        'success' => 1,
        'name'    => $newName
    ]);
}

public function agregarPlaca()
{
    $post = $this->request->getPost();

    $data = [
        'codi_pac'    => $post['paciente'] ?? null,
        'pla_nombre'  => $post['nombre'] ?? null,
        'pla_notas'   => $post['notas'] ?? null,
        'pla_archivo' => $post['archivo'] ?? null,
    ];

    $insert = $this->modelGeneral->insertRegist(
        'paciente_placa',
        $data
    );

    return $this->response->setJSON([
        'success' => !is_null($insert)
    ]);
}

public function anularPlaca()
{
    $data = [
        'pla_estado' => 2
    ];

    $where = [
        'pla_id' => $this->request->getGet('id')
    ];

    $edit = $this->modelGeneral->editRegist(
        'paciente_placa',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function getPlaca()
{
    $id = $this->request->getGet('id');

    $placa = $this->modelGeneral->getTableWhereRow(
        'paciente_placa',
        ['pla_id' => $id]
    );

    return $this->response->setJSON($placa);
}

public function editarPlaca()
{
    $post = $this->request->getPost();

    $data = [
        'pla_nombre' => $post['nombre'] ?? null,
        'pla_notas'  => $post['notas'] ?? null,
    ];

    if (!empty($post['archivo'])) {
        $data['pla_archivo'] = $post['archivo'];
        $data['pla_fecha']   = date('Y-m-d H:i:s');
    }

    $where = [
        'pla_id' => $post['id']
    ];

    $edit = $this->modelGeneral->editRegist(
        'paciente_placa',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => (bool) $edit
    ]);
}

public function imprimirHistoria($id)
{
    $mpdf = new \Mpdf\Mpdf([
        'mode'           => 'utf-8',
        'format'         => 'A4',
        'margin_left'    => 5,
        'margin_right'   => 5,
        'margin_top'     => 8,
        'margin_bottom'  => 50,
        'margin_header'  => 10,
        'margin_footer'  => 10,
    ]);

    $data['historia'] = $this->historiaModel->getHistoriaImprimir($id);

    $data['paciente'] = $this->modelGeneral->getTableWhereRow(
        'paciente',
        ['codi_pac' => $id]
    );

    $data['odontogramaInicialImagen'] =
        $this->getPrintableOdontogramaImage((string) $id, 'ini');

    $data['odontogramaEvolucionadoImagen'] =
        $this->getPrintableOdontogramaImage((string) $id, 'evo');

    $html = view('admin/historia/imprimir/contenido', $data);

    $htmlFooter = view('admin/historia/imprimir/footer');

    $cssPath = FCPATH . 'vendor/styles_pdf.css';
    $css = is_file($cssPath) ? file_get_contents($cssPath) : '';

    $mpdf->SetTitle('Historia');
    $mpdf->SetHTMLFooter($htmlFooter);
    if ($css !== '') {
        $mpdf->WriteHTML($css, 1);
    }
    $mpdf->WriteHTML($html, 2);

    return $this->response
        ->setContentType('application/pdf')
        ->setHeader('Content-Disposition', 'inline; filename="Historia.pdf"')
        ->setBody($mpdf->Output('Historia.pdf', 'S'));
}

private function getPrintableOdontogramaImage(string $paciente, string $tipo): ?string
{
    $source = ROOTPATH . 'vendor/img/odontogramas/odontograma-' . $paciente . '-' . $tipo . '.png';

    if (!is_file($source)) {
        return null;
    }

    if (!function_exists('imagecreatefrompng')) {
        return str_replace('\\', '/', $source);
    }

    $cacheDir = WRITEPATH . 'cache/odontogramas/';

    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $target = $cacheDir . 'print-odontograma-' . $paciente . '-' . $tipo . '-' . filemtime($source) . '.png';

    if (is_file($target)) {
        return str_replace('\\', '/', $target);
    }

    $image = imagecreatefrompng($source);

    if ($image === false) {
        return str_replace('\\', '/', $source);
    }

    $width  = imagesx($image);
    $height = imagesy($image);

    $minX = $width;
    $minY = $height;
    $maxX = 0;
    $maxY = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgba  = imagecolorat($image, $x, $y);
            $alpha = ($rgba & 0x7F000000) >> 24;
            $red   = ($rgba >> 16) & 0xFF;
            $green = ($rgba >> 8) & 0xFF;
            $blue  = $rgba & 0xFF;

            if ($alpha < 120 && ($red < 245 || $green < 245 || $blue < 245)) {
                $minX = min($minX, $x);
                $minY = min($minY, $y);
                $maxX = max($maxX, $x);
                $maxY = max($maxY, $y);
            }
        }
    }

    if ($maxX <= $minX || $maxY <= $minY) {
        imagedestroy($image);
        return str_replace('\\', '/', $source);
    }

    $padding = 8;
    $cropX = max(0, $minX - $padding);
    $cropY = max(0, $minY - $padding);
    $cropWidth = min($width - $cropX, ($maxX - $minX) + ($padding * 2));
    $cropHeight = min($height - $cropY, ($maxY - $minY) + ($padding * 2));

    $cropped = imagecrop($image, [
        'x'      => $cropX,
        'y'      => $cropY,
        'width'  => $cropWidth,
        'height' => $cropHeight,
    ]);

    if ($cropped === false) {
        imagedestroy($image);
        return str_replace('\\', '/', $source);
    }

    imagepng($cropped, $target);
    imagedestroy($cropped);
    imagedestroy($image);

    return str_replace('\\', '/', $target);
}

public function imprimirLista()
{
    $data = [
        'desde'             => $this->request->getGet('desde'),
        'hasta'             => $this->request->getGet('hasta'),
        'nombresApellidos'  => $this->request->getGet('nombresApellidos'),
    ];

    $data['historias'] = $this->historiaModel->getHistoriaListado($data);

    $mpdf = new \Mpdf\Mpdf([
        'mode'          => 'utf-8',
        'format'        => 'A4-L',
        'margin_left'   => 7,
        'margin_right'  => 7,
        'margin_top'    => 8,
        'margin_bottom' => 12,
    ]);

    $html = view('admin/historia/imprimir/listado', $data);

    $mpdf->SetTitle('Listado de Historias Clinicas');
    $mpdf->WriteHTML($html);

    return $this->response
        ->setContentType('application/pdf')
        ->setHeader('Content-Disposition', 'inline; filename="ListadoHistorias.pdf"')
        ->setBody($mpdf->Output('ListadoHistorias.pdf', 'S'));
}

public function getMedicosHistoria()
{
    $especialidad = $this->request->getPost('especialidad');

    $medicos = $this->db->table('medico')
        ->where('cod_especialidad', $especialidad)
        ->get()
        ->getResult();

    return $this->response->setJSON($medicos);
}

public function getCitaHistoria()
{
    $id = $this->request->getGet('id');

    $cita = $this->citasModel->getCita($id);

    return $this->response->setJSON($cita);
}

public function editarCitaHistoria()
{
    $rules = [
        'id'                 => 'required',
        'hora'               => 'required',
        'fecha'              => 'required',
        'medicoEditar'       => 'required',
        'especialidadEditar' => 'required',
        'motivo'             => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $post = $this->request->getPost();

    $data = [
        'codi_med'         => $post['medicoEditar'],
        'cod_especialidad' => $post['especialidadEditar'],
        'motivo_consult'   => $post['motivo'],
        'cod_sede'         => $post['sede'] ?? null,
        'cod_citado'       => $post['codigo'] ?? null,
        'fech_cit'         => $post['fecha'] . ' ' . $post['hora'] . ':00',
        'obsv_cit'         => $post['observacion'] ?? null,
    ];

    $where = [
        'codi_cit' => $post['id']
    ];

    $edit = $this->modelGeneral->editRegist(
        'cita_medica',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => !is_null($edit)
    ]);
}

public function jsonHistratamiento()
{
    $data = $this->getDataTableParams([
        'codi_tra',
        'codi_pac',
        'asunto_tra',
        'fecha_tra',
        'total_tra'
    ]);

    $data['paciente'] = $this->request->getGetPost('paciente');

    $datos = $this->historiaModel->getTrataHistoria($data);

    return $this->response->setJSON($datos);
}

public function getOdontograma($json = null)
{
    $paciente = $this->request->getGet('paciente');
    $tipo     = $this->request->getGet('tipoOdontograma');

    $odontograma = $this->db->table('paciente_odontograma')
        ->select('
            pacodo_id as id,
            id_hal,
            pacodo_categoria as categoria,
            pacodo_estado as estado,
            pacodo_sigla as sigla,
            pacodo_id,
            inicio.orden_die as inicio,
            fin.orden_die as fin,
            pacodo_marcas as marcas,
            paciente_odontograma.numero_die as diente
        ')
        ->join(
            'dientes as inicio',
            'paciente_odontograma.numero_die = inicio.numero_die'
        )
        ->join(
            'dientes as fin',
            'paciente_odontograma.pacodo_dientefinal = fin.numero_die',
            'left'
        )
        ->where('pacodo_tipo', $tipo)
        ->where('codi_pac', $paciente)
        ->get()
        ->getResult();

    if (is_null($json)) {
        return $this->response->setJSON($odontograma);
    }

    return $odontograma;
}

public function getHallazgo($id)
{
    return $this->db->table('paciente_odontograma')
        ->select('
            pacodo_id as id,
            id_hal,
            pacodo_categoria as categoria,
            pacodo_estado as estado,
            pacodo_sigla as sigla,
            pacodo_id,
            inicio.orden_die as inicio,
            fin.orden_die as fin,
            pacodo_marcas as marcas,
            paciente_odontograma.numero_die as diente
        ')
        ->join(
            'dientes as inicio',
            'paciente_odontograma.numero_die = inicio.numero_die'
        )
        ->join(
            'dientes as fin',
            'paciente_odontograma.pacodo_dientefinal = fin.numero_die',
            'left'
        )
        ->where('pacodo_id', $id)
        ->get()
        ->getRow();
}

public function getHallazgosDientePaciente()
{
    $tipoOdontograma = $this->request->getGet('tipoOdontograma');
    $paciente        = $this->request->getGet('paciente');
    $diente          = $this->request->getGet('diente');

    $query = $this->db->table('paciente_odontograma')
        ->select('
            pacodo_id as id,
            nombre_hal,
            pacodo_categoria as categoria,
            paciente_odontograma.id_hal,
            pacodo_estado as estado,
            pacodo_sigla as sigla,
            pacodo_id,
            inicio.orden_die as inicio,
            fin.orden_die as fin,
            paciente_odontograma.numero_die as dienteInicio,
            paciente_odontograma.pacodo_dientefinal as dienteFinal,
            pacodo_espec as especificaciones,
            pacodo_marcas as marcas,
            paciente_odontograma.numero_die as diente
        ')
        ->join(
            'dientes as inicio',
            'paciente_odontograma.numero_die = inicio.numero_die'
        )
        ->join(
            'dientes as fin',
            'paciente_odontograma.pacodo_dientefinal = fin.numero_die',
            'left'
        )
        ->join(
            'hallazgos',
            'paciente_odontograma.id_hal = hallazgos.id_hal'
        )
        ->where('codi_pac', $paciente)
        ->where('pacodo_tipo', $tipoOdontograma)
        ->where('paciente_odontograma.numero_die', $diente)
        ->get()
        ->getResult();

    return $this->response->setJSON($query);
}

public function agregarHallazgo()
{
    $post = $this->request->getPost();

    $data = [
        'codi_pac'        => $post['paciente'] ?? null,
        'pacodo_tipo'     => $post['tipoOdontograma'] ?? null,
        'id_hal'          => $post['hallazgo'] ?? null,
        'numero_die'      => $post['diente'] ?? null,
        'pacodo_espec'    => $post['especificaciones'] ?? null,
        'codi_usu'        => 1,
        'pacodo_datetime' => date('Y-m-d H:i:s'),
    ];

    if (!empty($post['estado'])) {
        $data['pacodo_estado'] = $post['estado'];
    }

    if (!empty($post['dienteFinal'])) {
        $data['pacodo_dientefinal'] = $post['dienteFinal'];
    }

    if (!empty($post['sigla'])) {
        $data['pacodo_sigla'] = $post['sigla'];
    }

    if (!empty($post['categoria'])) {
        $data['pacodo_categoria'] = $post['categoria'];
    }

    if (($post['marcas'] ?? '') === '1') {

        $marcas = [];

        foreach ([
            'Vestibular',
            'Palatino',
            'Lingual',
            'Distal',
            'Mesial',
            'Oclusal'
        ] as $marca) {

            if (isset($post[$marca])) {

                $marcas[$marca]['Valor'] = true;

                if (isset($post[$marca . 'Estado'])) {
                    $marcas[$marca]['Estado'] =
                        $post[$marca . 'Estado'];
                }
            }
        }

        $data['pacodo_marcas'] = json_encode($marcas);
    }

    $insert = $this->modelGeneral->insertRegist(
        'paciente_odontograma',
        $data
    );

    $resp = [
        'success' => !is_null($insert)
    ];

    if (!is_null($insert)) {
        $resp['data'] = $this->getHallazgo($insert);
    }

    return $this->response->setJSON($resp);
}

public function eliminarHallazgo()
{
    $id = $this->request->getGet('id');

    $delete = $this->db->table('paciente_odontograma')
        ->where('pacodo_id', $id)
        ->delete();

    return $this->response->setJSON([
        'success' => (bool) $delete
    ]);
}

public function guardarImagenOdontograma()
{
    $baseFromJavascript = $this->request->getPost('imgData');

    $baseToPhp = explode(',', $baseFromJavascript);

    $data = base64_decode($baseToPhp[1] ?? '', true);

    if ($data === false) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Imagen no valida',
        ]);
    }

    $tipo = $this->request->getPost('tipo') === 'Inicial'
        ? 'ini'
        : 'evo';

    $directory = ROOTPATH . 'vendor/img/odontogramas/';

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $filepath = $directory .
        'odontograma-' .
        $this->request->getPost('paciente') .
        '-' .
        $tipo .
        '.png';

    $saved = file_put_contents($filepath, $data);

    return $this->response->setJSON([
        'success' => $saved !== false
    ]);
}

public function guardarDetalleOdontograma()
{
    $data = [
        'detalleodontograma_pac' =>
            $this->request->getPost('detalle')
    ];

    $where = [
        'codi_pac' => $this->request->getPost('paciente')
    ];

    $edit = $this->modelGeneral->editRegist(
        'paciente',
        $where,
        $data
    );

    return $this->response->setJSON([
        'success' => !is_null($edit)
    ]);
}

public function cambiarTipoOdontograma()
{
    $data = [];

    $resp = [
        'html' => view(
            'admin/historia/movimiento/odontograma/cursores',
            $data
        ),
        'odontograma' => $this->getOdontograma('Json')
    ];

    return $this->response->setJSON($resp);
}

private function getDataTableParams(array $columns): array
{
    $request = $this->request;

    $order = $request->getGetPost('order');

    $columnIndex = $order[0]['column'] ?? 0;

    return [
        'start'          => $request->getGetPost('start'),
        'length'         => $request->getGetPost('length'),
        'sEcho'          => $request->getGetPost('_'),
        'orderCampo'     => $columns[$columnIndex] ?? $columns[0],
        'orderDireccion' => $order[0]['dir'] ?? 'asc',
    ];
}
}
