
 

   
    

  






   


<div class="content-wrapper">
   <section class="content-header">
               
   </section>
   <section class="content">
    
      <div class="box-body">
        <div class="col-md-14">
          <div class="row">
            <div class="col-md-12 col-md-offset-0">
              <div class="panel panel-info">
                 <div class="panel-heading text-center" > 
                 <h3>PROCESAR TRAMAS ELECTRONICAS</h3> 
                </div>
             <div class="panel-body">
             
            
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box-body table-responsive no-padding">
                           <table id="example1" class="table table-bordered table-hover">
                             <thead>
                                <tr>
                                    
                                    <th style="width: 5%;text-align: center">Lote</th>
                                    <th style="width: 1%;text-align: center">Cantidad</th> 
                                    <th style="width: 12%;text-align: center">Usuario</th>
                                    <th style="width: 14%;text-align: center">Area</th>
                                    <th style="width: 12%;text-align: center">Fecha creación</th>  
                                    <th style="width: 14%;text-align: center">Fecha Solucionado</th>
                                      <th style="width: 9%;text-align: center">Estado</th>
                                       <th style="width: 10%;text-align: center">Opciones</th>
                                </tr>
                             </thead>
                       <tbody>
                     <?php if(!empty($listar)):?>
                                  <?php foreach($listar as $list):?>
                                         <tr>
                                            
                                             <td><?php echo $list->lote;?></td>
                                              <td style="text-align: center"><?php echo $list->cantidad_factura;?></td>
                                             <td><?php echo $list->usuario;?></td>
                                              <td><?php echo $list->nombre_area;?></td>
                                               <td><?php echo $list->fecha_llegada;?></td>
                                              <td><?php echo $list->fecha_solucionado;?></td>
                                              <td style="text-align: center;"><?php if($list->estado == 'P'){$text_estado="Pendiente";$label_class='label-danger';} else if($list->estado == 'E') {$text_estado="Enviado aseguradora";$label_class='label-warning';}else if($list->estado == 'R') {$text_estado="Rechazo aseguradora";$label_class='label-info';}   else {$text_estado="Aceptado aseguradora";$label_class='label label-success';}?>
                                                    <span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span>
                                                  </td>
                                                   
                                                  
                                                <td>
                                                    <div class="btn-footer text-center">

                                                   <button class="btn btn-warning" onclick="edit_lista(<?php echo $list->secuencia;?>)">
                                                           <i class="glyphicon glyphicon-pencil"></i> </button>

                                                        
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
        </div>
    </div>
  </div>
      </div>
  
                    
    
   </section>
  
  
</div>

<script type="text/javascript">


     function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

     function save()
    {
      var url;
      if(save_method == 'update')
      {
           url = "<?php echo site_url('tramas/sistemas/listartrama/listar_update')?>";
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
              //  $('#modal_form').modal('hide');
              // location.reload();// for reload a page
               $('#modal_form').modal('hide');
              

               swal(
                'Good job!',
                'Data has been save!',
                'success',

                )
                location.reload();
               // location.reload();

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }

     function edit_lista(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('tramas/sistemas/listartrama/listar_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="secuencia"]').val(data.secuencia);
            $('[name="cantidad_factura"]').val(data.cantidad_factura);
            $('[name="estado"]').val(data.estado);
            $('[name="fecha_solucionado"]').val(data.fecha_solucionado);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('EDITAR PROCESO'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }



</script>


  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">AREA DE TRABAJO</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal" method="post">
          <input type="hidden" value="" name="secuencia"/>
          <div class="form-body">
            <div class="form-group">
                <label class="control-label col-md-3">Estado solucion :</label>
                <div class="col-md-6">
                   <select name="estado" class="form-control">
                     <option value="P">Pendiente</option>
                      <option value="E">Enviado aseguradora</option>
                      <option value="R">Rechazado aseguradora</option>
                      <option value="S">Aceptado aseguradora</option>
                   </select>
               </div>
            </div>

             <div class="form-group">
              <label class="control-label col-md-3">Cantidad :</label>
              <div class="col-md-6">
                <input name="cantidad_factura" placeholder="" class="form-control" type="text">
              </div>
            </div>

           <div class="form-group row">
              <label   class="control-label col-md-3"> Fecha solución:  </label>
               <div class="col-md-6"> 
                <div class='input-group' id='divMiCalendario'>
                  <input class="form-control" type='text' name="fecha_solucionado" />
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                      </span>
                </div>

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
       $('#divMiCalendario').datetimepicker({
          format: 'YYYY-MM-DD HH:mm:ss',
          
      });
   
      $('#divMiCalendario').data("DateTimePicker").hide();

  </script>
 


