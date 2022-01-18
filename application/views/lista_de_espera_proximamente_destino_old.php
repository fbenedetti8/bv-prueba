<?=$header;?>

	<!-- CUERPO -->

	<div id="main">

		<? if($this->detector->isMobile() && file_exists('./uploads/destinos/'.$destino->id.'/'.$destino->imagen_mobile)):
			$img = base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen_mobile;
		else:
			$img = base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen;
		endif; ?>

		<div class="banner" style="background-image: url('<?=$img;?>')">
			<div class="container">

				<div class="info">
					<div class="detalles">

						<!-- Class "paquete" -->
						<!-- Class "grupal" -->

						<div class="tag <?=$destino->grupal?'grupal':'paquete';?>">

							<!-- clase "icon-group" para grupales -->
							<!-- clase "icon-suitcase" para paquetes -->
							<span class="<?=$destino->grupal?'icon-group':'icon-suitcase';?>"></span>
							<span><?=$destino->grupal?'Grupal':'Paquete';?></span>

							<div class="btn_tooltip">
								<span class="icon-question-mark"></span>

								<div class="tooltip">
									<div></div>
									<? if($destino->grupal): ?>
										<span><?=$this->config->item('viaje_grupal');?></span>
									<? else: ?>
										<span><?=$this->config->item('viaje_no_grupal');?></span>
									<? endif; ?>
								</div>
							</div>
						</div>

						<h1 class="title"><strong><?=$destino->nombre;?></strong></h1>

						<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

						<div class="fb-share-button" data-href="<?=current_url();?>" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=current_url();?>&amp;src=sdkpreparse">Compartir</a></div>

						<a href="https://twitter.com/share" class="twitter-share-button">Twittear</a>
					</div>

					<div class="messenger hidden-xs hidden-sm">
						<span>¿Tenés una consulta?</span>

						<a class="messenger_button" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
							<span>Escribinos</span>
							<span class="icon-facebook-messenger"></span>
						</a>
						<a class="messenger_button" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank">
							<span>Whatsapp</span>
							<span class="icon-whatsapp"></span>
						</a>
					</div>
				</div>

			</div>
		</div>



		<div class="container">

			<div class="share_buttons hidden-md hidden-lg">
				<span>Compartí</span>

				<a href="#" class="facebook lnkFB" rel="<?=current_url();?>">
					<span class="icon-facebook"></span>
					<span class="sr-only">Facebook</span>
				</a>

				<a href="#" class="Twitter lnkTW" rel="<?=current_url();?>" data-text="<?=$destino->nombre;?>">
					<span class="icon-twitter"></span>
					<span class="sr-only">Twitter</span>
				</a>

				<a href="whatsapp://send?text=<?=$destino->nombre.' '.current_url();?>" class="whatsapp">
					<span class="icon-whatsapp"></span>
					<span class="sr-only">Whatsapp</span>
				</a>

				<div class="fb-like" data-href="<?=current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>

			<div class="messenger hidden-md hidden-lg">
				<a class="messenger_button" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
					<span>Escribinos</span>
					<span class="icon-facebook-messenger"></span>
				</a>
				<a class="messenger_button" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank">
					<span>Whatsapp</span>
					<span class="icon-whatsapp"></span>
				</a>
			</div>

		</div>



		<div class="aside_content container">
			<form class="contenido" id="frmLE" style="<?=(count($caracteristicas)==0)?'width:100%;':'';?>">
				<input type="hidden" name="tipo" value="destino"/>
				<input type="hidden" name="tipo_id" value="<?=$destino->id;?>"/>

				<p class="intro"><?=$destino->descripcion;?></p>


				<div>
					<h3>LISTA DE ESPERA</h3>

					<p>Dejanos tus datos y te avisamos cuando haya lugar disponible para este viaje.</p>

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

						<label>
							<span>Celular</span>

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

				<? if(count($caracteristicas)>0): ?>
				<div class="opciones_paquete hidden-md hidden-lg">

					<h3>Qué incluye este paquete:</h3>

					<div class="row">
						<!--
							"icon-hotel" 	 -> Alojamiento
							"icon-running" 	 -> Excursiones
							"icon-cocktail"  -> Fiestas Temática
							"icon-breakfast" -> Desayuno / Media Pensión
							"icon-car" 		 -> Traslados
							"icon-medic" 	 -> Seguros y Asistencia Médica
						-->
						<? foreach($caracteristicas as $c): ?>
						<div>
							<span class="<?=$c->clase;?>"></span>
							<span><?=$c->nombre;?></span>
						</div>
						<? endforeach; ?>
					</div>

				</div>

				<!-- FIN OPCIONES PAQUETE MOBILE -->
				<? endif; ?>


				<? if(count($fotos)>0): ?>
				<!-- GALERIA DESKTOP -->

				<div class="galeria no-padding hidden-xs hidden-sm">
					<h3>GALERÍA DE FOTOS</h3>

					<!-- LAS 3 PRIMERAS IMAGENES -->

					<div class="row">

						<? $n=0;
						foreach($fotos as $f): 
							if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
								$n++; 
								if($n<=3):?>
									<div>
										<a class="fancybox" data-fancybox="gallery" rel="group" href="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>">
											<div>
												<img class="img-responsive" src="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>" alt="Imagen 1" />
												
												<? if($n==3): ?>
													<!-- ESTE DIV VA EN LA TERCERA FOTO -->
													<div class="mas_fotos">
														<span>Ver más fotos</span>
													</div>
													<!-- ESTE DIV VA EN LA TERCERA FOTO -->
												<? endif; ?>
											</div>
										</a>
									</div>
								<? else: //despues de la 3ra pongo links ocultos?>
									<a class="fancybox" data-fancybox="gallery" rel="group" href="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>"></a>
								<? endif;							
							endif;
						endforeach; ?>

					</div>
				</div>

				<!-- FIN GALERIA DESKTOP -->
				<? endif; ?>

				<? if(count(@$fotos) > 0): ?>
				<!-- BOTÓN GALERIA MOVILE -->
				<div class="galeria hidden-md hidden-lg">
					<? $n=0;
					foreach($fotos as $f):
						if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
							$n++; ?>
							<a class="fancybox btn_galeria <?=$n>1?'hidden':'';?>" data-fancybox="gallery" rel="group" href="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>"><?=$n==1?'Galería de fotos':'';?></a>
						<? endif;
					endforeach; ?>
				</div>
				<!-- FIN BOTÓN GALERIA MOVILE -->
				<? endif; ?>

				<? if(count($caracteristicas)>0): ?>
				<!-- DETALLE DE COMPRA DESKTOP -->
				<!-- (cuadro fijo) -->
				
				<div class="detalle_compra hidden-xs hidden-sm">
					<div class="container">

						<div class="detalle_aside">

							<h3>La mayoría de las opciones del destino incluyen:</h3>

							<? foreach($caracteristicas as $c): ?>
							<div>
								<span class="<?=$c->clase;?>"></span>
								<span><?=$c->nombre;?></span>
							</div>
							<? endforeach; ?>

						</div>
					
					</div>
				</div>
				
				<!-- FIN DETALLE DE COMPRA DESKTOP -->
				<? endif; ?>


			</form>
		</div>




		<? if(count($otros_destinos)>0): ?>
		<div class="container">
			<div class="otros_destinos">
				<h3>Otros destinos para vos</h3>

				<div class="row">

					<? foreach($otros_destinos as $d): 
						echo contenido($d,'col-xs-12 col-sm-6 col-md-4');
					endforeach; ?>
					
				</div>
			</div>
		</div>
		<? endif; ?>
		
		<?=$medios_de_pago;?>

	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>