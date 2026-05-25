
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
      <li><a href="#">configuracion</a></li>
      <li class="active">tipo documento</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
               
                 <ol class="breadcrumb">

                

                    <li><a href="<?= base_url('tipodocumento') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                   

                   
                </ol>
            <!-- tools box -->
    
            <!-- /. tools -->
        
          <!-- /.box-header -->

        <div class="box-body pad">

                  <form  id="FormRegistrarDocumento" action="<?php echo base_url();?>tipodocumento/update" method="POST" autocomplete="off">

            <div class="row">

            <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-form-label">Secuencia *</label>
                    <input type="text" name="codigo" class="form-control input-sm" value="<?php echo !empty(form_error('codigo')) ? set_value('codigo'):$documento->cod_tipodocumento?>">
                  </div>
            </div>
              <div class="col-md-4">
                  <div class="form-group">
                    <label class="col-form-label">Documento *</label>
                    <input type="text" name="nombre" class="form-control input-sm" value="<?php echo !empty(form_error('nombre')) ? set_value('nombre'):$documento->descripcion?>">
                  </div>
            </div>

            <div class="col-md-2">
                  <div class="form-group  <?php echo !empty(form_error('abreviatura'))? 'has-error':'';?>">
                    <label class="control-label">Siglas *</label>
                    <input type="text" name="abreviatura" class="form-control input-sm" value="<?php echo !empty(form_error('abreviatura')) ? set_value('abreviatura'):$documento->abreviatura?>" maxlength="2" minlength="2"  onkeypress="return soloLetras(event)" >
                  </div>
                </div>

                    
                <div class="col-md-2">
                  <div class="form-group  <?php echo !empty(form_error('serie'))? 'has-error':'';?>">
                    <label class="control-label">Serie *</label>
                    <input type="text" name="serie" class="form-control input-sm" value="<?php echo !empty(form_error('serie')) ? set_value('serie'):$documento->serie?>" maxlength="3" minlength="3" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                  </div>
                </div>
                
            </div>
          <div class="row">

                <div class="col-md-2">
                  <div class="form-group  <?php echo !empty(form_error('inicio'))? 'has-error':'';?>">
                  <label class="col-form-label">Inicio *</label>
                      <input type="number" name="inicio" class="form-control input-sm" value="<?php echo !empty(form_error('inicio')) ? set_value('inicio'):$documento->inicio?>">
                  </div>
                </div>

                

            
                <div class="col-md-2">
                  <div class="form-group  <?php echo !empty(form_error('fin'))? 'has-error':'';?>">
                  <label class="col-form-label">Fin *</label>
                      <input type="number" name="fin" class="form-control input-sm" value="<?php echo !empty(form_error('fin')) ? set_value('serie'):$documento->fin?>">
                  </div>
                </div>
                     <div class="col-md-2">
                  <div class="form-group  <?php echo !empty(form_error('correlativo'))? 'has-error':'';?>">
                  <label class="col-form-label">Correlativo actual *</label>
                      <input type="number" name="correlativo" class="form-control input-sm" value="<?php echo !empty(form_error('correlativo')) ? set_value('serie'):$documento->correlativo_actual?>">
                  </div>
                </div>


                  <div class="col-md-2">
                  <div class="form-group">
                   <label class="control-label">Estado</label>
                     <select class="form-control input-sm"  name="estado"  >
                               <option value="1" <?php echo set_value('estado',$documento->estado)==1 ? "selected" : "" ?>>Activo</option>
                                <option value="2" <?php echo set_value('estado',$documento->estado)==2 ? "selected" : "" ?>>Inactivo</option>
 
                              </select>
                  </div>


                                   
                </div>



                
                
          </div>

          <div class="col-md-12">
                                      <button type="submit" class="pull-right btn btn-md btn-info"><i class="fa fa-floppy-o"></i> Guardar</button>
                                  
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