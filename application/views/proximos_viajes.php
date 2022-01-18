<?=$header;?>
  
  <?=$floating_help;?>

  <div class="full-width-container">
	<!-- CENTERED CONTAINER -->
	<div class="centered-container centered-container--proximos relative">
	  <!-- TITLE SECTION -->
	  <div class="title-section">
		<!-- <img alt="" class="hero__wave" src="<?=base_url();?>media/assets/images/shapes/hero-wave.png"> -->
		<div class="title-section__avion">
		  <div class="title-section__avion__content">
			<div class="title-section__avion__content__color"></div>
			<div class="title-section__avion__content__deco"></div>
			<div class="title-section__avion__content__nubes"></div>
			<div class="title-section__avion__content__avion" alt=""></div>
		  </div>
		</div>
		<div class="title-section__titles">
		  <div class="title-section__titles__color"></div>
		  <p>¿LISTOS PARA PARTIR?</p>
		  <h1>estos son nuestros próximos DESTINOS</h1>
		</div>
	  </div>
	  <!-- MAIN TOP -->
	  <div class="main-top clearfix">
		<!-- FILTORS MOBILE PLACEHOLDER -->
		<div id="filtros" class="filters filters--proximos">

		  <div class="filters__section">
			<a class="filters__trigger_modal fa fa-sliders-h" trigger-filter-modal-open></a>
			<div class="filters__section__title">Filtros</div>
			<div class="tags">
			  <? if(isset($filtros['region_id']) && $filtros['region_id']): ?>
			  <a class="tags__tag" trigger-filter-remove-tag data-tipo="region">
				<div class="tags__tag__close fa fa-times"></div>
				<div class="tags__tag__text"><?=@$region->nombre?></div>
			  </a>
			  <? endif; ?>
			  <? if(isset($filtros['fecha']) && $filtros['fecha']): ?>
			  <a class="tags__tag" trigger-filter-remove-tag data-tipo="fecha">
				<div class="tags__tag__close fa fa-times"></div>
				<div class="tags__tag__text"><?=fecha_buscador($filtros['fecha'].'-01')?></div>
			  </a>
			  <? endif; ?>
			  <? if(isset($filtros['categoria_id']) && $filtros['categoria_id']): ?>
			  <a class="tags__tag" trigger-filter-remove-tag data-tipo="categoria">
				<div class="tags__tag__close fa fa-times"></div>
				<div class="tags__tag__text"><?=@$categoria->nombre?></div>
			  </a>
			  <? endif; ?>
			</div>

		  </div>

		  <div class="filters__modal">
			<div class="filters__modal__close fa fa-times" trigger-filter-modal-close></div>
			<div class="filters__section filters_fecha">
			  <div class="filters__section__title">FECHA</div>
			  <div class="filters__nav">
				<a class="filters__nav__arrow is-left fas fa-chevron-left" trigger-owl-filter-dates-left></a>
				<a class="filters__nav__arrow is-right fas fa-chevron-right" trigger-owl-filter-dates-right></a>
			  </div>

			  <ul class="filters__options filters--mobile--slider" owl-filter-dates>
			  	<li class="filters__option is-carousel-enabled" ><a data-fecha="">Todos los meses</a></li>
				<? foreach ($fechas as $v): ?>
				<li class="filters__option is-carousel-enabled <?=(@$filtros['fecha']==$v->fecha)?'is-active':'';?>" ><a data-fecha="<?=$v->fecha;?>"><?=fecha_buscador($v->fecha)?></a></li>
				<? endforeach; ?>
			  </ul>

			</div>

			<? if(count($categorias)): ?>
			<div class="filters__section filters_categoria">
			  <div class="filters__section__title">Ideal para</div>
			  <ul class="filters__options">
			  	<li class="filters__option with-checkbox" ><a data-categoria="<?=$this->config->item("uri_categoria");?>">Todas las categorías</a></li>
				<? foreach ($categorias as $c): ?>
				<li class="filters__option with-checkbox <?=(@$filtros['categoria_id']==$c->id)?'is-active':'';?>" ><a data-categoria="<?=$c->slug;?>"><?=$c->nombre;?></a></li>
				<? endforeach; ?>
			  </ul>
			</div>
			<? endif ;?>

			<div class="filters__section filters_region">
			  <div class="filters__section__title">Región</div>
			  <ul class="filters__options">
			  	<li class="filters__option with-checkbox" ><a data-region="<?=$this->config->item("uri_regiones");?>">Todas las regiones</a></li>
				<? foreach ($categorias_activas as $d): ?>
				<li class="filters__option with-checkbox <?=(@$filtros['region_id']==$d->id)?'is-active':'';?>" ><a data-region="<?=$d->slug;?>"><?=$d->nombre;?></a></li>
				<? endforeach; ?>
			  </ul>
			</div>

			<div class="filters__actions">
			  <button class="button button--primary" trigger-filter-modal-apply>Aplicar</button>
			  <button class="button button--secondary" trigger-filter-modal-close>Cancelar</button>
			</div>

		  </div>
		</div>

		<section class="tours tours--proximos">
		  <!-- CATEGORÍA -->
		  <div class="tours__categoria" id="divContent">
			<?=$list_paquetes;?>
		  </div>

		  <div id="divLoading" class="ademas__title" style="display: none;">Cargando...</div>
		  <div id="divNomore" class="ademas__title" style="<?=count($destinos)?'display: none;':''?>">No hay más resultados.<br><br></div>

		  <!--<a href="#" class="btn sm btn-pink-outline">Ver todos</a>-->

		  <input type="hidden" id="offset" value="<?=@$offset;?>">


		</section>

	  </div>
	  <!-- FIN MAIN TOP-->
	  <!-- FIN TOURS -->
	  <? if(count($destinos_proximamente)): ?>
	  <!-- ADEMÁS -->
	  <section class="ademas">
		<h2 class="ademas__title">Además</h2>
		<? foreach($destinos_proximamente as $pr): ?>
		<article class="ademas-item clearfix">
			<? $img = ($pr->imagen && file_exists('./uploads/destinos/'.$pr->id.'/'.$pr->imagen)) ? (base_url().'uploads/destinos/'.$pr->id.'/'.$pr->imagen) : ''; ?>

		<a href="<?=site_url($pr->categoria_slug.'/'.$pr->slug);?>">
			<div class="ademas-item__foto" <?=$img?'style="background-image: url('.$img.');"':'style="background-image:none;"';?> >
				<div class="sku__foto__icons">
					<? foreach ($pr->cat_estacionales as $est) : ?>
						<? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen)): ?>
							<img src="<?=base_url();?>uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>" alt="">
						<? endif; ?>
					<? endforeach; ?>
				</div>
			</div>
		</a>


		  <div class="ademas-item__info">
			<div class="ademas-item__info__heading">
			  <div class="ademas-item__title-container relative">
			  	<a href="<?=site_url($pr->categoria_slug.'/'.$pr->slug);?>">
					<h3 class="ademas-item__title-container__title"><?=$pr->nombre;?></h3>
				</a>
				<div class="ademas-item__title-container__arrow"></div>
			  </div>
			</div>
			<div class="ademas-item__info__body">
			  <div class="ademas-item__description">
				<p class="ademas-item__description__text"><?=limit_text($pr->descripcion,145);?></p>
			  </div>
			</div>
			<div class="ademas-item__info__footer clearfix relative">
			  <div class="ademas-item__prox">
				<p class="ademas-item__prox__text">PRÓXIMAMENTE</p>
			  </div>
			<a href="<?=site_url($pr->categoria_slug.'/'.$pr->slug);?>">
				<img class="ademas-item__more-info--mb" src="<?=base_url();?>media/assets/images/icon/info.png" alt="">
			</a>
			  <a class="ademas-item__more-info--dp" href="<?=site_url($pr->categoria_slug.'/'.$pr->slug);?>">+Info</a>
			</div>
		  </div>
		</article>
		<? endforeach;?>
	  </section>
	<? endif;?>
	</div>
	<!-- FIN CENTERED CONTAINER -->


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


