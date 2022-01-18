<div class="categoria__content">


	<? foreach($destinos as $d): ?>
	<div style="display:none">
		<? pre($d); ?>
	</div>
	<!-- SKU -->
	<article class="sku clearfix">
		<a href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->destino_slug);?>" rel="<?=$d->imagen;?>">
			<? //sino la img del destino
	  		if($d->imagen && file_exists('./uploads/destinos/'.$d->destino_id.'/'.$d->imagen)):
	  			$src = base_url().'uploads/destinos/'.$d->destino_id.'/'.$d->imagen;
	  			$img = "background-image:url('$src')";
		  	else:
		  		$img = '';
		  	endif; ?>

			<div class="sku__foto"
				style="<?=$img;?>">
				<div class="sku__foto__icons">
					<? $ids_added = [];
					foreach($d->paquetes as $p): ?>
						<? foreach ($p->cat_estacionales as $est) : ?>
							<? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen) && !in_array($est->id, $ids_added)): 
									$ids_added[] = $est->id; ?>
								<img src="<?=base_url();?>uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>" alt="">
							<? endif; ?>
						<? endforeach; ?>
					<? endforeach; ?>
			  	</div>
			</div>
		</a>
		<div class="sku__info">
			<div class="sku__info__heading">
				<div class="sku__title-container clearfix">
					<!-- <div class="sku__title-container__icon" style="background-image: url('assets/images/icon/boat.png')"></div>
  	icon-tour-destino.png
  	aeroplane.png
  	bus.png -->
					<a href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->destino_slug);?>">
						<h3 class="sku__title-container__title"><?=$d->destino;?></h3>
					</a>
				</div>
			</div>
			<div class="sku__info__body">
				<div class="clearfix">
					<div class="sku__text">
						<p><?=limit_text($d->destino_descripcion,135);?></p>
					</div>
				</div>

			</div>
			<div class="sku__info__footer clearfix relative flexmobile">

				<? $anterior = false; $mobile = $this->detector->isMobile();
				if(isset($d->precio_anterior_neto) && $d->precio_anterior_neto): $anterior = true;?>
					<div class="sku__price" style="<?=$mobile?'width:50%':'';?>">
					  <p class="sku__price__label">Por persona desde</p>
					  <p class="sku__price__cost sku__price__cost--old"><?=precio_redondeado($d->precio_anterior_neto,$d->precio_usd);?></p>
					  <p class="sku__price__tax">+<?=precio_redondeado($d->precio_anterior_impuestos,$d->precio_usd);?> imp</p>
					</div>
				<? endif; ?>

				<? if(isset($d->precio_total) && $d->precio_total > 0): ?>
					<div class="sku__price" <?=$anterior && $mobile?'style="width:50%"':''; ?>>
						<? if($anterior): ?>
							  <p class="sku__price__label sku__price__label--current" >Ahora</p>
						<? else: ?>
					 		 <p class="sku__price__label ">Por persona desde</p>
						<? endif; ?>

					  <p class="sku__price__cost"><?=precio_redondeado($d->precio_total,$d->precio_usd);?></p>
					  <p class="sku__price__tax">+<?=precio_redondeado($d->precio_impuestos,$d->precio_usd);?> imp</p>
					</div>
				<? endif; ?>

				<a class="sku__options" href="#">Opciones</a>

				<a class="sku__more-info" href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->destino_slug);?>">+Info</a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="sku__more-content-info">
			
			<? foreach($d->paquetes as $p): ?>

			<div class="sku__more-cont">

				<div class="sku__paquete-title">
					<? $img = '';
				  	switch($p->tipo_id){
			  			case 1: $tipo_trans = 'en avión'; $img = 'aeroplane.png'; break;
				  		case 2: $tipo_trans = 'en bus'; $img = 'bus.png'; break;
				  		case 3: $tipo_trans = 'en crucero'; $img = 'boat.png'; break;
				  		case 4: $tipo_trans = 'tour en destino'; $img = 'icon-tour-destino.png'; break;
				  		case 5: $tipo_trans = 'en ferry'; $img = 'boat.png'; break;
				  	} 
				  	if($img):
				  		$src = base_url().'media/assets/images/icon/'.$img;
	  					$timg = "background-image:url('$src')";
				  	endif; 
				  	?>
						 
					<div class="paquete__title-wrapper__icon"
						style="<?=$timg;?>">
					</div>
					<p class="title"><?=$tipo_trans;?></p>
					<div class="clearfix"></div>
					<p><?=$p->nombre_visible;?></p>
				</div>
				<div class="sku__paquete-dates">
					<p class="sku__price__label"><?=dias_viaje($p->fecha_indefinida?$p->fecha_checkin:$p->fecha_inicio,$p->fecha_indefinida?$p->fecha_checkout:$p->fecha_fin);?> días - <?=noches_viaje($p->fecha_checkin,$p->fecha_checkout)?> noches</p>
					
					<p class="sku__days-dates">
						<? 
						$f_inicio = ($p->fecha_salida < $p->fecha_checkin) ? $p->fecha_salida : $p->fecha_checkin; 
						$f_fin = ($p->fecha_regreso > $p->fecha_checkout) ? $p->fecha_regreso : $p->fecha_checkout; 
						$fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin)); 
						?>
						<?=$fecha_elegida?>
					</p>
				</div>

				<? $anterior = false;
				if(isset($p->precio_anterior_neto) && $p->precio_anterior_neto): $anterior = true;?>
					<div class="sku__price">
					  <p class="sku__price__label">Por persona desde</p>
					  <p class="sku__price__cost sku__price__cost--old"><?=precio_redondeado($p->precio_anterior_neto,$p->precio_usd);?></p>
					  <p class="sku__price__tax">+<?=precio_redondeado($p->precio_anterior_impuestos,$p->precio_usd);?> imp</p>
					</div>
				<? endif; ?>

				<? if(isset($p->precio_total) && $p->precio_total > 0): ?>
					<div class="sku__price">
						<? if($anterior): ?>
							  <p class="sku__price__label sku__price__label--current">Ahora</p>
						<? else: ?>
					 		 <p class="sku__price__label ">Por persona desde</p>
						<? endif; ?>

					  <p class="sku__price__cost"><?=precio_redondeado($p->precio_total,$p->precio_usd);?></p>
					  <p class="sku__price__tax">+<?=precio_redondeado($p->precio_impuestos,$p->precio_usd);?> imp</p>
					</div>
				<? endif; ?>

				<div class="sku__seats">
					<p>Lugares</p>

					<? if($p->cupo_disponible):?>
					   	<span class="sku__seats__number sku__seats--vertical__number"><span><?=$p->cupo_disponible?>/</span><?=$p->cupo_total;?></span>
					   	 <p class="sku__seats__label">disponibles</p>
				   	<? else: ?>
						 <span class="sku__seats__completo">Completo</span>
					<? endif; ?>

				</div>
				<div class="sku__cont-buttons">

					<a class="sku__more-info" href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">+Info</a>
				</div>
			</div>
			
			<? endforeach; ?>

		</div>
	</article>
	<!-- SKU -->

	<? endforeach; ?>

