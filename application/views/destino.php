<?=$header;?>

	<?=$floating_help;?>
	
	<? $img = ($destino->imagen && file_exists('./uploads/destinos/'.$destino->id.'/'.$destino->imagen)) ? (base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen) : ''; ?>

  <div class="full-width-container">

	<div class="centered-container">
	  <section class="paquete-principal">
	  	<? if($img): ?>
			<div class="hero hero--intermedia" style="background-image: url('<?=$img;?>');">
			<img alt="" class="hero__wave" src="<?=base_url();?>media/assets/images/shapes/hero-wave.png"></div>
		<? endif; ?>
		<div class="paquete-principal__info clearfix">
		  <div class="paquete-principal__info__left">
			<div class="paquete-principal__info__left__top">
			  <h1><?=$destino->nombre;?></h1>
			  <div class="paquete-principal__info__left__top__share clearfix">
				<a href="javascript:void(0)">
					<i class="fas fa-share-alt"></i>
					<p>Compartir</p>
				</a>

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
			</div>
			<div class="paquete-principal__descripcion">
			  <p><?=$destino->descripcion;?></span></p>
			</div>
		  </div>
		  <div class="paquete-principal__info__right">
		  	<? if ($precio_minimo): ?>
			<div class="paquete__precios paquete__precios--color">
			  <div class="clearfix">
			  	<? $anterior = false;
				if(isset($precio_minimo->precio_anterior_neto) && $precio_minimo->precio_anterior_neto): $anterior = true;?>
					<div class="paquete__precios__precio paquete__precios__precio--margin-right">
					  <p class="paquete__precios__precio__title paquete__precios__precio__title--color">Por persona desde</p>
					  <div class="paquete__precios__precio__valores">
						<p class="paquete__precios__precio__valores__costo paquete__precios__precio__valores__costo--color paquete__precios__precio__valores__costo--tachado"><?=precio_redondeado($precio_minimo->precio_anterior_neto,$precio_minimo->precio_usd);?></p>
						<p class="paquete__precios__precio__valores__impuestos paquete__precios__precio__valores__impuestos--color">+<?=precio_redondeado($precio_minimo->precio_anterior_impuestos,$precio_minimo->precio_usd);?> imp</p>
					  </div>
					</div>
				<? endif; ?>


				<div class="paquete__precios__precio">
					<? if($anterior): ?>
				 	 <p class="paquete__precios__precio__title paquete__precios__precio__title--ahora">Ahora</p>
				 	 <? else: ?>
				 	 	<p class="paquete__precios__precio__title paquete__precios__precio__title--color">Por persona desde</p>
				 	 <? endif;?>
					  <div class="paquete__precios__precio__valores">
						<p class="paquete__precios__precio__valores__costo paquete__precios__precio__valores__costo--color"><?=precio_redondeado($precio_minimo->total,$precio_minimo->precio_usd);?></p>
						<p class="paquete__precios__precio__valores__impuestos paquete__precios__precio__valores__impuestos--color">+<?=precio_redondeado($precio_minimo->impuestos,$precio_minimo->precio_usd);?> imp</p>
					  </div>
				</div>

			  </div>
			</div>
			<? endif; ?>

			<div class="paquete__consultas clearfix">
			  <p>¿Tenés una consulta? <span>Escribinos</span></p>
			  <div class="paquete__consultas__icons">
				<a rel="nofollow" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank"><i class="fab fa-whatsapp"></i><span>Whatsapp</span></a> <a rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank"><i class="fab fa-facebook-messenger"></i><span>Facebook</span></a>
			  </div>
			</div>
		  </div>
		</div>
	  </section><!-- FIN PRINCIPAL -->


	  <section class="tours">
	  <? foreach($estacionales as $e): ?>
		<? if(count($e->paquetes)>0): ?>
			<!-- CATEGORÍA -->
			<div class="tours__categoria">
			  
			  <div class="tours__categoria__header clearfix">
			  	<? if($e->imagen && file_exists('./uploads/estacionales/'.$e->id.'/'.$e->imagen)): ?>
					<img alt=""
					 class="tours__categoria__header__icon"
					 src="<?=base_url();?>uploads/estacionales/<?=$e->id.'/'.$e->imagen;?>">
				<? endif; ?>

				<h2 class="tours__categoria__header__title"><?=$e->nombre;?></h2>
			  </div><!-- PAQUETE -->

			  <? foreach($e->paquetes as $p): ?>
			  
			  <article class="tour paquete clearfix">
			  	<? $src = '';
			  	if($p->imagen_listado && file_exists('./uploads/paquetes/'.$p->id.'/'.$p->imagen_listado)): 
			  		$src = base_url().'uploads/paquetes/'.$p->id.'/'.$p->imagen_listado;
			  	else:
			  		if($p->imagen && file_exists('./uploads/destinos/'.$p->destino_id.'/'.$p->imagen)):
			  				$src = base_url().'uploads/destinos/'.$p->destino_id.'/'.$p->imagen;
			  			else:
			  				$src = '';
			  			endif;
			  	endif; ?>

			  	<? if($src): ?>
					<div class="paquete__foto clearfix" onclick="javascript: location.href ='<?=site_url($p->destino_slug.'/'.$p->slug);?>';"
						 style="background-image: url('<?=$src;?>')">
					  <div class="paquete__foto__icons">
						<? foreach ($p->cat_estacionales as $est) : ?>
							<? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen)): ?>
								<a href="">
									<img alt="" class="paquete__foto__icons__icon" src="<?=base_url();?>uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>"></a>
							<? endif; ?>
						<? endforeach; ?>
					  </div>
					</div>
				<? endif; ?>

				<div class="paquete__data">
				  <div class="paquete__title-wrapper relative clearfix">
				  	<? $img = '';
				  	switch($p->tipo_id){
			  			case 1: $tipo_trans = 'en avión'; $img = 'aeroplane.png'; break;
				  		case 2: $tipo_trans = 'en bus'; $img = 'bus.png'; break;
				  		case 3: $tipo_trans = 'en crucero'; $img = 'boat.png'; break;
				  		case 4: $tipo_trans = 'tour en destino'; $img = 'icon-tour-destino.png'; break;
				  		case 5: $tipo_trans = 'ferry'; $img = 'boat-ferry.png'; break;
				  	  } ?>

				  	<? if($img): ?>

					<div class="paquete__title-wrapper__icon"
						 style="background-image: url('<?=base_url();?>media/assets/images/icon/<?=$img;?>')"></div>
					<? endif; ?>
					<h3 class="paquete__title"><a href="<?=site_url($p->destino_slug.'/'.$p->slug);?>"><? if($tipo_trans): ?><span><?=$tipo_trans;?></span> /<? endif; ?><?=$p->nombre_visible;?></a></h3>
				  </div>
				  <div class="clearfix">
					<div class="paquete__tiempo">
					  <div class="wrapper">
						<p class="paquete__tiempo__duracion"><?=dias_viaje($p->fecha_indefinida?$p->fecha_checkin:$p->fecha_inicio,$p->fecha_indefinida?$p->fecha_checkout:$p->fecha_fin);?> días - <?=noches_viaje($p->fecha_checkin,$p->fecha_checkout)?> noches</p>

						<? 
							$f_inicio = ($p->fecha_salida < $p->fecha_checkin) ? $p->fecha_salida : $p->fecha_checkin; 
							$f_fin = ($p->fecha_regreso > $p->fecha_checkout) ? $p->fecha_regreso : $p->fecha_checkout; 
							$fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin)); ?>

						<p class="paquete__tiempo__fechas"><?=$fecha_elegida?></p>
						<div class="paquete__tiempo__arrow paquete__tiempo__arrow <?=($this->detector->isMobile()?'isOpen':'');?>"></div>
					  </div>
					</div>

				    <? if($p->detalle_inicio): ?>
						<div class="paquete__inicio paquete__inicio--intermedia">
						  <h4 class="paquete__inicio__title">Inicio</h4>
						  <p class="paquete__inicio__lugar"><?=$this->detector->isMobile()?$p->detalle_inicio:limit_text($p->detalle_inicio,39);?></p>
						</div>
					<? endif; ?>
				  </div>
				  <div class="paquete__lugares-disponibles paquete__lugares-disponibles--mb paquete__lugares-disponibles--mb--intermedia" <?=($this->detector->isMobile()?'style="display:block"':'');?>>
					<div class="clearfix">
					  <img class="paquete__lugares-disponibles__icon"
						   src="<?=base_url();?>media/assets/images/icon/suitcase.png">
					  <? if($p->cupo_disponible):?>
					  	<p class="paquete__lugares-disponibles__cantidad"><span><?=$p->cupo_disponible?></span>/<?=$p->cupo_total;?></p>
					  	<p class="paquete__lugares-disponibles__leyenda">Lugares<br>disponibles</p>
					  <? else: ?>
					  	<p class="paquete__lugares-disponibles__cantidad"><span>Completo</p>  	
					  <? endif; ?>
					  
					</div>
				  </div>
				  <div class="relative clearfix">
					<div class="paquete__precios">
					  <div class="clearfix">
					  	<? $anterior = false;
						if(isset($p->precio_anterior_neto) && $p->precio_anterior_neto): $anterior = true;?>
							<? if($p->cupo_disponible):?>
								<div class="paquete__precios__precio paquete__precios__precio--margin-right">
								  <p class="paquete__precios__precio__title">Por persona desde</p>
								  <div class="paquete__precios__precio__valores">
									<p class="paquete__precios__precio__valores__costo paquete__precios__precio__valores__costo--tachado"><?=precio_redondeado($p->precio_anterior_neto,$p->precio_usd);?></p>
									<p class="paquete__precios__precio__valores__impuestos">+<?=precio_redondeado($p->precio_anterior_impuestos,$p->precio_usd);?> imp</p>
								  </div>
								</div>
							<? endif; ?>
						<? endif; ?>

						<div class="paquete__precios__precio">
							<? if($p->precio > 0): ?>
								<? if($p->cupo_disponible): ?>
									<? if($anterior): ?>
								 	 	<p class="paquete__precios__precio__title paquete__precios__precio__title--ahora">Ahora</p>
								 	 <? else: ?>
								 		 <p class="paquete__precios__precio__title paquete__precios__precio__title--ahora">Por persona desde</p>
									<? endif; ?>

									  <div class="paquete__precios__precio__valores">
										<p class="paquete__precios__precio__valores__costo"><?=precio_redondeado($p->precio,$p->precio_usd);?></p>
										<p class="paquete__precios__precio__valores__impuestos">+<?=precio_redondeado($p->impuestos,$p->precio_usd);?> imp</p>
									  </div>
								  <? endif; ?>
							 <? else: ?>
							 	<div class="paquete__precios__precio__valores">
								<p class="paquete__precios__precio__valores__costo">PROXIMAMENTE</p>
							  </div>
							 <? endif; ?>
						</div>
					  </div>
					</div>

					<? if($p->precio > 0): ?>
					<div class="paquete__lugares-disponibles paquete__lugares-disponibles--dp" style="<?=$p->cupo_disponible?'':'margin-left:0;';?>">
					  <div class="clearfix center">
						<img class="paquete__lugares-disponibles__icon"
							 src="<?=base_url();?>media/assets/images/icon/suitcase.png">
						 <? if($p->cupo_disponible):?>
							<p class="paquete__lugares-disponibles__cantidad"><span><?=$p->cupo_disponible?></span>/<?=$p->cupo_total;?></p>
							<p class="paquete__lugares-disponibles__leyenda">Lugares<br>disponibles</p>
						 <? else: ?>
							 <p class="paquete__lugares-disponibles__cantidad"><span>Completo</span></p>
						<? endif; ?>
					  </div>
					</div>
					<? endif; ?>
					<div class="paquete__info">
					  <a class="paquete__info__link--dp"
						   href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">+Info</a> <a class="paquete__info__link--mb"
						   href="<?=site_url($p->destino_slug.'/'.$p->slug);?>"><img class="paquete__info__icon"
						   src="<?=base_url();?>media/assets/images/icon/info.png"></a>
					</div>
				  </div>
				</div>
			  </article><!-- PAQUETE -->

			<? endforeach; ?>

			  
			</div><!-- CATEGORÍA -->
			

			<!-- <div class="ver-mas">
			  <a class="ver-mas__btn"
				   href="">Ver más</a>
			</div> -->
		<? endif; ?>
	  <? endforeach; ?>
	  </section>
	  <!-- FIN TOURS -->
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
		$(".paquete__tiempo__arrow").click(function() {
		  $(this).toggleClass("isOpen");
		  $(".paquete__inicio--intermedia", $(this).parents('.paquete__data')).slideToggle();
		  $(".paquete__lugares-disponibles--mb--intermedia", $(this).parents('.paquete__data')).slideToggle();
		});
	</script>
