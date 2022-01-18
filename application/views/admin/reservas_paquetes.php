<?php echo $header;	?>

<style type="text/css">
	.bootbox.modal { z-index: 10000; }
</style>

				<?php if (@$saved): ?>
					<br/>
					<div class="alert alert-success fade in"> 
						<i class="icon-remove close" data-dismiss="alert"></i> 
						<strong>&iexcl;Operación completada!</strong> Los datos fueron guardados con éxito.
					</div>
				<?php endif; ?>
				<?php if (!empty($error)): ?>
					<br/>
					<div class="alert alert-danger fade in"> 
						<i class="icon-remove close" data-dismiss="alert"></i> 
						<strong>Error!</strong> <?=$this->session->flashdata('error');?>
					</div>
				<?php endif; ?>
				<?php if (!empty($warning)): ?>
					<br/>
					<div class="alert alert-warning fade in"> 
						<i class="icon-remove close" data-dismiss="alert"></i> 
						<strong>&iexcl;Atención!</strong> <?=$this->session->flashdata('error');?>
					</div>
				<?php endif; ?>
	
				<!--=== Page Header ===-->
				<div class="page-header">
					<div class="page-title">
						<h3><?=$page_title;?></h3>
						<span>Listado de reservas del paquete.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-vertical page-stats" style="width:400px">
							<div class="row form-group">
								<label class="col-md-4 control-label">Estado:</label>
								<div class="input-group col-md-8"> 
									<select class="form-control" name="estado_id" onchange="javascript: location.href='<?=$route."/paquete/".$paquete_id;?>/'+this.value;">
										<option value="" <?=(!isset($estado_id) || @$estado_id == '')?'selected':'';?>>Todos</option>
										<?php foreach($estados as $estado){?>
											<option style="color:#<?=$estado->color;?>;" value="<?=$estado->id;?>" <?php if(@$estado_id == $estado->id) echo 'selected';?>><?php echo $estado->nombre;?></option>
										<?php } ?>
									</select>												
								</div>						
							</div>	
							
							<div class="row form-group">
								<label class="col-md-4 control-label">Lugar de Salida:</label>
								<div class="input-group col-md-8"> 
									<select class="form-control" name="sucursal_id" onchange="javascript: $('#frmSearch').submit();">
										<option value="" <?=(!isset($sucursal_id) || @$sucursal_id == '')?'selected':'';?>>Todas</option>
										<?php foreach($lugares as $l){?>
											<option value="<?=$l->id;?>" <?php if(@$sucursal_id == $l->id) echo 'selected';?>><?php echo $l->nombre;?></option>
										<?php } ?>
									</select>												
								</div>						
							</div>
						
							<div class="row form-group">
								<label class="col-md-4 control-label">Buscar:</label>
								<div class="input-group col-md-8"> 
									<input type="text" name="keywords" class="form-control" value="<?php echo @$keywords;?>"> 
									<input type="hidden" id="sort" name="sort" value="<?php echo isset($sort)?$sort:"";?>"/>
									<input type="hidden" id="sortType" name="sortType" value="<?php echo isset($sortType)?$sortType:"ASC";?>"/>
									<span class="input-group-btn"> 
										<button class="btn btn-default" type="submit">Buscar</button> 
									</span>
								</div>
							</div>
							
					</form>					
				</div>
				<!-- /Page Header -->

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?></h4>
							</div>
							
							<?
							/*
							Fecha de Salida	Cantidad reservas	
							Cantidad confirmadas	Cupo disponible	Saldo a cobrar	Opciones
							*/
							?>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('codigo', 'Cód Reserva', true);?>
											<?php echo $this->admin->th('PA.apellido', 'Usuario', true);?>
											<?php echo $this->admin->th('fecha_reserva', 'Fecha de reserva', true);?>
											<?php echo $this->admin->th('pasajeros', 'Pasajeros', true, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('estado_id', 'Estado', true);?>
											<?php echo $this->admin->th('saldo', 'Saldo', true, array('text-align'=>'center'));?>
											<!--
											<th id="cell_enviar_mail" style="text-align:center; width:90px;">
												<a href="#" class="" id="enviar_mail">Mailing</a>
												<input type="checkbox" id="check_all" style="margin:0 0 0 2px;" class="default"/>
											</th>
											-->
											<?php echo $this->admin->th('', 'Alertas', false, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
										</tr>
									</thead>
									<tbody>
										<?php
										 foreach($data as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<td>
												<?=$row->codigo;?>
												<? if($row->codigo_grupo): ?>
													<br><span class="label label-info reserva-grupo">Grupo <?=$row->codigo_grupo;?></span>
												<? endif;?>

												<? if(@$row->alarmas->tiene_adicionales){ ?>
													<br><button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=adicionales');?>'" type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Tiene adicionales contratados" style="margin-top:5px"><i class="glyphicon glyphicon-tags"></i></button>
												<? } ?>

											</td>
											<?php 
											$tipo_dni = '';
											$nro_dni = '';
											if($row->nacionalidad == 'Argentina'){
												$tipo_dni = 'DNI';
												$nro_dni = $row->dni;
											}
											else{
												$tipo_dni = 'Pasaporte';
												$nro_dni = @$row->pasaporte;	
											}
											echo $this->admin->td($row->nombre.' '.$row->apellido.'<br>'.$row->email.'<br>'.$tipo_dni.': '.$nro_dni.'<br>Tel: '.$row->celular_codigo.' '.$row->celular_numero);?>
											<td align="left" data-fecha="<?=$row->fecha;?>">
												<?=$row->fecha.' '.$row->hora.' hs';?>
												<br>
												<? //icono de calendario para indicar que hay pagos pendientes del usuario a 15 o 30 dias del viaje
												$days = ($row->en_avion) ? 30 :15;
												$fecha1 = $row->fechaSalida; 
												$fecha2 = date('Y-m-d',strtotime(date('Y-m-d').' +'.$days.' days')); 
												$dias_transcurridos = dias_transcurridos($fecha1,$fecha2);
												if($dias_transcurridos == 0 && $days == 30 && $row->saldo > 0){ //avion ?>
													<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="El usuario registra deuda a 30 días del viaje"><i class="glyphicon glyphicon-calendar"></i></button></td>
												<? }
												else if($dias_transcurridos == 0 && $days == 15 && $row->saldo > 0){ //micro ?>
													<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="El usuario registra deuda a 15 días del viaje"><i class="glyphicon glyphicon-calendar"></i></button></td>
												<? } ?>
											</td>
											<td style="text-align:center;"><?=$row->pasajeros;?></td>
											<td>
												<label class="label label-primary" style="background-color:#<?=$row->color;?>">
													<?=$row->estado;?>
												</label>
											</td>
											<td style="text-align:center;" data-saldo-viaje="<?=$row->precio_usd?$row->saldo_viaje_usd:$row->saldo_viaje;?>"><?=$row->precio_usd?'USD':'ARS';?> <?=number_format($row->precio_usd?$row->saldo_usd:$row->saldo,2,',','.');?></td>
											<!--
											<td style="text-align:center;">
												<input type="checkbox" class="envios default" name="envios_mailing[]" value="<?=$row->usuario_id;?>" />
											</td>
											-->
											<td style="text-align:center;">
												<? if(@$row->alarmas->completar_datos_pax):
													//si no completo los datos de pasajeros y faltan 2 dias para la fecha limite de completar ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Faltan completar datos de pasajeros"><i class="glyphicon glyphicon-user"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->informes): 
													//si tiene informes de pago sin verificar ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=informes');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay informes de pago sin verificar"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->fecha_limite_pago_completo): 
													//si tiene pagos pendientes alcanzada la fecha limite ?>
													<button onclick="return false; " type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay pagos pendientes"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												
												
												<? if(@$row->alarmas->falta_factura_proveedor): 
													//si el ultimo documento del usuario es un recibo muestro alarma ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=cta_cte');?>'" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Falta factura del Proveedor"><i class="glyphicon glyphicon-file"></i></button>
												<? endif; ?>
												
												<? //solo para reservas de vaijes que no sean operados por BUENAS VIBRAS
												if(@$row->alarmas->faltan_cargar_vouchers): 
													//si llego a la fecha limite para mostrar alerta de carga de vouchers ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=vouchers');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay vouchers sin cargar"><i class="glyphicon glyphicon-tags"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->alerta_llamar_pax){ ?>
													<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Llamar al pasajero"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if(@$row->alarmas->alerta_reestablecida){ ?>
													<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-html="true" data-placement="top" title="Restablecida / Extendida"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if(@$row->alarmas->alerta_contestador){ ?>
													<button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Contestador / Rellamar"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
											
												<? if(@$row->alarmas->alerta_cupos_vencidos){ ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="La reserva tiene cupos de transporte vencidos"><i class="glyphicon glyphicon-time"></i></button>
												<? } ?>

												
												
											</td>
											<td>
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/edit/<?=$row->id;?>?tab=cta_cte"><i class="glyphicon glyphicon-usd" style="font-size:12px;"></i> Cuenta Corriente</a>
													
											    <div class="btn-group">
												  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Opciones <span class="caret"></span>
												  </button>
												  <ul class="dropdown-menu" style="right:0;left:inherit;text-align:right;">
													<li><a href="<?=$route;?>/edit/<?=$row->id;?>">Editar</a></li>
													<li><a href="<?=$route;?>/edit/<?=$row->id;?>?tab=historial">Ver Historial</a></li>
													<li><a href="#" rel="<?=$route;?>/generarCupon/<?=$row->id;?>" class="cupon">Enviar Mail Datos Reserva</a></li>
													<li><a href="#" rel="<?=$route;?>/generar_voucher_pago/<?=$row->id;?>" class="sendVoucher" >Enviar Mail Pago Completo</a></li>
												  </ul>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
										<tr>
											<td colspan="8" align="center" style="padding:30px 0;">
												No se encontraron resultados.
											</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
								
								<div class="row">
									<div class="table-footer">
										<div class="col-md-6">
										</div>
										<div class="col-md-6">
											<?php echo $pages; ?>
										</div>
									</div>
								</div>
								
							</div> <!-- /.col-md-12 -->
							
						</div>
					
					</div> <!-- /.col-md-12 -->
					<!-- /Example Box -->
				</div> <!-- /.row -->
				<!-- /Page Content -->
		
				<script>
				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip();

					$('#tipo').change(function(){
						$('#frmSearch').submit();
					});
					
					$(".cupon").click(function(e){
						e.preventDefault();
						var me = this;
						var url = $(this).attr('rel');
						
						bootbox.confirm("Esto generará el envío del mail de confirmación al pasajero. <br>Está seguro?", function(result){
							if (result) {
								$.post(url,function(data){
									if(data){
										bootbox.alert(data);
										return false;
									}
								});
							}
						});
					});
					
					$(".sendVoucher").click(function(e){
						e.preventDefault();
						var me = this;
						var url = $(this).attr('rel');
						
						bootbox.confirm("Esto generará el envío del voucher de pago completo al pasajero. <br>Está seguro?", function(result){
							if (result) {
								$.post(url,function(data){
									if(data){
										bootbox.alert(data);
										return false;
									}
								});
							}
						});
					});
					
					
		
				});
				</script>

<?php echo $footer;?>