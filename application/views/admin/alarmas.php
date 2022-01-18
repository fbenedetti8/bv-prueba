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
						<h3><?=$page_title;?></h3>
						<span>Listado de alarmas sobre reservas.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-vertical page-stats" style="width:400px">
							<div class="row form-group">
								<label class="col-md-4 control-label">Tipo de Alarma:</label>
								<div class="input-group col-md-8"> 
									<select class="form-control" name="alarma_id" onchange="javascript: location.href='<?=$route."/set_alarma";?>/'+this.value;">
										<option value="" <?=(!isset($alarma_id) || @$alarma_id == '')?'selected':'';?>>Todas</option>
										<?php foreach($alarmas as $a){?>
											<option value="<?=$a['id'];?>" <?php if(@$alarma_id == $a['id']) echo 'selected';?>><?php echo $a['nombre'];?></option>
										<?php } ?>
									</select>												
								</div>						
							</div>	
						
					</form>					
				</div>
				<!-- /Page Header -->

				<div class="alert alert-success fade in hidden alert-iconos"> 
					<i class="icon-remove close" data-dismiss="alert"></i> 
					<strong>&iexcl;Atención!</strong> El sistema está cargando las alarmas de cada reserva.<br>Por favor, aguarde unos instantes <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
				</div>


				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows?><span id="cantReg"></span> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?></h4>
								<div class="btn-group pull-right" style="margin:5px;">
									<button class="btn btn-sm" id="lnkExport" data-rel="<?=$route;?>/exportar"><i class="icol-doc-excel-table"></i> Exportar</button>
								</div>
							</div>
							
							<?
							/*
							Fecha de Salida	Cantidad reservas	
							Cantidad confirmadas	Cupo disponible	Saldo a cobrar	Opciones
							*/
							?>
							
							<div class="widget-content" style="display: block;">
								<form id="frmIDS">
									
								<? if(FALSE && $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){?>
									<table id="tblsarasa" cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped ">
								<? } else { ?>
									<table id="tblsarasa" cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped datatable">
								<? } ?>

									<thead>
										<tr>
											<?php echo $this->admin->th('codigo', 'Cód Reserva', false);?>
											<?php echo $this->admin->th('paquete', 'Paquete', false, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('fechaSalida', 'Fecha de salida', false);?>
											<?php echo $this->admin->th('U.nombre', 'Usuario', false);?>
											<?php echo $this->admin->th('estado_id', 'Estado', false);?>
											<?php echo $this->admin->th('saldo', 'Saldo', false, array('text-align'=>'center'));?>
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
										<?php foreach($data as $row): ?>
											

										<tr class="trrow <?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->codigo);?>
											<td><?=$row->paquete.'<br>Cód: '.$row->paquete_codigo;?></td>
											<td><?=date('d/m/Y',strtotime($row->fechaSalida));?></td>
											<?php echo $this->admin->td($row->nombre.' '.$row->apellido.'<br>'.$row->email.'<br>'.$row->celular_codigo.' '.$row->celular_numero);?>											
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
											<td class="row_alarmas row_alarmas_<?=$row->id;?>" style="text-align:center;">
												<div style="display: none;">
													<? pre($row->alarmas); ?>
												</div>
												<? if(@$row->alarmas->completar_datos_pax):
													//si no completo los datos de pasajeros y faltan 2 dias para la fecha limite de completar ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Faltan completar datos de pasajeros"><i class="glyphicon glyphicon-user"></i></button>
												<? endif; ?>
												
												<? if(@$row->alarmas->informes): 
													//si tiene informes de pago sin verificar ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=informes');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay informes de pago sin verificar"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												<div style="display: none;"><?=@$row->fecha_limite_pago_completo;?></div>
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

												
												<? if(FALSE && @$row->alarmas->tiene_adicionales){ ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Tiene adicionales contratados"><i class="glyphicon glyphicon-tags"></i></button>
												<? } ?>
											</td>
											<td>
												<input type="hidden" name="ids[]" value="<?=$row->id;?>"/>

												<a class="btn btn-sm" target="_blank" style="margin-bottom:5px;" href="<?=site_url('admin/reservas/edit/'.$row->id.'?tab=cta_cte');?>"><i class="glyphicon glyphicon-usd" style="font-size:12px;"></i> Cuenta Corriente</a>
													
											    <div class="btn-group">
												  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Opciones <span class="caret"></span>
												  </button>
												  <ul class="dropdown-menu" style="right:0;left:inherit;text-align:right;">
													<li><a href="<?=site_url('admin/reservas/edit/'.$row->id);?>">Editar</a></li>
													<li><a href="<?=site_url('admin/reservas/edit/'.$row->id);?>?tab=historial">Ver Historial</a></li>
													<li><a href="#" rel="<?=site_url('admin/reservas/generarCupon/'.$row->id);?>" class="cupon">Enviar Mail Datos Reserva</a></li>
													<li><a href="#" rel="<?=site_url('admin/reservas/generar_voucher_pago/'.$row->id);?>" class="sendVoucher" >Enviar Mail Pago Completo</a></li>
												  </ul>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
										<tr>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td class="hidden"></td>
											<td colspan="8" align="center" style="padding:30px 0;">
												No se encontraron resultados.
											</td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
								</form>

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
					
					$("#lnkExport").click(function(e){
						e.preventDefault();
						var me = this;
						var url = $(this).attr('data-rel');
						
						bootbox.confirm("Esto exportará el listado de alarmas. <br>Está seguro?", function(result){
							if (result) {
								location.href = '<?=base_url();?>admin/alarmas?export=1';
								
								/*$.post('<?=base_url();?>admin/alarmas?export=1',$('#frmIDS').serialize(),function(data){
									if(data){
										location.href = url;
									}
									else{
										console.log('aa');
									}
								});*/
							}
						});
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
					
					
					<? if(FALSE && $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){?>
						load_icons();
					<? } ?>
				});

				function load_icons(){

					$('.alert-iconos').removeClass('hidden');
					$.post("<?=site_url('admin/alarmas/get_iconos');?>",$('#frmIDS').serialize(),function(data){
							$('.alert-iconos').addClass('hidden');
							
							var cant = 0;

							if(data.rows){
								$.each(data.rows,function(i,el){
									if(el.html_alarmas){
										cant+=1;

										$('.row_alarmas_'+el.id).html(el.html_alarmas);
										$('.row_alarmas_'+el.id).addClass('con_alarmas');
									}
								});

								//actualizo cantidad de registros
								$('#cantReg').text(cant);
								//elimino los rows que no tengan la clase con_alarmas
								$('.row_alarmas:not(.con_alarmas)').closest('.trrow').remove();

								$('#tblsarasa').addClass('datatable');
								Plugins.initDT();
								$('body').css('height','auto');
							}
						},'json');
				}
				</script>

<?php echo $footer;?>
