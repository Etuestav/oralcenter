
    

  








 <div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <i class="fa fa-th-list" aria-hidden="true"></i>
        Rol
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
        <li><a href="#">Usuario</a></li>
        <li><a href="#">Rol</a></li>
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
                      <li><a href="<?= base_url('rol') ?>"><i class="fa fa-refresh"></i> Lista</a></li>
                      <li><a href="<?= base_url('rol/add') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                     
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
             
           
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por apellido:</label>
                    <input type="text" name="usuario" class="form-control input-sm">
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
                  <table id="TableMantenimientoRol" class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">ID</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Nombre</th>
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



<div id="ModalEditarRol" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                                                <form id="FormEditarRol" action="<?= base_url('rol/editRol') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                      <div class="modal-header">
                                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Editar Rol</h4>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        

                                                     
                                                                      <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Cargo:</label>
                                                                <input type="text" name="nombre" class="form-control input-sm">
                                                            </div>
                                                       </div>
                                                 
                                                       

                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                    <label  class="control-label">Estado</label>
                                                    <select class="form-control select input-sm" name="estado" >
                                                       <option value="1" <?php echo set_value('estado') == 1 ? "selected" : "" ?>>Habilitar</option>
                                  <option value="2" <?php echo set_value('estado') == 2 ? "selected" : "" ?>>Deshabilitar</option>
   
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

