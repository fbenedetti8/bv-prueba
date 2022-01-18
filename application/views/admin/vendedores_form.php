<?php echo $header;?>

<style type="text/css">
.select2-container, .select2-drop, .select2-search, .select2-search input{
    width: 100% !important;
    max-width: 100% !important;	
}
</style>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Formulario de edición de datos personales del vendedor.</span>
		</div>
	</div>
	
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data" onsubmit="return validar();">
			
	<div class="row">
		<div class="col-md-12">	
			
			<!-- Tabs-->
			<div class="tabbable tabbable-custom "><!--tabs-left-->
				<ul class="nav nav-tabs con-borde"><!--tabs-left-->
					<li class="<?=(@$_GET['tab']=='cta_cte')?'':'active';?>"><a href="#basicos" data-toggle="tab">Datos</a></li>
					<li class="lnkTabCoordinador"><a href="#coordinador" data-toggle="tab">Datos Coordinador</a></li>
					<li class="<?=@$_GET['tab']=='cta_cte'?'active':'';?>"><a id="lnkTabCta" href="#cta_cte" data-toggle="tab">Cuenta Corriente</a></li>
					<li class="<?=@$_GET['tab']=='comisiones'?'active':'';?>"><a id="lnkTabComisiones" href="#comisiones" data-toggle="tab">Comisiones</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane <?=(@$_GET['tab']=='cta_cte')?'':'active';?>" id="basicos">
				
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos Personales</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
								
										<?php $this->admin->showErrors(); ?>
										
										<div class="alert alert-info msgCoordinador">
											<p><strong>Datos del coordinador</strong>: Si el vendedor es un coordinador, no olvides de completar los datos adicionales solicitados en el tab "Datos Coordinador".</p>
										</div>

										<?php echo $this->admin->checkbox('es_coordinador', 'Es Coordinador?', '', $row, $mode='', $hint="", $attr=array());?>

										<?php echo $this->admin->input('nombre', 'Nombre', '', $row, $required=true);?>
										<?php echo $this->admin->input('apellido', 'Apellido', '', $row, $required=true);?>
										<?php echo $this->admin->input('email', 'E-Mail', '', $row, $required=true);?>
										<?php echo $this->admin->input('telefono', 'Teléfono', '', $row, $required=true);?>

										<?php echo $this->admin->input('cuil', 'CUIL', '', $row, $required=true);?>
										<!--
										<?php echo $this->admin->combo('sucursal_id', 'Sucursal', 's2', $row, $sucursales, 'id', 'nombre',false); ?>
										-->

										<?php echo $this->admin->password('password', 'Contraseña', 'required', isset($row->password)?$row->password:"");?>
							
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="coordinador">
						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Datos Personales</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										
										<?php echo $this->admin->input('dni', 'DNI', 'onlynum', $row, $required=false, FALSE, "", "", '', 0,'',false,'number');?>

										<?php echo $this->admin->input('pasaporte', 'Pasaporte', '', $row, $required=false);?>
									
										<?php 
										$aux = @$row->fecha_emision; 
										$aux = ($aux == '0000-00-00') ? '' : $aux;
										$row->fecha_emision = '';
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_emision = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										echo $this->admin->datepicker('fecha_emision', 'Fecha de Emisión', '', $row, $required=false,false,false);?>

										<?php 
										$aux = @$row->fecha_vencimiento; 
										$aux = ($aux == '0000-00-00') ? '' : $aux;
										$row->fecha_vencimiento = '';
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_vencimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										echo $this->admin->datepicker('fecha_vencimiento', 'Fecha de Vencimiento', '', $row, $required=false,false,false);?>

										<?php 
										$aux = @$row->fechaNacimiento; 
										$aux = ($aux == '0000-00-00') ? '' : $aux;
										$row->fechaNacimiento = '';
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fechaNacimiento = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
										echo $this->admin->datepicker('fechaNacimiento', 'Fecha de Nacimiento', '', $row, $required=false,false,false);?>

										<?php echo $this->admin->combo('nacionalidad_id', 'Nacionalidad', 's2', $row, $paises, 'id', 'nombre',false); ?>

										<?php echo $this->admin->input('direccion', 'Domicilio', '', $row, $required=false);?>
									
										<?php echo $this->admin->combo('dieta', 'Tipo de Dieta', 's2', $row, $this->config->item('dieta'), 'id', 'id',false); ?>

										<?php echo $this->admin->input('talle', 'Talle de ropa', '', $row, $required=false);?>

										<?php echo $this->admin->textarea('observaciones', 'Observaciones', '', $row);?>
									
									</div>
								</div>
							</div>

							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">Contacto de Emergencia</h3>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-md-12">
										<?php echo $this->admin->input('emergencia_nombre', 'Nombre', '', $row, $required=false);?>
										<?php echo $this->admin->input('emergencia_telefono_codigo', 'Codigo Area', '', $row, $required=false);?>
										<?php echo $this->admin->input('emergencia_telefono_numero', 'Numero', '', $row, $required=false);?>

									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="tab-pane <?=@$_GET['tab']=='cta_cte'?'active':'';?>" id="cta_cte">
						<? if($cuenta_corriente){ 
							echo $cuenta_corriente;
						} else{ ?>
							<div class="alert alert-info">Debes guardar los datos del vendedor para luego poder acceder a la cuenta corriente.</div>
						<? } ?>
					</div>

					<div class="tab-pane <?=(@$_GET['tab']=='comisiones')?'':'active';?>" id="comisiones">
						
						<div class="widget box">
							<div class="widget-header">
								<h3 style="display:inline-block">Habilitar comisiones personalizadas</h3>
								<div style="display:inline-block; margin-left:10px; margin-bottom:20px;">
									<input type="checkbox" id="chkComisionesPersonalizadas" name="comisiones_personalizadas" value="1" <?=isset($row->id) && $row->comisiones_personalizadas ? 'checked' : '';?> />
								</div>
							</div>
							<div class="widget-content">
		                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
		                        <thead>
		                          <tr>
		                            <? foreach ($escalas as $escala): ?>
		                              <?php echo $this->admin->th('escala', $escala->nombre, false,array('text-align'=>'center'));?>
		                            <? endforeach; ?>
		                          </tr>
		                        </thead>
		                        <tbody>
		                          <tr class="<?php echo alternator('odd', 'even');?>">
		                            <? $i=0; foreach ($escalas as $escala): $i+=1; ?>
		                            <td>
		                                <div class="form-group" style="border:0;padding: 5px 0;">
		                                  <label class="col-md-6 control-label">Comisión</label>
		                                  <div class="col-md-6">
		                                    <div class="input-group">
		                                      <input type="text" class="form-control comi" name="comision<?=$i;?>" value="<?=@$row->{'comision'.$i};?>">
		                                      <span class="input-group-addon">%</span>
		                                    </div>    
		                                  </div>
		                                </div>
		                                <div class="form-group" style="border:0;padding: 5px 0;">
		                                  <label class="col-md-6 control-label">Com. equipo</label>
		                                  <div class="col-md-6">
		                                    <div class="input-group">
		                                      <input type="text" class="form-control comi" name="comision<?=$i;?>_eq" value="<?=@$row->{'comision'.$i.'_eq'};?>">
		                                      <span class="input-group-addon">%</span>
		                                    </div>    
		                                  </div>
		                                </div>
		                            </td>
		                            <? endforeach;?>
		                          </tr>                          
		                        </tbody>
		                      </table>
		                    </div>

		                    <div class="widget-header widget-comisiones" style="border-top:solid 1px #CCC;">
								<h3 style="display:inline-block">Habilitar minimos no comisionables personalizados</h3>
								<div style="display:inline-block; margin-left:10px; margin-bottom:20px;">
									<input type="checkbox" id="chkMinimosPersonalizados" name="minimos_personalizados" value="1" <?=isset($row->id) && $row->minimos_personalizados ? 'checked' : '';?> />
								</div>
							</div>
							<div class="widget-content widget-comisiones">
		                      	
		                      	<div class="tabbable tabbable-custom tabs-left">
									<ul class="nav nav-tabs tabs-left">
										<? $anos = array(); 
											$anos[] = date('Y');
											$anos[] = date('Y', strtotime('+1 years')); 
										?>
										<? foreach ($anos as $a) { ?>
											<li class="<?=$a==date('Y')?'active':'';?>"><a href="#ano<?=$a;?>" data-toggle="tab">Año <?=$a;?></a></li>
										<? } ?>
									</ul>
									<div class="tab-content">
										<? foreach ($anos as $a): ?>
										<div class="tab-pane <?=$a==date('Y')?'active':'';?>" id="ano<?=$a;?>">

											<div class="col-md-6">

							                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped table-border" style="    width: 230px;">
							                        <thead>
							                          <tr>
							                            <?php echo $this->admin->th('mes', 'Mes', false,array('text-align'=>'center'));?>
							                            <?php echo $this->admin->th('valor_mnc', 'Valor minimo', false,array('text-align'=>'center'));?>
							                          </tr>
							                        </thead>
							                        <tbody>
							                          <? for($i=1;$i<=6;$i++): ?>
							                          <tr class="<?php echo alternator('odd', 'even');?>">
							                            <td align="center"><?=$i;?></td>

							                            <td>
							                                <div class="form-group" style="border:0;padding: 5px 0;">
							                                  <div class="col-md-10" style="margin: 0 auto;float: none;">
							                                    <div class="input-group">
							                                      <span class="input-group-addon">$</span>
							                                      <input type="text" class="form-control mnc" name="valor_mnc[<?=$a;?>][<?=$i;?>]" value="<?=@$datos[$a][$i]['valor_mnc'];?>">
							                                    </div>    
							                                  </div>
							                                </div>
							                            </td>
							                            
							                            

							                          </tr>
							                          <?php endfor; ?>
							                          
							                        </tbody>
							                      </table>
							                    </div> 


							                    <div class="col-md-6">

							                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped table-border" style="    width: 230px;">
							                        <thead>
							                          <tr>
							                            <?php echo $this->admin->th('mes', 'Mes', false,array('text-align'=>'center'));?>
							                            <?php echo $this->admin->th('valor_mnc', 'Valor minimo', false,array('text-align'=>'center'));?>
							                          </tr>
							                        </thead>
							                        <tbody>
							                          <? for($i=7;$i<=12;$i++): ?>
							                          <tr class="<?php echo alternator('odd', 'even');?>">
							                            <td align="center"><?=$i;?></td>

							                            <td>
							                                <div class="form-group" style="border:0;padding: 5px 0;">
							                                  <div class="col-md-10" style="margin: 0 auto;float: none;">
							                                    <div class="input-group">
							                                      <span class="input-group-addon">$</span>
							                                      <input type="text" class="form-control mnc" name="valor_mnc[<?=$a;?>][<?=$i;?>]" value="<?=@$datos[$a][$i]['valor_mnc'];?>">
							                                    </div>    
							                                  </div>
							                                </div>
							                            </td>
							                            
							                            

							                          </tr>
							                          <?php endfor; ?>
							                          
							                        </tbody>
							                      </table>
							                    </div> 

										</div>
										<? endforeach; ?>
									</div>
								</div>


		                    </div>	
		                </div>	

					</div>

				</div>
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
	
