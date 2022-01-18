<?=$header;?>

	<?=$destino->script_conversion;?>

	<!-- CUERPO -->

	<div id="main">

		<? if($this->detector->isMobile() && file_exists('./uploads/destinos/'.$destino->id.'/'.$destino->imagen_mobile)):
			$img = base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen_mobile;
		else:
			$img = base_url().'uploads/destinos/'.$destino->id.'/'.$destino->imagen;
		endif; ?>

		<div rel="<?=$destino->imagen;?>" relm="<?=$destino->imagen_mobile;?>" class="banner" style="background-image: url('<?=$img;?>')">
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

						<? if ($precio_minimo): ?>
						<span>Por persona desde</span>
						<p class="descripcion"><?=strip_tags(precio($precio_minimo->total,$precio_minimo->precio_usd),'<sup><strong>');?> + <?=precio_impuestos_clean($precio_minimo->impuestos,false,false);?> imp.</p>
						<? endif; ?>

						<div class="fb-like" data-href="<?=$destino->fb_share_url?$destino->fb_share_url:current_url();?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

						<div class="fb-share-button" data-href="<?=current_url();?>" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=$destino->fb_share_url?site_url($destino->fb_share_url):current_url();?>&amp;src=sdkpreparse">Compartir</a></div>

						<a href="https://twitter.com/share" class="twitter-share-button">Twittear</a>
					</div>

					<div class="messenger hidden-xs hidden-sm">
						<span>¿Tienss una consulta?</span>

						<a class="messenger_button" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
							<span>Escríbenos</span>
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
					<span>Escríbenos</span>
					<span class="icon-facebook-messenger"></span>
				</a>
				<a class="messenger_button" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank">
					<span>Whatsapp</span>
					<span class="icon-whatsapp"></span>
				</a>
			</div>


			<? if(count($caracteristicas)>0): ?>
			<!-- OPCIONES PAQUETES DESKTOP -->

			<div class="opciones_paquete hidden-xs hidden-sm">

				<p><strong>Según el paquete que elijas, las opciones incluyen:</strong></p>

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

			<!-- FIN OPCIONES PAQUETES DESKTOP -->
			<? endif; ?>


			<? foreach($estacionales as $e): ?>
			<? if(count($e->paquetes)>0): ?>
			<div class="bloque_destino">

				<h2><a href="<?=base_url().$e->slug;?>"><?=$e->nombre;?></a></h2>

				<!--
					ICONOS:

						icon-anchor 	-> Crucero
						icon-bus		-> Ida y Vuelta
						icon-aeroplane	-> Ida y Vuelta
						icon-location	-> Tour en destino
				-->

				<? foreach($e->paquetes as $p): ?>
				
				<? 
				//si quiere mostrar cupo personalizado, piso los datos reales
				if($p->cupo_paquete_personalizado): 
					$p->disponibles = $p->cupo_paquete_disponible;
					$p->cupo_disponible = $p->cupo_paquete_disponible;
					$p->cupo_total = $p->cupo_paquete_total;
				endif; 

				if(date('Y-m-d') > $p->fecha_inicio):
					$p->cupo_disponible = 0;	
				endif;
				?>
				<!-- -->

				<div class="item_destino">

					<div class="viaje">
						<p><span class="icono <?=$p->clase_transporte;?>"></span></p>
						<p><?=$p->descripcion_transporte;?></p>
					</div>

					<!-- Apartado "DÍAS" DESKTOP -->
					<div class="dias">
						<p><strong><?=dias_viaje($p->fecha_indefinida?$p->fecha_checkin:$p->fecha_inicio,$p->fecha_indefinida?$p->fecha_checkout:$p->fecha_fin);?> días y <?=noches_viaje($p->fecha_checkin,$p->fecha_checkout);?> noches</strong></p>

						<!-- FECHAS PARA DEKTOPS -->
						<p class="hidden-xs hidden-sm"><?=fecha_corta($p->fecha_inicio);?> a <?=fecha_corta($p->fecha_fin);?></p>

						<!-- FECHAS PARA MOBILE -->
						<p class="hidden-md hidden-lg"><?=fecha_corta($p->fecha_inicio);?><br /><?=fecha_corta($p->fecha_fin);?></p>
						
						<? if($p->fecha_indefinida): ?>
						<!-- TOOLTIP -->
						<span class="btn_tooltip">
							<span class="icon-question-mark"></span>
							
							<div class="tooltip">
								<div></div>
								<span>Elegí las fechas disponibles en el próximo paso</span>
							</div>
						</span>
						<? endif; ?>
					</div>

					<div class="salida">
						<div>
							<p><strong>Salida</strong></p>
							<p><?=($p->nombrelugar_salida=='En Destino')?$p->nombrelugar_salida:str_replace(',','-',$p->referencialugar?$p->referencialugar:$p->lugar_salida);?><p>
						</div>
					</div>

					<? if(false): ?>
					<div class="base">
						<p><strong>Pasajeros</strong></p>
						<p><?=pasajeros($p->pax);?><p>
					</div>
					<? endif; ?>

					<!-- Apartado "DÍAS" MOBILE -->
					<div class="dias hidden-md hidden-lg">
						<!-- TEXTO DEKSTOP -->
						<p class="hidden-xs hidden-sm"><strong><?=dias_viaje($p->fecha_inicio,$p->fecha_fin);?> días y <?=noches_viaje($p->fecha_checkin,$p->fecha_checkout);?> noches</strong></p>

						<!-- TEXTO MOBILE -->
						<p class="hidden-md hidden-lg"><strong><?=dias_viaje($p->fecha_inicio,$p->fecha_fin);?> días<br /><?=noches_viaje($p->fecha_checkin,$p->fecha_checkout);?> noches</strong></p>
					</div>

					<div class="disponibilidad <?=!$p->cupo_disponible?'completo':'';?>">
						<p class="hidden-xs hidden-sm"><strong>Disponibilidad</strong></p>

						<? if($p->cupo_disponible): // && $p->disponibles?>
							<!-- TEXTO PARA DESKTOP -->
							<p class="hidden-xs hidden-sm"><span><strong><?=$p->cupo_disponible;?></strong> de <?=$p->cupo_total;?></span> lugares<p>

							<!-- TEXTO PARA MOBILE -->
							<p class="hidden-md hidden-lg"><span><strong><?=$p->cupo_disponible;?></strong>/<?=$p->cupo_total;?></span> Lugares<p>
						
						<? else: ?>
						
							<p><span>Completo</span></p>
						
						<? endif; ?>
					</div>

					<? if($p->cupo_disponible): //disponibles?>
					<div class="precio_final bg">
						<p class="precio_final"><span class="price_title">Por persona desde</span> <?=$p->disponibles?precio($p->precio,$p->precio_usd):precio($p->combinaciones_precio,$p->combinaciones_precio_usd);?> <span><span class="imp_price">+ <?=$p->disponibles?precio_impuestos_clean($p->impuestos,false,false):precio_impuestos_clean($p->combinaciones_impuestos,false,false);?> imp.</span></span></p>
						
					</div>
					<? else: ?>
					<div class="precio_final bg prox">
						<p><span>PROXIMAMENTE</span></p>
					</div>
					<? endif; ?>

					<div class="mas_info bg">

						<!-- TEXTO DESKTOP -->
						<a href="<?=site_url($p->slug);?>" class="hidden-xs hidden-sm">+ Info</a>

						<!-- TEXTO MOBILE -->
						<a href="<?=site_url($p->slug);?>" class="hidden-md hidden-lg">Más Info</a>
					</div>
    
					<div class="div_reserva bg" data-rel="<?=$p->disponibles;?>">
						<? if($p->cupo_disponible): //disponibles?>
						<!-- BOTÓN "RESERVAR" -->
						<a class="button <?=($p->fecha_indefinida || !$p->confirmacion_inmediata)?'solicitar':'';?>" href="<?=site_url($p->slug);?>"><?=($p->fecha_indefinida || !$p->confirmacion_inmediata)?'SOLICITAR RESERVA':'RESERVAR';?></a>
						<? else: ?>
						<a class="button espera" href="<?=site_url($p->slug);?>">LISTA DE ESPERA</a>
						<? endif; ?>
					</div>

				</div>

				<!-- -->
				<? endforeach; ?>
				
			</div>
			<? endif; ?>
		<? endforeach; ?>
		

		<? if(count($caracteristicas)): ?>
			<!-- OPCIONES PAQUETES DESKTOP -->

			<div class="opciones_paquete hidden-md hidden-lg">

				<p><strong>Según el paquete que elijas, las opciones incluyen:</strong></p>

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

			<!-- FIN OPCIONES PAQUETES DESKTOP -->
		<? endif; ?>


			<? if(count($otros_destinos)>0): ?>
			<div class="otros_destinos">
				<h3>Otros destinos para ti</h3>

				<div class="row">

					<? foreach($otros_destinos as $d): 
						echo contenido($d,'col-xs-12 col-sm-6 col-md-4');
					endforeach; ?>
					
				</div>
			</div>
			<? endif; ?>
			
		</div>


		<?=$medios_de_pago;?>

	</div>

	<!-- FIN CUERPO -->

<?=$footer;?>