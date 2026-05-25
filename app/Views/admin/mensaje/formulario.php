<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Mensaje</h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">

          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <form id="FormMensaje" action="<?= base_url('administrador/mensaje/guardar') ?>" method="post">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="label-control">Título</label>
                    <input type="text" name="titulo" class="form-control" value="<?= $mensaje->titulo ?>">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Contenido</label>
                    <textarea name="contenido" id="textareaContenido" class="form-control" rows="10"><?= $mensaje->contenido ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Activo</label>
                    <select name="activo" class="form-control">
                      <option value="s">Si</option>
                      <option value="n">No</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md">Guardar</button>
                  </div>
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



