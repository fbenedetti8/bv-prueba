<?=$header;?>

	<!-- CUERPO -->

	<div id="checkout" class="lista_de_espera_proximamente">
	<div id="main">

		<?=$floating_help;?>

		<div class="banner" style="background-image: url('<?=base_url();?>uploads/categorias/<?=$categoria->id;?>/<?=$categoria->imagen;?>')">
			<div class="container">

				<div class="info">
					<div class="detalles">
						<p class="title" style="<?=$this->detector->isMobile()?'text-shadow: none;color:#fff !important;':'display:none !important;';?>"><strong><?=$categoria->nombre;?></strong></p>
						
						<p class="descripcion"><strong>PRÓXIMAMENTE</strong></p>
						
						<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

						<div class="fb-share-button" data-href="<?=current_url();?>" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?=current_url();?>&amp;src=sdkpreparse">Compartir</a></div>

						<a rel="nofollow" href="https://twitter.com/share" class="twitter-share-button">Twittear</a>
					</div>

					<div class="messenger hidden-xs hidden-sm">
						<span>¿Tenés una consulta?</span>

						<a class="messenger_button" rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
							<span>Escribinos</span>
							<span class="icon-facebook-messenger"></span>
						</a>
						<a class="messenger_button" rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank">
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
				<span>Compartí</span>

				<a href="#" class="facebook lnkFB" rel="<?=current_url();?>">
					<span class="icon-facebook"></span>
					<span class="sr-only">Facebook</span>
				</a>

				<a href="#" class="Twitter lnkTW" rel="<?=current_url();?>" data-text="<?=$categoria->nombre;?>">
					<span class="icon-twitter"></span>
					<span class="sr-only">Twitter</span>
				</a>

				<a rel="nofollow" href="whatsapp://send?text=<?=$categoria->titulo.' '.current_url();?>" class="whatsapp">
					<span class="icon-whatsapp"></span>
					<span class="sr-only">Whatsapp</span>
				</a>

				<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>

			<div class="messenger hidden-md hidden-lg">
				<a class="messenger_button" rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
					<span>Escribinos</span>
					<span class="icon-facebook-messenger"></span>
				</a>
				<a class="messenger_button" rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank">
					<span>Whatsapp</span>
					<span class="icon-whatsapp"></span>
				</a>
			</div>


			<h2 class="hidden-xs hidden-sm"><?=$categoria->subtitulo;?></h2>

			<div class="aside_content container">
			<form class="contenido" id="frmLE" style="    width: 100%;">
				<input type="hidden" name="tipo" value="categoria"/>
				<input type="hidden" name="tipo_id" value="<?=$categoria->id;?>"/>


				<div>
					<h3>LISTA DE ESPERA</h3>

					<p>Dejanos tus datos y te avisamos cuando haya algún viaje de este tipo disponible.</p>

					<div class="row">
						<label class="required">
							<span>Nombre <span class="obligatorio">(Obligatorio)</span></span>
							
							<input type="text" name="nombre" />

							<span class="msg">CAMPO OBLIGATORIO</span>
						</label>

						<label class="required">
							<span>E-mail <span class="obligatorio">(Obligatorio)</span></span>
							
							<input type="text" name="email" />
							<span class="msg">CAMPO OBLIGATORIO O FORMATO INCORRECTO</span>
						</label>

						<label class="required">
							<span>Celular <span class="obligatorio">(Obligatorio)</span></span>

							<div class="tel">
								<div>
									<span>0</span>
									<input type="text" name="celular_codigo" />
								</div>
								
								<div>
									<span>15</span>
									<input type="text" name="celular_numero" />
								</div>
							</div>
							<span class="msg">CAMPO OBLIGATORIO</span>
						</label>


						<label>
							<span>Teléfono alternativo</span>

							<div class="tel">
								<div>
									<input type="text" placeholder="Cód. Área" name="telefono_codigo">
								</div>
								
								<div>
									<input type="text" placeholder="Teléfono" name="telefono_numero">
								</div>
							</div>
						</label>
					</div>

					<div class="submit">
						<input class="button espera" type="button" id="le_btn_submit" value="Avisarme cuando esté disponible" />
					</div>

					<!-- MENSAJE -->
					<div class="alert hidden">
						<p class="msg"></p>
					</div>
					<!-- FIN MENSAJE -->
					
				</div>


				<!-- FIN OPCIONES PAQUETE MOBILE -->

			</form>
		</div>



		</div>


		<?=$medios_de_pago;?>

	</div>
	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>