<? if(count($otros_destinos)>0): ?>
		<section class="module__destacados">
			<div class="container">
				<div>
					<div class="title">
						<div class="text">
							<h3 class="text_title">Otros destinos recomendados</h3>
						</div>
						<div class="buttons">
							<button class="btn sm btn-pink-outline"><a href="<?=site_url('proximos-viajes');?>">Ver todos</a></button>
						</div>
					</div>
				</div>
			</div>

							<div style="display:none;"><? print_r($otros_destinos); ?></div>
							
			<div class="cont-carrousel">
					<div class="row no-margin">
						<div class="owl-carousel owl-carousel-cards owl-theme">
							
							<? foreach ($otros_destinos as $d) { ?>
							<!-- -->

							<div class="item">

								<div class="item card-product type1">
									<? if($d->imagen && file_exists('./uploads/destinos/'.$d->id.'/'.$d->imagen)): ?>
									<!-- Card header -->
									<div class="card-header">
										<a href="<?=site_url((@$d->categoria_slug?$d->categoria_slug.'/':'').$d->slug);?>">
											<img class="card-img-top" src="<?=base_url();?>uploads/destinos/<?=$d->id.'/'.$d->imagen;?>" alt="Card image cap">
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
										<h4 class="card-title"><?=$d->nombre;?></h4>
										<!-- Text -->
										<p class="card-text"><?=limit_text($d->descripcion,150);?></p>

									</div>

									<!-- Card Footer -->
									<div class="card-footer">
										
										<? if($d->disponibles>0 && isset($d->precio) && $d->precio > 0): ?>
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
	<? endif; ?>