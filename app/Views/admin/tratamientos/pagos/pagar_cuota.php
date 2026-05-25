<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
   <section class="content-header">
    <h1>Tratamientos</h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">Tratamiento</a></li>
      <li class="active">cuotas</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
       
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <a href="<?= base_url('tratamiento') ?>"><i class="fa fa-hand-o-left"></i> Regresar
                  
                  </a>
                </div>
              </div>
            </div>
            <form id="TratamientosFormPagosCuotas" autocomplete="off" method="POST" action="<?= base_url('tratamiento/pagarCuotaGuardar') ?>">
              <input type="hidden" name="tratamiento" value="<?= $tratamiento->codi_tra ?>">
              <input type="hidden" name="igvValor" value="<?= $parametro->igv ?>">
              <input type="hidden" name="cuota" value="<?= $monto_pago ?>">
              <div class="row">
                <div class="col-md-9">
                   <div class="box box-default box-solid box-default ">
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Documento:</label>
                        <select name="documento" class="form-control input-sm">
                          <?php foreach ($tipo_documento as $t): ?>
                          <option value="<?= $t->cod_tipodocumento ?>" <?= ($t->descripcion=='BOLETA')?'selected':'' ?>><?= $t->descripcion ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Fecha:</label>
                        <input type="text" name="fecha" class="form-control input-sm" disabled value="<?= date('Y-m-d') ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Sec. Plan Trat.</label>
                        <input type="text" name="fecha" class="form-control input-sm" disabled>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Serie:</label>
                        <input type="text" name="serie" class="form-control input-sm" value="" disabled>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Secuencia:</label>
                        <input type="text" name="secuencia" class="form-control input-sm" value="" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Paciente:</label>
                        <div class="input-group">
                          <span class="input-group-addon" id="paciente-input-group"><?= $tratamiento->codi_pac ?></span>
                          <input class="form-control input-sm" placeholder="Username" aria-describedby="paciente-input-group" value="<?= $tratamiento->nomb_pac.' '.$tratamiento->apel_pac ?>" disabled>
                        </div>
                      </div>
                    </div>
                     <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Dirección:</label>
                        <input type="text" class="form-control input-sm" value="<?= $tratamiento->dire_pac ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Médico:</label>
                        <input type="text" class="form-control input-sm" value="<?= $tratamiento->nomb_med.' '.$tratamiento->apel_med ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Especialidad:</label>
                        <input type="text" class="form-control input-sm" value="<?= $tratamiento->nombre_especialidad ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Asunto:</label>
                        <input type="text" name="asunto" class="form-control input-sm" value="<?= $tratamiento->asunto_tra ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Observación:</label>
                        <input type="text" class="form-control input-sm" value="<?= $tratamiento->observacion_tra ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Total Tratamiento:</label>
                        <input type="text" name="precio" class="form-control input-sm" value="<?= $tratamiento->total_tra ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Sin Descuento:</label>
                        <input type="text" name="descuento" class="form-control input-sm" value="<?= $sin_descuento ?>" disabled style="text-decoration: line-through;">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Condición de Pago:</label>
                        <input type="text" class="form-control input-sm" value="Cuotas" disabled>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-primary">
                          <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                              <button type="button" class="btn btn-link tratamiento-detalle-toggle" data-target="#collapseOne" aria-expanded="false">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Presionar detalle
                              </button>
                            </h4>
                          </div>
                          <div id="collapseOne" class="panel-collapse collapse tratamiento-detalle-panel" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="table-responsive">
                              <table class="table table-bordered">
                                <thead>
                                  <tr  class="btn-primary btn-xs">
                                    <th style="text-align: center">Código</th>
                                    <th style="text-align: center" width="220">Nombre</th>
                                    <th style="text-align: center" width="100">Cantidad</th>
                                    <th style="text-align: center">Precio</th>
                                    <th style="text-align: center" width="90">Dsct%</th>
                                    <th style="text-align: center">Total</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($tratamiento->detalle as $t): ?>
                                    
                                  <tr>
                                    <td style="text-align: center;"><?= $t->id_procedimiento ?></td>
                                    <td><?= $t->nombre ?></td>
                                    <td style="text-align: center;"><?= $t->cant_tradet ?></td>
                                    <td style="text-align: center;"><?= $t->preciounit_tradet ?></td>
                                    <td style="text-align: center;"><?= $t->descuento_tradet ?></td>
                                    <td style="text-align: center;"><?= $t->subtotal_tradet ?></td>
                                  </tr>
                                  <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <tr>
                                      <th colspan="5" class="text-right" style="color:#1B9A10">Total: </th>
                                      <th style="text-align: center; color:#1B9A10"><?= $tratamiento->total_tra ?></th>
                                      <th></th>
                                    </tr>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row hidden" id="TratamientoFormPagoCuotas">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label input-sm">Periodicidad</label>
                        <select name="peridiocidad" class="form-control input-sm">
                          <option value="Semanal">Semanal</option>
                          <option value="Quincenal">Quincenal</option>
                          <option value="Mensual">Mensual</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">N° de Cuotas:</label>
                        <input type="number" name="numCuotas" class="form-control input-sm" value="2" min="2">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">1° Fecha de Pago:</label>
                        <input type="text" name="fechaCuota" class="form-control input-sm" value="<?= date('Y-m-d') ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <button type="button" class="btn btm-md btn-info" id="TratamientoPagosCalcularCuotas" style="margin-top:24px">Calcular Cuotas:</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <fieldset class="scheduler-border" style="border-color: #F00; border-style: solid; border-width:1px;">
                        <legend class="scheduler-border">Datos Comprobante - Cuotas</legend>
                          <div class="table-responsive">
                        <table id="TablePagoCuotas" class="table table-bordered table-hover">
                          <thead>
                            <tr class="btn-primary btn-xs">
                              <th style="text-align: center;">Marcar</th>
                              <th style="text-align: center;">N° Cuota</th>
                              <th style="text-align: center;">Fecha Registro</th>
                              <th style="text-align: center;">Fecha Vencimiento</th>
                              <th style="text-align: center;">Monto</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($cuotas as $c): ?>
                            <tr>
                              <td>
                                <?php if ($c->estado_pago==2): ?>
                                <input type="checkbox" disabled checked>
                                <?php else: ?>
                                <input type="checkbox" name="pago[]" value="<?= $c->num_pago ?>">
                                <?php endif ?>
                              </td>
                              <td style="text-align: center;"><?= $c->num_pago ?></td>
                              <td style="text-align: center;"><?= $c->fecharegistro_pago ?></td>
                              <td style="text-align: center;"><?= $c->fechavencimiento_pago ?></td>
                              <td style="text-align: center;"><?= $c->monto_pago ?></td>
                            </tr>
                            <?php endforeach ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th colspan="4" class="text-right" style="color:#1B9A10">Total:</th>
                              <th id="TableCuotas-Total" style="text-align: center; color:#1B9A10"><?= $tratamiento->total_tra ?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <fieldset class="scheduler-border" style="border-color: #F00; border-style: solid; border-width:1px;">
                    <legend class="scheduler-border">Forma de Pago</legend>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Método de Pago</label>
                          <select name="tipoPago" class="form-control input-sm">
                            <?php foreach ($tipo_pago as $t): ?>
                            <option value="<?= $t->cod_tipopago ?>" <?= ($t->cod_tipopago==4)?'selected':'' ?>><?= $t->descripcion ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Tipo de Tarjeta</label>
                          <select name="tipoTarjeta" class="form-control input-sm" disabled>
                            <option value=""></option>
                            <?php foreach ($tipo_tarjeta as $t): ?>
                            <option value="<?= $t->cod_tarjeta ?>" ><?= $t->descripcion ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Monto recibido:</label>
                          <input type="text" name="montoRecibido" class="form-control input-sm">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Vuelto:</label>
                          <input type="text" name="vuelto" class="form-control input-sm" readonly>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset class="scheduler-border" style="border-color: #F00; border-style: solid; border-width:1px;">
                    <legend class="scheduler-border">Total Pago</legend>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label" style="color:#1B9A10">Sub-total:</label>
                          <input type="text" name="subtotal" class="form-control input-sm" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label" style="color:#1B9A10">I.G.V</label>
                          <input type="text" name="IGV" class="form-control input-sm" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label" style="color:#1B9A10">Total:</label>
                          <input type="text" name="total" class="form-control input-sm" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Generar</button>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>

<style>
  .tratamiento-detalle-toggle {
    color: #fff;
    padding: 0;
    font-size: 16px;
    text-decoration: none;
  }

  .tratamiento-detalle-toggle:hover,
  .tratamiento-detalle-toggle:focus {
    color: #fff;
    text-decoration: none;
    outline: none;
  }
</style>

<script>
$(function () {
  $('.tratamiento-detalle-toggle').off('click.tratamientoDetalle').on('click.tratamientoDetalle', function (event) {
    event.preventDefault();
    event.stopPropagation();

    const $button = $(this);
    const $panel = $($button.data('target'));
    const $icon = $button.find('.fa');
    const abierto = $panel.is(':visible');

    if (abierto) {
      $panel.stop(true, true).slideUp(150, function () {
        $panel.removeClass('in').attr('aria-expanded', 'false');
      });
      $button.attr('aria-expanded', 'false');
      $icon.removeClass('fa-minus-square').addClass('fa-plus-square');
      return;
    }

    $panel.addClass('in').attr('aria-expanded', 'true').hide().stop(true, true).slideDown(150);
    $button.attr('aria-expanded', 'true');
    $icon.removeClass('fa-plus-square').addClass('fa-minus-square');
  });
});
</script>
