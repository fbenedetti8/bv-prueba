<?=$header;?>

	<!-- CUERPO -->

	<div id="main">


		<div class="container">
			<div class="title-section__titles">
			  <div class="title-section__titles__color"></div>
			  <p>La empresa N°1 en turismo para jóvenes</p>
			  <h1>Viajes de solas y solos</h1>
			</div>
		</div>



		<div class="contenido container">

			<div class="intro_div clearfix">
				<div class="col-xs-12 <?=count($imagenes)?'col-md-6':'col-md-12';?>">
					<h3>Somos la empresa líder en Turismo Joven.</h3>
					<p>Te ofrecemos viajes grupales para solos y solas a una gran variedad de destinos.<br />
					Escapate un fin de semana o por unos días más a recorrer los destinos nacionales. Viajá por Argentina para conocé Mendoza, Salta y Jujuy, Catamarca, Cataratas del Iguazú, Puerto Madryn, Talampaya o disfrutá del Oktoberfest en Córdoba.<br />
					Además, sentite exótico y descubrí lugares como Tailandia, India, Egipto y Dubai, Turquía o Sudáfrica. Como si eso fuera poco tenemos las mejores salidas grupales a playas como Florianópolis, Crucero Joven a Rio de Janeiro, Costa Rica, Colombia o México.<br />
					Todas las propuestas incluyen transporte, comidas, alojamiento, las mejores excursiones y coordinadores.</p>
				</div>

				<? if(count($imagenes)): ?>
				<div class="carousel col-xs-12 col-md-6">
					<div class="owl-carousel">

						<? foreach ($imagenes as $p): ?>
							<? if(file_exists('./uploads/solas_solos/'.$p->id.'/'.$p->imagen)): ?>
								<img src="<?=site_url('uploads/solas_solos/'.$p->id.'/'.$p->imagen);?>" alt="Paquetes a Florianopolis" />
							<? endif; ?>
						<? endforeach; ?>

					</div>
				</div>
				<? endif; ?>

			</div>


			<div class="textos row">

				<div class="col-xs-12 col-md-4">
					<h4>La tendencia de los viajes en grupo.</h4>

					<p>Para viajar siempre hay ganas pero nunca se encuentra el momento. Se cambian los lugares, la disponibilidad de los amigos y así se pasan los días. La mejor época para viajar es ahora, sin pensarlo más. Lo único que necesitás es tomar tu valija o mochila y ¡listo! Si tu excusa de no viajar es por no tener compañía, entonces te decimos: ¡no te preocupes! Existen los viajes en grupo, en los que vas a conocer personas dispuestas a descubrir otros paisajes, comer comida diferente y sobre todo, divertirse. ¡Cada vez son más las personas que realizan este tipo de viajes!</p>
				</div>

				<div class="col-xs-12 col-md-4">
					<h4>El viaje de tus sueños ya no tiene excusas.</h4>

					<p>Buenas Vibras es una empresa de viajes para gente joven que ofrece viajes a lugares con paisajes increíbles y actividades que no te vas a querer perder. Nos caracterizamos por una amplia experiencia en viajes grupales. Entre las ofertas de viajes grupales, se encuentran viajeros entre los 20 a 40 años de edad, que buscan conocer gente nueva, actividades interesantes y mucha diversión. Los viajes de grupo contemplan planes con deportes, caminatas y visitas durante el día. Por la noche, las fiestas temáticas con neón, música y colores son la cita obligada. La premisa es pasarla bien y con buena actitud.</p>
				</div>

				<div class="col-xs-12 col-md-4">
					<h4>Un viaje a medida de todos.</h4>

					<p>Desde que te decidís a ser parte del viaje en grupo, entrás en contacto con tus futuros compañeros de viaje en espacios de Facebook llamados "Las Previas", donde los viajeros se van conociendo un poco más y se preparan para la aventura que los espera. Así cuando viajás, no sólo viajás en grupo sino con amigos. A su vez, podés encontrar una comunidad de amigos que siguen en contacto, comentando las fotos más divertidas y recordando los momentos más interesantes del viaje. Muchos de los que van reviven su viaje de egresados pero en una nueva etapa. Son viajes en los que se hacen recuerdos y amigos para toda la vida.</p>
				</div>

			</div>


			<div class="viajaste_con_nosotros">
				<h3>¡Si tenés alguna duda contactanos!</h3>
			</div>

			<?=$contacto_subseccion;?>

		</div>

	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>