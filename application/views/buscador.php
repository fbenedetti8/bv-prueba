
<!-- Hero -->
		<section class="highlight">

			<div class="container">
				<div class="main_wrapper">

					<div class="main_banner" style="max-height: 460px;overflow: hidden;">
						<? if(isset($destacado) && $destacado): ?>
							<? if($destacado->tipo == 'video'): ?>
								<? if($this->detector->isMobile() && $destacado->imagen_bg_mobile && file_exists('./uploads/destacados/'.$destacado->id.'/'.$destacado->imagen_bg_mobile)):
									$img = base_url().'uploads/destacados/'.$destacado->id.'/'.$destacado->imagen_bg_mobile;
								else:
									$img = ($destacado->imagen_bg && file_exists('./uploads/destacados/'.$destacado->id.'/'.$destacado->imagen_bg)) ? (base_url().'uploads/destacados/'.$destacado->id.'/'.$destacado->imagen_bg) : '';
								endif; ?>
								

									<video width="100%" id="videod" loop autoplay muted style="<?=$img?'background-image: url('.$img.');     background-repeat:no-repeat;background-color: transparent;background-size: cover;height: 100%;':'';?>">
										<? if($destacado->video_mp4): ?>
									  		<source src="<?=base_url();?>uploads/destacados/<?=$destacado->id;?>/<?=$destacado->video_mp4;?>"  type="video/mp4">
									  	<? endif; ?>
									  	<? if($destacado->video_webm): ?>
									  		<source src="<?=base_url();?>uploads/destacados/<?=$destacado->id;?>/<?=$destacado->video_webm;?>"  type="video/webm">
									  	<? endif; ?>
									  	<? if($destacado->video_ogg): ?>
									  		<source src="<?=base_url();?>uploads/destacados/<?=$destacado->id;?>/<?=$destacado->video_ogg;?>" type="video/ogg">
									  	<? endif; ?>
										Tu navegador no soporta ver videos.
									</video>

							<? elseif($destacado->tipo == 'imagen'): ?>
									<? if($this->detector->isMobile()): //mobile ?>
									
										<? if($destacado->imagen_mobile && file_exists('./uploads/destacados/'.$destacado->id.'/'.$destacado->imagen_mobile)): ?>
											<img src="<?=base_url();?>uploads/destacados/<?=$destacado->id;?>/<?=$destacado->imagen_mobile;?>" class="hidden-lg hidden-md" alt="Experiencias grupales para viajeros de 20 a 40 años" />
										<? endif; ?>

									<? else : //desktop ?>

										<? if($destacado->imagen && file_exists('./uploads/destacados/'.$destacado->id.'/'.$destacado->imagen)): ?>
											<img src="<?=base_url();?>uploads/destacados/<?=$destacado->id;?>/<?=$destacado->imagen;?>" class="hidden-xs hidden-sm" alt="Experiencias grupales para viajeros de 20 a 40 años" />
										<? endif; ?>

									<? endif; ?>
									
							<? endif; ?>
						<? endif; ?>
					</div>

					<!-- Cont Info -->
					<div class="cont-info">

						<!-- Text -->
						<div class="box-info title">
							<h1><span>GENERAMOS VÍNCULOS</span><span>VIAJANDO POR</span><span>EL MUNDO</span></h1>
						</div>
						<!-- // Text -->


						<div class="selectors_wrapper">
							<form class="selectors">
								<select class="date_selector selectric" data-title="¿<strong>Cuando</strong> te gustaría viajar?">
									<option value="">Seleccionar</option>
									<option value="<?=site_url('proximos-viajes/'.$this->config->item("uri_regiones"));?>">Todas las fechas disponibles</option>
									<? foreach ($fechas as $v): ?>
										<option value="<?=site_url('proximos-viajes/'.$this->config->item("uri_regiones").'/'.$v->fecha);?>"><?=fecha_buscador($v->fecha);?></option>
									<? endforeach; ?>
								</select>

								<select class="place_selector selectric" data-title="¿O prefieres contarnos <strong>a dónde</strong>?">
									<option value="">Seleccionar</option>
									<option value="<?=site_url('proximos-viajes/'.$this->config->item("uri_regiones"));?>">Todas las regiones</option>
									<? foreach ($categorias_activas as $d): ?>
										<option value="<?=site_url('proximos-viajes/'.$d->slug);?>"><?=$d->nombre;?></option>
									<? endforeach; ?>
								</select>
							</form>
						</div>

					</div>
					<!-- //Cont Info -->

				</div>
			</div>
		</section>
		<!-- //--Hero -->
		
		<script>
			/*var vid = document.getElementById("videod");
			vid.oncanplay = function() {
				//oculto la imagen de fondo
			};*/
		</script>
