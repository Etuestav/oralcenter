
<div style="font-size: 12px">

	<div class="w100">
		<div class="w20">
			<img style="max-width: 90px"src="<?= base_url('vendor/uploads/logo/'.session()->get('foto')) ?>">
		</div>
		<div class="w60 text-center">
		<h3 style=" font-weight: bold; padding-top: 30px;">HISTORIA CLINICA</h3>
	
	</div>
		<!-- <h3 style="text-align: center; font-weight:bold; ">
		HISTORIA ODONTOLOGICA
		</h3> -->
		
	</div>
	<br>
	<br>

	<div class="w100">
			<div style="padding: 0px">
			
			<div style="border:1px solid #070707; padding:3px" height="20">

				H.C: <?= $historia->codi_pac ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha impresión: <?= date('d/m/Y   h:i:s')?>  
			</div>

		</div>

		<div style="padding: 0px; padding-top: 2px">
			
			<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
				<label>
					ANAMNESIS
				</label>
					
				
						
				

			
			
			</div>

		</div>

		<div style="padding: 0px; padding-top: 2px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				I. AFILIACION
					
			</div>

		</div>

			<div>
			
			<div style="border:1px solid #070707; padding:3px; font-size: 12;" height="80">
				<b>Apellidos y Nombres:</b> <?= $historia->paciente ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Edad:</b> <?= $historia->edad ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>Sexo:</b> <?= $historia->sexo ?><br>
				<b>D.N.I:</b> <?= $historia->dni ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Teléfono:</b> <?=$historia->telefono ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Email:</b> <?=$historia->email ?><br>
				<b>País:</b>  <?=$historia->pais ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Departamento: </b> <?=$historia->departamento ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Provincia: </b> <?=$historia->provincia ?> <br>
				<b>Distrito: </b> <?=$historia->distrito ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Lugar de nacimiento: </b> <?=$historia->lugarnacimiento ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fech.nacimiento: </b> <?=$historia->fechanacimiento ?><br>
				<b>Dirección:</b> <?=$historia->direccion ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Grado de Instrucción:</b> <?=$historia->estudios ?><br>
				<b>Ocupación:</b> <?=$historia->ocupacion ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Estado civil:</b> <?=$historia->civil ?><br>
				<b>Como se entero de la clinica: </b> <?=$historia->entero ?>

		
					
			</div>

		</div>



		<div>
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				II. ENFERMEDAD ACTUAL
					
			</div>

		</div>

	<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Motivo consulta: 
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <?=$historia->motivo ?> 
			</div>

			</div>
		</div>
	</div>

	<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Tiempo de la enfermedad:
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		   <?= $historia->enfermedad ?> 
			</div>

			</div>
		</div>
	</div>

	<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Relato:
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <!--  <?= $historia->codi_pac ?>  -->
			</div>

			</div>
		</div>
	</div>


	<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Esta tomando algun medicamento:
			</div>

			</div>
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				<?= $historia->medicamento ?>
		  <!--  <?= $historia->codi_pac ?> 
		   <?php foreach ($historia->alergias as $a): ?>
		   	<?= $a->pacale_observacion ?>
		   <?php endforeach ?> -->
			</div>

			</div>
		</div>
	<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Nombre del medicamento:
		  <!--  <?= $historia->codi_pac ?> 
		   <?php foreach ($historia->alergias as $a): ?>
		   	<?= $a->pacale_observacion ?>
		   <?php endforeach ?> -->
			</div>

			</div>
		</div>

			<div class="w50">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				
				<?= $historia->nombmedi ?>
		  <!--  <?= $historia->codi_pac ?> 
		   <?php foreach ($historia->alergias as $a): ?>
		   	<?= $a->pacale_observacion ?>
		   <?php endforeach ?> -->
			</div>

			</div>
		</div>
	</div>


		<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Via de uso:
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <!--  <?= $historia->codi_pac ?>  -->
			</div>

			</div>
		</div>
	</div>

		<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Dosis:
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <!--  <?= $historia->codi_pac ?>  -->
			</div>

			</div>
		</div>
	</div>

	<div>
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				III. ANTECEDENTES
					
			</div>

	</div>

		<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Personales: 
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <?=$historia->antecpersonales ?> 
			</div>

			</div>
		</div>
	</div>


		<div>
	

		<div class="w20">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
				Familiares: 
			</div>

			</div>
		</div>
		<div class="w80">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="50">
		  <?=$historia->antecfamiliares ?> 
			</div>

			</div>
		</div>
	</div>


<!--CONSULTA DE SALUD  -->
	<div>
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				IV. CONSULTA DE SALUD
					
			</div>


		<div>
	
<!--------CABECERA----------->

		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				Nro.
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				¿Pregunta?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				Respuesta
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
			 Comentario
			</div>

			</div>
		</div>
	</div>		

<!--------------------------->
<!------PREGUNTA ORTODONCIA------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				1 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				¿Ha tenido alguna vez tratamiento de ortodoncia?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
					<?=$historia->consulortodoncia?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta1 ?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>


