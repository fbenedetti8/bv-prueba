<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Los administradores son los usuarios con permiso de acceso al panel de administración.</span>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">	
			<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data" onsubmit="return validar();">
				<div class="widget box">
					<div class="widget-header">
						<h3>Propiedades</h3>
					</div>
					<div class="widget-content">
						
							<?php $this->admin->showErrors(); ?>
							
							<?php echo $this->admin->input('nombre', 'Nombre', '', $row, $required=true);?>
							<?php echo $this->admin->input('email', 'E-Mail', '', $row, $required=true);?>
							<?php echo $this->admin->input('usuario', 'Usuario', '', $row, $required=true);?>
							<?php echo $this->admin->password('password', 'Contraseña', 'required', isset($row->password)?$row->password:"");?>
							<?php echo $this->admin->checkbox('activo', 'Activo', '', $row, $required=true);?>
							<?php echo $this->admin->checkbox('cambio_password', 'Requerir cambio de contraseña', '', $row, $required=true);?>
					  		<?php echo $this->admin->combo('perfil', 'Perfil', 's2', $row, $perfiles, 'id', 'perfil'); ?>							
					  		
					  		<div id="boxvendedor" style="display:none">
					  			<?php echo $this->admin->combo('vendedor_id', 'Vendedor asociado', 's2 required', $row, $vendedores, 'id', 'nombreCompleto', true); ?>							
					  		</div>
					</div>
					<div class="widget-header widget-comisiones" style="border-top:solid 1px #CCC;">
						<h3 style="display:inline-block">Habilitar comisiones personalizadas</h3>
						<div style="display:inline-block; margin-left:10px; margin-bottom:20px;">
							<input type="checkbox" id="chkComisionesPersonalizadas" name="comisiones_personalizadas" value="1" <?=isset($row->id) && $row->comisiones_personalizadas ? 'checked' : '';?> />
						</div>
					</div>
					<div class="widget-content widget-comisiones">
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
						<h3 style="display:inline-block">Habilitar minimos no comisionables personalizadas</h3>
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
					                                      <input type="text" class="form-control min" name="valor_mnc[<?=$a;?>][<?=$i;?>]" value="<?=@$datos[$a][$i]['valor_mnc'];?>">
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
					                                      <input type="text" class="form-control min" name="valor_mnc[<?=$a;?>][<?=$i;?>]" value="<?=@$datos[$a][$i]['valor_mnc'];?>">
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
					<div class="widget-footer">
						<div class="actions">
							<input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />  
							<button type="submit" id="btnSubmit" class="btn btn-primary ladda-button" data-style="slide-left">
								<span class="ladda-label">
									Grabar
								</span>	
							</button>						
							<a class="btn btn-default" href="<?=$route;?>">Cancelar</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	
<script type="text/javascript">
	function validar(){
		var pass = document.getElementById('password').value;
		var pass2 = document.getElementById('password2').value;
		var error = "Por favor chequee su contraseña.";
		
		if(pass != pass2){
			alert(error);
			return false;
		}
		else{
			$("#formEdit").submit();
		}
		return false;
	}

	$(document).ready(function(){
		setInterval(function(){
			if (!$('#chkComisionesPersonalizadas').is(':checked')) {
				$('.comi').attr('disabled', 'disabled');
			}
			else {
				$('.comi').removeAttr('disabled');	
			}
		}, 1000);

		setInterval(function(){
			if (!$('#chkMinimosPersonalizados').is(':checked')) {
				$('.min').attr('disabled', 'disabled');
			}
			else {
				$('.min').removeAttr('disabled');	
			}
		}, 1000);

		$('body').on('change','#perfil', function(e){
			  var me = $(this);
			  show_hide_com(me.val());
			  show_hide_boxvendedor(me.val());
		  });
		  
		 show_hide_com($('#perfil').val());
		 show_hide_boxvendedor($('#perfil').val());
	});

	function show_hide_com(val){
		if(val == 'VEN'){
		  	$('.widget-comisiones').show();
		  }
		  else{
		  	$('.widget-comisiones').hide();
		  }
	}
	function show_hide_boxvendedor(val){
		if(val == 'VEN'){
		  	$('#boxvendedor').show();
		  }
		  else{
		  	$('#boxvendedor').hide();
		  }
	}
</script>
		
<?php echo $footer;	?>