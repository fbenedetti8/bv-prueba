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
						<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?> de Ventas</h3>
						<span>Desde este módulo se pueden visualizar los reportes de ventas.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:300px">
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
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?>  | <a href="<?=site_url('admin/reportes/index/ventas/1');?>">Exportar</a></h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('f.fecha', 'Fecha', false);?>
											<?php echo $this->admin->th('t.comprobante', 'Nro. Cpte', false);?>
											<?php echo $this->admin->th('p.apellido', 'Pasajero', false);?>
											<?php echo $this->admin->th('t.codigo', 'Cod Reserva', false);?>
											<?php echo $this->admin->th('v.titulo', 'Nombre FILE', false);?>
											<?php echo $this->admin->th('v.operador', 'Operador Mayorista', false);?>
											<?php echo $this->admin->th('', 'IVA', false);?>
											<?php echo $this->admin->th('r.f_cuit', 'CUIT', false);?>
											<?php echo $this->admin->th('', 'Exento', false);?>
											<?php echo $this->admin->th('', 'Gravado 21', false);?>
											<?php echo $this->admin->th('', 'Gravado 10.5', false);?>
											<?php echo $this->admin->th('', 'Comision', false);?>
											<?php echo $this->admin->th('', 'IVA 21', false);?>
											<?php echo $this->admin->th('', 'IVA 10.5', false);?>
											<?php echo $this->admin->th('', 'Gastos administrativos', false);?>
											<?php echo $this->admin->th('', 'Total en Pesos', false);?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data as $row):  ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->{'Fecha'});?>											
											<?php echo $this->admin->td($row->{'Nro. Cpte'});?>											
											<?php echo $this->admin->td($row->{'Pasajero'});?>											
											<?php echo $this->admin->td($row->{'Cod Reserva'});?>											
											<?php echo $this->admin->td($row->{'Nombre FILE'});?>											
											<?php echo $this->admin->td($row->{'Operador Mayorista'});?>											
											<?php echo $this->admin->td($row->{'IVA'});?>											
											<?php echo $this->admin->td($row->{'CUIT'});?>											
											<?php echo $this->admin->td($row->{'Exento'});?>											
											<?php echo $this->admin->td($row->{'Gravado 21'});?>											
											<?php echo $this->admin->td($row->{'Gravado 10.5'});?>											
											<?php echo $this->admin->td($row->{'Comision'});?>											
											<?php echo $this->admin->td($row->{'IVA 21'});?>											
											<?php echo $this->admin->td($row->{'IVA 10.5'});?>											
											<?php echo $this->admin->td($row->{'Gastos administrativos'});?>											
											<?php echo $this->admin->td($row->{'Total en Pesos'});?>											
										</tr>
										<?php endforeach; ?>
										<?php if (count($data) == 0): ?>
										<tr>
											<td colspan="16" align="center" style="padding:30px 0;">
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
		
<?php echo $footer;?>