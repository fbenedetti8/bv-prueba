					<h3>Alojamiento</h3>

					<div class="row">
						<label class="hotel col-3 large">
							<span>Elige tu alojamiento</span>
							
							<select class="selectpicker" name="alojamiento" id="alojamiento">
								<? foreach($alojamientos as $a): ?>
								<option value="<?=$a->id;?>" <?=(count($alojamientos)==1 || @$combinacion->alojamiento_id == $a->id)?'selected':'';?>><?=$a->nombre;?></option>
								<? endforeach; ?>
							</select>
						</label>

						<label class="habitacion col-3 small">
							<span>Tipo de habitación</span>
							
							<select class="selectpicker" data-title="Tipo de habitación" name="habitacion" id="habitacion">
								<? if (false && $paquete->grupal): ?>
								<option value="0" <?=(@$filtros['habitacion'] == '0')?'selected':'';?>>Compartida</option>
								<? endif; ?>
								<? foreach($tipos_habitacion as $p): ?>
								<option value="<?=$p->id;?>" <?=(@$combinacion->habitacion_id == $p->id && @$filtros['habitacion'] != '0')?'selected':'';?>><?=utf8_decode($p->nombre);?></option>
								<? endforeach; ?>
							</select>
						</label>

						<label class="pension col-3 large">
							<span>Pensión</span>
							
							<select class="selectpicker" data-title="Pensión" name="pension" id="pension">
								<? foreach($regimenes as $r): ?>
								<option value="<?=$r->id;?>" <?=(count($regimenes)==1 || @$combinacion->paquete_regimen_id == $r->id)?'selected':'';?>><?=utf8_decode($r->regimen);?></option>
								<? endforeach; ?>
							</select>
						</label>
					</div>

					
					<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || $habitacion_sin_cupo): ?>
					<!-- MENSAJE -->
					<div class="alert">
						<p><strong>El cupo está completo</strong>, podés elegir otra opción o anotarte en <strong>LISTA DE ESPERA</strong> y te avisaremos por mail cuando haya un lugar disponible.</p>
					</div>
					<!-- FIN MENSAJE -->
					<? endif; ?>
					
					<? foreach($alojamientos as $a): ?>
					<div class="descripcion_hotel descripcion_hotel_<?=$a->id;?>" style="display:<?=$a->id==@$combinacion->alojamiento_id?'block':'none';?>">
						<div class="hotel_info">
							<p class="titulo"><strong><?=$a->nombre;?></strong> <? if($a->link): ?>(<a href="<?=$a->link;?>" target="_blank">Web del Hotel</a>)<? endif; ?></p>

							<p><?=$a->introduccion;?> <a href="javascript:void(0)" class="btn_info">+ INFO</a></p>
						</div>

						<div class="mas_info">

							<p><?=$a->descripcion;?></p>

							<? if(count($a->servicios)>0): ?>
							<p><strong>Servicios</strong></p>

							<!--
								ICONOS:
									icon-wifi			 -> WiFi
									icon-toilet-paper	 -> Baño Privado
									icon-swimming-pool	 -> Pileta
									icon-towel			 -> Ropa de cama
									icon-safe-box		 -> Caja de seguridad
									icon-placeholder	 -> Buena ubicación
									icon-stationary-bike -> Gimnasio
									icon-sofa			 -> Sala de juegos / SUM
									icon-park			 -> Parque
									icon-palm-trees		 -> Salida a la playa
							-->
							<? foreach($a->servicios as $s): ?>
							<div class="item_servicio <?=$s->clase;?>">
								<span class="icon-<?=$s->clase;?>"></span>
								<span><?=$s->nombre;?></span>
							</div>
							<? endforeach; ?>
							
							<? endif; ?>
						</div>

					</div>
					<? endforeach; ?>