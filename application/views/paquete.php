<?=$header;?>
<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style>


	<?=$paquete->script_conversion;?>

	<? 
	$img = '';
	if($paquete->url_video){
			$img = '';
	}elseif(!empty($paquete->imagen_listado) && file_exists('./uploads/paquetes/'.$p->id.'/'.$p->imagen_listado)){
			$img = base_url().'uploads/paquetes/'.$paquete->id.'/'.$paquete->imagen_listado;
	}elseif($destino->imagen && file_exists('./uploads/destinos/'.$destino->id.'/'.$destino->imagen)){
			$img = base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen; 
	}
	?>

  <div class="full-width-container">
	<div class="centered-container">
	  <!-- HERO -->
	  <div class="hero hero--interna_paquete" style="<?=$paquete->url_video?'height:auto;':'';?>max-height: 460px; overflow:hidden; background-image: url('<?=$img;?>');">
	  	<? if($paquete->url_video): ?>
			<? $img='';

			if($this->detector->isMobile() && $paquete->imagen_bg_mobile && file_exists('./uploads/paquetes/'.$paquete->id.'/'.$paquete->imagen_bg_mobile)):
				$img = base_url().'uploads/paquetes/'.$paquete->id.'/'.$paquete->imagen_bg_mobile;
			else:
				$img = ($paquete->imagen_bg && file_exists('./uploads/paquetes/'.$paquete->id.'/'.$paquete->imagen_bg)) ? (base_url().'uploads/paquetes/'.$paquete->id.'/'.$paquete->imagen_bg) : '';
			endif; ?>
			
			<div class='embed-container'><iframe src="<?=youtube_embed_url($paquete->url_video);?>?controls=0&rel=0&showinfo=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>

		<? else: ?>
			<img alt="" class="hero__wave" src="<?=base_url();?>media/assets/images/shapes/hero-wave.png">
		<? endif; ?>
	  </div>
	  <!-- MAIN -->
	  <main class="main">
	  	<form id="fPaquete">
		<section class="heading">
		  <h2 class="heading__title"><?=$paquete->nombre_visible;?></h2>
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
				<? if($paquete->itinerario && file_exists('./uploads/paquetes/'.$paquete->id.'/'.$paquete->itinerario)): ?>
				<div>
				  <a class="heading__share__item__link" href=""><i class="far fa-file-pdf"></i></a>
				  <a href="<?=base_url().'uploads/paquetes/'.$paquete->id.'/'.$paquete->itinerario;?>" target="_blank" class="heading__share__item__label">Descargar itinerario</a>
				</div>
				<? endif; ?>
			  </div>
			  <? if($paquete->descripcion): ?>
			  <div class="heading__descripcion">
				<p><?=$paquete->descripcion;?></p>
			  </div>
			  <? endif; ?>
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
		
			<input type="hidden" id="paquete_id" name="paquete_id" value="<?=$paquete->id;?>"/>
			<input type="hidden" id="field" name="field" value=""/>
			<input type="hidden" id="detalle_visible" name="detalle_visible" value="0" />
			<input type="hidden" id="aside_mobile" name="aside_mobile" value="0" />
			<input type="hidden" id="zocalo_mobile" name="zocalo_mobile" value="0" />
			
			
			<input type="hidden" id="paquete_precio" name="paquete_precio" value="<?=precio_bruto($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
			<input type="hidden" id="impuestos" name="impuestos" value="<?=precio_impuestos($combinacion,true,$paquete->grupal?$pax_elegidos:false);?>"/>
			<input type="hidden" id="adicionales_precio" name="adicionales_precio" value="0"/>
			

		  <div class="detalle__left-container">
			<article class="items-incluidos clearfix">
				<? foreach($caracteristicas as $c): ?>					
					<div class="items-incluidos__item clearfix">
						<? if($c->icono): ?>
						<div class="items-incluidos__item__icon"
						  style="background-image: url('<?=base_url();?>media/assets/images/icon/features/<?=$c->icono;?>')"></div>
						<? endif; ?>
						<div class="items-incluidos__item__label"><span><?=$c->nombre;?></span></div>
				  	</div>
				<? endforeach; ?>
			</article>

			<div id="form_salidas">
				<?=$form_salidas;?>
			</div>

			<div class="transporte" id="form_transportes">
				<?=$form_transportes;?>
			</div>

			<div class="alojamiento" id="form_alojamientos">
				<?=$form_alojamientos;?>
			</div>

			<div id="form_adicionales">
				<?=$form_adicionales;?>
			</div>


		   <!-- MODULO CHECKLIST-->
			  <? if(count($excursiones)): ?>
				<article class="section-excursiones">
					<div class="modulo-excursiones">
					  <div class="modulo-excursiones_title">EXCURSIONES Y/O ACTIVIDADES</div>
					  <div class="modulo-excursiones_checklist">
						<? foreach($excursiones as $e): ?>
							<div>
								<input type="checkbox" id="exc-1"><label for="exc-1"><?=$e->nombre;?></label> </span>
							</div>
						<? endforeach; ?>
					  </div>
					</div>
			  	</article>
			  <? endif; ?>

			  <? if($paquete->documentacion_requerida || count($documentaciones)): ?>
		 	 	<article class="section-info-importante">
				  <!-- MODULO INFORMACIÓN IMPORTANTE-->
				  <div class="info-importante">
					<div class="info-importante_title"><img src="<?=base_url();?>media/assets/images/icon/info-blue.png"><div>INFORMACIÓN IMPORTANTE</div></div>
					<div class="info-importante_box">
					  <div class="info-importante_dni">
						<span class="blue">DOCUMENTACIÓN REQUERIDA</span>
						<? if($paquete->documentacion_requerida): ?>
							<p><?=$paquete->documentacion_requerida;?></p>
						<? endif; ?>
						<? foreach($documentaciones as $d): ?>
							<span class="bold"><?=$d->nombre;?></span><br>
						<? endforeach; ?>
					  </div>
					  <div class="info-importante_content clearfix">
						<? if(count($medios)>0): ?>
							<div class="info-importante__item info-importante_content-medios">
							  <span class="blue bold">Medios de pago</span>
							  	<? foreach($medios as $m): ?>
								<p>
									<span class="bold">
									<?=$m->nombre;?> <br>
									</span>
									<?=$m->descripcion;?>
								  </p>
								<? endforeach; ?>
							</div>
						<? endif; ?>

						<? if(count($promociones)>0): ?>
						<div class="info-importante__item info-importante_content-promos">
						  <span class="blue bold">Promociones</span>
						  <p>
						  	<? foreach($promociones as $m): ?>
							<span class="bold"><?=$m->nombre;?></span>
							<?=$m->descripcion;?>
						<? endforeach; ?>
						  </p>
						</div>
						<? endif; ?>

						<? if($paquete->operador): ?>
						<div class="info-importante__item info-importante_content-operador">
						  <span class="blue bold">Operador</span>
						  <p>
							<span class="bold"><?=$paquete->operador;?></span>
							<?=$paquete->legajo;?>
						  </p>
						</div>
						<? endif; ?>

						<? if($paquete->aclaraciones): ?>
						<div class="info-importante__item info-importante_content-adicional">
						  <span class="blue bold">Información Adicional</span>
						  <p>
							<?=$paquete->aclaraciones;?>
						  </p>
						</div>
						<? endif; ?>

					  </div>
					</div>
				  </div>

				</article>
		 	 <? endif; ?>

		  </div>

			<!--  TICKET -->
		  	 <div id="detalle_calculador">
				<div class="detalle_compra">
	 				<?=$detalle_calculador;?>
				</div>
			 </div>


		</section>
		</form>
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

	init_js_paquete();

  </script>