</div>

		<? //implementacion vieja
		if(false):
			foreach($paquetes as $dest=>$paqs): ?>
				<div class="tours__categoria__header tours__categoria__header--proximos clearfix">
				  <h2 class="tours__categoria__header__title"><?=$dest;?></h2>
				  <div class="tours__categoria__header__arrow">
					<img src="<?=base_url();?>media/assets/images/icon/blue-arrow-down.png" alt="">
				  </div>
				</div>
				<div class="categoria__content">
				<? foreach($paqs as $p): ?>
				  <!-- SKU -->
				  <article class="sku clearfix">
				  	<? //si tiene imagen el paquete
				  		if($p->imagen_listado && file_exists('./uploads/paquetes/'.$p->id.'/'.$p->imagen_listado)):
				  			$src = base_url().'uploads/paquetes/'.$p->id.'/'.$p->imagen_listado;
				  			$img = "background-image:url('$src')";
					  	else:
					  		//sino la img del destino
					  		if($p->imagen && file_exists('./uploads/destinos/'.$p->destino_id.'/'.$p->imagen)):
					  			$src = base_url().'uploads/destinos/'.$p->destino_id.'/'.$p->imagen;
					  			$img = "background-image:url('$src')";
						  	else:
						  		$img = '';
						  	endif; 
					  	endif; 
					  ?>

				  	<a href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">
						<div class="sku__foto" style="<?=$img?$img:'';?>">
						  <div class="sku__foto__icons">
						  	<? foreach ($p->cat_estacionales as $est) : ?>
								<? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen)): ?>
									<img src="<?=base_url();?>uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>" alt="">
								<? endif; ?>
							<? endforeach; ?>
						  </div>
						</div>
					</a>
					<div class="sku__info">
					  <div class="sku__info__heading">
						<div class="sku__title-container clearfix">
						  <!-- <div class="sku__title-container__icon" style="background-image: url('assets/images/icon/boat.png')"></div> 
						  	icon-tour-destino.png
						  	aeroplane.png
						  	bus.png -->
						  	<? $img = '';
						  	switch($p->tipo_id){
					  			case 1: $tipo_trans = 'en avión'; $img = 'aeroplane.png'; break;
						  		case 2: $tipo_trans = 'en bus'; $img = 'bus.png'; break;
						  		case 3: $tipo_trans = 'en crucero'; $img = 'boat.png'; break;
						  		case 4: $tipo_trans = 'tour en destino'; $img = 'icon-tour-destino.png'; break;
						  		case 5: $tipo_trans = 'en ferry'; $img = 'boat.png'; break;
						  	  } ?>
						  <a href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">
						  	<h3 class="sku__title-container__title"><? if($img): ?><img class="sku__title-container__title-icon" src="<?=base_url();?>media/assets/images/icon/<?=$img;?>" /><?=$tipo_trans;?> <span>/ <? endif; ?><?=$p->nombre_visible;?></span></h3>
						  </a>
						</div>
					  </div>
					  <div class="sku__info__body">
						<div class="clearfix">
						  <div class="sku__time relative">
							<p class="sku__time__duration"><?=dias_viaje($p->fecha_indefinida?$p->fecha_checkin:$p->fecha_inicio,$p->fecha_indefinida?$p->fecha_checkout:$p->fecha_fin);?> días - <?=noches_viaje($p->fecha_checkin,$p->fecha_checkout)?> noches</p>
							<? 
							$f_inicio = ($p->fecha_salida < $p->fecha_checkin) ? $p->fecha_salida : $p->fecha_checkin; 
							$f_fin = ($p->fecha_regreso > $p->fecha_checkout) ? $p->fecha_regreso : $p->fecha_checkout; 
							$fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin)); ?>
							<p class="sku__time__dates"><?=$fecha_elegida?></p>
						  </div>
						  <? if($p->detalle_inicio): ?>
						  <div class="sku__inicio">
							<p class="sku__inicio__title">Inicio</p>
							<p class="sku__inicio__place"><?=$this->detector->isMobile()?$p->detalle_inicio:limit_text($p->detalle_inicio,39);?></p>
						  </div>
						  <? endif; ?>
						</div>
						<div class="sku__seats sku__seats--mb">
						  <img class="sku__seats__icon" src="<?=base_url();?>media/assets/images/icon/suitcase.png" alt="">
						   <? if($p->cupo_disponible):?>
						   	<span class="sku__seats__number"><?=$p->cupo_disponible?>/<span><?=$p->cupo_total;?></span></span>
						   	 <p class="sku__seats__label">Lugares
							disponibles</p>
						   <? else: ?>
							 <span class="sku__seats__number">Completo</span>
							<? endif; ?>
						 
						</div>
					  </div>
					  <? if(isset($p->precio_total)): //$p->cupo_disponible>0 && ?>
					  <div class="sku__info__footer clearfix relative">
					  	<? $anterior = false;
						if(isset($p->precio_anterior_neto) && $p->precio_anterior_neto): $anterior = true;?>
							<div class="sku__price">
							  <p class="sku__price__label">Por persona desde</p>
							  <p class="sku__price__cost sku__price__cost--old"><?=precio_redondeado($p->precio_anterior_neto,$p->precio_usd);?></p>
							  <p class="sku__price__tax">+<?=precio_redondeado($p->precio_anterior_impuestos,$p->precio_usd);?> imp</p>
							</div>
						<? endif; ?>

						<? if(isset($p->precio_total) && $p->precio_total > 0): ?>
						<div class="sku__price">
							<? if($anterior): ?>
								  <p class="sku__price__label sku__price__label--current">Ahora</p>
							<? else: ?>
						 		 <p class="sku__price__label sku__price__label--current">Por persona desde</p>
							<? endif; ?>

						  <p class="sku__price__cost"><?=precio_redondeado($p->precio_total,$p->precio_usd);?></p>
						  <p class="sku__price__tax">+<?=precio_redondeado($p->precio_impuestos,$p->precio_usd);?> imp</p>
						</div>
						<? endif; ?>

						<div class="sku__seats sku__seats--vertical">
						  <img class="sku__seats__icon sku__seats--vertical__icon" src="<?=base_url();?>media/assets/images/icon/suitcase.png"
							alt="">
						  <? if($p->cupo_disponible):?>
						  	<span class="sku__seats__number sku__seats--vertical__number"><?=$p->cupo_disponible?>/<span><?=$p->cupo_total;?></span></span>
						 	 <p class="sku__seats__label sku__seats--vertical__label">Lugares
							disponibles</p>
							<? else: ?>
								 <span class="sku__seats__number sku__seats--vertical__number">Completo</span>
							<? endif; ?>
						</div>
						<a href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">
							<img class="sku__more-info--mb" src="<?=base_url();?>media/assets/images/icon/info.png" alt="">
						</a>
						<a class="sku__more-info--dp" href="<?=site_url($p->destino_slug.'/'.$p->slug);?>">+Info</a>
					  </div>
					  <? endif; ?>
					</div>
				  </article>
				<? endforeach; ?>			 
				</div>
			<? endforeach;
		endif; ?>