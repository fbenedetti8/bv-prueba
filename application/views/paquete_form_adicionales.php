
<? 					
if (count($adicionales)):
	$numpax = ($paquete->grupal) ? @$pax_elegidos : $combinacion->pax;
	
	$hay_adicionales = FALSE;
	foreach($adicionales as $a) {
		if (($a->cantidad-$a->usados) >= $numpax) {
			$hay_adicionales = TRUE;
			break;
		}
	}

	//cupo personalizado
	if($paquete->cupo_paquete_personalizado){ 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
	}

	if(date('Y-m-d') > $paquete->fecha_inicio){
		$paquete->cupo_disponible = 0;	
	}
	
	if ($hay_adicionales):
	?>

		<article class="section-excursiones">
		<div style="display:none;">
		<? pre($paquete); ?>
		</div>
		
			<div class="modulo-excursiones">
			  <div class="modulo-excursiones_title">ADICIONALES</div>
			  <div class="modulo-excursiones_checklist selectable">
			  	<? foreach($adicionales as $a): ?>
					<?
					if (($a->cantidad-$a->usados) >= $numpax):
						//solo voy a mostrar el adicional si la cantidad de restantes es mayor que la cant de pasajeros elegida
						$obligar = false; 
						//defino cuando voy a obligar a contratar adicional:
						//si el adicional es obligatorio y si ademas el cupo del paqete es <= a los adicionales restantes
						if($a->obligatorio && $paquete->cupo_disponible <= ($a->cantidad-$a->usados)):
							$obligar = true; 
						endif;
					?>
						<div data-restan="<?=$a->cantidad-$a->usados;?>" class="adicional <?=$a->transporte_fecha_id?('adicional_t adicional_'.$a->transporte_fecha_id):'';?>">
								<input type="checkbox" id="adicionales_<?=$a->id;?>" name="adicionales[<?=$a->id;?>]" <?=$obligar?'checked readonly onclick="javascript:return false;"':'';?> class="lnkAdicional" value="<?=$a->v_total;?>" data-valor="<?=$a->v_total;?>"><label for="adicionales_<?=$a->id;?>"><?=$a->adicional;?> <span>(disponibles <?=$a->cantidad-$a->usados;?>/<?=$a->cantidad;?>)</label> <?=$paquete->precio_usd?'USD':'ARS';?> <?=$a->v_total;?> p/persona</span>
						</div>
					<? endif;
				endforeach; ?>
			  </div>
			</div>
	 	 </article>

<? 
	endif; 
endif; 
?>