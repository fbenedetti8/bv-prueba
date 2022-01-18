<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Las regiones permiten segmentar el contenido disponible en el sitio web.</span>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">	
			<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data" onsubmit="return validar();">
				<div class="widget box">
					<div class="widget-header">
						<h3 style="margin-bottom:20px;">Opciones</h3>
					</div>
					<div class="widget-content">
							
						<?php $this->admin->showErrors(); ?>
						
						<?php echo $this->admin->combo('pais', 'País', 's2', $row, $paises, 'iso2', 'Nombre', false, false, ''); ?>

						<div class="form-group">
							<label class="col-md-3 control-label">Países Alias</label>
							<div class="col-md-9">
								<select size="1" name="paises_alias[]" class="s2 wide" multiple>
									<? foreach ($paises->result() as $pais): ?>
									<option value="<?=$pais->iso2;?>" <?=isset($row->id) && in_array($pais->iso2, $row->paises_alias) ? 'selected' : '';?>><?=$pais->Nombre;?></option>
									<? endforeach; ?>
								</select>
							</div>
						</div>

					</div>
					<div class="widget-header" style="border-top:solid 1px #CCC;">
						<h3 style="margin-bottom:20px;">Universal Assistance</h3>
					</div>
					<div class="widget-content">

						<?php echo $this->admin->checkbox('site_ua', 'Activar región', 'chk', $row, false); ?>

						<?php echo $this->admin->input('convenio_ua', 'Código de Convenio Viajeros', '', $row, $required=false);?>
						<?php echo $this->admin->input('organizacion_ua', 'Código de Organización Viajeros', '', $row, $required=false);?>

						<?php echo $this->admin->input('organizacion_ag_ua', 'Código de Organización Agencias', '', $row, $required=false);?>

						<?php echo $this->admin->combo('idioma_ua', 'Idioma por defecto', 's2', $row, $idiomas, 'iso', 'nombre', false, false, ''); ?>

						<div class="form-group">
							<label class="col-md-3 control-label">Idiomas Disponibles</label>
							<div class="col-md-9">
								<label class="checkbox">
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Español</div>
										<input type="checkbox" name="idioma_es_ua" value="1" class="uniform" <?=@$row->idioma_es_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Español Latino</div>
										<input type="checkbox" name="idioma_la_ua" value="1" class="uniform" <?=@$row->idioma_la_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Inglés</div>
										<input type="checkbox" name="idioma_en_ua" value="1" class="uniform" <?=@$row->idioma_en_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Portugués</div>
										<input type="checkbox" name="idioma_pt_ua" value="1" class="uniform" <?=@$row->idioma_pt_ua ? 'checked' : '';?> />
									</div>
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Accesos Disponibles</label>
							<div class="col-md-9">
								<label class="checkbox">
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Cotizador</div>
										<input type="checkbox" name="cotizador_ua" value="1" class="uniform" <?=@$row->cotizador_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Ingreso Agencias</div>
										<input type="checkbox" name="agencias_ua" value="1" class="uniform" <?=@$row->agencias_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Cotizador Agencias</div>
										<input type="checkbox" name="cotizador_ag_ua" value="1" class="uniform" <?=@$row->cotizador_ag_ua ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Chat Ventas</div>
										<input type="checkbox" name="chat_ua" value="1" class="uniform" <?=@$row->chat_ua ? 'checked' : '';?> />
									</div>
								</label>
							</div>
						</div>

						<?php echo $this->admin->file('archivo_tarifas_ua', 'Archivo Tarifas', '', isset($row->archivo_tarifas_ua)?$row->archivo_tarifas_ua:"", isset($row->id)?'/uploads/tarifas/'.$row->id.'/':'');?>

						<?php echo $this->admin->input('web_ua', 'Website Externo', '', $row, $required=false);?>
						<?php echo $this->admin->input('facebook_ua', 'Facebook', '', $row, $required=false);?>
						<?php echo $this->admin->input('twitter_ua', 'Twitter', '', $row, $required=false);?>
						<?php echo $this->admin->input('instagram_ua', 'Instagram', '', $row, $required=false);?>
						<?php echo $this->admin->input('googleplus_ua', 'Google Plus', '', $row, $required=false);?>
						<?php echo $this->admin->input('linkedin_ua', 'LinkedIn', '', $row, $required=false);?>
						<?php echo $this->admin->input('youtube_ua', 'YouTube', '', $row, $required=false);?>

					</div>
					<div class="widget-header" style="border-top:solid 1px #CCC;">
						<h3 style="margin-bottom:20px;">Travel Ace</h3>
					</div>
					<div class="widget-content">

						<?php echo $this->admin->checkbox('site_ta', 'Activar región', 'chk', $row, false); ?>

						<?php echo $this->admin->input('convenio_ta', 'Código de Convenio Viajeros', '', $row, $required=false);?>
						<?php echo $this->admin->input('organizacion_ta', 'Código de Organización Viajeros', '', $row, $required=false);?>
						<?php echo $this->admin->input('organizacion_ag_ta', 'Código de Organización Agencias', '', $row, $required=false);?>

						<?php echo $this->admin->combo('idioma_ta', 'Idioma por defecto', 's2', $row, $idiomas, 'iso', 'nombre', false, false, ''); ?>

						<div class="form-group">
							<label class="col-md-3 control-label">Idiomas Disponibles</label>
							<div class="col-md-9">
								<label class="checkbox">
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Español</div>
										<input type="checkbox" name="idioma_es_ta" value="1" class="uniform" <?=@$row->idioma_es_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Español Latino</div>
										<input type="checkbox" name="idioma_la_ta" value="1" class="uniform" <?=@$row->idioma_la_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Inglés</div>
										<input type="checkbox" name="idioma_en_ta" value="1" class="uniform" <?=@$row->idioma_en_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Portugués</div>
										<input type="checkbox" name="idioma_pt_ta" value="1" class="uniform" <?=@$row->idioma_pt_ta ? 'checked' : '';?> />
									</div>
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Accesos Disponibles</label>
							<div class="col-md-9">
								<label class="checkbox">
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:15px;">Cotizador</div>
										<input type="checkbox" name="cotizador_ta" value="1" class="uniform" <?=@$row->cotizador_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Ingreso Agencias</div>
										<input type="checkbox" name="agencias_ta" value="1" class="uniform" <?=@$row->agencias_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Cotizador Agencias</div>
										<input type="checkbox" name="cotizador_ag_ta" value="1" class="uniform" <?=@$row->cotizador_ag_ta ? 'checked' : '';?> />
									</div>
									<div class="col-sm-3">
										<div style="padding:5px; text-indent:0px;">Chat Ventas</div>
										<input type="checkbox" name="chat_ta" value="1" class="uniform" <?=@$row->chat_ta ? 'checked' : '';?> />
									</div>
								</label>
							</div>
						</div>

						<?php echo $this->admin->file('archivo_tarifas_ta', 'Archivo Tarifas', '', isset($row->archivo_tarifas_ta)?$row->archivo_tarifas_ta:"", isset($row->id)?'/uploads/tarifas/'.$row->id.'/':'');?>

						<?php echo $this->admin->input('web_ta', 'Website Externo', '', $row, $required=false);?>
						<?php echo $this->admin->input('facebook_ta', 'Facebook', '', $row, $required=false);?>
						<?php echo $this->admin->input('twitter_ta', 'Twitter', '', $row, $required=false);?>
						<?php echo $this->admin->input('instagram_ta', 'Instagram', '', $row, $required=false);?>
						<?php echo $this->admin->input('googleplus_ta', 'Google Plus', '', $row, $required=false);?>
						<?php echo $this->admin->input('linkedin_ta', 'LinkedIn', '', $row, $required=false);?>
						<?php echo $this->admin->input('youtube_ta', 'YouTube', '', $row, $required=false);?>

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
		
<?php echo $footer;	?>