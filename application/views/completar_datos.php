<html lang="es">
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

<body id="checkout" class="sublanding">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J23BKX"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	
	<div id="main">

		<div class="aside_content">

			<div class="contenido">

				<div>
					<a class="logo" href="#">
						<img src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" alt="Buenas Vibras" style="width:120px;" />
					</a>

					<a href="<?=site_url('reservas/resumen/'.$hash);?>">Ver resumen de tu compra</a>
					
					<div class="head">
						<h1>Resumen del viaje</h1>

						<p><strong>Cód. de reserva:</strong> <?=$reserva->code;?></p>
					</div>
				</div>
				
				<?=$completar_datos_form;?>
			
				<? if($reserva->estado_id == 5): //anulada ?>
					<div class="msg alert alert-danger">Tu reserva se encuentra <strong>ANULADA</strong>.<br>Por cualquier duda o inconveniente, por favor comunicate con nosotros:<br/>
						<ul>
							<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
							<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810</a> de Lunes a Viernes de 10 a 19 hs.</li>
						</ul>
					</div>
				<? endif; ?>
				
			</div>
			
		</div>
	</div>

	<script>
		var baseURL = '<?=base_url();?>';
		var mp_gastos_admin = '<?=$this->settings->mp_gastos_admin;?>';
		var start_date = '<?=date("d/m/Y",strtotime("-1 year",strtotime(date("Y-m-d"))));?>';
		var end_date = '<?=date("d/m/Y",strtotime("+1 year",strtotime(date("Y-m-d"))));?>';
		var mobile = '<?=$this->detector->isMobile()?1:0;?>';
	</script>
	
	<?=$this->carabiner->display('js');?>
	
</body>
</html>