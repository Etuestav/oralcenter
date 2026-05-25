
    

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                Permisos
                <small>Editar</small>
                </h1>
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="box box-solid">
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?php echo base_url();?>mantenimiento/permisos/add" class="btn btn-success btn-flat"><span class="fa fa-plus"></span>
                                Agregar Permisos</a>
                            </div>
                        </div>

                         <!-- tablas -->
                         <hr>
                         <div class="row">
                             <div class="col-xs-12">
                               <div class="box-body table-responsive no-padding">
                                <table id="example1" class="table table-bordered btn-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 0.1%; text-align: center">N°</th>
                                            <th style="width: 0.5%; text-align: center">Menu</th>
                                            <th style="width: 0.4%; text-align: center">Rol</th>
                                            <th style="width: 0.1%; text-align: center">Leer</th>
                                             <th style="width: 0.1%; text-align: center">Insertar</th>
                                              <th style="width: 0.1%; text-align: center">Actualizar</th>
                                               <th style="width: 0.1%; text-align: center">Eliminar</th>
                                                 <th style="width: 0.3%; text-align: center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($permisos)):?>
                                            <?php foreach ($permisos as $permiso):?> 
                                        <tr>
                                            <td><?php echo $permiso->id_permiso;?></td>
                                            <td><?php echo $permiso->menu;?></td>
                                            <td><?php echo $permiso->roles;?></td>
                                            <td>
                                              <?php if($permiso->read == 0):?>
                                                <span class="fa fa-times"></span>
                                              <?php else:?>
                                                <span class="fa fa-check"></span>
                                              <?php endif;?>
                                            </td>
                                             <td>
                                              <?php if($permiso->insert == 0):?>
                                                <span class="fa fa-times"></span>
                                              <?php else:?>
                                                <span class="fa fa-check"></span>
                                              <?php endif;?>
                                            </td>
                                             <td style="text-align: center">
                                              <?php if($permiso->update == 0):?>
                                                <span class="fa fa-times"></span>
                                              <?php else:?>
                                                <span class="fa fa-check"></span>
                                              <?php endif;?>
                                            </td>
                                             <td>
                                              <?php if($permiso->delete == 0):?>
                                                <span class="fa fa-times"></span>
                                              <?php else:?>
                                                <span class="fa fa-check"></span>
                                              <?php endif;?>
                                            </td>
                                            <td>
                                                <div class="btn-footer text-center">
                                                    <button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo $permiso->id_permiso;?>">
                                                        <span class="fa fa-search"></span>
                                                    </button>
                                                    <a href="<?php echo base_url()?>mantenimiento/permisos/edit/<?php echo $permiso->id_permiso;?>" class="btn btn-warning"><span class="fa fa-pencil"></span></a>
                                                    <a href="<?php echo base_url()?>mantenimiento/permisos/delete/<?php echo $permiso->id_permiso;?>" class="btn btn-danger"><span class="fa fa-remove"></span></a>
                                                </div>
                                              </td>
                                        </tr> 
                                        <?php endforeach;?>      
                                     <?php endif;?>
                                    </tbody>

                                </table>
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


<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Información de la categoria</h4>
              </div>
              <div class="modal-body">
             
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
               
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->