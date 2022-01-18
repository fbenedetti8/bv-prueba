<?php echo $header;	?>
<style>
.filtros_comision .control-label { padding: 0; text-align: right;  line-height: 28px; }
.filtros_comision .col-md-3 { padding: 0; }
.datepicker{z-index: 10000 !important;}
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
						<span>Listado de comisiones de cada una de las reservas del vendedor.</span>
					</div>
								
					<a class="btn btn-md btn-success pull-right" target="_blank" href="<?=base_url();?>" style="margin:30px 20px 0 0;">Generar nueva reserva</a>	
				</div>
				<!-- /Page Header -->

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12 filtros_comision">
						<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-vertical page-stats" style="width:100%">
							<div class=" form-group">
								<div class="col-md-3">
									<label class="col-md-5 control-label">Estado comisión:</label>
									<div class="col-md-7">
										<div class="row">
											<div class="col-md-12">
												<select class="form-control" name="estado">
													<option value="" <?=(!isset($estado) || @$estado == '')?'selected':'';?>>Todos</option>
													<option value="Liquidada" <?=(isset($estado) && $estado == 'Liquidada')?'selected':'';?>>Liquidada</option>
													<option value="Pendiente" <?=(isset($estado) && $estado == 'Pendiente')?'selected':'';?>>Pendiente</option>
												
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-3">
									<label class="col-md-3 control-label">Mes </label>
									<div class="col-md-8">
										<select class="form-control" id="mes" name="mes">
											<option value="">Todos</option>
											<? for($m=1;$m<=12;$m++){ ?>
											<option value="<?=$m;?>" <?=(isset($mes) && $mes == $m)?'selected':'';?>><?=ucfirst(nombre_mes($m,'%B'));?></option>
											<? } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<label class="col-md-3 control-label" style="text-align:center !important;">Año </label>
									<div class="col-md-8" style="padding-right:0;">
										<select class="form-control" id="anio" name="anio">
											<option value="">Todos</option>
											<? for($m=date('Y');$m>2017;$m--){ ?>
											<option value="<?=$m;?>" <?=(isset($anio) && $anio == $m)?'selected':'';?>><?=$m;?></option>
											<? } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<input type="button" name="btnReset" value="Resetear" class="btn btn-md btn-primary pull-right" style="margin:0 15px;" onclick="javascript: $('#frmSearch input,#frmSearch select').val('');$('#filtro_fechas').val('mes');$('#frmSearch').submit();"/>
									<button class="btn btn-md btn-primary pull-right" id="btnSearch" >Buscar</button>
								</div>
							</div>	
								
						</form>	
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?></h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('code', 'Cód Reserva', true);?>
											<?php echo $this->admin->th('PA.apellido', 'Usuario', true);?>
											<?php echo $this->admin->th('fecha_reserva', 'Fecha de reserva', true);?>
											<?php echo $this->admin->th('pasajeros', 'Pasajeros', true, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('comision_estado', 'Estado Comisión', true, array('text-align'=>'center'));?>
											<? //lo mando siempre por el ele
											if(FALSE && isset($data[0]) && isset($data[0]->liquidacion_id) && $data[0]->liquidacion_id): 
												//si existe la liquidacion?>
												<?php // echo $this->admin->th('comision_monto', 'Importe comisión', true, array('text-align'=>'center'));?>
												<?php echo $this->admin->th('monto_comisionable', 'Monto comisionable', true, array('text-align'=>'center'));?>
											<? else: 
												//si no existe, pongo valores referencia?>
												<?php //echo $this->admin->th('v_total', 'Valor viaje', true, array('text-align'=>'center'));?>
												<?php echo $this->admin->th('monto_comisionable', 'Monto comisionable', true, array('text-align'=>'center'));?>
											<? endif ;?>
											<?php echo $this->admin->th('estado', 'Estado reserva', false, array('width'=>'100px'));?>
										</tr>
									</thead>
									<tbody>
										<?php
										 foreach($data as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->code);?>
											<?php echo $this->admin->td($row->nombre.' '.$row->apellido.'<br>'.$row->email);?>
											<?php echo $this->admin->td(date('d/m/Y H:i',strtotime($row->fecha_reserva)).' hs.');?>
											<td style="text-align:center;">
												<?=$row->pasajeros;?></td>
											<td style="text-align:center;">
												<label class="label label-<?=$row->comision_estado?'primary':'warning';?>">
													<?=$row->comision_estado?'Confirmada':'Pendiente';?>
												</label>
											</td>
											<? if(FALSE && isset($row->liquidacion_id) && $row->liquidacion_id): ?>
												<!--<td style="text-align:center;">
													<?=precio($row->comision_monto,false,$bold=false,$alternate=false,$numeric=false);?>
												</td>-->
												<td style="text-align:center;">
													<?=precio($row->monto_comisionable,$row->precio_usd,$bold=false,$alternate=false,$numeric=false);?>
												</td>
											<? else: ?>
												<? if(false): ?>
													<td style="text-align:center;">
													<?=precio($row->v_total,$row->precio_usd,$bold=false,$alternate=false,$numeric=false);?>
													</td>
												<? endif; ?>
												<td style="text-align:center;">
													<?=precio($row->monto_comisionable,$row->precio_usd,$bold=false,$alternate=false,$numeric=false);?>
												</td>
											<? endif; ?>
											<td>
												<? foreach($estados as $estado): ?>
												<? if ($estado->id == $row->estado_id): ?>
												<label class="label label-primary" style="background-color:#<?=$estado->color;?>; vertical-align:middle; padding: 6px;font-size: 12px;margin-bottom:5px;display: inline-block;"><?=$estado->nombre;?></label>
												<? endif; ?>
												<? endforeach; ?>
												<a class="btn btn-sm btn-primary btnCambiarEstado" href="#" data-href="<?=base_url();?>admin/reservas/form_cambiar_estado/<?=$row->id.'/'.$row->paquete_id;?>?ref=reservas_vendedor" data-code="<?=$row->code;?>">Cambiar estado</a>
											</td>
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
					

					//abre popup para cambiar de estado
					  $("body").on('click','.btnCambiarEstado',function(e) {
							e.preventDefault();
							var me = $(this);
							var url = me.attr('data-href');
							var code = me.attr('data-code');
							$.post(url,function(data){
								if(data.view){
									var dialog = bootbox.dialog({
										title: 'Cambio de estado de la reserva Cód. '+code,
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
					
		
				});
				</script>

<?php echo $footer;?>