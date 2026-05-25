  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Paciente
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Paciente</a></li>
        <li class="active">Registrar</li>
      </ol>

  
    
    </section>

     
    <!-- Main content -->
    <section class="content">
       <form id='FormRegistrarPaciente' action="<?= base_url('paciente/guardar')?>" method="POST" autocomplete="OFF" enctype="multipart/form-data">
 
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?= base_url() ?>vendor/img/usuario_inicio.png" alt="User profile picture">



              <p class="text-muted text-center">Paciente</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
            
                <div class="form-group">
                  <div class="input-group">
              
                  
                                <input name="foto_paciente" type="file">
                                <span class="help-block"></span>
                           
                  </div>
                  
                </div>
                  <!-- <b>Following</b> <a class="pull-right">543</a> -->
                </li>


                <li class="list-group-item">
                    <b>Fecha registro:</b> 
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

              <a href="<?=base_url('paciente')?>" class="btn btn-primary btn-block" ><i class="fa fa-hand-o-left"></i> Regresar</a>
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Nombres: *</label>
                      <input type="text" name="nombre" class="form-control input-sm" placeholder="Nombres" onkeypress="return soloLetras(event)">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Apellidos: *</label>
                      <input type="text" name="apellidos" class="form-control input-sm" placeholder="Apellidos" onkeypress="return soloLetras(event)">
                    </div>
                  </div>
            </div>


                    <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="col-form-label">Edad: *</label>
                      <input type="number" name="edad" class="form-control input-sm" maxlength="3" minlength="1" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Grado Instruccion: *</label>
                       
                      <select class="form-control input-sm"  name="ocupacion" >
                                   <option value="">--Seleccionar--</option>
                                  <option value="S">Secundaria Completa</option>
                                  <option value="U">Universitaria Superior</option>
                                  <option value="P">Primaria Completa</option>
                                  <option value="N">No Especifica</option>
                                </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Hospital de nacimiento: *</label>
                      <input type="text" name="lugarnacimiento" class="form-control input-sm" placeholder="Lugar nacimiento">
                    </div>
                  </div>
            </div>
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                     <label class="col-form-label">País: *</label>
                    <select  name="pais" class="form-control select input-sm">
                      <option value="">--Seleccionar--</option>
                    <?php foreach ($pais as $pa): ?>
                      <option value="<?= $pa->id ?>"><?= $pa->nombre ?></option>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                     <label class="col-form-label">Departamento: *</label>
                    <select  name="departamento" class="form-control input-sm">
                      <option value="">--Seleccionar--</option>
                    <?php foreach ($departamentos as $d): ?>
                      <option value="<?= $d->departamento_id ?>"><?= $d->departamento_nombre ?></option>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>

                      <div class="col-md-3">
                    <div class="form-group">
                 
                    <label class="col-form-label">Provincia:</label>
                      <select name="provincia" class="form-control input-sm" disabled>
                          <option value="">--Seleccionar--</option>
                      </select>
                    </div>
                  </div>

                     <div class="col-md-3">
                    <div class="form-group">
                    
                        <label class="col-form-label">Distrito:</label>
                      <select name="distrito" class="form-control input-sm" disabled>
                          <option value="">--Seleccionar--</option>
                      </select>
                    </div>
                  </div>


            </div>

             <div class="row">


                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Dirección: *</label>
                      <input type="text" name="direccion"  class="form-control input-sm" placeholder="Dirección">
                    </div>
                  </div>
</div>
                   <div class="row">
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Documento: *</label>
                      <input type="text" name="dni" class="form-control input-sm" placeholder="Documento" maxlength="8" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                    </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                     <label class="control-label">Teléfono: *</label>
                       <input type="text" name="telefono"  class="form-control input-sm" placeholder="Teléfono" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Fecha nacimiento: *</label>
                      <div class="input-group date">
                      
                      <input type="text"  name="fechanacimiento" class="form-control input-sm datepicker" >
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                     </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Estado civil:</label>
                        <select class="form-control input-sm" required="" name="estadocivil" >
                                  <option value="C">Casado(a)</option>
                                  <option value="S">Soltero(a)</option>
                                  <option value="V">Viudo(a)</option>
                                  <option value="D">Divorciado(a)</option>
                                </select>
                    </div>
                  </div>
            </div>

                    <div class="row">

                      <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Afilidado:</label>
                       <input type="text" name="afiliado"  class="form-control input-sm" placeholder="Afiliado">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                   <label class="col-form-label">Sexo:</label>
                     <div class="radio">
          <label><input type="radio" name="sexo" value="M" checked="true" >Masculino</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <label><input type="radio" name="sexo" value="F"  >Femenino</label>
      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                     <label class="control-label">Estado *</label>
                      <select class="form-control input-sm"  name="estado" >
                                  <option value="S">Activado</option>
                                  <option value="N">Inactivo</option>
   
                                </select>
                    </div>
                  </div>
                 
            </div>
             
             <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Alergia:</label>
                        <input type="text" name="alergia"  class="form-control input-sm" placeholder="Alergia">
                    </div>
                  </div>
                         <div class="col-md-8">
                    <div class="form-group">
                      <label class="col-form-label">Correo: *</label>
                      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
  <input name="email" placeholder="email" class="form-control input-sm"  type="email">
    </div>
                    </div>
                  </div>
              </div>

               <div class="row">
                     <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Observación:</label>
                        <textarea class="form-control input-sm " name="observacion" cols="20" style="height:auto!important;"></textarea>  
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