<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-calendar-check-o"></i> Resumen de boletas</h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('facturacion/electronica') ?>">Facturación electrónica</a></li>
      <li class="active">Resumen de boletas</li>
    </ol>
  </section>

  <?php if (session()->getFlashdata('success')): ?>
    <script>$(function(){ Swal.fire('Listo', '<?= esc(session()->getFlashdata('success')) ?>', 'success'); });</script>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <script>$(function(){ Swal.fire('Atención', '<?= esc(session()->getFlashdata('error')) ?>', 'error'); });</script>
  <?php endif; ?>

  <section class="content">
    <div class="box box-info">
      <div class="box-header with-border">
        <form method="get" class="form-inline" autocomplete="off">
          <div class="form-group">
            <label>Fecha</label>
            <input type="text" name="fecha" class="form-control input-sm datepicker" value="<?= esc($fecha) ?>">
          </div>
          <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Buscar</button>
          <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-default btn-sm"><i class="fa fa-hand-o-left"></i> Regresar</a>
        </form>
      </div>
      <div class="box-body">
        <div class="alert alert-info">
          Se enviarán a SUNAT las boletas pendientes del día seleccionado mediante resumen diario.
        </div>

        <?php if (!empty($boletas)): ?>
          <form method="post" action="<?= base_url('facturacion/electronica/resumen-boletas/enviar') ?>" onsubmit="return confirm('Se generará y enviará el resumen de boletas a SUNAT. ¿Continuar?');">
            <input type="hidden" name="fecha" value="<?= esc($fecha, 'attr') ?>">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-send"></i> Generar XML y enviar resumen</button>
          </form>
          <br>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="info">
                <th>Número</th>
                <th>Cliente</th>
                <th class="text-right">Op. gravada</th>
                <th class="text-right">IGV</th>
                <th class="text-right">Total</th>
                <th>SUNAT</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($boletas)): ?>
                <tr><td colspan="6" class="text-center">No hay boletas pendientes para esta fecha.</td></tr>
              <?php endif; ?>
              <?php foreach ($boletas as $boleta): ?>
                <tr>
                  <td><?= esc($boleta->serie . '-' . str_pad((string) $boleta->correlativo, 8, '0', STR_PAD_LEFT)) ?></td>
                  <td><?= esc($boleta->razon_social_cliente) ?><br><small><?= esc($boleta->numero_documento_cliente) ?></small></td>
                  <td class="text-right"><?= esc(number_format((float) $boleta->op_gravada, 2)) ?></td>
                  <td class="text-right"><?= esc(number_format((float) $boleta->igv, 2)) ?></td>
                  <td class="text-right"><?= esc(number_format((float) $boleta->total, 2)) ?></td>
                  <td><span class="label label-warning"><?= esc($boleta->sunat_estado) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
