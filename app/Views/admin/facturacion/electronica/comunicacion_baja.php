<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-file-excel-o"></i> Comunicación de baja</h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('facturacion/electronica') ?>">Facturación electrónica</a></li>
      <li class="active">Comunicación de baja</li>
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
            <input type="text" name="fecha" class="form-control input-sm datepicker" value="<?= esc($filtros['fecha'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Cliente</label>
            <input type="text" name="cliente" class="form-control input-sm" value="<?= esc($filtros['cliente'] ?? '') ?>">
          </div>
          <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Buscar</button>
          <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-default btn-sm"><i class="fa fa-hand-o-left"></i> Regresar</a>
        </form>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="info">
                <th>Número</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th class="text-right">Total</th>
                <th>SUNAT</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($documentos)): ?>
                <tr><td colspan="6" class="text-center">No hay documentos disponibles para baja.</td></tr>
              <?php endif; ?>
              <?php foreach ($documentos as $documento): ?>
                <tr>
                  <td><?= esc($documento->serie . '-' . str_pad((string) $documento->correlativo, 8, '0', STR_PAD_LEFT)) ?></td>
                  <td><?= esc($documento->fecha_emision) ?></td>
                  <td><?= esc($documento->razon_social_cliente) ?><br><small><?= esc($documento->numero_documento_cliente) ?></small></td>
                  <td class="text-right"><?= esc(number_format((float) $documento->total, 2)) ?></td>
                  <td><span class="label label-warning"><?= esc($documento->sunat_estado) ?></span></td>
                  <td>
                    <form method="post" action="<?= base_url('facturacion/electronica/comunicacion-baja/enviar') ?>" class="form-inline" onsubmit="return confirm('Se enviará la comunicación de baja a SUNAT. ¿Continuar?');">
                      <input type="hidden" name="id_facturacion" value="<?= esc($documento->id_facturacion, 'attr') ?>">
                      <input type="text" name="motivo" class="form-control input-sm" placeholder="Motivo" required>
                      <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-send"></i> Enviar baja</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
