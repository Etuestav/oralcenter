<footer class="main-footer app-footer">

    <div class="pull-right hidden-xs">
        <b>Versión</b> 2026
    </div>

    <strong>
        GHVCORP
        <a href="https://adminlte.io"></a>
    </strong>

</footer>

</div>
<!-- ./wrapper -->

<!-- Highcharts -->
<script src="<?= base_url('vendor/template/highcharts/highcharts.js') ?>"></script>
<script src="<?= base_url('vendor/template/highcharts/exporting.js') ?>"></script>
<script src="<?= base_url('vendor/template/highcharts/export-data.js') ?>"></script>

<!-- Bootstrap -->
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap.min.js') ?>"></script>

<!-- Plugins -->
<script src="<?= base_url('vendor/template/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/jquery-print/jquery.print.js') ?>"></script>
<script src="<?= base_url('vendor/template/fastclick/lib/fastclick.js') ?>"></script>

<!-- AdminLTE 4 -->
<script src="<?= base_url('vendor/template/adminlte4/js/adminlte.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/dist/js/bootstrap-switch.js') ?>"></script>
<script src="<?= base_url('vendor/template/dist/js/jquery.formatCurrency-1.4.0.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/dist/js/jquery.uitablefilter.js') ?>"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<!-- DataTables -->
<script src="<?= base_url('vendor/template/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/datatables/js/dataTables.bootstrap.js') ?>"></script>

<!-- Datepicker -->
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap-datepicker.es.min.js') ?>"></script>

<!-- DateTimePicker -->
<script src="<?= base_url('vendor/template/bootstrap/js/moment.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap-datetimepicker.es.js') ?>"></script>

<!-- Timepicker -->
<script src="<?= base_url('vendor/plugins/timepicker/bootstrap-timepicker.min.js') ?>"></script>

<!-- Select2 -->
<script src="<?= base_url('vendor/plugins/select2/select2.full.min.js') ?>"></script>

<!-- jQuery Validate -->
<script src="<?= base_url('vendor/plugins/jquery-validate/jquery.validate.js') ?>"></script>
<script src="<?= base_url('vendor/plugins/jquery-validate/additional-methods.js') ?>"></script>
<script src="<?= base_url('vendor/plugins/jquery-validate/localization/messages_es.js') ?>"></script>

<!-- jQuery Form -->
<script src="<?= base_url('vendor/plugins/jquery-form/jquery.form.js') ?>"></script>

<!-- DateDropper -->
<script src="<?= base_url('vendor/plugins/datedropper3/datedropper.js') ?>"></script>

<!-- Input Mask -->
<script src="<?= base_url('vendor/plugins/input-mask/jquery.inputmask.bundle.js') ?>"></script>
<script src="<?= base_url('vendor/plugins/input-mask/jquery.mask.min.js') ?>"></script>

<!-- Upload -->
<script src="<?= base_url('vendor/jquery-upload/js/vendor/jquery.ui.widget.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-upload/js/jquery.iframe-transport.js') ?>"></script>
<script src="<?= base_url('vendor/jquery-upload/js/jquery.fileupload.js') ?>"></script>

<!-- Main JS -->
<script src="<?= base_url('vendor/main.js?v=' . time()) ?>"></script>
<script src="<?= base_url('vendor/html2canvas.min.js') ?>"></script>

<!-- CKEditor -->
<script src="<?= base_url('vendor/template/ckeditor/ckeditor.js') ?>"></script>

<!-- Odontograma -->
<script src="<?= base_url('vendor/odontograma/js/main.js?v=' . time()) ?>"></script>

<?php if (session()->getFlashdata('titulo')): ?>

<div id="ModalMensajeFlash"
     class="modal fade"
     role="dialog">

    <div class="modal-dialog modal-md"
         role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

                <h4 class="modal-title">
                    <?= esc(session()->getFlashdata('titulo')) ?>
                </h4>

            </div>

            <div class="modal-body">

                <?= session()->getFlashdata('contenido') ?>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-danger pull-left"
                        data-dismiss="modal">

                    <i class="fa fa-close"></i>
                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>

<script>
    $('#ModalMensajeFlash').modal();
</script>

<?php endif; ?>

<script>

    const base_url = '<?= base_url('') ?>';
    const path = '<?= base_url('') ?>';
    const asset_url = '<?= base_url('') ?>';

</script>

<script src="<?= base_url('vendor/template/dist/js/archivos.js') ?>"></script>

<script>