<!------PREGUNTA TOMA MEDICAMENTO------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				2 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				¿Esta tomando algun medicamento?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
					<?=$historia->consutmedicamento?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta2 ?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>


<!------PREGUNTA ALERGICO------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				3 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				¿Es alergico algun medicamento o anestesico?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
					<?=$historia->consulalergico?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta3 ?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>

<!------PREGUNTA HOSPITALIZADO------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				4 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px;" height="20">
				¿Has estado hospitalizado o tenido una cirugia?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
					<?=$historia->consulhospi?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta4 ?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>

<!------PREGUNTA TRANSTORNO EMOCIONAL------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				5 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				¿Ha tenido algun transtorno nervioso o emocional?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
					<?=$historia->consultranstorno?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta5 ?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>

<!------PREGUNTA TRANSTORNO EMOCIONAL------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				6 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px;" height="20">
				¿Padece o a padecido alguna enfermedad?
			</div>

	
		</div>


		<div class="w40">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->padece?>
			</div>

			</div>
		</div>
	</div>
<!------FIN PREGUNTA------------>

<!------PREGUNTA CEPILLA LOS DIENTES------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				7 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px;" height="20">
				¿Cepilla los dientes diariamente?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->consulcepilla?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta6?>
			</div>

			</div>
		</div>
	</div>
	<!------FIN PREGUNTA------------>

	<!------PREGUNTA CEPILLA LOS DIENTES------------>
	<div>
	


		<div class="w5">
	
			
			<div style="border:1px solid #070707; padding:3px; text-align: center;" height="20">
				8 
			</div>

	
		</div>

			<div class="w55">
	
			
			<div style="border:1px solid #070707; padding:3px;" height="20">
				¿Sufre de presión arterial alta?
			</div>

	
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->consulpresion?>
			</div>

			</div>
		</div>

		<div class="w30">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				<?=$historia->respuesta7?>
			</div>

			</div>
		</div>
	</div>
	<!------FIN PREGUNTA------------>

	</div>

<!--FIN CONSULTA DE SALUD  -->
<!--------------------------->

	<!--EXAMEN CLINICO  -->
	<div style="padding: 0px; padding-top: 10px">
			
			<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
				<label>
					EXAMEN CLINICO
				</label>
			</div>

	</div>

		<div style="padding: 0px; padding-top: 2px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				1. FUNCIONES VITALES
					
			</div>

		</div>

			<div>
	

		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Presión arterial:		
			</div>

			</div>
		</div>
		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		   <?= $historia->exploracion ?> 
			</div>

			</div>
		</div>

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    mn Hg
			</div>

			</div>
		</div>

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Pulso:		
			</div>

			</div>
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    <?= $historia->pulso ?> 
			</div>

			</div>
		</div>

		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    /min
			</div>

			</div>
		</div>
		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Temperatura:		
			</div>

			</div>
		</div>
		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		   <?= $historia->temperatura ?> 
			</div>

			</div>
		</div>

		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    °C
			</div>

			</div>
		</div>

	</div>


			<div>
	

		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Frec. cardiaca:		
			</div>

			</div>
		</div>
		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    <?= $historia->fcardiaca ?> 
			</div>

			</div>
		</div>

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    x min.
			</div>

			</div>
		</div>

		<div class="w15">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Frec. Respiratoria:		
			</div>

			</div>
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    <?= $historia->frespiratoria ?>
			</div>

			</div>
		</div>

		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    /min
			</div>

			</div>
		</div>



	</div>
      
	<div style="padding: 0px; padding-top: 0px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				2. PESO - TALLA
					
			</div>

   </div>

   		<div>
	

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Peso:		
			</div>

			</div>
		</div>
		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		   <?= $historia->peso ?>  
			</div>

			</div>
		</div>

		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    Kg.
			</div>

			</div>
		</div>

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				Talla:		
			</div>

			</div>
		</div>
		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    <?= $historia->talla ?>  
			</div>

			</div>
		</div>

		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    m.
			</div>

			</div>
		</div>
		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
				IMC:		
			</div>

			</div>
		</div>
		<div class="w5">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    <?= $historia->imc ?>  
			</div>

			</div>
		</div>

		<div class="w10">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="20">
		    Kg/m.
			</div>

			</div>
		</div>

	</div>



	<div style="padding: 0px; padding-top: 0px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				3. EXAMEN CLINICO GENERAL
					
			</div>

   </div>

   	<div>
	

		<div class="w100">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="60">
				 <?= $historia->exmclinico ?>  
			</div>

			</div>
		</div>
	
	</div>

	<div style="padding: 0px; padding-top: 0px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				4. EXAMEN COMPLEMENTARIO
					
			</div>

   </div>

   	<div>
	

		<div class="w100">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="60">
				<?= $historia->exmcomplet ?>  
			</div>

			</div>
		</div>
	
	</div>

	<div style="padding: 0px; padding-top: 0px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
				5. ODONTOESTOMATOLOGICO
					
			</div>

   </div>

   	<div>
	

		<div class="w100">
			<div>
			
			<div style="border:1px solid #070707; padding:3px" height="60">
					<?= $historia->exmodonto ?>  
			</div>

			</div>
		</div>
	
	</div>





