<?=$header;?>

	<!-- CUERPO -->

	<div id="main">

		<div class="container">

			<div class="title-section__titles">
			  <div class="title-section__titles__color"></div>
			  <p>Revolucionamos el Turismo para Jóvenes</p>
			  <h1>Quiénes Somos</h1>
			</div>

		</div>


		<div class="contenido container">

			<div class="intro_div clearfix">
				<div class="col-xs-12 col-md-6">
					<h3>Más de 10 años generando vínculos viajando.</h3>
					<p>Creamos experiencias únicas alrededor del mundo. Para nosotros, viajar es mucho más que visitar un nuevo destino, para nosotros viajar es hacer nuevos amigos y vivir juntos momentos inolvidables. Queremos acompañarte en tu propia aventura, logrando cumplir tus sueños de romper las barreras, no como un simple turista sino como un verdadero viajero del mundo. Somos Buenas Vibras.</p>

					<a href="<?=site_url('contacto');?>" class="button button--primary">Contáctanos!</a>
				</div>

				<? if($this->settings->video_quienessomos): ?>
				<div class="video col-xs-12 col-md-6">
					<div class="embed-responsive embed-responsive-16by9" style="position:relative;height:0;padding-bottom:51.25%"><iframe class="embed-responsive-item" src="<?=youtube_embed_url($this->settings->video_quienessomos);?>" width="640" height="270" frameborder="0" style="position:absolute;width:100%;height:100%;left:0" allowfullscreen></iframe></div>
				</div>
				<? endif; ?>
			</div>


			<div class="viaja_solo clearfix">
				<h3>SÉ PARTE DE BUENAS VIBRAS</h3>
				<p>Vive la experiencia de nuestros viajes grupales</p>

				<div class="row features">
					<div>
						<span class="icon-bus"></span>
						<span>Pasajes y traslados</span>
					</div>

					<div>
						<span class="icon-hotel"></span>
						<span>Alojamiento incluído</span>
					</div>

					<div>
						<span class="icon-running"></span>
						<span>Excursiones y actividades</span>
					</div>

					<div>
						<span class="icon-breakfast"></span>
						<span>Desayuno / media pensión</span>
					</div>

					<div>
						<span class="icon-cocktail"></span>
						<span>Fiestas temáticas</span>
					</div>

					<div>
						<span class="icon-medic_2"></span>
						<span>Seguros y Asistencia</span>
					</div>

					<div>
						<span class="icon-group_2"></span>
						<span>Staff de Coordinadores</span>
					</div>

					<div>
						<span class="icon-chat"></span>
						<span>Previas y charlas</span>
					</div>

					<div>
						<span class="icon-contact"></span>
						<span>Gente nueva y buena onda!</span>
					</div>

					<div>
						<span class="icon-smile"></span>
						<span>Juegos y Diversión</span>
					</div>

					<div>
						<span class="icon-dance"></span>
						<span>Boliches / Peñas</span>
					</div>

					<div>
						<span class="icon-starfish"></span>
						<span>Aventura / adrenalina</span>
					</div>

					<div>
						<span class="icon-camera"></span>
						<span>Puntos turísticos</span>
					</div>

					<div>
						<span class="icon-time"></span>
						<span>Tiempo libre</span>
					</div>
				</div>


				<div class="textos row">

					<div class="col-xs-12 col-md-4">
						<h4>Conoce cómo son nuestros viajes grupales.</h4>

						<p>Tenemos la fórmula secreta para aquellos viajeros de entre 18 y 35 años (grupos JOVENES), que buscan explorar y conocer nuevas culturas, pero sin dejar de lado la fiesta y la aventura. También ideamos viajes exclusivos +35, (grupos ELITE), para aquellos que hoy buscan explorar el mundo de una forma diferente, rodeado de gente nueva y sumergiéndose en lo más profundo de la cultura local. Se parte del espíritu Buenas Vibras.</p>
					</div>

					<div class="col-xs-12 col-md-4">
						<h4>Estamos siempre a la vanguardia.</h4>


						<p>Para nosotros, estar a la vanguardia, es pensar en crecer y en seguir siendo líderes en turismo joven, continuamos comercializando nuestros productos en el canal minorista online y ahora comenzamos a hacerlo a través del canal mayorista de agencias de viajes para Argentina, México y Colombia. Te animas a ser parte de Buenas Vibras?</p>

					</div>

					<div class="col-xs-12 col-md-4">
						<h4>Tour Leaders Profesionales.</h4>

						<p>¿Cuál es la mejor forma de explorar una ciudad? Sí, a pie, visitando cada rincón, absorbiendo la esencia local y sin dejar de conocer los lugares icónicos del destino. En nuestros viajes, disfrutamos de una nueva aventura todos los días, aprendemos sobre cultura, probamos nuevas comidas y hacemos nuevos amigos, divirtiéndonos junto a los Tour Leaders. Ellos son los encargados de transmitir nuestros valores, generar la integración del grupo y estar a tu disposición para todo lo que necesites, desde recomendarte dónde comer hasta tomarte esa foto que tanto soñaste. Gracias a ellos, esta experiencia será inolvidable.</p>
					</div>

				</div>
			</div>


			<div class="otros_paquetes">
				<h3>Otros Paquetes</h3>
				<p>Un viaje a tu medida</p>

				<div class="row features">
					<div>
						<span class="icon-bus"></span>
						<span>Pasajes ida / vuelta</span>
					</div>

					<div>
						<span class="icon-hotel"></span>
						<span>Alojamiento incluido</span>
					</div>

					<div>
						<span class="icon-running"></span>
						<span>Excursiones opcionales</span>
					</div>

					<div>
						<span class="icon-breakfast"></span>
						<span>Desayuno / media pensión</span>
					</div>

					<div>
						<span class="icon-camera"></span>
						<span>Puntos turísticos</span>
					</div>

					<div>
						<span class="icon-medic_2"></span>
						<span>Seguros y Asistencia</span>
					</div>

					<div>
						<span class="icon-group_2"></span>
						<span>Asesoramiento</span>
					</div>
				</div>

			</div>

		</div>

		<? if(false): ?>
		
		<!-- contenido nuevo -->
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
		<!-- contenido nuevo -->
		<? endif; ?>
		
	</div>

	<!-- FIN CUERPO -->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?=$footer;?>

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
