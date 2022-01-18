<html lang="es">
<? if(!isset($hide_header) || !$hide_header): ?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<?=$fb_meta;?>
	
	<title>Buenas Vibras Viajes</title>

	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-select.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-datepicker.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/estilos.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/media-queries.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/selectric/selectric.css" />

	<!-- [if lt IE 9] >
		<script src="<?=base_url();?>media/assets/js/css3-mediaqueries.js"></script>
		<script src="<?=base_url();?>media/assets/js/html5shiv.js"></script>
	<! [endif] -->
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5J23BKX');</script>
	<!-- End Google Tag Manager -->
</head>
<? endif; ?>

<body id="checkout" class="sublanding resumen_viaje">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J23BKX"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<div id="main">

		<div class="aside_content">

			<div class="contenido">

				<div>
					<a class="logo" href="<?=base_url();?>">
						<img src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" alt="Buenas Vibras" style="width:120px;" />
					</a>

					<div class="head">
						<h1>Resumen del viaje</h1>

						<p><strong>Cód. de reserva:</strong> <?=$reserva->code;?></p>
					</div>
				</div>

				<? if ($success): ?>
				<div class="msg alert alert-info">Los datos fueron grabados con éxito.</div>
				<? endif; ?>
				

				<div class="module">
					<h2>Detalle de  tu compra</h2>

					<div class="info_compra">

						<div class="row">
							<div class="col-xs-12 col-md-6">
								<p><strong>Destino:</strong> <span><?=$combinacion->paquete;?>.</span></p>
								<? if($combinacion->itinerario && file_exists('./uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario)): ?>
								<p><strong>Excursiones y/o actividades:</strong> <span><a href="<?=base_url().'uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario;?>">Descargar itinerario</a>.</span></p>
								<? endif; ?>
								<p><strong>Pasajeros:</strong> <span><?=count($pasajeros);?></span></p>
								<p><strong>Lugar de salida:</strong> <span><?=$reserva->lugarSalida?($reserva->nombre_lugar.', '.$reserva->lugarSalida):$combinacion->lugar;?></span></p>
									
								<? 
								$f_inicio = ($combinacion->fecha_salida < $combinacion->fecha_checkin) ? $combinacion->fecha_salida : $combinacion->fecha_checkin; 
								$f_fin = ($combinacion->fecha_regreso > $combinacion->fecha_checkout) ? $combinacion->fecha_regreso : $combinacion->fecha_checkout; 
								?>
								
								<? //defino la fecha a mostrar
								if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
									<? $fecha_elegida = fecha_completa(formato_fecha($reserva->fecha));?>
								<? else: ?>
									<? $fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin));?>	
								<? endif; ?>	
								
								<p><strong><?=dias_viaje($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio,$combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin);?> días - <?=noches_viaje($combinacion->fecha_checkin,$combinacion->fecha_checkout)?> noches:</strong> <span><?=$fecha_elegida;?>.</span></p>
								<p><strong>Transporte:</strong> <span><?=$combinacion->transporte;?></span></p>
							</div>

							<div class="col-xs-12 col-md-6">
								<p><strong>Alojamiento:</strong> <span><?=$combinacion->alojamiento;?></span></p>
								<p><strong>Tipo de habitación:</strong> <span><?=$combinacion->habitacion;?></span></p>
								<p><strong>Pensión:</strong> <span><?=$combinacion->regimen;?></span></p>
								<? if($reserva->nombre_adicionales): ?>
									<p><strong>Adicionales:</strong> <span><?=$reserva->nombre_adicionales;?>.</span></p>
								<? endif; ?>
								<p><span><a href="<?=site_url($combinacion->slug);?>">Ver más info del viaje</a></span></p>
							</div>
						</div>

					</div>

					<hr />

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

				<? if($reserva->estado_id != 5): //no anulada ?>
					
					<?=$completar_datos_form;?>
				
					<?=$generar_pago_form;?>
					
					<?=@$vouchers_viaje;?>
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

	<script>
		var currentURL = '<?=current_url();?>';
		var baseURL = '<?=base_url();?>';
		var mp_gastos_admin = '<?=$this->settings->mp_gastos_admin;?>';
		var start_date = '<?=date("d/m/Y",strtotime("-1 year",strtotime(date("Y-m-d"))));?>';
		var end_date = '<?=date("d/m/Y",strtotime("+1 year",strtotime(date("Y-m-d"))));?>';
	</script>
	<script type="text/javascript">
	var tipo_viaje = "<?=(!$paquete->grupal && $paquete->exterior)?'individual':'grupal';?>";
	var origen_activo = "<?=($paquete->grupal)?'backend':'mercadopago';?>";
	var viaje_ind_ext = '<?=$paquete->precio_usd;?>';
	var moneda = '<?=$paquete->precio_usd?'dolares':'pesos';?>';
	var aside = '<?=$this->detector->isMobile()?"aside_mobile":"aside_desktop";?>';
	var mobile = '<?=$this->detector->isMobile()?1:0;?>';
	</script>
	
	<?=$this->carabiner->display('js');?>
		
	<script type="text/javascript">
	(function(){function $MPBR_load(){window.$MPBR_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = ("https:"==document.location.protocol?"https://www.mercadopago.com/org-img/jsapi/mptools/buttons/":"http://mp-tools.mlstatic.com/buttons/")+"render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPBR_loaded = true;})();}window.$MPBR_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPBR_load) : window.addEventListener('load', $MPBR_load, false)) : null;})();
	</script>
</body>
</html>