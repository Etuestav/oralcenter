<style>
  .checkbox-inline{
    margin-right: 20px;
  }
</style>
<div id="HistoriaContenidoOdontograma" class="panel panel-primary" style="display: none">
  <div class="panel-heading">
    <span class="glyphicon glyphicon-list"></span> Odontograma - <span id="TipoOdontogramaSpan">Inicial</span>
    <div class="pull-right action-buttons">
 
    </div>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
         <div id="imprimir" style="width: 100%; max-width: 800px"></div>
      </div>
      <div class="col-md-9" id="OdontogramaImprimir">
        <div id="odontograma">
          <div id="odontograma-contenido" class="detalle" data-marcaClass="">
            <img src="<?= base_url('vendor/odontograma/images/plantilla_nuevo11.png') ?>" class="img-responsive">
            <div id="cursoresRecuadros">
              
              <?= view('admin/historia/movimiento/odontograma/cursores.php') ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <?= view('admin/historia/movimiento/odontograma/odontograma_nav') ?>
      </div>
    </div>
  </div>
</div>

<div id="ModalOdontogramaDetalle" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Lista de Hallazgos</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Diente</th>
              <th>Diente Final</th>
              <th>Estado</th>
              <th>Dibujo</th>
              <th>Especificaciones</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="ModalAgregarHallazgo" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <form id="FormHistoriaMovimientoAgregarHallazgo" action="<?= base_url('historia/agregarHallazgo') ?>" method="post" autocomplete="off">
        <input type="hidden" name="hallazgo">
        <input type="hidden" name="estado">
        <input type="hidden" name="paciente" value="<?= $paciente->codi_pac ?>">
        <input type="hidden" name="marcas" value="0">
        <input type="hidden" name="tipoOdontograma" value="Inicial">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Agregar Hallazgo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Hallazgo</label>
                <input type="text" id="modalHallazgo" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-3" id="colCategoria">
              <div class="form-group">
                <label class="control-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">N° de Diente</label>
                <input type="text" name="diente" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-3" id="colDienteFinal" style="display: none">
              <div class="form-group">
                <label class="control-label">N° Diente Final</label>
                <input type="text" name="dienteFinal" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-3" id="colEstado">
              <div class="form-group">
                <label class="control-label">Estado</label>
                <input type="text" id="modalEstado" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-3" id="colSigla">
              <div class="form-group">
                <label class="control-label">Sigla</label>
                <input type="text" name="sigla" class="form-control" readonly>
              </div>
            </div>
          </div>
          <div id="FormMarcarPieza">
            <div class="row">
              <div class="col-md-12">
                <p><b>Seleccione las partes</b></p>
              </div>
            </div> 
            <div class="row">
              <div class="col-md-3">
                <div id="dibujar">
                  <div id="pieza">
                    <img src="<?= base_url('vendor/odontograma/images/pieza_tres.png') ?>">
                    <div class="direccionPieza" id="direccionPieza-arriba"></div>
                    <div class="direccionPieza" id="direccionPieza-abajo"></div>
                    <div class="direccionPieza" id="direccionPieza-izquierda"></div>
                    <div class="direccionPieza" id="direccionPieza-derecha"></div>
                    <div class="direccionPieza" id="direccionPieza-centro"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <div class="row">
                  <div class="col-md-12">
                    <label class="checkbox-inline">
                      <input id="CheckBoxVestibular" type="checkbox" name="Vestibular" value="Vestibular">
                      Vestibular
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="VestibularEstado" id="RadioVestibularBuenEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline" for="RadioVestibularMalEstado">
                      <input type="radio" name="VestibularEstado" id="RadioVestibularMalEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label class="checkbox-inline" id="ContentCheckBoxPalatino">
                      <input id="CheckBoxPalatino" type="checkbox" name="Palatino" value="Palatino">
                      Palatino
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="PalatinoEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="PalatinoEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label class="checkbox-inline" for="CheckBoxLingual" id="ContentCheckBoxLingual">
                      <input id="CheckBoxLingual" type="checkbox" name="Lingual" value="Lingual">
                      Lingual
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="LingualEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="LingualEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label class="checkbox-inline">
                      <input id="CheckBoxDistal" type="checkbox" name="Distal" value="Distal">
                      Distal
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="DistalEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="DistalEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label class="checkbox-inline">
                      <input id="CheckBoxMesial" type="checkbox" name="Mesial" value="Mesial">
                      Mesial
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="MesialEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="MesialEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label class="checkbox-inline" id="ContentCheckBoxOclusal">
                      <input id="CheckBoxOclusal" type="checkbox" name="Oclusal" value="Oclusal">
                      Oclusal
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="OclusalEstado" value="bueno"> Buen Estado
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="OclusalEstado" value="malo"> Mal Estado
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Especificaciones</label>
                <textarea name="especificaciones" class="form-control" rows="5"></textarea>
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




<div id="ModalCapturarOdontograma" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <form id="FormGuardarCapturaOdontograma" action="<?= base_url('historia/guardarImagenOdontograma') ?>" method="POST">
        <input type="hidden" name="paciente">
        <input type="hidden" name="tipo">
        <input type="hidden" name="imgData">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Odontograma Capturado</h4>
        </div>
        <div class="modal-body">
          <div id="ImagenOdontogramaCapturado">
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
