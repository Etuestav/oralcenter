<p>
	<b>Fecha Hora:</b> <?= date('d/m/Y',strtotime($cita->fech_cit)) ?> - <?= date('h:i a',strtotime($cita->fech_cit)) ?> <br>
	<b>Médico:</b> <?= $cita->nomb_med.' '.$cita->apel_med ?> <br>

	<b>Estado:</b> <?= $cita->nomb_citado ?> <br>
	
	
</p>