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

						if ($hay_adicionales):
						?>
							<h3>Adicionales</h3>

							<div class="check_content">
								<? foreach($adicionales as $a): ?>
								<?
								if (($a->cantidad-$a->usados) >= $numpax):
									//solo voy a mostrar el adicional si la cantidad de restantes es mayor que la cant de pasajeros elegida
									$obligar = false; 
									//defino cuando voy a obligar a contratar adicional:
									//si el adicional es obligatorio y si ademas el cupo del paqete es <= a los adicionales restantes
									if($a->obligatorio && $paquete->cupo_disponible <= ($a->cantidad-$a->usados)):
										$obligar = false; 
									endif;
								?>
								<label data-restan="<?=$a->cantidad-$a->usados;?>" class="adicional <?=$a->transporte_fecha_id?('adicional_t adicional_'.$a->transporte_fecha_id):'';?>">
									<input class="lnkAdicional" value="<?=$a->v_total;?>" data-valor="<?=$a->v_total;?>" type="checkbox" name="adicionales[<?=$a->id;?>]" <?=$obligar?'checked readonly onclick="javascript:return false;"':'';?>/> <span><strong><?=$a->adicional;?></strong> <?=$paquete->precio_usd?'USD':'ARS';?> <?=$a->v_total;?></span>
								</label>
								<? endif;
								endforeach; ?>
							</div>
						<? 
						endif; 
					endif; 
					?>