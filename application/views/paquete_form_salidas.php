			<article class="select-section">
			  <h2>CONFIGURA TU EXPERIENCIA</h2>
			  <div class="clearfix select-section__groups">
				<form action="#" method="POST" class="interna-de-paquete__form">
				  <div id="select-pasajeros" class="select-section__group">
					<p class="select-section__group__label">Cantidad de pasajeros</p>
					<div class=" select-section__dropdown dropdown">
					  <select class="dropdown__select" name="pasajeros" id="pasajeros">
					  	<? foreach ($cantidad_pasajeros as $p): ?>
							<option  class="dropdown__select__option" value="<?=$p;?>" <?=(@$pax_elegidos == $p)?'selected':'';?>><?=$p.' pasajero'.($p==1 ? '' : 's');?></option>
						<? endforeach; ?>
					  </select>
					</div>
				  </div>

				  <div id="select-lugar" class="select-section__group">
					<p class="select-section__group__label">Lugar de inicio</p>
					<div class=" select-section__dropdown dropdown">
					  <select class="dropdown__select" name="lugar_salida" id="lugar_salida">
					  	<? foreach($lugares_salida as $l): ?>
							<option class="dropdown__select__option" value="<?=$l->id;?>" <?=(count($lugares_salida)==1 || @$combinacion->lugar_id == $l->id)?'selected':'';?> data-content="<strong><?=$l->lugar;?></strong><? if($l->hora_min_full && $l->hora_max_full): ?>, <?=$l->hora_min_full;?>hs<?=$l->hora_max_full!=$l->hora_min_full?(' - '.$l->hora_max_full.'hs'):'';?><? endif;?>"><strong><?=$l->lugar;?></strong><? if($l->hora_min_full && $l->hora_max_full): ?>, <?=$l->hora_min_full;?>hs<?=$l->hora_max_full!=$l->hora_min_full?(' - '.$l->hora_max_full.'hs'):'';?><? endif; ?></option>
						<? endforeach; ?>
					  </select>
					</div>
				  </div>

				  <div id="select-lapso" class="select-section__group">
					<p class="select-section__group__label"><?=dias_viaje($paquete->fecha_indefinida?$paquete->fecha_checkin:$paquete->fecha_inicio,$paquete->fecha_indefinida?$paquete->fecha_checkout:$paquete->fecha_fin);?> días - <?=noches_viaje($paquete->fecha_checkin,$paquete->fecha_checkout)?> noches</p>

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
							
							<div class=" select-section__dropdown dropdown">
							 <select class="dropdown__select" name="fecha_id" id="fecha_id">
								<? foreach($fechas_disponibles as $f): ?>
									<option class="dropdown__select__option" value="<?=$f->fecha_in.'|'.$f->fecha_out;?>" <?=(count($fechas_disponibles)==1 || @$combinacion->fecha_checkin.'|'.@$combinacion->fecha_checkout == $f->fecha_in.'|'.$f->fecha_out)?'selected':'';?>><?=fecha_completa($f_inicio,$f_fin);?></option>
								<? endforeach; ?>
								
							  </select>
							</div>

							<? if(false): ?>
								<!-- CAMPO DESHABILITADO -->
								<input type="text" value="<?=fecha_completa($f_inicio,$f_fin);?>" disabled />

								<input type="hidden" name="fecha_id" id="fecha_id" value="<?=formato_fecha($f_inicio,true).'|'.formato_fecha($f_fin,true);?>" />
							<? endif; ?>
					
							<!-- la del alojamiento -->
							<input type="hidden" name="fecha_aloj_id" id="fecha_aloj_id" value="<?=$fechas_disponibles[0]->fecha_in.'|'.$fechas_disponibles[0]->fecha_out;?>" />
						<? else: ?>

							<div class=" select-section__dropdown dropdown">
							  <select class="dropdown__select" name="fecha_id" id="fecha_id">
								<? foreach($fechas_disponibles as $f): ?>
									<? 
									$f_inicio = ($f->fecha_in < @$combinacion->fecha_inicio) ? $f->fecha_in : @$combinacion->fecha_inicio; 
									$f_fin = ($f->fecha_out > @$combinacion->fecha_fin) ? $f->fecha_out : @$combinacion->fecha_fin; 
									$f_inicio = date('d/m/Y',strtotime($f_inicio));
									$f_fin = date('d/m/Y',strtotime($f_fin));
									?>
									<option class="dropdown__select__option" value="<?=$f->fecha_in.'|'.$f->fecha_out;?>" <?=(count($fechas_disponibles)==1 || @$combinacion->fecha_checkin.'|'.@$combinacion->fecha_checkout == $f->fecha_in.'|'.$f->fecha_out)?'selected':'';?>><?=fecha_completa($f_inicio,$f_fin);?></option>
								<? endforeach; ?>
								
							  </select>
							</div>
						<? endif; ?>
					<? endif; ?>	
				  </div>
				</form>
			  </div>
			</article>
