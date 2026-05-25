<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-user"></i> Mi perfil
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Mi perfil</li>
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
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <form action="<?= base_url('usuario/perfil/update') ?>" method="post" autocomplete="off">
            <div class="box-header with-border">
              <a href="<?= base_url('dashboard') ?>" class="btn btn-default btn-sm">
                <i class="fa fa-hand-o-left"></i> Regresar
              </a>
              <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-save"></i> Guardar
              </button>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellido" class="form-control input-sm" value="<?= esc(old('apellido', $usuario->apellido ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" name="nombre" class="form-control input-sm" value="<?= esc(old('nombre', $usuario->nombre ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tipo doc.</label>
                    <select name="tipo_documento" class="form-control input-sm">
                      <?php foreach ([1 => 'DNI', 2 => 'RUC', 3 => 'PASAPORTE', 4 => 'NIC', 5 => 'CEDULA'] as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= (string) old('tipo_documento', $usuario->tipo_documento ?? '') === (string) $id ? 'selected' : '' ?>>
                          <?= esc($nombre) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="documento" class="form-control input-sm" value="<?= esc(old('documento', $usuario->documento ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control input-sm" value="<?= esc(old('direccion', $usuario->direccion ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control input-sm" value="<?= esc(old('telefono', $usuario->telefono ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control input-sm" value="<?= esc(old('email', $usuario->email ?? '')) ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Login</label>
                    <input type="text" class="form-control input-sm" value="<?= esc($usuario->logi_usu ?? '') ?>" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="passwoord" class="form-control input-sm" placeholder="Dejar vacio para no cambiar">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
