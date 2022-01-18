				<? if($reserva->estado_id == 5): //anulada ?>
				
				<div class="module generar_pago">
					<div class="info_valores">
						<div>
							<p>Total: <?=$precios['precio_total'];?></p>
							<p>Abonaste: <?=$precios['monto_abonado'];?></p>
						</div>
						<div>
							<p>Saldo pendiente: <?=$precios['saldo_pendiente'];?></p>
						</div>
					</div>
				</div>
				
				<div class="msg alert alert-danger">Tu reserva se encuentra <strong>ANULADA</strong>.<br>Por cualquier duda o inconveniente, por favor comunicate con nosotros.<br/>
					<ul>
						<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
						<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810.</a> de Lunes a Viernes de 10 a 19 hs.</li>
					</ul>
				</div>
				
				<? else: ?>
					
				<div class="module generar_pago">
					<div class="info_valores">
						<div>
							<p>Total: <?=$precios['precio_total'];?></p>
							<p>Abonaste: <?=$precios['monto_abonado'];?></p>
						</div>
						<div>
							<p>Saldo pendiente: <?=$precios['saldo_pendiente'];?></p>
						</div>
					</div>

					<hr />

					<h2>generar Pago</h2>

					<? //Maxi 23-11-19 | si alcanzo fecha limite de pago completo, solo permito pago total
					$pago_completo = $paquete->fecha_limite_pago_completo < date('Y-m-d'); ?>

					<? if($pago_completo): ?>

						<p>Si ya realizaste el pago y no ves actualizado el saldo, puede que todav&iacute;a no se haya acreditado en nuestra cuenta. Eso puede demorar unas horas seg&uacute;n el metodo de pago.</p>

					<? else: ?>

						<p>Si realizás un pago parcial, podés saldar el resto del viaje utilizando cualquier otro medio de pago. <strong>Tenés tiempo hasta el <?=date('d/m/Y', strtotime($reserva->fecha_limite_pago_completo));?></strong>.</p>

					<? endif; ?>

					<form class="form_pago" name="formPago" id="formPago">
						<input type="hidden" name="reserva_id" id="reserva_id" value="<?=$reserva->id;?>"/>
						<input type="hidden" name="hash" id="hash" value="<?=$hash;?>"/>
						
						<div>
							<label style="display:block;">
								<input type="radio" name="pago" value="total" checked />
								<span><strong>Pago total único</strong>: <?=strip_tags($precios['saldo_pendiente'],'<sup>');?></span>
							</label>

							<? if(!$pago_completo): ?>
								<label>
									<input type="radio" name="pago" value="parcial" />
									<? if($precios['num']['monto_abonado'] > 0): //si ya pagó algo, el monto minimo no corre ?>
										<span><strong>Pago parcial</strong></span>
									<? else: ?>
										<span><strong>Pago parcial</strong> (mínimo <?=strip_tags($precios['monto_minimo_reserva'],'<sup>');?>):</span>
									<? endif; ?>
								</label>

								<input class="monto_pago_parcial onlydecimal" data-minimo="<?=($precios['num']['monto_abonado'] > 0)?'':$montos_numericos['monto_minimo_reserva'];?>" name="monto" type="text" placeholder="$XXXX" />

							<? endif; ?>

							<? if($combinacion->precio_usd): ?>
								<label><span>El tipo de cambio del día es USD 1 = ARS <?=$this->settings->cotizacion_dolar;?></span></label>
							<? endif; ?>

							<!-- ERROR -->
							<p class="msg error">Por favor, ingresá un monto mínimo para poder efectuar el pago</p>
							<!-- FIN ERROR -->
						</div>

						<button class="button pago pagarMP" type="button">
							<span>Pagar</span>
							<img src="<?=base_url();?>media/assets/images/icon/mercado_pago.png" alt="MercadoPago" />
						</button>

						<div style="margin-top:30px; ">
							<p class="msg "><b>Pagos con Paypal</b>: la acreditación de los mismos puede demorar unos minutos. Recibirás un mail de confirmación del pago una vez que se acredite en nuestra cuenta.</p>
						</div>

						<button class="button pago pagarPP" type="button">
							<span>Pagar con</span>
							<img class="img-responsive" src="<?=base_url();?>media/assets/imgs/iconos/paypal.png" alt="Paypal" />
						</button>
					</form>

				</div>

				<div class="module transferencia">
					<h2>Depósito o transferencia bancaria</h2>

					<p>Consulta los datos de nuestras cuentas bancarias acá. <? if($this->settings->file_datos_cuenta != '' && file_exists('./uploads/config/1/'.$this->settings->file_datos_cuenta)): ?><a target="_blank" href="<?=base_url().'uploads/config/1/'.$this->settings->file_datos_cuenta;?>">Descargar PDF</a><? endif; ?></p>

					<a href="<?=site_url('reservas/informar_transferencia/'.$hash);?>" class="button">Informar Transferencia</a>
				</div>
				<? endif; ?>