<?=$header;?>

	<!-- CUERPO -->

	<div id="main">

		<div class="carousel">
			<div class="owl-carousel no-nav carousel_desktop">

				<!-- BANNER DESKTOP -->
				<? foreach($destacados as $d): ?>
					<? if($d->imagen && file_exists('./uploads/destacados/'.$d->id.'/'.$d->imagen)): ?>
						<? if($d->url): ?>
						<a href="<?=$d->url;?>" <?=$d->link_externo?'target="_blank"':'';?>>
						<? endif;?>
							<img src="<?=base_url();?>uploads/destacados/<?=$d->id.'/'.$d->imagen;?>" alt="<?=$d->nombre;?>" />						
						<? if($d->url): ?>
						</a>
						<? endif;?>
					<? endif; ?>
				<? endforeach; ?>
				<!-- -->
			</div>



			<div class="owl-carousel no-nav carousel_mobile">

				<!-- BANNER MOBILE -->
				<? foreach($destacados as $d): ?>
					<? if($d->imagen_mobile && file_exists('./uploads/destacados/'.$d->id.'/'.$d->imagen_mobile)): ?>
						<? if($d->url): ?>
						<a href="<?=$d->url;?>" <?=$d->link_externo?'target="_blank"':'';?>>
						<? endif;?>
							<img src="<?=base_url();?>uploads/destacados/<?=$d->id.'/'.$d->imagen_mobile;?>" alt="<?=$d->nombre;?>" />
						<? if($d->url): ?>
						</a>
						<? endif;?>
					<? endif; ?>
				<? endforeach; ?>
				<!-- -->
			</div>
		</div>


		<div class="container">
			<h1>Revolucionamos el Turismo para Jóvenes.</h1>
			<h2>Tu viaje comienza aquí.</h2>

			<div class="row">
				<div class="viajes_wrapper owl-carousel">

					<? $clases = array('col-xs-12 col-sm-6','col-xs-12 col-sm-6 col-md-4','col-xs-12 col-sm-6 col-md-4','col-xs-12 col-sm-6 col-md-4','col-xs-12 col-sm-6','col-xs-12 col-sm-6');;
					?>
					<? $n=0;$size_otros=0;
					foreach($destinos as $d): 
						if($d->otros == 0): 
							echo contenido($d,null,false,true);
							$n+=1;
						else:
							$size_otros+=1;
						endif;
					endforeach; ?>
					
					<!-- -->

					<!-- FIN LISTA VIAJES -->

				</div>
			</div>


			<? if($size_otros>0): ?>
			<div class="otras_opciones">
				<h2>Otras opciones para vos</h2>

				<div class="row">
				<? $n=0;
					foreach($categorias as $c): 
					if($c->otros): ?>
					<a href="<?=site_url($c->slug);?>" class="related_tag"><?=$c->nombre;?></a>
					<? endif; 
					endforeach; ?>
				</div>
			</div>
			<? endif; ?>

		</div>


		<?=$medios_de_pago;?>


		<div class="conocenos">
			<div class="container">
				<div class="row">

					<div class="col-xs-12 col-md-5">
						<p class="title"><strong>¡Conócenos!</strong></p>

						<span>La Oficina Feliz</span>

						<p>Así trabajamos. Este es nuestro equipo. Nos gusta lo que hacemos, lo disfrutamos, estamos orgullosos de nuestra empresa.<br />
						Te invitamos a que nos conozcas.</p>

						<div class="fb-like" data-href="https://www.facebook.com/buenas.vibras" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
					</div>


					<div class="col-xs-12 col-md-5">
						<div class="owl-carousel">
							<img src="<?=base_url();?>media/assets/imgs/slider/1.png" alt="Foto 1" />
							<img src="<?=base_url();?>media/assets/imgs/slider/2.png" alt="Foto 2" />
							<img src="<?=base_url();?>media/assets/imgs/slider/3.png" alt="Foto 3" />
							<img src="<?=base_url();?>media/assets/imgs/slider/4.png" alt="Foto 4" />
							<img src="<?=base_url();?>media/assets/imgs/slider/5.png" alt="Foto 5" />
							<img src="<?=base_url();?>media/assets/imgs/slider/6.png" alt="Foto 6" />
							<img src="<?=base_url();?>media/assets/imgs/slider/7.png" alt="Foto 7" />
							<img src="<?=base_url();?>media/assets/imgs/slider/8.png" alt="Foto 8" />
							<img src="<?=base_url();?>media/assets/imgs/slider/9.png" alt="Foto 9" />
							<img src="<?=base_url();?>media/assets/imgs/slider/10.png" alt="Foto 10" />
							<img src="<?=base_url();?>media/assets/imgs/slider/11.png" alt="Foto 11" />
							<img src="<?=base_url();?>media/assets/imgs/slider/12.png" alt="Foto 12" />
							<img src="<?=base_url();?>media/assets/imgs/slider/13.png" alt="Foto 13" />
							<img src="<?=base_url();?>media/assets/imgs/slider/14.png" alt="Foto 14" />
							<img src="<?=base_url();?>media/assets/imgs/slider/15.png" alt="Foto 15" />
							<img src="<?=base_url();?>media/assets/imgs/slider/16.png" alt="Foto 16" />
							<img src="<?=base_url();?>media/assets/imgs/slider/17.png" alt="Foto 17" />
							<img src="<?=base_url();?>media/assets/imgs/slider/18.png" alt="Foto 18" />
							<img src="<?=base_url();?>media/assets/imgs/slider/19.png" alt="Foto 19" />
							<img src="<?=base_url();?>media/assets/imgs/slider/20.png" alt="Foto 20" />
						</div>
					</div>

					<div class="seguinos col-xs-12 col-md-2">
						<a href="http://www.facebook.com/buenas.vibras" target="_blank">
							<div>
								<span class="icon-facebook"></span>
							</div>

							<p>Síguenos <span>en Facebook</span></p>
						</a>

						<a href="https://www.instagram.com/buenasvibrasviajes" target="_blank">
							<div>
								<span class="icon-instagram"></span>
							</div>

							<p>Síguenos <span>en Instagram</span></p>
						</a>
					</div>

				</div>
			</div>
		</div>

	</div>

	<!-- FIN CUERPO -->


<?=$footer;?>