<?php echo $header;	?>


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
						<span>Listado de usuarios.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:70%;padding-bottom:0;">
						<div class="form-group">
							<label class="col-md-3 control-label">Sucursal:</label>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<select class="form-control" id="sucursal_id" name="sucursal_id">
											<option value="">Todas</option>
												<? foreach($sucursales as $s){ ?>
												<option value="<?=$s->id;?>" <?=(isset($sucursal_id) && $sucursal_id == $s->id)?'selected':'';?>><?=$s->nombre_completo;?></option>
												<? } ?>
										</select>
									</div>
								</div>
							</div>
							
							<label class="col-md-3 control-label">Tipo factura:</label>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<select class="form-control" id="tipo_factura" name="tipo_factura">
											<option value="">Todas</option>
											<option value="b" <?=(isset($tipo_factura) && $tipo_factura == 'b')?'selected':'';?>>Oficial</option>
											<option value="x" <?=(isset($tipo_factura) && $tipo_factura == 'x')?'selected':'';?>>Yellow</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label">Filtrar:</label>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<select class="form-control" id="filtro_fechas" name="filtro_fechas">
											<option value="">Todas</option>
											<option value="mes" <?=(isset($filtro_fechas) && $filtro_fechas == 'mes')?'selected':'';?>>Por mes</option>
											<option value="rango" <?=(isset($filtro_fechas) && $filtro_fechas == 'rango')?'selected':'';?>>Por rango de fechas</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-md-7" id="filtrar_por_mes" style="display:none;">
								<label class="col-md-2 control-label">Mes </label>
								<div class="col-md-4">
									<select class="form-control" id="mes" name="mes">
										<option value="">Todas</option>
										<? for($m=1;$m<=12;$m++){ ?>
										<option value="<?=$m;?>" <?=(isset($mes) && $mes == $m)?'selected':'';?>><?=ucfirst(nombre_mes($m,'%B'));?></option>
										<? } ?>
									</select>
								</div>
								<label class="col-md-2 control-label" style="text-align:center !important;">Año </label>
								<div class="col-md-4" style="padding-right:0;">
									<select class="form-control" id="anio" name="anio">
										<option value="">Todas</option>
										<? for($m=date('Y');$m>2011;$m--){ ?>
										<option value="<?=$m;?>" <?=(isset($anio) && $anio == $m)?'selected':'';?>><?=$m;?></option>
										<? } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-7" id="filtrar_por_rango" style="display:none;">
								<label class="col-md-2 control-label">Desde </label>
								<div class="col-md-4">
									<input type="text" id="fecha_desde" name="fecha_desde" class="form-control datepicker" value="<?php echo (!isset($filtro_mes) && isset($fecha_desde))?$fecha_desde:"";?>" />
								</div>
								<label class="col-md-2 control-label">Hasta </label>
								<div class="col-md-4" style="padding-right:0;">
									<input type="text" id="fecha_hasta" name="fecha_hasta" class="form-control datepicker" value="<?php echo (!isset($filtro_mes) && isset($fecha_hasta))?$fecha_hasta:"";?>" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<input type="button" name="btnReset" value="Resetear" class="btn btn-md btn-primary pull-right" style="margin:0 15px;" onclick="javascript: $('#frmSearch input,#frmSearch select').val('');$('#filtro_fechas').val('mes');$('#frmSearch').submit();"/>
							<button class="btn btn-md btn-primary pull-right" id="btnSearch" >Buscar</button>
						</div>
						
					</form>	
					
					<div class="" style="clear: both;display: block;width: 100%;position: relative;border-top: 1px solid #ccc;padding-top: 15px;">
						<div class="col-sm-1 col-md-1 text-center">
							<h5>Exportar</h5>
						</div>
						<div class="col-sm-4 col-md-2 text-center">
							<a href="<?php echo $route;?>/export" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> Listado</a> 
						</div>
						<div class="col-sm-4 col-md-2 text-center">
							<a href="<?php echo $route;?>/download_zip" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> PDF Facturas</a> 
						</div>
						<div class="col-sm-4 col-md-2 text-center">
						<? if(isset($mes) || isset($anio)): ?>
							<a href="<?php echo $route;?>/sicore/<?=isset($mes)?$mes:'';?>/<?=isset($anio)?$anio:'';?>" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> SICORE</a> 
						<? else: ?>
							<a href="<?php echo $route;?>/sicore/<?=(!isset($filtro_mes) && isset($fecha_desde))?$fecha_desde:"";?>/<?=(!isset($filtro_mes) && isset($fecha_hasta))?$fecha_hasta:"";?>" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> SICORE</a> 
						<? endif; ?>
						</div>
						<div class="col-sm-4 col-md-2 text-center">
							<a href="<?php echo $route;?>/alicuotas_txt/<?=isset($mes)?$mes:'';?>/<?=isset($anio)?$anio:'';?>" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> Alícuotas Ventas</a> 
						</div>
						<div class="col-sm-4 col-md-2 text-center">
							<a href="<?php echo $route;?>/compras_txt/<?=isset($mes)?$mes:'';?>/<?=isset($anio)?$anio:'';?>" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> IVA Ventas</a> 
						</div>
					</div>
				</div>
				<!-- /Page Header -->

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header mt-5" style="clear: both;display: block;width: 100%;position: relative;">
								<h4 class="col-sm-10" style="margin-top: 12px;">
									<i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?>
								</h4>
								<div class="col-sm-1 text" style="margin-bottom: 4px; margin-left: 35px; padding-right: 0px;">
									<a href="" data-href="<?=base_url().'admin/facturacion/actualizarComprobantes'?>" class="lnkMostrarNumeracion btn btn-md btn-success">Mostrar numeración AFIP </a> 
								</div>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?=$this->admin->th('id', 'ID', true, array('width'=>'50px'));?>
											<?=$this->admin->th('tipo', 'Tipo', true);?>
											<?=$this->admin->th('cae', 'CAE', true);?>
											<?=$this->admin->th('nombre', 'Usuario', true);?>
											<?=$this->admin->th('code', 'Cód Reserva', true);?>
											<?=$this->admin->th('subtitulo', 'Paquete', true);?>
											<?=$this->admin->th('operador', 'Operador', true);?>
											<?=$this->admin->th('sucursal', 'Sucursal', true, array('width'=>'100px'));?>
											<?=$this->admin->th('fecha', 'Fecha', true);?>
											<?=$this->admin->th('moneda', 'Moneda', false);?>
											<?=$this->admin->th('tipo_cambio', 'Tipo de cambio', false);?>
											<?=$this->admin->th('valor', 'Valor', true);?>
											<?=$this->admin->th('', 'Opciones', false);?>
										</tr>
									</thead>
									<tbody>
										<? foreach ($data->result() as $row): ?>
										<tr class="<?=alternator('odd', 'even');?>">
											<?=$this->admin->td($row->id);?>
											<?=$this->admin->td($row->tipo);?>
											<td id="factura_<?=$row->id;?>">
												<? if ($row->cae == ''): ?>
													<? if ($row->sucursal_talonario == 'B'): ?>
														<a href="#" class="lnkInformarFactura btn btn-small btn-orange" data-factura="<?=$row->id;?>" data-comprobante="<?=$row->talonario;?>" data-sucursal="<?=$row->sucursal_id;?>" data-reserva="<?=$row->reserva_id;?>">INFORMAR</a>
													<? else: ?>
														<a style="width: 100px;" href="#" class="lnkGenerarFactura btn btn-small btn-orange" data-factura="<?=$row->id;?>" data-comprobante="<?=$row->talonario;?>" data-sucursal="<?=$row->sucursal_id;?>">GENERAR FACTURA</a>
													<? endif; ?>								
												<? else:?>
													<a href="<?=site_url('pagos/download_pdf/'.$row->comprobante.'/'.$row->talonario.'/'.$row->id.'/'.$row->reserva_id.'/'.$row->sucursal_id.'/'.$row->fecha);?>"> <?=$row->cae;?> </a>
												<? endif; ?>
											</td>
											<?=$this->admin->td($row->nombre.' '.$row->apellido.'<br>DNI: '.$row->dni);?>
											<?=$this->admin->td($row->code);?>
											<?=$this->admin->td($row->titulo.' '.$row->subtitulo);?>
											<?=$this->admin->td($row->operador);?>
											<?=$this->admin->td($row->sucursal);?>
											<?=$this->admin->td(date('d/m/Y H:i',strtotime($row->fecha)).'hs.');?>
											<?php echo $this->admin->td($row->moneda);?>											
											<?php echo $this->admin->td($row->moneda=='Dolares'?('USD 1 = ARS '.$row->tipo_cambio):'');?>											
											<?=$this->admin->td(($row->tipo=='NC_B' || $row->tipo=='NC_X')?(-$row->valor_final):$row->valor_final);?>
											<td>
												<? if ( $row->cae != '' || ($row->cae != '' && $row->sucursal_talonario != 'B') ): ?>
													<a class="btn btn-sm btn-info lnk-resend lnkReenviarFactura" href="#" data-href="<?=base_url().'admin/facturacion/reenviar_factura/'.$row->id;?>" data-toggle="tooltip" data-html="true" data-placement="top" title="Reenviar Factura"><i class="glyphicon glyphicon-send"></i></a>
												<? endif; ?>
											</td>
										</tr>
										<? endforeach; ?>
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


				<!-- popup tabla numeracion  -->
				<div id="modalNumeracion" style="display:none">
					<div class="widget-content" >
						<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
							<thead>
								<tr>
									<td> Sucursal ID </td>
									<td> FA_B </td>
									<td> FA_X </td>
									<td> NC_B </td>
									<td> NC_X </td>
									<td> RE_X </td>
								</tr>
							</thead>
							<tbody>
								<? foreach ($numeradora as $sucursal): ?>
								<tr>
										<? foreach ($sucursal as $num): ?>
										<td><?=$num?> </td>
										<? endforeach; ?>
									</tr>
								<? endforeach; ?>
							</tbody>
						</table>			
					</div>
				</div>

				
