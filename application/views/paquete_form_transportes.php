		<? if(@$combinacion->lugar_id == 4): //si es tour en destino, este bloque viene oculto, solo paso referencia al transporte ?>
			<input type="hidden" name="transporte" id="transporte" value="<?=@$combinacion->fecha_transporte_id;?>">
		<? else: ?>

			<article class="select-section">
			  <h2>TRANSPORTE</h2>

			  <p style="display:none">Elegí tu transporte de acuerdo a la cantidad de asientos disponibles.</p>

			  <div class="clearfix select-section__groups">
				<!-- <form action="#" method="POST" class="interna-de-paquete__form"> -->
				  <div id="select-transporte" class="select-section__group clearfix">
					<div class=" select-section__dropdown dropdown dropdown-float">
					  <select class="dropdown__select" name="transporte" id="transporte">
						<? foreach($transportes as $t): ?>
							<option class="dropdown__select__option" value="<?=$t->id;?>" <?=(count($transportes)==1 || @$combinacion->fecha_transporte_id==$t->id)?'selected':'';?>><?=$t->titulo;?></option>
						<? endforeach; ?>
					  </select>
					</div>

					<? $i=0; 
					foreach($transportes as $t): $i++; ?>
					<p class="select-section__group__text descripcion_transporte descripcion_transporte_<?=$t->id;?>" style="<?=$i>1?'display:none':'';?>"><?=str_replace(array('</div>','<div>'),array('',''),$t->descripcion);?>
					</p>
					<? endforeach; ?>

				  </div>
				<!-- </form> -->
			  </div>
			 
			  <? if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || $transporte_sin_cupo): ?>
					<!-- MENSAJE -->
					<div class="alert">
						<p><strong>El cupo está completo</strong>, podés elegir otra opción o anotarte en <strong>LISTA DE ESPERA</strong> y te avisaremos por mail cuando haya un lugar disponible.</p>
					</div>
					<!-- FIN MENSAJE -->

				<? endif; ?>

			</article>
			
		<? endif; ?>