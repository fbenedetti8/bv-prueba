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
						<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?> de Ingresos</h3>
						<span>Desde este módulo se pueden visualizar los reportes de ingresos.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:70%;padding-bottom:0;">
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
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?>  | <a href="<?=site_url('admin/reportes/index/ingresos/1');?>">Exportar</a></h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('f.fecha', 'Fecha', false);?>
											<?php echo $this->admin->th('t.comprobante', 'Nro. Recibo', false);?>
											<?php echo $this->admin->th('p.apellido', 'Pasajero', false);?>
											<?php echo $this->admin->th('t.codigo', 'Cod Reserva', false);?>
											<?php echo $this->admin->th('v.titulo', 'Nombre FILE', false);?>
											<?php echo $this->admin->th('v.operador', 'Operador Mayorista', false);?>
											<?php echo $this->admin->th('t.origen', 'Concepto (Forma de Pago)', false);?>
											<?php echo $this->admin->th('t.banco', 'Tipo de cambio', false);?>
											<?php echo $this->admin->th('', 'Moneda', false);?>
											<?php echo $this->admin->th('', 'Total', false);?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data as $row):  ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->{'Fecha'});?>											
											<?php echo $this->admin->td($row->{'Nro. Recibo'});?>											
											<?php echo $this->admin->td($row->{'Pasajero'});?>											
											<?php echo $this->admin->td($row->{'Cod Reserva'});?>											
											<?php echo $this->admin->td($row->{'Nombre FILE'});?>											
											<?php echo $this->admin->td($row->{'Operador'});?>											
											<?php echo $this->admin->td($row->{'Concepto (Forma de Pago)'});?>											
											<?php echo $this->admin->td($row->{'Tipo de cambio'});?>											
											<?php echo $this->admin->td($row->{'Moneda'});?>											
											<?php echo $this->admin->td($row->{'Total'});?>											
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
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