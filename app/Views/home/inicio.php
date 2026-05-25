<style>
  .dashboard-side-box {
    border-top: 3px solid #18b7d9;
    border-radius: 4px;
  }

  .dashboard-side-box .box-header {
    padding: 18px 24px 12px;
  }

  .dashboard-side-box .box-title {
    font-size: 24px;
    line-height: 1.2;
  }

  .quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
    gap: 12px;
    padding: 8px 8px 4px;
  }

  .quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 72px;
    padding: 14px 16px;
    color: #263238;
    background: #f8fafc;
    border: 1px solid #e4e9ef;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
  }

  .quick-action:hover,
  .quick-action:focus {
    color: #0f6d8f;
    background: #eef9fd;
    border-color: #bfe9f4;
    text-decoration: none;
  }

  .quick-action-icon {
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    color: #fff;
    background: #1689ad;
    border-radius: 4px;
    flex: 0 0 32px;
  }

  .quick-action-meta {
    min-width: 0;
    flex: 1 1 auto;
  }

  .quick-action-title {
    display: block;
    font-weight: 600;
    line-height: 1.25;
    white-space: normal;
    overflow: visible;
    text-overflow: clip;
    overflow-wrap: anywhere;
  }

  .quick-action-count {
    display: inline-block;
    min-width: 24px;
    margin-top: 4px;
    padding: 2px 6px;
    color: #fff;
    background: #1c75ff;
    border-radius: 10px;
    font-size: 11px;
    line-height: 1.2;
    text-align: center;
  }

  .recent-treatment-list {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .recent-treatment-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 16px;
    border-bottom: 1px solid #edf0f3;
  }

  .recent-treatment-item:last-child {
    border-bottom: 0;
  }

  .recent-treatment-avatar {
    width: 34px;
    height: 34px;
    line-height: 34px;
    text-align: center;
    color: #1689ad;
    background: #e9f7fb;
    border-radius: 4px;
    flex: 0 0 34px;
  }

  .recent-treatment-info {
    min-width: 0;
    flex: 1;
  }

  .recent-treatment-title {
    display: block;
    color: #25313b;
    font-weight: 600;
    line-height: 1.25;
    overflow-wrap: anywhere;
  }

  .recent-treatment-title:hover {
    color: #0f6d8f;
    text-decoration: none;
  }

  .recent-treatment-detail {
    display: block;
    margin-top: 4px;
    color: #6b7785;
    font-size: 12px;
    line-height: 1.35;
  }

  .recent-treatment-amount {
    margin-left: 8px;
    white-space: nowrap;
    flex: 0 0 auto;
  }

  @media (max-width: 480px) {
    .quick-actions-grid {
      grid-template-columns: 1fr;
    }

    .recent-treatment-item {
      flex-wrap: wrap;
    }

    .recent-treatment-amount {
      margin-left: 46px;
    }
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-dashboard"></i> Dashboard
      <small><?= esc(session()->get('clinica') ?: 'Resumen general') ?></small>
    </h1>
    <ol class="breadcrumb">
      <li class="active"><i class="fa fa-dashboard"></i> Inicio</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?= esc($metricas['citas_hoy'] ?? 0) ?></h3>
            <p>Citas de hoy</p>
          </div>
          <div class="icon"><i class="fa fa-calendar"></i></div>
          <a href="<?= base_url('cita') ?>" class="small-box-footer">Ver agenda <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?= esc($metricas['citas_pendientes'] ?? 0) ?></h3>
            <p>Citas pendientes</p>
          </div>
          <div class="icon"><i class="fa fa-clock-o"></i></div>
          <a href="<?= base_url('cita') ?>" class="small-box-footer">Gestionar <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= esc($metricas['pacientes'] ?? 0) ?></h3>
            <p>Pacientes activos</p>
          </div>
          <div class="icon"><i class="fa fa-users"></i></div>
          <a href="<?= base_url('paciente') ?>" class="small-box-footer">Ver pacientes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
          <div class="inner">
            <h3><?= esc($metricas['medicos'] ?? 0) ?></h3>
            <p>Médicos activos</p>
          </div>
          <div class="icon"><i class="fa fa-user-md"></i></div>
          <a href="<?= base_url('medico') ?>" class="small-box-footer">Ver médicos <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-briefcase"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Tratamientos activos</span>
            <span class="info-box-number"><?= esc($metricas['tratamientos_activos'] ?? 0) ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Por cobrar</span>
            <span class="info-box-number">S/ <?= esc(number_format((float) ($metricas['por_cobrar'] ?? 0), 2)) ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Ingresos del mes</span>
            <span class="info-box-number">S/ <?= esc(number_format((float) ($metricas['ingresos_mes'] ?? 0), 2)) ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Próximas citas</h3>
            <div class="box-tools pull-right">
              <a href="<?= base_url('cita/registrar') ?>" class="btn btn-info btn-xs">
                <i class="fa fa-plus"></i> Nueva cita
              </a>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Paciente</th>
                  <th>Médico</th>
                  <th>Especialidad</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($proximasCitas)): ?>
                  <tr>
                    <td colspan="5" class="text-center">No hay citas próximas.</td>
                  </tr>
                <?php endif; ?>
                <?php foreach ($proximasCitas as $cita): ?>
                  <tr>
                    <td><?= esc(date('d/m/Y H:i', strtotime($cita->fech_cit))) ?></td>
                    <td><?= esc(trim(($cita->nomb_pac ?? '') . ' ' . ($cita->apel_pac ?? ''))) ?></td>
                    <td><?= esc(trim(($cita->nomb_med ?? '') . ' ' . ($cita->apel_med ?? ''))) ?></td>
                    <td><?= esc($cita->nombre_especialidad ?? '') ?></td>
                    <td><span class="label label-info"><?= esc($cita->nomb_citado ?? '') ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="box dashboard-side-box">
          <div class="box-header with-border">
            <h3 class="box-title">Actividad rápida</h3>
          </div>
          <div class="box-body">
            <div class="quick-actions-grid">
              <a href="<?= base_url('tratamiento') ?>" class="quick-action">
                <span class="quick-action-icon"><i class="fa fa-briefcase"></i></span>
                <span class="quick-action-meta">
                  <span class="quick-action-title">Tratamientos</span>
                  <span class="quick-action-count"><?= esc($metricas['tratamientos_activos'] ?? 0) ?></span>
                </span>
              </a>
              <a href="<?= base_url('facturacion/electronica') ?>" class="quick-action">
                <span class="quick-action-icon"><i class="fa fa-file-text-o"></i></span>
                <span class="quick-action-meta">
                  <span class="quick-action-title">Electrónicos</span>
                  <span class="quick-action-count"><?= esc($metricas['cpe_pendientes'] ?? 0) ?></span>
                </span>
              </a>
              <a href="<?= base_url('historia') ?>" class="quick-action">
                <span class="quick-action-icon"><i class="fa fa-file-text"></i></span>
                <span class="quick-action-meta">
                  <span class="quick-action-title">Historias</span>
                </span>
              </a>
              <a href="<?= base_url('reportes/redashboard') ?>" class="quick-action">
                <span class="quick-action-icon"><i class="fa fa-bar-chart"></i></span>
                <span class="quick-action-meta">
                  <span class="quick-action-title">Reportes</span>
                </span>
              </a>
            </div>
          </div>
        </div>

        <div class="box dashboard-side-box">
          <div class="box-header with-border">
            <h3 class="box-title">Tratamientos recientes</h3>
          </div>
          <div class="box-body no-padding">
            <ul class="recent-treatment-list">
              <?php if (empty($tratamientosRecientes)): ?>
                <li class="recent-treatment-item text-center">No hay tratamientos recientes.</li>
              <?php endif; ?>
              <?php foreach ($tratamientosRecientes as $tratamiento): ?>
                <li class="recent-treatment-item">
                  <span class="recent-treatment-avatar"><i class="fa fa-user"></i></span>
                  <div class="recent-treatment-info">
                    <a href="<?= base_url('tratamiento/editar/' . $tratamiento->codi_tra) ?>" class="recent-treatment-title">
                      <?= esc($tratamiento->paciente) ?>
                    </a>
                    <span class="recent-treatment-detail">
                      <?= esc($tratamiento->fecha_tra) ?> - <?= esc($tratamiento->asunto_tra) ?>
                    </span>
                  </div>
                  <span class="label label-success recent-treatment-amount">S/ <?= esc(number_format((float) $tratamiento->total_tra, 2)) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
