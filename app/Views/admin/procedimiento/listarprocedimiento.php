
<div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-th-list" aria-hidden="true"></i>
      Procedimiento
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Módulo</a></li>
        <li><a href="#">Procedimiento</a></li>
        <li class="active">Listado</li>
      </ol>
    </section>
    <?php if (session()->getFlashdata('success')): ?>
        <script type="text/javascript">
           $(function(){
         Swal.fire(
  'Guardo Exitosamente!',
  'Procedimiento!',
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

                      <li><a href="<?= base_url('tarifario') ?>"><i class="fa fa-refresh"></i> Lista</a></li>

                      <li>
                        <?php if($permisos->insert ==1):?>
                        <a href="<?= base_url('tarifario/nuevo') ?>"><i class="fa fa-user-plus"></i> Nuevo</a>
                      <?php endif;?>
                      </li>
                     
                  </ol>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="ProcedimientoFormBusqueda" autocomplete="off">
                        <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Desde</label>
                    <input type="text" name="desde" class="form-control input-sm datepicker">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Hasta</label>
                    <input type="text" name="hasta" class="form-control input-sm datepicker">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por nombre :</label>
                    <input type="text" name="procedimiento" class="form-control input-sm">
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
                  <table id="TableMantenimientoProcedimiento" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr class="info">
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">N°</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Concepto</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Medida</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Categoría</th>
                       
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Descripción</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Precio</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">F.registro</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Estado</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Opciones</th>
                      
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
               
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



