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
					<a class="logo" href="<?=base_url();?>">
						<img src="<?=base_url();?>media/assets/imgs/iconos/logo.png" alt="Buenas Vibras" />
					</a>

					<a href="<?=site_url('reservas/resumen/'.$hash);?>">Ver resumen de tu compra</a>

					<div class="head">
						<h1>Resumen del viaje</h1>

						<p><strong>Cód. de reserva:</strong> <?=$reserva->code;?></p>
					</div>
				</div>

			<? if(isset($mis_vouchers) && count($mis_vouchers)): ?>				
				<div class="module vouchers">
					<h2>Vouchers del viaje</h2>

					<p>
						<strong>Te informamos que ya registramos la totalidad del pago de tu viaje.</strong>
						<br>A continuación te mostramos el listado de cada uno de tus vouchers para que los puedas descargar e imprimir. Recordá que el día de la salida deberás presentarlos acompañados con tu dni para poder viajar.
					</p>

					<div class="row">
						<? foreach($mis_vouchers as $v): ?>
						<p class="col-xs-6">
							<span><?=$v->archivo;?></span>
							&nbsp;<a target="_blank" href="<?=base_url().'uploads/reservas/'.$v->reserva_id.'/'.utf8_encode($v->archivo);?>">Descargar</a>
						</p>
						<? endforeach; ?>
					</div>
				</div>
			<? endif; ?>

			</div>
			
		</div>
	</div>

	<script src="<?=base_url();?>media/assets/jquery/jquery-1.12.3.min.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-select.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-datepicker.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-datepicker.es.js"></script>
	<script src="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.js"></script>
	<script src="<?=base_url();?>media/assets/fancybox/jquery.fancybox.js"></script>
	<script>
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
	<script src="<?=base_url();?>media/assets/js/jquery.scrollTo.min.js"></script>
	<script src="<?=base_url();?>media/assets/js/main.js"></script>
	<script src="<?=base_url();?>media/assets/js/functions.js"></script>
	<script src="<?=base_url();?>media/assets/js/respond.js"></script>

</body>
</html>