


          content="IE=edge">

          content="width=device-width, initial-scale=1">

<div class="content-wrapper">

    <section class="content-header">
    </section>

    <section class="content">

        <div class="box-body">

            <div class="col-md-14">

                <div class="row">

                    <div class="col-md-12 col-md-offset-0">

                        <div class="panel panel-info">

                            <div class="panel-heading text-center">

                                <h3>
                                    Ocurrencias recibidas
                                </h3>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-xs-12">

                                        <div class="box-body table-responsive no-padding">

                                            <table id="example1"
                                                   class="table table-bordered table-hover">

                                                <thead>

                                                <tr>

                                                    <th style="width: 2%; text-align: center">
                                                        Secuencia
                                                    </th>

                                                    <th style="width: 20%; text-align: center">
                                                        Usuario
                                                    </th>

                                                    <th style="width: 12%; text-align: center">
                                                        Área
                                                    </th>

                                                    <th style="width: 14%; text-align: center">
                                                        Fecha problema
                                                    </th>

                                                    <th style="width: 12%; text-align: center">
                                                        Fecha enviado
                                                    </th>

                                                    <th style="width: 14%; text-align: center">
                                                        Fecha solucionado
                                                    </th>

                                                    <th style="width: 9%; text-align: center">
                                                        Estado
                                                    </th>

                                                    <th style="width: 10%; text-align: center">
                                                        Opciones
                                                    </th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                <?php if (!empty($recibidos)): ?>

                                                    <?php foreach ($recibidos as $recibido): ?>

                                                        <?php
                                                            if ($recibido->estado == 2) {
                                                                $text_estado = 'Pendiente';
                                                                $label_class = 'label-danger';
                                                            } elseif ($recibido->estado == 1) {
                                                                $text_estado = 'Proceso';
                                                                $label_class = 'label-warning';
                                                            } else {
                                                                $text_estado = 'Solucionado';
                                                                $label_class = 'label-success';
                                                            }
                                                        ?>

                                                        <tr>

                                                            <td>
                                                                <?= $recibido->id_ocurrencia ?>
                                                            </td>

                                                            <td>
                                                                <?= $recibido->usuario ?>
                                                            </td>

                                                            <td>
                                                                <?= $recibido->nombre_area ?>
                                                            </td>

                                                            <td>
                                                                <?= $recibido->fecha_problema ?>
                                                            </td>

                                                            <td>
                                                                <?= $recibido->fecha_registro ?>
                                                            </td>

                                                            <td>
                                                                <?= $recibido->fecha_finalizado ?>
                                                            </td>

                                                            <td style="text-align:center">

                                                                <span class="label <?= $label_class ?>">
                                                                    <?= $text_estado ?>
                                                                </span>

                                                            </td>

                                                            <td>

                                                                <div class="btn-footer text-center">

                                                                    <button class="btn btn-warning"
                                                                            onclick="edit_recibidos(<?= $recibido->id_ocurrencia ?>)">

                                                                        <i class="glyphicon glyphicon-pencil"></i>

                                                                    </button>

                                                                </div>

                                                            </td>

                                                        </tr>

                                                    <?php endforeach; ?>

                                                <?php endif; ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

<script type="text/javascript">

    function reload_table()
    {
        table.ajax.reload(null, false);
    }

    function save()
    {
        let url = '';

        if (save_method === 'update')
        {
            url = "<?= site_url('mantenimiento/recibidos/recibido_update') ?>";
        }

        $.ajax({

            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",

            success: function(data)
            {
                $('#modal_form').modal('hide');

                swal(
                    'Good job!',
                    'Data has been save!',
                    'success'
                );

                location.reload();
            },

            error: function(jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }

        });
    }

    function edit_recibidos(id)
    {
        save_method = 'update';

        $('#form')[0].reset();

        $.ajax({

            url: "<?= site_url('mantenimiento/recibidos/recibidos_edit') ?>/" + id,

            type: "GET",

            dataType: "JSON",

            success: function(data)
            {
                $('[name="id_ocurrencia"]').val(data.id_ocurrencia);

                $('[name="estado"]').val(data.estado);

                $('[name="fecha_finalizado"]').val(data.fecha_finalizado);

                $('#modal_form').modal('show');

                $('.modal-title').text('Editar ocurrencia');
            },

            error: function(jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }

        });
    }

</script>

<!-- Modal -->

<div class="modal fade"
     id="modal_form"
     role="dialog">

    <div class="modal-dialog">

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

                <h3 class="modal-title">
                    ÁREA DE TRABAJO
                </h3>

            </div>

            <div class="modal-body form">

                <form action="#"
                      id="form"
                      class="form-horizontal"
                      method="post">

                    <input type="hidden"
                           value=""
                           name="id_ocurrencia">

                    <div class="form-body">

                        <div class="form-group">

                            <label class="control-label col-md-3">
                                Estado solución :
                            </label>

                            <div class="col-md-6">

                                <select name="estado"
                                        class="form-control">

                                    <option value="2">
                                        Pendiente
                                    </option>

                                    <option value="1">
                                        Procesado
                                    </option>

                                    <option value="0">
                                        Solucionado
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="control-label col-md-3">
                                Fecha solución:
                            </label>

                            <div class="col-md-6">

                                <div class="input-group"
                                     id="divMiCalendario">

                                    <input class="form-control"
                                           type="text"
                                           name="fecha_finalizado">

                                    <span class="input-group-addon">

                                        <span class="glyphicon glyphicon-calendar"></span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button"
                        id="btnSave"
                        onclick="save()"
                        class="btn btn-primary">

                    Guardar

                </button>

                <button type="button"
                        class="btn btn-danger"
                        data-dismiss="modal">

                    Cancelar

                </button>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">

    $('#divMiCalendario').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#divMiCalendario')
        .data("DateTimePicker")
        .show();

</script>