</div>
	




<div style="padding: 0px; padding-top: 210px">
			
			<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
				<label>
					ODONTOGRAMA INICIAL
				</label>
			</div>

</div>

   	<div>
	

		<div class="w100">
			<div style="padding: 0px; padding-top: 5px">
			
			<div style="border:1px solid #070707; padding:3px; text-align:center">
				<?php if (! empty($odontogramaInicialImagen)): ?>
					<img class="odontograma-pdf-img" alt="Odontograma inicial" src="<?= esc($odontogramaInicialImagen) ?>">
				<?php else: ?>
					<div style="padding: 18px; text-align:center">Odontograma no capturado.</div>
				<?php endif; ?>

			</div>

			</div>
		</div>
	
		<br>
			<div style="padding: 0px; padding-top: 5px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
			- ESTADO DE LAS PIEZAS DENTALES (ODONTOGRAMA INICIAL)
					
			</div>

 		  </div>

 		  <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 240px;">Descripción</th>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 75px;">Pieza</th>
				<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 92px;">Pieza Final</th>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 120px">Estado</th>
				<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 145px">Observación</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($historia->odinicial as $o): ?>
		<tr>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_sigla.' '. $o->nombre_hal?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->numero_die ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_dientefinal ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_estado ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_espec ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
	
	</div>
	

	<div style="padding: 0px; padding-top: 0px">
			
			<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
				<label>
					ODONTOGRAMA EVOLUCIONADO
				</label>
			</div>

   </div>
	   	<div>

		<div class="w100">
			<div style="padding: 0px; padding-top: 5px">
			
			<div style="border:1px solid #070707; padding:3px; text-align:center">
				<?php if (! empty($odontogramaEvolucionadoImagen)): ?>
					<img class="odontograma-pdf-img" alt="Odontograma evolucionado" src="<?= esc($odontogramaEvolucionadoImagen) ?>">
				<?php else: ?>
					<div style="padding: 18px; text-align:center">Odontograma no capturado.</div>
				<?php endif; ?>

			</div>

			</div>
		</div>
	
		<br>
			<div style="padding: 0px; padding-top: 5px">
			
			<div style="border:1px solid #070707; padding:3px; font-size: 14;" height="20">
			- ESTADO DE LAS PIEZAS DENTALES (ODONTOGRAMA EVOLUCIONADO)
					
			</div>

 		  </div>

 		  <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 240px;">Descripción</th>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 75px;">Pieza</th>
				<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 92px;">Pieza Final</th>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 120px">Estado</th>
				<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 145px">Observación</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($historia->evolucionado as $o): ?>
		<tr>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_sigla.' '. $o->nombre_hal?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->numero_die ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_dientefinal ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_estado ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $o->pacodo_espec ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

	</div>


   <div style="padding: 0px; padding-top: 20px">
			
			<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
				<label>
					ALERGIAS
				</label>
			</div>

	</div>

	<table class="table table-bordered">
	<thead>	
		<tr>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center;">NOMBRE</th>
			<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center;">OBSERVACION</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($historia->alergias as $a): ?>
		<tr>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $a->pacale_observacion ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $a->nombre_ale ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>


		    <div style="padding: 0px; padding-top: 20px">
					
					<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
						<label>
							DIAGNOSTICO
						</label>
					</div>

			</div>

					<table class="table table-bordered">
					<thead>	
						<tr>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 100px;">SIGLAS</th>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 380px;">CIE10</th>
				
						</tr>
					</thead>
					<tbody>
						<?php foreach ($historia->pacdiagnostico as $dg): ?>
						<tr>
							<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dg->codi_enf01 ?></td>
							<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dg->desc_enf ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>


			  <div style="padding: 0px; padding-top: 0px">
					
					<div style="border:1px solid #070707; padding:3px; text-align: center; font-weight:bold; font-size: 16; background-color: #B5ADAD;" height="20">
						<label>
							EVOLUCIÓN
						</label>
					</div>

			</div>

					<table class="table table-bordered">
					<thead>	
						<tr>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 100px;">FECHA</th>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 230px;">DESCRIPCION DE EVOLUCION</th>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 115px;">ESPECIALIDAD</th>
							<th style="border:1px solid #070707; padding: 6px; background-color: #B5ADAD; text-align: center; width: 180px;">DOCTOR</th>
						
						</tr>
					</thead>
					<tbody>
						<?php foreach ($historia->evolucion as $ev): ?>
						<tr>
							<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $ev->fecha_evolucion ?></td>
							<td style="border:1px solid #070707; padding: 6px;  "><?= $ev->pacevol_descripcion ?></td>
							<td style="border:1px solid #070707; padding: 6px;  "><?= $ev->nombre_especialidad ?></td>
							<td style="border:1px solid #070707; padding: 6px; "><?= $ev->apel_med.' '. $ev->nomb_med?></td>
							
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>

	
	</div>




</div>




