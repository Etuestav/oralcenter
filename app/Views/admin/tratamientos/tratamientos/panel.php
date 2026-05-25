
<div class="content-wrapper">

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header box-form-header">
            <h3 class="box-title">Listado de Tratamientos</h3>


          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="box box-default box-solid box-default ">
                <div class="box-body">
            <form id="TratamientosFormBusqueda" target="_blank" action="<?= base_url('tratamiento/imprimirListadoTratamientos') ?>" autocomplete="off" method="off">
              <div class="row">
                <div class="col-md-2">
                   
                  <div class="form-group">
                    <label class="control-label">Desde</label>
                    <div class="input-group date">
                    <input  name="desde" class="form-control input-sm datepicker text-box single-line" value="<?= date('Y-m-d') ?>">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Hasta</label>
                     <div class="input-group date">
                    <input  name="hasta" class="form-control input-sm datepicker text-box single-line" value="<?= date('Y-m-d') ?>">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Estado</label>
                    <select name="estado" class="form-control input-sm">
                      <option value="Activo">Activo</option>
                      <option value="Anulado">Anulado</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por Paciente</label>
                    <input type="text" name="paciente" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" style="margin-top:24px" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>

                </div>

            </form>
          </div>
        </div>
      </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <a target="_blank" id="TratamientosImprimirLista" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Imprimir Lista</a>
                  <a href="<?= base_url('tratamiento/nuevo') ?>" class="btn btn-success btn-sm right"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Tratamiento</a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="TableTratamientosPanel" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr class="btn-primary btn-xs">
                        <th></th>
                        <th style="text-align: center">Secuencia</th>
                        <th style="text-align: center">Paciente</th>
                        <th style="text-align: center">Asunto</th>
                        <th style="text-align: center">Fecha</th>
                        <th style="text-align: center">Total</th>
                        <th style="text-align: center">Pago</th>
                        <th style="text-align: center">Estado</th>
                        <th style="text-align: center">Opciones</th>
                      </tr>
                    </thead>
                
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

