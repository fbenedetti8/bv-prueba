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

						<p style="font-family: 'arial'; font-size: 40px; color: #282b5a; margin: 0"><strong style="font-family: 'arial'; font-size: 40px; color: #282b5a">Informe de pago recibido</strong></p>

						<p style="font-family: 'arial'; color: #282b5a; font-size: 20px; margin: 15px 0 0">
							Recibimos tu informe de pago
									<br/>Una vez acreditado recibirás otro email junto con el comprobante correspondiente.
									<br/>Este proceso podría demorar unas 48hs hábiles.
									<br />
						</p>

						<div style="text-align: center">
							<a href="<?=site_url('reservas/resumen/'.encriptar($reserva->code));?>" target="_blank" style="font-family: 'arial'; box-sizing: border-box; background: #ff5c5d; color: white; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 20px auto">Ver datos del viaje</a>
						</div>

						<div style="border-top: 1px solid #cccccc; padding: 20px 0">

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Medio de Pago:</strong><br />
								<?=$informe->tipo_pago;?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Banco:</strong><br />
								<?=$informe->banco;?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Fecha y hora de Pago:</strong><br />
								<?=date('d/m/Y',strtotime($informe->fecha_pago));?> <?=$informe->hora_pago;?>
							</p>

							<p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Monto de Pago:</strong><br />
								<?=@$informe->tipo_moneda;?> <?=number_format($informe->monto_pago,2,',','.');?>
							</p>

							<? if($informe->comprobante && file_exists('./uploads/reservas/'.$reserva->id.'/'.$informe->comprobante)): ?>

								<p style="font-family: 'arial'; color: #282b5a; font-size: 20px">
									<strong style="font-family: 'arial'">Comprobante:</strong><br />
									<a href="<?=base_url().'uploads/reservas/'.$reserva->id.'/'.$informe->comprobante;?>" target="_blank">Ver comprobante</a>
								</p>
							<? endif; ?>


						</div>

					</td>
				</tr>

				<?=$mail_datos_viaje;?>

						
			<?=$mail_footer;?>