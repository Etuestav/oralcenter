<h2 class="text-center">Plan de Tratamiento</h2>

<br>
<div class="w100">
	<div class="w50">
		<div class="w100">
			<b>Especialidad:</b> <?= $tratamiento->nombre_especialidad ?> <br>
		</div>
		<div class="w100">
			<b>Doctor:</b> <?= $tratamiento->nomb_med.' '.$tratamiento->apel_med ?> <br>
		</div>
		<div class="w100">
			<b>Paciente:</b> <?= $tratamiento->nomb_med.' '.$tratamiento->nomb_med ?> <br>
		</div>
		<div class="w100">
			<b>Asunto:</b> <?= $tratamiento->asunto_tra ?> <br>
		</div>
	</div>
	<div class="w50">
		<div class="w100">
			<b>Fecha creación:</b> <?= $tratamiento->fecha_tra ?> <br>
		</div>
	</div>
</div>
<br>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Descripción</th>
			<th>Cant.</th>
			<th>Precio</th>
			<th>Desc.</th>
			<th>Subtotal</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tratamiento->detalle as $t): ?>
		<tr>
			<td><?= $t->nombre ?></td>
			<td><?= $t->cant_tradet ?></td>
			<td><?= $t->preciounit_tradet ?></td>
			<td><?= $t->descuento_tradet ?>%</td>
			<td><?= $t->subtotal_tradet ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4" class="text-right">Total</th>
			<th><?= $tratamiento->total_tra ?></th>
		</tr>
	</tfoot>
</table>