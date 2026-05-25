<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-cog"></i> Configuración SUNAT</h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('facturacion/electronica') ?>">Facturacion electronica</a></li>
      <li class="active">Configuración</li>
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
        Swal.fire('Error', '<?= esc(session()->getFlashdata('error')) ?>', 'error');
      });
    </script>
  <?php endif; ?>

  <section class="content">
    <form method="post" action="<?= base_url('facturacion/electronica/configuracion/guardar') ?>" enctype="multipart/form-data" autocomplete="off">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <a href="<?= base_url('facturacion/electronica') ?>" class="btn btn-default btn-sm">
                <i class="fa fa-hand-o-left"></i> Regresar
              </a>
              <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-save"></i> Guardar configuracion
              </button>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sede</label>
                    <select name="cod_sede" class="form-control input-sm" id="SedeFacturacionConfig">
                      <?php foreach (($sedes ?? []) as $sede): ?>
                        <option value="<?= esc($sede->cod_sede) ?>" <?= ((int) ($codSedeSeleccionada ?? $config->cod_sede ?? 0) === (int) $sede->cod_sede) ? 'selected' : '' ?>>
                          <?= esc($sede->nombre_sede) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 col-ruc-emisor">
                  <div class="form-group">
                    <label>RUC emisor</label>
                    <div class="ruc-search-control">
                      <input type="text" name="ruc_emisor" class="form-control input-sm" maxlength="11" value="<?= esc(old('ruc_emisor', $config->ruc_emisor ?? '')) ?>">
                      <button type="button" id="BuscarRucEmisor" class="btn btn-info btn-sm" title="Buscar RUC">
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Razon social</label>
                    <input type="text" name="razon_social" class="form-control input-sm" value="<?= esc(old('razon_social', $config->razon_social ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombre comercial</label>
                    <input type="text" name="nombre_comercial" class="form-control input-sm" value="<?= esc(old('nombre_comercial', $config->nombre_comercial ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Ubigeo</label>
                    <input type="text" name="ubigeo" class="form-control input-sm" maxlength="6" value="<?= esc(old('ubigeo', $config->ubigeo ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Dirección fiscal</label>
                    <input type="text" name="direccion" class="form-control input-sm" value="<?= esc(old('direccion', $config->direccion ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Departamento</label>
                    <input type="text" name="departamento" class="form-control input-sm" value="<?= esc(old('departamento', $config->departamento ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Provincia</label>
                    <input type="text" name="provincia" class="form-control input-sm" value="<?= esc(old('provincia', $config->provincia ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Distrito</label>
                    <input type="text" name="distrito" class="form-control input-sm" value="<?= esc(old('distrito', $config->distrito ?? '')) ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Clave SOL</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Ambiente</label>
                    <select name="modo" class="form-control input-sm">
                      <option value="beta" <?= (($config->modo ?? 'beta') === 'beta') ? 'selected' : '' ?>>Beta</option>
                      <option value="produccion" <?= (($config->modo ?? '') === 'produccion') ? 'selected' : '' ?>>Produccion</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Usuario SOL</label>
                    <input type="text" name="sol_usuario" class="form-control input-sm" value="<?= esc(old('sol_usuario', $config->sol_usuario ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Clave SOL</label>
                    <input type="password" name="sol_clave" class="form-control input-sm" placeholder="<?= !empty($config->sol_clave) ? 'Clave guardada' : '' ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Firma digital</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Certificado digital (.pfx / .p12)</label>
                    <input type="file" name="certificado" class="form-control input-sm" accept=".pfx,.p12">
                    <?php if (!empty($config->certificado_nombre)): ?>
                      <small>Actual: <?= esc($config->certificado_nombre) ?></small>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Clave del certificado</label>
                    <input type="password" name="certificado_clave" class="form-control input-sm" placeholder="<?= !empty($config->certificado_clave) ? 'Clave guardada' : '' ?>">
                  </div>
                </div>
              </div>
              <p class="text-muted">
                El certificado se guarda en <strong>writable/facturacion/certificados</strong>, fuera de la carpeta publica.
              </p>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>

<style>
  .ruc-search-control {
    position: relative;
    width: 100%;
    max-width: 300px;
  }

  .ruc-search-control .form-control {
    width: 100%;
    padding-right: 46px;
  }

  .ruc-search-control #BuscarRucEmisor {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    right: 0;
    width: 40px;
    min-width: 40px;
    height: 100%;
    padding: 0;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }

  .ruc-search-control #BuscarRucEmisor .fa {
    margin: 0;
    line-height: 1;
  }
</style>

<script>
$(function () {
  const $button = $('#BuscarRucEmisor');
  const $ruc = $('input[name="ruc_emisor"]');
  const fields = {
    razon_social: $('input[name="razon_social"]'),
    nombre_comercial: $('input[name="nombre_comercial"]'),
    ubigeo: $('input[name="ubigeo"]'),
    direccion: $('input[name="direccion"]'),
    departamento: $('input[name="departamento"]'),
    provincia: $('input[name="provincia"]'),
    distrito: $('input[name="distrito"]')
  };

  $('#SedeFacturacionConfig').on('change', function () {
    const codSede = $(this).val() || '';
    window.location.href = '<?= base_url('facturacion/electronica/configuracion') ?>' + (codSede ? '?cod_sede=' + encodeURIComponent(codSede) : '');
  });

  function limpiarCamposRuc() {
    $.each(fields, function (_, $field) {
      $field.val('');
    });
  }

  function llenarCamposRuc(data) {
    fields.razon_social.val(data.razon_social || '');
    fields.nombre_comercial.val(data.nombre_comercial || '');
    fields.ubigeo.val(data.ubigeo || '');
    fields.direccion.val(data.direccion || '');
    fields.departamento.val(data.departamento || '');
    fields.provincia.val(data.provincia || '');
    fields.distrito.val(data.distrito || '');
  }

  $button.on('click', function () {
    const ruc = ($ruc.val() || '').replace(/\D+/g, '');

    if (ruc.length !== 11) {
      limpiarCamposRuc();
      Swal.fire('Atencion', 'Ingrese un RUC valido de 11 digitos.', 'warning');
      return;
    }

    $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.getJSON('<?= base_url('facturacion/electronica/consulta-ruc') ?>/' + ruc)
      .done(function (response) {
        if (response && response.success) {
          llenarCamposRuc(response.data || {});
          return;
        }

        limpiarCamposRuc();
        Swal.fire('Atencion', (response && response.message) || 'No se encontraron datos para el RUC.', 'warning');
      })
      .fail(function () {
        limpiarCamposRuc();
        Swal.fire('Error', 'No se pudo consultar el RUC.', 'error');
      })
      .always(function () {
        $button.prop('disabled', false).html('<i class="fa fa-search"></i>');
      });
  });
});
</script>
