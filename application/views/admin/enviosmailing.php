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
						<span>Listado de mailings generados.</span>
					</div>
					
					<form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:300px; padding-bottom:10px;">
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

				<div class="alert alert-info">
					<b>Envíos automáticos</b>: El envío de los mailings restantes se hace de forma automática desde el servidor mediante un proceso que ejecuta en forma periódica cada 30 minutos. Sin embargo, se puede hacer de forma manual a través de la opción correspondiente en la columna <b>Opciones</b>.
				</div>

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?></h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<form id="formList">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<?=$this->admin->th('nombre', 'Paquete', true);?>
											<?=$this->admin->th('estado', 'Estado Reserva', true);?>
											<?=$this->admin->th('fecha_inicio', 'Fecha Salida', true);?>
											<?=$this->admin->th('asunto', 'Asunto mail', true);?>
											<?=$this->admin->th('envios_hechos', 'Envios hechos', true);?>
											<?=$this->admin->th('envios_restantes', 'Envios restantes', true);?>
											<?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'200px'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data->result() as $row): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?=$this->admin->td($row->nombre);?>
											<?=$this->admin->td($row->estado);?>
											<?=$this->admin->td(date('d/m/Y',strtotime($row->fechaSalida)));?>
											<?=$this->admin->td($row->asunto);?>
											<?=$this->admin->td($row->envios_hechos);?>
											<?=$this->admin->td($restantes = ($row->inscriptos_paquete)-($row->envios_hechos));?>
											<td>
												<input type="hidden" name="id[]" value="<?=$row->id;?>" />

												<? if($restantes > 0) {?>
													<a href="#" data-href="<?php echo $route;?>/enviarRestantes/<?=$row->id;?>" rel="<?=$row->id;?>" class="icon-location-arrow send-restantes"> &nbsp;Enviar restantes</a>
												<? } ?>

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
								</form>
								
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
		

<script type="text/javascript">
	$(document).ready(function(){
		//send-restantes

		$(".send-restantes").click(function(e){
			e.preventDefault();
			var me = this;
			var href = $(me).attr('data-href');

			  bootbox.confirm("Esta seguro que desea enviar los mails restantes?", function(result){
				if (result) {
					  location.href = href;
				}
			  });
			  return false;
		});
	});
</script>

<?php echo $footer;?>