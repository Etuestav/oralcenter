<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-file-text"></i> Facturacion electronica</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li class="active">Facturacion electronica</li>
    </ol>
  </section>

  <?php if (session()->getFlashdata('success')): ?>
    <script>
      $(function () {
        Swal.fire('Listo', '<?= esc(session()->getFlashdata('success')) ?>', 'success');
      });
    </script>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <script>
      $(function () {
        Swal.fire('Atención', '<?= esc(session()->getFlashdata('error')) ?>', 'error');
      });
    </script>
  <?php endif; ?>
  <?php if (session()->getFlashdata('sunat_detalle')): ?>
    <script>
      $(function () {
        Swal.fire('Detalle SUNAT', '<pre style="text-align:left; white-space:pre-wrap"><?= esc(session()->getFlashdata('sunat_detalle')) ?></pre>', 'info');
      });
    </script>
  <?php endif; ?>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <a href="<?= base_url('tratamiento') ?>" class="btn btn-success btn-sm">
              <i class="fa fa-credit-card"></i> Registrar pago
            </a>
            <a href="<?= base_url('facturacion/electronica/nuevo') ?>" class="btn btn-primary btn-sm">
              <i class="fa fa-plus"></i> Nuevo
            </a>
            <a href="<?= base_url('facturacion/electronica/configuracion') ?>" class="btn btn-warning btn-sm">
              <i class="fa fa-cog"></i> Configuración SUNAT
            </a>
            <form action="<?= base_url('facturacion/electronica/reenvioMasivoSunat') ?>" method="post" style="display:inline" onsubmit="return confirm('Se reenviaran a SUNAT los documentos pendientes o rechazados que tengan XML. ¿Continuar?');">
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-send"></i> Reenviar pendientes SUNAT</button>
            </form>
            <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-default btn-sm">
              <i class="fa fa-refresh"></i> Actualizar
            </a>
          </div>
          <div class="box-body">
            <form method="get" autocomplete="off">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Desde</label>
                    <input type="text" name="desde" class="form-control input-sm datepicker" value="<?= esc($filtros['desde'] ?? '') ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Hasta</label>
                    <input type="text" name="hasta" class="form-control input-sm datepicker" value="<?= esc($filtros['hasta'] ?? '') ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Cliente</label>
                    <input type="text" name="cliente" class="form-control input-sm" value="<?= esc($filtros['cliente'] ?? '') ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo_comprobante" class="form-control input-sm">
                      <option value="">Todos</option>
                      <option value="01" <?= (($filtros['tipo_comprobante'] ?? '') === '01') ? 'selected' : '' ?>>Factura</option>
                      <option value="03" <?= (($filtros['tipo_comprobante'] ?? '') === '03') ? 'selected' : '' ?>>Boleta</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-success btn-sm" style="margin-top: 24px">
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="TableFacturacionElectronica" class="table table-bordered table-striped">
                <thead>
                  <tr class="info">
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Número</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Fecha</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Tipo</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Pago</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Cliente</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Total</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">SUNAT</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($comprobantes as $comprobante): ?>
                    <tr>
                      <td><?= esc($comprobante->serie . '-' . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT)) ?></td>
                      <td><?= esc($comprobante->fecha_emision) ?></td>
                      <td><?php $tiposSunat = ['01' => 'Factura', '03' => 'Boleta', '07' => 'Nota de credito', '08' => 'Nota de debito']; ?><?= esc($tiposSunat[$comprobante->tipo_comprobante] ?? $comprobante->tipo_comprobante) ?></td>
                      <td>
                        <?php if (!empty($comprobante->id_com)): ?>
                          <a href="<?= base_url('tratamiento/imprimirComprobante/' . $comprobante->id_com) ?>" target="_blank">
                            <?= esc(($comprobante->serie_com ?? '') . '-' . ($comprobante->secuencia_com ?? '')) ?>
                          </a>
                        <?php else: ?>
                          <span class="text-muted">Manual</span>
                        <?php endif; ?>
                      </td>
                      <td><?= esc($comprobante->razon_social_cliente) ?><br><small><?= esc($comprobante->numero_documento_cliente) ?></small></td>
                      <td style="text-align: right;"><?= esc(number_format((float) $comprobante->total, 2)) ?></td>
                      <td><span class="label label-warning"><?= esc($comprobante->sunat_estado) ?></span></td>
                      <td style="text-align: center;">
                        <a href="<?= base_url('facturacion/electronica/ver/' . $comprobante->id_facturacion) ?>" class="btn btn-info btn-xs">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="<?= base_url('facturacion/electronica/ticket/' . $comprobante->id_facturacion) ?>" target="_blank" class="btn btn-default btn-xs">
                          <i class="fa fa-print"></i>
                        </a>
                        <?php if (($comprobante->sunat_estado ?? '') !== 'aceptado'): ?>
                          <a href="<?= base_url('facturacion/electronica/enviarSunat/' . $comprobante->id_facturacion) ?>"
                             class="btn btn-danger btn-xs"
                             title="Enviar SUNAT"
                             onclick="return confirm('Se generara, firmara y enviara este documento a SUNAT. Continuar?');">
                            <i class="fa fa-send"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
$(function () {
  $('#TableFacturacionElectronica').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json' },
    order: [[0, 'desc']]
  });
});
</script>
