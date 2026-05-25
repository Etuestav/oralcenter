 <div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <h1 >
       <i class="fa fa-user-md" ></i> Médicos
      </h1>    

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
        <li><a href="#">Médicos</a></li>
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
                      <li><a href="<?= base_url('medico') ?>"><i class="fa fa-refresh"></i> Lista</a></li>
                      <li><a href="<?= base_url('medico/nuevo') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                     
                  </ol>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="MedicosFormBusqueda" autocomplete="off">
                        <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Desde</label>
                     <div class="input-group date">
                       <input type="text" name="desde" class="form-control input-sm datepicker" >
                       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Hasta</label>
                     <div class="input-group date">
                       <input type="text" name="hasta" class="form-control input-sm datepicker" >
                       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por médico</label>
                    <input type="text" name="medico" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Especialidad</label>
                    <select  name="especialidad" class="form-control input-sm">
                      <option value="">--Todos--</option>
                    <?php foreach ($especialidades as $e): ?>
                      <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
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
                  <table id="TableMantenimientoMedico" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">N°</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Médico</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Especialidad</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">DNI</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Colegiatura</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">F. registro</th>
                        <th style="background-color: #3c8dbc; color: white;text-align: center;">Estado</th>
                        <th style="background-color: #3c8dbc; color: white;text-align: center;">Opciones</th>
                        <th></th>
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