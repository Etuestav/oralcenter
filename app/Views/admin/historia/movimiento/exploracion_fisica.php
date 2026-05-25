<div id="HistoriaContenidoExploracionFisica" class="panel panel-primary" style="display: none">

    <div class="panel-heading">
        Exploración física paciente
    </div>

    <div class="panel-body">

        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 15px">

            <li class="active" role="presentation">
                <a href="#enfermedad" aria-controls="enfermedad" role="tab" data-toggle="tab">
                    Enfermedad actual
                </a>
            </li>

            <li role="presentation">
                <a href="#consulta" aria-controls="consulta" role="tab" data-toggle="tab">
                    Consulta de salud
                </a>
            </li>

            <li role="presentation">
                <a href="#exploracion" aria-controls="exploracion" role="tab" data-toggle="tab">
                    Exploración Física
                </a>
            </li>

            <li role="presentation">
                <a href="#alergias" aria-controls="alergias" role="tab" data-toggle="tab">
                    Alergias
                </a>
            </li>

        </ul>

        <div class="tab-content">

            <!-- ================= ENFERMEDAD ================= -->

            <div role="tabpanel" class="tab-pane active" id="enfermedad">

                <form id="FormHistoriaMovimientoPacienteEnfermedad"
                      action="<?= base_url('historia/guardarPacienteEnfermedad') ?>"
                      method="post">

                    <?= csrf_field() ?>

                    <input type="hidden"
                           name="paciente"
                           value="<?= esc($paciente->codi_pac ?? '') ?>">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">

                                <label class="control-label">
                                    Tiempo de enfermedad:
                                </label>

                                <input type="text"
                                       name="tiempoEnfermedad"
                                       class="form-control input-sm"
                                       value="<?= esc($enfermedad->tiempo_enfact ?? '') ?>">

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Motivo de la consulta:
                                </label>

                                <textarea name="motivoConsulta"
                                          class="form-control input-sm"
                                          rows="3"><?= esc($enfermedad->motivo_enfact ?? '') ?></textarea>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Signos y síntomas principales:
                                </label>

                                <textarea name="signosSintomas"
                                          class="form-control input-sm"
                                          rows="3"><?= esc($enfermedad->signo_enfact ?? '') ?></textarea>

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Antecedentes personales:
                                </label>

                                <textarea name="antecedentesPersonales"
                                          class="form-control input-sm"
                                          rows="3"><?= esc($enfermedad->antecper_enfact ?? '') ?></textarea>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Antecedentes familiares:
                                </label>

                                <textarea name="antecedentesFamiliares"
                                          class="form-control input-sm"
                                          rows="3"><?= esc($enfermedad->antecfam_enfact ?? '') ?></textarea>

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    ¿Está tomando algún medicamento?
                                </label>

                                <div>

                                    <label class="radio-inline">
                                        <input type="radio"
                                               name="tomandoMedicamento"
                                               value="1"
                                            <?= (($enfermedad->medicam_enfact ?? '') === '1') ? 'checked' : '' ?>>
                                        Sí
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio"
                                               name="tomandoMedicamento"
                                               value="0"
                                            <?= (($enfermedad->medicam_enfact ?? '') === '0') ? 'checked' : '' ?>>
                                        No
                                    </label>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Nombre del medicamento:
                                </label>

                                <input type="text"
                                       name="nombreMedicamento"
                                       class="form-control input-sm"
                                       value="<?= esc($enfermedad->nommedicam_enfact ?? '') ?>">

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Motivo de uso:
                                </label>

                                <input type="text"
                                       name="motivoUso"
                                       class="form-control input-sm"
                                       value="<?= esc($enfermedad->motivomedi_enfact ?? '') ?>">

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label">
                                    Dosis:
                                </label>

                                <input type="text"
                                       name="dosis"
                                       class="form-control input-sm"
                                       value="<?= esc($enfermedad->dosis_enfact ?? '') ?>">

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group pull-right">

                                <button type="submit" class="btn btn-info">
                                    Guardar
                                </button>

                            </div>
                        </div>

                    </div>

                </form>

            </div>

            <!-- ================= CONSULTA ================= -->

            <div role="tabpanel" class="tab-pane" id="consulta">

                <form id="FormHistoriaMovimientoPacienteConsulta"
                      action="<?= base_url('historia/guardarPacienteConsulta') ?>"
                      method="post">

                    <?= csrf_field() ?>

                    <input type="hidden"
                           name="paciente"
                           value="<?= esc($paciente->codi_pac ?? '') ?>">

                </form>

            </div>

            <!-- ================= EXPLORACION ================= -->

            <div role="tabpanel" class="tab-pane" id="exploracion">

                <form id="FormHistoriaMovimientoPacienteExploracion"
                      action="<?= base_url('historia/guardarPacienteExploracion') ?>"
                      method="post">

                    <?= csrf_field() ?>

                    <input type="hidden"
                           name="paciente"
                           value="<?= esc($paciente->codi_pac ?? '') ?>">

                    <div class="col-md-14">

                        <div class="box box-default box-solid">

                            <div class="box-header with-border">
                                <h6 class="box-title">Funciones Vitales</h6>
                            </div>

                            <div class="box-body">

                                <div class="form-horizontal form-valid">

                                    <div class="form-group">

                                        <div class="col-sm-3 val-smk">

                                            <label class="control-label">
                                                Presión arterial:
                                            </label>

                                            <div class="input-group">

                                                <input type="text"
                                                       name="PA"
                                                       class="form-control input-sm"
                                                       value="<?= esc($exploracion->pa_exp ?? '') ?>">

                                                <div class="input-group-addon">
                                                    mn Hg
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-sm-3 val-smk">

                                            <label class="control-label">
                                                Pulso:
                                            </label>

                                            <div class="input-group">

                                                <input type="text"
                                                       name="pulso"
                                                       class="form-control input-sm"
                                                       value="<?= esc($exploracion->pulso_exp ?? '') ?>">

                                                <div class="input-group-addon">
                                                    / min
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group pull-right">

                                <button type="submit" class="btn btn-info">
                                    Guardar
                                </button>

                            </div>
                        </div>

                    </div>

                </form>

            </div>

            <!-- ================= ALERGIAS ================= -->

            <div role="tabpanel" class="tab-pane" id="alergias">

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">

                            <button class="btn btn-success btn-sm"
                                    data-toggle="modal"
                                    data-target="#ModalAgregarAlergia">

                                <i class="fa fa-plus"></i> Agregar

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
