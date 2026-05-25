<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <section class="content-header">
    <h1>Historia clínica</h1>

    <ol class="breadcrumb">
      <div style="float:right;">
        <div class="dropdown">
          <div class="btn btn-default dropdown-toggle"
               type="button"
               data-toggle="dropdown"
               style="padding:0px 8px;">
            <i class="fa fa-cog fa-2"
               aria-hidden="true"
               style="font-size: 22px;"></i>
          </div>

          <ul class="dropdown-menu dropdown-menu-right">
            <li>
              <a target="_blank"
                 href="<?= base_url('historia/imprimirHistoria/' . esc($paciente->codi_pac ?? '')) ?>">
                <i class="fa fa-print fa-1" aria-hidden="true"></i>
                Imprimir Historia
              </a>
            </li>
          </ul>
        </div>
      </div>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">

        <div class="box">
          <div class="box-body">
            <div class="row">

              <div class="col-md-9">
                <div id="HistoriaContenido"
                     data-paciente="<?= esc($paciente->codi_pac ?? '') ?>">

                  <?= view('admin/historia/movimiento/datos_paciente', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/exploracion_fisica', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/receta', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/placas', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/odontograma/odontograma', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/evolucion', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/cita', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/histratamiento', ['paciente' => $paciente ?? null]) ?>

                  <?= view('admin/historia/movimiento/diagnostico', ['paciente' => $paciente ?? null]) ?>

                </div>
              </div>

              <div class="col-md-3">
                <div class="text-center">

                  <?php if (! empty($paciente->foto_paciente)): ?>
                    <img src="<?= base_url('vendor/uploads/pacientes/' . esc($paciente->foto_paciente)) ?>"
                         alt="<?= esc(($paciente->nomb_pac ?? '') . ' ' . ($paciente->apel_pac ?? '')) ?>"
                         class="img img-responsive"
                         style="max-width: 200px">
                  <?php else: ?>
                    <img src="<?= base_url('vendor/img/usuario_inicio.png') ?>"
                         alt="User profile picture"
                         class="profile-user-img img-responsive img-circle"
                         style="max-width: 100px">
                  <?php endif; ?>

                  <h4>H.C: <?= esc($paciente->codi_pac ?? '') ?></h4>

                  <h5>
                    <?= esc(($paciente->nomb_pac ?? '') . ' ' . ($paciente->apel_pac ?? '')) ?>
                  </h5>
                </div>

                <div id="HistoriaMenu" class="list-group">

                  <a href="#"
                     data-id="HistoriaContenidoDatosPaciente"
                     class="list-group-item active">
                    <i class="fa fa-user"></i>
                    Datos del Paciente
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoExploracionFisica"
                     class="list-group-item">
                    <i class="fa fa-file-text-o"></i>
                    Exploración Física
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoOdontograma"
                     class="list-group-item">
                    <i class="fa fa-life-ring"></i>
                    Odontograma
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoDiagnostico"
                     class="list-group-item">
                    <i class="fa fa-heart"></i>
                    Diagnóstico
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoEvolucion"
                     class="list-group-item">
                    <i class="fa fa-user-md"></i>
                    Evolución
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoPlacas"
                     class="list-group-item">
                    <i class="fa fa-files-o"></i>
                    Exam. Auxiliares Placas
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoTratamientos"
                     class="list-group-item">
                    <i class="fa fa-credit-card"></i>
                    Tratamientos realizados
                  </a>

                  <a href="#"
                     data-id="HistoriaContenidoCita"
                     class="list-group-item">
                    <i class="fa fa-calendar-plus-o"></i>
                    Citas
                  </a>

                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

</div>
