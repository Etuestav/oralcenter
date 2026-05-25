<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<div id="HistoriaContenidoCita" class="panel panel-primary" style="display: none">
 <div class="panel-heading"> <span class="fa fa-user-md"></span> Cita </div>          
   <div class="panel-body" >
                        

         <div class="row">
          <div class="col-md-12">
            <table id="TableHistoriaMovimientoCita" class="table table-bordered">
              <thead>
                <tr class="btn-primary btn-xs">
                 <th style="text-align: center;">N° Cita</th>
                  <th style="text-align: center;">Fecha cita</th>
                  <th style="text-align: center;">Especialidad</th>
                  <th style="text-align: center;">Médico</th>
                  <th style="text-align: center;">Tipo citado</th>
                  <th style="text-align: center;">Opciones</th>

                </tr>
              </thead>
     
            </table>
          </div>
        </div>
     </div>
</div>

<div id="ModalEditarCitaHistoria" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormEditarCitaHistoria" action="<?= base_url('historia/editarCitaHistoria') ?>" method="post" autocomplete="off">
        <input type="hidden" name="paciente" value="<?= esc($paciente->codi_pac ?? '') ?>">
        <input type="hidden" name="id">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">CITA RESERVADA</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Cita médica para: <span id="NombrePaciente"></span></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha</label>
                <input type="text" name="fecha" class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-6">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label class="control-label">Hora</label>
                  <input type="text" name="hora" class="form-control timepicker" id="modalHora">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="contro-label">Especialidad</label>
                <select name="especialidadEditar" class="form-control">
                  <option value="">Seleccione</option>
                  <?php foreach ($especialidad as $e): ?>
                  <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="contro-label">Médico</label>
                <select name="medicoEditar" class="form-control">
                  
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="contro-label">Sede</label>
                <select name="sede" class="form-control">
                  <option value=""></option>
                  <?php foreach ($sedes as $s): ?>
                  <option value="<?= $s->cod_sede ?>"><?= $s->nombre_sede ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="contro-label">Estado</label>
                <select name="codigo" class="form-control">
                  <option value=""></option>
                  <?php foreach ($tipo_citado as $t): ?>
                  <option value="<?= $t->cod_citado ?>"><?= $t->nomb_citado ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Motivo Consulta</label>
                <input type="text" name="motivo" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Observación</label>
                <textarea name="observacion" class="form-control" rows="3"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="checkbox">
                  <label><input type="checkbox" name="notificar">Notificar correo del paciente</label>
                </div>
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
