
<div id="content" class="content-wrapper-2">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-th-list" aria-hidden="true"></i> Tipo Documento
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">Configuración</a></li>
      <li class="active">Tipo documento</li>
    </ol>
  </section>
  <?php if (session()->getFlashdata('success')): ?>
      <script type="text/javascript">
         $(function(){
       Swal.fire(
'Guardo Exitosamente!',
'Tipo documento!',
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
                    <li><a href="<?= base_url('tipodocumento') ?>"><i class="fa fa-refresh"></i> Actualizar</a></li>
                    <li><a href="<?= base_url('tipodocumento/nuevo') ?>"><i class="fa fa-user-plus"></i> Nuevo</a></li>
                   
                </ol>
            <!-- tools box -->
    
            <!-- /. tools -->
        
          <!-- /.box-header -->
          <div class="box-body pad">
            <form id="DocumentoFormBusqueda" autocomplete="off">
                      <div class="row">
          
            
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Búsqueda por documento :</label>
                  <input type="text" name="tipo_documento" class="form-control input-sm">
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
                <table id="TableMantenimientoDocumento" class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr class="info">
                   
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Descripción</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Abreviatura</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Serie</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Inicio</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Fin</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Actual</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Estado</th>
                      <th style="background-color: #3c8dbc; color: white; text-align: center;">Acciones</th>
                    
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

<div id="ModalEditarTipoDocumento" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <form id="FormEditarTipoDocumento" action="<?= base_url('tipodocumento/editTipoDocumento') ?>" method="post" autocomplete="off">
        <input type="hidden" name="id" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Editar Tipo Documento</h4>
        </div>
        <div class="modal-body">
          <div class="row">
     
        
      
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Documento:</label>
                    <input type="text" name="nombre" class="form-control input-sm">
                  </div>
                </div>

                   <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Abreviatura:</label>
                    <input type="text" name="abreviatura" class="form-control input-sm"></input>
                  </div>
                </div>

                 <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Serie:</label>
                    <input type="text" name="serie" class="form-control input-sm"></input>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Inicio:</label>
                    <input type="text" name="inicio" class="form-control input-sm"></input>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Fin:</label>
                    <input type="text" name="fin" class="form-control input-sm"></input>
                  </div>
                </div>

                 <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Correlativo:</label>
                    <input type="text" name="correlativo" class="form-control input-sm"></input>
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
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



