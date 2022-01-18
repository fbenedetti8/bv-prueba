<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Formularios de contacto recibidos.</span>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">	
			<form id="formEdit" class="form-horizontal row-border" onsubmit="return false;">
				<div class="widget box">
					<div class="widget-content">
						
							<?php $this->admin->showErrors(); ?>
							
							<?php echo $this->admin->input('nombre', 'Nombre', '', $row, $required=true);?>
							<?php echo $this->admin->input('email', 'E-Mail', '', $row, $required=true);?>
							<?php echo $this->admin->input('telefono', 'Telefono', '', $row, $required=true);?>
							<?php echo $this->admin->input('asunto', 'Asunto', '', $row, $required=true);?>
							<?php echo $this->admin->textarea('consulta', 'Mensaje', '', $row, 5);?>
							<?php echo $this->admin->input('fecha', 'Fecha', '', $row, $required=true);?>
							<?php echo $this->admin->input('ip', 'Dirección IP', '', $row, $required=true);?>
							
					</div>
					<div class="widget-footer">
						<div class="actions">
							<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
							<a class="btn btn-default" href="<?=$route;?>">Volver</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
		
<?php echo $footer;	?>