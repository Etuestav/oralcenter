


          content="IE=edge">

          content="width=device-width, initial-scale=1">




<div class="content-wrapper">

    <section class="content-header">
    </section>

    <section class="content">

        <div class="box-body">

            <div class="col-md-12">

                <div class="row">

                    <div class="col-md-10 col-md-offset-1">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <h3 style="text-align: center;">
                                    REGISTRAR OCURRENCIAS
                                </h3>

                            </div>

                            <div class="panel-body">

                                <form action="<?= base_url('mantenimiento/ocurrencias/addOcurrencias') ?>"
                                      method="POST"
                                      autocomplete="off"
                                      class="form-horizontal">

                                    <!-- Usuario -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label">
                                            Usuario :
                                        </label>

                                        <div class="col-md-6">

                                            <select name="id_usuario"
                                                    id="id_usuario"
                                                    class="form-control">

                                                <option value="">
                                                    --Seleccionar usuario--
                                                </option>

                                                <?php foreach ($user as $users): ?>

                                                    <option value="<?= $users->id_usuario ?>">
                                                        <?= $users->apellidos ?>
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                    <!-- Área -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label"
                                               for="area">

                                            Área :

                                        </label>

                                        <div class="col-md-6">

                                            <select name="id_area"
                                                    id="id_area"
                                                    class="form-control">

                                                <option value="">
                                                    --Seleccionar área--
                                                </option>

                                                <?php foreach ($area as $areas): ?>

                                                    <option value="<?= $areas->id_area ?>">
                                                        <?= $areas->nombre_area ?>
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                    <!-- Tipo problema -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label"
                                               for="problema">

                                            Tipo problema :

                                        </label>

                                        <div class="col-md-6">

                                            <select name="id_tipo_problema"
                                                    id="id_tipo_problema"
                                                    class="form-control">

                                                <option value="">
                                                    Seleccione un problema
                                                </option>

                                                <?php foreach ($problema as $problemas): ?>

                                                    <option value="<?= $problemas->id_tipo_problema ?>">
                                                        <?= $problemas->tipo_nombre ?>
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                    <!-- Tipo documento -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label"
                                               for="tipodocumento">

                                            Tipo documento :

                                        </label>

                                        <div class="col-md-6">

                                            <select name="id_tipo_documento"
                                                    id="id_tipo_documento"
                                                    class="form-control">

                                                <option value="">
                                                    Seleccione tipo documento
                                                </option>

                                                <?php foreach ($tipodocumento as $tipodocumentos): ?>

                                                    <option value="<?= $tipodocumentos->id_tipo_documento ?>">
                                                        <?= $tipodocumentos->nom_documento ?>
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                    <!-- Mensaje -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label">
                                            Mensaje :
                                        </label>

                                        <div class="col-md-6">

                                            <textarea id="mensaje"
                                                      name="mensaje"
                                                      class="form-control"
                                                      rows="5"
                                                      placeholder="Escriba su mensaje"
                                                      required></textarea>

                                        </div>

                                    </div>

                                    <!-- Fecha problema -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label">
                                            Fecha problema
                                        </label>

                                        <div class="col-md-6">

                                            <input type="date"
                                                   name="fecha_problema"
                                                   id="datetimepicker"
                                                   class="form-control"
                                                   required>

                                        </div>

                                    </div>

                                    <!-- Fecha finalizado -->
                                    <div class="form-group">

                                        <label class="col-md-4 control-label"
                                               style="visibility:hidden">

                                            Fecha finalizado

                                        </label>

                                        <div class="col-md-6">

                                            <input type="date"
                                                   name="fecha_finalizado"
                                                   id="datepicker"
                                                   class="form-control"
                                                   style="visibility:hidden">

                                        </div>

                                    </div>

                                    <!-- Botones -->
                                    <div class="col-md-6 col-md-offset-4">

                                        <button type="submit"
                                                class="btn btn-primary">

                                            Enviar

                                        </button>

                                        <a class="btn btn-default"
                                           href="<?= base_url('mantenimiento/ocurrencias') ?>">

                                            Cancelar

                                        </a>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>


