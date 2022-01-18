

			<article class="select-section">
			  <h2>ALOJAMIENTO</h2>
			  <div class="clearfix select-section__groups">
				<div class="interna-de-paquete__form">
				  <div id="select-alojamiento" class="select-section__group">
					<p class="select-section__group__label">Elige tu alojamiento</p>
					<div class=" select-section__dropdown dropdown">
					  <select class="dropdown__select" name="alojamiento" id="alojamiento">
					  	<? foreach($alojamientos as $a): ?>
							<option class="dropdown__select__option" value="<?=$a->id;?>" <?=(count($alojamientos)==1 || @$combinacion->alojamiento_id == $a->id)?'selected':'';?>><?=$a->nombre;?></option>
						<? endforeach; ?>
					  </select>
					</div>
				  </div>
				  <div id="select-habitacion" class="select-section__group">
					<p class="select-section__group__label">Tipo de habitación</p>
					<div class=" select-section__dropdown dropdown">
					  <select class="dropdown__select" name="habitacion" id="habitacion">
						<? foreach($tipos_habitacion as $p): ?>
							<option class="dropdown__select__option" value="<?=$p->id;?>" <?=(@$combinacion->habitacion_id == $p->id && @$filtros['habitacion'] != '0')?'selected':'';?>><?=($p->nombre);?></option>
						<? endforeach; ?>
					  </select>
					</div>
				  </div>
				  <div id="select-pension" class="select-section__group">
					<p class="select-section__group__label">Pensión</p>
					<div class=" select-section__dropdown dropdown">
					  <select class="dropdown__select" name="pension" id="pension">
						<? foreach($regimenes as $r): ?>
							<option class="dropdown__select__option" value="<?=$r->id;?>" <?=(count($regimenes)==1 || @$combinacion->paquete_regimen_id == $r->id)?'selected':'';?>><?=($r->regimen);?></option>
						<? endforeach; ?>
					  </select>
					</div>
				  </div>

				</div>

				<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || $habitacion_sin_cupo): ?>
					<!-- MENSAJE -->
					<div class="alert">
						<p><strong>El cupo está completo</strong>, podés elegir otra opción o anotarte en <strong>LISTA DE ESPERA</strong> y te avisaremos por mail cuando haya un lugar disponible.</p>
					</div>
					<!-- FIN MENSAJE -->
			 	<? endif; ?>
				
				  <div class="select-section__group__info clearfix">
					  <div>
						<a href="javascript:void(0)" class="mas_info_btn"><span>Más info</span><i class="fas fa-info-circle"></i></a>

						<? foreach($alojamientos as $a): ?>
						<div class="dropdown_info descripcion_hotel descripcion_hotel_<?=$a->id;?>" >
								<p><?=$a->nombre;?> <? if($a->link): ?>(<a href="<?=$a->link;?>" target="_blank">Web del Hotel</a>)<? endif; ?>
								</p>
								<p><?=$a->descripcion;?></p>

								<? if(count($a->servicios)>0): ?>
									<? $size_s=0;
									foreach($a->servicios as $s): $size_s+=1; ?>
										<div class="items-incluidos__item clearfix" style="<?=$size_s%5==0?'clear:both':'';?>">
											<? if($s->icono): ?>
											<div class="items-incluidos__item__icon" style="background-image: url('<?=base_url();?>media/assets/images/icon/features/<?=$s->icono;?>')"></div>
											<? endif; ?>
											<div class="items-incluidos__item__label">
												<span><?=$s->nombre;?></span>
											</div>
										</div>
									<? endforeach; ?>
								<? endif; ?>
						</div>
						<? endforeach;?>
					  </div>
					</div>
					
			  </div>
			</article>