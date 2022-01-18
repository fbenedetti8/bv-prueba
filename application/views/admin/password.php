<?php echo $header;?>

	<!--=== Page Header ===-->
	<? if (isset($_GET['error']) && $_GET['error'] == 1): ?>
	<div class="page-header">
		<div class="alert alert-danger fade in"> 
			<i class="icon-remove close" data-dismiss="alert"></i> 
			<strong>&iexcl;Atención</strong><br/>La contraseña actual ingresada no es correcta.
		</div>	
	</div>	
	<? elseif (isset($_GET['error']) && $_GET['error'] == 2): ?>
	<div class="page-header">
		<div class="alert alert-danger fade in"> 
			<i class="icon-remove close" data-dismiss="alert"></i> 
			<strong>&iexcl;Atención</strong><br/>El reingreso de contraseña no coincide con la nueva contraseña ingresada.
		</div>	
	</div>	
	<? elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
	<div class="page-header">
		<div class="alert alert-danger fade in"> 
			<i class="icon-remove close" data-dismiss="alert"></i> 
			<strong>&iexcl;Atención</strong><br/>La contraseña es demasiado corta. Debe tener al menos 8 caracteres.
		</div>	
	</div>			
	<? elseif (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
	<div class="page-header">
		<div class="alert alert-success fade in"> 
			<i class="icon-remove close" data-dismiss="alert"></i> 
			<strong>&iexcl;Operación completada!</strong> Su contraseña ha sido modificada.
		</div>	
	</div>			
	<? elseif ($first): ?>
	<div class="page-header">
		<div class="alert alert-warning fade in"> 
			<i class="icon-remove close" data-dismiss="alert"></i> 
			<strong>&iexcl;Cambiar contraseña</strong><br/>Por seguridad le recomendamos cambiar su contraseña de acceso al panel de administración.
		</div>	
	</div>
	<? else: ?>
	<br/>
	<? endif; ?>
	
	<div class="row">
		<div class="col-md-12">	
			<div class="widget box">
				<div class="widget-header">
					<div class="row">
						<div class="col-sm-12">
							<h3 style="margin:15px 0;">Generar una nueva contraseña</h3>
						</div>
					</div>
				</div>
				<div class="widget-content">
					<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/cambiar" enctype="multipart/form-data" onsubmit="return validar();">
						
							<?php $this->admin->showErrors(); ?>
							
							<div class="form-group">
								<label class="col-md-5 control-label">Contraseña actual <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="password" id="password" name="password" class="form-control  required" value="" style="">
								</div>
							</div>							
							<div class="form-group">
								<label class="col-md-5 control-label">Nueva contraseña <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="password" name="new_password" class="form-control required " size="30" value="">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-5 control-label">Reingrese su nueva contraseña <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="password" name="new_password2" class="form-control required " size="30" value="" >
								</div>
							</div>
							
							<div class="form-actions">
								<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
								<button type="submit" id="btnSubmit" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Cambiar Contraseña</span></button>
							</div>
							
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
<?php echo $footer;	?>