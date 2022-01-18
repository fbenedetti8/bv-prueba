<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Bienvenido | Buenas Vibras Viajes</title>

	<meta name="description" content="¡La empresa N°1 en Turismo para jóvenes!">

	<link type="image/x-icon" rel="shortcut icon" href="<?=base_url();?>media/assets/images/icons/favicon.ico">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

	<link rel="stylesheet" href="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.css">
	<link rel="stylesheet" href="<?=base_url();?>media/assets/owl-carousel/owl.theme.default.min.css">
	<link rel="stylesheet" href="<?=base_url();?>media/assets/selectric/selectric.css">
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/app.css" />
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5J23BKX');</script>
	<!-- End Google Tag Manager -->
</head>


<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J23BKX"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<div class="centered-container">
		<div class="equipo__container">
			<div class="equipo__title">
				CONOCE A NUESTRO <span>EQUIPO</span>
			</div>
			<div class="equipo__description">
				¿Querés saber más sobre quienes diseñan tu experiencia? Nos gusta lo que hacemos, lo disfrutamos y estamos orgullosos,
				por eso <span>te contamos lo que significa Buenas Vibras para nosotros.</span>
			</div>
			<div class="equipo__slider-container">
				<div class="equipo__prev-arrow"><img src="<?=base_url();?>media/assets/images/icon/red-left.png"></div>
				<div class="equipo__slider owl-carousel owl-theme">
					<div class="equipo__slider-item"><img src="<?=base_url();?>media/assets/images/banners/intermedia_mb.jpg"><div class="equipo__slider-text">EQUIPO DE MARKETING ARGENTINA</div></div>
					<div class="equipo__slider-item"><img src="<?=base_url();?>media/assets/images/banners/intermedia_mb.jpg"><div class="equipo__slider-text">EQUIPO DE COORDINACIÓN EN VIAJE</div></div>
					<div class="equipo__slider-item"><img src="<?=base_url();?>media/assets/images/banners/intermedia_mb.jpg"><div class="equipo__slider-text">EQUIPO DE ECOMMERCE</div></div>
					<div class="equipo__slider-item"><img src="<?=base_url();?>media/assets/images/banners/intermedia_mb.jpg"><div class="equipo__slider-text">EQUIPO DE COMUNICACIÓN</div></div>
				</div>
				<div class="equipo__next-arrow"><img src="<?=base_url();?>media/assets/images/icon/red-right.png"></div>
			</div>
			<div class="equipo__redes">
				<span class="equipo__red"><a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes" target="_blank"><img src="<?=base_url();?>media/assets/images/icon/instagram.png"><span>Ver más historias en Instagram</span></a></span>
				<span class="equipo__red"><a rel="nofollow" href="http://www.facebook.com/buenas.vibras" target="_blank"><img src="<?=base_url();?>media/assets/images/icon/facebook.png"><span>Seguinos en Facebook</span></a></span>
			</div>
			<div class="equipo__blue-wave"><img src="<?=base_url();?>media/assets/images/shapes/blue-wave.png"></div>
			<div class="equipo__single-hero-wave"><img src="<?=base_url();?>media/assets/images/shapes/hero-single-wave.png"></div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
	<script>

	$(document).ready(function() {
		$(".equipo__slider").owlCarousel({
			loop: true,
			items: 4,
			itemsDesktop : [992,1],
			navText : ["",""],
			responsive: {
				0: {
					dotsEach: 0,
					items: 1
				},
				768: {
					dotsEach: 0,
					items: 3
				},
				992: {
					dotsEach: 0,
					items: 4
			}}
		});
	});

	var theCarousel = $('.owl-carousel');

	$(".equipo__prev-arrow").click(function() {
		theCarousel.trigger('prev.owl.carousel');
	});
	$(".equipo__next-arrow").click(function() {
		theCarousel.trigger('next.owl.carousel');
	});

	</script>
</body>
</html>