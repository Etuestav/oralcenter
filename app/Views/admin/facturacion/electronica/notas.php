<?php
  $tiposSunat = [
    '01' => 'Factura',
    '03' => 'Boleta',
    '07' => 'Nota de Credito',
    '08' => 'Nota de Debito',
  ];

  $numeroRelacionado = '';
  if (!empty($comprobante)) {
      $numeroRelacionado = $comprobante->serie . '-' . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT);
  }

  $motivosCredito = [
    '01' => 'ANULACION DE LA OPERACION',
    '02' => 'ANULACION POR ERROR EN RUC',
    '03' => 'CORRECCION POR ERROR EN DESCRIPCION',
    '04' => 'DESCUENTO GLOBAL',
    '05' => 'DESCUENTO POR ITEM',
    '06' => 'DEVOLUCION TOTAL',
    '07' => 'DEVOLUCION POR ITEM',
    '08' => 'BONIFICACION',
    '09' => 'DISMINUCION EN EL VALOR',
    '10' => 'OTROS CONCEPTOS',
  ];

  $motivosDebito = [
    '01' => 'INTERESES POR MORA',
    '02' => 'AUMENTO EN EL VALOR',
    '03' => 'PENALIDADES',
  ];

  $motivos = $tipoNota === '08' ? $motivosDebito : $motivosCredito;
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-sticky-note"></i> Ingreso Nota de debito y credito</h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('facturacion/electronica') ?>">Facturacion electronica</a></li>
      <li class="active">Nota deb/cred</li>
    </ol>
  </section>

  <?php if (session()->getFlashdata('error')): ?>
    <script>
      $(function () {
        Swal.fire('Atención', '<?= esc(session()->getFlashdata('error')) ?>', 'error');
      });
    </script>
  <?php endif; ?>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-list"></i> Lista
            </a>
          </div>
          <div class="box-body">
            <form method="get" action="<?= base_url('facturacion/electronica/notas') ?>" autocomplete="off">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tipo de Nota</label>
                    <select name="tipo" class="form-control input-sm">
                      <option value="07" <?= $tipoNota === '07' ? 'selected' : '' ?>>Nota de Credito</option>
                      <option value="08" <?= $tipoNota === '08' ? 'selected' : '' ?>>Nota de Debito</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nro Doc. Modificado</label>
                    <input type="text" name="documento" class="form-control input-sm" value="<?= esc($documento) ?>" placeholder="Buscar documento">
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary btn-sm" style="margin-top:24px">
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </form>

            <?php if (empty($comprobante)): ?>
              <div class="alert alert-warning" style="margin-top: 12px">
                Busque el comprobante original para cargar el cliente, el documento modificado y el detalle de productos como en el sistema inicial.
              </div>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($comprobante)): ?>
          <form method="post" action="<?= base_url('facturacion/electronica/guardar') ?>" autocomplete="off">
            <input type="hidden" name="tipo_comprobante" value="<?= esc($tipoNota) ?>">
            <input type="hidden" name="tipo_documento_relacionado" value="<?= esc($comprobante->tipo_comprobante) ?>">
            <input type="hidden" name="documento_relacionado" value="<?= esc($numeroRelacionado) ?>">
            <input type="hidden" name="fecha_emision" value="<?= date('Y-m-d') ?>">
            <input type="hidden" name="moneda" value="<?= esc($comprobante->moneda ?: 'PEN') ?>">
            <input type="hidden" name="codi_pac" value="<?= esc($comprobante->codi_pac ?? '') ?>">
            <input type="hidden" name="tipo_documento_cliente" value="<?= esc($comprobante->tipo_documento_cliente) ?>">
            <input type="hidden" name="numero_documento_cliente" value="<?= esc($comprobante->numero_documento_cliente) ?>">
            <input type="hidden" name="razon_social_cliente" value="<?= esc($comprobante->razon_social_cliente) ?>">
            <input type="hidden" name="direccion_cliente" value="<?= esc($comprobante->direccion_cliente) ?>">

            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title"><?= esc($tiposSunat[$tipoNota]) ?> para <?= esc($numeroRelacionado) ?></h3>
                <button type="submit" class="btn btn-success btn-sm pull-right">
                  <i class="fa fa-save"></i> Guardar nota
                </button>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Serie</label>
                      <input type="text" name="serie" class="form-control input-sm" value="<?= esc($comprobante->serie) ?>" maxlength="4">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Tipo afectado</label>
                      <input type="text" class="form-control input-sm" value="<?= esc($tiposSunat[$comprobante->tipo_comprobante] ?? $comprobante->tipo_comprobante) ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Documento afectado</label>
                      <input type="text" class="form-control input-sm" value="<?= esc($numeroRelacionado) ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Cliente</label>
                      <input type="text" class="form-control input-sm" value="<?= esc($comprobante->razon_social_cliente) ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Motivo SUNAT</label>
                      <select name="motivo_codigo" class="form-control input-sm" id="MotivoNota">
                        <?php foreach ($motivos as $codigo => $motivo): ?>
                          <option value="<?= esc($codigo) ?>"><?= esc($motivo) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <label>Descripción motivo</label>
                      <input type="text" name="motivo_descripcion" class="form-control input-sm" id="MotivoDescripcion" value="<?= esc(reset($motivos)) ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Detalle cargado del comprobante original</h3>
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
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($comprobante->detalle as $item): ?>
                        <tr>
                          <td><input type="text" name="codigo_producto[]" class="form-control input-sm" value="<?= esc($item->codigo_producto ?? '') ?>"></td>
                          <td><input type="text" name="descripcion[]" class="form-control input-sm" value="<?= esc($item->descripcion) ?>"></td>
                          <td><input type="number" name="cantidad[]" class="form-control input-sm item-cantidad" value="<?= esc(number_format((float) $item->cantidad, 2, '.', '')) ?>" min="0.01" step="0.01"></td>
                          <td><input type="number" name="precio_unitario[]" class="form-control input-sm item-precio" value="<?= esc(number_format((float) $item->precio_unitario, 2, '.', '')) ?>" min="0.01" step="0.01"></td>
                          <td class="item-total text-right">0.00</td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr><th colspan="4" class="text-right">Op. gravada</th><th class="text-right" id="OpGravada">0.00</th></tr>
                      <tr><th colspan="4" class="text-right">IGV</th><th class="text-right" id="IgvTotal">0.00</th></tr>
                      <tr><th colspan="4" class="text-right">Total</th><th class="text-right" id="TotalComprobante">0.00</th></tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>

<script>
$(function () {
  function recalcularNota() {
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

  $('#MotivoNota').on('change', function () {
    $('#MotivoDescripcion').val($(this).find('option:selected').text());
  });

  $('#TablaItemsFacturacion').on('input', '.item-cantidad, .item-precio', recalcularNota);
  recalcularNota();
});
</script>
