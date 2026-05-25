<?php foreach ($comprobantes as $c): ?>
<table class="table table-bordered">
	<thead>
    <tr>
    	<?php if ($_GET['estado']=='Emitido' OR $_GET['estado']=='Anulado'): ?>
      <th style="background: #222d32;color:white">Comprobante</th>
    	<?php endif ?>
      <th style="background: #222d32;color:white">Fecha de Registro</th>
      <?php if ($_GET['estado']=='Por Cobrar'): ?>
      <th style="background: #222d32;color:white">Fecha de Vencimiento</th>
      <?php endif ?>
      <?php if ($_GET['estado']=='Emitido' OR $_GET['estado']=='Anulado'): ?>
      <th style="background: #222d32;color:white">Tipo</th>
      <?php endif ?>
      <th style="background: #222d32;color:white">Paciente</th>
      <th colspan="2" style="background: #222d32;color:white">Estado</th>
      <?php if ($_GET['estado']=='Por Cobrar'): ?>
      <th style="background: #222d32;color:white">Total</th>
      <?php endif ?>
    </tr>
  </thead>
  <tbody>
  	<tr>
  		<?php if ($_GET['estado']=='Emitido' OR $_GET['estado']=='Anulado'): ?>
  		<td><?= $c->serie_com.'-'.$c->secuencia_com ?></td>
  		<?php endif ?>
  		<td><?= $c->fecharegistro_pago ?></td>
  		<?php if ($_GET['estado']=='Por Cobrar'): ?>
  		<td><?= $c->fechavencimiento_pago ?></td>
  		<?php endif ?>
  		<?php if ($_GET['estado']=='Emitido' OR $_GET['estado']=='Anulado'): ?>
  		<td><?= $c->descripcion ?></td>
  		<?php endif ?>
  		<td><?= $c->NombresApellidos ?></td>
  		<td colspan="2"><?= $_GET['estado'] ?></td>
  		<?php if ($_GET['estado']=='Por Cobrar'): ?>
  		<td><?= $c->monto_pago_acumulado ?></td>
  		<?php endif ?>
  	</tr>
		<?php if ($_GET['estado']=='Emitido' OR $_GET['estado']=='Anulado'): ?>
			
		<tr>
			<th style="background: #3c8dbc;color:white">Código</th>
			<th style="background: #3c8dbc;color:white">Procedimiento</th>
			<th style="background: #3c8dbc;color:white">Prec. Unit.</th>
			<th style="background: #3c8dbc;color:white">Cant.</th>
			<th style="background: #3c8dbc;color:white">Dsct.</th>
			<th style="background: #3c8dbc;color:white">Subtotal</th>
		</tr>
		<?php $subtotalacumulado = 0; ?>
		<?php foreach ($c->comprobante->procedimientos as $p): ?>
		<?php
			$impuesto = $c->comprobante->igvparam_com;
			$precio_unitario = $p->precio_unitario / $c->comprobante->num;
			$precio_unitario = ($precio_unitario * 100) / ($impuesto + 100);
			$subtotal = $precio_unitario * $p->cant_tradet;
			$subtotalacumulado += $subtotal;
		 ?>
		<tr>
			<td><?= $p->id_procedimiento ?></td>
			<td><?= $p->nombre ?></td>
			<td><?= round($precio_unitario,2) ?></td>
			<td><?= $p->cant_tradet ?></td>
			<td><?= $p->descuento_tradet ?>%</td>
			<td><?= round($subtotal,2) ?></td>
		</tr>
		<?php endforeach ?>
		<tr>
			<td colspan="4"></td>
			<td><b>Subtotal</b></td>
			<td><?= round($subtotalacumulado,2) ?></td>
		</tr>
		<tr>
			<td colspan="4"></td>
			<td><b>IGV</b></td>
			<td><?= ($impuesto > 0)?round(($subtotalacumulado*($impuesto/100)),2):'0.00' ?></td>
		</tr>
		<tr>
			<td colspan="4"></td>
			<td><b>Total</b></td>
			<td><?= $c->monto_pago_acumulado ?></td>
		</tr>
		<?php endif ?>
  </tbody>
</table>
<?php endforeach ?>