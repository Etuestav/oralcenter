
<div class="row" style="margin-top: 2%">
    <div class="col-md-8 col-md-offset-2">
        <div id="panel-cie" class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Registro de usuarios</h3>
            </div>
            <div class="panel-body">
                <?php if (session()->get('mensaje_usu') && session()->get('mensaje_usu') != "") { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?= session()->get('mensaje_usu') ?>
                    </div>
                    <?php
                    session()->remove('mensaje_usu');
                }
                ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalNuevoUsuario">Nuevo usuario</button>
                <br><br>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Código</th>
                                <th style="text-align: center;">Usuario</th>
                                <!--<th style="text-align: center;">Contraseña</th>-->
                                <th style="text-align: center;">Rol</th>
                                <th style="text-align: center;">Estado</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $row) { ?>
                                <tr style="background-color: none;">
                                    <td style="text-align: center;"><?= $row->codi_usu ?></td>
                                    <td style="text-align: center;"><?= $row->logi_usu ?></td>
                                    <!--<td style="text-align: center;"><button type="button" class="tooltip-usu btn btn-default watch-key" data-toggle="tooltip" data-placement="top" title="Ver contraseña"><i class="fa fa-eye"></i></button><span class="pass_key" style="display: none;"><?= $row->pass_usu ?></span></td>-->
                                    <td style="text-align: center;"><?= $row->nomb_rol ?></td>
                                    <td style="text-align: center;"><?php if ($row->esta_usu == "A") {
                                echo "Habilitado";
                            } else if ($row->esta_usu == "D") {
                                echo "Deshabilitado";
                            } ?></td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <button type="button" class="tooltip-usu btn btn-success btn-circle editar_usu" data-toggle="tooltip" data-placement="top" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
    <?php if ($row->esta_usu == "D") { ?>
                                            <span>
                                                <?= form_open(base_url() . 'usuario', $form_a) ?>
                                                <input type="hidden" name="codigo" value="<?= $row->codi_usu ?>">
                                                <input type="hidden" name="usuario" value="<?= $row->logi_usu ?>">
                                                <input name="activar" type="submit" class="tooltip-usu btn btn-primary btn-circle fa" value="&#xf00c;" data-toggle="tooltip" data-placement="top" title="Habilitar">
                                                <?= form_close() ?>
                                            </span>
    <?php } else if ($row->esta_usu == "A") { ?>
                                            <span>
                                                <?= form_open(base_url() . 'usuario', $form_a) ?>
                                                <input type="hidden" name="codigo" value="<?= $row->codi_usu ?>">
                                                <input type="hidden" name="usuario" value="<?= $row->logi_usu ?>">
                                                <input name="desactivar" type="submit" class="tooltip-usu btn btn-danger btn-circle fa" value="&#xf05e;" data-toggle="tooltip" data-placement="top" title="Deshabilitar">
                                    <?= form_close() ?>
                                            </span>
    <?php } ?>
                                    </td>
                                </tr>
<?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalNuevoUsuario" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content" style="border-color: #428bca; border-style: inset;">
<?= form_open(base_url() . 'usuario', $form) ?>
            <div class="modal-header" style="
                 padding: 10px 15px;
                 border-bottom: 1px solid transparent;
                 border-top-left-radius: 3px;
                 border-top-right-radius: 3px;
                 color: #fff;
                 background-color: #428bca;
                 border-color: #428bca;
                 ">
                <h4 class="modal-title" id="myModalLabel">Nuevo usuario</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Usuario: *</label>
                    <?= form_input($login) ?>
                </div>
                <div class="form-group">
                    <label>Contraseña: *</label>
                    <?= form_password($pass) ?>
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña: *</label>
                    <?= form_password($con_pass) ?>
                </div>
                <div class="form-group">
                    <label>Rol: *</label>
<?= form_dropdown('rol', $roles, array(), 'id="codi_rol" class="form-control"') ?>
                </div>

            </div>
            <div class="modal-footer">
                <div style="float: right;">
                    <button id="btnCancelarNuevoUsu" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
<?= form_submit($registrar) ?>
                </div>
            </div>
<?= form_close() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEditarUsuario" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content" style="border-color: #428bca; border-style: inset;">
<?= form_open(base_url() . 'usuario', $form_editar) ?>
            <div class="modal-header" style="
                 padding: 10px 15px;
                 border-bottom: 1px solid transparent;
                 border-top-left-radius: 3px;
                 border-top-right-radius: 3px;
                 color: #fff;
                 background-color: #428bca;
                 border-color: #428bca;
                 ">
                <h4 class="modal-title" id="myModalLabel">Editar usuario</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Código: *</label>
                    <?= form_input($codigo_e) ?>
                </div>
                <div class="form-group">
                    <label>Usuario: *</label>
                    <?= form_input($login_e) ?>
                </div>
                <div class="form-group">
                    <label>Contraseña:</label>
                    <?= form_password($pass_e) ?>
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña:</label>
                    <?= form_password($con_pass_e) ?>
                </div>
                <div class="form-group">
                    <label>Rol: *</label>
<?= form_dropdown('rol_e', $roles, array(), 'id="codi_rol_e" class="form-control"') ?>
                </div>
            </div>
            <div class="modal-footer">
                <div style="float: right;">
                    <button id="btnCancelarNuevoUsu" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
<?= form_submit($editar) ?>
                </div>
            </div>
<?= form_close() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDeshabilitarUsu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; top: 25%;">
    <div class="modal-dialog" style="width: 350px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel" style="text-align: center;">¿Estás seguro de que desea deshabilitar?</h4>
            </div>
            <div class="modal-footer" style="margin: 0px; border: 0px; padding: 0px;">
                <div style="text-align: center">
                    <button type="button" class="btn btn-default" data-dismiss="modal" 
                            style="padding-top: 20px; padding-bottom: 20px; width: 49%; float: left; margin:0px; border: none; border-radius: 0px;">No</button>
                    <button id="deshabilitarUsu" type="button" class="btn btn-danger" style="padding-top: 20px; padding-bottom: 20px; width: 49%; float: right; margin:0px; border: none; border-radius: 0px;">Si</button>
                </div>
            </div>
        </div>
    </div>
</div>

<ul id="usuarios-rep" style="display: none;">    
<?php foreach ($usuarios as $row) { ?>
        <li><?= $row->logi_usu ?></li>
<?php } ?>
</ul>
<table id="usuarios-det" style="display: none;">
<?php foreach ($usuarios as $row) { ?>
    <tr><td><?= $row->codi_usu ?></td>
    <td><?= $row->logi_usu ?></td>
    <td><?= $row->nomb_rol ?></td>
    <td><?= $row->esta_usu ?></td></tr>
<?php } ?>
</table>