			<? if($orden->pasajeros > 1): ?>
				
				<!-- PASO 3 -->

				<!-- Si van a haber mas acordeones dentro hay que agregar la clase "multiple-accordion" -->

				<div class="panel-group multiple-accordion">
					<div class="panel panel-default">

						<!-- LA CLASE "completo" junto al "panel-heading" pinta de azul el desplegable -->

						<div class="panel-heading <?=($orden->completo_paso3 || (count($incompletos) < ($orden->pasajeros-1)))?'completo':'';?>">
							<h3 class="panel-title">
								<a class="<?=($orden->paso_actual==3)?'':'collapsed';?>" role="button" data-toggle="collapse" href="#paso_3">
									<span class="num_paso">3</span>
									<span>
										<!-- Si hay un <span> fuera del <strong> levanta los estilos como si fuera un link -->
										<? if($orden->completo_paso3): ?>
											<strong class="head-acompa">Completos Pasajero <?=implode(',',$completos);?></strong> <span>Editar</span>
										<? else: ?>
											<? if(count($incompletos) == ($orden->pasajeros-1)): //si TODOS los acompañantes estan incompletos ?>
												<strong class="head-acompa">Completa los datos de los acompañantes (pendiente)</strong>
											<? elseif(count($incompletos) < ($orden->pasajeros-1)): //si hay alguno incompleto (NO TODOS) ?>
												<strong class="head-acompa">Completos Pasajero <?=implode(',',$incompletos);?></strong> <span>Editar</span>
											<? else: ?>
												<strong class="head-acompa">Completa los datos de los acompañantes</strong>
											<? endif; ?>
										<? endif; ?>
									</span>
								</a>
							</h3>
						</div>


						<? if($orden->completo_paso2): ?>
						<!-- La clase "in" junto a la clase "collpase" mantiene el desplegable abierto -->

						<div id="paso_3" class="panel-collapse collapse <?=($orden->paso_actual==3)?'in':'';?>">
							<div class="panel-body">

								<div>
									<span class="help">Puedes completarlos ahora o <a class="link linkfix lnkCompletarLuego" href="#">completarlos luego</a> (te enviaremos un link por mail para hacerlo).</span>
								</div>

								<!-- lo pongo por fuera de cada acompañante individual-->
								<form class="panel-body checkout_paso" id="frmPaso3" style="padding:0;">
								<input type="hidden" name="completa_luego" id="completa_luego" value="0"/>
								<input type="hidden" name="numero_paso" id="numero_paso" value="3"/>
								<input type="hidden" name="saltear_pax" id="saltear_pax" value="0"/>
								<input type="hidden" name="grabar_pax" id="grabar_pax" value="0"/>
								<input type="hidden" name="orden_id" value="<?=$orden->id;?>"/>
								
								<? $i=1;
								foreach($acompanantes as $a): $i++; ?>
								<!-- PASAJERO <?=$i?> -->

								<div class="panel-group">
									<div class="panel panel-default">

										<!-- LA CLASE "completo" junto al "panel-heading" pinta de azul el desplegable -->

										<div class="panel-heading <?=$a->completo?'completo':'';?>">
											<h4 class="panel-title">

												<!-- El HREF debe ser el mismo que el ID del div con clase "panel-collapse" -->

												<a class="collapsed" role="button" data-toggle="collapse" href="#paso_3_<?=$i?>">
													<span>
														<? if($a->completo): ?>
															<strong class="head-pax-<?=$a->id;?>">Pasajero <?=$i?>: <?=$a->nombre.' '.$a->apellido;?></strong> <span>Editar</span>
														<? else: ?>
															<strong class="head-pax-<?=$a->id;?>">Pasajero <?=$i?></strong> <span>Completar</span>
														<? endif; ?>
													</span>
												</a>
											</h4>
										</div>

										<!-- El ID debe ser el mismo que el HREF del ancla con clase "button" -->

										<div id="paso_3_<?=$i?>" class="panel-collapse collapse">
											

												<div class="row data_pax">

													<label>
														<span>Nombre</span>

														<input type="text" name="nombre_<?=@$a->id;?>" value="<?=@$a->nombre;?>" class="onlytext" />

													</label>
													
													
													<label>
														<span>Apellido</span>

														<input type="text" name="apellido_<?=@$a->id;?>" value="<?=@$a->apellido;?>" class="onlytext" />
													</label>
													
													
													<label>
														<span>Fecha de Nacimiento</span>

														<? $fecha = @$a->fecha_nacimiento;
														$fecha = explode('-',$fecha);
														$fecha_nac='';
														 ?>
														
														<!--
														<input type="text" readonly class="datepicker-free fnacimiento onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="nacimiento_<?=@$a->id;?>" value="<?=$fecha_nac;?>"/>
														-->
														
														<div class="fecha fecha_nacimiento">
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
															<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
														</div>
													</label>
													
													
													<label>
														<span>Sexo</span>

														<div class="check_content">
															<label>
																<input type="radio" name="sexo_<?=@$a->id;?>" value="femenino" <?=@$a->sexo=='femenino'?'checked':'';?> /> <span>Femenino</span>
															</label>
															<label>
																<input type="radio" name="sexo_<?=@$a->id;?>" value="masculino" <?=@$a->sexo=='masculino'?'checked':'';?> /> <span>Masculino</span>
															</label>
														</div>
													</label>
													
													
													<label>
														<span>Nacionalidad</span>

														<select class="selectric selectpicker nacionalidad_id" data-title="Elige un país" name="nacionalidad_id_<?=@$a->id;?>">
															<option value="">Seleccionar</option>
															<? foreach($paises as $p): ?>
															<option value="<?=$p->id;?>" <?=$p->id==@$a->nacionalidad_id?'selected':'';?>><?=$p->nombre;?></option>
															<? endforeach; ?>
														</select>
													</label>
													
													
													<label class="data_dni">
														<span>DNI</span>

														<input type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="ej: 34697216" class="onlynum" name="dni_<?=@$a->id;?>" value="<?=@$a->dni;?>"/>
														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>

													
													<label class=" data_pasaporte <?=@$pasaporte_obligatorio?'':'hidden';?>">
														<span>Pasaporte N°</span>

														<input type="text" name="pasaporte_<?=@$a->id;?>" value="<?=@$a->pasaporte;?>" />
														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>

													
													<label class=" data_pasaporte <?=@$pasaporte_obligatorio?'':'hidden';?>">
														<span>País de emisión</span>
									
														
														<select class="selectric selectpicker" data-title="Elige un país" name="pais_emision_id_<?=@$a->id;?>">
															<option value="">Seleccionar</option>
															<? foreach($paises as $p): ?>
															<option value="<?=$p->id;?>" <?=$p->id==@$a->pais_emision_id?'selected':'';?>><?=$p->nombre;?></option>
															<? endforeach; ?>
														</select>
														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>
													
													
													<label class=" data_pasaporte <?=@$pasaporte_obligatorio?'':'hidden';?>">
														<span>Fecha de emisión del pasaporte</span>


														<? $fecha = @$a->fecha_emision;
														$fecha = explode('-',$fecha);
														$fecha_nac='';
														if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
															$fecha_nac = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
														} ?>
														
														<!--<input type="text" readonly class="datepicker-free onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="fecha_emision_<?=@$a->id;?>" value="<?=$fecha_nac;?>"/>
														-->
														
														<div class="fecha fecha_nacimiento">
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
															<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
														</div>
														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>
													
													
													<label class=" data_pasaporte <?=@$pasaporte_obligatorio?'':'hidden';?>">
														<span>Fecha de vencimiento del pasaporte</span>

														<? $fecha = @$a->fecha_vencimiento;
														$fecha = explode('-',$fecha);
														$fecha_nac='';
														if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
															$fecha_nac = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
														} ?>
														
														<!-- <input type="text" readonly class="datepicker-free onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="fecha_vencimiento_<?=@$a->id;?>" value="<?=$fecha_nac;?>"/> -->
										
														
														<div class="fecha fecha_nacimiento">
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
															<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
															<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
														</div>
														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>
													
													
													<label class="required">
														<span>E-mail</span>

														<input type="email" name="email_<?=@$a->id;?>" value="<?=@$a->email;?>" class="email"/>

														<span class="msg">CAMPO OBLIGATORIO</span>
													</label>
													
													
													<div>
														<span>Celular</span>

														<div class="tel">
															<div>
																<span>0</span>
																<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="celular_codigo_<?=@$a->id;?>" value="<?=@$a->celular_codigo;?>" />
															</div>
															
															<div>
																<span>15</span>
																<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="celular_numero_<?=@$a->id;?>" value="<?=@$a->celular_numero;?>" />
															</div>
														</div>
													</div>

												</div>


												<!-- CONTACTO DE EMERGENCIA -->
												<div>

													<label style="margin-bottom:10px;"><span>Contacto de Emergencia <span>(Pasajero <?=$i;?>)</span></span></label>
													

													<div class="row">
														<label>
															<span>Nombre y Apellido</span>

															<input type="text" name="emergencia_nombre_<?=@$a->id;?>" value="<?=@$a->emergencia_nombre;?>" class="onlytext" />
														</label>
														
														
														<div>
															<span>Teléfono</span>

															<div class="tel">
																<div>
																	<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Cód." name="emergencia_telefono_codigo_<?=@$a->id;?>" value="<?=@$a->emergencia_telefono_codigo;?>" >
																</div>
																
																<div>
																	<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Número" name="emergencia_telefono_numero_<?=@$a->id;?>" value="<?=@$a->emergencia_telefono_numero;?>">
																</div>
															</div>
														</div>
													
													</div>

												</div>
												<!-- FIN CONTACTO DE EMERGENCIA -->


												<div class="submit">
													<a class="link lnkSaltearPasajero" rel="<?=@$a->id;?>" href="javascript:void(0)">Saltear Paso</a>

													<input class="btn btn-pink button btnSavePax" type="button" value="Guardar datos" rel="<?=@$a->id;?>" />
												</div>

										</div>
									</div>
								</div>

								<!-- FIN PASAJERO <?=$i?> -->
								<? endforeach; ?>
								


								
									<!-- FORM TÉRMINOS -->

									<div data-rel="<?=count($incompletos);?>" class="form_terminos <?=count($incompletos)?'hidden':'';?>">
										<div class="text">
											<div>
												<p><?=$this->config->item('texto_abogado');?></p>
											</div>
										</div>

										<div class="required submit">
											<div class="check_content">
												<label>
													<input type="checkbox" name="terminos_pax" value="1" <?=$orden->completo_paso3?'checked':'';?>/>
													<span>Acepto que los datos expuestos arriba son correctos.</span>
												</label>
											</div>

											<input class="button confimar" type="submit" value="Confirmar todos los datos" />

											<!-- ERROR -->
											<p class="msg error">Para confirmar todos los datos debes aceptar términos y condiciones</p>
											<!-- FIN ERROR -->
										</div>
									</div>

									<!-- FIN FORM TÉRMINOS -->

								</form>
								
							</div>
						</div>
						
						<? endif; ?>
						
					</div>

				</div>

				<!-- FIN PASO 3 -->
			
			<? endif; ?>
			
		<script>
		window.onload = function(){
			$.each($('.selectpicker.nacionalidad_id'),function(i,el){
				var obj = $(el);
				campos_documentacion(obj);
			});
		}
		</script>