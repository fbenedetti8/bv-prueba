<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title></title>

	<style>
		html,
		body {
			margin: 0;
			padding: 0
		}
	</style>

</head>
<body style="background: #f5f5f5;">

	<center style="background: #f5f5f5; padding: 30px 0">
		<div style="background: white; border-radius: 13px; overflow: hidden; max-width: 580px; margin: auto">

			<table style="font-family: 'arial'; background: white; border: none; border-collapse: collapse" border="0">
				<tr style="text-align: center">
					<td style="padding: 20px">
						<a href="#" style="font-family: 'arial'; display: inline-block; margin-bottom: 30px">
							<img style="width: 100%; max-width: 100%" src="<?=base_url();?>media/assets/mail-reserva/buenas_vibras.png" alt="Buenas Vibras" />
						</a>

						<p style="font-family: 'arial'; font-size: 40px; color: #282b5a; margin: 0"><strong style="font-family: 'arial'; font-size: 40px; color: #282b5a">Reserva anulada</strong></p>

						<p style="font-family: 'arial'; color: #282b5a; font-size: 20px; margin: 15px 0 0">
							La reserva <?=$reserva->code;?> del viaje <?=$reserva->nombre;?>, se ha anulado por falta de pago.
							<br>
							En caso de que hayas realizado un pago te pedimos que te comuniques con nosotros a la brevedad para reestablecer tu reserva anulada.
						</p>

					</td>
				</tr>

						
			<?=$mail_footer;?>