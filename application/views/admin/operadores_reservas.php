<?php echo $header;	?>

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
						<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
						<h5>Paquete <?=$paquete->codigo." - ".$paquete->nombre;?></h5>
						<span>El listado muestra la totalidad de reservas del paquete</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-vertical page-stats" style=" padding-bottom: 0; width:400px">
							<div class="row form-group">
								<label class="col-md-4 control-label">Estado:</label>
								<div class="input-group col-md-8"> 
									<select class="form-control" name="estado_id" onchange="javascript: location.href='<?=$route."/reservas/".$operador->id.'/'.$paquete_id;?>/'+this.value;">
										<option value="" <?=(!isset($estado_id) || @$estado_id == '')?'selected':'';?>>Todos</option>
										<?php foreach($estados as $estado){?>
											<option style="color:#<?=$estado->color;?>;" value="<?=$estado->id;?>" <?php if(@$estado_id == $estado->id) echo 'selected';?>><?php echo $estado->nombre;?></option>
										<?php } ?>
									</select>												
								</div>						
							</div>	
							
							<? if(false): ?>
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
							<? endif; ?>
						
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

						<button id="" class="btn btn-primary pull-right" onclick="javascript: location.href = '<?=$route;?>/paquetes/<?=$operador->id;?>';" type="button" style="clear:both;">Volver a Paquetes</button>		
			
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
								<table id="tblOp" cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<th><input type="checkbox" class="default" name="select_all" value="1" id="select-all"></th>
											<?php echo $this->admin->th('codigo', 'Cod Reserva', true);?>
											<?php echo $this->admin->th('nombre', 'Usuario', true);?>
											<?php echo $this->admin->th('fecha_reserva', 'Fecha de reserva', true);?>
											<?php echo $this->admin->th('S.nombre', 'Lugar de Salida', true);?>
											<?php echo $this->admin->th('estado_id', 'Estado de reserva', true, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('', 'Costo del viaje', false, array('text-align'=>'center'));?>
											<? if(false): ?>
												<?php echo $this->admin->th('', 'Alertas', false, array('text-align'=>'center'));?>
											<? endif; ?>
											<?php //echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach($data as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<td class="id_cell" data-ref="<?=$row->id;?>"><input <?=($row->registro_de_costo || $row->estado_id!=4)?'disabled':'';?> type="checkbox" class="default" name="" value="1"></td>
											<?php echo $this->admin->td($row->codigo);?>
											<?php echo $this->admin->td($row->nombre.' '.$row->apellido.'<br>'.$row->email.'<br>'.$row->celular_codigo.' '.$row->celular_numero);?>
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
											<?php echo $this->admin->td($row->lugar_salida);?>
											<td align="center">
												<label class="label label-primary" style="background-color:#<?=$row->color;?>">
													<?=$row->estado;?>
												</label>
											</td>
											<td align="center">
												<span style="margin-right:10px;">
												<?=$row->precio_usd?'USD':'ARS';?> <?=number_format($row->estado_id==4?$row->saldoAPagar:'0.00',2,',','.');?>
												</span>
												<label class="label label-<?=$row->registro_de_costo?'success':'warning';?>">
													<?=$row->registro_de_costo?'REGISTRADO':'PENDIENTE';?>
												</label>												
											</td>
											<!--
											<td style="text-align:center;">
												<input type="checkbox" class="envios default" name="envios_mailing[]" value="<?=$row->usuario_id;?>" />
											</td>
											-->
											<? if(false): ?>
											<td style="text-align:center;">
												<? if($row->alarmas->completar_datos_pax):
													//si no completo los datos de pasajeros y faltan 2 dias para la fecha limite de completar ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Faltan completar datos de pasajeros"><i class="glyphicon glyphicon-user"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->informes): 
													//si tiene informes de pago sin verificar ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=informes');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay informes de pago sin verificar"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->falta_factura_proveedor): 
													//si el ultimo documento del usuario es un recibo muestro alarma ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=cta_cte');?>'" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Falta factura del Proveedor"><i class="glyphicon glyphicon-file"></i></button>
												<? endif; ?>
												
												<? //solo para reservas de vaijes que no sean operados por BUENAS VIBRAS
												if($row->alarmas->faltan_cargar_vouchers): 
													//si llego a la fecha limite para mostrar alerta de carga de vouchers ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=vouchers');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay vouchers sin cargar"><i class="glyphicon glyphicon-duplicate"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->alerta_llamar_pax){ ?>
													<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Llamar al pasajero"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if($row->alarmas->alerta_reestablecida){ ?>
													<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-html="true" data-placement="top" title="Restablecida / Extendida"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if($row->alarmas->alerta_contestador){ ?>
													<button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Contestador / Rellamar"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
											
											</td>
											<? endif; ?>
											<!--<td>
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/edit/<?=$row->id;?>?tab=cta_cte"><i class="glyphicon glyphicon-usd" style="font-size:12px;"></i> Cuenta Corriente</a>
											</td>-->	
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
										<tr>
											<td colspan="7" align="center" style="padding:30px 0;">
												No se encontraron resultados.
											</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
								
								<div class="row">
									<div class="col-md-12">
										<br>Para registrar el costo del viaje de las <strong>reservas pendientes</strong> seleccionadas hacé <button id="btnRegistrar" class="btn btn-sm btn-primary" type="button" disabled>click acá</button>
									</div>
								</div>
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
				var ids = [];
				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip();

					$('#tipo').change(function(){
						$('#frmSearch').submit();
					});
					
					$(".cupon").click(function(e){
						e.preventDefault();
						var me = this;
						var url = $(this).attr('rel');
						
						bootbox.confirm("Esto generará el envío del voucher al pasajero. <br>Está seguro?", function(result){
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
					
					// Handle click on "Select all" control
					$('#select-all').on('click', function(){
						// Get all rows with search applied
						//var rows = table.rows({ 'search': 'applied' }).nodes();
						// Check/uncheck checkboxes for all rows in the table
						//$.each($('input[type="checkbox"]').prop('checked', this.checked);
						var me = this;
						console.log(me.checked);
						console.log($(this).prop('checked'));
						//ids.splice();
						ids = ids.unique();
						if ($(this).prop('checked')) {
							$('.id_cell').each(function(index, obj){
								console.log(obj);
								if(!$(obj).find('input[type=checkbox]').prop('disabled')){
									$(obj).find('input[type=checkbox]').prop('checked', me.checked);
									ids.push($(obj).data('ref'));
								}
							});
						}
						else{
							$('.id_cell').each(function(index, obj){
								if(!$(obj).find('input[type=checkbox]').prop('disabled')){
									$(obj).find('input[type=checkbox]').prop('checked', me.checked);
								}
							});
							ids = ids.splice();
						}
						
						if (ids.length) {
							$('#btnRegistrar').removeAttr('disabled');
						}
						else {
							$('#btnRegistrar').attr('disabled', 'disabled');	
						}
						
						ids = ids.unique();
					});

					// Handle click on checkbox to set state of "Select all" control
					$('#tblOp tbody').on('change', 'input[type="checkbox"]', function(){
						var id = $(this).parent().data('ref');
						console.log(id);
							// If checkbox is not checked
							console.log(this.checked);
							if(!this.checked){
								var el = $('#select-all').get(0);
								// If "Select all" control is checked and has 'indeterminate' property
								if(el && el.checked && ('indeterminate' in el)){
									// Set visual state of "Select all" control
									// as 'indeterminate'
									el.indeterminate = true;
								}

								index = ids.indexOf(id);
								console.log(index);
								if (index > -1) {
									ids.splice(index, 1);
								}
							}
							else {
								ids.push(id);
							}

							if (ids.length) {
								$('#btnRegistrar').removeAttr('disabled');
							}
							else {
								$('#btnRegistrar').attr('disabled', 'disabled');	
							}
							
							ids = ids.unique();
						});

						$('#btnRegistrar').click(function(e){
							if (!ids.length) {
								bootbox.alert('No hay reservas seleccionadas.');
							}
							else {
								bootbox.confirm('¿Esta seguro de registrar el costo del viaje de las ' + ids.length + ' reservas seleccionadas?', function(res){
									if (res) {   					
										
										$.post('<?=site_url('admin/operadores/registrar_costo_viaje');?>', {ids:ids}, function(response){
											if (response.success){
												location.href = location.href;
											}
											else {
												bootbox.alert(response.error);
											}
										}, "json");
									}
								});
							}
						});
	
		
					});
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
					</script>

<?php echo $footer;?>