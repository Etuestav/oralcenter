<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-th-list" aria-hidden="true"></i> Areas
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Módulo</a></li>
      <li><a href="#">Mantenimiento</a></li>
      <li class="active">Areas</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <button type="button" class="btn btn-success btn-sm" id="BtnNuevaArea">
              <i class="fa fa-plus"></i> Nueva area
            </button>
            <a href="<?= base_url('area') ?>" class="btn btn-default btn-sm">
              <i class="fa fa-refresh"></i> Actualizar
            </a>
          </div>
        </div>

        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="TableMantenimientoArea" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr class="info">
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Código</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Nombre</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Descripción</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (($area ?? []) as $areas): ?>
                    <tr>
                      <td style="text-align: center;"><?= esc($areas->id_area) ?></td>
                      <td><?= esc($areas->nombre_area) ?></td>
                      <td><?= esc($areas->descripcion_area ?? '') ?></td>
                      <td style="text-align: center;">
                        <button type="button" class="btn btn-warning btn-xs editar-area" data-id="<?= esc($areas->id_area) ?>">
                          <i class="fa fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs anular-area" data-id="<?= esc($areas->id_area) ?>">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="ModalArea" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form id="FormArea" autocomplete="off">
        <input type="hidden" name="id_area">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Area</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label">Nombre</label>
            <input type="text" name="nombre_area" class="form-control input-sm" required>
          </div>
          <div class="form-group">
            <label class="control-label">Descripción</label>
            <textarea name="descripcion_area" class="form-control input-sm" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">
            <i class="fa fa-close"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-info">
            <i class="fa fa-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(function () {
  let modoArea = 'nuevo';

  $('#TableMantenimientoArea').DataTable({
    language: {
      url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json'
    },
    order: [[0, 'desc']]
  });

  $('#BtnNuevaArea').on('click', function () {
    modoArea = 'nuevo';
    $('#FormArea')[0].reset();
    $('#FormArea input[name=id_area]').val('');
    $('#ModalArea .modal-title').text('Nueva area');
    $('#ModalArea').modal('show');
  });

  $('#TableMantenimientoArea').on('click', '.editar-area', function () {
    modoArea = 'editar';
    const id = $(this).data('id');

    $.getJSON(base_url + 'area/editar/' + id, function (data) {
      $('#FormArea input[name=id_area]').val(data.id_area);
      $('#FormArea input[name=nombre_area]').val(data.nombre_area);
      $('#FormArea textarea[name=descripcion_area]').val(data.descripcion_area);
      $('#ModalArea .modal-title').text('Editar area');
      $('#ModalArea').modal('show');
    });
  });

  $('#FormArea').on('submit', function (event) {
    event.preventDefault();

    const url = modoArea === 'nuevo'
      ? base_url + 'area/nuevo'
      : base_url + 'area/update';

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'JSON',
      data: $(this).serialize(),
      success: function (data) {
        if (data.status) {
          $('#ModalArea').modal('hide');
          location.reload();
          return;
        }

        Swal.fire('Error', 'Debe completar los campos obligatorios.', 'error');
      },
      error: function () {
        Swal.fire('Error', 'No se pudo guardar el area.', 'error');
      }
    });
  });

  $('#TableMantenimientoArea').on('click', '.anular-area', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: 'Confirmar',
      text: 'Desea desactivar el area?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then(function (result) {
      if (!result.value) {
        return;
      }

      $.ajax({
        url: base_url + 'area/eliminar/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
          if (data.status) {
            location.reload();
          }
        },
        error: function () {
          Swal.fire('Error', 'No se pudo desactivar el area.', 'error');
        }
      });
    });
  });
});
</script>