<?=$footer?>

  <script>
	$(function () {

	  var createTag = function (value) {
		return $(
		  '<a class="tags__tag" trigger-filter-remove-tag><div class="tags__tag__close fa fa-times"></div><div class="tags__tag__text">' +
		  value + '</div></a>')
	  }

	  function applyFilters() {
		var $tags = $('.tags', '.filters');
		$tags.empty();
		
		var furl = '';
		var durl = '';
		var curl = '';

		$('.filters__option.is-active').each(function (i) {
		  var $a = $('a', this);
		  $tags.append(createTag($a.text()))

		  /* ACA SE PUEDEN CAPTURAR LOS VALORES ELEGIDOS */
		  if($a.data('fecha')){
			furl = $a.data('fecha');
		  } 

		  if($a.data('region')){
			durl = $a.data('region');
		  } 

		  if($a.data('categoria')){
			curl = $a.data('categoria');
		  } 

		});

		console.log(durl);
		console.log(furl);
		console.log(curl);

		if(!furl && !durl && !curl){
			location.href = "<?=base_url();?>proximos-viajes";
		}
		else{
			if(!furl){
				furl = '<?=$this->config->item("uri_fecha");?>';
			}
			if(!durl){
				durl = '<?=$this->config->item("uri_regiones");?>';
			}

			location.href = "<?=base_url();?>proximos-viajes/"+durl+'/'+furl+'/'+curl;

		  }
	  }

	  $('[trigger-filter-modal-open]').on('click', function (e) {
		e.preventDefault();
		$('.filters').addClass('filters--modal--open');
		$('body').css('overflow', 'hidden');
	  });

	  $('[trigger-filter-modal-close]').on('click', function (e) {
		e.preventDefault();
		$('.filters').removeClass('filters--modal--open');
		$('body').css('overflow', 'auto')
	  });

	  $('[trigger-filter-modal-apply]').on('click', function (e) {
		e.preventDefault();
		applyFilters();
		$('.filters').removeClass('filters--modal--open');
		$('body').css('overflow', 'auto')
	  });


	  $('[trigger-filter-remove-tag]').on('click', function(e) {
		var $el = $(this);

		//obtengo el tipo de dato que voy a dejar de filtrar
		var tipo = $el.data('tipo');
		console.log(tipo);
		//le saco la marca de activo del listado de filtros de mas abajo
		$('.filters_'+tipo).find('.filters__option').removeClass('is-active');

		$el.detach();

		/* vuelvo a llamar a esta funcion para que vuelva a redirigir */
		applyFilters();
	  });

	  function selectOption($option) {
		$('.filters__option', $option.parents('.filters__options')).removeClass('is-active');
		$option.addClass('is-active')

		if (!window.matchMedia('(max-width: 991px)').matches) {
		  applyFilters();
		}
	  }

	  $('.filters__option').on('click', function (e) {
		e.preventDefault();
		var $this = $(this);
		selectOption($this);
	  });

	  if (window.matchMedia('(max-width: 991px)').matches) {
		/*Slider intermedia*/
		var filterDatesOwl = $('[owl-filter-dates]')
		  .addClass('owl-carousel')
		  .owlCarousel({
			items: 1,
			loop: false,
			center: true,
			margin: 30,
		  })

		$('[trigger-owl-filter-dates-left').on('click', function () {
		  filterDatesOwl.trigger('prev.owl.carousel');
		})
		$('[trigger-owl-filter-dates-right]').on('click', function () {
		  filterDatesOwl.trigger('next.owl.carousel');
		})

		filterDatesOwl.on('changed.owl.carousel', function (event) {
		  var index = event.item.index;

		  var option = $('.filters__option', event.relatedTarget.$element)[index]
		  selectOption($(option));
		})
	  }

	});

	var $filtros = $('#filtros');
	var offsetTop = $filtros.offset().top;

	function stickyFilters(e) {
	  var scrollTop = $(window).scrollTop();

	  if (offsetTop-scrollTop <= 0) {
		$filtros.addClass("sticky");
	  } else {
		$filtros.removeClass("sticky");
	  }
	}

	$(function () {
	  window.addEventListener("scroll", function(e){
		if (window.matchMedia('(max-width: 991px)').matches) {
		  stickyFilters(e)
		}
	  });

	});

</script>
  <script>

	$(".sku__arrow").click(function() {
	  $(this).toggleClass("isOpen");
	  $(".sku__inicio", $(this).parents('.sku__info__body')).slideToggle();
	  $(".sku__seats--mb",  $(this).parents('.sku__info__body')).slideToggle();
	});
  </script>
  <script>
	$(".ademas-item__title-container__arrow").click(function() {
	  $(this).toggleClass("isOpen");
	  $(".ademas-item__info__body").slideToggle();
	});
  </script>
</body>

</html>