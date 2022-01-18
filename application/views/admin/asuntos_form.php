<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
		</div>
	</div>
	<br/>
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

	<div class="row">
		<div class="col-md-12">	
			<div class="tabbable tabbable-custom tabbable-full-width">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-contenidos" data-toggle="tab">Contenidos</a></li>
				</ul>
				<div class="tab-content row">
					<div class="tab-pane active" id="tab-contenidos">
						<div class="col-md-12">
							<div class="widget box">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Propiedades</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12">	
											<?php echo $this->admin->input('codigo', 'Código de asunto', '', $row, false); ?>
											<?php echo $this->admin->input('email', 'E-Mail de recepción', '', $row, false); ?>
										</div>
									</div>
								</div>
								<div class="widget-header" style="border-top:solid 1px #CCC;">
									<h3 style="margin-bottom:20px;" class="pull-left">Atributos regionales</h3>

									<div class="btn-group pull-right" style="margin-top:13px;">
									  <button type="button" class="btn btn-default" id="btnIdioma"><img src="<?=base_url();?>media/admin/assets/img/es.png" />&nbsp;&nbsp;Español</button>
									  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    <span class="caret"></span>
									    <span class="sr-only">Seleccionar</span>
									  </button>
									  <ul class="dropdown-menu">
									  	<? foreach ($languages as $lang=>$language): ?>
									    <li><a href="#" class="btnOpcionIdioma" data-lang="<?=$lang;?>"><img src="<?=base_url();?>media/admin/assets/img/<?=$lang;?>.png" />&nbsp;&nbsp;<?=$language;?></a></li>
										<? endforeach; ?>
									  </ul>
									</div>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12">	
											<?php $this->admin->showErrors(); ?>
											
											<? foreach ($languages as $lang=>$language): ?>
											<div class="langfields <?=$lang;?> <?=$lang == 'es' ? '' : 'hide';?>">
												<?php echo $this->admin->input('asunto_'.$lang, 'Asunto', '', $row); ?>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-actions">
				<input type="hidden" name="id" value="<?=@$row->id;?>" />
				<input type="submit" value="Grabar" class="btn btn-primary">
				<input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
			</div>
		</div>

	</div>

	</form>

<script>
	$(document).ready(function(){
		$('.btnOpcionIdioma').click(function(e){
			e.preventDefault();
			$('#btnIdioma').html($(this).html());
			$('.langfields').addClass('hide');
			$('.' + $(this).data('lang')).removeClass('hide');
		});
	});
</script>
		
<?php echo $footer;	?>