<script>
	function show_filtro(tipo){
		if(tipo == 'mes'){
			$('#filtrar_por_rango').hide();
			$('#filtrar_por_mes').show();
		}
		else{
			$('#filtrar_por_mes').hide();
			$('#filtrar_por_rango').show();
		}
	}
	$(document).ready(function(){
		$('#fecha_desde.datepicker').datepicker({
			format: "yyyy-mm-dd",
			language: "es"
		});
		$('#fecha_hasta.datepicker').datepicker({
			format: "yyyy-mm-dd",
			language: "es"
		});
		
		$('#fecha_desde.datepicker').attr('readonly',true);
		$('#fecha_hasta.datepicker').attr('readonly',true);
		
		$("#fecha_desde.datepicker").on("changeDate", function (e) {
			var start = $("#fecha_desde.datepicker").datepicker('getDate');
			$("#fecha_hasta.datepicker").datepicker('setStartDate',start);
			$("#fecha_desde.datepicker").datepicker('hide');
		});

		
		$('[data-toggle="tooltip"]').tooltip();
		
		$('.lnkInformarFactura').click(function(e){
			e.preventDefault();
			var btn = $(this);
			if (btn.hasClass('btn-blue')) {
				bootbox.alert('Por favor espere a que concluya la operacion');
				return;
			}
			
			bootbox.confirm('Esta seguro que desea informar la factura a AFIP?', function(result){
				if (result){
					btn.html('INFORMANDO...').removeClass('btn-orange').addClass('btn-blue');
				
					$.post('<?=base_url();?>admin/reservas/informar_factura', {factura_id:$(btn).attr('data-factura'), comprobante:$(btn).attr('data-comprobante'), sucursal_id:$(btn).attr('data-sucursal'), reserva_id:$(btn).attr('data-reserva')}, function(data){
						data = $.parseJSON(data);
						if (data.status == 'ok') {
							location.href = location.href;
						} else if(data.msj) {
							btn.removeClass('btn-blue').addClass('btn-orange').html('INFORMAR');
							bootbox.alert(data.msj);
						} 
						else {
							btn.removeClass('btn-blue').addClass('btn-orange').html('INFORMAR');
							bootbox.alert('No se pudo informar la factura. ');
						}
					});
				}
			});
			
		});
		
		//generar factura para las de test
		$('.lnkGenerarFactura').click(function(e){
			e.preventDefault();
			var btn = $(this);
			if (btn.hasClass('btn-blue')) {
				alert('Por favor espere a que concluya la operacion');
				return;
			}
			
			bootbox.confirm('Esta seguro que desea generar la factura?', function(result){
				if (result){
					btn.html('GENERANDO...').removeClass('btn-orange').addClass('btn-blue');
					
					$.post('<?=base_url();?>admin/reservas/informar_factura', {factura_id:$(btn).attr('data-factura'), comprobante:$(btn).attr('data-comprobante'), sucursal_id:$(btn).attr('data-sucursal')}, function(data){
						$.fancybox.hideActivity();
						data = $.parseJSON(data);
					});
				}
			});
		});
		
		//reenviar factura 
		$('.lnkReenviarFactura').click(function(e){
			e.preventDefault();
			var btn = $(this);
			if (btn.hasClass('btn-blue')) {
				bootbox.alert('Por favor espere a que concluya la operacion');
				return;
			}
			
			var url = $(this).attr('data-href');
			
			bootbox.confirm('Esta seguro que desea reenviar el mail al pasajero con factura adjunta?', function(result){
				if (result){
					btn.html('Reenviando mail...').removeClass('btn-orange').addClass('btn-blue');
					
					$.post(url, function(data){
						data = $.parseJSON(data);
						if (data.status == 'ok') {
							btn.removeClass('btn-blue').addClass('btn-orange').html('<i class="glyphicon glyphicon-send"></i>');
							bootbox.alert(data.msg);
						}
						else {
							btn.removeClass('btn-blue').addClass('btn-orange').html('<i class="glyphicon glyphicon-send"></i>');
							bootbox.alert(data.msg);
						}
					});
				}
			});
		});


		//actualizar tabla numeracion con los ultimos comprobantes (AFIP)
		$('.lnkMostrarNumeracion').click(function(e){
			e.preventDefault();

			var btn = $(this);
			var url = $(this).attr('data-href');
			
			let container = $('#modalNumeracion').clone();
      		container.find('table').attr('id', 'tableNumeracion');
	
			let box = bootbox.dialog({
				show: false,
				message: container.html(),
				title: "Tabla Numeracion (AFIP)",
				buttons: {
					ok: {
						label: "Actualizar tabla",
						className: "btn-primary",
						callback: function() {
							btn.html('Actualizando...').removeClass('btn-success').addClass('btn-danger');
							$.post(url, function(data){
								bootbox.alert('Tabla numeradora actualizada con exito!');
								btn.html('Mostrar numeración AFIP').removeClass('btn-danger').addClass('btn-success');
							});
						}
					},
					cancel: {
						label: "Cerrar",
						className: "btn-default"
					}
				}
			});
		
			box.on("shown.bs.modal", function() {
				$('#tableNumeracion').DataTable(); 
			});
		
			box.modal('show'); 
		});


		
		$('#filtro_fechas').change(function() {
			
			if($(this).val() == 'mes'){
				show_filtro('mes');
			}
			else{
				show_filtro('rango');
			}
			
		});
		
		
		<? if(isset($filtro_fechas) && $filtro_fechas != ''){ ?>
			$('#chk_<?=$filtro_fechas;?>').trigger('click');
			show_filtro('<?=$filtro_fechas;?>');
		<? } else { ?>
			$('#chk_mes').trigger('click');
			show_filtro('mes');
		<? } ?>
	});
</script>

<?php echo $footer;?>