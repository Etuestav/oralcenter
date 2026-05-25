<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


<div id="HistoriaContenidoPlacas"
     class="panel panel-primary"
     style="display: none">

    <div class="panel-heading">
        Exámenes Auxiliares - Placas
    </div>

    <div class="panel-body">

        <div class="row">

            <div class="col-md-12">

                <div class="form-group">

                    <button id="ButtonAgregarPlaca"
                            class="btn btn-success btn-sm"
                            data-toggle="modal"
                            data-target="#ModalAgregarPlaca">

                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Agregar

                    </button>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <table id="TableHistoriaMovimientoPlacas"
                       class="table table-bordered">

                    <thead>

                        <tr class="btn-primary btn-xs">

                            <th style="text-align: center;">Fecha</th>
                            <th style="text-align: center;">Nombre</th>
                            <th style="text-align: center;">Notas</th>
                            <th style="text-align: center;">Archivo</th>
                            <th style="text-align: center;">Opciones</th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- MODAL AGREGAR -->
<div id="ModalAgregarPlaca"
     class="modal fade"
     role="dialog">

    <div class="modal-dialog modal-md"
         role="document">

        <div class="modal-content">

            <form id="FormHistoriaMovimientoAgregarPlaca"
                  action="<?= base_url('historia/agregarPlaca') ?>"
                  method="post"
                  enctype="multipart/form-data"
                  autocomplete="off">

                <?= csrf_field() ?>

                <input type="hidden"
                       name="paciente"
                       value="<?= esc($paciente->codi_pac ?? '') ?>">

                <input type="hidden"
                       name="archivo">

                <div class="modal-header">

                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                    <h4 class="modal-title">
                        Agregar Placa
                    </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <span class="btn btn-success fileinput-button">

                                    <i class="glyphicon glyphicon-plus"></i>

                                    <span>
                                        Buscar Archivo...
                                    </span>

                                    <input id="SubirPlaca"
                                           type="file"
                                           name="placaArchivo">

                                </span>

                                <br><br>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div id="progress"
                                             class="progress">

                                            <div class="progress-bar progress-bar-success"></div>

                                        </div>

                                    </div>

                                </div>

                                <div id="files"></div>

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label">
                                    Nombre de la Placa
                                </label>

                                <input type="text"
                                       name="nombre"
                                       class="form-control input-sm">

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label">
                                    Notas
                                </label>

                                <textarea name="notas"
                                          class="form-control input-sm"
                                          rows="5"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-danger pull-left"
                            data-dismiss="modal">

                        <i class="fa fa-close"></i>
                        Cancelar

                    </button>

                    <button type="submit"
                            class="btn btn-info">

                        <i class="fa fa-save"></i>
                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- MODAL EDITAR -->
<div id="ModalEditarPlaca"
     class="modal fade"
     role="dialog">

    <div class="modal-dialog modal-md"
         role="document">

        <div class="modal-content">

            <form id="FormHistoriaMovimientoEditarPlaca"
                  action="<?= base_url('historia/editarPlaca') ?>"
                  method="post"
                  enctype="multipart/form-data"
                  autocomplete="off">

                <?= csrf_field() ?>

                <input type="hidden" name="id">

                <input type="hidden" name="archivo">

                <div class="modal-header">

                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                    <h4 class="modal-title">
                        Editar Placa
                    </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <span class="btn btn-success fileinput-button">

                                    <i class="glyphicon glyphicon-plus"></i>

                                    <span>
                                        Buscar Archivo...
                                    </span>

                                    <input id="SubirPlacaEditar"
                                           type="file"
                                           name="placaArchivo">

                                </span>

                                <div class="row">

                                    <div class="col-md-12">

                                        <span class="text-danger">
                                            Subir archivo solo si va a cambiar la imagen.
                                        </span>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div id="progressEditar"
                                             class="progress">

                                            <div class="progress-bar progress-bar-success"></div>

                                        </div>

                                    </div>

                                </div>

                                <div id="filesEditar">

                                    <img src=""
                                         class="img img-responsive">

                                </div>

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label">
                                    Nombre de la Placa
                                </label>

                                <input type="text"
                                       name="nombre"
                                       class="form-control input-sm">

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label">
                                    Notas
                                </label>

                                <textarea name="notas"
                                          class="form-control input-sm"
                                          rows="5"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-danger pull-left"
                            data-dismiss="modal">

                        <i class="fa fa-close"></i>
                        Cancelar

                    </button>

                    <button type="submit"
                            class="btn btn-info">

                        <i class="fa fa-save"></i>
                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>