$(document).ready(function () {

    $(document)
        .on('show.bs.modal shown.bs.modal', '.modal', function () {
            $(this).addClass('show in');
        })
        .on('hide.bs.modal hidden.bs.modal', '.modal', function () {
            $(this).removeClass('show in');
        });

    $(document)
        .on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function () {
            const $modal = $(this).closest('.modal');

            if ($modal.length && $.fn.modal) {
                $modal.modal('hide');
            }
        });

    let year = (new Date()).getFullYear();

    if ($('#grafico').length) {

        datagrafico(base_url, year);

        $("#year").on("change", function () {

            const yearselect = $(this).val();

            datagrafico(base_url, yearselect);

        });

    }

    $('button.ingresar').click(function () {

        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
        );

    });

    function datagrafico(base_url, year) {

        const namesMonth = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Setiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ];

        $.ajax({

            url: base_url+'reportes/getData',
            type: "POST",

            data: {
                year: year
            },

            dataType: "json",

            success: function (data) {

                const meses = [];
                const montos = [];

                $.each(data, function (key, value) {

                    meses.push(namesMonth[value.mes - 1]);

                    montos.push(Number(value.montos));

                });

                graficar(meses, montos, year);

            }

        });

    }

    function graficar(meses, montos, year) {

        Highcharts.chart('grafico', {

            chart: {
                type: 'column'
            },

            title: {
                text: 'Tratamientos x Meses'
            },

            subtitle: {
                text: 'Año: ' + year
            },

            xAxis: {

                categories: meses,
                crosshair: true

            },

            yAxis: {

                min: 0,

                title: {
                    text: 'Monto Total (soles)'
                }

            },

            tooltip: {

                headerFormat:
                    '<span style="font-size:10px">{point.key}</span><table>',

                pointFormat:
                    '<tr>' +
                    '<td style="color:{series.color};padding:0">' +
                    '{series.name}: </td>' +
                    '<td style="padding:0">' +
                    '<b>{point.y:.1f} Soles</b></td></tr>',

                footerFormat: '</table>',

                shared: true,
                useHTML: true

            },

            plotOptions: {

                column: {

                    pointPadding: 0.2,
                    borderWidth: 0

                },

                series: {

                    dataLabels: {

                        enabled: true,

                        formatter: function () {

                            return Highcharts.numberFormat(this.y, 2);

                        }

                    }

                }

            },

            series: [
                {
                    name: 'Tratamientos cobrados',
                    data: montos
                }
            ]

        });

    }

    $('.sidebar-toggle').on('click', function (event) {
        event.preventDefault();
        $('body').toggleClass('sidebar-collapse');
        if (window.localStorage) {
            localStorage.setItem('ocSidebarCollapsed', $('body').hasClass('sidebar-collapse') ? '1' : '0');
        }
        activateSidebarFromUrl();
    });

    function activateSidebarFromUrl() {
        const currentPath = window.location.pathname.replace(/\/+$/, '');
        let $bestMatch = $();
        let bestLength = -1;

        $('.sidebar-menu a[href]').each(function () {
            const href = $(this).attr('href');

            if (!href || href === '#') {
                return;
            }

            const link = new URL(href, window.location.origin);
            const linkPath = link.pathname.replace(/\/+$/, '');
            const isMatch = currentPath === linkPath || currentPath.indexOf(linkPath + '/') === 0;

            if (isMatch && linkPath.length > bestLength) {
                $bestMatch = $(this);
                bestLength = linkPath.length;
            }
        });

        if (!$bestMatch.length && currentPath.indexOf('/facturacion/electronica') !== -1) {
            $bestMatch = $('.sidebar-menu a[href$="facturacion/electronica/documentos"]').first();
        }

        if (!$bestMatch.length) {
            return;
        }

        $('.sidebar-menu li').removeClass('active menu-open');

        const $item = $bestMatch.closest('li');
        const $treeview = $bestMatch.closest('li.treeview');

        $item.addClass('active');
        $treeview.addClass('active');

        if (!$('body').hasClass('sidebar-collapse')) {
            $treeview.addClass('menu-open');
        }
    }

    activateSidebarFromUrl();

    $('.sidebar-menu .treeview > a').on('click', function (event) {
        const $parent = $(this).parent();
        const hasSubmenu = $parent.children('.treeview-menu').length > 0;

        if (!hasSubmenu || $(this).attr('href') !== '#') {
            return;
        }

        event.preventDefault();
        if ($('body').hasClass('sidebar-collapse')) {
            return;
        }

        $parent.siblings('.treeview').removeClass('menu-open active');
        $parent.toggleClass('menu-open active');
    });

    $('.sidebar-menu > .treeview').on('mouseenter', function () {
        const item = this;
        const menu = item.querySelector(':scope > .treeview-menu');

        if (!$('body').hasClass('sidebar-collapse') || !menu) {
            item.classList.remove('flyout-up');
            return;
        }

        item.classList.remove('flyout-up');

        const itemRect = item.getBoundingClientRect();
        const menuHeight = menu.scrollHeight;
        const spaceBelow = window.innerHeight - itemRect.bottom;
        const spaceAbove = itemRect.top;

        if (spaceBelow < menuHeight && spaceAbove > spaceBelow) {
            item.classList.add('flyout-up');
        }
    }).on('mouseleave', function () {
        this.classList.remove('flyout-up');
    });

    $('.main-sidebar').on('mouseenter mouseleave', function () {
        $('body').removeClass('sidebar-flyout-suppressed');
    });

});

</script>

</body>
</html>
