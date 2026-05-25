<!-- Iconos -->


<div class="content-wrapper">

    <section class="content-header">

        <div class="row">

            <!-- Excelente -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">

                    <span class="info-box-icon">
                        <i class="fa fa-user-circle"></i>
                    </span>

                    <div class="info-box-content">

                        <span class="info-box-text">
                            Excelente
                        </span>

                        <span class="info-box-number">
                            <?= $excelente ?? 0 ?>
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Bueno -->
            <div class="col-md-3 col-sm-6 col-xs-12">

                <div class="info-box bg-green">

                    <span class="info-box-icon">
                        <i class="fa fa-user-circle"></i>
                    </span>

                    <div class="info-box-content">

                        <span class="info-box-text">
                            Bueno
                        </span>

                        <span class="info-box-number">
                            <?= $bueno ?? 0 ?>
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Regular -->
            <div class="col-md-3 col-sm-6 col-xs-12">

                <div class="info-box bg-red">

                    <span class="info-box-icon">
                        <i class="fa fa-user-circle"></i>
                    </span>

                    <div class="info-box-content">

                        <span class="info-box-text">
                            Regular
                        </span>

                        <span class="info-box-number">
                            <?= $regular ?? 0 ?>
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Pésimo -->
            <div class="col-md-3 col-sm-6 col-xs-12">

                <div class="info-box bg-blue">

                    <span class="info-box-icon">
                        <i class="fa fa-user-circle"></i>
                    </span>

                    <div class="info-box-content">

                        <span class="info-box-text">
                            Pésimo
                        </span>

                        <span class="info-box-number">
                            <?= $pesimo ?? 0 ?>
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Malo -->
            <div class="col-md-3 col-sm-6 col-xs-12">

                <div class="info-box bg-purple">

                    <span class="info-box-icon">
                        <i class="fa fa-user-circle"></i>
                    </span>

                    <div class="info-box-content">

                        <span class="info-box-text">
                            Malo
                        </span>

                        <span class="info-box-number">
                            <?= $malo ?? 0 ?>
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- /.row -->

        <!-- Gráfico -->
        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="nav-tabs-custom">

                    <div class="box">

                        <div class="box-header with-border">

                            <h3 class="box-title">
                                Gráfico Estadístico
                            </h3>

                            <div class="box-tools pull-right">

                                <select name="year"
                                        id="year"
                                        class="form-control">

                                    <?php foreach ($years as $year): ?>

                                        <option value="<?= $year->year ?>">
                                            <?= $year->year ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <!-- /.box-header -->

                        <div class="box-body">

                            <div class="row">

                                <div class="col-md-11">

                                    <div id="grafico"
                                         style="margin: 0 auto;">
                                    </div>

                                </div>

                            </div>

                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->

                </div>

            </section>

        </div>
        <!-- /.row -->

    </section>

</div>

