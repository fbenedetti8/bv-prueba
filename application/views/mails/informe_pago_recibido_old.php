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
			
			<table style="padding-top: 10px; border-spacing: 0; color: #333333;" align="center">
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
								<h1 style="color: #001862; font-family: 'arial'; font-size: 28px; margin-bottom: 5px">Informe de pago recibido</h1>

								<p style="font-size: 14px; line-height: 22px; width: 100%; margin: 10px auto; display: block">
									Recibimos tu informe de pago
									<br/>Una vez acreditado recibirás otro email junto con el comprobante correspondiente.
									<br/>Este proceso podría demorar unas 48hs hábiles.
									<br />
								</p>

								<a href="<?=site_url('reservas/resumen/'.encriptar($reserva->code));?>" style="box-sizing: border-box; color: #001862; background-color: #f7f7f7; font-size: 16px; font-weight: 900; text-align: center; padding: 10px 20px; border: 1px solid #6f6f6f; text-decoration: none; display: block; width: 100%; max-width: 255px; margin: 18px auto; text-transform: uppercase">Ver datos del viaje</a>
							</div>

							<div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Medio de Pago:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$informe->tipo_pago;?></p>
								</div>

								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Banco:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$informe->banco;?></p>
								</div>

								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Fecha y hora de Pago:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=date('d/m/Y',strtotime($informe->fecha_pago));?> <?=$informe->hora_pago;?></p>
								</div>

								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Monto de Pago:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=@$informe->tipo_moneda;?> <?=number_format($informe->monto_pago,2,',','.');?></p>
								</div>

								<? if($informe->comprobante && file_exists('./uploads/reservas/'.$reserva->id.'/'.$informe->comprobante)): ?>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Comprobante:</p>
								</div>
								<div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
									<p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><a href="<?=base_url().'uploads/reservas/'.$reserva->id.'/'.$informe->comprobante;?>" target="_blank">Ver comprobante</a></p>
								</div>
								<? endif; ?>


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