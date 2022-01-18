<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<style>
	label.static { width:100%;display: block; }
	</style>
</head>
<body id="checkout" class="sublanding resumen_viaje">

	<div id="main">

		<div class="aside_content">

			<div class="contenido">

				<div>
					<a class="logo" href="#">
						<img src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" alt="Buenas Vibras" style="width:120px;"/>
					</a>

					<div class="head">
						<h1>Voucher de viaje</h1>

						<p><strong>Cód. de reserva:</strong> <?=$reserva->code;?></p>
					</div>
				</div>

				<div class="module">
					<h2>Detalle de  tu compra</h2>

					<div class="info_compra">

						<div class="row">
							<div class="col-xs-12 col-md-6">
								<p><strong>Destino:</strong> <span><?=$combinacion->paquete;?>.</span></p>
								<? if($combinacion->itinerario && file_exists('./uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario)): ?>
								<p><strong>Excursiones y/o actividades:</strong> <span><a href="<?=base_url().'uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario;?>">Descargar itinerario</a>.</span></p>
								<? endif; ?>
								<p><strong>Pasajeros:</strong> <span><?=$combinacion->pax;?></span></p>
								<p><strong>Lugar de salida:</strong> <span><?=$reserva->lugarSalida?($reserva->nombre_lugar.', '.$reserva->lugarSalida):$combinacion->lugar;?></span></p>
								<? //defino la fecha a mostrar
								if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
									<? $fecha_elegida = fecha_completa(formato_fecha($reserva->fecha));?>
								<? else: ?>
									<? $fecha_elegida = fecha_completa(formato_fecha($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio),formato_fecha($combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin));?>	
								<? endif; ?>	
								
								<p><strong><?=dias_viaje($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio,$combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin);?> días - <?=noches_viaje($combinacion->fecha_checkin,$combinacion->fecha_checkout)?> noches:</strong> <span><?=$fecha_elegida;?>.</span></p>
								<p><strong>Transporte:</strong> <span><?=$combinacion->transporte;?></span></p>
							
								<p><strong>Alojamiento:</strong> <span><?=$combinacion->alojamiento;?></span></p>
								<p><strong>Tipo de habitación:</strong> <span><?=$combinacion->habitacion;?></span></p>
								<p><strong>Pensión:</strong> <span><?=$combinacion->regimen;?></span></p>
								<? if($reserva->nombre_adicionales): ?>
									<p><strong>Adicionales:</strong> <span><?=$reserva->nombre_adicionales;?>.</span></p>
								<? endif; ?>
							</div>
						</div>

					</div>

					<hr />

					<div class="info_valores">
						<div>
							<p>Abonaste: <?=$precios['monto_abonado'];?></p>
							<p>Saldo pendiente: <?=$precios['saldo_pendiente'];?></p>
							<p>Total ARS: <?=$precios['precio_total'];?></p>
						</div>
					</div>
				</div>

				<hr />
					
				<? if($reserva->estado_id != 5): //no anulada ?>
					
					<?=$completar_datos_form;?>
				
					
				<? else: //anulada ?>
				<div class="msg alert alert-danger">Tu reserva se encuentra <strong>ANULADA</strong>.<br>Por cualquier duda o inconveniente, por favor comunicate con nosotros.<br/>
					<ul>
						<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
						<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810.</a> de Lunes a Viernes de 10 a 19 hs.</li>
					</ul>
				</div>
				<? endif; ?>
			</div>

		</div>

	</div>

</body>
</html>