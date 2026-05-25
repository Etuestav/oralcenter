
    

  








 <div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user" aria-hidden="true"></i>
        Usuario
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
        <li><a href="#">Administrador</a></li>
        <li><a href="#">Usuario</a></li>
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
                      <li><a href="<?= base_url('usuario') ?>"><i class="fa fa-refresh"></i> Lista</a></li>
                      <li><a href="<?= base_url('usuario/add') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                     
                  </ol>
                      <?php if (session()->getFlashdata('success')): ?>
        <script type="text/javascript">
           $(function(){
         Swal.fire(
  'Guardo Exitosamente!',
  'Diagnóstico!',
  'success'
)
      });
</script>
    <?php endif ?>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="UsuariosFormBusqueda" autocomplete="off">
                        <div class="row">
                <div class="col-md-2">
                  
                    <label class="control-label">Desde:</label>
                    <div class="input-group date">
                    <input type="text" name="desde" class="form-control input-sm datepicker" >
                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
                 <div class="col-md-2">
                  
                    <label class="control-label">Hasta:</label>
                    <div class="input-group date">
                    <input type="text" name="hasta" class="form-control input-sm datepicker" >
                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por apellido:</label>
                    <input type="text" name="usuario" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Cargo:</label>
                    <select  name="rol" class="form-control input-sm">
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
                  <table id="TableMantenimientoUsu" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">ID</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Nombre</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Usuario</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Cargo</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Email</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">F. Registro</th>
                        <th style="background-color: #3c8dbc; color: white;text-align: center;">Estado</th>
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



<div id="ModalEditarUsuario" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                                                <form id="FormEditarUsuario" action="<?= base_url('usuario/editUsuario') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar usuario</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Apellidos:</label>
                                                                <input type="text" name="apellido" class="form-control input-sm">
                                                            </div>
                                                       </div>
                                                       <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombres:</label>
                                                                <input type="text" name="nombre" class="form-control input-sm">
                                                            </div>
                                                       </div>

                                                      <div class="col-md-2">
                                                            <div class="form-group">
                                                    <label  class="control-label">Tipo:</label>
                                                    <select class="form-control input-sm select" name="tipo_documento" >
                                                       <option value="1" <?php echo set_value('tipo_documento') == 1 ? "selected" : "" ?>>DNI</option>
                                                     <option value="2" <?php echo set_value('tipo_documento') == 2 ? "selected" : "" ?>>RUC</option>
                                                     <option value="3" <?php echo set_value('tipo_documento') == 3 ? "selected" : "" ?>>PASAPORTE</option>
                                                     <option value="4" <?php echo set_value('tipo_documento') == 4 ? "selected" : "" ?>>NIC</option>
                                                      <option value="5" <?php echo set_value('tipo_documento') == 5 ? "selected" : "" ?>>CEDULA</option>
   
                                                        </select>
                                                       </div>
                                                     </div> 
                                                <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Documento:</label>
                                                                <input type="text" name="documento" class="form-control input-sm">
                                                            </div>
                                                </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Dirección:</label>
                                                                <input type="text" name="direccion" class="form-control input-sm">
                                                            </div>
                                                       </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">Teléfono:</label>
                                                                <input type="text" name="telefono" class="form-control input-sm">
                                                            </div>
                                                       </div>

                                                       

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Email:<span class="text-danger">*</span></label>
                                                                <input type="email" name="email" class="form-control input-sm" >
                                                         
                                                            </div>
                                                       </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Cargo:</label>
                                                                  <select class="form-control select input-sm" name="codigorol" >
                                                    
                                                                 <?php foreach ($rol as $r): ?>
                                                                  <option value="<?= $r->codi_rol ?>"><?= $r->nomb_rol ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">Login:</label>
                                                                <input type="text" name="login" class="form-control input-sm">
                                                            </div>
                                                       </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label" for="cambiarPassword">Contraseña:</label>
                                                                <input  type="password" name="passwoord" class="form-control input-sm" disabled>
                                                                 <input type="checkbox" id="cambiarPassword">
                                                                    Cambiar Password
                                                            </div>

                                                             
                                                       </div>

                                                        <div class="col-md-3">
                                                          <div class="form-group">
                                                           <label class="control-label">Fecha:</label>
                                                              <input type="input" name="fecharegistro" class="form-control datepicker" disabled="true">
                                                           </div>
                                                       </div>
                                                       

                                                         <div class="col-md-3">
                                                            <div class="form-group">
                                                    <label  class="control-label">Estado</label>
                                                    <select class="form-control select input-sm" name="estado" >
                                                       <option value="1" <?php echo set_value('estado') == 1 ? "selected" : "" ?>>Activo</option>
                                  <option value="2" <?php echo set_value('estado') == 2 ? "selected" : "" ?>>Desactivado</option>
   
                                                   </select>
                                               </div>
                                                </div> 

                                         </div> 
                                              </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                                    </div>
                                          </form>
                                     </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->

