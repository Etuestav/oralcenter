  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Médico</a></li>
        <li class="active">Editar</li>
      </ol>

  
    
    </section>

     
    <!-- Main content -->
    <section class="content">
       <form id="FormRegistrar" action="<?php echo base_url();?>medico/update" method="POST" autocomplete="OFF" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="usuario" value="<?= $medicos->codi_usu ?>">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?= base_url() ?>vendor/img/Odontologo.jpg" alt="User profile picture">



              <p class="text-muted text-center">Odontologo</p>

              <ul class="list-group list-group-unbordered">
              
                <li class="list-group-item">
                    <b>Fecha registro</b> 
                <div class="form-group">
                  <div class="input-group date">
                    <input type='text' name="fecha_registro" class="form-control datepicker" value="<?= date('Y-m-d') ?>" readonly="readonly" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  
                </div>
                  <!-- <b>Following</b> <a class="pull-right">543</a> -->
                </li>
               
              </ul>

              <a href="<?=base_url('medico')?>" class="btn btn-primary btn-block" ><i class="fa fa-hand-o-left"></i> Regresar</a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

   
          <!-- /.box -->
        </div>

           <!-- /.col -->
        <div class="col-md-9">
          
           <div class="box box-primary collapsed-box">
             <div class="box-header with-border">
                 <div class="nav-tabs-custom">
           <!-- /.tab-pane -->

            <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                   
                      <label class="col-form-label">Código *</label>
                      <input type="text" name="codigo" class="form-control input-sm" value="<?php echo $medicos->codi_med;?>" readonly="readonly" >
                    </div>
                  </div>
           
            </div>
             <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Nombres *</label>
                      <input type="text" name="nombre" class="form-control input-sm" value="<?= esc(old('nombre', $medicos->nomb_med)) ?>" onkeypress="return soloLetras(event)">
                  
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Apellidos *</label>
                      <input type="text" name="apellidos" class="form-control input-sm" value="<?= esc(old('apellidos', $medicos->apel_med)) ?>" onkeypress="return soloLetras(event)">
                    </div>
                  </div>
            </div>

            <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                     <label class="control-label">Especialidad *</label>
                    <select  name="especialidad" class="form-control input-sm">
                      <option value="">Seleccione</option>
                    <?php foreach ($especialidades as $e): ?>
                       <?php if($e->cod_especialidad == $medicos->cod_especialidad):?>
                         <option value="<?= $e->cod_especialidad ?>" selected><?php echo $e->nombre_especialidad;?></option>
                          <?php else:?>
                      <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
                      <?php endif;?>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Nro Doc: *</label>
                      <input type="text" name="dni"  class="form-control input-sm" value="<?= esc(old('dni', $medicos->dni_med)) ?>" maxlength="8" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Ruc</label>
                       <input type="text" name="ruc"  class="form-control input-sm" value="<?= esc(old('ruc', $medicos->ruc_med)) ?>" maxlength="11" minlength="11" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                    </div>
                  </div>
            </div>

             <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Dirección *</label>
                      <input type="text" name="direccion" class="form-control input-sm" value="<?= esc(old('direccion', $medicos->dire_med)) ?>">
                    </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                     <label class="control-label">Colegiatura *</label>
                       <input type="text" name="colegiatura"  class="form-control input-sm" value="<?= esc(old('colegiatura', $medicos->coleg_med)) ?>" maxlength="9" minlength="4" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;" >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Fecha nacimiento *</label>
                      <div class="input-group date">
                      
                      <input type="text"  name="fechanacimiento" class="form-control input-sm datepicker" value="<?= esc(old('fechanacimiento', $medicos->fena_med)) ?>">
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                     </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Teléfono</label>
                       <input type="text" name="telefono"  class="form-control input-sm" value="<?= esc(old('telefono', $medicos->telf_med)) ?>">
                    </div>
                  </div>
            </div>

                    <div class="row">

                      <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Celular</label>
                       <input type="text" name="celular"  class="form-control input-sm" value="<?= esc(old('celular', $medicos->cel_med)) ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                   <label class="col-form-label">Sexo :</label>
                     <div class="radio">
          <label><input type="radio" name="sexo" value="M" <?= old('sexo', $medicos->sexo_med) == 'M' ? 'checked' : '' ?> >Masculino</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label><input type="radio" name="sexo" value="F" <?= old('sexo', $medicos->sexo_med) == 'F' ? 'checked' : '' ?>  >Femenino</label>
      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                     <label class="control-label">Estado *</label>
                      <select class="form-control input-sm" name="estado" >
                                  <option value="S" <?= old('estado', $medicos->esta_med) == 'S' ? 'selected' : '' ?>>Activo</option>
                                  <option value="N" <?= old('estado', $medicos->esta_med) == 'N' ? 'selected' : '' ?>>Inactivo</option>
   
                                </select>
                    </div>
                  </div>
                 
            </div>
             
             <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="col-form-label">Correo *</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                      <input name="email"  class="form-control input-sm"  type="text" value="<?= esc(old('email', $medicos->emai_med)) ?>">
                    </div>
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Foto</label>
                  <input type="file" name="foto">
                  <br>
                  <img src="<?= base_url('vendor/uploads/usuarios/'.$usuario->foto) ?>" width="100">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Usuario</label>
                  <input type="text" name="usuarioMedico" class="form-control" value="<?= esc(old('usuarioMedico', $usuario->logi_usu)) ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Password</label>
                  <input type="password" name="passwordMedico" class="form-control" disabled>
                  <label for="cambiarPasswordMedico">
                    <input type="checkbox" id="cambiarPasswordMedico">
                    Cambiar Password
                  </label>
                </div>
              </div>
            </div>
            

               <div class="row">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                                  
                                     </div>  
            </div>
            

          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
          </div>
         
         </div>
      </div>
      <!-- /.row -->

        </form>
    </section>
    <!-- /.content -->    
  </div>

<script>
    $(function(){
      $('#cambiarPasswordMedico').click(function(event) {
  
        if ($(this).is(":checked")) {
          $('#FormRegistrar input[name=passwordMedico]').prop('disabled', false);
        }else{
          $('#FormRegistrar input[name=passwordMedico]').prop('disabled', true);
        }
      });
    })
    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }

        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>