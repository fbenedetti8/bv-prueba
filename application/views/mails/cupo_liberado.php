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
			
			<table style="border-spacing: 0; color: #333333;" align="center">
				<tr>
					<td style="padding: 0; text-align: center; font-size: 0;">

						<div style="padding: 0 20px; background-color: white">

							<div>
								<div style="width: 50%; display: inline-block; vertical-align: middle; text-align: left">
									<a href="<?=site_url();?>" style="display: inline-block">
										<img style="max-width: 100%" src="<?=base_url();?>media/assets/mails/logo.png" alt="Buenas Vibras viajes" />
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

								<div style="margin-top: 10px">
									<img style="width: 100%" src="<?=base_url();?>media/assets/mails/border.png" alt="---" />
								</div>
							</div>


							<div>
								<h1 style="color: #00529b; font-family: 'arial'; font-size: 28px; margin-bottom: 5px">Se liberó un lugar!</h1>

								<p style="font-size: 14px; line-height: 22px; width: 100%; margin: 10px auto; display: block">
									Tu reserva <?=$reserva->code;?> del viaje <?=$reserva->nombre;?>, se encuentra en lista de espera, pero contamos con cupo disponible.
									<br>
									En caso de que te interese viajar te pedimos que te comuniques con nosotros a la brevedad para confirmar tu reserva.
									<br>
									Si ya decidiste no viajar y querés darte de baja de la lista, podés hacerlo haciendo <a href="<?=site_url('reservas/baja/'.$hash_reserva);?>">click acá</a>
								</p>
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