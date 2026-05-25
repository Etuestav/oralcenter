<!-- =============================================== -->

<!-- Left side column -->
<aside class="main-sidebar app-sidebar">

    <!-- Sidebar -->
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">

            <!-- Inicio -->
            <li class="treeview active">

                <a href="<?= base_url('dashboard') ?>">

                    <i class="fa fa-tachometer"
                       aria-hidden="true"></i>

                    <span>Inicio</span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('dashboard') ?>">

                            <i class="fa fa-user-circle-o"></i>
                            Cuenta

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('regtuto') ?>">

                            <i class="fa fa-video-camera"></i>
                            Video Tutorial

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Registro -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-pencil-square-o"></i>

                    <span class="title">
                        Registro
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('paciente') ?>">

                            <i class="fa fa-male"></i>
                            Paciente

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('medico') ?>">

                            <i class="fa fa-user-md"></i>
                            Odontólogo

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Citas -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-calendar"></i>

                    <span>
                        Citas
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('cita/') ?>">

                            <i class="fa fa-table"></i>
                            Agenda

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('cita/registrar') ?>">

                            <i class="fa fa-calendar-plus-o"></i>
                            Registrar

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Tratamientos -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-medkit"></i>

                    <span>
                        Tratamientos
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('tratamiento') ?>">

                            <i class="fa fa-medkit"></i>
                            Registrar

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('tratamiento/comprobante') ?>">

                            <i class="fa fa-line-chart"></i>
                            Comprobantes

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Facturacion electronica -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-file-text"></i>

                    <span>
                        Facturacion electronica
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('facturacion/electronica/configuracion') ?>">

                            <i class="fa fa-cogs"></i>
                            Configuración

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('facturacion/electronica/documentos') ?>">

                            <i class="fa fa-file-text-o"></i>
                            Documentos

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('facturacion/electronica/notas') ?>">

                            <i class="fa fa-sticky-note"></i>
                            Nota deb/cred

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('facturacion/electronica/resumen-boletas') ?>">

                            <i class="fa fa-calendar-check-o"></i>
                            Resumen boletas

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('facturacion/electronica/comunicacion-baja') ?>">

                            <i class="fa fa-file-excel-o"></i>
                            Comunicacion baja

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('facturacion/electronica/guias-remision') ?>">

                            <i class="fa fa-map-marker"></i>
                            Guias remision

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Historia clínica -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-file-text-o"></i>

                    <span>
                        Historia clínica
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('historia') ?>">

                            <i class="fa fa-pencil"></i>
                            Movimiento

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Reportes -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-bar-chart-o"></i>

                    <span>
                        Reportes
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('reportes/redashboard') ?>">

                            <i class="fa fa-money"></i>
                            Tratamientos cobrados

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Procedimiento -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-suitcase"></i>

                    <span class="title">
                        Procedimiento
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('tarifario') ?>">

                            <i class="fa fa-usd"></i>
                            Tarifario

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('diagnostico') ?>">

                            <i class="fa fa-medkit"></i>
                            Diagnóstico

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Mantenimiento -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-wrench"></i>

                    <span class="title">
                        Mantenimiento
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('pago') ?>">

                            <i class="fa fa-newspaper-o"></i>
                            Tipo pago

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('moneda') ?>">

                            <i class="fa fa-dollar"></i>
                            Moneda

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('banco') ?>">

                            <i class="fa fa-cubes"></i>
                            Banco

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('area') ?>">

                            <i class="fa fa-map-marker"></i>
                            Areas

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('tarjeta') ?>">

                            <i class="fa fa-cc-visa"></i>
                            Tipo tarjeta

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Catálogo -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-cube"></i>

                    <span class="title">
                        Catálogo
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>
                        <a href="<?= base_url('medida') ?>">

                            <i class="fa fa-cube"></i>
                            Unidad Medida

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('concepto') ?>">

                            <i class="fa fa-hand-o-right"></i>
                            Tipo concepto

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('categoria') ?>">

                            <i class="fa fa-diamond"></i>
                            Categoría

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('especialidad') ?>">

                            <i class="fa fa-plus-square"></i>
                            Especialidad

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('citado') ?>">

                            <i class="fa fa-stethoscope"></i>
                            Tipo citado

                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('alergia') ?>">

                            <i class="fa fa-stethoscope"></i>
                            Alergia

                        </a>
                    </li>

                </ul>

            </li>

            <!-- Gestión de usuarios -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-users"></i>

                    <span class="title">
                        Gestión de usuarios
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>

                        <a href="<?= base_url('usuario') ?>">

                            <i class="fa fa-user"></i>

                            <span class="title">
                                Usuarios
                            </span>

                        </a>

                    </li>

                    <li>

                        <a href="<?= base_url('rol') ?>">

                            <i class="fa fa-briefcase"></i>

                            <span class="title">
                                Roles
                            </span>

                        </a>

                    </li>

                    <li>

                        <a href="<?= base_url('permiso') ?>">

                            <i class="fa fa-unlock-alt"></i>

                            <span class="title">
                                Permisos
                            </span>

                        </a>

                    </li>

                </ul>

            </li>

            <!-- Configuración -->
            <li class="treeview">

                <a href="#">

                    <i class="fa fa-gear"></i>

                    <span class="title">
                        Configuración
                    </span>

                </a>

                <ul class="treeview-menu">

                    <li>

                        <a href="<?= base_url('clinica/regclinica') ?>">

                            <i class="fa fa-hospital-o"></i>

                            <span class="title">
                                Mi Clínica
                            </span>

                        </a>

                    </li>

                    <li>

                        <a href="<?= base_url('tipodocumento') ?>">

                            <i class="fa fa-newspaper-o"></i>

                            <span class="title">
                                Tipo Documento
                            </span>

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </section>
    <!-- /.sidebar -->

</aside>

<!-- =============================================== -->
