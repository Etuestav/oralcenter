
<div id="content" class="content-wrapper-2">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-th-list" aria-hidden="true"></i> Actualizar
    
    </h1>
    <ol class="breadcrumb">
     <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">catalogo</a></li>
      <li class="active">alergia</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
               
                 <ol class="breadcrumb">

                

                    <li><a href="<?= base_url('alergia') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                   

                   
                </ol>
            <!-- tools box -->
    
            <!-- /. tools -->
        
          <!-- /.box-header -->

        <div class="box-body pad">

                  <form  id="FormRegistrarBanco" action="<?php echo base_url('');?>alergia/update" method="POST" autocomplete="off">

         
   
          <div class="row">

                        <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Secuencia</label>
                        <input type="text" class="form-control input-sm" name="codigo" value="<?php echo $alergia->cod_ale;?>" readonly="readonly">
                  </div>
                </div>
                

            
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Descripción</label>
                    <input type="text" name="nombre" class="form-control input-sm" value="<?= old('nombre', $alergia->nombre_ale) ?>">
                  </div>
                </div>


                  <div class="col-md-12">
                                      <button type="submit" class="pull-right btn btn-md btn-info"><i class="fa fa-floppy-o"></i> Guardar</button>
                                  
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

