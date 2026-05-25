<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<div id="HistoriaContenidoEvolucion" class="panel panel-primary" style="display: none">
 <div class="panel-heading"> <span class="fa fa-user-md"></span> Evolución </div>          
   <div class="panel-body" >
                           <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ModalAgregarEvolucion"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
                                     </div>
                                </div>
                            </div>

         <div class="row">
          <div class="col-md-12">
            <table id="TableHistoriaMovimientoEvolucion" class="table table-bordered">
              <thead>
                <tr class="btn-primary btn-xs">
                 <th style="text-align: center;">Fecha</th>
                  <th style="text-align: center;">Descripción evolución</th>
                  <th style="text-align: center;">Médico evoluciona</th>
                  <th style="text-align: center;">Especialidad</th>
                
                  <th style="text-align: center;">Opciones</th>
                </tr>
              </thead>
     
            </table>
          </div>
        </div>
     </div>
       
</div>

<div id="ModalAgregarEvolucion" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormHistoriaMovimientoAgregarEvolucion" action="<?= base_url('historia/agregarEvolucion') ?>" method="post" autocomplete="off">
        <input type="hidden" name="paciente" value="<?= esc($paciente->codi_pac ?? '') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Agregar Evolución</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Especilidad:</label>
                <select name="especialidad" class="form-control input-sm filtrarMedicos" style="width: 100%">
                  <option value="">--Selecciona--</option>
                  <?php foreach ($especialidad as $esp): ?>
                  <option value="<?= $esp->cod_especialidad ?>"><?= $esp->nombre_especialidad ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

             <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Médico:</label>
                <select name="medico" class="form-control input-sm" style="width: 100%">
                  <option value="">--Selecciona--</option>
                  <?php foreach ($medicos as $med): ?>
                  <option value="<?= $med->codi_med ?>"><?= $med->apel_med ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
             <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Fecha</label>

                    <input type="text" name="fecha" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>">
                  </div>
                </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Descripción evolución:</label>
                <textarea name="evolucion" class="form-control input-sm" rows="3"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="ModalEditarEvolucion" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormHistoriaMovimientoEditarEvolucion" action="<?= base_url('historia/editarEvolucion') ?>" method="post" autocomplete="off">
        <input type="hidden" name="paciente" value="<?= esc($paciente->codi_pac ?? '') ?>">
        <input type="hidden" name="id">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Editar Evolución</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Especilidad:</label>
                <select name="especialidad" class="form-control input-sm filtrarMedicos" style="width: 100%">
                
                  <?php foreach ($especialidad as $e): ?>
                  <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

             <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Médico:</label>
                <select name="medico" class="form-control input-sm" style="width: 100%">
                  
                </select>
              </div>
            </div>
             <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Fecha</label>

                    <input type="text" name="fecha" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>">
                  </div>
                </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Descripción evolución:</label>
                <textarea name="evolucion" class="form-control input-sm" rows="3"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


