<?=$header;?>

	<!-- CUERPO -->

	<div id="main">


		<div class="container">
			<div class="title-section__titles">
				<div class="title-section__titles__color"></div>
				<? if ($orden->vencida): ?>
					<h1>Encontramos una reserva anulada a tu nombre, por favor contáctanos para que podamos asistirte.</h1>
				<? else: ?>
					<h1>¡Reserva tu viaje a <?=$combinacion->destino;?>!</h1>
				<? endif; ?>
			</div>
		</div>

		<? //si quiere mostrar cupo personalizado, piso los datos reales
	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible;
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; 
	if(date('Y-m-d') > $paquete->fecha_inicio):
		$paquete->cupo_disponible = 0;	
	endif;
	?>


		<div class="aside_content container">

			<div id="detalle" class="relative">
				<form id="fPaquete">
					<input type="hidden" id="pasajeros" name="pasajeros" value="<?=$orden->pasajeros;?>"/>
					<input type="hidden" id="paquete_id" name="paquete_id" value="<?=$paquete->id;?>"/>
					<input type="hidden" id="field" name="field" value=""/>
					<input type="hidden" id="detalle_visible" name="detalle_visible" value="0" />
					<input type="hidden" id="aside_mobile" name="aside_mobile" value="0" />
					<input type="hidden" id="zocalo_mobile" name="zocalo_mobile" value="0" />
					<input type="hidden" id="tipo_moneda_m" name="tipo_moneda_m" value="" />
					
					<input type="hidden" id="combinacion_id" name="combinacion_id" value="<?=@$combinacion->id;?>"/>						
					<input type="hidden" id="paquete_precio" name="paquete_precio" value="<?=precio_bruto($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
					<input type="hidden" id="impuestos" name="impuestos" value="<?=precio_impuestos($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
					<input type="hidden" id="adicionales_precio" name="adicionales_precio" value="<?=$orden->adicionales_precio;?>"/>

					<? 
		
		foreach($orden->adicionales as $a): 
					if($a->paquete_adicional_id): ?>
					<input type="hidden" id="adicionales_<?=$a->paquete_adicional_id;?>" name="adicionales[<?=$a->paquete_adicional_id;?>]" value="<?=$a->v_total;?>">
					<? endif; 
					endforeach; ?>
					
					<article id="ticket" class="ticket relative hidden-xs hidden-sm">
						<div class="ticket__sticky-center">
							<div class="ticket__sticky-width">
								<div id="ticket-detail" class="ticket__detail">
									<div class="ticket__detail__estimado relative">
										<div class="moneda_button">
											<span>Moneda</span>

											<label class="moneda_block">
												<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?>/>
												<span>ARS</span>
											</label>

											<label class="moneda_block">
												<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?>/>
												<span>USD</span>
											</label>
										</div>

										<div>
											<p class="estimado__label-dp">Ahora</p>
											<p class="estimado__label-mb">Estimado total</p>
											<!--<p class="estimado__precio"><comb id="precio_final_persona"><?=precio_redondeado($combinacion->precio_total,$combinacion->precio_usd);?></comb></p>
											<p class="estimado__imp">+<comb id="precio_impuestos_persona"><?=precio_redondeado($combinacion->precio_impuestos,$combinacion->precio_usd);?></comb> imp</p>
											-->
											<p class="estimado__precio"><comb id="precio_final_persona"><?=$precios['precio_bruto_persona'];?></comb></p>
											<p class="estimado__imp">+<comb id="precio_impuestos_persona"><?=$precios['precio_impuestos_persona'];?></comb> imp</p>

										</div>
										<a class="estimado__ocultar-detalles--dp">detalles</a>
									</div>
									<div class="ticket__detail__desgloce">
										<div class="desgloce__item clearfix">
											<p class="desgloce__item__label"><comb class="pax"><?=$orden->pasajeros;?></comb> personas</p>
											<!--<p class="desgloce__item__cantidad"><comb id="precio_bruto"><?=strip_tags(str_replace('<sup>',',',precio_bruto($combinacion,false,$orden->pasajeros)));?></comb></p>
											-->
											<p class="desgloce__item__cantidad"><comb id="precio_bruto"><?=$precios['precio_bruto'];?></comb></p>

										</div>
										<div class="desgloce__item clearfix">
											<p class="desgloce__item__label">Imp. y/o tasas</p>
											<!--<p class="desgloce__item__cantidad"><comb id="precio_impuestos"><?=strip_tags(str_replace('<sup>',',',precio_impuestos($combinacion,false,$orden->pasajeros)));?></comb></p>
											-->
											<p class="desgloce__item__cantidad"><comb id="precio_impuestos"><?=$precios['precio_impuestos'];?></comb></p>
										</div>
										<div class="desgloce__item clearfix">
											<p class="desgloce__item__label">Precio total</p>
											<!--<p class="desgloce__item__cantidad"><comb id="precio_total"><?=strip_tags(str_replace('<sup>',',',precio($combinacion->v_total*$orden->pasajeros,$combinacion->precio_usd,true,true)));?></comb></p>
											-->
											<p class="desgloce__item__cantidad"><comb id="precio_total"><?=$precios['precio_total'];?></comb></p>
										</div>

										<div class="moneda_button">
											<span>Moneda</span>

											<label class="moneda_block">
												<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?>/>
												<span>ARS</span>
											</label>

											<label class="moneda_block">
												<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?>/>
												<span>USD</span>
											</label>
										</div>
									</div>
									<div class="paquete__lugares-disponibles">
										<div class="clearfix">
											<img class="paquete__lugares-disponibles__icon" src="<?=base_url();?>media/assets/images/icon/icon-suitcase-w-bg.png">
											<? if($paquete->cupo_disponible): ?>
												<p class="paquete__lugares-disponibles__cantidad"><span><?=$paquete->cupo_disponible;?></span>/<?=$paquete->cupo_total;?></p>
												<p class="paquete__lugares-disponibles__leyenda">Lugares<br>
											disponibles</p>
											<? else: ?>
												<p class="paquete__lugares-disponibles__cantidad"><span>COMPLETO</p>
											<? endif; ?>
										</div>
									</div>

									<?
									if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || !$paquete->cupo_disponible || $habitacion_sin_cupo): ?>
								
										<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--dp">Anotarme<br>en lista de espera</a>
										<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--mb">Anotarme<br>en lista de espera</a>

									<? else: ?>
										<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--dp"><span>Reserva</span> con solo<br><span class="rift"><comb id="precio_minimo"><?=precio_redondeado($combinacion->monto_minimo_reserva,$combinacion->precio_usd);?></comb></span> por persona</a>
										<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--mb">Reserva con solo<br><span><comb id="precio_minimo"><?=precio_redondeado($combinacion->monto_minimo_reserva,$combinacion->precio_usd);?></comb> <span class="isSmaller">P/P</span></span></a>
									<? endif; ?>
								</div>
								<div id="paquete-consultas" class="paquete__consultas clearfix relative">
									<p>¿Tenés una consulta? <span>Escribinos</span></p>
									<div class="paquete__consultas__icons">
										<a rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank"><i class="fab fa-whatsapp"></i><span>Whatsapp</span></a>
										<a rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank"><i class="fab fa-facebook-messenger"></i><span>Facebook</span></a>
									</div>
								</div>
								<!-- <div class="ticket__blue-div--mb"></div> -->
							</div>
						</div>
						<a class="ticket__ocultar-detalle--mb" href=""><img class="closed" src="<?=base_url();?>media/assets/images/icon/icon-doble-arrow-up-w.png"><img class="open" src="<?=base_url();?>media/assets/images/icon/double-arrow-down.png"><span>Clickear para detalles y más opciones</span></a>
					</article>

				</form>
			</div>




			<div class="contenido">

				<div class="info_compra">
					<a class="btn_editar" href="<?=site_url($combinacion->slug);?>">< VOLVER</a>
					
					<h2>Detalle de tu compra</h2>

					<div class="row">
						<div class="col-xs-12 col-md-6">
							<p><strong>Destino:</strong> <span><?=$combinacion->destino;?>.</span></p>
							<? if($combinacion->itinerario && file_exists('./uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario)): ?>
							<p><strong>Excursiones y/o actividades:</strong> <span><a href="<?=base_url().'uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario;?>">Descargar itinerario</a>.</span></p>
							<? endif; ?>
							<p><strong>Pasajeros:</strong> <span><?=count($pasajeros);?></span></p>
							<? if(@$combinacion->lugar_id != 4): //si no es tour en destino muestro el lugar de salida ?>
								<p><strong>Lugar de salida:</strong> <span><?=$combinacion->lugar;?></span></p>
							<? endif; ?>
							
							<? 
								$f_inicio = ($combinacion->fecha_salida < $combinacion->fecha_checkin) ? $combinacion->fecha_salida : $combinacion->fecha_checkin; 
								$f_fin = ($combinacion->fecha_regreso > $combinacion->fecha_checkout) ? $combinacion->fecha_regreso : $combinacion->fecha_checkout; 
							?>
							
							<?
							//defino la fecha a mostrar
							if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
								<? $fecha_elegida = fecha_completa(formato_fecha($orden->fecha));?>
							<? else: ?>
								<? $fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin));?>	
							<? endif; ?>	
							
									
							<p><strong><?=dias_viaje($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio,$combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin);?> días - <?=noches_viaje($combinacion->fecha_checkin,$combinacion->fecha_checkout)?> noches:</strong> <span><?=$fecha_elegida;?>.</span></p>
							<? if(@$combinacion->lugar_id != 4): //si no es tour en destino muestro el transporte ?>
								<p><strong>Transporte:</strong> <span><?=$combinacion->transporte;?></span></p>
							<? endif; ?>
						</div>

						<div class="col-xs-12 col-md-6">
							<p><strong>Alojamiento:</strong> <span><?=($combinacion->alojamiento);?></span></p>
							<p><strong>Tipo de habitación:</strong> <span><?=($combinacion->habitacion);?></span></p>
							<p><strong>Pensión:</strong> <span><?=($combinacion->regimen);?></span></p>
							<? if($orden->nombre_adicionales): ?>
								<p><strong>Adicionales:</strong> <span><?=$orden->nombre_adicionales;?>.</span></p>
							<? endif; ?>
						</div>
					</div>
				</div>


				<!-- DETALLE DE TU COMPRA -->
					
				<div class="detalle_compra">
					<div>
					
						<div class="detalle_aside hidden-md hidden-lg">

							<div class="reserva">
								<p class="dato">PASAJEROS<span class="hidden-md hidden-lg">:</span> <span><?=count($pasajeros);?></span></p>
								
								<p class="precio_final">
									<span>TOTAL<span class="hidden-md hidden-lg">:</span></span> <comb id="ordenfinal"><?=strip_tags($precios['precio_total'],'<sup><strong>');?></comb>
								</p>

								
								<div class="info_detalle">
									<p>Precio x persona</p>
									<comb id="ordenpersonafinal"><?=$precios['precio_bruto_persona'];?></comb>


									<p>Imp. x persona</p>
									<comb id="ordenpersonaimp"><?=$precios['precio_impuestos_persona'];?></comb>
								</div>

								<? if($combinacion->monto_minimo_reserva > 0): ?>
								<div class="precio_reserva">
									<p><strong>Reserva con</strong></p>
									<span class="btn_tooltip">
										<span class="icon-question-mark"></span>
										
										<div class="tooltip">
											<div></div>
											<span>Reserva con el mínimo y pagá el resto del viaje después</span>
										</div>
									</span>

									<?//25-02-19  
									//$precio_usd = $orden->user_tipo_moneda ? ($orden->user_tipo_moneda=='USD'?true:false) : $combinacion->precio_usd;
									$precio_usd = $combinacion->precio_usd; ?>
									<p><comb id="ordenpersonareserva"><?=$combinacion->grupal?precio($precios['num']['monto_minimo_reserva']/$orden->pasajeros,$precio_usd):$precios['monto_minimo_reserva'];?></comb></p>
								</div>

								<div class="moneda_button">
									<span>Moneda</span>

									<label class="moneda_block">
										<input type="radio" name="tipo_moneda_ch" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?>/>
										<span>ARS</span>
									</label>

									<label class="moneda_block">
										<input type="radio" name="tipo_moneda_ch" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?>/>
										<span>USD</span>
									</label>
								</div>
								<? endif; ?>
							</div>

						</div>
					
					</div>
				</div>
				
				<!-- FIN DETALLE DE TU COMPRA -->

				<hr class="hidden-xs hidden-sm" />

				<h2>Datos de Reserva</h2>
				
				<? //if(@$combinacion->agotada && @$combinacion->confirmacion_inmediata): ?>
				<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || @$transporte_sin_cupo || @$habitacion_sin_cupo): ?>
				<div class="alert">
					<p>Debido a que el <strong>cupo del viaje está COMPLETO</strong>, tu reserva ingresará en <strong>LISTA DE ESPERA</strong>. Una vez que te confirmemos el lugar vas a poder generar los pagos correspondientes sobre la misma.</p>
				</div>
				<? endif; ?>
				
				<!-- PASO 1 -->
				<div id="paso1">
					<?=$checkout_paso1;?>
				</div>
				<!-- FIN PASO 1 -->
				
				<!-- PASO 2 -->
				<div id="paso2">
					<?=$checkout_paso2;?>
				</div>
				<!-- FIN PASO 2 -->
				
				<!-- PASO 3 -->
				<div id="paso3">
					<?=$checkout_paso3;?>
				</div>
				<!-- FIN PASO 3 -->
				
				<!-- PASO 4 -->
				<div id="paso4">
					<?=$checkout_paso4;?>
				</div>
				<!-- FIN PASO 4 -->
								
			</div>

		</div>

	</div>

	<!-- FIN CUERPO -->
	<script type="text/javascript">
	var tipo_viaje = "<?=(!$paquete->grupal && $paquete->exterior)?'individual':'grupal';?>";
	var origen_activo = "<?=($paquete->grupal)?'backend':'mercadopago';?>";
	var viaje_ind_ext = '<?=$paquete->precio_usd;?>';
	var moneda = '<?=$paquete->precio_usd?'dolares':'pesos';?>';
	var aside = 'ticket';
	</script>
	

<?=$footer;?>

  <script>

	init_js_paquete();

  </script>