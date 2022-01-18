					<h3>Seleccioná tu salida</h3>

					<div class="row">
						<label class="col-3 small">
							<span>Cantidad de pasajeros</span>
							
							<select class="selectpicker" name="pasajeros" id="pasajeros">
								<? foreach ($cantidad_pasajeros as $p): ?>
									<option value="<?=$p;?>" <?=(@$pax_elegidos == $p)?'selected':'';?>><?=$p.' pasajero'.($p==1 ? '' : 's');?></option>
								<? endforeach; ?>
							</select>
							<!--
							<select class="selectpicker" name="pasajeros" id="pasajeros">
								<? //si es un paquete grupal y filtré por habitacion compartido, le cargo cantidad de pasajeros de 1 a 10
								if($paquete->grupal): // && $paquete->grupal && $compartida?>
									<? for($i=1;$i<=10;$i++): ?>
									<option value="<?=$i;?>" <?=(@$pax_elegidos == $i)?'selected':'';?>><?=$i;?> pasajero<?=$i==1?'':'s';?></option>
									<? endfor; ?>
								<? else: ?>
									<? foreach($pasajeros as $p): ?>
									<option value="<?=$p->pax;?>" <?=(count($pasajeros)==1 || @$combinacion->pax == $p->pax)?'selected':'';?>><?=$p->pax;?> pasajero<?=$p->pax==1?'':'s';?></option>
									<? endforeach; ?>
								<? endif; ?>
							</select>
							-->
						</label>

						<label class="salida col-3 large">
							<span>Lugar de salida</span>

							<!--
								Si tiene HTML dentro del <OPTION> hay que copiar el contenido dentro de un atributo  data-content""  para que se vea en el botón.
							-->
							
							<select class="selectpicker" data-title="Lugar de salida" name="lugar_salida" id="lugar_salida">
								<? foreach($lugares_salida as $l): ?>
								<option value="<?=$l->id;?>" <?=(count($lugares_salida)==1 || @$combinacion->lugar_id == $l->id)?'selected':'';?> data-content="<strong><?=$l->lugar;?></strong><? if($l->hora_min_full && $l->hora_max_full): ?>, <?=$l->hora_min_full;?>hs<?=$l->hora_max_full!=$l->hora_min_full?(' - '.$l->hora_max_full.'hs'):'';?><? endif;?>"><strong><?=$l->lugar;?></strong><? if($l->hora_min_full && $l->hora_max_full): ?>, <?=$l->hora_min_full;?>hs<?=$l->hora_max_full!=$l->hora_min_full?(' - '.$l->hora_max_full.'hs'):'';?><? endif; ?></option>
								<? endforeach; ?>
							</select>
						</label>

						<label class="col-3 large">
							<span><?=dias_viaje($paquete->fecha_indefinida?$paquete->fecha_checkin:$paquete->fecha_inicio,$paquete->fecha_indefinida?$paquete->fecha_checkout:$paquete->fecha_fin);?> días - <?=noches_viaje($paquete->fecha_checkin,$paquete->fecha_checkout)?> noches</span>

							<? if($paquete->fecha_indefinida && $paquete->mostrar_calendario): ?>
									
								<!-- CALENDARIO -->
								<input type="text" class="datepicker" placeholder="Elegí la fecha de salida" name="fecha" id="fecha" value="<?=(isset($filtros['fecha']) && $filtros['fecha'])?$filtros['fecha']:'';?>"/>
								
							<? else: ?>
								<? if(count($fechas_disponibles)==1): ?>
									<? 
									$f_inicio = ($fechas_disponibles[0]->fecha_in < @$combinacion->fecha_inicio) ? $fechas_disponibles[0]->fecha_in : @$combinacion->fecha_inicio; 
									$f_fin = ($fechas_disponibles[0]->fecha_out > @$combinacion->fecha_fin) ? $fechas_disponibles[0]->fecha_out : @$combinacion->fecha_fin; 
									$f_inicio = date('d/m/Y',strtotime($f_inicio));
									$f_fin = date('d/m/Y',strtotime($f_fin));
									?>
									
									<!-- CAMPO DESHABILITADO -->
									<input type="text" value="<?=fecha_completa($f_inicio,$f_fin);?>" disabled />
									<input type="hidden" name="fecha_id" id="fecha_id" value="<?=formato_fecha($f_inicio,true).'|'.formato_fecha($f_fin,true);?>" />
							
									<!-- la del alojamiento -->
									<input type="hidden" name="fecha_aloj_id" id="fecha_aloj_id" value="<?=$fechas_disponibles[0]->fecha_in.'|'.$fechas_disponibles[0]->fecha_out;?>" />
								<? else: ?>
							
									<!-- DESPLEGABLE: -->
									<select class="selectpicker" data-title="<?=dias_viaje($paquete->fecha_indefinida?$paquete->fecha_checkin:$paquete->fecha_inicio,$paquete->fecha_indefinida?$paquete->fecha_checkout:$paquete->fecha_fin);?> días - <?=noches_viaje($paquete->fecha_checkin,$paquete->fecha_checkout)?> noches" name="fecha_id" id="fecha_id">
										<? foreach($fechas_disponibles as $f): ?>
										<option value="<?=$f->fecha_in.'|'.$f->fecha_out;?>" <?=(count($fechas_disponibles)==1 || @$combinacion->fecha_checkin.'|'.@$combinacion->fecha_checkout == $f->fecha_in.'|'.$f->fecha_out)?'selected':'';?>><?=fecha_completa($f->fecha_checkin,$f->fecha_checkout);?></option>
										<? endforeach; ?>
									</select>
									
								<? endif; ?>
								
							<? endif; ?>							
						</label>
					</div>