<script type="text/javascript">
$(document).ready(function(){
	
	$('#formEdit').on('submit', function(){
		if ($(this).hasClass('validado')) {
			return true;
		}

		var pass = document.getElementById('password').value;
		var pass2 = document.getElementById('password2').value;
		var error = "Por favor chequee su contraseña.";
		
		if(pass != pass2){
			bootbox.alert(error);
			return false;
		}

		$.post('<?=$route.'/validar';?>', $(this).serialize(), function(result){
			if (result.success) {
				$('#formEdit').addClass('validado');
				$('#formEdit').submit();
			}
			else {
				bootbox.alert('<h3 style="margin:0 0 15px">No fue posible grabar, se detectaron errores:</h3><div class="alert alert-danger">' + result.error + '</div>');
			}
		}, "json");

		return false;
	});

	$('#es_coordinador').on('switchChange.bootstrapSwitch', function (event, state) {
		var me = $(this);
		show_hide_coordinador(me.is(':checked'));
	});

	show_hide_coordinador($('#es_coordinador').is(':checked'));

	$(document).ready(function(){
		setInterval(function(){
			if (!$('#chkComisionesPersonalizadas').is(':checked')) {
				$('.comi').attr('disabled', 'disabled');
			}
			else {
				$('.comi').removeAttr('disabled');	
			}

			if (!$('#chkMinimosPersonalizados').is(':checked')) {
				$('.mnc').attr('disabled', 'disabled');
			}
			else {
				$('.mnc').removeAttr('disabled');	
			}
		}, 1000);
	});
});	

function show_hide_coordinador(checked){
	console.log(checked);
	if(checked){
		$('.lnkTabCoordinador').removeClass('hidden');
		$('.msgCoordinador').removeClass('hidden');
	}
	else{
		$('.lnkTabCoordinador').addClass('hidden');
		$('.msgCoordinador').addClass('hidden');
	}
}

</script>

<?php echo $footer;	?>