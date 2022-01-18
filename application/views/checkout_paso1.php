				<!-- PASO 1 -->

				<div class="panel-group">
					<div class="panel panel-default">

						<!-- LA CLASE "completo" junto al "panel-heading" pinta de azul el desplegable -->

						<div class="panel-heading <?=($orden->completo_paso1)?'completo':'';?>">
							<h3 class="panel-title">
								<a class="<?=($orden->paso_actual==1)?'':'collapsed';?>" role="button" data-toggle="collapse" href="#paso_1">
									<span class="num_paso">1</span>
									<span>
										<!-- Si hay un <span> fuera del <strong> levanta los estilos como si fuera un link -->
										<? if($orden->completo_paso1): ?>
										<strong class="head-pasajero-1">Pasajero 1: <?=$responsable->nombre.' '.$responsable->apellido;?></strong> <span>Editar</span>
										<? else: ?>
										<strong class="head-pasajero-1">Completa los datos del pasajero 1 tal cual figura en el dni o pasaporte <span>(Responsable)</span></strong>
										<? endif; ?>
									</span>
								</a>
							</h3>
						</div>


						<!-- La clase "in" junto a la clase "collpase" mantiene el desplegable abierto -->
						
						<div id="paso_1" class="panel-collapse collapse <?=($orden->paso_actual==1)?'in':'';?>">

							<form class="panel-body checkout_paso" id="frmPaso1">
								<input type="hidden" name="numero_paso" id="numero_paso" value="1"/>
								<input type="hidden" name="orden_id" value="<?=$orden->id;?>"/>
								<input type="hidden" name="pax_id" value="<?=$responsable->id;?>"/>
								<input type="hidden" id="pasaporte_obligatorio" value="<?=$pasaporte_obligatorio;?>"/>
								
								<div class="row data_pax form_block">

									<!-- + clase "error" para mostrar mensaje de error-->
									<label class="required">
										<span>Nombres completos <span class="obligatorio">(Obligatorio)</span></span>

										<input type="text" name="nombre" value="<?=@$responsable->nombre;?>" class="onlytext" />

										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Apellido <span class="obligatorio">(Obligatorio)</span></span>

										<input type="text" name="apellido" value="<?=@$responsable->apellido;?>"  class="onlytext" />
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Fecha de Nacimiento <span class="obligatorio">(Obligatorio)</span></span>
										
										<? $fecha = @$responsable->fecha_nacimiento;
										$fecha = explode('-',$fecha); 
										$fecha_nac='';
										/*if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
											$fecha_nac = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
										}*/ ?>
										
										<!--
										<input type="text" readonly class="datepicker-free fnacimiento onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="nacimiento" value="<?=$fecha_nac;?>"/>
										-->
										
										<div class="fecha fecha_nacimiento">
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_dia" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_mes" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
											<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_ano" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
										</div>
										
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									<label class="required">
										<span>Sexo <span class="obligatorio">(Obligatorio)</span></span>
											
										<div class="check_content">
											<label>
												<input type="radio" name="sexo" value="femenino" <?=@$responsable->sexo=='femenino'?'checked':'';?> /> <span>Femenino</span>
											</label>
											<label>
												<input type="radio" name="sexo" value="masculino" <?=@$responsable->sexo=='masculino'?'checked':'';?> /> <span>Masculino</span>
											</label>
										</div>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required">
										<span>Nacionalidad <span class="obligatorio">(Obligatorio)</span></span>


										<select class="selectric selectpicker nacionalidad_id" data-title="Elige un país" id="nacionalidad_id" name="nacionalidad_id">
											<option value="">Seleccionar</option>
											<? foreach($paises as $p): ?>
											<option value="<?=$p->id;?>" <?=$p->id==@$responsable->nacionalidad_id?'selected':'';?>><?=$p->nombre;?></option>
											<? endforeach; ?>
										</select>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									<br />

									<p class="completar_dni_pasaporte hidden">Debe completar Documento y/o Pasaporte</p>

									<label class="required data_dni">
										<span>Documento de Indentificación <span class="obligatorio">(Obligatorio)</span></span>

										<input type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="ej: 34697216" class="onlynum" name="dni" value="<?=@$responsable->dni;?>"/>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>

									<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
										<span>Pasaporte N° <? if(@$forzar_datos_basicos):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>

										<input type="text" name="pasaporte" value="<?=@$responsable->pasaporte;?>" />
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
										<span>País de emisión <? if(@$forzar_datos_basicos):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>
			
										<select class="selectric selectpicker" data-title="Elige un país" name="pais_emision_id">
											<option value="">Seleccionar</option>
											<? foreach($paises as $p): ?>
											<option value="<?=$p->id;?>" <?=$p->id==@$responsable->pais_emision_id?'selected':'';?>><?=$p->nombre;?></option>
											<? endforeach; ?>
										</select>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
										<span>Fecha de emisión del pasaporte <? if(@$forzar_datos_basicos):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>

										<? $fecha = @$responsable->fecha_emision;
										$fecha = explode('-',$fecha);
										$fecha_emision='';
										if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
											$fecha_emision = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
										} ?>
										
										<!-- <input type="text" readonly class="datepicker-free onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="fecha_emision" value="<?=$fecha_emision;?>"/> -->
										
										<div class="fecha fecha_nacimiento">
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_dia" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_mes" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
											<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_emision_ano" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
										</div>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
										<span>Fecha de vto. del pasaporte <? if(@$forzar_datos_basicos):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>

										<? $fecha = @$responsable->fecha_vencimiento;
										$fecha = explode('-',$fecha);
										$fecha_vencimiento='';
										if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
											$fecha_vencimiento = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
										} ?>

										<!-- <input type="text" readonly class="datepicker-free onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Elige la fecha" name="fecha_vencimiento" value="<?=$fecha_vencimiento;?>"/> -->


										<div class="fecha fecha_nacimiento">
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="dd" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_dia" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
											<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="mm" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_mes" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
											<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="aaaa" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="fecha_vencimiento_ano" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
										</div>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									<br>
									<label class="required">
										<span>E-mail <span class="obligatorio">(Obligatorio)</span></span>

										<input type="email" name="email" value="<?=@$responsable->email;?>" class="email"/>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</label>
									
									
									<div class="required fw">
										<span>Celular <span class="obligatorio">(Obligatorio)</span></span>

										<div class="tel">
											<div>
												<span>0</span>
												<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="celular_codigo" value="<?=@$responsable->celular_codigo;?>" />
											</div>
											
											<div>
												<span>15</span>
												<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="celular_numero" value="<?=@$responsable->celular_numero;?>" />
											</div>
										</div>
										<span class="msg">CAMPO OBLIGATORIO</span>
									</div>

								</div>


								<!-- CONTACTO DE EMERGENCIA -->
								<div>

									<label style="margin-bottom:10px;"><span>Contacto de Emergencia</span></label>

									<div class="row form_block">
										<label class="<?=(@$forzar_datos_basicos || $combinacion->grupal)?'required':'';?>">
											<span>Nombre y Apellido <? if(@$forzar_datos_basicos || $combinacion->grupal):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>

											<input type="text" name="emergencia_nombre" value="<?=@$responsable->emergencia_nombre;?>" class="onlytext" />
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<div class="<?=@$forzar_datos_basicos || $combinacion->grupal?'required':'';?>">
											<span>Teléfono <? if(@$forzar_datos_basicos || $combinacion->grupal):?><span class="obligatorio">(Obligatorio)</span><? endif; ?></span>

											<div class="tel">
												<div>
													<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Cód." name="emergencia_telefono_codigo" value="<?=@$responsable->emergencia_telefono_codigo;?>" >
												</div>
												
												<div>
													<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" placeholder="Número" name="emergencia_telefono_numero" value="<?=@$responsable->emergencia_telefono_numero;?>">
												</div>
											</div>
											<span class="msg">CAMPO OBLIGATORIO</span>
										</div>

										<? if(@$combinacion->lugar_id != 4): ?>
										<div class="full_width required">
											<span>Seleccioná de dónde sales <span class="obligatorio">(Obligatorio)</span></span>
											
											<div class="check_content">
												<? foreach($paradas as $p): ?>
												<label>
													<input type="radio" name="paquete_parada_id" value="<?=$p->id;?>" <?=($p->id==@$orden->paquete_parada_id || count($paradas)==1)?'checked':'';?> /> <span><strong><?=$p->hora;?>hs</strong> - <?=$p->nombre;?></span>
												</label>
												<? endforeach; ?>
											</div>

											<!-- ERROR -->
											<p class="msg error">Por favor, selecciona de dónde sales</p>
											<!-- FIN ERROR -->
										</div>
										<? endif; ?>

										<div class="full_width required">
											<span>Detalle de Dieta <span class="obligatorio">(Obligatorio)</span></span>
											
											<div class="check_content">
												<label>
													<input type="radio" name="dieta" value="Vegetariano" <?='Vegetariano'==@$responsable->dieta?'checked':'';?> /> <span>Vegetariano</span>
												</label>
												
												<label>
													<input type="radio" name="dieta" value="Celíaco" <?='Celíaco'==@$responsable->dieta?'checked':'';?> /> <span>Celíaco</span>
												</label>
												
												<label>
													<input type="radio" name="dieta" value="Diabético" <?='Diabético'==@$responsable->dieta?'checked':'';?> /> <span>Diabético</span>
												</label>
												
												<label>
													<input type="radio" name="dieta" <?=('Ninguno'==@$responsable->dieta || @$responsable->dieta =='')?'checked':'';?>  value="Ninguno" /> <span>Sin Variaciones</span>
												</label>
											</div>

											<!-- ERROR -->
											<p class="msg error">Por favor, selecciona el detalle de dieta</p>
											<!-- FIN ERROR -->

											
										</div>
									
										<? $cant = 0;
										foreach($orden->adicionales as $a):
											$cant += $a->cantidad;
										endforeach; ?>

										<? //si hay algun adicional restringido para los pax
										if($cant): ?>
											<div class="full_width required"><h4>Adicionales </h4></div>
												
										<? foreach($orden->adicionales as $a): ?>

											<div class="full_width required">
												<span><?=$a->nombre;?></span>
												<div class="check_content">
													<input type="hidden" name="pasajero_adicional_<?=@$responsable->id;?>">
													<label>
														<input type="radio" name="pax_adicional[<?=$a->id;?>]" value="1" <?=in_array($a->id,@$responsable_adicionales)?'checked':'';?>/> <span>Si</span>
													</label>
													
													<label>
														<input type="radio" name="pax_adicional[<?=$a->id;?>]" value="0" <?=!in_array($a->id,@$responsable_adicionales)?'checked':'';?> /> <span>No</span>
													</label>
													
												</div>

												<!-- ERROR -->
												<p class="msg error">Por favor, selecciona una opción</p>
												<!-- FIN ERROR -->

											</div>

											<? endforeach;
										endif; ?>
									
									</div>

								</div>
								<!-- FIN CONTACTO DE EMERGENCIA -->


								<div class="submit required">
									<input class="submit_button btn btn-pink" type="submit" value="Guardar datos" />
								</div>




							</form>
						</div>
					</div>
				</div>

				<!-- FIN PASO 1 -->
		<script>
		window.onload = function(){
			
			var obj = $('#nacionalidad_id');
			campos_documentacion(obj);
		}
		</script>