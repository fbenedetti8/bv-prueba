<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
		<!--[if (gte mso 9)|(IE)]>
			<style type="text/css">
				table {border-collapse: collapse;}
			</style>
		<![endif]-->
</head>
<body style="margin: 0; padding: 0; min-width: 100%; background-color: #ffffff; font-family: 'arial'">
	<center style="width: 100%; table-layout: fixed; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
		<div style="max-width: 600px">
			
			<table style="padding-top: 10px; width: 100%; border-spacing: 0; color: #333333;" align="center">
				<tr>
					<td style="padding: 0; text-align: center; font-size: 0;">

						<div style="padding: 0 20px; background-color: white">

							<div>
								<div style="width: 50%; display: inline-block; vertical-align: middle; text-align: left">
									<a href="<?=site_url();?>" style="display: inline-block">
										<img style="max-width: 100%; max-height: 50px" src="<?=base_url();?>media/assets/mails/logo.png" alt="Buenas Vibras viajes" />
									</a>
								</div>

								<div style="width: 50%; display: inline-block; text-align: right; vertical-align: middle">
									<a href="http://www.facebook.com/buenas.vibras">
										<img src="<?=base_url();?>media/assets/mails/facebook.png" alt="Facebook" />
									</a>
									<a href="https://www.instagram.com/buenasvibrasviajes" style="margin-left: 20px">
										<img src="<?=base_url();?>media/assets/mails/instagram.png" alt="Instagram" />
									</a>
								</div>

								<div style="margin-top: 10px; height: 6px; background-color: #00efd6; width: 100%"></div>
							</div>


							<div>
								<h1 style="color: #001862; font-family: 'arial'; font-size: 28px; margin-bottom: 5px">¡Gracias por tu pago!</h1>

								<p style="font-size: 14px; line-height: 22px; width: 100%; margin: 10px auto; display: block; color: #001862">
									<? $equivalencia='';
									if($reserva->precio_usd && !$pago->pago_usd): 
										//si el paquete es en USD y el pago en PESOS ?>
										<? $equivalencia = ' (equivalentes a USD '.number_format($pago->haber_usd,2,',','.').')'; ?>
									<? endif; ?>
									Recibimos tu pago de: <?=($pago->pago_usd?('USD '.number_format($pago->haber_usd,2,',','.')):('ARS '.number_format($pago->haber,2,',','.'))).$equivalencia;?> correspondiente a tu viaje:
								</p>

							</div>

							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Número de Reserva:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$reserva->code;?></p>
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
									<p style="font-size: 12px; text-align: left; line-height: 23px; margin: 5px 0; color: #001862"><?=$combinacion->destino;?></p>
								</div>

								
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Salida:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$fecha_elegida;?> <? if($reserva->hora != ''): ?>(<?=$reserva->hora;?> hs)<? endif; ?><br><?=$reserva->lugarSalida;?></p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Regreso:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$fecha_elegida_fin;?></p>
								</div>
							</div>
							
							<div>
								<a href="<?=site_url('reservas/resumen/'.encriptar($reserva->code));?>" style="box-sizing: border-box; color: #001862; background-color: #f7f7f7; font-size: 16px; font-weight: 900; text-align: center; padding: 10px 20px; border: 1px solid #6f6f6f; text-decoration: none; display: block; width: 100%; max-width: 255px; margin: 18px auto; text-transform: uppercase">Ver datos del viaje</a>
							</div>

							
							<div style="width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 15px; margin-bottom: 15px"></div>

							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Saldo del viaje:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862">Total: <?=strip_tags($precios['precio_total'],'<sup>');?></p>
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862">Abonaste <?=strip_tags($precios['monto_abonado'],'<sup>');?></p>
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862">Saldo pendiente: <?=strip_tags($precios['saldo_pendiente'],'<sup>');?></p>
									<? if($reserva->fecha_limite_pago_completo): ?>
										<span style="width: 100%; text-align: left; display: block; color: #e71656; font-size: 12px; margin-top: 8px; font-weight: 900">Tenés tiempo hasta el <?=date('d/m',strtotime(fecha_saldar_viaje($reserva->id)));?>
										</span>
									<? endif; ?>
								</div>
							</div>


							<div style="width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 15px; margin-bottom: 15px"></div>


							<div>
								<p style="color: #343434; font-family: arial; font-size: 14px">Para generar un nuevo pago, elegi una opción:</p>

								<a href="<?=site_url('reservas/generar_pago/'.encriptar($reserva->code));?>" style="display: inline-block; vertical-align: top; margin: 10px 20px; border: 1px solid #4480b6; text-decoration: none; background-color: #001862; color: white; font-weight: 900; font-size: 14px; width: 100%; max-width: 180px; padding: 7px; height: 47px; box-sizing: border-box"><span style="display: inline-block;vertical-align: middle;width: 35%;line-height: 16px;">Pagar con</span><img src="<?=base_url();?>media/assets/imgs/iconos/mercado_pago.png" alt="" style="vertical-align: middle;"/></a>

								<a href="<?=site_url('reservas/informar_transferencia/'.encriptar($reserva->code));?>" style="display: inline-block; vertical-align: top; margin: 10px 20px; border: 1px solid #4480b6; text-decoration: none; background-color: #001862; color: white; font-weight: 900; font-size: 14px; width: 100%; max-width: 180px; padding: 5px 14px; height: 47px; box-sizing: border-box">Informar Depósitos o Transferencias</a>
							</div>

						</div>

						<?=$mail_footer;?>

					</td>
				</tr>
			</table>
			
		</div>
	</center>
</body>
</html>