<style>
@media screen and (min-width: 768px){
	.modal-dialog {width: 75%; }
	.modal-dialog .widget-content { max-height:300px; overflow-x:hidden; overflow-y:scroll; }
}
</style>
						
					<form id="fCambioPaquete" method="post" action="<?=$route;?>/elegirCombinacion" >

						<input type="hidden" value="<?=@$nro_hab;?>" name="nro_hab" />

						<input type="hidden" name="reserva_id" value="<?=$reserva->id;?>"/>
						<div class="widget-header">
						  <h2 style="margin:0 0 10px;"><?=$paquete->nombre;?> - <p style="margin: 0;display: inline-block;font-size: 16px;"><?='Desde el '.formato_fecha($paquete->fecha_inicio).' al '.formato_fecha($paquete->fecha_fin);?></p></h2>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12 ">
								<div class="alert alert-info">
									<b>Viajes grupales:</b> Recuerda que el cambio se realizará también en otras reservas con el mismo código de grupo.
								</div>
							</div>
						</div>

						  <div class="row">
							<div class="col-md-12">
								<? if(count($combinaciones)): ?>
									<table cellpadding="0" cellspacing="0" border="0" class="table table-hover datatable">
									  <thead>
										<tr>
										  <?php echo $this->admin->th('lugar', 'Salida y transporte', false);?>
										  <?php echo $this->admin->th('alojamiento', 'Alojamiento', false);?>
										  <?php echo $this->admin->th('habitacion', 'Habitación y Regimen', false);?>
										  <?php echo $this->admin->th('v_total', 'Precio total', false, array('text-align'=>'right'));?>
										  <?php echo $this->admin->th('opciones', 'Seleccionar', false, array('width'=>'95px','text-align'=>'center'));?>
										</tr>
									  </thead>
									  <tbody id="tblCombinaciones">
								  		<?php foreach ($combinaciones as $r): ?>
											<?=cambio_micro_row($r);?>
										<?php endforeach; ?>
									  </tbody>
									</table>


								<? else: ?>
									<input type="hidden" name="combinacion_id" id="combinacion_actual" value="<?=$reserva->combinacion_id;?>">
									<div class="alert alert-success">
										<b>Sin combinaciones:</b> Detectamos que no existen combinaciones disponibles diferentes de la que ya tiene asignada esta reserva.
										<br>
										<? if(isset($nro_hab) && $nro_hab): 
											//si viene de cabiar habitacion desde rooming, muestro mensaje aclaratorio ?>
											Confirma si deseas realizar solamente el cambio de habitación.
										<? endif; ?>
									</div>
								<? endif; ?>

							</div>
						  </div>
						</div>
					</form>