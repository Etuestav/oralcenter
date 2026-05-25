
<div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Agregar
      
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
        <li><a href="#">Procedimiento</a></li>
        <li class="active">Cie10</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
                 
                   <ol class="breadcrumb">

                  

                      <li><a href="<?= base_url('diagnostico') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                     

                     
                  </ol>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->

          <div class="box-body pad">
        
              <form id="FormRegistrarDiagnostico" action="<?php echo base_url();?>diagnostico/guardar" method="POST" autocomplete="off">


            <div class="row">


                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="col-form-label">CIE10 *</label>
                      <input type="text" name="cie10" class="form-control input-sm" placeholder="CIE10">
                    </div>
                  </div>

                    
                  <div class="col-md-8">
                    <div class="form-group  <?php echo !empty(form_error('descripcion'))? 'has-error':'';?>">
                      <label class="control-label">Descripción*</label>
                      <input type="text" name="descripcion" class="form-control input-sm" placeholder="Nombre">
                    </div>
                  </div>

                          <div class="col-md-2">
                    <div class="form-group">
                     <label class="control-label">Estado *</label>
                       <select class="form-control input-sm" required="" name="estado" >
                                  <option value="S">Activado</option>
                                  <option value="N">Inactivo</option>
   
                                </select>
                    </div>


                                     
                  </div>
 <div class="col-md-12">
                                        <button type="submit" class="pull-right btn btn-md btn-info "><i class="fa fa-floppy-o"></i> Guardar</button>
                                    
                                     </div>  

                  
            </div>


                   

          </form>
          </div>
          </div>
          <!-- /.box -->

          </div>
   

      
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->

</div>

  </div>

