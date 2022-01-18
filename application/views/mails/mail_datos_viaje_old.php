							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Número de Reserva:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0"><?=$reserva->code;?></p>
								</div>


								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Destino:</p>
								</div>
								
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
								
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; line-height: 23px; margin: 5px 0"><?=$combinacion->destino;?></p>
								</div>

								
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Salida:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0"><?=$fecha_elegida;?> <? if($reserva->hora != ''): ?>(<?=$reserva->hora;?> hs)<? endif; ?><br><?=$reserva->lugarSalida;?></p>
								</div>
								
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Regreso:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0"><?=$fecha_elegida_fin;?></p>
								</div>

								<? if(@$reserva->nombre_adicionales): ?>
									<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
										<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Adicionales:</p>
									</div>
									<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
										<p style="font-size: 12px; text-align: left; margin: 5px 0"><?=$reserva->nombre_adicionales;?></p>
									</div>

								<? endif; ?>
							</div>


							<div style="width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 15px; margin-bottom: 15px"></div>


							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Pasajero responsable:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px;  text-align: left; margin: 5px 0"><?=$responsable->apellido.', '.$responsable->nombre.(!$responsable->completo?' <span style="color: #e71656">Faltan datos</span>':'');?></p>
								</div>


								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">DNI:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0"><?=$responsable->dni;?></p>
								</div>

								<? if(count($acompanantes)>0): ?>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Acompañantes (<?=count($acompanantes);?>):</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top; font-size: 12px; text-align: left">
									<? foreach($acompanantes as $a): ?>
										<p style="min-width: 280px;">Pasajero <?=$a->numero_pax;?>: <?=($a->completo)?($a->apellido.', '.$a->nombre):'<span style="color: #e71656">Faltan datos</span>';?></p>
									<? endforeach; ?>
								</div>
								<? endif; ?>

								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top; font-size: 12px; text-align: left">
									<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13 && ($incompletos || !$responsable->completo)): ?>
									<a href="<?=site_url('reservas/completar_datos/'.encriptar($reserva->code));?>" style="border: 1px solid #001862; width: 135px; text-align: center; padding: 10px 0; background-color: #f7f7f7; display: block; color: #001862; font-weight: 900; font-size: 12px; text-decoration: none">Completar datos</a>
									<span style="width: 100%; display: block; color: #e71656; font-size: 12px; margin-top: 8px; font-weight: 900">Tenés tiempo hasta el <?=date('d/m',strtotime(fecha_completar_datos($reserva->id)));?> a las <?=date('H:i',strtotime(fecha_completar_datos($reserva->id)));?>hs</span>
									<? endif; ?>
								</div>
							</div>

							<div style="width: 100%; max-width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 20px; margin-bottom: 15px"></div>

							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Valor total del viaje:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top; font-size: 12px; text-align: left">
									<span style="display: block; color: #001862; font-weight: 900; font-size: 14px"><?=strip_tags($precios['precio_total'],'<sup>');?></span>
									<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13): ?>
									<p style="color: #696969">Abonaste: <?=strip_tags($precios['monto_abonado'],'<sup><span>');?></p>
									<p style="color: #696969">Saldo pendiente: <?=strip_tags($precios['saldo_pendiente'],'<sup><span>');?></p>
										<? if($reserva->fecha_limite_pago_completo): ?>
											<span style="width: 100%; display: block; color: #e71656; font-size: 12px; margin-top: 8px; font-weight: 900">Tenés tiempo hasta el <?=date('d/m',strtotime(fecha_saldar_viaje($reserva->id)));?>
											</span>
										<? endif; ?>
									<? else: ?>
									<p></p>
									<? endif; ?>
								</div>
							</div>



							<? if ($reserva->estado_id != 12 && $reserva->estado_id != 13 && (!isset($ocultar_boton_pago) || (isset($ocultar_boton_pago) && !$ocultar_boton_pago))): ?>
							<div>
								<a href="<?=site_url('reservas/generar_pago/'.encriptar($reserva->code));?>" style="display: inline-block; vertical-align: top; margin: 10px 20px; border: 1px solid #4480b6; text-decoration: none; background-color: #001862; color: white; font-weight: 900; font-size: 14px; width: 100%; max-width: 180px; padding: 7px; height: 47px; box-sizing: border-box"><span style="display: inline-block;vertical-align: middle;width: 35%;line-height: 16px;">Pagar con</span><img src="<?=base_url();?>media/assets/imgs/iconos/mercado_pago.png" alt="" style="vertical-align: middle;"/></a>

								<a href="<?=site_url('reservas/informar_transferencia/'.encriptar($reserva->code));?>" style="display: inline-block; vertical-align: top; margin: 10px 20px; border: 1px solid #4480b6; text-decoration: none; background-color: #001862; color: white; font-weight: 900; font-size: 14px; width: 100%; max-width: 180px; padding: 5px 14px; height: 47px; box-sizing: border-box">Informar Depósitos o Transferencias</a>
							</div>

							<div style="width: 100%; max-width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 5px; margin-bottom: 15px"></div>

							<div>
								<p style="color: #4090FF; font-size: 14px; margin-top: 20px; margin-bottom: 20px; display: inline-block">
									<img style="display: inline-block; vertical-align: middle; margin-right: 10px" src="<?=base_url();?>media/assets/mails/ic_atencion.jpg" alt="Atencion" />
									<a href="<?=base_url();?>terminos-condiciones.pdf" style="display: inline-block; vertical-align: middle; text-transform: uppercase; font-weight: bold; color: #4090FF; text-decoration: underline">No dejes de leer términos y condiciones (Click Aquí)</a>
								</p>
							</div>
							<? endif; ?>