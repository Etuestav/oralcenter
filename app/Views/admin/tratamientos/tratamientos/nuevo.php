<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
    <h1><i class="fa fa-th-list" aria-hidden="true"></i> Tratamientos</h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">tratamiento</a></li>
      <li class="active">registrar</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
       <div class="col-md-12">
         <div class="box box-info">
           <form id="TratamientosFormNuevo" autocomplete="off" method="POST" action="<?= base_url('tratamiento/guardarTratamiento') ?>">
           <ol class="breadcrumb">
                      <li><a href="<?= base_url('tratamiento/nuevo') ?>"><i class="fa fa-refresh"></i> Actualizar</a></li>
                      <li><a href="<?= base_url('tratamiento/') ?>"><i class="fa fa-hand-o-left"></i> Regresar</a></li>
                     
                  </ol>
                <!-- /.box-header -->
          <div class="box-body pad">
           
              <div class="box box-default box-solid box-default ">
                <div class="box-body">
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
                      <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Médico</label>
                    <select name="medico" class="form-control input-sm">
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
              </div>

                  <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Paciente</label>
                    <select name="paciente" class="form-control select2 input-sm">
                      <option value="">Seleccione</option>
                      <?php foreach ($pacientes as $p): ?>
                      <option value="<?= $p->codi_pac ?>"><?= $p->apel_pac.' '.$p->nomb_pac ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Asunto</label>
                    <input type="text" name="asunto" class="form-control input-sm" placeholder="Asunto">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Observación</label>
                    <input type="text" name="observacion" class="form-control input-sm" placeholder="Observación">
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
          <!-- /.box-body -->
           <div class="box">
              <div class="box box-default box-solid box-default ">
                <div class="box-body">
            <div class="box-header">
                
              <div class="col-md-12">
               <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <button type="button" class="pull-left btn btn-md btn-success" id="TratamientoAgregarProcedimiento"><i class="fa fa-plus"></i> Agregar</button>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="box box-default box-solid box-default ">
                <div class="box-body">
                <div class="col-md-12">
                   <div class="table-responsive">
                  <table id="TableTratamientoProcedimiento" class="table table-bordered">
                    <thead>
                      <tr class="btn-primary">
                        <th style="text-align: center">Código</th>
                        <th style="text-align: center">Nombre</th>
                        <th style="text-align: center;" width="100">Cantidad</th>
                        <th style="text-align: center">Precio</th>
                        <th style="text-align: center;" width="100">Dsct%</th>
                        <th style="text-align: center">Total</th>
                       <th></th>
                      </tr>
                    </thead>
                    <tbody id="TratamientoBodyProcedimiento">
                    </tbody>
                    <tfoot class="hidden">
                      <tr>
                        <tr>
                          <th colspan="5" class="text-right">Total </th>
                          <th></th>
                          <th></th>
                        </tr>
                      </tr>
                    </tfoot>
                  </table>
                   <div class="table-responsive">
                </div>
                <div class="col-md-13">
                  <button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> Guardar</button>
                </div>
              </div>

          
            </div>
            
 </div>
</div>


          </div>
        </div>
      </div>
</div>
</div>

    </div>
      </form>
        </div>

          </div>
       </div>
   
  </section>
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
            <tr class="btn-primary">
              <th style="text-align: center;">Código</th>
              <th style="text-align: center;">Procedimiento</th>
              <th style="text-align: center;" width="100">Cant.</th>
              <th style="text-align: center;">Precio</th>
              <th style="text-align: center;" width="70"></th>
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