<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <i class="fa fa-th-list" aria-hidden="true"></i>
        Comprobantes
      </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          
          <!-- /.box-header -->
          <div class="box-body">
         
            <form id="TratamientosFormComprobantesBúsqueda" target="_blank" action="<?= base_url('tratamiento/imprimirListadoTratamientos') ?>" autocomplete="off" method="off">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Desde:</label>
                    <div class="input-group date">
                    <input type="text" name="desde" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Hasta:</label>
                    <div class="input-group date">
                    <input type="text" name="hasta" class="form-control input-sm datepicker" value="<?= date('Y-m-d') ?>">
                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label">Búsqueda por Paciente:</label>
                    <input type="text" name="paciente" class="form-control input-sm">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Estado:</label>
                    <select name="estado" class="form-control input-sm">
                      <option value="Emitido">Emitido</option>
                      <option value="Por Cobrar">Por Cobrar</option>
                      <option value="Anulado">Anulado</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" style="margin-top:24px" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Buscar</button>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <a id="TratamientoImprimirListaComprobantes"  href="<?= base_url('tratamiento/imprimir') ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Imprimir Lista</a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="TableTratamientosComprobantes" data-igv="<?= $parametro->igv ?? 0 ?>" class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;"></th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Comprobante</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Fecha de Registro</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Fecha de Vencimiento</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Tipo</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Paciente</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Total</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Estado</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Tratamiento</th>
                        <th style="background-color: #3c8dbc; color: white; text-align: center;">Opciones</th>
                      </tr>
                    </thead>
      
                  </table>
                </div>
                <div id="TotalComprobante" class="alert alert-success weight" role="alert">
                  <strong>
                    El total es: <span></span>
                  </strong>
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

