<div class="content-wrapper">
<section class="content-header">
    <?php
        $estadoPagoTexto = static function (int $estado): string {
            return match ($estado) {
                defined('POR_COBRAR') ? POR_COBRAR : 1 => 'Por Cobrar',
                defined('PROCESO') ? PROCESO : 2 => 'Proceso',
                defined('COBRADO') ? COBRADO : 3 => 'Cobrado',
                defined('ANULADO') ? ANULADO : 4 => 'Anulado',
                default => '',
            };
        };
    ?>
    <h1>
        <i class="fa fa-money"></i> Estado de Cuenta
        <small><?= esc($paciente->nomb_pac . ' ' . $paciente->apel_pac) ?></small>
    </h1>
</section>

<section class="content">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Resumen del paciente</h3>
            <div class="box-tools pull-right">
                <a href="<?= base_url('historia/ver/' . $paciente->codi_pac) ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-file-text-o"></i> Historia Clínica
                </a>
                <button type="button" class="btn btn-default btn-sm" onclick="window.print()">
                    <i class="fa fa-print"></i> Imprimir
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Paciente</strong>
                    <p><?= esc($paciente->nomb_pac . ' ' . $paciente->apel_pac) ?></p>
                </div>
                <div class="col-md-3">
                    <strong>DNI</strong>
                    <p><?= esc($paciente->dni_pac ?? '') ?></p>
                </div>
                <div class="col-md-3">
                    <strong>Teléfono</strong>
                    <p><?= esc($paciente->telf_pac ?? '') ?></p>
                </div>
                <div class="col-md-3">
                    <strong>Dirección</strong>
                    <p><?= esc($paciente->dire_pac ?? '') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>S/ <?= number_format($totalTratamientos, 2) ?></h3>
                    <p>Total tratamientos</p>
                </div>
                <div class="icon"><i class="fa fa-stethoscope"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>S/ <?= number_format($totalPagado, 2) ?></h3>
                    <p>Total pagado</p>
                </div>
                <div class="icon"><i class="fa fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>S/ <?= number_format($saldo, 2) ?></h3>
                    <p>Saldo pendiente</p>
                </div>
                <div class="icon"><i class="fa fa-credit-card"></i></div>
            </div>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Tratamientos y pagos</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tratamiento</th>
                        <th>Fecha</th>
                        <th>Doctor</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Saldo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tratamientos)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay tratamientos registrados para este paciente.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($tratamientos as $tratamiento): ?>
                        <?php
                            $pagadoTratamiento = 0.0;
                            foreach ($tratamiento->pagos as $pago) {
                                if ((int) ($pago->estado_pago ?? 0) === (defined('FINALIZADO') ? FINALIZADO : 2)) {
                                    $pagadoTratamiento += (float) ($pago->monto_pago ?? 0);
                                }
                            }
                            $saldoTratamiento = (float) ($tratamiento->total_tra ?? 0) - $pagadoTratamiento;
                        ?>
                        <tr>
                            <td>
                                <strong><?= esc($tratamiento->asunto_tra) ?></strong><br>
                                <small>#<?= esc($tratamiento->codi_tra) ?></small>
                            </td>
                            <td><?= esc($tratamiento->fecha_tra) ?></td>
                            <td><?= esc(trim(($tratamiento->nomb_med ?? '') . ' ' . ($tratamiento->apel_med ?? ''))) ?></td>
                            <td class="text-right">S/ <?= number_format((float) ($tratamiento->total_tra ?? 0), 2) ?></td>
                            <td class="text-right">S/ <?= number_format($pagadoTratamiento, 2) ?></td>
                            <td class="text-right">S/ <?= number_format($saldoTratamiento, 2) ?></td>
                            <td><?= esc($estadoPagoTexto((int) ($tratamiento->estadopago_tra ?? 0))) ?></td>
                        </tr>

                        <?php if (!empty($tratamiento->pagos)): ?>
                            <tr>
                                <td colspan="7" style="padding: 0;">
                                    <table class="table table-condensed" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th style="width: 15%;">Cuota</th>
                                                <th style="width: 20%;">Registro</th>
                                                <th style="width: 20%;">Vencimiento</th>
                                                <th style="width: 20%;">Comprobante</th>
                                                <th style="width: 15%;" class="text-right">Monto</th>
                                                <th style="width: 10%;">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tratamiento->pagos as $pago): ?>
                                                <?php
                                                    $comprobante = '';
                                                    if (!empty($pago->serie_com) || !empty($pago->secuencia_com)) {
                                                        $comprobante = trim(($pago->abreviatura ?? '') . ' ' . ($pago->serie_com ?? '') . '-' . ($pago->secuencia_com ?? ''));
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= esc($pago->num_pago ?? '') ?></td>
                                                    <td><?= esc($pago->fecharegistro_pago ?? '') ?></td>
                                                    <td><?= esc($pago->fechavencimiento_pago ?? '') ?></td>
                                                    <td><?= esc($comprobante) ?></td>
                                                    <td class="text-right">S/ <?= number_format((float) ($pago->monto_pago ?? 0), 2) ?></td>
                                                    <td><?= ((int) ($pago->estado_pago ?? 0) === (defined('FINALIZADO') ? FINALIZADO : 2)) ? 'Pagado' : 'Pendiente' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
</div>
