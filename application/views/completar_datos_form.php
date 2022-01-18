			<? if($reserva->estado_id == 5): //anulada
				$completar_datos = false;//fuerzo para que no pueda completar
			endif; ?>
			
			<? //si va a descargar voucher, le devuelvo los datos que estan completos
			$force_data = isset($download_voucher) && $download_voucher ? true : false ; ?>

			<? if($completar_datos && !$force_data): ?>
				
				<div class="module">
					<h2>Datos del pasajero responsable y acompañantes</h2>

					<? if($reserva->grupal): ?>
						
						<p>Podés editar los datos de tu reserva. <strong>Tenés tiempo hasta el <?=date('d/m/Y',strtotime(fecha_completar_datos($reserva->id)));?> a las <?=date('H:i',strtotime(fecha_completar_datos($reserva->id)));?>hs</strong>.</p>

					<? else: ?>
						
						<p>Podés editar los datos del Pasajero 1 y/o completar los datos de los acompañantes. <strong>Tenés tiempo hasta el <?=date('d/m/Y',strtotime(fecha_completar_datos($reserva->id)));?> a las <?=date('H:i',strtotime(fecha_completar_datos($reserva->id)));?>hs</strong>.</p>

					<? endif; ?>

					<form class="panel-body checkout_paso frmResumen" id="frmPaso2" style="padding:0;">
						<input type="hidden" name="completa_luego" id="completa_luego" value="0"/>
						<input type="hidden" name="numero_paso" id="numero_paso" value="3"/>
						<input type="hidden" name="saltear_pax" id="saltear_pax" value="0"/>
						<input type="hidden" name="grabar_pax" id="grabar_pax" value="0"/>
						<input type="hidden" name="reserva_id" id="reserva_id" value="<?=$reserva->id;?>"/>
						<input type="hidden" id="pasaporte_obligatorio" value="<?=$pasaporte_obligatorio;?>"/>

					<? foreach($pasajeros as $a): ?>
					<!-- PASAJERO <?=$a->numero_pax;?> -->

					<div class="panel-group">
						<div class="panel panel-default">

							<!-- LA CLASE "completo" pinta de verde el desplegable -->

							<div class="panel-heading <?=$a->completo?'completo':'incompleto';?>">
								<h4 class="panel-title">

									<!-- El HREF debe ser el mismo que el ID del div con clase "panel-collapse" -->

									<a class="collapsed" role="button" data-toggle="collapse" href="#pasajero_<?=$a->id;?>">
										<span>
											<strong class="head-pax-<?=$a->id;?>">Pasajero <?=$a->numero_pax;?> <?=$a->responsable?'<span>(responsable)</span>':'';?></strong>
											<span> <?=$a->completo?'Editar datos':'Completar datos';?></span>
											<? if($reserva->grupal): ?>
												<span class="mr10 pull-right">Estado: <?=$a->estado;?></span>
											<? endif; ?>
										</span>
									</a>
								</h4>
							</div>

							<!-- El ID debe ser el mismo que el HREF del ancla con clase "button" -->

							<div id="pasajero_<?=$a->id;?>" class="panel-collapse collapse">
								
									<div class="row data_pax">

										<label class="required">
											<span>Nombre <span class="obligatorio">(Obligatorio)</span></span>

											<input type="text" value="<?=$a->nombre;?>" name="nombre_<?=$a->id;?>" class="onlytext" />
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required">
											<span>Apellido <span class="obligatorio">(Obligatorio)</span></span>

											<input type="text" value="<?=$a->apellido;?>" name="apellido_<?=$a->id;?>"  class="onlytext" />
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required">
											<span>Fecha de Nacimiento <span class="obligatorio">(Obligatorio)</span></span>

											<? $fecha = @$a->fecha_nacimiento;
											$fecha = explode('-',$fecha); 
											 ?>
											
											<!--
											<input type="text" readonly class="datepicker-free fnacimiento onlynum" placeholder="Elegí la fecha" name="nacimiento_<?=@$a->id;?>" value="<?=$fecha_nac;?>"/>
											-->
											
											<div class="fecha fecha_nacimiento">
												<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum diavalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" placeholder="dd" /><span>/</span>
												<input maxlength="2" type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum mesvalido <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" placeholder="mm" /><span>/</span>
												<input maxlength="4" type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum <?=$this->detector->isMobile()?'mobile':'';?>" name="nacimiento_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" placeholder="aaaa" />
											</div>
											
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required">
											<span>Sexo <span class="obligatorio">(Obligatorio)</span></span>

											<div class="check_content">
												<label>
													<input type="radio" name="sexo_<?=@$a->id;?>" value="femenino" <?=@$a->sexo=='femenino'?'checked':'';?> /> <span>Femenino</span>
												</label>
												<label>
													<input type="radio" name="sexo_<?=@$a->id;?>" value="masculino" <?=@$a->sexo=='masculino'?'checked':'';?> /> <span>Masculino</span>
												</label>
											</div>
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required">
											<span>Nacionalidad <span class="obligatorio">(Obligatorio)</span></span>

											<select class="selectric selectpicker nacionalidad_id" data-title="Elegí un país" name="nacionalidad_id_<?=@$a->id;?>">
												<option value="">Seleccionar</option>
												<? foreach($paises as $p): ?>
												<option value="<?=$p->id;?>" <?=$p->id==@$a->nacionalidad_id?'selected':'';?>><?=$p->nombre;?></option>
												<? endforeach; ?>
											</select>

											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required data_dni">
											<span>DNI <span class="obligatorio">(Obligatorio)</span></span>

											<input type="<?=$this->detector->isMobile()?'number':'text';?>" placeholder="ej: 34697216" class="onlynum" name="dni_<?=@$a->id;?>" value="<?=@$a->dni;?>"/>
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>

										<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
											<span>Pasaporte N° <span class="obligatorio">(Obligatorio)</span></span>

											<input type="text" name="pasaporte_<?=@$a->id;?>" value="<?=@$a->pasaporte;?>" />
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>

										
										<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
											<span>País de emisión <span class="obligatorio">(Obligatorio)</span></span>
											
											<select class="selectric selectpicker pais_emision" data-title="Elegí un país" name="pais_emision_id_<?=@$a->id;?>">
												<option value="">Seleccionar</option>
												<? foreach($paises as $p): ?>
												<option value="<?=$p->id;?>" <?=$p->id==@$a->pais_emision_id?'selected':'';?>><?=$p->nombre;?></option>
												<? endforeach; ?>
											</select>
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
											<span>Emisión del pasaporte <span class="obligatorio">(Obligatorio)</span></span>

											<? $fecha = @$a->fecha_emision;
											$fecha = explode('-',$fecha);
											$fecha_emision='';
											if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
												$fecha_emision = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
											} ?>
											
											<input type="text" readonly class="datepicker-free onlynum " placeholder="Elegí la fecha" name="fecha_emision_<?=@$a->id;?>" value="<?=$fecha_emision;?>"/>
											
											<!--<div class="fecha">
												<input type="text" placeholder="dd" class="onlynum" name="fecha_emision_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
												<input type="text" placeholder="mm" class="onlynum" name="fecha_emision_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
												<input type="text" placeholder="aaaa" class="onlynum" name="fecha_emision_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
											</div>-->
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<label class="required data_pasaporte <?=@$pasaporte_obligatorio?'pasaporte_obligatorio':'hidden';?>">
											<span>Fecha de vto. del pasaporte <span class="obligatorio">(Obligatorio)</span></span>

											<? $fecha = @$a->fecha_vencimiento;
											$fecha = explode('-',$fecha);
											$fecha_vencimiento='';
											if(@$fecha[2] > 0 && @$fecha[1] > 0 && @$fecha[0] > 0){
												$fecha_vencimiento = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
											} ?>
											
											<input type="text" readonly class="datepicker-free onlynum" placeholder="Elegí la fecha" name="fecha_vencimiento_<?=@$a->id;?>" value="<?=$fecha_vencimiento;?>"/>
											
											<!--<div class="fecha">
												<input type="text" placeholder="dd" class="onlynum" name="fecha_vencimiento_dia_<?=@$a->id;?>" value="<?=@$fecha[2]>0?@$fecha[2]:'';?>" /><span>/</span>
												<input type="text" placeholder="mm" class="onlynum" name="fecha_vencimiento_mes_<?=@$a->id;?>" value="<?=@$fecha[1]>0?@$fecha[1]:'';?>" /><span>/</span>
												<input type="text" placeholder="aaaa" class="onlynum" name="fecha_vencimiento_ano_<?=@$a->id;?>" value="<?=@$fecha[0]>0?@$fecha[0]:'';?>" />
											</div>-->
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										<label class="required">
											<span>E-mail <span class="obligatorio">(Obligatorio)</span></span>

											<input type="email" name="email_<?=@$a->id;?>" value="<?=@$a->email;?>" class="email" />
											<span class="msg">CAMPO OBLIGATORIO</span>
										</label>
										
										
										<div class="required">
											<span>Celular <span class="obligatorio">(Obligatorio)</span></span>

											<div class="tel">
												<div>
													<span>0</span>
													<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum" name="celular_codigo_<?=@$a->id;?>" value="<?=@$a->celular_codigo;?>" />
												</div>
												
												<div>
													<span>15</span>
													<input type="<?=$this->detector->isMobile()?'number':'text';?>" class="onlynum" name="celular_numero_<?=@$a->id;?>" value="<?=@$a->celular_numero;?>" />
												</div>
											</div>
											<span class="msg">CAMPO OBLIGATORIO</span>
										</div>

									</div>

									<!-- CONTACTO DE EMERGENCIA -->
									<div>

										<h4>Contacto de Emergencia</h4>

										<div class="row">
											<label class="required">
												<span>Nombre y Apellido <span class="obligatorio">(Obligatorio)</span></span>

												<input type="text" name="emergencia_nombre_<?=@$a->id;?>" value="<?=@$a->emergencia_nombre;?>" class="onlytext" />
											</label>											
											
											<div class="required">
												<span>Teléfono <span class="obligatorio">(Obligatorio)</span></span>

												<div class="tel">
													<div>
														<input type="<?=$this->detector->isMobile()?'number':'text';?>" name="emergencia_telefono_codigo_<?=@$a->id;?>" value="<?=@$a->emergencia_telefono_codigo;?>" placeholder="Cód.">
													</div>
													
													<div>
														<input type="<?=$this->detector->isMobile()?'number':'text';?>" name="emergencia_telefono_numero_<?=@$a->id;?>" value="<?=@$a->emergencia_telefono_numero;?>" placeholder="Número">
													</div>
												</div>
											</div>
										
											<span class="msg">CAMPO OBLIGATORIO</span>

											<div class="full_width required">
											<span>Detalle de Dieta <span class="obligatorio">(Obligatorio)</span></span>
											
											<div class="check_content">
												<label>
													<input type="radio" name="dieta_<?=@$a->id;?>" value="Vegetariano" <?='Vegetariano'==@$a->dieta?'checked':'';?> /> <span>Vegetariano</span>
												</label>
												
												<label>
													<input type="radio" name="dieta_<?=@$a->id;?>" value="Celíaco" <?='Celíaco'==@$a->dieta?'checked':'';?> /> <span>Celíaco</span>
												</label>
												
												<label>
													<input type="radio" name="dieta_<?=@$a->id;?>" value="Diabético" <?='Diabético'==@$a->dieta?'checked':'';?> /> <span>Diabético</span>
												</label>
												
												<label>
													<input type="radio" name="dieta_<?=@$a->id;?>" <?=('Ninguno'==@$a->dieta || @$a->dieta =='')?'checked':'';?>  value="Ninguno" /> <span>Sin Variaciones</span>
												</label>
											</div>

											<!-- ERROR -->
											<p class="msg error">Por favor, seleccioná el detalle de dieta</p>
											<!-- FIN ERROR -->
										</div>
										</div>

									</div>
									<!-- FIN CONTACTO DE EMERGENCIA -->
									
									<div class="submit submit-pax">
										<input class="button btnSavePaxRes" type="button" value="Guardar datos" rel="<?=@$a->id;?>" />
									</div>

							</div>
						</div>
					</div>

					<!-- FIN PASAJERO 1 -->
					<? endforeach; ?>
