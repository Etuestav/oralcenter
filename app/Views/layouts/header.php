<!DOCTYPE html>

<html lang="es" style="height: auto;">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">

    <title>
        Dental
    </title>

    <!-- Responsive -->
    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1,
                   maximum-scale=1,
                   user-scalable=no">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/template/bootstrap/css/bootstrap.min.css') ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/template/bootstrap/css/bootstrap-datepicker3.min.css') ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/template/bootstrap/css/bootstrap-datetimepicker.css') ?>">

    <!-- Timepicker -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/plugins/timepicker/bootstrap-timepicker.min.css') ?>">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/template/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/template/datatables/css/dataTables.bootstrap.css') ?>">

    <!-- AdminLTE 4 -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/template/adminlte4/css/adminlte.min.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/template/font-awesome/css/font-awesome.min.css') ?>">

    <!-- Ionicons -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/template/Ionicons/css/ionicons.css') ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/template/Ionicons/css/ionicons.min.css') ?>">

    <!-- Select2 -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/plugins/select2/select2.css') ?>">

    <!-- Calendar -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/plugins/calendar/css/calendar.css') ?>">

    <!-- DateDropper -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/plugins/datedropper3/datedropper.css') ?>">

    <!-- Upload -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/jquery-upload/css/jquery.fileupload.css') ?>">

    <!-- Estilos -->
    <link rel="stylesheet"
          href="<?= base_url('vendor/adminlte4-legacy.css?v=' . time()) ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/styles.css?v=' . time()) ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/odontograma/css/odontograma.css?v=' . time()) ?>">

    <link rel="stylesheet"
          href="<?= base_url('vendor/template/bootstrap/css/odontogeneral.css') ?>">

    <!-- jQuery -->
    <script src="<?= base_url('vendor/template/jquery/jquery.min.js') ?>"></script>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-mini adminlte4-legacy">

    <script>
        if (window.localStorage && localStorage.getItem('ocSidebarCollapsed') === '1') {
            document.body.classList.add('sidebar-collapse');
        }
    </script>

    <!-- Wrapper -->
    <div class="wrapper app-wrapper"
         style="height: auto;">

        <!-- Header -->
        <header class="main-header app-header">

            <!-- Logo -->
            <a href="<?= base_url('dashboard') ?>"
               class="logo"
               style="position: fixed;">

                <span class="logo-mini">
                    <b>OC</b>
                </span>

                <!--
                <center>
                    <img style="width:60%;margin-top:auto;"
                         src="<?= base_url('vendor/img/dentalsac.png') ?>">
                </center>
                -->

            </a>

            <!-- Navbar -->
            <nav class="navbar navbar-static-top"
                 role="navigation">

                <!-- Sidebar toggle -->
                <a href="#"
                   class="sidebar-toggle"
                   role="button">

                    <span class="sr-only">
                        Toggle navigation
                    </span>

                </a>

                <!-- Logo superior -->
                <img src="<?= base_url('vendor/img/dentalsac.png') ?>"
                     style="max-width: 118px;
                            padding-top: 1px;
                            margin-left: 5px;">

                <!-- Navbar Right -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">

                        <!-- Usuario -->
                        <li class="dropdown user user-menu">

                            <a href="#"
                               class="dropdown-toggle"
                               data-toggle="dropdown">

                                <img src="<?= base_url('vendor/img/usuario_inicio.png') ?>"
                                     class="user-image"
                                     alt="User Image">

                                <span class="hidden-xs">
                                    <?= esc(session()->get('nombre')) ?>
                                </span>

                            </a>

                            <!-- Dropdown -->
                            <ul class="dropdown-menu">

                                <!-- User Header -->
                                <li class="user-header">

                                    <img src="<?= base_url('vendor/img/usuario_inicio.png') ?>"
                                         class="img-circle"
                                         alt="User Image">

                                    <p>

                                        <span class="hidden-xs">
                                            <?= esc(session()->get('nombre')) ?>
                                        </span>

                                        <small>
                                            <?= esc(session()->get('nombrerol')) ?>
                                        </small>

                                    </p>

                                </li>

                                <!-- Footer -->
                                <li class="user-footer">

                                    <div class="pull-left">

                                        <a href="<?= base_url('usuario/perfil') ?>"
                                           class="btn btn-default btn-flat">

                                            Perfil

                                        </a>

                                    </div>

                                    <div class="pull-right">

                                        <a href="<?= base_url('logout') ?>"
                                           class="btn btn-default btn-flat">

                                            Cerrar Sesión

                                        </a>

                                    </div>

                                </li>

                            </ul>

                        </li>

                    </ul>

                </div>

            </nav>

        </header>
