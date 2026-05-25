    <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                Tipo problema
                <small>/ Listado</small>
                </h1>
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="box box-solid">
                    <div class="box-body">
                         <div class="row">
                                <div class="col-md-12">
                                    <button  class="btn btn-success" onclick="add_problema()"><span class="glyphicon glyphicon-plus"></span>  Agregar</button>
                                </div>
                        </div>
                    
                     <hr>
                         <div class="row">
                                <div class="col-xs-12">
                                   <div class="box-body table-responsive no-padding">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 1%; text-align: center">Código</th>
                                                <th style="width: 2%; text-align: center">Nombre</th>
                                                 <th style="width: 2%; text-align: center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($problema)):?>
                                                <?php foreach($problema as $problemas):?>
                                            <tr>
                                                <td><?php echo $problemas->id_tipo_problema;?></td>
                                                <td><?php echo $problemas->tipo_nombre;?></td>
                                               
                                                <td>
                                                    <div class="btn-footer text-center">

                                                        <button class="btn btn-warning" onclick="edit_problema(<?php echo $problemas->id_tipo_problema;?>)">
                                                            <i class="glyphicon glyphicon-pencil"></i></button>

                                                        <button class="btn btn-danger btn-remove" onclick="delete_rol(<?php echo $problemas->id_tipo_problema;?>)">
                                                         <i class="glyphicon glyphicon-trash"></i></button>
                                                        <!-- <a onclick="if(confirma()==false) return false"  href="<?php echo base_url()?>mantenimiento/Area/delete/<?php echo $areas->id_area;?>" class="btn-sm btn-danger btn-remove"><i class="glyphicon glyphicon-trash"></i>Desactivar</a> -->

                                                       <!--  <a onclick=href="#" class="btn-sm btn-info"><i class="fa fa-eye"></i>View</a> -->
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                    
                                </div>
                              </div>
                         </div>
                     
                  
                     </div>
             </div>
                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
<script type="text/javascript">
    
    function add_problema()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }

     function save()
    {
      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('mantenimiento/problema/problema_add')?>";
      }
      else
      {
        url = "<?php echo site_url('mantenimiento/problema/problema_update')?>";
      }

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
               $('#modal_form').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }

     function edit_problema(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('mantenimiento/problema/problema_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_tipo_problema"]').val(data.id_tipo_problema);
            $('[name="tipo_nombre"]').val(data.tipo_nombre);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Book'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }


    function delete_rol(id)
    {
      if(confirm('Desea desactivar rol?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('rol/anularRol')?>",
            type: "POST",
            data: { id: id },
            dataType: "JSON",
            success: function(data)
            {
               
               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error Desactivación');
            }
        });

      }
    }
</script>

<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">ROL CSP</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="id_tipo_problema"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Nombre :</label>
              <div class="col-md-9">
                <input name="tipo_nombre" placeholder="nombre" class="form-control" type="text">
              </div>
            </div>
           </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
  
        


<script type="text/javascript">
function confirma(){
 if (confirm("¿Realmente desea eliminarlo?")){ 
 alert("El registro ha sido eliminado.") }
 else { 
 return false
 }
}
</script>