<!-- RESERVA GRUPAL -->
					<? if($reserva->grupal && isset($pasajeros_grupo) && $pasajeros_grupo): ?>
						<? $npax=1;
						foreach($pasajeros_grupo as $p): $npax++; ?>
							<!-- PASAJERO <?=$p->numero_pax;?> -->

							<div class="panel-group">
								<div class="panel panel-default">

									<!-- LA CLASE "completo" pinta de verde el desplegable -->

									<div class="panel-heading">
										<h4 class="panel-title">
											<!-- El HREF debe ser el mismo que el ID del div con clase "panel-collapse" -->

											<a class="collapsed" role="button" data-toggle="collapse" href="#pasajero_<?=$p->id;?>">
												<span>
													<strong>Pasajero <?=$npax;?></strong>
													<span>Ver datos</span>
												</span>
												<? if($reserva->grupal): ?>
													<span class="mr10 pull-right">Estado: <?=$p->estado;?></span>
												<? endif; ?>
											</a>
										</h4>
									</div>

									<!-- El ID debe ser el mismo que el HREF del ancla con clase "button" -->

									<div id="pasajero_<?=$p->id;?>" class="panel-collapse collapse">
										<div class="panel-body">

											<div class="row">											
												<? if($p->nombre): ?>
												<label class="static">
													<span>Nombre: <span class="text"><?=$p->nombre;?></span></span>
												</label>	
												<? endif; ?>									
												
												<? if($p->apellido): ?>
												<label class="static">
													<span>Apellido: <span class="text"><?=$p->apellido;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->fecha_nacimiento != '0000-00-00'): ?>
												<label class="static">
													<span>Fecha de Nacimiento: <span class="text"><?=formato_fecha($p->fecha_nacimiento);?></span></span>
												</label>	
												<? endif; ?>									
												
												<? if($p->fecha_nacimiento != '0000-00-00'): ?>
												<label class="static">
													<span>Edad: <span class="text"><?=edad($p->fecha_nacimiento);?></span></span>
												</label>
												<? endif; ?>
												
												<? if($p->nacionalidad): ?>
												<label class="static">
													<span>Nacionalidad: <span class="text"><?=$p->nacionalidad;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->dni): ?>
												<label class="static">
													<span>DNI: <span class="text"><?=number_format($p->dni,0,'','.');?></span></span>
												</label>
												<? endif; ?>

												<? if($p->pasaporte): ?>
												<label class="static">
													<span>Pasaporte N°: <span class="text"><?=$p->pasaporte;?></span></span>
												</label>
												<? endif; ?>
												
												<? if($p->pais_emision): ?>
												<label class="static">
													<span>País de emisión: <span class="text"><?=$p->pais_emision;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->fecha_emision != '0000-00-00' && $p->pasaporte): ?>
												<label class="static">
													<span>Fecha de emisión del pasaporte: <span class="text"><?=formato_fecha($p->fecha_emision);?></span></span>
												</label>		
												<? endif; ?>									
												
												<? if($p->fecha_vencimiento != '0000-00-00' && $p->pasaporte): ?>
												<label class="static">
													<span>Fecha de vencimiento del pasaporte: <span class="text"><?=formato_fecha($p->fecha_vencimiento);?></span></span>
												</label>
												<? endif; ?>	

												<? if($p->email): ?>
												<label class="static">
													<span>E-mail: <span class="text"><?=$p->email;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->celular_codigo && $p->celular_numero): ?>
												<label class="static">
													<span>Celular: <span class="text"><?=$p->celular_codigo.' '.$p->celular_numero;?></span></span>
												</label>
												<? endif; ?>									
												
												<? if(@$p->direccion): ?>
												<label class="static">
													<span>Dirección/Ciudad/Provincia: <span class="text"><?=$p->direccion;?></span></span>
												</label>
												<? endif; ?>

											</div>

											<? if($p->emergencia_nombre || ($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero)): ?>
											<!-- CONTACTO DE EMERGENCIA -->
											<div>

												<h4>Contacto de Emergencia</h4>

												<div class="row">
													<? if($p->emergencia_nombre): ?>
													<label class="static">
														<span>Nombre y Apellido: <span class="text"><?=$p->emergencia_nombre;?></span></span>
													</label>
													<? endif; ?>
													
													<? if($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero): ?>
													<label class="static">
														<span>Teléfono: <span class="text"><?=$p->emergencia_telefono_codigo.' '.$p->emergencia_telefono_numero;?></span></span>
													</label>
													<? endif; ?>

													<? if($p->dieta): ?>
													<label class="static">
														<span>Detalle de la dieta: <span class="text"><?=$p->dieta;?></span></span>
													</label>
													<? endif; ?>
												
												</div>

											</div>
											<!-- FIN CONTACTO DE EMERGENCIA -->
											<? endif; ?>


										</div>
									</div>
								</div>
							</div>

							<!-- FIN PASAJERO <?=$p->numero_pax;?> -->
							<? endforeach; ?>
					<? endif; ?>
					<!-- end RESERVA GRUPAL -->

					

					<!-- FORM TÉRMINOS -->

					<div class="form_terminos <?=count($incompletos)?'hidden':'';?>">
							<div class="text">
								<div>
									<p><?=$this->config->item('texto_abogado');?></p>
								</div>
							</div>

							<div class="required submit">
								<div class="check_content">
									<label>
										<input type="checkbox" name="terminos_pax" value="1" <?=@$reserva->completo_paso3?'checked  readonly':'';?>/>
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
				
			<? else: ?>
				
				<div class="module">
					<h2>Datos del pasajero responsable y acompañantes</h2>

					<p>Todos los datos a continuación fueron confirmados por el pasajero responsable el <?=formato_fecha($reserva->fecha_completo_paso3);?>. Si notás que hay algún error por favor comunicate con nosotros<br/>
						<ul>
							<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
							<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810</a> de Lunes a Viernes de 10 a 19 hs.</li>
						</ul>
					</p>


					<? foreach($pasajeros as $p): ?>
					<!-- PASAJERO <?=$p->numero_pax;?> -->

					<div class="panel-group">
						<div class="panel panel-default">

							<!-- LA CLASE "completo" pinta de verde el desplegable -->

							<div class="panel-heading">
								<h4 class="panel-title">
									<? if(isset($descarga_voucher) && $descarga_voucher): ?>
										<span>
											<strong>Pasajero <?=$p->numero_pax;?> <?=$p->responsable?'(Responsable)':'';?></strong>
										</span>
									<? else: ?>
										<!-- El HREF debe ser el mismo que el ID del div con clase "panel-collapse" -->

										<a class="collapsed" role="button" data-toggle="collapse" href="#pasajero_<?=$p->id;?>">
											<span>
												<strong>Pasajero <?=$p->numero_pax;?> <?=$p->responsable?'(Responsable)':'';?></strong>
												<span>Ver datos</span>
												<? if($reserva->grupal): ?>
													<span class="mr10 pull-right">Estado: <?=$p->estado;?></span>
												<? endif; ?>
											</span>
										</a>
									<? endif; ?>
								</h4>
							</div>

							<!-- El ID debe ser el mismo que el HREF del ancla con clase "button" -->

							<div id="pasajero_<?=$p->id;?>" class="panel-collapse collapse">
								<div class="panel-body">

									<? if($p->estado_id ==5): ?>
										<div class="col-xs-12 msg alert alert-danger">
											Esta reserva se encuentra <strong>ANULADA</strong>..<br>Por cualquier duda o inconveniente, por favor comunicate con nosotros.<br/>
											<ul>
												<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
												<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810</a> de Lunes a Viernes de 10 a 19 hs.</li>
											</ul>
										</div>
									<? endif; ?>
										
									<div class="row">

										<? if($p->nombre): ?>
										<label class="static">
											<span>Nombre: <span class="text"><?=$p->nombre;?></span></span>
										</label>	
										<? endif; ?>									
										
										<? if($p->apellido): ?>
										<label class="static">
											<span>Apellido: <span class="text"><?=$p->apellido;?></span></span>
										</label>
										<? endif; ?>										
										
										<? if($p->fecha_nacimiento != '0000-00-00'): ?>
										<label class="static">
											<span>Fecha de Nacimiento: <span class="text"><?=formato_fecha($p->fecha_nacimiento);?></span></span>
										</label>	
										<? endif; ?>									
										
										<? if($p->fecha_nacimiento != '0000-00-00'): ?>
										<label class="static">
											<span>Edad: <span class="text"><?=edad($p->fecha_nacimiento);?></span></span>
										</label>
										<? endif; ?>
										
										<? if($p->nacionalidad): ?>
										<label class="static">
											<span>Nacionalidad: <span class="text"><?=$p->nacionalidad;?></span></span>
										</label>
										<? endif; ?>										
										
										<? if($p->dni): ?>
										<label class="static">
											<span>DNI: <span class="text"><?=number_format($p->dni,0,'','.');?></span></span>
										</label>
										<? endif; ?>

										<? if($p->pasaporte): ?>
										<label class="static">
											<span>Pasaporte N°: <span class="text"><?=$p->pasaporte;?></span></span>
										</label>
										<? endif; ?>
										
										<? if($p->pais_emision): ?>
										<label class="static">
											<span>País de emisión: <span class="text"><?=$p->pais_emision;?></span></span>
										</label>
										<? endif; ?>										
										
										<? if($p->fecha_emision != '0000-00-00' && $p->fecha_emision != ''): ?>
										<label class="static" data-rel="<?=$p->fecha_emision;?>">
											<span>Fecha de emisión del pasaporte: <span class="text"><?=formato_fecha($p->fecha_emision);?></span></span>
										</label>		
										<? endif; ?>									
										
										<? if($p->fecha_vencimiento != '0000-00-00' && $p->fecha_vencimiento != ''): ?>
										<label class="static">
											<span>Fecha de vencimiento del pasaporte: <span class="text"><?=formato_fecha($p->fecha_vencimiento);?></span></span>
										</label>
										<? endif; ?>	

										<? if($p->email): ?>
										<label class="static">
											<span>E-mail: <span class="text"><?=$p->email;?></span></span>
										</label>
										<? endif; ?>										
										
										<? if($p->celular_codigo && $p->celular_numero): ?>
										<label class="static">
											<span>Celular: <span class="text"><?=$p->celular_codigo.' '.$p->celular_numero;?></span></span>
										</label>
										<? endif; ?>									
										
										<? if(@$p->direccion): ?>
										<label class="static">
											<span>Dirección/Ciudad/Provincia: <span class="text"><?=$p->direccion;?></span></span>
										</label>
										<? endif; ?>

									</div>

									<? if($p->emergencia_nombre || ($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero)): ?>
									<!-- CONTACTO DE EMERGENCIA -->
									<div>

										<h4>Contacto de Emergencia</h4>

										<div class="row">
											<? if($p->emergencia_nombre): ?>
											<label class="static">
												<span>Nombre y Apellido: <span class="text"><?=$p->emergencia_nombre;?></span></span>
											</label>
											<? endif; ?>
											
											<? if($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero): ?>
											<label class="static">
												<span>Teléfono: <span class="text"><?=$p->emergencia_telefono_codigo.' '.$p->emergencia_telefono_numero;?></span></span>
											</label>
											<? endif; ?>

											<? if($p->dieta): ?>
											<label class="static">
												<span>Detalle de la dieta: <span class="text"><?=$p->dieta;?></span></span>
											</label>
											<? endif; ?>
										
										</div>

									</div>
									<!-- FIN CONTACTO DE EMERGENCIA -->
									<? endif; ?>


								</div>
							</div>
						</div>
					</div>

					<!-- FIN PASAJERO <?=$p->numero_pax;?> -->
					<? endforeach; ?>

					<!-- RESERVA GRUPAL -->
					<? if($reserva->grupal && isset($pasajeros_grupo) && $pasajeros_grupo): ?>
						<? $npax=1;
						foreach($pasajeros_grupo as $p): $npax++; ?>
							<!-- PASAJERO <?=$p->numero_pax;?> -->

							<div class="panel-group">
								<div class="panel panel-default">

									<!-- LA CLASE "completo" pinta de verde el desplegable -->

									<div class="panel-heading">
										<h4 class="panel-title">
											<!-- El HREF debe ser el mismo que el ID del div con clase "panel-collapse" -->

											<a class="collapsed" role="button" data-toggle="collapse" href="#pasajero_<?=$p->id;?>">
												<span>
													<strong>Pasajero <?=$npax;?></strong>
													<span>Ver datos</span>
												</span>
												<? if($reserva->grupal): ?>
													<span class="mr10 pull-right">Estado: <?=$p->estado;?></span>
												<? endif; ?>
											</a>
										</h4>
									</div>

									<!-- El ID debe ser el mismo que el HREF del ancla con clase "button" -->

									<div id="pasajero_<?=$p->id;?>" class="panel-collapse collapse">
										<div class="panel-body">

											<div class="row">

												<? if($p->nombre): ?>
												<label class="static">
													<span>Nombre: <span class="text"><?=$p->nombre;?></span></span>
												</label>	
												<? endif; ?>									
												
												<? if($p->apellido): ?>
												<label class="static">
													<span>Apellido: <span class="text"><?=$p->apellido;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->fecha_nacimiento != '0000-00-00'): ?>
												<label class="static">
													<span>Fecha de Nacimiento: <span class="text"><?=formato_fecha($p->fecha_nacimiento);?></span></span>
												</label>	
												<? endif; ?>									
												
												<? if($p->fecha_nacimiento != '0000-00-00'): ?>
												<label class="static">
													<span>Edad: <span class="text"><?=edad($p->fecha_nacimiento);?></span></span>
												</label>
												<? endif; ?>
												
												<? if($p->nacionalidad): ?>
												<label class="static">
													<span>Nacionalidad: <span class="text"><?=$p->nacionalidad;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->dni): ?>
												<label class="static">
													<span>DNI: <span class="text"><?=number_format($p->dni,0,'','.');?></span></span>
												</label>
												<? endif; ?>

												<? if($p->pasaporte): ?>
												<label class="static">
													<span>Pasaporte N°: <span class="text"><?=$p->pasaporte;?></span></span>
												</label>
												<? endif; ?>
												
												<? if($p->pais_emision): ?>
												<label class="static">
													<span>País de emisión: <span class="text"><?=$p->pais_emision;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->fecha_emision != '0000-00-00' && $p->pasaporte): ?>
												<label class="static">
													<span>Fecha de emisión del pasaporte: <span class="text"><?=formato_fecha($p->fecha_emision);?></span></span>
												</label>		
												<? endif; ?>									
												
												<? if($p->fecha_vencimiento != '0000-00-00' && $p->pasaporte): ?>
												<label class="static">
													<span>Fecha de vencimiento del pasaporte: <span class="text"><?=formato_fecha($p->fecha_vencimiento);?></span></span>
												</label>
												<? endif; ?>	

												<? if($p->email): ?>
												<label class="static">
													<span>E-mail: <span class="text"><?=$p->email;?></span></span>
												</label>
												<? endif; ?>										
												
												<? if($p->celular_codigo && $p->celular_numero): ?>
												<label class="static">
													<span>Celular: <span class="text"><?=$p->celular_codigo.' '.$p->celular_numero;?></span></span>
												</label>
												<? endif; ?>									
												
												<? if(@$p->direccion): ?>
												<label class="static">
													<span>Dirección/Ciudad/Provincia: <span class="text"><?=$p->direccion;?></span></span>
												</label>
												<? endif; ?>

											</div>

											<? if($p->emergencia_nombre || ($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero)): ?>
											<!-- CONTACTO DE EMERGENCIA -->
											<div>

												<h4>Contacto de Emergencia</h4>

												<div class="row">
													<? if($p->emergencia_nombre): ?>
													<label class="static">
														<span>Nombre y Apellido: <span class="text"><?=$p->emergencia_nombre;?></span></span>
													</label>
													<? endif; ?>
													
													<? if($p->emergencia_telefono_codigo && $p->emergencia_telefono_numero): ?>
													<label class="static">
														<span>Teléfono: <span class="text"><?=$p->emergencia_telefono_codigo.' '.$p->emergencia_telefono_numero;?></span></span>
													</label>
													<? endif; ?>

													<? if($p->dieta): ?>
													<label class="static">
														<span>Detalle de la dieta: <span class="text"><?=$p->dieta;?></span></span>
													</label>
													<? endif; ?>
												
												</div>

											</div>
											<!-- FIN CONTACTO DE EMERGENCIA -->
											<? endif; ?>


										</div>
									</div>
								</div>
							</div>

							<!-- FIN PASAJERO <?=$p->numero_pax;?> -->
							<? endforeach; ?>
					<? endif; ?>
					<!-- end RESERVA GRUPAL -->
				</div>

			<? endif; ?>
			
		<script>
		window.onload = function(){
			$.each($('.nacionalidad_id'),function(i,el){
				var obj = $(el);
				campos_documentacion(obj);
				$(el).selectric('refresh');
			});
			$.each($('.pais_emision'),function(i,el){
				var obj = $(el);
				$(el).selectric('refresh');
			});

			//$('.selectric').selectric('refresh');

			$('.datepicker').datepicker({
				format: "dd/mm/yyyy",
				language: "es",
				startDate: start_date,
				endDate: end_date,
				autoclose: true
			});
			$('.datepicker-free:not(.fnacimiento)').datepicker({
				format: "dd/mm/yyyy",
				language: "es",
				autoclose: true
			});
		}
		</script>