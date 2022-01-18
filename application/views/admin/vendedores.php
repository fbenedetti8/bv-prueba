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
						<span>Listado de vendedores.</span>
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

						<div class="form-group"> 
							<div class="row"> 
								<div class="col-sm-12"> 
									<label class="col-md-5 control-label">Es coordinador:</label>
									<div class="input-group col-md-7"> 
										<select name="es_coordinador" class="form-control" onchange="javascript: location.href = '<?=$route;?>?es_coordinador='+this.value;" style=" margin: 0 5px">
											<option value="" <?=@$es_coordinador==''?'selected':'';?>>Todos</option>
											<option value="1" <?=@$es_coordinador=='1'?'selected':'';?>>Si</option>
											<option value="0" <?=@$es_coordinador=='0'?'selected':'';?>>No</option>
										</select>
									</div>
								</div>
							
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
								<div class="btn-group pull-right" style="margin:5px;">
									<button class="btn btn-sm" onclick="javascript: location.href = '<?=$route;?>/exportar';"><i class="icol-doc-excel-table"></i> Exportar</button>
								</div>
							</div>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('nombre', 'Nombre', true);?>
											<?php echo $this->admin->th('apellido', 'Apellido', true);?>
											<?php echo $this->admin->th('email', 'E-Mail', true);?>
											<?php echo $this->admin->th('telefono', 'Teléfono', true);?>
											<?php echo $this->admin->th('es_coordinador', 'Coordinador', true);?>
											<?php echo $this->admin->th('saldo', 'Saldo', true, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data->result() as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->nombre);?>
											<?php echo $this->admin->td($row->apellido);?>
											<?php echo $this->admin->td($row->email);?>
											<?php echo $this->admin->td($row->telefono);?>
											<td class="text-center"><?=$row->es_coordinador?'<i class="glyphicon glyphicon-ok-sign" style="color:green;font-size:14px;"></i>':'<i class="glyphicon glyphicon-minus-sign" style="color:red;font-size:14px;"></i>';?></td>
											<td align="center"><?=@$row->saldo?$row->saldo:'0.00';?></td>
											<td>
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/edit/<?=$row->id;?>?tab=cta_cte"><i class="glyphicon glyphicon-usd" style="font-size:12px;"></i> Cuenta Corriente</a>
												
												<div class="btn-group">
												  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Opciones <span class="caret"></span>
												  </button>
												  <ul class="dropdown-menu" style="right:0;left:inherit;text-align:right;">
													<li><a href="<?=$route;?>/edit/<?=$row->id;?>">Editar</a></li>
													<li><a href="<?=$route;?>/delete/<?=$row->id;?>" class="delete">Borrar</a></li>
												  </ul>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data->result()) == 0): ?>
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
											<a class="btn btn-primary" href="<?php echo $route;?>/add">Agregar Nuevo</a>
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
					$('#tipo').change(function(){
						$('#frmSearch').submit();
					});
				});
				</script>

<?php echo $footer;?>