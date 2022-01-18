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
					<div class="page-title" style="width:25%">
						<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?> de Utilidades</h3>
						<span>Desde este módulo se pueden visualizar los reportes de utilidades.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:70%;padding-bottom:0;">
						<div class="form-group">
							<label class="col-md-2 control-label">Estado:</label>
							<div class="input-group col-md-10"> 
								<select name="estado_id" class="form-control" onchange="javascript: $('#frmSearch').submit();">
									<option value="">Todos</option>
									<? foreach($estados as $e): ?>
									<option value="<?=$e->id;?>" <?=(@$estado_id == $e->id)?'selected':'';?>><?=$e->nombre;?></option>
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
				</div>
				<!-- /Page Header -->

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?>  | <a href="<?=site_url('admin/reportes/index/utilidades/1');?>">Exportar</a></h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('fecha', 'Fecha Confirmada', false);?>
											<?php echo $this->admin->th('r.status', 'Estado Reserva', false);?>
											<?php echo $this->admin->th('t.codigo', 'Cod Reserva', false);?>
											<?php echo $this->admin->th('v.titulo', 'Nombre FILE', false);?>
											<?php echo $this->admin->th('', 'Nro. Cpte.', false);?>
											<?php echo $this->admin->th('p.apellido', 'Pasajero', false);?>
											<?php echo $this->admin->th('v.operador', 'Operador', false);?>
											<?php echo $this->admin->th('', 'Moneda', false);?>
											<?php echo $this->admin->th('', 'Tipo de Cambio', false);?>
											<?php echo $this->admin->th('', 'Venta Total', false);?>
											<?php echo $this->admin->th('', 'Costo Total', false);?>
											<?php echo $this->admin->th('', 'Utilidad', false);?>
											<?php echo $this->admin->th('', 'Venta Neta', false);?>
											<?php echo $this->admin->th('', 'Costo Neto', false);?>
											<?php echo $this->admin->th('', 'Utilidad Neta', false);?>
											<?php echo $this->admin->th('', 'Porcentual Neto', false);?>
										</tr>
									</thead>
									<tbody>
										<?php 
										$venta_total = 0.00;
										$costo_total = 0.00;
										$utilidad = 0.00;
										$venta_neta = 0.00;
										$costo_neto = 0.00;
										$utilidad_neta = 0.00;
										$porcentual = 0.00;
										foreach ($data as $row):  ?>
										<?
										$venta_total+=str_replace(array('.',','),array('','.'),$row->{'Venta Total'}); 
										$costo_total+=str_replace(array('.',','),array('','.'),$row->{'Costo Total'}); 
										$utilidad+=str_replace(array('.',','),array('','.'),$row->{'Utilidad'}); 
										$venta_neta+=str_replace(array('.',','),array('','.'),$row->{'Venta Neta'}); 
										$costo_neto+=str_replace(array('.',','),array('','.'),$row->{'Costo Neto'}); 																					
										$utilidad_neta+=str_replace(array('.',','),array('','.'),$row->{'Utilidad Neta'}); 
										$porcentual+=str_replace(array('.',','),array('','.'),$row->{'Porcentual Neto'}); ?>											
											
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->{'Fecha Confirmada'});?>											
											<?php echo $this->admin->td($row->{'Estado Reserva'});?>									
											<?php echo $this->admin->td($row->{'Cod Reserva'});?>											
											<?php echo $this->admin->td($row->{'Nombre FILE'});?>											
											<?php echo $this->admin->td($row->{'Nro. Cpte'});?>												
											<?php echo $this->admin->td($row->{'Pasajero'});?>											
											<?php echo $this->admin->td($row->{'Operador'});?>											
											<?php echo $this->admin->td($row->{'Moneda'});?>											
											<?php echo $this->admin->td($row->{'Moneda'}=='Dolares'?('USD 1 = ARS '.$row->{'Tipo de Cambio'}):'');?>											
											<?php echo $this->admin->td($row->{'Venta Total'}); ?>
											<?php echo $this->admin->td($row->{'Costo Total'}); ?>
											<?php echo $this->admin->td($row->{'Utilidad'}); ?>
											<?php echo $this->admin->td($row->{'Venta Neta'}); ?>
											<?php echo $this->admin->td($row->{'Costo Neto'}); ?>
											<?php echo $this->admin->td($row->{'Utilidad Neta'}); ?>
											<?php echo $this->admin->td($row->{'Porcentual Neto'}); ?>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
										<tr>
											<td colspan="14" align="center" style="padding:30px 0;">
												No se encontraron resultados.
											</td>
										</tr>
										<?php else: ?>
										<tr>
											<td colspan="8">&nbsp;</td>
											<td><b>Total</b></td>
											<td><b><?=number_format($venta_total,2,',','.');?></b></td>
											<td><b><?=number_format($costo_total,2,',','.');?></b></td>
											<td><b><?=number_format($utilidad,2,',','.');?></b></td>
											<td><b><?=number_format($venta_neta,2,',','.');?></b></td>
											<td><b><?=number_format($costo_neto,2,',','.');?></b></td>
											<td><b><?=number_format($utilidad_neta,2,',','.');?></b></td>
											<td><b><?=number_format($porcentual/count($data));?>%</b></td>
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