<?php echo $header;?>

<style>
.form-horizontal #basicos .control-label {
    padding-top: 0 !important;
}
.row-border .form-group { padding-bottom:30px; }
#vendedornombre { margin-top: -8px; }
.box-parada{ padding-bottom: 15px !important; }
.box-parada .select2 { width:500px !important; max-width:500px !important; }
</style>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Formulario de datos de <a href="<?=site_url('reservas/resumen/'.encriptar($row->code));?>">reserva</a>.</span>
		</div>
		
		<ul class="page-stats" style="padding-bottom:0;">
			<li>
				<div class="summary" style="margin-top: -4px;">
					<span>Precio cotizado</span>
					<h3><?=strip_tags($precios['precio_total'],'<sup>');?></h3>
				</div>
			</li>
			<? if ($row->estado_id == 13 || $row->estado_id == 12): ?>
			<li>
				<button id="btnConfirmarDisponibilidad" class="btn btn-primary">CONFIRMAR DISPONIBILIDAD</button>
			</li>
			<? endif; ?>
			<? if ($row->estado_id == 1 || $row->estado_id == 2): ?>
			<li>
				<button id="" class="btn btn-primary btnEsperarPago" style="background-color:#ff33cc;">EXTENDER FECHA</button>
			</li>
			<? endif; ?>
		</ul>
					
	</div>
	
	<? if ($row->rooming_cupo_id && $row->fecha_alojamiento_cupo_id && $row->rooming_cupo_id != $row->fecha_alojamiento_cupo_id): 
	//si la reserva tiene una habitación pero en el rooming está asociada a otra, le informo ?>
	<div class="alert alert-warning">
		"el pasajero está asignado a una habitación diferente de la que ha reservado originariamente"
		<strong>Diferencias con el Rooming.</strong> El pasajero está asignado a una habitación diferente de la que ha reservado originariamente.
		<br>
		<br>
		Habitación de Reserva: <b><?=$combinacion->habitacion;?></b>
		<br>
		<? if(isset($mi_rooming->id) && $mi_rooming->id): ?>
		Habitación en Rooming: <b><?=$mi_rooming->nombre;?></b>
		<? endif; ?>

	</div>
	<? endif; ?>

	<? if ($row->estado_id == 13 || $row->estado_id == 12): ?>
	<div class="alert alert-warning">
		<strong>Esta reserva necesita confirmación.</strong> Esto significa que es necesario verificar si hay disponibilidad para poder confirmar la posibilidad de que se realice.
	</div>
	<? elseif ($row->estado_id == 1): ?>
	<div class="alert alert-info">
		<strong>Esta reserva es válida pero aún no ha sido confirmada.</strong> Si el pasajero no paga el mínimo establecido antes del tiempo límite será anulada.
	</div>
	<? elseif ($row->estado_id == 14): ?>
	<div class="alert alert-info">
		<strong>Esta reserva se encuentra en estado Por Acreditar.</strong> Para confirmarla, debes generar un movimiento de pago en la cuenta corriente (puedes esperar el <b>Informe de Pago</b> del usuario).
		<? if($row->fecha_extendida > '0000-00-00'): ?>
			<br>Se ha extendido la fecha de la reserva al <b><?=date('d/m/Y H:i',strtotime($row->fecha_extendida));?>hs</b>. Para cambiar la fecha, haz <button id="" class="btn btn-primary btn-sm btnEsperarPago">click aquí</button>
		<? endif; ?>
	</div>
	<? endif;?>
	<? if ($row->estado_id != 14 && $row->fecha_extendida > '0000-00-00'): ?>
	<div class="alert alert-info">
		Se ha extendido la fecha de la reserva al <b><?=date('d/m/Y H:i',strtotime($row->fecha_extendida));?>hs</b>. Para cambiar la fecha, haz <button id="" class="btn btn-primary btn-sm btnEsperarPago">click aquí</button>
	</div>
	<? endif; ?>

	<? if (isset($_GET['conf']) && $_GET['conf'] == 'sincupo'): ?>
	<div class="alert alert-danger">
		<strong>No se puede confirmar la reserva.</strong> Actualmente no hay cupo disponible en el viaje para la cantidad de pasajeros de la reserva.
	</div>
	<? endif; ?>

	<form id="formEdit" class="form-vertical row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">
			
	<div class="row">
		<div class="col-md-12">	
			<!-- Tabs-->
			<div class="tabbable tabbable-custom tabs-left">
				<ul class="nav nav-tabs tabs-left">
					<li class="<?=!isset($_GET['tab'])?'active':'';?>"><a href="#basicos" data-toggle="tab">Datos generales</a></li>
					<li><a href="#pasajeros" data-toggle="tab">Pasajeros</a></li>
					<li><a href="#facturacion" data-toggle="tab">Datos Facturación</a></li>
					<li class="<?=@$_GET['tab']=='adicionales'?'active':'';?>"><a href="#adicionales" data-toggle="tab">Adicionales</a></li>
					<li class="<?=@$_GET['tab']=='informes'?'active':'';?>"><a href="#informes" data-toggle="tab">Informes de Pago</a></li>
					<li class="<?=@$_GET['tab']=='cta_cte'?'active':'';?>"><a id="lnkTabCta" href="#cta_cte" data-toggle="tab">Cuenta Corriente</a></li>
					<li class="<?=@$_GET['tab']=='vouchers'?'active':'';?>"><a href="#vouchers" data-toggle="tab">Vouchers</a></li>
					<li class="<?=@$_GET['tab']=='historial'?'active':'';?>"><a href="#historial" data-toggle="tab">Historial</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane <?=!isset($_GET['tab'])?'active':'';?>" id="basicos">
					
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos Generales</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
								
										<?php $this->admin->showErrors(); ?>
										
										<!-- dato de vendedor oculto -->
										<input type="hidden" value="<?=@$row->vendedor_id;?>" name="vendedor_id">

										<?php $row->vendedornombre = isset($row->vendedor_id) ? $row->vendedor_id : 0;
										echo $this->admin->combo('vendedornombre', 'Vendedor', '', $row, $vendedores, 'id', 'nombreCompleto',false); ?>
											

										<div class="form-group">
											<label class="col-md-3 control-label">Estado de Reserva:  </label>
											<div class="col-md-9 clearfix">

												<? foreach($estados as $estado): ?>
												<? if ($estado->id == $row->estado_id): ?>
												<label class="label label-primary" style="background-color:#<?=$estado->color;?>; vertical-align:middle; padding: 3px 6px;font-size: 12px;"><?=$estado->nombre;?></label>
												<? endif; ?>
												<? endforeach; ?>
												
												<a class="btn btn-sm btn-primary btnCambiarEstado" href="#" data-href="<?=$route;?>/form_cambiar_estado/<?=$row->id.'/'.$row->paquete_id;?>">Cambiar estado</a>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">Paquete:  </label>
											<div class="col-md-9 clearfix">
												<?=$combinacion->destino;?> - <?=$combinacion->paquete;?>
												<button data-href="<?=base_url();?>admin/reservas/combinaciones_disponibles/<?php echo $row->paquete_id;?>/<?php echo $row->id;?>/<?=$combinacion->id;?>" class="btn btn-primary btn-sm btnCambiarPaquete" data-rel="<?=$combinacion->id;?>" data-grupal="<?=$reserva->grupal;?>" data-codigo="<?=$row->code;?>" style="margin-left:10px;">Cambiar combinación</button>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Fecha del viaje:  </label>
											<div class="col-md-9 clearfix">
												Desde el <?=date('d/m/Y',strtotime($combinacion->fecha_inicio));?> al <?=date('d/m/Y',strtotime($combinacion->fecha_fin));?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Pasajeros:  </label>
											<div class="col-md-9 clearfix">
												<?=count($pasajeros);?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Transporte:  </label>
											<div class="col-md-9 clearfix">
												<?=$combinacion->transporte;?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Alojamiento:  </label>
											<div class="col-md-9 clearfix">
												<?=$combinacion->alojamiento;?>
											</div>
										</div>
										<? if($row->lugar_id == 4 && $row->fecha != '0000-00-00'): ?>
										<div class="form-group">
											<label class="col-md-3 control-label">Fechas de estadía:  </label>
											<div class="col-md-9 clearfix">
												Desde el <?=date('d/m/Y',strtotime($row->fecha));?>
											</div>
										</div>
										<? else: ?>
										<div class="form-group">
											<label class="col-md-3 control-label">Fechas de estadía:  </label>
											<div class="col-md-9 clearfix">
												Desde el <?=date('d/m/Y',strtotime($combinacion->fecha_checkin));?> al <?=date('d/m/Y',strtotime($combinacion->fecha_checkout));?>
											</div>
										</div>
										<? endif; ?>
										<div class="form-group">
											<label class="col-md-3 control-label">Habitación:  </label>
											<div class="col-md-9 clearfix">
												<?=$combinacion->habitacion;?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Regimen de comidas:  </label>
											<div class="col-md-9 clearfix">
												<?=$combinacion->regimen;?>
											</div>
										</div>
										
										<? if($row->lugar_id != 4): ?>
										<!--
										<div class="form-group">
											<label class="col-md-3 control-label">Lugar de Salida:  </label>
											<div class="col-md-9 clearfix">
												<?=$row->sucursal;?> | <?=$row->lugarSalida;?> | <?=$row->hora;?>
											</div>
										</div>
										-->
										<? endif; ?>
										
										<? if($row->lugar_id != 4): ?>
										<div class="form-group box-parada">
											<div class="row">
												<input type="hidden" name="paquete_parada_anterior" value="<?=@$row->paquete_parada_id;?>">
												<?php echo $this->admin->combo('paquete_parada_id', 'Lugar de salida', 's2', $row, $mis_paradas, 'id', 'nombrefull'); ?>
											</div>
										</div>
										<? endif; ?>

										<?php echo $this->admin->textarea('comentario', 'Comentarios', '', $row, false); ?>
							  
							  			
									</div>
								
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="pasajeros">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Listado de Pasajeros</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<? foreach($pasajeros as $p): ?>
										<div class="pasajero" data-rel="pasajero_<?=$p->numero_pax?>">
											<input type="hidden" id="pasajero_<?=$p->numero_pax?>" name="pasajero_<?=$p->numero_pax?>" value="">
											<input type="hidden" name="pasajero_id" value="<?=$p->id;?>">
											<div class="">
												<div class="widget-header">
												  <h3 style="margin-bottom:20px;">Pasajero <?=$p->numero_pax;?> <?=$p->numero_pax==1?' (responsable)':'';?></h3>
												</div>
											</div>
											<?php echo $this->admin->input('nombre', 'Nombre', '', $p, $required=false);?>
											<?php echo $this->admin->input('apellido', 'Apellido', '', $p, $required=false);?>
											<?php echo $this->admin->input('email', 'E-Mail', '', $p, $required=false);?>
											<?php 
											$aux = ($p->fecha_nacimiento && $p->fecha_nacimiento != '0000-00-00') ? $p->fecha_nacimiento : false; 
											if($aux != ''){
												$aux = explode('-',$aux);
												$p->fecha_nacimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
											}
											echo $this->admin->datepicker('fecha_nacimiento', 'Fecha Nacimiento', '', $p, $required=false,false,false);?>
											<?php echo $this->admin->combo('sexo', 'Sexo', 's2', $p, $sexos, 'id', 'nombre',false); ?>
											<?php echo $this->admin->combo('nacionalidad_id', 'Nacionalidad', 's2', $p, $paises, 'id', 'nombre',false); ?>
											<?php echo $this->admin->input('dni', 'DNI', 'onlynum', $p, $required=false, FALSE, "", "", '', 0,'',false,'number');?>
											<?php echo $this->admin->input('pasaporte', 'Pasaporte', '', $p, $required=false);?>
											<?php echo $this->admin->combo('pais_emision_id', 'País de Emisión', 's2', $p, $paises, 'id', 'nombre',false); ?>
											<?php 
											$aux = ($p->fecha_emision && $p->fecha_emision != '0000-00-00') ? $p->fecha_emision : false; 
											if($aux != ''){
												$aux = explode('-',$aux);
												$p->fecha_emision = $aux[2].'/'.$aux[1].'/'.$aux[0];
											}
											echo $this->admin->datepicker('fecha_emision', 'Fecha de Emisión', '', $p, $required=false,false,false);?>
											<?php 
											$aux = ($p->fecha_vencimiento && $p->fecha_vencimiento != '0000-00-00') ? $p->fecha_vencimiento : false;
											if($aux != ''){
												$aux = explode('-',$aux);
												$p->fecha_vencimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
											}
											echo $this->admin->datepicker('fecha_vencimiento', 'Fecha de Vencimiento', '', $p, $required=false,false,false);?>
											<?php echo $this->admin->input('celular_codigo', 'Celular Código', 'onlynum', $p, $required=false, FALSE, "", "", '', 0,'',false,'number');?>
											<?php echo $this->admin->input('celular_numero', 'Celular Número', 'onlynum', $p, $required=false, FALSE, "", "", '', 0,'',false,'number');?>
											<?php echo $this->admin->combo('dieta', 'Dieta', 's2', $p, $dietas, 'nombre', 'nombre',false); ?>
											<p style="margin: 15px 30px 5px;font-weight: bold;">Contacto de emergencia</p>
											<?php echo $this->admin->input('emergencia_nombre', 'Nombre', '', $p, $required=false);?>
											<?php echo $this->admin->input('emergencia_telefono_codigo', 'Codigo Area', '', $p, $required=false);?>
											<?php echo $this->admin->input('emergencia_telefono_numero', 'Numero', '', $p, $required=false);?>
											<br>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane " id="facturacion">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos de Facturación</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<?php echo $this->admin->input('f_nombre', 'Nombre', '', $facturacion, $required=false);?>
										<?php echo $this->admin->input('f_apellido', 'Apellido', '', $facturacion, $required=false);?>
										<?php 
										$aux = $facturacion->f_fecha_nacimiento; 
										if($aux != ''){
											$aux = explode('-',$aux);
											$facturacion->f_fecha_nacimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										echo $this->admin->datepicker('f_fecha_nacimiento', 'Fecha Nacimiento', '', $facturacion, $required=false,false,false);?>
										<div class="row">											
											<div class="col-md-3">
												<div class="form-group">
													<label class="col-md-3 control-label">CUIT </label>
												</div>											
											</div>
											
											<div class="col-md-9">
												<div class="form-group">
													<div class="col-md-2" style="padding:0;">
														<input type="number" id="f_cuit_prefijo" name="f_cuit_prefijo" class="form-control  onlynum " value="<?=@$facturacion->f_cuit_prefijo;?>" placeholder="" style="" onkeydown="max_length(this,2);" onkeyup="max_length(this,2);" min="1" max="99">
													</div>
													<label class="col-md-1 control-label text-center"> - </label>
													<div class="col-md-3" style="padding:0;">
														<input type="number" id="f_cuit_numero" name="f_cuit_numero" class="form-control  onlynum " value="<?=@$facturacion->f_cuit_numero;?>" placeholder="" style="" onkeydown="max_length(this,8);" onkeyup="max_length(this,8);" min="1" max="99999999">
													</div>
													<label class="col-md-1 control-label text-center"> - </label>
													<div class="col-md-2" style="padding:0;">
														<input type="number" id="f_cuit_sufijo" name="f_cuit_sufijo" class="form-control onlynum  " value="<?=@$facturacion->f_cuit_sufijo;?>" placeholder="" style="" onkeydown="max_length(this,1);" onkeyup="max_length(this,1);" min="0" max="9">
													</div>
												</div>											
											</div>
										</div>
										<?php echo $this->admin->combo('f_nacionalidad_id', 'Nacionalidad', 's2', $facturacion, $paises, 'id', 'nombre',false); ?>
										<?php echo $this->admin->combo('f_residencia_id', 'Residencia', 's2', $facturacion, $paises, 'id', 'nombre',false); ?>
										<?php echo $this->admin->input('f_ciudad', 'Ciudad', '', $facturacion, $required=false);?>
										<?php echo $this->admin->input('f_domicilio', 'Domicilio', '', $facturacion, $required=false);?>
										<?php echo $this->admin->input('f_numero', 'Número', 'onlynum', $facturacion, $required=false, FALSE, "", "", '', 0,'',false,'number');?>
										<?php echo $this->admin->input('f_depto', 'Depto', '', $facturacion, $required=false);?>
										<?php echo $this->admin->input('f_cp', 'CP', '', $facturacion, $required=false);?>
										
										
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane <?=@$_GET['tab']=='adicionales'?'active':'';?>" id="adicionales">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Adicionales contratados</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-info"><strong>Nota: </strong>El agregado y/o eliminación de adicionales sobre la reserva implicará verificación de cupos disponibles de los mismos y generación de movimientos en las cuentas corrientes de los pasajeros.</div>
									</div>
									<div class="col-md-12">
										<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
										<thead>
											<tr>
												<th>Nombre Adicional</th>
												<th>Monto</th>
												<th style="text-align:center;">Contratado</th>
												<th>Opciones</th>
											</tr>
										</thead>
										<tbody>
											
										<? foreach($paquete_adicionales as $ad): ?>
											<tr class="<?php echo alternator('odd', 'even');?> tr_adicional">
												<td class="nombre_adicional"><?=$ad->adicional;?></td>
												<td><?=$ad->precio_usd?'USD':'ARS';?> <?=$ad->v_total*$row->pasajeros;?></td>
												<td style="text-align:center;">
													<span class="label label-<?=in_array($ad->id,$row->adicionales_reservados) ? 'success' : 'danger';?>"><?=in_array($ad->id,$row->adicionales_reservados) ? 'SI' : 'NO';?></span>
												</td>
												<td>
													<? if(in_array($ad->id,$row->adicionales_reservados)): ?>
														<a href="#" class="btn btn btn-sm btn-danger lnkBorrarAd" data-href="<?=$route;?>/eliminar_adicional/<?=$row->id;?>/<?=$ad->id;?>">Eliminar</a>
													<? else: ?>
														<a href="#" class="btn btn btn-sm btn-primary lnkAgregarAd" data-href="<?=$route;?>/agregar_adicional/<?=$row->id;?>/<?=$ad->id;?>">Agregar</a>
													<? endif; ?>
												</td>
											</tr>
											<? endforeach; ?>
											<? if(count($paquete_adicionales)==0): ?>
												<tr><td colspan="4">La reserva no posee adicionales contratados.</td></tr>
											<? endif; ?>
										</tbody>
										</table>
									</div>
								</div>
							</div>
									
						</div>
					</div>
					
					<div class="tab-pane <?=@$_GET['tab']=='informes'?'active':'';?>" id="informes">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Informes de Pago</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
										<thead>
											<tr>
												<th>Medio de Pago</th>
												<th>Fecha</th>
												<th>Monto</th>
												<th>Comprobante</th>
												<th class="text-center">Movimiento en Cta Cte</th>
											</tr>
										</thead>
										<tbody>
											<? foreach($informes_pago as $inf): ?>
											<tr class="<?php echo alternator('odd', 'even');?> tr_informe" >
												<td><?=$inf->banco.'<br>'.$inf->tipo_pago;?></td>
												<td><?=date('d/m/Y',strtotime($inf->fecha_pago)).' '.$inf->hora_pago;?></td>
												<td><?=$inf->tipo_moneda;?> <?=number_format($inf->monto_pago,2,',','.');?></td>
												<td>
													<? if($inf->comprobante && file_exists('./uploads/reservas/'.$inf->reserva_id.'/'.$inf->comprobante)): ?>
														<a target="_blank" href="<?=base_url().'uploads/reservas/'.$inf->reserva_id.'/'.$inf->comprobante;?>">
															<?=$inf->comprobante;?>
														</a>
													<? endif; ?>
												</td>
												<td class="text-center">
													<? if($inf->informe_id): ?>
														<label class="label label-success" title="Pago verificado"><i class="glyphicon glyphicon-ok"></i></a>
													<? else: ?>
														<? if( perfil()=='ADM' || perfil()=='SUP' ): ?>
															<a class="btn btn-xs btn-primary lnkCrearMov" rel="<?=$inf->id;?>" data-concepto="<?=@$inf->concepto->nombre;?>" data-monto="<?=$inf->monto_pago;?>" data-moneda="<?=$inf->tipo_moneda;?>">Crear</a>
														<? endif; ?>
													<? endif; ?>
												</td>
											</tr>
											<? endforeach; ?>
											<? if(count($informes_pago)==0): ?>
												<tr><td colspan="5">La reserva no posee informes de pago realizados.</td></tr>
											<? endif; ?>
										</tbody>
										</table>
									</div>
								</div>
							</div>
									
						</div>
					</div>
					
					<div class="tab-pane <?=@$_GET['tab']=='cta_cte'?'active':'';?>" id="cta_cte">
						<?=@$cuenta_corriente;?>
					</div>
					
					<div class="tab-pane <?=@$_GET['tab']=='vouchers'?'active':'';?>" id="vouchers">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">
								Vouchers del Viaje
								<a data-href="<?=$route;?>/enviar_vouchers/<?=$row->id;?>" href="#" class="btn btn-primary pull-right lnkVoucher <?=(count($mis_vouchers) > 0)?'':'hidden';?>" style="margin-top: -5px;">Enviar vouchers al pasajero</a>
							  </h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-info">
											<p>La cantidad de vouchers a adjuntar para este viaje es <strong style="font-size:14px;"><?=$paquete->cantidad_vouchers;?></strong></p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 seleccion">										
										<div class="form-group">
											<label class="col-md-4 control-label" style="padding: 0;">Elegir Voucher</label>
											<div class="col-md-8" style="padding: 0;">
												<input type="file" id="file_voucher" name="file_voucher" class="" data-style="fileinput" data-inputsize="medium" value="" style="width: 235px;margin-top: 0;">
											</div>
										</div>
									</div>
									
									<div class="col-md-1 seleccion"> 
										<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-voucher" style="margin-top: 4px;">
									</div>
								</div>
							</div>
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">
								Vouchers asociados
								<a href="<?=site_url('reservas/vouchers/'.encriptar($row->code));?>" target="_blank" class="btn btn-primary pull-right" style="margin-top: -5px;"><i class="glyphicon glyphicon-download-alt"></i> Descargar ZIP</a>
							  </h3>
							</div>
							<div class="widget-content">
							  <div class="row">
								<div class="col-md-12">
									<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
									  <thead>
										<tr>
										  <?php echo $this->admin->th('archivo', 'Voucher', false);?>
										  <?php echo $this->admin->th('timestamp', 'Fecha de carga', false);?>
										  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
										</tr>
									  </thead>
									  <tbody id="tblVouchers">
										<?php foreach ($mis_vouchers as $r): ?>
											<?=vouchers_row($r);?>
										<?php endforeach; ?>
										<tr class="no_vouchers <?=(count($mis_vouchers) == 0)?'':'hidden';?>">
										  <td colspan="3" align="center" style="padding:30px 0;">
											No hay vouchers cargados para esta reserva.
										  </td>
										</tr>
									  </tbody>
									</table>
								</div>
							  </div>
							</div>
						</div>
					</div>
					
					
					<div class="tab-pane <?=@$_GET['tab']=='historial'?'active':'';?>" id="historial">
						<?=@$historial_reserva;?>
					</div>
					
				</div>
			</div>
			
				<div class="widget-content">
					<div class="row">
						<div class="col-md-12">
							<div class="widget-footer">
								<div class="actions">
									<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
									  <input type="submit" value="Grabar" class="btn btn-success" name="btnvolver">
									  <input type="button" value="Volver" class="btn btn-default" onclick="javascript: location.href='<?=site_url('admin/reservas/paquete/'.$row->paquete_id);?>';">
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
		
	</form>
	
<?php echo $footer;	?>
	
	<script src="<?=base_url();?>media/admin/assets/js/jquery.timeMask.js"></script>
	<style>.datepicker{z-index: 10000 !important;}</style>

	<script>
	$(document).ready(function(){
		$('body').on('submit','#formEdit',function(e){
			$.each($('.pasajero'),function(i,el){
				var rel = $(el).attr('data-rel');
				var pax = $('.pasajero[data-rel='+rel+'] input, .pasajero[data-rel='+rel+'] select').serialize();
				$('#'+rel).val(pax);
			});
		});

		$('body').on('click','.lnkBorrarAd',function(e){
			e.preventDefault();
			
			var me = $(this);
			var url = me.attr('data-href');
			var nombre = me.closest('.tr_adicional').find('.nombre_adicional').text();
			
			 bootbox.confirm("Esta seguro que desea eliminar el adicional <b>"+nombre+"</b> de la reserva?", function(result){
				if (result) {
					$.post(url,function(data){
						  if(data.status=='SUCCESS'){
							  bootbox.alert(data.msg,function(){
								  location.href = data.redirect;
							  });							  
						  }
					  },'json');
				}
			  });
		});		
		$('body').on('click','.lnkAgregarAd',function(e){
			e.preventDefault();
			
			var me = $(this);
			var url = me.attr('data-href');
			var nombre = me.closest('.tr_adicional').find('.nombre_adicional').text();
			
			me.attr('disabled',true);

			 bootbox.confirm("Esta seguro que desea agregar el adicional <b>"+nombre+"</b> a la reserva?", function(result){
			 	me.attr('disabled',false);
				if (result) {
					$.post(url,function(data){
						  if(data.status=='SUCCESS'){
							  bootbox.alert(data.msg,function(){
								  location.href = data.redirect;
							  });
						  }
						  else{
							  bootbox.alert(data.msg);
						  }
					  },'json');
				}
			  });
		});		

		$('.datepicker').attr('readonly',true);
		
		if($('#fecha_vencimiento').val() == '0000-00-00'){
			$('#fecha_vencimiento').datepicker('update',new Date()).val('')
		}
		if($('#fecha_nacimiento').val() == '0000-00-00'){
			$('#fecha_nacimiento').datepicker('update',new Date()).val('')
		}
		if($('#fecha_emision').val() == '0000-00-00'){
			$('#fecha_emision').datepicker('update',new Date()).val('');
		}

		$('.btnEsperarPago').click(function(){
			var dialog = bootbox.dialog({
				title: '¿Confirma que desea poner la reserva en estado POR ACREDITAR?',
				message: '<div class="modal-body">'+
							'<div class="bootbox-body">'+
								'<form class="bootbox-form">'+
									'<label for="fecha_extendida" style="width: 15%;display: inline-block;">Fecha: </label><input type="text" id="fecha_extendida" name="fecha_extendida" class="form-control datepicker " value="" readonly="readonly" style="width: 75%;display: inline-block;"><!--<input style="    vertical-align: middle;width: 75%;display: inline-block;" name="fecha_extendida" id="fecha_extendida" class="bootbox-input bootbox-input-date form-control" autocomplete="off" type="date">-->'+
									'<br><br><label for="hora_extendida" style="width: 15%;display: inline-block;">Hora: </label><input style="    vertical-align: middle;width: 75%;display: inline-block;" name="hora_extendida" id="hora_extendida" class="bootbox-input form-control" placeholder="hh:mm">'+
								'</form>'+
							'</div><script>$("#hora_extendida").timeMask();$(".datepicker").datepicker({language: "es",format:"dd/mm/yyyy",startDate:"<?=date('d/m/Y');?>"});<\/script>'+
						'</div>',
				buttons: {
					cancel: {
						label: "Cancelar",
						className: 'btn-default'
					},
					ok: {
						label: "OK",
						className: 'btn-info',
						callback: function(){
							var fecha = $('#fecha_extendida').val();
							var hora = $('#hora_extendida').val();

							$.post('<?=site_url('admin/reservas/por_acreditar/'.$row->id);?>',{fecha: fecha,hora: hora},function(data){
								location.href = location.href;
							});
						}
					}
				}
			});

			bootbox.prompt({
				title: "",
				inputType: 'date',
				callback: function (result) {
					if (result){
						
					}
					else{
						return false;
					}
				}
			});
		});

		$('#btnConfirmarDisponibilidad').click(function(e){
			e.preventDefault();
			var me = $(this);
			me.attr('disabled',true);

			bootbox.confirm('¿Confirma que existe disponibilidad para esta reserva?', function(result){
				if (result){
					location.href = '<?=site_url('admin/reservas/confirmar_disponibilidad/'.$row->id);?>';
				}
				else{
					me.attr('disabled',false);
				}
			});
		});

		$('body').on('change','#informe_id',function(){
			var me = $(this);
			var concepto = me.find('option:selected').data('concepto');
			var monto = me.find('option:selected').data('monto');
			var moneda = me.find('option:selected').data('moneda');
			habilitar_pago(concepto,monto,moneda);
		});
		
		$('body').on('click','.lnkCrearMov',function(e){
			e.preventDefault();
			var me = $(this);
			var concepto = me.data('concepto');
			var monto = me.data('monto');
			var moneda = me.data('moneda');
			habilitar_pago(concepto,monto,moneda);
			$('#informe_id').val(me.attr('rel'));
		});
		
		//agrega nuevo voucher en tabla
		  $('body').on('click','.btn-add-voucher', function(e){
			e.preventDefault();

			var me = $(this);
			me.val('Agregando...');

			  if($('#file_voucher').val().trim() == ''){
			  		me.val('Agregar');
				  bootbox.alert('Debes seleccionar el archivo del voucher.');
				  return false;
			  }
			  
			  cargar_voucher();
		  });
		  
		  //borrar voucher 
		  $('body').on('click','.btn-delete-voucher', function(e){
			  e.preventDefault();
			  var me = $(this);
			  var url = me.attr('data-href');
			  
			  bootbox.confirm("Esta seguro que desea borrar el voucher?", function(result){
				if (result) {
					$.post(url,function(data){
						  if(data){
							  me.closest('tr').slideUp(function(){
							  	$(this).remove();
					  			show_hide_vouchers();
							  });

						  }
					  });
				}
			  });
		  });
		  
		  //enviar voucher 
		  $('body').on('click','.lnkVoucher', function(e){
			  e.preventDefault();
			  var me = $(this);
			  var url = me.attr('data-href');
			  
			  bootbox.confirm("Esta seguro que desea enviar el mail de vouchers al pasajero?", function(result){
				if (result) {
					$.post(url,function(data){
						  if(data.status == 'error'){
							  bootbox.alert(data.msg);
							  return false;
						  }
						  else{
						  	bootbox.alert('Se ha enviado el mail de vouchers al pasajero.');
							  return false;
						  }
					  },'json');
				}
			  });
		  });
		  
		  //lnkBorrarMov 
		  $('body').on('click','.lnkBorrarMov', function(e){
			  e.preventDefault();
			  var me = $(this);
			  var url = me.attr('data-href');
			  
			  bootbox.confirm("Esta seguro que deseas eliminar el informe de pago del pasajero?", function(result){
				if (result) {
					$.post(url,function(data){
						  if(data){
							  me.closest('.tr_informe').hide();
							  bootbox.alert('Se ha eliminado el informe de pago del pasajero.');
							  return false;
						  }
					  });
				}
			  });
		  });
		  
			//abre popup para cambiar de estado
		  $("body").on('click','.btnCambiarEstado',function(e) {
				e.preventDefault();
				var me = $(this);
				var url = me.attr('data-href');
				$.post(url,function(data){
					if(data.view){
						var dialog = bootbox.dialog({
							title: 'Cambio de estado de la reserva Cód. <?=$row->code;?>',
							message: data.view,
							buttons: {
								cancel: {
									label: "Cancelar",
									className: 'btn-danger',
									callback: function(){
									}
								},
								ok: {
									label: "OK",
									className: 'btn-success',
									callback: function(){
										//si eligió anulada que haya puesto un motivo
										if($('#estado_id').val() == 5 && $('#motivo').val() == ''){
											bootbox.alert('Debes ingresar el motivo de anulación.');
											return false;
										}
										if($('#estado_id').val() == 5 && $('#fecha_baja').val() == ''){
											bootbox.alert('Debes ingresar la fecha de cancelación.');
											return false;
										}
										
										$.fancybox.showLoading();

										var nuevo_estado = $('#estado_id').val();
										var furl = $('#fCambioEstado').attr('action');
										furl = furl+'/'+nuevo_estado;
										
										if (nuevo_estado != '') {
											$.post(furl,$('#fCambioEstado').serialize(),function(d){
												$.fancybox.hideLoading();

												if(d.status == 'ok'){
													bootbox.alert('El cambio de estado se realizó correctamente.',function(){
														location.href=location.href;
													});
												}
												if(d.error == 'sin_cupo'){
													bootbox.alert('No hay cupo disponible para hacer el cambio de estado.',function(){
														bootbox.hideAll();
													});
													return false;
												}
											},'json');
										}
										else {
											$.fancybox.hideLoading();

											$('#fCambioEstado .alert-error').show();
											return false;
										}
									}
								}
							}
						});
					}
				},"json");
			});


		  $("#vendedornombre").prop("disabled", true);
	});
	
	
	function habilitar_pago(concepto,monto,moneda){
		$('#lnkTabCta').trigger('click');
		$('#agregar-mov').show();
		$('#concepto').val(concepto);
		$('#concepto').trigger('change');
		$('#moneda').val(moneda);
		$('#moneda').trigger('change');
		monto = addCommas(monto);
		$('#haber').val(monto);
	}
		
	function addCommas(nStr)
	{
		nStr += '';
		var x = nStr.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}

	function cargar_voucher(){
		var form = $('#formEdit')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
		formData.append('image', $('input[type=file]')[0].files[0]); 
		
		$.ajax({
			url: '<?=$route;?>/grabar_voucher',
			data: formData,
			type: 'POST',
			dataType: "json",
			contentType: false,
			processData: false,
			success: function(data) {
				if(data.row){
					 	$('#tblVouchers').prepend(data.row);
					  	$('.btn-add-voucher').val('Agregar');
					  	show_hide_vouchers();
				  }

				  if(data.status == 'error'){
				  	bootbox.alert(data.msg);
				  	$('.btn-add-voucher').val('Agregar');
				  }
			}
		});

	}

	function show_hide_vouchers(){
		if($('#tblVouchers tr:not(.no_vouchers)').length){
	  		$('.no_vouchers').addClass('hidden');
	  		$('.lnkVoucher').removeClass('hidden');
	  	}
	  	else{
	  		$('.no_vouchers').removeClass('hidden');	
	  		$('.lnkVoucher').addClass('hidden');	
	  	}
	}
	</script>
	