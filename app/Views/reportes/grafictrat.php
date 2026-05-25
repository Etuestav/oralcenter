


          content="IE=edge">


    <!-- Responsive -->
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">




    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Content Header -->
        <section class="content-header">

            <h1>
                Reportes
                <small>Trat. cobrados</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Small boxes -->
            <div class="row">

                <!-- Pacientes -->
                <div class="col-lg-3 col-xs-6">

                    <div class="small-box bg-aqua">

                        <div class="inner">

                            <h3>
                                <?= $cantpacientes ?? 0 ?>
                            </h3>

                            <p>Pacientes</p>

                        </div>

                        <div class="icon">
                            <i class="ion ion-man"></i>
                        </div>

                        <a href="<?= base_url('paciente') ?>"
                           class="small-box-footer">

                            Ver pacientes
                            <i class="fa fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

                <!-- Tratamientos cobrados -->
                <div class="col-lg-3 col-xs-6">

                    <div class="small-box bg-green">

                        <div class="inner">

                            <h3>
                                <?= $cantratamientopag ?? 0 ?>
                            </h3>

                            <p>Tratamientos cobrados</p>

                        </div>

                        <div class="icon">
                            <i class="fa fa-usd"></i>
                        </div>

                        <a href="#"
                           class="small-box-footer">

                            Ver tratamientos
                            <i class="fa fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

                <!-- Tratamientos por cobrar -->
                <div class="col-lg-3 col-xs-6">

                    <div class="small-box bg-yellow">

                        <div class="inner">

                            <h3>
                                <?= $cantratamientocob ?? 0 ?>
                            </h3>

                            <p>Tratamientos por cobrar</p>

                        </div>

                        <div class="icon">
                            <i class="fa fa-credit-card"></i>
                        </div>

                        <a href="#"
                           class="small-box-footer">

                            Ver tratamientos
                            <i class="fa fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

                <!-- Odontólogos -->
                <div class="col-lg-3 col-xs-6">

                    <div class="small-box bg-red">

                        <div class="inner">

                            <h3>
                                <?= $cantmedicos ?? 0 ?>
                            </h3>

                            <p>Odontólogos Habilitados</p>

                        </div>

                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>

                        <a href="#"
                           class="small-box-footer">

                            Ver odontólogos
                            <i class="fa fa-arrow-circle-right"></i>

                        </a>

                    </div>

                </div>

            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">

                <section class="col-lg-12">

                    <!-- Calendar -->
                    <div class="box box-solid bg-light-blue-gradient">

                        <div class="box-header">

                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">
                                Calendario
                            </h3>

                            <!-- Tools -->
                            <div class="pull-right box-tools">

                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-widget="collapse">

                                    <i class="fa fa-minus"></i>

                                </button>

                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="box-footer text-black">

                            <div class="box-tools pull-right">

                                <label for="year">
                                    Año:
                                </label>

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

                            <div class="row">

                                <div class="col-sm-12">

                                    <div class="col-md-12">

                                        <div id="grafico"
                                             style="margin: 0 auto;">
                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!-- /.row -->

                        </div>

                    </div>

                </section>

            </div>
            <!-- /.row -->

        </section>

    </div>

</div>


