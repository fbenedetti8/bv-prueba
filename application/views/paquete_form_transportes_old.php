				<? if(@$combinacion->lugar_id == 4): //si es tour en destino, este bloque viene oculto, solo paso referencia al transporte ?>
					<input type="hidden" name="transporte" id="transporte" value="<?=@$combinacion->fecha_transporte_id;?>">
				<? else: ?>
					<h3>Transporte</h3>

					<p class="hidden">Elegí tu transporte de acuerdo a la cantidad de asientos disponibles.</p>

					<div class="row">
						<label>
							<select class="selectpicker" name="transporte" id="transporte">
								<? foreach($transportes as $t): ?>
								<option value="<?=$t->id;?>" <?=(count($transportes)==1 || @$combinacion->fecha_transporte_id==$t->id)?'selected':'';?>><?=$t->titulo;?></option>
								<? endforeach; ?>
							</select>
						</label>
					</div>

					<? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || $transporte_sin_cupo): ?>
					<!-- MENSAJE -->
					<div class="alert">
						<p><strong>El cupo está completo</strong>, podés elegir otra opción o anotarte en <strong>LISTA DE ESPERA</strong> y te avisaremos por mail cuando haya un lugar disponible.</p>
					</div>
					<!-- FIN MENSAJE -->
					<? endif; ?>
					
					<? $i=0; 
					foreach($transportes as $t): $i++; ?>
					<div class="descripcion_transporte descripcion_transporte_<?=$t->id;?>" style="<?=$i>1?'display:none':'';?>">
						<p><strong><?=$t->nombre;?></strong></p>
						<p><?=str_replace(array('</div>','<div>'),array('',''),$t->descripcion);?></p>
					</div>
					<? endforeach; ?>
				<? endif; ?>