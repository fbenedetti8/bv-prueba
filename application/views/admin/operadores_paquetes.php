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
						<span>El listado muestra la totalidad de paquetes asociados al Operador.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?=current_url();?>" class="form-horizontal page-stats" style="padding-bottom:0; width:300px">
								
						<div class="form-group">
							<label class="col-md-2 control-label">Destino:</label>
							<div class="input-group col-md-10"> 
								
								<select name="destino_id" class="form-control" onchange="javascript: location.href = '<?=$route;?>/paquetes/<?=$operador->id;?>?destino_id='+this.value;">
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
						
						<button id="" class="btn btn-primary pull-right" onclick="javascript: location.href = '<?=$route;?>';" type="button" style="clear:both;">Volver a Operadores</button>		
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
							</div>
							
							<?
							/*
							Fecha de Salida	Cantidad reservas	
							Cantidad confirmadas	Cupo disponible	Saldo a cobrar	Opciones
							*/
							?>
							
							<div class="widget-content" style="display: block;">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?php echo $this->admin->th('codigo', 'Cód. Paquete', true);?>
											<?php echo $this->admin->th('nombre', 'Nombre', true);?>
											<?php echo $this->admin->th('fecha_inicio', 'Fecha de salida', true);?>
											<?php echo $this->admin->th('cantidad', 'Cantidad reservas', true);?>
											<?php echo $this->admin->th('confirmadas', 'Cantidad confirmadas', true);?>
											<?php echo $this->admin->th('cupo_disponible', 'Cupo disponible', true);?>
											<?php echo $this->admin->th('saldoAPagar', 'Saldo a pagar', false, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('', 'Alertas', false, array('text-align'=>'center'));?>
											<?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data->result() as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td($row->codigo);?>
											<?php echo $this->admin->td($row->nombre);?>
											<td align="center"><?=date('d/m/Y',strtotime($row->fecha_inicio));?></td>
											<td align="center">
												<span style="width:40px;display:inline-block;"><?=$row->cantidad?$row->cantidad:0;?></span>
												<button type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-html="true" data-placement="top" title="<?=$row->mujeres.' mujeres<br/>'.$row->hombres.' hombres';?>"><i class="glyphicon glyphicon-user"></i></button></td>
											<td align="center"><?=$row->confirmadas?$row->confirmadas:0;?></td>
											<td align="center"><?=$row->cupo_disponible;?></td>
											<td align="center"><?=($row->precio_usd?'USD ':'ARS ').number_format(@$row->saldoAPagar?$row->saldoAPagar:'0.00',2,',','.');?></td>
											<td style="text-align:center;">
												
												<? if(@$row->alarmas->alerta_cargar_costo_operador){ ?>
													<button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Falta cargar costo del paquete"><i class="glyphicon glyphicon-usd"></i></button>
												<? } ?>
											
											</td>
											<td>
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/edit/<?=$operador->id;?>?tab=cta_cte&paquete_id=<?=$row->id;?>"><i class="glyphicon glyphicon-usd" style="font-size:12px;"></i> Cuenta Corriente</a>
												
												<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/reservas/<?=$operador->id;?>/<?=$row->id;?>"><i class="glyphicon glyphicon-list" style="font-size:12px;"></i> Ver Reservas</a>
												
											</td>
										</tr>
										<?php endforeach; ?>
										<?php if (count($data->result()) == 0): ?>
										<tr>
											<td colspan="8" align="center" style="padding:30px 0;">
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
				});
				</script>

<?php echo $footer;?>