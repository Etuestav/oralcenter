<?php

namespace App\Models;

use CodeIgniter\Model;

class TratamientosModel extends Model
{
    protected $table      = 'tratamiento';
    protected $primaryKey = 'codi_tra';
    protected $returnType = 'object';

    protected $allowedFields = [
        'codi_pac',
        'codi_med',
        'asunto_tra',
        'fecha_tra',
        'total_tra',
        'estadopago_tra',
        'condpago_tra',
        'estado_tra',
    ];

    public function getTratamientos(array $data): array
    {
        $builderLike = $this->baseTratamientoQuery();

        $this->aplicarFiltrosTratamiento($builderLike, $data);

        $total = $builderLike->countAllResults();

        $builder = $this->baseTratamientoQuery();

        $this->aplicarFiltrosTratamiento($builder, $data);

        if (!empty($data['orderCampo'])) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        $query = $builder->get();

        $rows = [];

        foreach ($query->getResult() as $q) {
            $estado = $this->labelEstadoPago((int) $q->estadopago_tra);

            $pago = ((int) $q->condpago_tra === CUOTAS)
                ? '<label class="label label-default">Cuotas</label>'
                : '<label class="label label-default">Contado</label>';

            $opciones = $this->opcionesTratamiento($q);

            $botonDetalle = '<button class="btn btn-md"><span class="fa fa-caret-right"></span></button>';

            $rows[] = [
                $botonDetalle,
                $q->codi_tra,
                $q->NombresApellidos,
                $q->asunto_tra,
                $q->fecha_tra,
                $q->total_tra,
                $pago,
                $estado,
                $opciones,
                $this->detalleTratamiento($q->codi_tra),
                $this->detallePago($q->codi_tra),
                $q->condpago_tra,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $total,
            'iTotalDisplayRecords' => $total,
            'aaData'               => $rows,
        ];
    }

    private function baseTratamientoQuery()
    {
        return $this->db->table('tratamiento')
            ->select('
                tratamiento.*,
                CONCAT(nomb_pac, " ", apel_pac) AS NombresApellidos,
                estadopago_tra,
                condpago_tra
            ')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac');
    }

    private function aplicarFiltrosTratamiento($builder, array $data): void
    {
        if (!empty($data['paciente'])) {
            $builder->like('CONCAT(nomb_pac, " ", apel_pac)', $data['paciente']);
        }

        if (!empty($data['desde']) && !empty($data['hasta'])) {
            $builder->where('fecha_tra >=', $data['desde']);
            $builder->where('fecha_tra <=', $data['hasta']);
        }

        if (!empty($data['estado']) && $data['estado'] === 'Activo') {
            $builder->where('estado_tra', TRATAMIENTO_ACTIVO);
        } elseif (!empty($data['estado']) && $data['estado'] === 'Anulado') {
            $builder->where('estado_tra', TRATAMIENTO_ANULADO);
        }
    }

    private function labelEstadoPago(int $estado): string
    {
        return match ($estado) {
            POR_COBRAR => '<label class="label label-warning">Por Cobrar</label>',
            PROCESO    => '<label class="label label-info">Proceso</label>',
            COBRADO    => '<label class="label label-success">Cobrado</label>',
            ANULADO    => '<label class="label label-danger">Anulado</label>',
            default    => '',
        };
    }

    private function opcionesTratamiento(object $q): string
    {
        $opciones = '
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs">Opciones</button>
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
        ';

        if ((int) $q->estadopago_tra === POR_COBRAR) {
            $opciones .= '
                <li>
                    <a href="' . base_url('tratamiento/nuevoPago/' . $q->codi_tra) . '">
                        <i class="fa fa-credit-card"></i> Pago
                    </a>
                </li>
            ';
        }

        if ((int) $q->estadopago_tra === PROCESO) {
            $opciones .= '
                <li>
                    <a href="' . base_url('tratamiento/pagarCuota/' . $q->codi_tra) . '">
                        <i class="fa fa-credit-card"></i> Pagar Cuota
                    </a>
                </li>
            ';
        }

        if (
            (int) $q->estadopago_tra === PROCESO ||
            (int) $q->estadopago_tra === POR_COBRAR
        ) {
            $opciones .= '
                <li data-id="' . $q->codi_tra . '" class="tratamiento-anular">
                    <a href="#"><i class="fa fa-trash-o"></i> Anular</a>
                </li>
            ';
        }

        if ($q->condpago_tra === null) {
            $opciones .= '
                <li>
                    <a href="' . base_url('tratamiento/editar/' . $q->codi_tra) . '">
                        <i class="fa fa-pencil"></i> Editar
                    </a>
                </li>
            ';
        }

        $opciones .= '
                <li>
                    <a href="' . base_url('tratamiento/imprimirTratamiento/' . $q->codi_tra) . '" target="_blank">
                        <i class="fa fa-print"></i> Imprimir
                    </a>
                </li>
            </ul>
        </div>';

        return $opciones;
    }

    public function detalleTratamiento(int|string $tratamiento): string
    {
        $detalle = $this->db->table('tratamiento_detalle')
            ->select('tratamiento_detalle.*, procedimiento.nombre')
            ->join('procedimiento', 'tratamiento_detalle.id_procedimiento = procedimiento.id_procedimiento')
            ->where('tratamiento_detalle.codi_tra', $tratamiento)
            ->get()
            ->getResult();

        return json_encode($detalle);
    }

    public function detallePago(int|string $tratamiento): string
    {
        $pago = $this->db->table('pago')
            ->select('pago.*, comprobante.*, tipo_documento.descripcion, tipo_documento.abreviatura')
            ->join('pago_comprobante', 'pago.id_pago = pago_comprobante.id_pago', 'left')
            ->join('comprobante', 'pago_comprobante.id_com = comprobante.id_com AND estado_com = ' . COMPROBANTE_VALIDO, 'left')
            ->join('tipo_documento', 'comprobante.cod_tipodocumento = tipo_documento.cod_tipodocumento', 'left')
            ->where('codi_tra', $tratamiento)
            ->orderBy('num_pago', 'ASC')
            ->get()
            ->getResult();

        return json_encode($pago);
    }

    public function getTratamiento(int|string $id): ?object
    {
        $tratamiento = $this->db->table('tratamiento')
            ->select('
                tratamiento.*,
                especialidad.cod_especialidad,
                nombre_especialidad,
                nomb_pac,
                apel_pac,
                dire_pac,
                nomb_med,
                apel_med
            ')
            ->join('medico', 'tratamiento.codi_med = medico.codi_med')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac')
            ->where('codi_tra', $id)
            ->get()
            ->getRow();

        if (!$tratamiento) {
            return null;
        }

        $tratamiento->detalle = $this->db->table('tratamiento_detalle')
            ->select('tratamiento_detalle.*, procedimiento.nombre')
            ->join('procedimiento', 'tratamiento_detalle.id_procedimiento = procedimiento.id_procedimiento')
            ->where('tratamiento_detalle.codi_tra', $id)
            ->get()
            ->getResult();

        return $tratamiento;
    }

    public function getPagosCuotas(int|string $tratamiento): array
    {
        return $this->db->table('pago')
            ->where('codi_tra', $tratamiento)
            ->orderBy('num_pago', 'ASC')
            ->get()
            ->getResult();
    }

    public function getComprobante(int|string $id): ?object
    {
        $comprobante = $this->db->table('comprobante')
            ->select('
                comprobante.*,
                tipo_documento.cod_tipodocumento,
                tipo_documento.descripcion,
                abreviatura,
                asunto_tra,
                tratamiento.codi_tra,
                paciente.codi_pac,
                paciente.nomb_pac,
                paciente.apel_pac,
                dni_pac,
                dire_pac,
                nomb_med,
                apel_med
            ')
            ->join('tipo_documento', 'comprobante.cod_tipodocumento = tipo_documento.cod_tipodocumento')
            ->join('pago_comprobante', 'comprobante.id_com = pago_comprobante.id_com')
            ->join('pago', 'pago_comprobante.id_pago = pago.id_pago')
            ->join('tratamiento', 'pago.codi_tra = tratamiento.codi_tra')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac')
            ->join('medico', 'tratamiento.codi_med = medico.codi_med')
            ->where('comprobante.id_com', $id)
            ->groupBy('comprobante.id_com')
            ->get()
            ->getRow();

        if (!$comprobante) {
            return null;
        }

        $comprobante->procedimientos = $this->db->table('tratamiento_detalle')
            ->select('
                tratamiento_detalle.*,
                (preciounit_tradet - ((preciounit_tradet * descuento_tradet) / 100)) AS precio_unitario,
                procedimiento.nombre
            ')
            ->join('procedimiento', 'tratamiento_detalle.id_procedimiento = procedimiento.id_procedimiento')
            ->where('tratamiento_detalle.codi_tra', $comprobante->codi_tra)
            ->get()
            ->getResult();

        $comprobante->num = $this->db->table('pago')
            ->where('codi_tra', $comprobante->codi_tra)
            ->countAllResults();

        return $comprobante;
    }

    public function getTratamientosImprimir(array $data): array
    {
        $builder = $this->db->table('tratamiento');

        $builder->select('
                tratamiento.*,
                CONCAT(nomb_pac, " ", apel_pac) AS NombresApellidos,
                nomb_med,
                apel_med,
                nombre_especialidad
            ')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac')
            ->join('medico', 'tratamiento.codi_med = medico.codi_med')
            ->join('especialidad', 'medico.cod_especialidad = especialidad.cod_especialidad');

        $this->aplicarFiltrosTratamiento($builder, $data);

        $tratamientos = $builder->get()->getResult();

        foreach ($tratamientos as $t) {
            $t->procedimientos = $this->db->table('tratamiento_detalle')
                ->select('tratamiento_detalle.*, procedimiento.nombre')
                ->join('procedimiento', 'tratamiento_detalle.id_procedimiento = procedimiento.id_procedimiento')
                ->where('tratamiento_detalle.codi_tra', $t->codi_tra)
                ->get()
                ->getResult();
        }

        return $tratamientos;
    }

    public function getComprobantes(array $data): array
    {
        $queryTotal = $this->baseComprobantesQuery($data, true)
            ->get()
            ->getResult();

        $total = 0;

        foreach ($queryTotal as $t) {
            $total += (float) ($t->total ?? 0);
        }

        $builderLike = $this->baseComprobantesQuery($data);

        $totalRegistros = $builderLike->countAllResults();

        $builder = $this->baseComprobantesQuery($data, false, true);

        if (isset($data['length']) && (int) $data['length'] !== -1) {
            $builder->limit((int) $data['length'], (int) ($data['start'] ?? 0));
        }

        if (!empty($data['orderCampo']) && in_array($data['estado'], ['Emitido', 'Anulado'], true)) {
            $builder->orderBy($data['orderCampo'], $data['orderDireccion'] ?? 'ASC');
        }

        if (!empty($data['paciente'])) {
            $builder->like('CONCAT(nomb_pac, " ", apel_pac)', $data['paciente']);
        }

        $query = $builder->get();

        $rows = [];

        foreach ($query->getResult() as $q) {
            $opciones = '
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-xs">Opciones</button>
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="' . base_url('tratamiento/imprimirComprobante/' . $q->id_com) . '" target="_blank">
                                <i class="fa fa-print"></i> Imprimir
                            </a>
                        </li>
                        <li>
                            <a href="' . base_url('facturacion/electronica/desdeComprobante/' . $q->id_com) . '">
                                <i class="fa fa-file-text-o"></i> Comprobante electronico
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#" data-id="' . $q->id_com . '" class="anular-comprobante">
                                <i class="fa fa-trash-o"></i> Anular
                            </a>
                        </li>
                    </ul>
                </div>';

            $comprobante = !empty($q->id_com)
                ? json_encode($this->getComprobante($q->id_com))
                : null;

            $botonDetalle = '<button class="btn btn-md"><span class="fa fa-caret-right"></span></button>';

            $serie = isset($q->serie_com, $q->secuencia_com)
                ? $q->serie_com . '-' . $q->secuencia_com
                : '';

            $rows[] = [
                $botonDetalle,
                $serie,
                $q->fecharegistro_pago,
                $q->fechavencimiento_pago,
                $q->descripcion ?? '',
                $q->NombresApellidos,
                $q->monto_pago_acumulado,
                $data['estado'],
                $q->codi_tra ?? '',
                $opciones,
                $comprobante,
            ];
        }

        return [
            'sEcho'                => $data['sEcho'] ?? 1,
            'iTotalRecords'        => $totalRegistros,
            'iTotalDisplayRecords' => $totalRegistros,
            'total'                => $total,
            'aaData'               => $rows,
        ];
    }

    private function baseComprobantesQuery(
        array $data,
        bool $withTotal = false,
        bool $withTratamiento = false
    ) {
        $builder = $this->db->table('pago');

        if (in_array($data['estado'], ['Emitido', 'Anulado'], true)) {
            $select = $withTratamiento ? 'tratamiento.codi_tra, ' : '';

            $builder->select($select . '
                pago.id_pago,
                fecharegistro_pago,
                fechavencimiento_pago,
                serie_com,
                secuencia_com,
                comprobante.id_com,
                CONCAT(nomb_pac, " ", apel_pac) AS NombresApellidos,
                tipo_documento.*,
                SUM(monto_pago) AS monto_pago_acumulado
                ' . ($withTotal ? ', SUM(monto_pago) AS total' : '') . '
            ');
        } else {
            $select = $withTratamiento ? 'tratamiento.codi_tra, ' : '';

            $builder->select($select . '
                pago.id_pago,
                fecharegistro_pago,
                fechavencimiento_pago,
                CONCAT(nomb_pac, " ", apel_pac) AS NombresApellidos,
                monto_pago AS monto_pago_acumulado
                ' . ($withTotal ? ', SUM(monto_pago) AS total' : '') . '
            ');
        }

        $builder->join('tratamiento', 'pago.codi_tra = tratamiento.codi_tra')
            ->join('paciente', 'tratamiento.codi_pac = paciente.codi_pac');

        if (!empty($data['desde'])) {
            $builder->where('fecha_tra >=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $builder->where('fecha_tra <=', $data['hasta']);
        }

        if (in_array($data['estado'], ['Emitido', 'Anulado'], true)) {
            $builder->join('pago_comprobante', 'pago.id_pago = pago_comprobante.id_pago', 'left')
                ->join('comprobante', 'pago_comprobante.id_com = comprobante.id_com', 'left')
                ->join('tipo_documento', 'comprobante.cod_tipodocumento = tipo_documento.cod_tipodocumento', 'left')
                ->where('comprobante.id_com IS NOT NULL');

            if ($data['estado'] === 'Emitido') {
                $builder->where('pago_comprobante.estado', 1);
            } else {
                $builder->where('pago_comprobante.estado', 2);
            }

            $builder->groupBy('comprobante.id_com');
        } elseif ($data['estado'] === 'Por Cobrar') {
            $builder->where('estado_pago', 1);
        }

        return $builder;
    }

    public function getImprimirComprobantes(array $data): array
    {
        $builder = $this->baseComprobantesQuery($data);

        if (!empty($data['paciente'])) {
            $builder->like('CONCAT(nomb_pac, " ", apel_pac)', $data['paciente']);
        }

        $query = $builder->get()->getResult();

        if (in_array($data['estado'], ['Emitido', 'Anulado'], true)) {
            foreach ($query as $q) {
                $q->comprobante = $this->getComprobante($q->id_com);
            }
        }

        return $query;
    }
}
