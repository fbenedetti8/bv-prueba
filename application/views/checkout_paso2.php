
	<? //si quiere mostrar cupo personalizado, piso los datos reales
	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible>=$orden->pasajeros;
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; ?>

	<!-- PASO 2 -->

				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading <?=($orden->completo_paso2)?'completo':'';?>">
							<h3 class="panel-title">
								<a class="<?=($orden->paso_actual==2)?'':'collapsed';?>" role="button" data-toggle="collapse" href="#paso_2">
									<span class="num_paso">2</span>
									<span>
										<!-- Si hay un <span> fuera del <strong> levanta los estilos como si fuera un link -->
										<? if($orden->completo_paso2): ?>
										<strong class="head-facturacion">Persona a quien se le emitirá la factura: <?=@$facturacion->f_nombre.' '.@$facturacion->f_apellido;?></strong> <span>Editar</span>
										<? else: ?>
										<strong class="head-facturacion">Completa los datos de facturación <span>(persona a quien se le emitirá la factura)</span></strong>
										<? endif; ?>
									</span>
								</a>
							</h3>
						</div>

						<? 
						if($orden->completo_paso1): ?>
						<!-- La clase "in" junto a la clase "collpase" mantiene el desplegable abierto -->

						<div id="paso_2" class="panel-collapse collapse <?=($orden->paso_actual==2)?'in':'';?>">
							<form class="panel-body checkout_paso" id="frmPaso2">
								
								
								<div class="row form_block">

									<div class="check_content full_width">
										<label class="full_width">
											<input type="checkbox" id="reusar" />
												<span>Usar los datos del Pasajero 1 para la facturación
												
												<span class="btn_tooltip">
													<span class="icon-question-mark"></span>
													
													<div class="tooltip">
														<div></div>
														<span>Si vas a pagar con la tarjeta de crédito de una persona diferente completa los datos.</span>
													</div>
												</span>
											</span>
										</label>
									</div>


									<label class="required">
										<span>Nombre <span class="obligatorio">(Obligatorio)</span></span>

										<input type="text" name="f_nombre" value="<?=@$facturacion->f_nombre;?>" class="onlytext" />

										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Apellido <span class="obligatorio">(Obligatorio)</span></span>

										<input type="text" name="f_apellido" value="<?=@$facturacion->f_apellido;?>" class="onlytext"/>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									

									<label class="required">
										<span>Cuit / Cuil <span class="obligatorio">(Obligatorio)</span></span>
										
										<div class="cuil campo_cuil">
											<input type="text" name="f_cuit_prefijo" maxlength="2" class="onlynum" value="<?=@$facturacion->f_cuit_prefijo;?>" /><span>-</span>
											<input type="text" name="f_cuit_numero" maxlength="8" class="onlynum" value="<?=@$facturacion->f_cuit_numero;?>" /><span>-</span>
											<input type="text" name="f_cuit_sufijo" maxlength="1" class="onlynum" value="<?=@$facturacion->f_cuit_sufijo;?>" />
										</div>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>

									<label class="required">
										<span>Nacionalidad <span class="obligatorio">(Obligatorio)</span></span>

										<select class="selectric selectpicker" data-title="Elige un país" name="f_nacionalidad_id">
											<option value="">Seleccionar</option>
											<? foreach($paises as $p): ?>
											<option value="<?=$p->id;?>" <?=$p->id==@$facturacion->f_nacionalidad_id?'selected':'';?>><?=$p->nombre;?></option>
											<? endforeach; ?>
										</select>
										
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>País de residencia <span class="obligatorio">(Obligatorio)</span></span>

										<? //valor defecto 
										$facturacion->f_residencia_id = (@$facturacion->f_residencia_id) ? $facturacion->f_residencia_id : 1;
										?>
										<select class="selectric selectpicker" data-title="Elige un país" name="f_residencia_id">
											<option value="">Seleccionar</option>
											<? foreach($paises as $p): ?>
											<option value="<?=$p->id;?>" <?=$p->id==@$facturacion->f_residencia_id?'selected':'';?>><?=$p->nombre;?></option>
											<? endforeach; ?>
										</select>
										
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Provincia de residencia <span class="obligatorio">(Obligatorio)</span></span>
										
										<input type="text" name="f_provincia" value="<?=@$facturacion->f_provincia;?>" />
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Ciudad de residencia <span class="obligatorio">(Obligatorio)</span></span>
										
										<input type="text" name="f_ciudad" value="<?=@$facturacion->f_ciudad;?>" />
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Domicilio - Calle <span class="obligatorio">(Obligatorio)</span></span>
										
										<input type="text" name="f_domicilio" value="<?=@$facturacion->f_domicilio;?>" />
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<div class="required domicilio">
										<label>
											<span>Número <span class="obligatorio">(Obligatorio)</span></span>

											<input type="text" name="f_numero" value="<?=@$facturacion->f_numero;?>" />
										</label>
										
										<label>
											<span>Depto</span>

											<input type="text" name="f_depto" value="<?=@$facturacion->f_depto;?>" />
										</label>
										
										<span class="msg">CAMPO OBLIGATORIO</span>
									</div>
									
									
									<label class="required">
										<span>Código Postal <span class="obligatorio">(Obligatorio)</span></span>

										<input type="text" name="f_cp" value="<?=@$facturacion->f_cp;?>" />	
										<span class="msg">CAMPO OBLIGATORIO</span>										
									</label>

								</div>

								<div class="submit required">
															
									<? $txt_terms = "";
									if($orden->pasajeros == 1): ?>
										<!-- FORM TÉRMINOS datos -->
										<div class="form_terminos">
											<div class="text">
												<div>
													<p style="text-align: left;"><?=$this->config->item('texto_abogado_1pax');?></p>
												</div>
											</div>
										</div>
										<!-- FIN FORM TÉRMINOS datos -->
									<? $txt_terms = " y que los datos expuestos arriba son correctos";
									endif; ?>

									<div class="form_terminos">
										<div class="check_content col-md-8" style="text-align: left;">
											<label>
												<input type="checkbox" name="terminos" value="1" <?=($orden->completo_paso2)?'checked':'';?> />
												<span>Acepto <a href="<?=site_url('home/terminos_y_condiciones')?>" target="_blank" >Términos y Condiciones</a> del viaje<?=$txt_terms;?>.</span>
											</label>
										</div>
									</div>

									<input class="submit_button btn btn-pink" type="submit" value="Guardar datos" />


									<!-- ERROR -->
									<p class="msg error">Para confirmar todos los datos debes aceptar términos y condiciones</p>
									<!-- FIN ERROR -->
								</div>
								
								
								<input type="hidden" name="numero_paso" id="numero_paso" value="2"/>
								<input type="hidden" name="orden_id" value="<?=$orden->id;?>"/>
								<input type="hidden" name="fact_id" value="<?=@$facturacion->id;?>"/>
								
								

			
							</form>
						</div>
						<? endif; ?>
					</div>
				</div>

				<!-- FIN PASO 2 -->