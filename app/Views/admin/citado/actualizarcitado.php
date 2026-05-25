
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
      <li class="active">citado</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
               
                 <ol class="breadcrumb">

                

                    <li><a href="<?= base_url('citado') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                   

                   
                </ol>
            <!-- tools box -->
    
            <!-- /. tools -->
        
          <!-- /.box-header -->

        <div class="box-body pad">

                  <form  id="FormRegistrarCitado" action="<?php echo base_url('');?>citado/update" method="POST" autocomplete="off">

         
   
          <div class="row">

                        <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Secuencia :</label>
                        <input type="text" class="form-control input-sm" name="codigo" value="<?php echo $citado->cod_citado;?>" readonly="readonly">
                  </div>
                </div>
                

            
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Nombre :</label>
                    <input type="text" name="nombre" class="form-control input-sm" value="<?php echo !empty(form_error('nombre'))?
                    set_value('nombre'):$citado->nomb_citado?>">
                  </div>
                </div>

           


                  <div class="col-md-2">
                  <div class="form-group">
                   <label class="control-label">Estado :</label>
                     <select class="form-control input-sm"  name="estado"  >
                               <option value="S" <?php echo set_value('estado',$citado->esta_citado)==S ? "selected" : "" ?>>Activo</option>
                                <option value="N" <?php echo set_value('estado',$citado->esta_citado)==N ? "selected" : "" ?>>Inactivo</option>
 
                              </select>
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

