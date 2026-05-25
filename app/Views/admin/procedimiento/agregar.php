
<div id="content" class="content-wrapper-2">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Procedimiento
      
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">Editors</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
                 
                   <ol class="breadcrumb">

                  

                      <li><a href="<?= base_url('tarifario') ?>"><i class="fa fa-hand-o-left"></i> Volver</a></li>
                     

                     
                  </ol>
              <!-- tools box -->
      
              <!-- /. tools -->
          
            <!-- /.box-header -->

          <div class="box-body pad">
        
              <form id="FormRegistrarTarifario" action="<?php echo base_url();?>tarifario/guardar" method="POST" autocomplete="off">


            <div class="row">


                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-form-label">Procedimiento *</label>
                      <input type="text" name="nombre" class="form-control input-sm" placeholder="Nombres">
                    </div>
                  </div>

                       <div class="col-md-4">
                    <div class="form-group  <?php echo !empty(form_error('categoria'))? 'has-error':'';?>">
                     <label class="control-label">Categoría *</label>
                    <select  name="categoria" class="form-control input-sm">
                      <option value="">Seleccione</option>
                    <?php foreach ($categorias as $ca): ?>
                      <option value="<?= $ca->codi_cat ?>"><?= $ca->nomb_cat ?></option>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group  <?php echo !empty(form_error('precio'))? 'has-error':'';?>">
                      <label class="control-label">Precio *</label>
                      <input type="text" name="precio" class="form-control input-sm" placeholder="Precio">
                    </div>
                  </div>
                  
            </div>

                       <div class="row">
                 

                       <div class="col-md-3">
                    <div class="form-group <?php echo !empty(form_error('medida'))? 'has-error':'';?>">
                     <label class="control-label">Medida *</label>
                    <select  name="medida" class="form-control input-sm">
                      <option value="">Seleccione</option>
                    <?php foreach ($unidad_medidas as $m): ?>
                      <option value="<?= $m->id_medida ?>"><?= $m->nom_medida ?></option>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group <?php echo !empty(form_error('concepto'))? 'has-error':'';?>">
                     <label class="control-label">Tipo concepto *</label>
                    <select  name="concepto" class="form-control input-sm">
                      <option value="">Seleccione</option>
                    <?php foreach ($tipo_conceptos as $t): ?>
                      <option value="<?= $t->id_tipoconcepto ?>"><?= $t->nombre_concepto ?></option>
                    <?php endforeach ?>
                    </select>
                    </div>
                  </div>
                   <div class="col-md-3">
                <div class="form-group">
                    <label class="col-form-label">Fecha registro</label>
                  <div class="input-group date">
                    <input type='text' name="fecha_registro" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>" disabled />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  
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

                    <div class="col-md-11">
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

