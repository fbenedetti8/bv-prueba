						<div style="background-color: #f7f7f7; padding: 0 20px">
							<? if(!isset($ocultar_boton_pago)): ?>
							<div style="padding-top: 5px; margin: auto; background-color: white">
								<p style="display: inline-block; vertical-align: middle; text-transform: uppercase; color: #001862; font-size: 16px; margin-right: 20px; font-weight: bold">Formas de Pago</p>
								<img style="max-width: 100%; display: inline-block; vertical-align: middle" src="<?=base_url();?>media/assets/mails/formas_de_pago.png" alt="MasterCard - Transferencia Bancaria - Rapipago - PagoFacil" />
							</div>
							<? endif; ?>

							<div style="text-align: left">
								<span style="display: block; font-size: 18px; margin: 10px 0; margin-top: 15px; padding-left: 3px">Si tenés dudas, contactate con nosotros</span>

								<div>
									<div style="width: 100%; max-width: 45px; display: inline-block; vertical-align: middle; margin-right: 10px">
										<img style="max-width: 100%; margin: auto; display: block" src="<?=base_url();?>media/assets/mails/info_buenasvibras.png" alt="Escribinos" />
									</div>
									<p style="display: inline-block; vertical-align: middle; font-size: 12px">
										<span style="text-transform: uppercase; display: block; font-weight: bold; color: #001862">Escribinos</span>
										<a href="mailto:reservas@buenas-vibras.com.ar" style="color: #2db466; font-size: 14px; font-weight: bold; text-decoration: none">reservas@buenas-vibras.com.ar</a>
									</p>
								</div>

								<div>
									<div style="width: 100%; max-width: 45px; display: inline-block; vertical-align: middle; margin-right: 10px">
										<img style="max-width: 100%; margin: auto; display: block" src="<?=base_url();?>media/assets/mails/icono_telefono.png" alt="Teléfono" />
									</div>
									<p style="display: inline-block; vertical-align: middle; font-size: 12px">
										<span style="text-transform: uppercase; display: block; font-weight: bold; color: #001862">Teléfono</span>
										<a href="tel:01152353810" style="color: #ef6c47; font-size: 14px; font-weight: bold; text-decoration: none"><?=isset($reserva) ? $reserva->telefono : $orden->telefono;?></a>
									</p>
								</div>

								<? if(isset($sucursales) && $sucursales): ?>
								<? foreach($sucursales as $s): ?>
								<div>
									<div style="width: 100%; max-width: 45px; display: inline-block; vertical-align: middle; margin-right: 10px">
										<img style="max-width: 100%; margin: auto; display: block" src="<?=base_url();?>media/assets/mails/ic_ubicacion.png" alt="Teléfono" />
									</div>
									<p style="display: inline-block; vertical-align: middle; font-size: 12px">
										<span style="text-transform: uppercase; display: block; font-weight: bold; color: #001862"><?=$s->nombre;?></span>
										<a href="tel:01152353810" style="color: black; font-size: 13px; font-weight: bold; text-decoration: none"><?=$s->direccion;?></a>
									</p>
								</div>
								<? endforeach; ?>
								<? else: ?>
								<div>
									<div style="width: 100%; max-width: 45px; display: inline-block; vertical-align: middle; margin-right: 10px">
										<img style="max-width: 100%; margin: auto; display: block" src="<?=base_url();?>media/assets/mails/ic_ubicacion.png" alt="Teléfono" />
									</div>
									<p style="display: inline-block; vertical-align: middle; font-size: 12px">
										<span style="text-transform: uppercase; display: block; font-weight: bold"><?=$s->nombre;?></span>
										<a href="tel:01152353810" style="color: black; font-size: 13px; font-weight: bold; text-decoration: none"><?=$s->direccion;?></a>
									</p>
								</div>
								<? endif; ?>
								
							</div>

						</div>


						<div style="padding: 0 20px; display:none;">
							<p style="width: 100%; max-width: 450px; color: #999999; font-size: 14px; text-transform: uppercase; line-height: 18px; margin: 7px auto; font-weight: bold">Buenas Vibras Viajes - EVT Leg 14.641</p>
							<p style="width: 100%; max-width: 450px; color: #999999; font-size: 11px; text-transform: uppercase; line-height: 14px; margin: 0 auto; font-weight: bold">Si no querés recibir mas información de nuestra empresa, hacé <a href="#" style="color: #0199d7; text-decoration: underline">Click Aquí</a> y serás removido en forma automática de la lista de envíos.</p>
						</div>