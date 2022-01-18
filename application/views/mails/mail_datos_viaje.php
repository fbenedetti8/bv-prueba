				
				<tr>
					<td style="padding: 0 15px">
						<div style="border-top: 1px solid #cccccc; padding: 20px 0">
							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Número de reserva:</strong><br />
								<?=$reserva->code;?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Destino:</strong><br />
								<?=$combinacion->destino;?>
							</p>


							<? //defino la fecha a mostrar
							if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario):
								//si la fecha la eligió el usuario
								$fecha_elegida = (formato_fecha($reserva->fecha));
								$fecha_elegida_fin = (formato_fecha($combinacion->fecha_fin));
							else:
								//si hay transporte asociado, tomo la fecha salida del mismo
								if($combinacion->fecha_salida > '0000-00-00'):
									$fecha_elegida = (formato_fecha($combinacion->fecha_salida));
								else:
									$fecha_elegida = (formato_fecha($combinacion->fecha_checkin));
								endif;
								//si hay transporte asociado, tomo la fecha regreso del mismo
								if($combinacion->fecha_regreso > '0000-00-00'):
									$fecha_elegida_fin = (formato_fecha($combinacion->fecha_regreso));
								else:

									$fecha_elegida_fin = (formato_fecha($combinacion->fecha_fin));
								endif;
							endif; ?>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Salida:</strong><br />
								<?=$fecha_elegida;?> <? if($reserva->hora != ''): ?>(<?=$reserva->hora;?> hs)<? endif; ?><br><?=$reserva->lugarSalida;?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Regreso:</strong><br />
								<?=$fecha_elegida_fin;?>
							</p>

							<? if(@$reserva->nombre_adicionales): ?>
							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Adicionales:</strong><br />
								<?=$reserva->nombre_adicionales;?>
							</p>
							<? endif; ?>
						</div>
					</td>
				</tr>


				<tr>
					<td style="padding: 0 15px">
						<div style="border-top: 1px solid #cccccc; padding: 20px 0">
							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Pasajero responsable:</strong><br />
								<?=$responsable->apellido.', '.$responsable->nombre.(!$responsable->completo?' <span style="color: #e71656">Faltan datos</span>':'');?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">DNI:</strong><br />
								<?=$responsable->dni;?>
							</p>

							<? if(isset($acompanantes) && !empty($acompanantes) && count($acompanantes)>0): ?>
							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Acompañantes (<?=count($acompanantes);?>):</strong><br />
								<? foreach($acompanantes as $a): ?>
									<?=($a->completo)?($a->apellido.', '.$a->nombre):'<span style="color: #e71656">Faltan datos</span>';?><br>
								<? endforeach; ?>
							</p>
							<? endif; ?>

							<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13 && ($incompletos || !$responsable->completo)): ?>
									
									<a href="<?=site_url('reservas/completar_datos/'.encriptar($reserva->code));?>" style="font-family: 'arial'; box-sizing: border-box; background: #ff5c5d; color: white; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 20px auto">Completar datos</a>

									<p style="font-family: 'arial'; font-size: 15px; color: #282b5a; display: block; text-align: right; font-weight: bold">Tenés tiempo hasta el <?=date('d/m',strtotime(fecha_completar_datos($reserva->id)));?> a las <?=date('H:i',strtotime(fecha_completar_datos($reserva->id)));?>hs</p>

							<? endif; ?>

						</div>
					</td>
				</tr>


				<tr>
					<td style="padding: 0 15px">
						<div style="border-top: 1px solid #cccccc; padding: 20px 0">
							<p style="font-family: 'arial'; color: #282b5a; font-size: 0; margin-top: 20px">
								<strong style="font-family: 'arial'; width: 50%; display: inline-block; vertical-align: middle; font-size: 20px">Valor total del viaje:</strong>
								<span style="color: #00adee; width: 50%; font-weight: bold; display: inline-block; vertical-align: middle; text-align: right; font-size: 20px"><?=strip_tags($precios['precio_total'],'<sup>');?><span>
							</p>

							<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13): ?>

								<p style="font-family: 'arial'; color: #282b5a; font-size: 0; margin-top: 20px">
									<strong style="font-family: 'arial'; width: 50%; display: inline-block; vertical-align: middle; font-size: 20px">Abonaste:</strong>
									<span style="color: #00adee; width: 50%; font-weight: bold; display: inline-block; vertical-align: middle; text-align: right; font-size: 20px"><?=strip_tags($precios['monto_abonado'],'<sup><span>');?><span>
								</p>

								<p style="font-family: 'arial'; color: #282b5a; font-size: 0; margin-top: 20px">
									<strong style="font-family: 'arial'; width: 50%; display: inline-block; vertical-align: middle; font-size: 20px">Saldo pendiente:</strong>
									<span style="color: #00adee; width: 50%; font-weight: bold; display: inline-block; vertical-align: middle; text-align: right; font-size: 20px"><?=strip_tags($precios['saldo_pendiente'],'<sup><span>');?><span>
								</p>

								<p style="font-family: 'arial'; font-size: 15px; color: #282b5a; display: block; text-align: right; font-weight: bold">Tenés tiempo hasta el <?=date('d/m',strtotime(fecha_saldar_viaje($reserva->id)));?></p>

							<? endif; ?>
						</div>
					</td>
				</tr>

				<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13 && (!isset($ocultar_boton_pago) || (isset($ocultar_boton_pago) && !$ocultar_boton_pago))): ?>
				<tr>
					<td style="padding-bottom: 30px">
						<div style="text-align: center">
							<a href="<?=site_url('reservas/generar_pago/'.encriptar($reserva->code));?>" target="_blank" style="font-family: 'arial'; box-sizing: border-box; background: #ff5c5d; color: white; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 5px auto">Pagar con MercadoPago</a>
						</div>

						<div style="text-align: center">
							<a href="<?=site_url('reservas/generar_pago/'.encriptar($reserva->code));?>" target="_blank" style="font-family: 'arial'; box-sizing: border-box; background: #ff5c5d; color: white; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 5px auto">Pagar con Paypal</a>
						</div>

						<div style="text-align: center">
							<a href="<?=site_url('reservas/informar_transferencia/'.encriptar($reserva->code));?>" target="_blank" style="font-family: 'arial'; box-sizing: border-box; background: #CCF8FF; color: #282b5a; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 5px auto">Informar Pago</a>
						</div>
					</td>
				</tr>

				<tr style="background-color: #CCF8FF; line-height: 0">
					<td style="text-align: center; padding: 30px 20px">
						<p style="font-family: 'arial'; color: #282b5a; margin: 0 0 10px"><strong style="font-family: 'arial'">Formas de pago</strong></p>

						<a href="#" style="font-family: 'arial'; display: inline-block; margin-top: 15px">
							<img style="width: 100%; max-width: 85%" src="<?=base_url();?>media/assets/mail-reserva/medios_pago.png" alt="Targeta de crédito - Transferencia - Rapipago - Pago Fácil - Paypal" />
						</a>
					</td>
				</tr>
				<? endif; ?>
