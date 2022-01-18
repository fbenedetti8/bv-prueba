<? //si quiere mostrar cupo personalizado, piso los datos reales

	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible>=$orden->pasajeros;
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; 
/*
pre($combinacion);
pre($paquete);
echo $habitacion_sin_cupo?'habitacion_sin_cupo':'nohabitacion_sin_cupo';
echo $transporte_sin_cupo?'transporte_sin_cupo':'notransporte_sin_cupo';
exit();*/
	?>

			<!-- PASO 4 -->
				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading <?=($orden->completo_paso4)?'completo':'';?>">
							<h3 class="panel-title">
								<a class="<?=($orden->paso_actual==4)?'':'collapsed';?>" role="button" data-toggle="collapse" href="#paso_4">
									<span class="num_paso"><?=($orden->pasajeros > 1)?'4':'3';?></span>
									<span>
										<!-- Si hay un <span> fuera del <strong> levanta los estilos como si fuera un link -->
										<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || !$paquete->cupo_disponible || @$habitacion_sin_cupo || @$transporte_sin_cupo): ?>
										<strong>Finalizar</strong>
										<? else: ?>
										<strong>Finalizá tu compra</strong>
										<? endif; ?>
									</span>
								</a>
							</h3>
						</div>

						<? if($orden->completo_paso3 || $orden->salteo_paso3): ?>
						<!-- La clase "in" junto a la clase "collpase" mantiene el desplegable abierto -->

						<div id="paso_4" class="panel-collapse collapse <?=($orden->paso_actual==4)?'in':'';?>">
							<div class="panel-body">

								<? //Maxi 23-11-19 | si alcanzo fecha limite de pago completo, solo permito pago total
								$pago_completo = $paquete->fecha_limite_pago_completo < date('Y-m-d'); ?>

								<? //if (@$combinacion->agotada): ?>
								<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || !$paquete->cupo_disponible || @$habitacion_sin_cupo || @$transporte_sin_cupo): ?>
								
									<p><strong>¡Gracias por anotarte!</strong> Te enviaremos un mail  cuando haya cupo disponible para este viaje, con un link para generar el pago.</p>
								
								<? elseif (@!$combinacion->confirmacion_inmediata): ?>

									<p><strong>¡Gracias por completar tu solicitud de reserva!</strong> Verificaremos la disponibilidad y te informaremos a tu dirección de e-mail registrada para que puedas realizar el pago.</p>
								
								<? else: ?>
									<? if($pago_completo): ?>

										<p class="bold_text">Tu reserva ya está generada. <strong>Para poder efectivizar la reserva debes realizar el pago total de la misma</strong>.</p>
									
									<? else: ?>

										<p class="bold_text">Tu reserva ya está generada. <strong>Puedes pagar ahora mismo o hacerlo luego con los medios de pagos disponibles. Recuerda que tienes 24 hs para hacer el pago mínimo, de lo contrario tu reserva será anulada automáticamente</strong>.</p>

										<h4>Elegí tu forma de pago</h4>

										<hr />
									
									<? endif; ?>

									
									<? if(!$pago_completo): ?>
									
										<h5>Pago parcial o total</h5>

										<p>Si realizas un pago parcial, puedes saldar el resto del viaje utilizando cualquier otro medio de pago. En el mail de confirmación de reserva tendrás un botón para generar el pago.</p>

									<? endif; ?>

									<form class="form_pago" name="formPago" id="formPago">
										<input type="hidden" name="orden_id" value="<?=$orden->id;?>"/>
										<input type="hidden" name="numero_paso" id="numero_paso" value="4"/>
										<input type="hidden" name="hash" id="hash" value="<?=$hash;?>"/>
										<div>
											<label class="total">
												<input type="radio" name="pago" value="total" checked />
												<span><strong>Pago total único</strong>: <?=strip_tags($paquete->grupal?$precios['precio_final_persona']:$precios['precio_total'],'<sup>');?></span>
											</label>

											<? if(!$pago_completo): ?>
											
												<label>
													<input type="radio" name="pago" value="parcial" />
													
													<span><strong>Pago parcial</strong> <? if($combinacion->monto_minimo_reserva>0): ?>(mínimo <?=strip_tags($paquete->grupal?$precios['monto_minimo_reserva_persona']:$precios['monto_minimo_reserva'],'<sup>');?>)<? endif; ?>:</span>
												</label>

												<? $minimo = $combinacion->monto_minimo_reserva * ($paquete->grupal?1:$orden->pasajeros); 
													/*if($combinacion->precio_usd && $orden->user_tipo_moneda == 'ARS'):
														$minimo = $minimo*$this->settings->cotizacion_dolar;
													endif;
													if(!$combinacion->precio_usd && $orden->user_tipo_moneda == 'USD'):
														$minimo = $minimo/$this->settings->cotizacion_dolar;
													endif;*/
													$minimo = number_format($minimo,2,'.','');
												?>
												<input class="monto_pago_parcial onlydecimal" type="text" name="monto" data-minimo="<?=$minimo;?>" placeholder="$XXXX" />

											<? endif; ?>

											<label><span>El tipo de cambio del día es USD 1 = ARS <?=$this->settings->cotizacion_dolar;?></span></label>
											
											<!-- ERROR -->
											<p class="msg error">Por favor, ingresa un monto mínimo para poder efectuar el pago</p>
											<!-- FIN ERROR -->
										</div>

										<button class="submit_button btn btn-pink pago pagarMP" type="button">
											<span>Pagar con</span>
											<img src="<?=base_url();?>media/assets/images/icon/mercado_pago.png" alt="MercadoPago" />
										</button>

										<div style="margin-top:30px; display:none;">
											<p class="msg "><b>Pagos con Paypal</b>: la acreditación de los mismos puede demorar unos minutos. Recibirás un mail de confirmación del pago una vez que se acredite en nuestra cuenta.</p>
										</div>

										<button class="submit_button btn btn-pink pago pagarPP" type="button" >
											<span>Pagar con</span>
											<img src="<?=base_url();?>media/assets/images/icon/paypal.png" alt="Paypal" />
										</button>
									</form>

									<div class="transferencia hidden">
										<h5>Depósito o transferencia bancaria</h5>

										<p>Tenemos cuenta en Banco Galicia, HSBC y Macro. Conocé los datos de las cuentas: <? if($this->settings->file_datos_cuenta != '' && file_exists('./uploads/config/1/'.$this->settings->file_datos_cuenta)): ?><a target="_blank" href="<?=base_url().'uploads/config/1/'.$this->settings->file_datos_cuenta;?>">Descargar PDF</a><? endif; ?></p>

										<a href="<?=site_url('checkout/informar_transferencia/'.$hash);?>" class="button">Informar Transferencia</a>
									</div>


									<hr />

									<div class="pagar_luego">
										<h5>Pagar luego</h5>

										<p>Te enviaremos un mail con un link para generar el pago con tarjeta de crédito o vía transferencia bancaria.</p>

										<a href="#" class="submit_button btn btn-pink button lnkPagarLuego">Pagar luego</a>
									</div>

								
								<? endif; ?>
								
							</div>
						</div>
						<? endif; ?>
						
					</div>
				</div>

				<!-- FIN PASO 4 -->
								
	<script type="text/javascript">
	(function(){function $MPBR_load(){window.$MPBR_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = ("https:"==document.location.protocol?"https://www.mercadopago.com/org-img/jsapi/mptools/buttons/":"http://mp-tools.mlstatic.com/buttons/")+"render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPBR_loaded = true;})();}window.$MPBR_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPBR_load) : window.addEventListener('load', $MPBR_load, false)) : null;})();
	</script>