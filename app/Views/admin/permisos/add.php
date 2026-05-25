
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                     <i class="fa fa-th-list" aria-hidden="true"></i>
                Permisos del Sistema
               
                </h1>
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="box box-solid">
                    <div class="box-body">
                  
                         <hr>
                         <div class="row">
                             <div class="col-md-12">
                                <?php if(session()->getFlashdata("error")):?>
                                <div class="alert alert-danger alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <p><i class="icon fa fa-ban"></i><?php echo session()->getFlashdata("error");?></p>  
                                </div>
                            <?php endif;?>
                                <form action="<?php echo base_url();?>administrador/permisos/store" method="POST">
                                     <div class="form-group">
                                        <label >Cargo:</label>
                                        <select name="rol"  class="form-control select2">
                                       <?php foreach ($roles as $rol): ?>
                      <option value="<?= $rol->codi_rol ?>"><?= $rol->nomb_rol ?></option>
                    <?php endforeach ?>
                                         
                                        </select>
                                    
                                    </DIV>
                                 
                                    <DIV class="form-group">
                                        <label for="menu">Módulo:</label>
                                        <select name="menu"  class="form-control select2">
                                          <?php foreach ($menus as  $menu):?> 
                                    <option value="<?php echo $menu->id_menu;?>">
                                    <?php echo $menu->nombre;?></option>
                                          <?php endforeach;?>
                                         
                                        </select>
                                    
                                    </DIV>
                                     <DIV class="form-group">
                                        <label for="read">Leer: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="read" value="1" checked="checked"> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="read" value="0" checked="checked"> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Insertar: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="insert" value="1" checked="checked"> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="insert" value="0" checked="checked"> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Update: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="update" value="1" checked="checked"> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="update" value="0" checked="checked"> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Anular: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="delete" value="1" checked="checked"> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="delete" value="0" checked="checked"> No
                                        </label>
                                    </DIV>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"> <span class="fa fa-save">          Guardar</button>
                                        
                                    </div>
                                
                                 </form>
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
