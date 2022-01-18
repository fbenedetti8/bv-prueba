<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	
	<meta name="p:domain_verify" content="4df894be900dd8cf4984c06cfd6114d2"/>
	<meta name="facebook-domain-verification" content="etkg5jhmzuznjbzer8fsnkc4x48e54" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<?=$fb_meta;?>
	
	<title><?=@$seo_title;?></title>
	<meta name="description" content="<?=@$seo_description;?>">
	<meta name="keywords" content="<?=@$seo_keywords;?>">

	<link type="image/x-icon" rel="shortcut icon" href="<?=base_url();?>favicon.ico">

	<!-- CSS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	
	<? $this->carabiner->display('css'); ?>

	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/fixed_css.css?v=1" />

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-4354235-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	
	fbq('init', '102528616748506');
	fbq('track', "PageView");</script>
	<!-- End Facebook Pixel Code -->
	
	<meta name="google-site-verification" content="WpYczJ9WbZjtysspbbFr3AlO0YzJkJf2h0WYuEAyf18" />

	<!-- GOOGLE RECAPTCHA API -->
	<script defer src='https://www.google.com/recaptcha/api.js'></script>

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5J23BKX');</script>
	<!-- End Google Tag Manager -->
	
</head>
<body id="<?=@$body_id;?>" class="<?=@$body_class;?>">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J23BKX"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=102528616748506&ev=PageView&noscript=1"
	/></noscript>

	<!-- FACEBOOK API -->
	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?=$this->config->item("fb_id");?>',
      xfbml      : true,
      version    : 'v2.7'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/es_LA/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
	</script>


	<div class="popup full_popup popup_ayuda">
		<div class="wrapper">

			<a href="javascript:void(0)" class="btn_close">
				<i class="icon_close_white"></i>
				<span class="sr-only">Cerrar</span>
			</a>


			<div class="content">

				<div class="module__Menuhelp">
					<div class="nav help">

						<div class="select-options">
							<p>Podemos ayudarte a resorver tus dudas por distintos medios,<strong> ¿Cúal prefieres?</strong></p>
						</div>
						<div class="hand-options">
							<ul class="listado social">
								<li>
									<a rel="nofollow" href="tel:01152353810" target="_blank">
										<i class="fas fa-phone"></i>
									Telefono</a>
								</li>
								<li>
									<a rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank">
										<i class="fab fa-whatsapp"></i>
									Whatsapp</a>
								</li>
								<li>
									<a rel="nofollow" href="http://www.facebook.com/buenas.vibras" target="_blank">
										<i class="fab fa-facebook-square"></i>
									Facebook</a>
								</li>
								<li>
									<a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes" target="_blank">
										<i class="fab fa-instagram"></i>
									Instagram</a>
								</li>
							</ul>
							<div class="option-emails open">
								<div class="m_title">
									<i class="far fa-envelope"></i>
									<p>Email</p>
								</div>
								<ul class="listado mail">
									<li>
										<i class="fas fa-arrow-right"></i>
										<div class="info">
											<p class="text">Ayuda en general</p>
											<p class="mail"><a rel="nofollow" href="mailto: info@buenas-vibras.com">info@buenas-vibras.com</a></p>
										</div>
									</li>
									<li>
										<i class="fas fa-arrow-right"></i>
										<div class="info">
											<p class="text">Ayuda para realizar tu reserva online</p>
											<p class="mail"><a rel="nofollow" href="mailto: reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></p>
										</div>
									</li>
								</ul>
							</div>
							<ul class="listado faqs">
								<li>
									<a href="<?=site_url('preguntas-frecuentes');?>">
										<i class="fas fa-question"></i>
										<span>Preguntas Frecuentes</span>
									</a>
								</li>
							</ul>
						</div>

					</div>
				</div>

			</div>

		</div>
	</div>


	<? if(@$page == 'home'): ?>
	<!-- // POPUP "CUANDO TE GUSTARÍA VIAJAR" -->

	<div class="popup full_popup popup_fechas">
		<div class="wrapper">

			<a href="javascript:void(0)" class="btn_close">
				<i class="icon_close_white"></i>
				<span class="sr-only">Cerrar</span>
			</a>


			<div class="content">

				<div class="title">
					<i class="icon-calendar"></i>
					<p>¿<strong>Cuándo</strong> te gustaría viajar?</p>
				</div>

				<div class="tels">
					<ul>
						<? foreach ($fechas as $v): ?>
						<li>
							<a href="<?=site_url('proximos-viajes/'.$this->config->item("uri_regiones").'/'.$v->fecha);?>"><?=fecha_buscador($v->fecha);?></a>
						</li>
						<? endforeach; ?>
					</ul>
				</div>
			

			</div>

		</div>
	</div>

	<!-- // FIN POPUP "CUANDO TE GUSTARÍA VIAJAR" -->



	<!-- // POPUP "A DONDE QUIERE IR" -->

	<div class="popup full_popup popup_lugares">
		<div class="wrapper">

			<a href="javascript:void(0)" class="btn_close">
				<i class="icon_close_white"></i>
				<span class="sr-only">Cerrar</span>
			</a>


			<div class="content">

				<div class="title">
					<i class="icon-place"></i>
					<p>¿A <strong>dónde</strong> quieres ir ?</p>
				</div>

				<div class="tels">
					<ul>
						<? foreach ($categorias_activas as $d): ?>
						<li>
							<a href="<?=site_url('proximos-viajes/'.$d->slug);?>"><?=$d->nombre;?></a>
						</li>
						<? endforeach; ?>
					</ul>
				</div>
			

			</div>

		</div>
	</div>

	<!-- // FIN POPUP "A DONDE QUIERE IR" -->
	<? endif; ?>


	<!-- // POPUP TELÉFONOS -->

	<div class="popup full_popup popup_telefonos">
		<div class="wrapper">

			<a href="javascript:void(0)" class="btn_close">
				<i class="icon_close_white"></i>
				<span class="sr-only">Cerrar</span>
			</a>


			<div class="content">

				<div class="title">
					<i class="icon-tel_white"></i>
					<p><strong>¡Llamanos!</strong></p>
					<p>Toca un número para llamar</p>
				</div>

				<div class="tels">
					<ul>
						<? foreach ($telefonos as $t) :?>
						<li>
							<a rel="nofollow" href="tel:<?=str_replace([" ","-"],["",""],$t->telefono);?>">
								<i>
									<? if($t->imagen && file_exists('./uploads/telefonos_contacto/'.$t->id.'/'.$t->imagen)): ?>
										<img src="<?=base_url();?>uploads/telefonos_contacto/<?=$t->id;?>/<?=$t->imagen;?>" alt="<?=$t->pais;?>" />
									<? endif; ?>
								</i>
								<span><?=$t->telefono;?></span>
							</a>
						</li>
						<? endforeach; ?>
					</ul>
				</div>
			</div>

		</div>
	</div>

	<!-- // FIN POPUP TELÉFONOS -->



	<!-- // POPUP PRÓXIMOS VIAJES -->

	<div class="popup offet_popup popup_proximos_viajes">
		<div class="wrapper">

			<a href="javascript:void(0)" class="btn_close">
				<i class="icon_close"></i>
				<span class="sr-only">Cerrar</span>
			</a>


			<div class="content">

				<div class="links">

					<a href="<?= base_url() ?>" class="popup_logo">
						<img src="<?= base_url() ?>media/assets/images/logo/logo.png" alt="Buenas Vibras" />
					</a>

					<ul>
						<li>
							<a href="<?=site_url('proximos-viajes');?>">Próximos Viajes</a>
						</li>
						<li>
							<a href="<?=site_url('quienes-somos');?>">Quiénes somos</a>
						</li>
						<li>
							<a href="<?=base_url();?>como-reservar">¿Cómo reservar?</a>
						</li>
						<li>
							<a href="<?=site_url('preguntas-frecuentes');?>">Preguntas frecuentes</a>
						</li>
						<li>
							<a href="<?=base_url();?>contacto">Contacto</a>
						</li>
						<li>
							<a href="<?=base_url();?>blog">Blog</a>
						</li>
						<li>
							<a href="<?=site_url('home/terminos_y_condiciones')?>" target="_blank">Terminos y condiciones</a>
						</li>
					</ul>
				</div>


				<div class="social">
					<span>
						<i class="icon-share"></i>
						<span class="Compartir"></span>
					</span>

					<ul>
						<li>
							<a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes" target="_blank">
								<i class="fab fa-instagram"></i>
								<span class="sr-only">Instagram</span>
							</a>
						</li>
						<li>
							<a rel="nofollow" href="http://www.facebook.com/buenas.vibras" target="_blank">
								<i class="fab fa-facebook-square"></i>
								<span class="sr-only">Facebook</span>
							</a>
						</li>
					</ul>
				</div>

			</div>

		</div>
	</div>

	<!-- // FIN POPUP PRÓXIMOS VIAJES -->









	<header>
		<!-- Nav -->

		<nav class="nav_header fixed">

			<? if(esVendedor() || $this->session->userdata('perfil') == 'VEN'): ?>
				<div class="submenu">
					<div class="row vendedor-logged"><div class="col-md-12 etiqueta"><i class="icon-person"></i><span><strong>Hola <?=admin_username();?>!</strong> Estás logueado como vendedor, las operaciones quedarán registradas a tu nombre.</span></div></div>
				</div>
			<? endif; ?>

			<div class="container">

				<div class="nav_wrapper">

					<button class="btn_menu">
						<i class="icon-menu"></i>
						<span class="sr-only">Menú</span>
					</button>

					<div class="logo">
						<a href="<?=base_url();?>">
							<img class="desktop" src="<?=base_url();?>media/assets/images/logo/logo.png" alt="Buenas Vibras" />
							<img class="mobile" src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" alt="Buenas Vibras" />
						</a>
					</div>

					<div class="links_block">
						<div class="links">
							<ul>
								<li>
									<a href="<?=base_url();?>proximos-viajes">Próximos viajes</a>
								</li>
								<li>
									<a href="<?=base_url();?>quienes-somos">Quiénes somos</a>
								</li>
								<li>
									<a href="<?=base_url();?>contacto">Contacto</a>
								</li>
							</ul>
						</div>


						<div class="button_links">

							<div class="tel_box_wrapper">
								<a href="javascript:void(0)" class="button_tel">
									<i class="icon-tel"></i>
									<span>Llamanos</span>
								</a>

								<div class="dropdown_tel">
									<ul>
										<? foreach ($telefonos as $t) :?>
										<li>
											<a rel="nofollow" href="tel:<?=str_replace([" ","-"],["",""],$t->telefono);?>">
												<i>
													<? if($t->imagen && file_exists('./uploads/telefonos_contacto/'.$t->id.'/'.$t->imagen)): ?>
														<img src="<?=base_url();?>uploads/telefonos_contacto/<?=$t->id;?>/<?=$t->imagen;?>" alt="<?=$t->pais;?>" />
													<? endif; ?>
												</i>
												<span><?=$t->telefono;?></span>
											</a>
										</li>
										<? endforeach; ?>										
									</ul>
								</div>
							</div>

							<? if(FALSE): ?>
							<a href="#" class="button_user">
								<i class="icon-like"></i>
								<span class="sr-only">Acceso a usuarios</span>
							</a>
							<? endif;?>
						</div>

						<div class="button_links">
							<div class="tel_box_wrapper video-call button_video_call">
								<a href="https://crm.zoho.com/bookings/Calendariodeatenci%C3%B3nBuenasVibrasViajes?rid=9589dd09fb432e99f4746ed5cf483b6bb6cb6802946e5253597719b767e8fa09gidc52cb70b206161c797f1c7f89bcd220a76e913641a5c06e9caf99aa3e12506f0" target="_blank"">
									<i class="fas fa-video"></i>
									<span>Agendá una videollamada</span>
								</a>
							</div>
						</div>

					</div>

				</div>

			</div>
		</nav>

		<?=@$buscador;?>
		
	</header>
