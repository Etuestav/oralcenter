<div id="content" class="content-wrapper-2">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
                  

             <section class="content-header">
    <h1>
       <i class="fa fa-th-list" aria-hidden="true"></i> Agregar
    
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">Usuario</a></li>
      <li class="active">Rol</li>
    </ol>
  </section>
            <section class="content">


                <!-- Default box -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="box box-info">
                             <ol class="breadcrumb">
                              <li><a href="<?= base_url('banco') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                              </ol>
                       <div class="box-body">
             
                        
                         <div class="row">
                             <div class="col-md-12">

                                <?php if(session()->getFlashdata("error")):?>
                                <div class="alert alert-danger alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <p><i class="icon fa fa-ban"></i><?php echo session()->getFlashdata("error");?></p>  
                                </div>
                            <?php endif;?>
                                <form action="<?php echo base_url();?>rol/rol_add" method="POST">
                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <label>Los campos con (*) son olbigatorios</label>
                </div>
          
                <div class="col-lg-8 left">
                    
                                     <div class="form-group has-success">
                                        <label for="nomb_rol">Nombre (*):</label>
                                        <input type="text" maxlength="50" class="form-control"  name="nomb_rol" required="" placeholder="nombre">
                                    </div>
                                   
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                                       <a href="<?php echo base_url();?>rol/" class="btn btn-primary" ><i class="fa fa-remove"></i> Cancelar</a>
                                    </div>
                                    
                </div>
                                 </form>
                             </div>
                         </div>
                    </div>
                   
               
                      </div>
                </div>
          
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

</div>
