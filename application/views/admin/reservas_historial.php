						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">
								Historial de Reserva
								<a href="#" class="btn btn-sm btn-primary pull-right btnToggleHistorial">Agregar nuevo registro <i class="glyphicon glyphicon-chevron-down"></i></a>
							  </h3>
							</div>
							<div class="widget-content">
								
							<? if (isset($_GET['nuevoregistro'])): ?>	
								<div class="alert alert-success fade in">
									<i class="icon-remove close" data-dismiss="alert"></i>
									<strong>Nuevo registro en historial agregado con éxito!</strong> 
								</div>
							<? endif; ?>
				
								<div class="" id="agregar-historial" style="display:none;">
									<div class="col-md-12"><p>Completa los siguientes campos para agregar una nuevo registro en el historial</p></div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Tipo de acción:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" name="tipo_accion_id" id="tipo_accion_id" >
																<option value=""></option>
																<? foreach($tipos_acciones_editables as $s): ?>
																<option value="<?=$s->id;?>" <?=(isset($tipo_accion_id) && $tipo_accion_id == $s->id)?'selected':'';?>><?=$s->nombre;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Comentarios:</label>
													<textarea name="comentarios" id="comentarios" class="form-control" style="height:100px;"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><br><br></label>
													<button id="btnHist" class="btn btn-sm btn-success">Guardar</a>
													<button id="btnHist-loading" class="btn btn-success disabled hidden" data-loading-text="Guardando..." disabled="disabled">Guardando...</button>													
												</div>
											</div>
										</div>
									</div>
									<hr/>
								</div>
								
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped datatable">
									<thead>
										<tr>
											<?php echo $this->admin->th('fecha', 'Fecha', true);?>
											<?php echo $this->admin->th('admin', 'Usuario', true);?>
											<?php echo $this->admin->th('nombre', 'Nombre', true);?>
											<?php echo $this->admin->th('comentarios', 'Comentarios', true);?>
										</tr>
									</thead>
									<tbody>
										<?php foreach (@$historial->result() as $m): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td("<span class='hidden'>".$m->fecha."</span>".date('d/m/Y H:i',strtotime($m->fecha)).'hs');?>
											<?php echo $this->admin->td($m->admin);?>
											<?php echo $this->admin->td($m->nombre);?>
											<td>
												<?=$m->comentarios;?>
												<? if($m->data_updated): ?>
													<a href="<?=site_url('admin/reservas/ver_datos_comentario/'.$m->id)?>" class="various fancybox.ajax">Ver datos</a>

												<? endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						
<script type="text/javascript">
	$(document).ready(function(){
		$(".various").fancybox({
			maxWidth	: 640,
			maxHeight	: 500,
			fitToView	: false,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});

		$('body').on('click','.btnToggleHistorial',function(e){
			e.preventDefault();
			var me = $(this);
			$('#agregar-historial').slideToggle(); 
			
			if($(this).find('i').hasClass('glyphicon-chevron-down')){
				$(this).find('i').removeClass('glyphicon-chevron-down');
				$(this).find('i').addClass('glyphicon-chevron-up');
			}
			else if($(this).find('i').hasClass('glyphicon-chevron-up')){
				$(this).find('i').removeClass('glyphicon-chevron-up');
				$(this).find('i').addClass('glyphicon-chevron-down');
			}
		});
		
		$('body').on('click','#btnHist',function(e){
			e.preventDefault();
			var me = $(this);
			me.addClass("hidden");
			$("#btnHist-loading").removeClass("hidden");
			
			$.post("<?=$route;?>/grabar_historial",me.closest('form').serialize(),function(data){				
				if(data.status == 'success'){
					location.href = data.redirect;
				}
				else{
					$("#btnHist-loading").addClass("hidden");
					me.removeClass("hidden");
				}
			},'json');
		});
		
	});
</script>