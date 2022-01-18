<?=$header;?>

	<?=$categoria->script_conversion;?>

	<!-- CUERPO -->

	<div id="main">

		<div class="banner" style="background-image: url('<?=base_url();?>uploads/categorias/<?=$categoria->id;?>/<?=$categoria->imagen;?>')">
			<div class="container">

				<div class="info">
					<div class="detalles">					
						<p class="title"><strong><?=$categoria->nombre;?></strong></p>

						<? if(@$categoria->disponibles && @$precio_minimo->total>0): ?>
						<span>Por persona desde</span>
						<p class="descripcion"><?=strip_tags(precio($precio_minimo->total,$precio_minimo->precio_usd),'<sup><strong>');?> + <?=precio_impuestos_clean($precio_minimo->impuestos,false,false);?> imp.</p>
						<? else: ?>
						<p class="descripcion"><strong>PRÓXIMAMENTE</strong></p>
						<? endif; ?>
						
						<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

						<div class="fb-share-button" data-href="<?=current_url();?>" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=current_url();?>&amp;src=sdkpreparse">Compartir</a></div>

						<a href="https://twitter.com/share" class="twitter-share-button">Twittear</a>
					</div>

					<div class="messenger hidden-xs hidden-sm">
						<span>¿Tienes una consulta?</span>

						<a class="messenger_button" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
							<span>Escríbenos</span>
							<span class="icon-facebook-messenger"></span>
						</a>

						<a class="messenger_button" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank">
							<span>Whatsapp</span>
							<span class="icon-whatsapp"></span>
						</a>
					</div>
				</div>

			</div>
		</div>

		<div class="container">
			<h1><?=$categoria->titulo!=''?$categoria->titulo:'¡Los mejores paquetes turísticos para conocer '.$categoria->nombre.'!';?></h1>


			<div class="share_buttons hidden-md hidden-lg">
				<span>Comparte</span>

				<a href="#" class="facebook lnkFB" rel="<?=current_url();?>">
					<span class="icon-facebook"></span>
					<span class="sr-only">Facebook</span>
				</a>

				<a href="#" class="Twitter lnkTW" rel="<?=current_url();?>" data-text="<?=$categoria->nombre;?>">
					<span class="icon-twitter"></span>
					<span class="sr-only">Twitter</span>
				</a>

				<a href="whatsapp://send?text=<?=$categoria->nombre.' '.current_url();?>" class="whatsapp">
					<span class="icon-whatsapp"></span>
					<span class="sr-only">Whatsapp</span>
				</a>

				<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>

			<div class="messenger hidden-md hidden-lg">
				<a class="messenger_button" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
					<span>Escríbenos</span>
					<span class="icon-facebook-messenger"></span>
				</a>
				<a class="messenger_button" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank">
					<span>Whatsapp</span>
					<span class="icon-whatsapp"></span>
				</a>
			</div>


			<h2 class="hidden-xs hidden-sm"><?=$categoria->subtitulo;?></h2>

			<? foreach($estacionales as $c): ?>
			
			<!-- BLOQUE -->

			<div class="bloque_viajes">
				<h3><a href="<?=site_url($c->slug);?>"><?=$c->nombre;?></a></h3>

				<div class="row">
					<? foreach($c->destinos as $d): 
						echo contenido($d,'col-xs-12 col-sm-6 col-md-4');
					endforeach; ?>
				</div>
			</div>

			<!-- FIN BLOQUE -->

			<? endforeach; ?>

		</div>


		<div class="nota_paquetes">
			<div class="container">

				<span>Paquetes en <?=$categoria->nombre;?></span>

				<p><?=$categoria->descripcion;?></p>

			</div>
		</div>


		<?=$medios_de_pago;?>

	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>