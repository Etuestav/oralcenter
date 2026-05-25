<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-plus"></i> Nuevo comprobante electronico</h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('facturacion/electronica') ?>">Facturacion electronica</a></li>
      <li class="active">Nuevo</li>
    </ol>
  </section>

  <?php if (session()->getFlashdata('error')): ?>
    <script>
      $(function () {
        Swal.fire('Error', '<?= esc(session()->getFlashdata('error')) ?>', 'error');
      });
    </script>
  <?php endif; ?>

  <section class="content">
    <form method="post" action="<?= base_url('facturacion/electronica/guardar') ?>" autocomplete="off">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-default btn-sm">
                <i class="fa fa-hand-o-left"></i> Regresar
              </a>
              <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-save"></i> Guardar
              </button>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Sede</label>
                    <select name="cod_sede" class="form-control input-sm">
                      <?php foreach (($sedes ?? []) as $sede): ?>
                        <option value="<?= esc($sede->cod_sede) ?>"><?= esc($sede->nombre_sede) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo_comprobante" class="form-control input-sm" id="TipoComprobante">
                      <option value="01" <?= (($tipoComprobante ?? '01') === '01') ? 'selected' : '' ?>>Factura</option>
                      <option value="03" <?= (($tipoComprobante ?? '01') === '03') ? 'selected' : '' ?>>Boleta</option>
                      <option value="07" <?= (($tipoComprobante ?? '01') === '07') ? 'selected' : '' ?>>Nota de credito</option>
                      <option value="08" <?= (($tipoComprobante ?? '01') === '08') ? 'selected' : '' ?>>Nota de debito</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Serie</label>
                    <input type="text" name="serie" class="form-control input-sm" id="SerieComprobante" value="<?= in_array(($tipoComprobante ?? '01'), ['03'], true) ? 'B001' : 'F001' ?>" maxlength="4">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Fecha emision</label>
                    <input type="text" name="fecha_emision" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Moneda</label>
                    <select name="moneda" class="form-control input-sm">
                      <option value="PEN">PEN</option>
                      <option value="USD">USD</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="box" id="DatosNotaElectronica" style="display: none;">
            <div class="box-header with-border">
              <h3 class="box-title">Documento relacionado</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tipo afectado</label>
                    <select name="tipo_documento_relacionado" class="form-control input-sm">
                      <option value="01">Factura</option>
                      <option value="03">Boleta</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Documento afectado</label>
                    <input type="text" name="documento_relacionado" class="form-control input-sm" placeholder="F001-00000001">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Motivo SUNAT</label>
                    <select name="motivo_codigo" class="form-control input-sm" id="MotivoNota">
                      <option value="01" data-tipo="07">Anulación de la operación</option>
                      <option value="02" data-tipo="07">Anulación por error en RUC</option>
                      <option value="03" data-tipo="07">Corrección por error en descripcion</option>
                      <option value="04" data-tipo="07">Descuento global</option>
                      <option value="05" data-tipo="07">Descuento por item</option>
                      <option value="06" data-tipo="07">Devolucion total</option>
                      <option value="07" data-tipo="07">Devolucion por item</option>
                      <option value="08" data-tipo="07">Bonificacion</option>
                      <option value="09" data-tipo="07">Disminucion en el valor</option>
                      <option value="10" data-tipo="07">Otros conceptos</option>
                      <option value="01" data-tipo="08">Intereses por mora</option>
                      <option value="02" data-tipo="08">Aumento en el valor</option>
                      <option value="03" data-tipo="08">Penalidades</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Descripción motivo</label>
                    <input type="text" name="motivo_descripcion" class="form-control input-sm" id="MotivoDescripcion" value="ANULACION DE LA OPERACION">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Cliente</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Paciente</label>
                    <select name="codi_pac" class="form-control input-sm select2" id="PacienteFacturacion" style="width: 100%;">
                      <option value="">Cliente manual</option>
                      <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?= esc($paciente->codi_pac) ?>"><?= esc(($paciente->nomb_pac ?? '') . ' ' . ($paciente->apel_pac ?? '')) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tipo doc.</label>
                    <select name="tipo_documento_cliente" class="form-control input-sm">
                      <option value="1">DNI</option>
                      <option value="6">RUC</option>
                      <option value="0">Doc. no domiciliado</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Número doc.</label>
                    <input type="text" name="numero_documento_cliente" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Razon social / nombres</label>
                    <input type="text" name="razon_social_cliente" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion_cliente" class="form-control input-sm">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Detalle</h3>
              <button type="button" class="btn btn-success btn-xs pull-right" id="AgregarItemFacturacion">
                <i class="fa fa-plus"></i> Item
              </button>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="TablaItemsFacturacion">
                  <thead>
                    <tr>
                      <th style="width: 12%;">Código</th>
                      <th>Descripción</th>
                      <th style="width: 12%;">Cantidad</th>
                      <th style="width: 15%;">Precio inc. IGV</th>
                      <th style="width: 15%;">Total</th>
                      <th style="width: 5%;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" name="codigo_producto[]" class="form-control input-sm"></td>
                      <td><input type="text" name="descripcion[]" class="form-control input-sm" value="Servicio odontológico"></td>
                      <td><input type="number" name="cantidad[]" class="form-control input-sm item-cantidad" value="1" min="0.01" step="0.01"></td>
                      <td><input type="number" name="precio_unitario[]" class="form-control input-sm item-precio" value="0.00" min="0.01" step="0.01"></td>
                      <td class="item-total text-right">0.00</td>
                      <td class="text-center"><button type="button" class="btn btn-danger btn-xs quitar-item"><i class="fa fa-trash"></i></button></td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr><th colspan="4" class="text-right">Op. gravada</th><th class="text-right" id="OpGravada">0.00</th><th></th></tr>
                    <tr><th colspan="4" class="text-right">IGV</th><th class="text-right" id="IgvTotal">0.00</th><th></th></tr>
                    <tr><th colspan="4" class="text-right">Total</th><th class="text-right" id="TotalComprobante">0.00</th><th></th></tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>

