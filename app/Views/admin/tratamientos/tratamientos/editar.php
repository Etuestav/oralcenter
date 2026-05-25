<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>Tratamientos</h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">tratamiento</a></li>
      <li class="active">editar</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
           <form id="TratamientosFormNuevo" autocomplete="off" method="POST" action="<?= base_url('tratamiento/editarGuardarTratamiento') ?>">
             <ol class="breadcrumb">
                     
                      <li><a href="<?= base_url('tratamiento/') ?>"><i class="fa fa-hand-o-left"></i> Regresar</a></li>
                     
                  </ol>
          <!-- /.box-header -->
          <div class="box-body">

   
           <div class="box box-default box-solid box-default ">
                <div class="box-body">
              <input type="hidden" name="id_tratamiento" value="<?= $tratamiento->codi_tra ?>">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Fecha</label>
                    <input type="text" class="form-control input-sm" value="<?= date('Y-m-d') ?>" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Especialidad</label>
                    <select name="especialidad" class="form-control input-sm filtrarMedicos">
                      <option value="">Seleccione</option>
                      <?php foreach ($especialidades as $e): ?>
                      <option 
                        value="<?= $e->cod_especialidad ?>"
                        <?= ($e->cod_especialidad==$tratamiento->cod_especialidad)?'selected':'' ?>
                      >
                        <?= $e->nombre_especialidad ?>
                      </option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Médico</label>
                    <select name="medico" class="form-control input-sm">
                      <option value="">Seleccione</option>
                      <?php foreach ($medicos as $m): ?>
                      <option 
                        value="<?= $m->codi_med ?>" 
                        <?= ($tratamiento->codi_med==$m->codi_med)?'selected':'' ?>
                      >
                        <?= $m->nomb_med.' '.$m->apel_med ?>
                      
                      </option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Paciente</label>
                    <select name="paciente" class="form-control input-sm">
                      <option value="">Seleccione</option>
                      <?php foreach ($pacientes as $p): ?>
                      <option 
                        value="<?= $p->codi_pac ?>"
                        <?= ($p->codi_pac==$tratamiento->codi_pac)?'selected':'' ?>
                      >
                        <?= $p->apel_pac.' '.$p->nomb_pac ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Asunto</label>
                    <input type="text" name="asunto" class="form-control input-sm" placeholder="Asunto" value="<?= $tratamiento->asunto_tra ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Observación</label>
                    <input type="text" name="observacion" class="form-control input-sm" placeholder="Observación" value="<?= $tratamiento->observacion_tra ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
              <hr>
   
              <div class="row">
                
                <div class="table-responsive">
                <div class="col-md-12">
                  <div class="form-group">
                    <button type="button" class="pull-left btn btn-md btn-success" id="TratamientoAgregarProcedimiento"><i class="fa fa-plus"></i> Agregar</button>
                  </div>
                </div>
                <hr>
                <div class="col-md-12">
                   <div class="box box-default box-solid box-default ">
                <div class="box-body">
                  <table id="TableTratamientoProcedimiento" class="table table-bordered">
                    <thead>
                      <tr class="btn-primary">
                        <th>Código</th>
                        <th>Nombre</th>
                        <th width="100">Cantidad</th>
                        <th>Precio</th>
                        <th width="100">Dsct%</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="TratamientoBodyProcedimiento">
                      <?php foreach ($tratamiento->detalle as $t): ?>
                        
                      <tr id="proc-<?= $t->id_procedimiento ?>" data-id="<?= $t->id_procedimiento ?>">
                        <input class="valores" type="hidden" name="id[]" value="<?= $t->id_procedimiento ?>">
                        <td><?= $t->id_procedimiento ?></td>
                        <td><?= $t->nombre ?></td>
                        <td><input type="number" name="cant[]" class="form-control" value="<?= $t->cant_tradet ?>" min="1" step="1"></td>
                        <td><?= $t->preciounit_tradet ?></td>
                        <td><input type="text" name="desc[]" class="form-control porcentaje" value="<?= $t->descuento_tradet ?>"></td>
                        <td><?= $t->subtotal_tradet ?></td>
                        <td><button type="button" data-id="<?= $t->id_procedimiento ?>" class="removeProcedimiento btn btn-ico btn-xs btn-danger"><i class="fa fa-close"></i></button></td>
                      </tr>
                      <?php endforeach ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <tr>
                          <th colspan="5" class="text-right">Total </th>
                          <th><?= $tratamiento->total_tra ?></th>
                          <th></th>
                        </tr>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Guardar</button>
                </div>


              </div>
          </div>
          </div>
            </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>


<div id="ModalTratamientoProcedimientos" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">PROCEDIMIENTOS</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
        <table id="TableTratamientoProcedimientosModal" class="table table-bordered">
          <thead>
            <tr>
              <th>Código</th>
              <th>Procedimiento</th>
              <th width="100">Cant.</th>
              <th>Precio</th>
              <th width="70"></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Código</th>
              <th>Procedimiento</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
          <tbody>
          <?php foreach ($procedimientos as $p): ?>
          <tr>
            <td><?= $p->id_procedimiento ?></td>
            <td><?= $p->nombre ?></td>
            <td><input id="proc-cant-<?= $p->id_procedimiento ?>" type="number" name="cant" class="form-control" value="1"></td>
            <td><?= $p->prec_procedimiento ?></td>
            <td><button data-id="<?= $p->id_procedimiento ?>" class="addProcedimiento btn btn-ico btn-info"><i class="fa fa-plus"></i></button></td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

