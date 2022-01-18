<?=$header;?>

	<?=@$settings->script_conversion;?>

	<!-- CUERPO -->

	<div id="main">

		<div class="container">
			<h1>¡Nos vamos de viaje!</h1>
		</div>


		<div class="aside_content container">
			<div class="contenido">

				<div class="info_compra">
					
					<div class="pago_generado">
						<p><strong>Tu pago fue generado correctamente.</strong> Te enviaremos un mail una vez que se acredite el pago dentro de las
						72hs hábiles, dependiendo de los plazos de acreditación en nuestra cuenta bancaria.</p>
					</div>
					
					<h2>Detalle de tu compra</h2>

					<div class="row">
						<div class="col-xs-12 col-md-6">
							<p><strong>Destino:</strong> <span><?=$combinacion->destino;?>.</span></p>
							<? if($combinacion->itinerario && file_exists('./uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario)): ?>
							<p><strong>Excursiones y/o actividades:</strong> <span><a href="<?=base_url().'uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario;?>">Descargar itinerario</a>.</span></p>
							<? endif; ?>
							<p><strong>Pasajeros:</strong> <span><?=$combinacion->pax;?></span></p>
							<p><strong>Lugar de salida:</strong> <span><?=$combinacion->lugar;?></span></p>
							
							<? //defino la fecha a mostrar
							if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
								<? $fecha_elegida = fecha_completa(formato_fecha($reserva->fecha));?>
							<? else: ?>
								<? $fecha_elegida = fecha_completa(formato_fecha($combinacion->fecha_checkin),formato_fecha($combinacion->fecha_checkout));?>	
							<? endif; ?>	
							
							<p><strong><?=dias_viaje($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio,$combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin);?> días - <?=noches_viaje($combinacion->fecha_checkin,$combinacion->fecha_checkout)?> noches:</strong> <span><?=$fecha_elegida;?>.</span></p>
							<p><strong>Transporte:</strong> <span><?=$combinacion->transporte;?></span></p>
						</div>

						<div class="col-xs-12 col-md-6">
							<p><strong>Alojamiento:</strong> <span><?=$combinacion->alojamiento;?></span></p>
							<p><strong>Tipo de habitación:</strong> <span><?=$combinacion->habitacion;?></span></p>
							<p><strong>Pensión:</strong> <span><?=$combinacion->regimen;?></span></p>
							<? if($reserva->nombre_adicionales): ?>
								<p><strong>Adicionales:</strong> <span><?=$reserva->nombre_adicionales;?>.</span></p>
							<? endif; ?>
						</div>
					</div>
				</div>


				<!-- DETALLE DE TU COMPRA -->
					
				<div class="detalle_compra">
					<div>
					
						<div class="detalle_aside">

							<div class="reserva">
								<p class="dato">PASAJEROS<span class="hidden-md hidden-lg">:</span> <span><?=$combinacion->pax;?></span></p>
								
								<p class="precio_final">
									<span>TOTAL<span class="hidden-md hidden-lg">:</span></span> <?=precio($reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio,$combinacion->precio_usd);?>
								</p>

								
								<div class="info_detalle">
									<p>Precio x persona</p>
									<?=$precios['precio_bruto_persona'];?>


									<p>Imp. x persona</p>
									<?=$precios['precio_impuestos_persona'];?>
								</div>

							</div>

						</div>
					
					</div>
				</div>
				
				<!-- FIN DETALLE DE TU COMPRA -->
	
			</div>

		</div>

	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>