<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
     <h1 >
       <i class="fa fa-male" ></i> Pacientes
      </h1>    

    <ol class="breadcrumb">
      <li><a><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a>Pacientes</a></li>
      <li class="active">Listado</li>
    </ol>
  </section>
    <?php if (session()->getFlashdata('success')): ?>
      <script type="text/javascript">
         $(function(){
       Swal.fire(
'Guardo Exitosamente!',
'Paciente!',
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
                      <li><a href="<?= base_url('paciente') ?>"><i class="fa fa-refresh"></i> Lista</a></li>
                      <li><a href="<?= base_url('paciente/nuevo') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                     
                  </ol>
          <!-- /.box-header -->
          <div class="box-body pad">
            <form id="PacienteFormBusqueda" autocomplete="off">
                        <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Desde:</label>
                      <div class="input-group date">
                       <input type="text" name="desde" class="form-control input-sm datepicker" >
                       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Hasta:</label>
                     <div class="input-group date">
                       <input type="text" name="hasta" class="form-control input-sm datepicker" >
                       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                     </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por paciente:</label>
                    <input type="text" name="paciente" class="form-control input-sm">
                  </div>
                </div>

             
           
                <div class="col-md-2">
                  <button type="submit" style="margin-top:24px" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Buscar</button>
           
                </div>         
            
              </div>    
              </form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

               <div class="box">
            <div class="box-header">
              <div class="col-md-12">
       

                <div class="table-responsive">
                  <table id="TableMantenimientoPaciente" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">N°</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Paciente</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Edad</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">DNI</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Dirección</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">F.registro</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Estado</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Opciones</th>
                      
                      </tr>
                    </thead>
               
                  </table>
                </div>
          
            </div>
            
        
          </div>
        </div>

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
