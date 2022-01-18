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
						<span>Listado de registros sobre el sistema de caja. Para agregar un nuevo registro haga <a href="<?=site_url('admin/caja/add');?>" class="btn btn-sm btn-primary">click aquí</a></span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:80%;padding-bottom:0;">
						<div class="form-group">
							<label class="col-md-2 control-label">Sucursal:</label>
							<div class="col-md-2">
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
							
							<label class="col-md-2 control-label">Buscar</label>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<input type="text" id="valor_buscar" name="valor_buscar" class="form-control" value="<?php echo isset($valor_buscar)?$valor_buscar:"";?>" >
									</div>
								</div>
							</div>

							<label class="col-md-1 control-label">en</label>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<select class="form-control" id="clave_buscar" name="clave_buscar">
											<option value="">Todas</option>
											<option value="concepto" <?=(isset($clave_buscar) && $clave_buscar == 'concepto')?'selected':'';?>>Concepto</option>
											<option value="observaciones" <?=(isset($clave_buscar) && $clave_buscar == 'observaciones')?'selected':'';?>>Observaciones</option>
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
						<div class="col-md-3 text-left">
							<h5>Exportar en formato Excel</h5>
						</div>
						<div class="col-md-2 text-left">
							<a href="<?php echo $route;?>/exportar" target="_blank" class="btn btn-md btn-default"><i class="glyphicon glyphicon-download-alt"></i> Descargar</a> 
						</div>						
					</div>
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
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?=$this->admin->th('id', 'ID', true, array('width'=>'50px'));?>
											<?=$this->admin->th('fecha', 'Fecha', true);?>
											<?=$this->admin->th('concepto', 'Concepto', true);?>
											<?=$this->admin->th('paquete_titulo', 'Paquete', true);?>
											<?=$this->admin->th('usuario_nombre', 'Usuario', true);?>
											<?=$this->admin->th('ingreso', 'Ingreso', true);?>
											<?=$this->admin->th('egreso', 'Egreso', true);?>
											<?=$this->admin->th('saldo', 'Saldo', true);?>
											<?=$this->admin->th('admin_username', 'Admin', true);?>
											<?=$this->admin->th('', 'Observaciones', false);?>
										</tr>
									</thead>
									<tbody>
										<? foreach ($data->result() as $row): ?>
										<tr class="<?=alternator('odd', 'even');?>">
											<?=$this->admin->td($row->id);?>
											<?=$this->admin->td(date('d/m/Y H:i',strtotime($row->fecha)).'hs.');?>
											<?=$this->admin->td($row->concepto);?>
											<?=$this->admin->td($row->paquete_titulo);?>
											<?=$this->admin->td($row->usuario_nombre.' '.$row->usuario_apellido);?>
											<?=$this->admin->td($row->ingreso);?>
											<?=$this->admin->td($row->egreso);?>
											<?=$this->admin->td($row->saldo);?>
											<?=$this->admin->td($row->admin_username);?>
											<td>
												<? if($row->observaciones != ''){ ?>
													<a href="<?=site_url('admin/caja/ver/'.$row->id);?>" class="fancybox fancybox.ajax">Ver</a>
												<? } ?>
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
				
					$.post('<?=base_url();?>admin/reservas/informar_factura', {factura_id:$(btn).attr('data-factura'), comprobante:$(btn).attr('data-comprobante'), sucursal_id:$(btn).attr('data-sucursal')}, function(data){
						data = $.parseJSON(data);
						if (data.status == 'ok') {
							location.href = location.href;
						}
						else {
							btn.removeClass('btn-blue').addClass('btn-orange').html('INFORMAR');
							bootbox.alert('No se pudo informar la factura. ' + data.error);
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
						if (data.status == 'ok') {
							btn.remove();
							$('#factura_' + btn.attr('data-factura')).show();
							$('#cell_comprobante_' + btn.attr('data-factura')).html('<a href="<?=base_url();?>data/facturas/' + data.comprobante + '.pdf" target="_blank">' + data.comprobante + '</a>');
						}
						else {
							btn.removeClass('btn-blue').addClass('btn-orange').html('GENERAR FACTURA');
							bootbox.alert('No se pudo generar la factura. ' + data.error);
						}
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