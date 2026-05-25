 <div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <i class="fa fa-th-list" aria-hidden="true"></i>
        Permisos del sistema
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
        <li><a href="#">Permisos</a></li>
      </ol>
    </section>
        <?php if (session()->getFlashdata('success')): ?>
        <script type="text/javascript">
           $(function(){
         Swal.fire(
  'Guardo Exitosamente!',
  'Su Médico!',
  'success'
)
      });
</script>
    <?php endif ?> 
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
                 
                   <ol class="breadcrumb">
                      <li><a href="<?= base_url('administrador/permisos') ?>"><i class="fa fa-refresh"></i> Lista</a></li>
                      <li><a href="<?= base_url('administrador/permisos/add') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                     
                  </ol>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="PermisosFormBusqueda" autocomplete="off">
                        <div class="row">
              
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por menu:</label>
                    <select  name="menus" class="form-control select2 input-sm">
                      <option value="">--Todos--</option>
                    <?php foreach ($menus as $m): ?>
                      <option value="<?= $m->id_menu ?>"><?= $m->nombre ?></option>
                    <?php endforeach ?>
                    </select>
                  </div>
                </div>
               
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por rol:</label>
                    <select  name="rol" class="form-control select2 input-sm">
                      <option value="">--Todos--</option>
                    <?php foreach ($rol as $r): ?>
                      <option value="<?= $r->codi_rol ?>"><?= $r->nomb_rol ?></option>
                    <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" style="margin-top:24px" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Buscar</button>
           
                </div>         
            
              </div>    
              </form>
            </div>
          </div>
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <div class="col-md-12">
       

                <div class="table-responsive">
                  <table id="TableMantenimientoPermisos" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">ID</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Módulo</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Cargo</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Leer</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Insertar</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Actualizar</th>
                        <th style="background-color: #3c8dbc; color: white;text-align: center;">Anular</th>
                        <th style="background-color: #3c8dbc; color: white;text-align: center;">Opciones</th>
                      </tr>
                    </thead>
               
                  </table>
                </div>
          
            </div>
            
        
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>

</div>
