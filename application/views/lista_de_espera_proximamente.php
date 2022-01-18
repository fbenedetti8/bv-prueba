<?=$header;?>

	<? $img = ($destino->imagen && file_exists('./uploads/destinos/'.$destino->id.'/'.$destino->imagen)) ? (base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen) : ''; ?>

  <div class="full-width-container">
	<div class="centered-container">
	  <? if($img): ?>
	  	<!-- HERO -->
	  		<div class="hero hero--interna_paquete" style="background-image: url('<?=$img;?>');"><img alt="" class="hero__wave" src="<?=base_url();?>media/assets/images/shapes/hero-wave.png">
	 	    </div>
		<? endif; ?>
	  <!-- MAIN -->
	  <main class="main">
		
		<?=$floating_help;?>

		<section class="heading">
		  <h2 class="heading__title"><?=$paquete->nombre;?></h2>
		  <div class="clearfix relative">
			<div class="heading__left">
			  <div class="heading__share clearfix">
				<div class="heading__share__item clearfix">
				  <a class="heading__share__item__link" href=""><i class="fas fa-share-alt"></i></a>
				  <a href="" target="_blank" class="heading__share__item__label">Compartir</a>
				  <div class="share_buttons">
					<a href="#" class="lnkFB" rel="<?=current_url();?>">
						<i class="fab fa-facebook-square"></i>
						<span class="sr-only">Facebook</span>
					</a>
					<a rel="nofollow" href="https://api.whatsapp.com/send?text=<?=$destino->nombre.' '.current_url();?>" target="_blank">
						<i class="fab fa-whatsapp"></i>
						<span class="sr-only">Whatsapp</span>
					</a>
				  </div>
				</div>

				<!--<div>
				  <a class="heading__share__item__link" href=""><i class="far fa-file-pdf"></i></a>
				  <a href="" target="_blank" class="heading__share__item__label">Descargar itinerario</a>
				</div>-->
			  </div>
			  <div class="heading__descripcion">
				<p><?=$paquete->descripcion;?></p>
			  </div>

			</div>


			<? if(count($paquete->cat_estacionales)): ?>
			<div class="heading__right ideal heading__ideal clearfix">
			  <p class="ideal__text">Ideal para</p>
			  <div class="ideal__icons clearfix">
			  	<? foreach ($paquete->cat_estacionales as $est) : ?>
					<div class="ideal__icons__icon">
					  <span class="ideal__icons__icon__tooltip"><?=$est->nombre?></span>
					  <? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen)): ?>
						  <a href=""><img alt="" class="" src="<?=base_url();?>uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>"></a>
						<? endif; ?>
					</div>
				<? endforeach; ?>
			  </div>
			</div>
			<? endif; ?>
		  </div>

		</section>
		<section id="detalle" class="detalle relative">

			<div class="detalle__left-container">

				<form class="form_espera" id="frmLE">
					<input type="hidden" name="tipo" value="paquete"/>
					<input type="hidden" name="tipo_id" value="<?=$paquete->id;?>"/>

					<div class="block_title">
						<h3 class="title">lista de espera</h3>
						<p class="subtitle">dejanos tus datos y te avisamos cuando haya lugar disponible para este viaje.</p>
					</div>


					<div class="form_field">
						<label class="field_required">
							<span>Nombre <i>*</i></span>
							<input type="text" placeholder="Tu Nombre" name="nombre"/>
							<span class="msg">CAMPO OBLIGATORIO</span>
						</label>
					</div>

					<div class="form_field">
						<label class="field_required">
							<span>Email <i>*</i></span>
							<input type="text" placeholder="Tu Email"  name="email" />
							<span class="msg">CAMPO OBLIGATORIO O FORMATO INCORRECTO</span>
						</label>
					</div>

					<div class="form_field field_required">
						<span>Celular <i>*</i></span>

						<div class="phone_fields ">
							<input type="text" placeholder="Cod área" class="cod_field" name="celular_codigo"/>
							<input type="text" placeholder="Número" name="celular_numero" />
							
						</div>

						<span class="msg">CAMPO OBLIGATORIO</span>
					</div>

					<div class="form_field">
						<span>Teléfono alternativo</span>

						<div class="phone_fields">
							<input type="text" placeholder="Cod área" class="cod_field" name="telefono_codigo" />
							<input type="text" placeholder="Número" name="telefono_numero" />
						</div>
					</div>

					<div class="submit_block">
						<button type="button" class="submit_button btn btn-pink" id="le_btn_submit">Anótame en lista de espera</button>
					</div>


					<!-- MENSAJE -->
					<div class="alert hidden">
						<p class="msg"></p>
					</div>
					<!-- FIN MENSAJE -->
				</form>

			</div>

  		<!--  TICKET -->

		  <article id="ticket" class="ticket relative">
			  <div class="ticket__sticky-center">
				<div class="ticket__sticky-width">
				  <div id="paquete-consultas" class="paquete__consultas clearfix relative">
					<p>¿Tenés una consulta? <span>Escribinos</span></p>
					<div class="paquete__consultas__icons">
					  <a rel="nofollow" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank"><i class="fab fa-whatsapp"></i><span>Whatsapp</span></a>
					  <a rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank"><i class="fab fa-facebook-messenger"></i><span>Facebook</span></a>
					</div>
				  </div>
				  <!-- <div class="ticket__blue-div--mb"></div> -->
				</div>

				<? if(count($caracteristicas)):?>
				<article class="items-incluidos clearfix">

					<h3 class="text_title">Este destino suele incluir:</h3>				
						<? foreach($caracteristicas as $c): ?>

							 <div class="items-incluidos__item clearfix">
								<? if($c->icono): ?>
									<div class="items-incluidos__item__icon" style="background-image: url('<?=base_url();?>media/assets/images/icon/features/<?=$c->icono;?>')"></div>
								<? endif; ?>
								<div class="items-incluidos__item__label"><span><?=$c->nombre;?></span></div>
							  </div>
						<? endforeach; ?>

					  
				</article>
				<? endif; ?>

			  </div>
			  <a class="ticket__ocultar-detalle--mb" href=""><img class="closed" src="<?=base_url();?>media/assets/images/icon/icon-doble-arrow-up-w.png"><img class="open" src="<?=base_url();?>media/assets/images/icon/double-arrow-down.png"><span>Deslice para detalles y más opciones</span></a>
			</article>
		</section>
	  </main>

	  <?=@$destino_galeria;?>

	</div>
	<!-- FIN CENTERED CONTAINER -->
	


	<?=@$destinos_recomendados;?>

	<section class="banner-notice">
		<div class="container">
			<div class="type-1">
				<div class="title">
					<div class="text">
						<h3>¿Eres una agencia de viajes? <span>¿quieres vender nuestros paquetes?</span></h3>
					</div>
					<div class="group-button">
						<button class="btn btn-pink"><a href="<?=site_url('contacto?a=vender');?>">Acceso agencias</a></button>
					</div>
				</div>
			</div>
		</div>
	</section>


  </div>


	<?=$footer;?>

  <script>

	function showTicketDetail($el, mode) {
	  if (mode === 'open') {
		$el.addClass('isOpen');
		$(".ticket__ocultar-detalle--mb").addClass('isOpen');
		$(".ticket__detail__desgloce").slideDown();
		$(".ticket .paquete__lugares-disponibles").slideDown();
		$(".ticket .paquete__consultas").slideDown();

	  } else {
		$el.removeClass('isOpen');
		$(".ticket__ocultar-detalle--mb").removeClass('isOpen');
		$(".ticket__detail__desgloce").slideUp();
		$(".ticket .paquete__lugares-disponibles").slideUp();
		$(".ticket .paquete__consultas").slideUp();
	  }
	}

	$(".estimado__ocultar-detalles--dp").click(function() {
	  $(this).toggleClass("isOpen");
	  $(".ticket__detail__desgloce").slideToggle();
	});



	$('.galeria .fa-expand').click(function(){
		$('.galeria .galeria__ver-mas').trigger('click')
	})

	var ticket = document.getElementById('ticket');
	var mode = 'close'
	$(".ticket__ocultar-detalle--mb").click(function(e) {
	  e.preventDefault();
	  mode = mode === 'close' ? 'open' : 'close'

	  showTicketDetail($(ticket), mode);
	});
  </script>