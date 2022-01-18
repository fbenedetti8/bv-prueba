<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Formulario de edición de datos personales del usuario.</span>
		</div>
	</div>
	
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">
			
	<div class="row">
		<div class="col-md-12">	
			<!-- Tabs-->
			<div class="tabbable tabbable-custom tabs-left">
				<ul class="nav nav-tabs tabs-left">
					<li class="<?=!isset($_GET['tab'])?'active':'';?>"><a href="#basicos" data-toggle="tab">Datos Personales</a></li>
					<li><a href="#documentacion" data-toggle="tab">Documentación</a></li>
					<li><a href="#emergencia" data-toggle="tab">Contacto Emergencia</a></li>
					<li class="<?=@$_GET['tab']=='cta_cte'?'active':'';?>"><a href="#cta_cte" data-toggle="tab">Cuenta Corriente</a></li>
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
										
										<?php echo $this->admin->input('nombre', 'Nombre', '', $row, $required=true);?>
										<?php echo $this->admin->input('apellido', 'Apellido', '', $row, $required=true);?>
										<?php echo $this->admin->input('email', 'E-Mail', '', $row, $required=true);?>
										
										<?php $aux = $row->fecha_nacimiento != '0000-00-00' ? $row->fecha_nacimiento : ''; 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_nacimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										else{
											$row->fecha_nacimiento = "";
										}
											echo $this->admin->datepicker('fecha_nacimiento', 'Fecha Nacimiento', '', $row, true, false, false); ?>
										
										<?php echo $this->admin->combo('sexo', 'Sexo', 's2', $row, $sexos, 'id', 'nombre',false); ?>
										<?php echo $this->admin->combo('nacionalidad_id', 'Nacionalidad', 's2', $row, $paises, 'id', 'nombre',false); ?>
										<?php echo $this->admin->input('celular_codigo', 'Celular Código', 'onlynum', $row, $required=true);?>
										<?php echo $this->admin->input('celular_numero', 'Celular Número', 'onlynum', $row, $required=true);?>
										<?php echo $this->admin->combo('dieta', 'Dieta', 's2', $row, $dietas, 'nombre', 'nombre',false); ?>
										<?php echo $this->admin->input('timestamp', 'Fecha creación', '', $row, $required=true);?>
										<?php echo $this->admin->input('ip', 'Dirección IP', '', $row, $required=true);?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane " id="documentacion">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Documentación</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<?php echo $this->admin->input('dni', 'DNI', 'onlynum', $row, $required=true);?>
										<?php echo $this->admin->input('pasaporte', 'Pasaporte', '', $row, $required=true);?>
										<?php echo $this->admin->combo('pais_emision_id', 'País Emision', 's2', $row, $paises, 'id', 'nombre',false); ?>
										
										<?php $aux = ($row->fecha_emision != '0000-00-00' ? $row->fecha_emision : ""); 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_emision = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										else{
											$row->fecha_emision = "";
										}
										
											echo $this->admin->datepicker('fecha_emision', 'Fecha Emisión', '', $row, true, false, false); ?>
										
										
										<?php $aux = ($row->fecha_vencimiento != '0000-00-00' ? $row->fecha_vencimiento : ""); 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_vencimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										else{
											$row->fecha_vencimiento = "";
										}
											echo $this->admin->datepicker('fecha_vencimiento', 'Fecha Vencimiento', '', $row, true, false, false); ?>
										
										
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane " id="emergencia">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Contacto de Emergencia</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<?php echo $this->admin->input('emergencia_nombre', 'Nombre', '', $row, $required=true);?>
										<?php echo $this->admin->input('emergencia_telefono_codigo', 'Teléfono Código', 'onlynum', $row, $required=true);?>
										<?php echo $this->admin->input('emergencia_telefono_numero', 'Teléfono Número', 'onlynum', $row, $required=true);?>
									</div>
								</div>
							</div>
									
						</div>
					</div>
					
					<div class="tab-pane <?=@$_GET['tab']=='cta_cte'?'active':'';?>" id="cta_cte">
						<?=@$cuenta_corriente;?>
					</div>
					
				</div>
			</div>
			
				<div class="widget-content">
					<div class="row">
						<div class="col-md-12">
							<div class="widget-footer">
								<div class="actions">
									<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
									  <input type="submit" value="Grabar" class="btn btn-success" name="btnvolver">
									  <input type="button" value="Volver" class="btn btn-default" onclick="history.back();">
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
		
	</form>
	
<?php echo $footer;	?>

<script>
$(document).ready(function(){
	$('#fecha_nacimiento.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es"
	});
	$('#fecha_nacimiento.datepicker').attr('readonly',true);
	$('#fecha_vencimiento.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es"
	});
	$('#fecha_vencimiento.datepicker').attr('readonly',true);
	$('#fecha_emision.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es"
	});
	$('#fecha_emision.datepicker').attr('readonly',true);
});
</script>