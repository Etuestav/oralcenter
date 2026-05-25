





<div class="content-wrapper">
   <section class="content-header">
               
            </section>
   <section class="content">
    
      <div class="box-body">
        <div  class="col-md-12">
    <div class="row">
    <div class="col-md-10 col-md-offset-1">

       <div class="panel panel-primary">
      <div class="panel-heading"> 
             <h3 style="text-align: center;">REGISTRAR ESTADO TRAMAS ELECTRONICAS</h3> </div>
         <div class="panel-body">
             <form  action="<?php echo base_url();?>tramas/sistemas/registrartrama/insertar" method="POST" autocomplete="off" class="form-horizontal"  >

              <div class="form-group">
                      <label class="col-md-4 control-label">Lote: </label>
                         <div class="col-md-6">
                          <input type="text" id="lote" name="lote" class="form-control"  placeholder="Escriba su lote"  required="Ingresar contenido"></input>
                             <!--  <input type="text" name="nombre" class="form-control" row="5" required=""> -->
                          </div>
                </div>

                <div class="form-group">
                      <label class="col-md-4 control-label">Aseguradora :</label>
                         <div class="col-md-6">
                          
                          <select name="aseguradora" class="form-control">
                     <option value="PEPS">PACIFICO EPS</option>
                      <option value="PPPS">PACIFICO SEGUROS Y REASEGUROS</option>
                      <option value="PSCTR">PACIFICO SCTR</option>
                      <option value="REPS">RIMAC EPS</option>
                      <option value="RPPS">RIMAC SEGUROS Y REASEGUROS</option>
                      <option value="MEPS">MAPFRE EPS</option>
                       <option value="MEPS">MAPFRE SEGUROS</option>
                      <option value="MPPS">MAPFRE SCTR</option>
                      <option value="FEPS">FEBAN</option>
                      <option value="SEPS">POSITIVA SANITAS</option>

                            
                      </select>

                          </div>
                </div>

                 <div class="form-group">
                      <label class="col-md-4 control-label">Cantidad Facturas: </label>
                         <div class="col-md-6">
                          <input type="text" id="cantidad_factura" name="cantidad_factura" class="form-control"  placeholder="Escriba su cantidad"  required="Ingresar contenido"></input>
                            
                          </div>
                </div>
                 <div class="form-group">
                      <label class="col-md-4 control-label">Usuario :</label>
                       <div class="col-md-6">
                    <select name="id_usuario" id="id_usuario" class="form-control">
                      <option value="No">--Seleccionar usuario--</option>
                      <?php foreach ($user as $users):?>
                      <option value="<?php echo $users->id_usuario?>">
                        <?php echo $users->apellidos;?>
                        
                      </option>
                    <?php endforeach;?>
                    </select>
                <!-- <input name="id_area" placeholder="area" class="form-control" type="text"> -->
              </div>
                      
                </div>
                <div class="form-group">
              <label class="col-md-4 control-label" for="area">Area :</label>
              <div class="col-md-6">
                    <select name="id_area" id="id_area" class="form-control">
                         <option value="No">--Seleccionar area--</option>
                      <?php foreach ($area as $areas):?>
                      <option value="<?php echo $areas->id_area?>">
                        <?php echo $areas->nombre_area;?>
                        
                      </option>
                    <?php endforeach;?>
                    </select>
                <!-- <input name="id_area" placeholder="area" class="form-control" type="text"> -->
              </div>
            </div>

            

             
              
                 <div class="form-group">
                      <label class="col-md-4 control-label">Observación :</label>
                         <div class="col-md-6">
                          <textarea id="observacion" name="observacion" class="form-control" rows="5" placeholder="Escriba su observacion"  required="Ingresar contenido"></textarea>
                             <!--  <input type="text" name="nombre" class="form-control" row="5" required=""> -->
                          </div>
                </div>
                

             <div class="form-group">
            <label  class="col-md-4 control-label">
              Fecha creación:
                        </label>
            <div class="col-md-6">  
            <input type="date" name="fecha_llegada" id="datetimepicker" 
                          class="form-control" required="">
              </div>
            </div>

            <div class="form-group">
            <label  class="col-md-4 control-label" style = "visibility:hidden">
              Fecha finalizado:
                        </label>
            <div class="col-md-6">  
            <input type="date" name="fecha_solucionado" id="datepicker" 
                          class="form-control"  style = "visibility:hidden">
              </div>
            </div>

               
             <div class="col-md-6 col-md-offset-4">
            <button type="submit"   class="btn btn-primary"> Enviar 
                        </button> 
            <a type="button" class="btn btn-default" 
                          href="<?php echo base_url();?>Dashboard"> Cancelar </a>
             </div>
            </form>
           </div>
              </div>
        </div>
    </div>
  </div>
      </div>
  
                    
    
   </section>
  
  
</div>





