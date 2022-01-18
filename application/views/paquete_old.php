<?=$header;?>

<? if($this->detector->isMobile()): ?>
<style>
.fancybox-prev span, .fancybox-next span { visibility:visible !important; }
</style>
<? endif; ?>

	<?=$paquete->script_conversion;?>

	<!-- CUERPO -->

	<? //si quiere mostrar cupo personalizado, piso los datos reales
	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible;
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; 

	if(date('Y-m-d') > $paquete->fecha_inicio):
		$paquete->cupo_disponible = 0;	
	endif;
	?>
	
	<div id="main">

		<? if($this->detector->isMobile() && file_exists('./uploads/destinos/'.$paquete->destino_id.'/'.$paquete->imagen_mobile)):
			$img = base_url().'uploads/destinos/'.$paquete->destino_id.'/'.$paquete->imagen_mobile;
		else:
			$img = base_url().'uploads/destinos/'.$paquete->destino_id.'/'.$paquete->imagen;
		endif; ?>

		<div class="banner" style="background-image: url('<?=$img;?>')">
			<div class="container">

				<div class="info">
					<div class="detalles">

						<!-- Class "paquete" -->
						<!-- Class "grupal" -->

						<div class="tag <?=$paquete->grupal?'grupal':'paquete';?>">

							<!-- clase "icon-group" para grupales -->
							<!-- clase "icon-suitcase" para paquetes -->
							<span class="<?=$paquete->grupal?'icon-group':'icon-suitcase';?>"></span>
							<span><?=$paquete->grupal?'Grupal':'Paquete';?></span>

							<div class="btn_tooltip">
								<span class="icon-question-mark"></span>

								<div class="tooltip">
									<div></div>
									<? if($paquete->grupal): ?>
										<span><?=$this->config->item('viaje_grupal');?></span>
									<? else: ?>
										<span><?=$this->config->item('viaje_no_grupal');?></span>
									<? endif; ?>
								</div>
							</div>
						</div>

						<h1 class="title"><strong><?=$paquete->nombre;?></strong></h1>

						<? if($paquete->paquete_estacional): ?>
						<h2 class="descripcion"><?=$paquete->paquete_estacional;?></h2>
						<? else:
							if($paquete->estacional): ?>
							<h2 class="descripcion"><?=$paquete->estacional;?></h2>
							<? endif; 
						endif; ?>
						
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

						<a class="messenger_button" href="https://api.whatsapp.com/send?phone=5491141745025" target="_blank">
							<span>Whatsapp</span>
							<span class="icon-whatsapp"></span>
						</a>
					</div>
				</div>

			</div>
		</div>

		<? if (isset($_GET['vencida'])): ?>
		<div class="container" style="padding-right:0; margin-top:20px">
			<div class="alert alert-danger" style="margin:0">
				<strong>Lo sentimos pero la orden de reserva está vencida. Es necesario que vuelvas a realizarla para poder asegurar tu lugar en el viaje.</strong>
			</div>
		</div>
		<? endif; ?>

		<div class="container">
			<div class="share_buttons hidden-md hidden-lg">
				<span>Comparte</span>

				<a href="#" class="facebook lnkFB" rel="<?=current_url();?>">
					<span class="icon-facebook"></span>
					<span class="sr-only">Facebook</span>
				</a>

				<a href="#" class="Twitter lnkTW" rel="<?=current_url();?>" data-text="<?=$paquete->nombre;?>">
					<span class="icon-twitter"></span>
					<span class="sr-only">Twitter</span>
				</a>

				<a href="whatsapp://send?text=<?=$paquete->nombre.' '.current_url();?>" class="whatsapp">
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


			<div class="features hidden-md hidden-lg">
				<p><span class="<?=$paquete->clase_transporte;?>"></span> <span><?=$paquete->descripcion_transporte;?></span></p>
				<? if(!$paquete->cupo_disponible): ?>
				<p><span class="icon-suitcase"></span> <span>Disponibilidad:</span> <span>Completo</span></p>
				<? else: ?>
				<p><span class="icon-suitcase"></span> <span><?=$paquete->cupo_disponible;?>/<?=$paquete->cupo_total;?></span> <span>lugares disponibles</span></p>
				<? endif; ?>
			</div>

		</div>



		<div class="aside_content container">
			<form id="fPaquete" class="contenido">
				<input type="hidden" id="paquete_id" name="paquete_id" value="<?=$paquete->id;?>"/>
				<input type="hidden" id="field" name="field" value=""/>
				<input type="hidden" id="detalle_visible" name="detalle_visible" value="0" />
				<input type="hidden" id="aside_mobile" name="aside_mobile" value="0" />
				<input type="hidden" id="zocalo_mobile" name="zocalo_mobile" value="0" />
				
				<div class="features hidden-xs hidden-sm">
					<p><span class="<?=$paquete->clase_transporte;?>"></span> <span><?=$paquete->descripcion_transporte;?></span></p>
					<? if(!$paquete->cupo_disponible): ?>
					<p><span class="icon-suitcase"></span> <span>Disponibilidad:</span> <span>Completo</span></p>
					<? else: ?>
					<p><span class="icon-suitcase"></span> <span><?=$paquete->cupo_disponible;?>/<?=$paquete->cupo_total;?> lugares disponibles</span></p>
					<? endif; ?>
				</div>

				<hr class="hidden-xs hidden-sm" />

				<? if(count($caracteristicas)>0): ?>
				<!-- OPCIONES PAQUETE DESKTOP -->

				<div class="opciones_paquete hidden-xs hidden-sm">

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

				<!-- FIN OPCIONES PAQUETE DESKTOP -->
				<? endif; ?>
				
				<p class="intro">
					<?=str_replace(array('<div>','</div>'),array('',''),$paquete->descripcion);?> <? if($paquete->itinerario && file_exists('./uploads/paquetes/'.$paquete->id.'/'.$paquete->itinerario)): ?><a href="<?=base_url().'uploads/paquetes/'.$paquete->id.'/'.$paquete->itinerario;?>" target="_blank"><strong>Descargar Itineario</strong></a><? endif; ?>
				</p>


				<div id="form_salidas">
					<?=$form_salidas;?>
				</div>


				<div class="alojamiento" id="form_alojamientos">
					<?=$form_alojamientos;?>
				</div>


				<div id="form_adicionales">
					<?=$form_adicionales;?>
				</div>
				

				<div class="transporte" id="form_transportes">
					<?=$form_transportes;?>
				</div>


				<? if(count($excursiones)): ?>
				<div class="excursiones">

					<h3>Excursiones</h3>

					<div>
						<ul>
							<? $n=0; foreach($excursiones as $e): $n++;
							if($n%2==1): ?>
							<li><?=$e->nombre;?></li>
							<? endif;
							endforeach; ?>
						</ul>

						<ul>
							<? $n=0; foreach($excursiones as $e): $n++;
							if($n%2==0): ?>
							<li><?=$e->nombre;?></li>
							<? endif;
							endforeach; ?>
						</ul>
					</div>

				</div>
				<? endif; ?>

				
				<? if($paquete->documentacion_requerida): ?>
				<div>

					<h4>Documentación requerida</h4>

					<p><?=str_replace(array('<div>','</div>'),array('',''),$paquete->documentacion_requerida);?></p>

				</div>
				<? endif; ?>

				<? if(count($medios)>0): ?>
				<div class="medios_de_pago">
					<h4>Medios de pago</h4>

					<? foreach($medios as $m): ?>
					<p><strong><?=$m->nombre;?></strong></p>
					<p><?=$m->descripcion;?></p>
					<? endforeach; ?>
				</div>
				<? endif; ?>

				<? if(count($promociones)>0): ?>
				<div>
					<h4>Promociones</h4>

					<? foreach($promociones as $m): ?>
					<p><strong><?=$m->nombre;?></strong></p>
					<p><?=$m->descripcion;?></p>
					<? endforeach; ?>
				</div>
				<? endif; ?>

				<? if($paquete->operador): ?>
				<div>
					<h4>Operador</h4>

					<p><strong><?=$paquete->operador;?></strong> - <?=$paquete->legajo;?></p>
				</div>
				<? endif; ?>
				
				<? if($paquete->aclaraciones): ?>
				<div>
					<h4>Información Adicional</h4>
					
					<p><?=$paquete->aclaraciones;?></p>
				</div>
				<? endif; ?>

				
				<? if($paquete->calculador_cuotas || $paquete->pago_oficina): ?>
				<!-- CALCULADOR MOBILE -->

				<div class="calculador mobile hidden-md hidden-lg">
					<h3>Calculador de cuotas</h3>

					<div>
						<label>
							<span>Forma de pago</span>
							<select class="selectpicker lnkMedioPago" data-title="Seleccionar" name="forma_pago">
								<? if($paquete->grupal && $paquete->pago_oficina): ?>
								<option value="backend">En nuestras oficinas</option>
								<? endif; ?>
								<? if($paquete->calculador_cuotas): ?>
								<option value="mercadopago">MercadoPago</option>
								<? endif; ?>
							</select>
						</label>

						<label class="tarjeta">
							<span>Tarjeta / Otros</span>
							<select class="selectpicker lnkMetodoPago" data-title="Seleccionar" id="metodo_pago" name="metodo_pago">
								<? foreach($metodos_pago as $b){ ?>
									<optgroup label="<?=$b->banco;?>">
									<? foreach($b->tarjetas as $t){ ?>
										<option data-banco="<?=$b->banco;?>" value="<?=$t->tarjeta;?>"><?=$t->tarjeta;?></option>
									<? } ?>
									</optgroup>
								<? } ?>			
							</select>
						</label>

						<label class="cuotas">
							<span>Cuotas</span>
							<select class="selectpicker lnkCuotas" data-title="- -" id="cuotas" name="cuotas">
								<option>- -</option>
							</select>
						</label>
					</div>

					<p>Valor de cuota <strong id="valor_cuota">ARS 0</strong></p>
					<span>Intereses, gastos e impuestos, incluídos.</span>
					<span>Precio financiado: <strong id="precio-total">ARS 0</strong></span>

					<span>CFT: <strong id="cft">0%</strong> - TEA: <strong id="tea">0%</strong></span>
				</div>

				<!-- FIN CALCULADOR MOBILE -->
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
				<!-- OPCIONES PAQUETE MOBILE -->

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
				<? endif; ?>


				<div id="detalle_calculador">
					<div class="detalle_compra">
						<?=@$detalle_calculador;?>
					</div>
				</div>

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
		<? else: ?>
		<br>
		<? endif; ?>
		
		<?=$medios_de_pago;?>

	</div>

	<!-- FIN CUERPO -->
	
<?=$footer;?>

	<? if(false && $paquete->grupal): ?>
		<script type="text/javascript">
		$(document).ready(function(){
			$('#habitacion').selectpicker('val','0');
			obtener_combinacion('habitacion');
		});
		</script>
	<? endif; ?>
	