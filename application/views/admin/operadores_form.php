<?php echo $header;?>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
	  <span>Formulario de edición de datos personales del operador.</span>
    </div>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    <div class="col-md-12"> 
		<!-- Tabs-->
			<div class="tabbable tabbable-custom tabs-left">
				<ul class="nav nav-tabs tabs-left">
					<li class="<?=!isset($_GET['tab'])?'active':'';?>"><a href="#basicos" data-toggle="tab">Datos Personales</a></li>
					<li class=""><a href="#bancarios" data-toggle="tab">Datos Bancarios</a></li>
					<? if(@$row->id != 1): //solo lo muestro para los q no sean Buenas Vibras ?>
					<li class="<?=@$_GET['tab']=='cta_cte'?'active':'';?>"><a href="#cta_cte" data-toggle="tab">Cuenta Corriente</a></li>
					<? endif; ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane <?=!isset($_GET['tab'])?'active':'';?>" id="basicos">
					
						  <div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos Personales</h3>
							</div>
							<div class="widget-content">
							  <div class="row">
								<div class="col-md-12"> 
								<?php $this->admin->showErrors(); ?>
										
								  <?php echo $this->admin->input('nombre', 'Nombre del Operador', '', $row, false); ?>
								  <?php echo $this->admin->input('razonsocial', 'Razón social', '', $row, false); ?>
								  <?php echo $this->admin->input('cuit', 'CUIT', '', $row, false); ?>
								  <?php echo $this->admin->input('legajo', 'Legajo', '', $row, false); ?>
								</div>
							  </div>
							</div>
						  </div>
					  </div>
					  
					<div class="tab-pane" id="bancarios">
					
						  <div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos Bancarios</h3>
							</div>
							<div class="widget-content">
							  <div class="row">
								<div class="col-md-12"> 
								<?php $this->admin->showErrors(); ?>

								  <?php echo $this->admin->input('titular', 'Titular', '', $row, false); ?>
								  <?php echo $this->admin->input('banco', 'Banco', '', $row, false); ?>
								  <?php echo $this->admin->input('moneda', 'Moneda', '', $row, false); ?>
								  <?php echo $this->admin->input('tipo_cuenta', 'Tipo de Cuenta', '', $row, false); ?>
								  <?php echo $this->admin->input('numero_cuenta', 'Número de Cuenta', '', $row, false); ?>
								  <?php echo $this->admin->input('cbu', 'CBU', '', $row, false); ?>
								  <?php echo $this->admin->input('alias', 'Alias', '', $row, false); ?>
								  <?php echo $this->admin->input('email_informar_pag', 'Email Informar Pagos', '', $row, false); ?>
								</div>
							  </div>
							</div>
						  </div>
					  </div>
					  
					  <? if(@$row->id != 1): //solo lo muestro para los q no sean Buenas Vibras ?>
						<div class="tab-pane <?=@$_GET['tab']=='cta_cte'?'active':'';?>" id="cta_cte">
							<?=@$cuenta_corriente;?>
						</div>
					  <? endif; ?>
					
				  </div>
			  </div>
            
			
			<div class="widget-content">
					<div class="row">
						<div class="col-md-12">
							<div class="widget-footer">
								<div class="actions">
									<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
									  <input type="submit" value="Grabar" class="btn btn-success" name="btnvolver">
									  <input type="button" value="Volver" class="btn btn-default" onclick="javascript: location.href='<?=$route;?>';">
								</div>
							</div>
						</div>
					</div>
				</div>
    </div>
  </div>

  </form>
    
<?php echo $footer; ?>