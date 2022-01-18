<?php echo $header;	?>
<style>
.fancybox-wrap {
	width  : 800px;
	height : 400px;
	max-width  : 80%;
	max-height : 60%;
	margin: 0;
	overflow-y:scroll;
	top:5% !important;
}
.fancybox-inner { width: 800px !important; }
</style>

				<?php if ($saved): ?>
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
						<span>Este listado muestra el listado de paquetes que contienen reservas.</span>
						<a style="margin-top:10px;" class="btn btn-sm <?=@$activo==1?'btn-primary':'btn-success';?>" href="<?=$route.(@$activo==1?'/finalizadas':'/vigentes');?>" rel="<?=@$activo==1?0:1;?>">
							<?=$msg_text;?>
						</a>
					</div>
					<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-horizontal page-stats" style="width:300px">
					
					
								
						<div class="form-group">
							<label class="col-md-2 control-label">Destino:</label>
							<div class="input-group col-md-10"> 
								
								<select name="destino_id" class="form-control" onchange="javascript: location.href = '<?=$route;?>?destino_id='+this.value;">
									<option value="">Todos</option>
									<? foreach($destinos->result() as $d): ?>
									<option value="<?=$d->id;?>" <?=@$destino_id==$d->id?'selected':'';?>><?=$d->nombre;?></option>
									<? endforeach; ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label">Buscar:</label>
							<div class="input-group col-md-10"> 
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
							<div class="alert alert-info fade in"> 
								<i class="icon-remove close" data-dismiss="alert"></i> 
								<strong>Exportar listados</strong>: Para realizar una descarga múltiple de las reservas de varios paquetes puedes seleccionarlos desde la primer columna del listado y luego clickear en el siguiente botón <a href="#" rel="<?=$route;?>/export"  class="btn btn-xs btn-primary lnkExportAll">Exportar</a>.
							</div>


						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=count($data->result());?> registro(s) disponible(s)</h4>
							</div>
							
							<?
							/*
							Fecha de Salida	Cantidad reservas	
							Cantidad confirmadas	Cupo disponible	Saldo a cobrar	Opciones
							*/
							?>
							
							<div class="widget-content" style="display: block;">
								<table id="table-1" cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<th style="text-align: center;">
												<input type="checkbox" value="1" class="select_all default" data-toggle="tooltip" data-html="true" data-placement="top" title="Seleccionar todos" />
											</th>
											<?php echo $this->admin->th('codigo', 'Cód. Paquete', true);?>
											<?php echo $this->admin->th('nombre', 'Nombre', true);?>
											<?php echo $this->admin->th('fecha_inicio', 'Fecha de salida', true);?>
											<?php echo $this->admin->th('cantidad', 'Cantidad reservas', true);?>
											<?php echo $this->admin->th('confirmadas', 'Cantidad confirmadas', true);?>
											<?php echo $this->admin->th('cupo_disponible', 'Cupo disponible', true);?>
											<?php echo $this->admin->th('adicionales_disponibles', 'Cupo Adicionales', true);?>
											<?php echo $this->admin->th('saldo_orden', 'Saldo a cobrar', true, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('', 'Alertas', false, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data->result() as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<td align="center">
												<input type="checkbox" name="ids_exportar[]" value="<?=$row->id;?>" class="ids_exportar default">
											</td>
											<?php echo $this->admin->td($row->codigo);?>
											<?php echo $this->admin->td($row->nombre);?>
											<td align="center"><?=date('d/m/Y',strtotime($row->fecha_inicio));?></td>
											<td align="center">
												<span style="width:70px;display:inline-block;"><?=$row->cantidad?$row->cantidad:0;?>

													<button style="margin-left: 5px;" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-html="true" data-placement="top" title="<?=$row->mujeres.' mujeres<br/>'.$row->hombres.' hombres';?>"><i class="glyphicon glyphicon-user"></i></button>
												</span>
												

												<? if($row->en_lista_de_espera > 0): ?>
													<span style="width:60px;display:inline-block;">
														<?=$row->en_lista_de_espera?>
														<button style="margin-left: 5px;" type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay reservas en lista de espera"><i class="glyphicon glyphicon-list"></i></button>
													</span>
												<? endif;?>

											</td>
											<td align="center"><?=$row->confirmadas?$row->confirmadas:0;?></td>
											<td align="center"><?=$row->cupo_paquete_disponible_real;?></td>
											<td align="center"><?=$row->adicionales_disponibles.'/'.$row->adicionales_total;?></td>
											<td align="center"><?=($row->precio_usd?'USD ':'ARS ').number_format(@$row->saldoACobrar?$row->saldoACobrar:'0.00',2,',','.');?></td>
											<td style="text-align:center;">
												<? if(@$row->alarmas->completar_datos_pax):
													//si no completo los datos de pasajeros y faltan 2 dias para la fecha limite de completar ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Faltan completar datos de pasajeros"><i class="glyphicon glyphicon-user"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->informes): 
													//si tiene informes de pago sin verificar ?>
													<button onclick="return false; location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=informes');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay informes de pago sin verificar"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												<? if(@$row->alarmas->fecha_limite_pago_completo): 
													//si tiene pagos pendientes alcanzada la fecha limite ?>
													<button onclick="return false; " type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay pagos pendientes"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->falta_factura_proveedor): 
													//si el ultimo documento del usuario es un recibo muestro alarma ?>
													<button onclick="return false; location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=cta_cte');?>'" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Falta factura del Proveedor"><i class="glyphicon glyphicon-file"></i></button>
												<? endif; ?>
												
												<? //solo para reservas de vaijes que no sean operados por BUENAS VIBRAS
												if(@$row->alarmas->faltan_cargar_vouchers): 
													//si llego a la fecha limite para mostrar alerta de carga de vouchers ?>
													<button onclick="return false; location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=vouchers');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay vouchers sin cargar"><i class="glyphicon glyphicon-tags"></i></button>
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
											
												<? if(@$row->alarmas->reservas_a_confirmar){ ?>
													<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay reservas por confirmar"><i class="glyphicon glyphicon-briefcase"></i></button>
												<? } ?>
												
												<? if(@$row->alarmas->alerta_cupos_vencidos){ ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="El paquete tiene cupos de transporte vencidos"><i class="glyphicon glyphicon-time"></i></button>
												<? } ?>
												
											</td>
											<td>
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/paquete/<?=$row->id;?>"><i class="glyphicon glyphicon-list" style="font-size:12px;"></i> Ver listado</a>
												<div class="btn-group">
												  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="glyphicon glyphicon-cog" style="font-size:12px;"></i> Opciones <span class="caret"></span>
												  </button>
												  <ul class="dropdown-menu">
													<li><a class="lnkExport" rel="<?=$row->id;?>" href="<?=$route;?>/export/<?=$row->id;?>" target="_blank">Descargar Excel</a></li>
													<li><a href="<?=$route;?>/manifiesto/<?=$row->id;?>" target="_blank">Descargar Manifiesto</a></li>
													<li><a href="<?=$route;?>/export_csv/<?=$row->id;?>" target="_blank">Descargar CSV</a></li>
													<? if($row->grupal): ?>
														<li><a href="<?=$route;?>/rooming/<?=$row->id;?>">Ver Rooming</a></li>
													<? endif;?>
												  </ul>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data->result()) == 0): ?>
										<tr>
											<td colspan="10" align="center" style="padding:30px 0;">
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

					$('.lnkExport').click(function(e){
						e.preventDefault();
						var paq_id = $(this).attr('rel');
						$.post("<?php echo $route;?>/get_download_fields/"+paq_id,function(data){
							if(data.view){
								$.fancybox({'content': data.view, 'width':800});
							}
						},'json');
					});

					$('body').on('click','.lnkExportAll',function(e){
						var me = $(this);

						//genero ids con os paquetes a exportar
						var ids = '';
						$.each($('.ids_exportar'),function(i,el){
							if($(el).is(':checked')){
								ids += (!ids ? "" : "-") + $(el).val();
							}	
						});

						if(!ids){
							bootbox.alert("Debes seleccionar al menos un paquete");
							return false;
						}

						$.post("<?php echo $route;?>/get_download_fields/"+ids,function(data){
							if(data.view){
								$.fancybox({'content': data.view, 'width':800});
							}
						},'json');

						//a la ruta de exportar le paso el primero parametro en 0 y el segundo la lista
						//me.attr('href',me.attr('rel')+'/0/'+ids);
					});

					$('body').on('click','.select_all',function(e){
						var me = $(this);
						if(me.is(':checked')){
							//selecciono todos
							$.each($('.ids_exportar'),function(i,el){
								$(el).prop('checked',true);
							});
						}
						else{
							//deselecciono todos
							$.each($('.ids_exportar'),function(i,el){
								$(el).prop('checked',false);
							});
						}
					});

				});
				</script>

<?php echo $footer;?>