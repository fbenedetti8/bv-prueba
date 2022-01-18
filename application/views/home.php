<?=$header;?>

	<main>

		<?=$floating_help;?>

		<section>
			<div class="container intro">
				<div>
					<div class="title">
						<h2>La experiencia de tu vida.</h2>
						<div class="text">
							<p>Para nosotros viajar es mucho más que visitar un nuevo destino: es hacer nuevos amigos y vivir momentos inolvidables</strong>.</p>
						</div>
					</div>
				</div>
			</div>
		</section>


		<section class="module__destacados">
			<div class="container">
				<div class="controls">
					<button class="prev_btn"></button>
					<button class="next_btn"></button>
				</div>

				<div>
					<div class="title">
						<div class="text">
							<h3 class="text_title">Destacados</h3>
						</div>
						<div class="buttons">
							<button class="btn sm btn-pink-outline"><a href="<?=site_url('proximos-viajes');?>">Ver todos</a></button>
						</div>
					</div>
				</div>
			</div>

			<div class="cont-carrousel">
				<div class="row no-margin">
					<div class="owl-carousel owl-carousel-cards owl-theme">

						<? foreach ($destinos as $d) { ?>
						<!-- -->

						<div class="item">

							<div class="item card-product type1">
								<? 
								$img_anterior = ($d->imagen && file_exists('./uploads/destinos/'.$d->id.'/'.$d->imagen)) ? base_url().'./uploads/destinos/'.$d->id.'/'.$d->imagen : FALSE;
								$resize_desktop = hay_resize($d,'destinos','desktop');
								$resize_mobile = hay_resize($d,'destinos','mobile');

								if($resize_desktop || $resize_mobile || $img_anterior): ?>
								<!-- Card header -->
								<div class="card-header">
									<a href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->slug);?>">

										<? if($resize_desktop || $resize_mobile): ?>
											
											<picture>
												<? if($resize_desktop):?>
													<source media="(min-width: 991px)" srcset="<?=$resize_desktop;?>" />
												<? endif; ?>

												<? if($resize_mobile):?>
													<source srcset="<?=$resize_mobile;?>" />
												<? endif; ?>
													
												<img class="card-img-top" src="<?=$img_anterior;?>" alt="Card image cap" />
											</picture>
										
										<? else: ?>
		
											<img class="card-img-top" src="<?=$img_anterior;?>" alt="Card image cap">

										<? endif; ?>

										<div class="group-buttons">
											<? foreach ($d->cat_estacionales as $est) : ?>
												<? if($est->imagen && file_exists('./uploads/estacionales/'.$est->id.'/'.$est->imagen)): ?>
													<button class="btn-photo" style="background-image: url('./uploads/estacionales/<?=$est->id.'/'.$est->imagen;?>');"></button>
												<? endif; ?>
											<? endforeach; ?>
											<!--
											<button class="btn-photo"></button>
											<button class="btn-config"></button>
											-->
										</div>
									</a>
								</div>
								<? endif; ?>

								<!-- Card Body -->
								<div class="card-body">
									<!-- Title -->
									<a class="card-title-wrapper" href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->slug);?>">
										<h4 class="card-title"><?=$d->nombre;?></h4>
									</a>
									<!-- Text -->
									<p class="card-text"><?=limit_text($d->descripcion,150);?></p>

								</div>

								<!-- Card Footer -->
								<div class="card-footer">
									
									<? if($d->disponibles>0 && isset($d->precio)): ?>
										<div class="group-data-info">

											<? $anterior = false;
											if(isset($d->precio_anterior_neto) && $d->precio_anterior_neto): $anterior = true;?>
											<div class="option-1">
												<p class="title">Por persona desde</p>
												<div class="group-price">
													<p class="price price-line-through"><?=precio_redondeado($d->precio_anterior_neto,$d->precio_usd);?></p>
													<p class="price-imp">+<?=precio_redondeado($d->precio_anterior_impuestos,$d->precio_usd);?> imp</p>
												</div>
											</div>
											<? endif; ?>

											<div class="option-<?=$anterior?'2':'1';?>">
												<? if($anterior): ?>
													<p class="title now">Ahora</p>
												<? else: ?>
													<p class="title">Por persona desde</p>
												<? endif; ?>
												<div class="group-price">
													<p class="price"><?=precio_redondeado($d->precio,$d->precio_usd);?></p>
													<p class="price-imp">+<?=precio_redondeado($d->impuestos,$d->precio_usd);?> imp</p>
												</div>
											</div>

										</div>
									<? else: ?>
										<div class="group-data-info">
											<div class="option-1">
												<p class="title">Por persona desde</p>
												<div class="group-price">
													<p class="text-default">Proximamente</p>
												</div>
											</div>
										</div>
									<?endif; ?>

									<div class="group-info-button">
									
										<button class="btn btn-pink" onclick="location.href='<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->slug);?>';"><a href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->slug);?>">+ Info</a></button>
									</div>
								</div>
							</div>
						</div>
				
						<? } ?>
						
						<!-- -->
					</div>

				</div>
			</div>
		</section>


		<!-- Una experiencia distinta -->
		<section class="module__unaExperiencia">
			<div class="cont-info">
				<div class="container">
					<div class="title">
						<h2>Una experiencia distinta</h2>
					</div>

					<div class="info_wrapper">
						<div class="picture">
							<div>
								<img src="<?=base_url();?>media/assets/images/img/una_experiencia_distinta.jpg" alt="">
							</div>
						</div>
						<div class="info">
							<div class="text">
								<p>No te preocupes si no podes viajar con tus amigos o si no te animas a viajar solo. Con nosotros podes vivir la experiencia de viajar de manera grupal. Vas a conocer personas de tu misma edad dispuestas a descubrir nuevos lugares, comer algo diferente y, sobre todo, compartir momentos inolvidables y divertirse.</p>
							</div>
							<button class="btn btn-border-white">
								<a href="<?=base_url();?>proximos-viajes">¿Quieres acompañarnos?</a>
							</button>
						</div>

					</div>
				</div>
			</div>
		</section>
		<!-- // Una experiencia distinta -->


		<? $src_ofi = base_url().'media/assets/images/img/02.jpg'; ?>
		<!-- Oficina Feliz -->
		<section class="module__oficina">
			<div class="container">
				<div class="info_wrapper">
					<div class="title">
						<h2>La Oficina Feliz</h2>
					</div>
					<div class="info">
						<div class="text">
							<p><strong>Así trabajamos</strong>. Este es nuestro equipo. Nos gusta lo que hacemos, lo disfrutamos, estamos orgullosos de nuestra empresa. <strong>Te invitamos a que nos conozcas</strong>.</p>
						</div>
						<div class="gallery hidden-xs hidden-sm">
							<div class="cont-thumbs">
							<? if(isset($oficinas) && count($oficinas)): ?>
								<? $i=0; foreach ($oficinas as $o): 
									if($o->imagen && file_exists('./uploads/oficinafeliz/'.$o->id.'/'.$o->imagen)): 
										if($i == 0){ 
											$src_ofi = base_url().'uploads/oficinafeliz/'.$o->id.'/'.$o->imagen;
										}
										$i++;
										?>
										<div class="thumbs">
											<img src="<?=base_url();?>uploads/oficinafeliz/<?=$o->id.'/'.$o->imagen;?>" alt="Foto <?=$i;?>" class="filter-bw" style="width:100%" onclick="myFunction(this);" />
										</div>
									<? endif; ?>
								<? endforeach; ?>
							<? else: ?>
								<div class="thumbs">
									<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="Snow" class="filter-bw" style="width:100%" onclick="myFunction(this);">
								</div>
								<div class="thumbs">
									<img src="<?=base_url();?>media/assets/images/img/una_experiencia_distinta.jpg" alt="Nature" class="filter-bw" style="width:100%" onclick="myFunction(this);">
								</div>
								<div class="thumbs">
									<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="Snow" class="filter-bw" style="width:100%" onclick="myFunction(this);">
								</div>
								<div class="thumbs">
									<img src="<?=base_url();?>media/assets/images/img/una_experiencia_distinta.jpg" class="filter-bw" alt="Mountains" style="width:100%" onclick="myFunction(this);">
								</div>
								<div class="thumbs">
									<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="Lights" class="filter-bw" style="width:100%" onclick="myFunction(this);">
								</div>
							<? endif; ?>
							</div>
						</div>


						<div class="gallery hidden-md hidden-lg">
							<div class="owl-carousel">
								<? if(isset($oficinas) && count($oficinas)): ?>
									<? $i=0; foreach ($oficinas as $o): 
										if($o->imagen && file_exists('./uploads/oficinafeliz/'.$o->id.'/'.$o->imagen)): 
											$i++; ?>
											<div class="item">
												<img src="<?=base_url();?>uploads/oficinafeliz/<?=$o->id.'/'.$o->imagen;?>" alt="Foto <?=$i;?>"  />
											</div>
										<? endif; ?>
									<? endforeach; ?>
								<? else: ?>
									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>

									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>

									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>

									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>

									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>

									<div class="item">
										<img src="<?=base_url();?>media/assets/images/img/02.jpg" alt="La Oficina Feliz" />
									</div>
								<? endif; ?>
							</div>
						</div>


						<div class="group-buttons">
							<div>
								<a rel="nofollow" href="http://www.facebook.com/buenas.vibras" target="_blank"><img src="<?=base_url();?>media/assets/images/icons/icon-fb.png" alt=""></a>
							</div>
							<div>
								<a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes" target="_blank"><img src="<?=base_url();?>media/assets/images/icons/icon-inst.png" alt=""></a>
							</div>
						</div>
					</div>

					<div class="picture hidden-xs hidden-sm">
						<div class="">
							<img id="expandedImg" style="width:100%" src="<?=$src_ofi;?>">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- // Oficina Feliz -->


		<? if(count($testimonios)): ?>
		<!-- Slider Viajeros -->
		<section class="module__slideViajeros">
			<div class="container cont-slider">
				<div>
					<div class="owl-carousel owl-carousel-viajeros">

						<? foreach($testimonios as $t): ?>
						<!-- -->

						<div class="item card-testimonio">
							<div class="cont-card">
								<div class="info">
									<div class="title">
										<h3>Los viajeros opinan: <strong><?=$t->nombre;?></strong></h3>
										<p>Visitó <strong><?=$t->lugar;?></strong> (<?=$t->provincia;?>, <?=$t->pais;?>)</p>
									</div>

									<? 
									$img_anterior = ($t->imagen && file_exists('./uploads/testimonios/'.$t->id.'/'.$t->imagen))  ? base_url().'./uploads/testimonios/'.$t->id.'/'.$t->imagen : FALSE;
									$resize_desktop = hay_resize($t,'testimonios','desktop');
									$resize_mobile = hay_resize($t,'testimonios','mobile');

									if($resize_desktop || $resize_mobile || $img_anterior): ?>
									
									<div class="avatar">
										<div class="marks"><img src="<?=base_url();?>media/assets/images/icons/quotes.png" alt=""></div>

										<? if($resize_desktop || $resize_mobile): ?>
											
											<picture class="perfil">
												<? if($resize_desktop):?>
													<source media="(min-width: 991px)" srcset="<?=$resize_desktop;?>" />
												<? endif; ?>

												<? if($resize_mobile):?>
													<source srcset="<?=$resize_mobile;?>" />
												<? endif; ?>

												<img class="card-img-top" src="<?=$img_anterior;?>" alt="Card image cap" />
											</picture>
										
										<? else: ?>

											<figure class="perfil"><img src="<?=$img_anterior;?>" alt=""></figure>

										<? endif; ?>
									
									</div>

									<? endif; ?>

									<div class="text">
										<div class="icon-triangle">
											<span class="triangulo"></span>
										</div>
										<p><?=$t->testimonio;?></p>
									</div>
								</div>
							</div>
						</div>

						<? endforeach; ?>

						

					</div>
				</div>

			</div>
		</section>
		<!-- // Slider Viajeros -->
		<? endif; ?>

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


	</main>

<?=$footer?>