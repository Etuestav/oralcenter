<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
     <h1>
       <i class="fa fa-calendar"></i> Citas
      </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">

          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <form id="FormCitas" action="" autocomplete="off">
              <div class="row">
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="text" name="fecha" class="form-control hidden" data-lang="es" data-format="Y-m-d" data-inline="true" data-max-year="2030">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="control-label">Especialidad</label>
                            <select name="especialidad" class="form-control input-sm filtrarMedicos">
                              <option value="">Seleccione</option>
                              <?php foreach ($especialidad as $e): ?>
                              <option value="<?= $e->cod_especialidad ?>"><?= $e->nombre_especialidad ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="control-label">Médico</label>
                            <select name="medico" class="form-control input-sm">
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4" id="totales">
                  <label style="margin-top:50px;font-size: 14px" class="control-label">Total Ocupados: <span id="total-ocupados"></span></label> <br>
                  <label style="font-size: 14px" class="control-label">Total Disponibles: <span id="total-disponibles"></span></label>
                </div>
                
              </div>
            </form>
          
            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-info titulosBusqueda hidden" role="alert">
                  
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="HorariosCita" class="table table-bordered">
                    <thead>
                      <input type="hidden" name="busquedaEspecialidad">
                      <input type="hidden" name="busquedaMedico">
                      <input type="hidden" name="busquedaMedicoNombre">
                      <input type="hidden" name="busquedaFecha">
                      <tr class="btn-primary">
                        <th style="text-align: center">Hora</th>
                        <th style="text-align: center">Historia</th>
                        <th style="text-align: center">Paciente</th>
                        <th style="text-align: center">Secuencia</th>
                        <th style="text-align: center">Observación</th>
                        <th style="text-align: center">Estado</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
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



<div id="ModalAgendarCita" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormAgendarCita" action="<?= base_url('cita/guardar') ?>" method="post" autocomplete="off">
        <input type="hidden" name="hora">
        <input type="hidden" name="fecha">
        <input type="hidden" name="medico">
        <input type="hidden" name="especialidad">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">AGENDAR CITA</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label 
              ">Cita médica para: <span id="AgendarNombreMedico"></span></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Fecha: <span id="AgendarFecha"></span></label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Hora: <span id="AgendarHora"></span></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Paciente</label>
                <select id="paciente" name="paciente" class="form-control" style="width: 100%">
                  
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <a href="<?= base_url('paciente/nuevo') ?>" class="btn btn-success btn-md" target="_blank" style="margin-top: 20px"><i class="fa fa-plus"></i> Paciente Nuevo</a>
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
       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="ModalEditarCita" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormEditarCita" action="<?= base_url('cita/editar') ?>" method="post" autocomplete="off">
        <input type="hidden" name="id">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">EDITAR CITA</h4>
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
  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->