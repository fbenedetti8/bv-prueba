				<!-- DETALLE DE COMPRA DESKTOP -->
				<!-- (cuadro fijo) -->

				
	<? //si quiere mostrar cupo personalizado, piso los datos reales
	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible>=((isset($pax_elegidos)&&$pax_elegidos>0)?$pax_elegidos:$combinacion->pax);
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; ?>
				
				<div class="hidden-xs hidden-sm">
					<div class="container">

						<div class="detalle_aside aside_desktop">

							<div class="reserva">
								
								<? if($combinacion->precio_usd): ?>
								<div class="moneda">
									<span><strong>Moneda</strong></span>

									<label>
										<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?> />
										<span>ARS</span>
									</label>

									<label>
										<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?> />
										<span>USD</span>
									</label>
								</div>
								<? else: ?>
								<input type="hidden" name="tipo_moneda" value="<?=$combinacion->precio_usd?'USD':'ARS';?>"  />
								<? endif; ?>
								
								<div class="total">
									<p class="precio_final">
										<comb id="precio_final_persona"><?=precio($combinacion->v_total,$combinacion->precio_usd);?></comb><span>x persona <a class="btn_detalle" href="javascript:void(0)"><?=$detalle_visible ? 'OCULTAR' : 'VER';?> DETALLE</a></span>
									</p>
								</div>

								
								<div class="info_detalle" style="display:<?=$detalle_visible ? 'block' : 'none';?>">
									<p><comb class="pax"><?=(isset($pax_elegidos)&&$pax_elegidos>0)?$pax_elegidos:$combinacion->pax;?></comb> personas</p>
									<comb id="precio_bruto"><?=precio_bruto($combinacion);?></comb>

									<p>Imp. y/o tasas</p>
									<comb id="precio_impuestos"><?=precio_impuestos($combinacion);?></comb>

									<p>Precio total</p>
									<comb id="precio_total"><?=precio($combinacion->v_total*$combinacion->pax,$combinacion->precio_usd,true,true);?></comb>
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

									<p><comb id="precio_minimo"><?=precio($combinacion->monto_minimo_reserva,$combinacion->precio_usd,true,true);?></comb></p>
									<p class="porpersona"><strong>por persona</strong></p>
								</div>
								<? endif; ?>

								<?
								if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || !$paquete->cupo_disponible || $habitacion_sin_cupo): ?>
									<input class="button espera btnReservar" type="submit" value="Anotarme en lista de espera" />
								<? else: ?>
									<input class="button btnReservar" type="submit" value="<?=($paquete->fecha_indefinida || !$paquete->confirmacion_inmediata)?'Solicitar Reserva':'Reservar';?>" />
								<? endif; ?>
							</div>

							<? if($paquete->calculador_cuotas || $paquete->pago_oficina): ?>
							<div class="calculador desktop">
								<h3>Calculador de cuotas</h3>

								<div>
									<label>
										<span>Forma de pago</span>
										<select class="selectpicker lnkMedioPago" data-title="Seleccionar" name="forma_pago">
											<? if($paquete->grupal && $paquete->pago_oficina): ?>
											<option value="backend">En nuestras oficinas</option>
											<? endif; ?>
											<? if($paquete->calculador_cuotas): ?>
											<option value="mercadopago">MercadoPago</option>
											<? endif; ?>
										</select>
									</label>

									<label class="tarjeta">
										<span>Tarjeta / Otros</span>
										<select class="selectpicker lnkMetodoPago" data-title="Seleccionar" id="metodo_pago" name="metodo_pago">
											<? foreach($metodos_pago as $b){ ?>
												<optgroup label="<?=$b->banco;?>">
												<? foreach($b->tarjetas as $t){ ?>
													<option data-banco="<?=$b->banco;?>" value="<?=$t->tarjeta;?>"><?=$t->tarjeta;?></option>
												<? } ?>
												</optgroup>
											<? } ?>			
										</select>
									</label>

									<label class="cuotas">
										<span>Cuotas</span>
										<select class="selectpicker lnkCuotas" data-title="- -" id="cuotas" name="cuotas">
											<option>- -</option>
										</select>
									</label>
								</div>

								<p>Valor de cuota <strong id="valor_cuota">ARS 0</strong></p>
								<span>Intereses, gastos e impuestos, incluídos.</span>
								<span>Precio financiado: <strong id="precio-total">ARS 0</strong></span>

								<span>CFT: <strong id="cft">0%</strong> - TEA: <strong id="tea">0%</strong></span>
							</div>
							<? endif; ?>
							
						</div>
					
					</div>
				</div>
				
				<!-- FIN DETALLE DE COMPRA DESKTOP -->


				<!-- DETALLE COMPRA MOBILE -->

				<div class="detalle_compra hidden-md hidden-lg <?=(isset($filtros['zocalo_mobile']) && $filtros['zocalo_mobile'])?'fixed':'';?>">
					<div class="container">
					
						<div class="detalle_aside aside_mobile">

							<div class="reserva">						

								<div class="precio_reserva">
									<p>Reserva con</p>
									<span class="btn_tooltip">
										<span class="icon-question-mark"></span>
										
										<div class="tooltip">
											<div></div>
											<span>Reserva con el mínimo y pagá el resto del viaje después</span>
										</div>
									</span>
									<p><comb id="precio_minimo"><?=precio($combinacion->monto_minimo_reserva,$combinacion->precio_usd,true,true);?></comb></p>
									<p class="">por persona</p>
									
								</div>
								
								<div class="total">
									<p class="precio_final">
										<comb id="precio_final_persona"><?=precio($combinacion->v_total,$combinacion->precio_usd,true,false);?></comb><span>x persona</span>
										<a class="btn_detalle" href="javascript:void(0)">VER DETALLE</a>
									</p>
								</div>
								
								<div class="info_detalle">
									<p><comb class="pax"><?=(isset($pax_elegidos)&&$pax_elegidos>0)?$pax_elegidos:$combinacion->pax;?></comb> personas</p>
									<comb id="precio_bruto"><?=precio_bruto($combinacion);?></comb>

									<p>Imp. y/o tasas</p>
									<comb id="precio_impuestos"><?=precio_impuestos($combinacion);?></comb>

									<p>Precio total</p>
									<comb id="precio_total"><?=precio($combinacion->v_total*$combinacion->pax,$combinacion->precio_usd,true,true);?></comb>

									<? if($combinacion->precio_usd): ?>
									<div class="moneda">
										<span><strong>Moneda</strong></span>

										<label>
											<input type="radio" name="tipo_moneda_m" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?> />
											<span>ARS</span>
										</label>

										<label>
											<input type="radio" name="tipo_moneda_m" value="USD" <?=$combinacion->precio_usd?'checked':'';?> />
											<span>USD</span>
										</label>
									</div>
									<? else: ?>
									<input type="hidden" name="tipo_moneda_m" value="<?=$combinacion->precio_usd?'USD':'ARS';?>"  />
									<? endif; ?>
								</div>

							</div>

							<div class="submit">
								<? if(@$combinacion->agotada || !$paquete->cupo_disponible || $habitacion_sin_cupo): ?>
									<input class="button espera btnReservar" type="submit" value="Anotarme en lista de espera" />
								<? else: ?>
									<input class="button btnReservar" type="submit" value="<?=($paquete->fecha_indefinida || !$paquete->confirmacion_inmediata)?'Solicitar Reserva':'Reservar';?>" />
								<? endif; ?>
							</div>
						</div>
					
					</div>
				</div>
				<!-- FIN DETALLE COMPRA MOBILE -->
				
<script>

		
</script>

	<script type="text/javascript">
	var tipo_viaje = "<?=(!$paquete->grupal && $paquete->exterior)?'individual':'grupal';?>";
	var origen_activo = "<?=($paquete->grupal)?'backend':'mercadopago';?>";
	var viaje_ind_ext = '<?=$paquete->precio_usd;?>';
	var moneda = '<?=$paquete->precio_usd?'dolares':'pesos';?>';
	var aside = '<?=$this->detector->isMobile()?"aside_mobile":"aside_desktop";?>';
	</script>
	
	<input type="hidden" id="combinacion_id" name="combinacion_id" value="<?=@$combinacion->id;?>"/>				
	<input type="hidden" id="paquete_id" name="paquete_id" value="<?=$paquete->id;?>"/>				
	<input type="hidden" id="paquete_precio" name="paquete_precio" value="<?=precio_bruto($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
	<input type="hidden" id="impuestos" name="impuestos" value="<?=precio_impuestos($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
	<input type="hidden" id="adicionales_precio" name="adicionales_precio" value="0"/>