<script>
$(function () {
  function recalcular() {
    let total = 0;
    $('#TablaItemsFacturacion tbody tr').each(function () {
      const cantidad = parseFloat($(this).find('.item-cantidad').val()) || 0;
      const precio = parseFloat($(this).find('.item-precio').val()) || 0;
      const linea = cantidad * precio;
      total += linea;
      $(this).find('.item-total').text(linea.toFixed(2));
    });
    const opGravada = total / 1.18;
    const igv = total - opGravada;
    $('#OpGravada').text(opGravada.toFixed(2));
    $('#IgvTotal').text(igv.toFixed(2));
    $('#TotalComprobante').text(total.toFixed(2));
  }

  $('#TipoComprobante').on('change', function () {
    const tipo = $(this).val();
    $('#SerieComprobante').val(tipo === '03' ? 'B001' : 'F001');
    $('#DatosNotaElectronica').toggle(tipo === '07' || tipo === '08');
    $('#MotivoNota option').each(function () {
      $(this).toggle($(this).data('tipo') === tipo);
    });
    const firstVisible = $('#MotivoNota option:visible:first');
    if (firstVisible.length) {
      $('#MotivoNota').val(firstVisible.val());
      $('#MotivoDescripcion').val(firstVisible.text().toUpperCase());
    }
  });

  $('#MotivoNota').on('change', function () {
    $('#MotivoDescripcion').val($(this).find('option:selected').text().toUpperCase());
  });

  $('#PacienteFacturacion').on('change', function () {
    const id = $(this).val();
    if (!id) return;
    $.getJSON(base_url + 'facturacion/electronica/paciente/' + id, function (paciente) {
      $('input[name=numero_documento_cliente]').val(paciente.dni_pac || '');
      $('input[name=razon_social_cliente]').val(((paciente.nomb_pac || '') + ' ' + (paciente.apel_pac || '')).trim());
      $('input[name=direccion_cliente]').val(paciente.dire_pac || '');
      $('select[name=tipo_documento_cliente]').val('1');
    });
  });

  $('#AgregarItemFacturacion').on('click', function () {
    $('#TablaItemsFacturacion tbody').append(`
      <tr>
        <td><input type="text" name="codigo_producto[]" class="form-control input-sm"></td>
        <td><input type="text" name="descripcion[]" class="form-control input-sm"></td>
        <td><input type="number" name="cantidad[]" class="form-control input-sm item-cantidad" value="1" min="0.01" step="0.01"></td>
        <td><input type="number" name="precio_unitario[]" class="form-control input-sm item-precio" value="0.00" min="0.01" step="0.01"></td>
        <td class="item-total text-right">0.00</td>
        <td class="text-center"><button type="button" class="btn btn-danger btn-xs quitar-item"><i class="fa fa-trash"></i></button></td>
      </tr>`);
  });

  $('#TablaItemsFacturacion').on('input', '.item-cantidad, .item-precio', recalcular);
  $('#TablaItemsFacturacion').on('click', '.quitar-item', function () {
    if ($('#TablaItemsFacturacion tbody tr').length > 1) {
      $(this).closest('tr').remove();
      recalcular();
    }
  });
  $('#TipoComprobante').trigger('change');
  recalcular();
});
</script>
