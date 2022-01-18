<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Esta sección le permite configurar parámetros generales de administración del sitio.</span>
		</div>
	</div>
	
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data" onsubmit="return validar();">
			
	<div class="row">
		<div class="col-md-12">	
			<!-- Tabs-->
			<div class="tabbable tabbable-custom tabs-left">
				<ul class="nav nav-tabs tabs-left">
					<li class="active"><a href="#tipo_cambio" data-toggle="tab">Tipo de Cambio</a></li>
					<li><a href="#contactos" data-toggle="tab">Destinatario de Contactos</a></li>
					<li><a href="#cta_bancaria" data-toggle="tab">Cuenta Bancaria</a></li>
					<li><a href="#mercadopago" data-toggle="tab">Mercado Pago</a></li>
					<li><a href="#paypal" data-toggle="tab">Paypal</a></li>
					<li><a href="#financiacion" data-toggle="tab">Financiación Oficina</a></li>
					<li><a href="#ordenes" data-toggle="tab">Ordenes</a></li>
					<li><a href="#backups" data-toggle="tab">Backups</a></li>
					<li><a href="#conversion" data-toggle="tab">Código de Conversión</a></li>
					<li><a href="#quienessomos" data-toggle="tab">Video Quiénes Somos</a></li>
					<li><a href="#mailings" data-toggle="tab">Destinatario de Mailings</a></li>
					<li><a href="#impuestopais" data-toggle="tab">Impuesto PAIS</a></li>
				</ul>
				<div class="tab-content">
					
					<div class="tab-pane active" id="tipo_cambio">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Cotización del Dólar</h3>
							</div>
							<div class="widget-content">
								<? 
								/*
								$url = "http://apilayer.net/api/live?access_key=".$this->config->item('currency_api_key')."&currencies=ARS&format=1"; 
								$cont = file_get_contents($url);
								$cont = json_decode($cont);
								$cotizacion = @$cont->quotes->USDARS;
								*/
								
								$cotizacion = cotizacion_dolar();
								?>
								<input type="hidden" id="dolar_oficial" value="<?=$cotizacion;?>"/>
								<!--<p>Sitio de referencia: <a href="https://currencylayer.com">https://currencylayer.com</a></p>-->
								<p>Cotización actual: 1 USD = <b>ARS <?=$cotizacion;?></b></p>
								<div class="alert alert-warning">
									Tené en cuenta que el "Valor de la divisa a utilizar" se actualiza automáticamente por sistema cada 1 hora.<br>
									<b>Sólo tienes que especificar un porcentaje de ajuste a aplicar sobre la cotización actual.</b>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label">Porcentaje de ajuste sobre divisa <span class="required">*</span></label>
									<div class="col-md-9 input-group">
										<span class="input-group-addon">%</span>
										<input type="text" id="cotizacion_ajuste" name="cotizacion_ajuste" class="form-control onlydecimal required " value="<?=@$row->cotizacion_ajuste;?>" placeholder="" style="">
										<span><small><b></b></small></span>
										<label for="cotizacion_ajuste" generated="true" class="has-error help-block" style="display:none;"></label>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label">Valor de la divisa a utilizar <span class="required">*</span></label>
									<div class="col-md-9 input-group">
										<span class="input-group-addon">ARS</span>
										<input readonly type="text" id="cotizacion_dolar" name="cotizacion_dolar" class="form-control onlydecimal required " value="<?=@$row->cotizacion_dolar;?>" placeholder="" style="">
										<span><small><b></b></small></span>
										<label for="cotizacion_dolar" generated="true" class="has-error help-block" style="display:none;"></label>
									</div>
								</div>
							</div>		
						</div>	
					</div>	
					
					<div class="tab-pane" id="contactos">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Destinatario de Contactos</h3>
							</div>
							<div class="widget-content">
								<?php echo $this->admin->input('email_contacto', 'Dirección de E-mail', '', $row, $required=false);?>
							</div>		
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Destinatario de Contactos de Agencias</h3>
							</div>
							<div class="widget-content">
								<?php echo $this->admin->input('email_agencias', 'Dirección de E-mail', '', $row, $required=false);?>
							</div>		
						</div>	
					</div>

					<div class="tab-pane" id="cta_bancaria">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Datos de las cuentas bancarias</h3>
							</div>
							<div class="widget-content">
								<?php echo $this->admin->file('file_datos_cuenta', 'Archivo PDF, DOC o DOCX', '', @$row->file_datos_cuenta, '/uploads/config/'.@$row->id.'/', $type = 'view',$attributes=""); ?>							  
							</div>						
						</div>	
					</div>
						
					<div class="tab-pane" id="mercadopago">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Gastos Administrativos de Mercadopago</h3>
							</div>
							<div class="widget-content">
								<?php $row->mp_gastos_admin = $row->mp_gastos_admin > 0 ? ($row->mp_gastos_admin-1)*100 : 0;
								echo $this->admin->input('mp_gastos_admin', 'Indique el valor en %', 'onlydecimal', $row, $required=true);?>
							</div>		
						</div>	
					</div>	
					
					<div class="tab-pane" id="paypal">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Gastos Administrativos de Paypal</h3>
							</div>
							<div class="widget-content">
								<?php $row->pp_gastos_admin = $row->pp_gastos_admin > 0 ? ($row->pp_gastos_admin)*100 : 0;
								echo $this->admin->input('pp_gastos_admin', '% del total de venta', 'onlydecimal', $row, $required=true);?>

								<?php echo $this->admin->input('pp_gastos_admin_fijos', 'Monto fijo en USD', 'onlydecimal', $row, $required=true);?>
							</div>		
						</div>	
					</div>	
					
					<div class="tab-pane" id="financiacion">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Financiación Cuotas Oficina</h3>
							</div>
							<div class="widget-content">
								<div class="alert alert-warning">
									El formato del importe del Coeficiente debe ser del estilo: Ej, 1.0012 <b>(separados los decimales por un punto, NO con coma)</b>.
									<br>Verificar antes de guardar los cambios.
								</div>
								
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
									<thead>
										<tr>
											<th>Tarjeta</th>
											<th style="text-align:center;"># Cuota</th>
											<th style="text-align:center;">Coeficiente</th>
											<th style="text-align:center;">CFT</th>
											<th style="text-align:center;">TEA</th>
											<th style="text-align:center;">TEM</th>
										</tr>
									</thead>
									<tbody>
										
										<? foreach($cuotas_oficina as $c): ?>					
											<tr class="<?php echo alternator('odd', 'even');?> tr_adicional">
												<td><?=$c->tarjeta;?></td>
												<td style="text-align:center;"><?=$c->cuotas;?></td>
												<td style="text-align:center;">
													<input style="width:60px;margin: 0 auto;" type="text" name="mp_id[<?=$c->id;?>]" value="<?=$c->coeficiente;?>" class="form-control mp_id"/>
												</td>
												<td style="text-align:center;">
													<input style="width:60px;margin: 0 auto;" type="text" name="cft[<?=$c->id;?>]" value="<?=$c->cft;?>" class="form-control mp_id"/>
												</td>
												<td style="text-align:center;">
													<input style="width:60px;margin: 0 auto;" type="text" name="tea[<?=$c->id;?>]" value="<?=$c->tea;?>" class="form-control mp_id"/>
												</td>
												<td style="text-align:center;">
													<input style="width:60px;margin: 0 auto;" type="text" name="tem[<?=$c->id;?>]" value="<?=$c->tem;?>" class="form-control mp_id"/>
												</td>
											</tr>
										<? endforeach; ?>	
									</tbody>
								</table>
							</div>		
						</div>	
					</div>	
					
					<div class="tab-pane" id="ordenes">
						<div class="widget box">						
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Vigencia de las Ordenes</h3>
							</div>
							<div class="widget-content">
								<?php echo $this->admin->input('horas_orden', 'Minutos de vigencia', 'onlynum', $row, $required=true);?>
							</div>						
						</div>	
					</div>	
							
					<div class="tab-pane" id="backups">
						<div class="widget box">						
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Backups del sistema</h3>
							</div>
							<div class="widget-content">
								
								<div class="alert alert-info">
									<p>La realización de backups puede demorar algunos minutos, no cerrar las ventanas abiertas ni abandonar la página actual mientras se están generando.</p>
								</div>
								
								<div class="row">

									<div class="col-md-12">
										<p>Para realizar un backup de la <b>Base de Datos</b> haz click en el siguiente botón:</p>
										<a class="btn btn-primary btnback" target="_blank" href="<?=site_url('admin/config/backup_db');?>">
											Generar Backup
										</a>
									</div>
								</div>
								<hr/>
								<div class="row">
									<div class="col-md-12">
										<p>Para realizar un backup de los <b>Archivos del Sistema</b> haz click en el siguiente botón:</p>
										<a class="btn btn-primary btnback" target="_blank" href="<?=site_url('admin/config/backup_system');?>">
											Generar Backup
										</a>
									</div>
								</div>
								<hr/>
								<div class="row">
									<div class="col-md-12">
										<p>Para realizar un backup de los <b>Archivos MEDIA</b> haz click en el siguiente botón:</p>
										<a class="btn btn-primary btnback" target="_blank" href="<?=site_url('admin/config/backup_media');?>">
											Generar Backup
										</a>
									</div>
								</div>
							</div>						
						</div>	
					</div>	
						
					<div class="tab-pane" id="conversion">
						<div class="widget box">						
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Código de conversión</h3>
							</div>
							<div class="widget-content">								
								<div class="row">
									<div class="col-md-12">
									  <textarea name="script_conversion" style="width:100%; height:150px;"><?=@$row->script_conversion;?></textarea>										
									</div>
								</div>
							</div>						
						</div>	
					</div>

					<div class="tab-pane" id="quienessomos">
						<div class="widget box">						
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Quiénes Somos</h3>
							</div>
							<div class="widget-content">	
								<div class="alert alert-info"><b>Atención</b>: Debes indicar la url completa del video en Youtube</div>

								<div class="row">
									<div class="col-md-12">	
										<?php echo $this->admin->input('video_quienessomos', 'URL Video', '', $row, $required=false);?>								
									</div>
								</div>
							</div>						
						</div>	
					</div>


					<div class="tab-pane" id="mailings">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Destinatario de Mailings</h3>
							</div>
							<div class="widget-content">
								<div class="alert alert-info">
									Puedes especificar varias direcciones de mail separadas por coma (,) a donde quieres que se entreguen las copias de dichos correos.<br>
									Si no se especifica ninguno, el sistema enviará a <b>reservas@buenas-vibras.com.ar</b> como hasta ahora.
								</div>

								<?php echo $this->admin->input('email_mailings', 'Dirección de E-mail', '', $row, $required=false);?>
							</div>		
						</div>	
					</div>

					<div class="tab-pane" id="impuestopais">
						<div class="widget box">
							<div class="widget-header">
							  <h3 class="pull-left" style="margin-bottom:20px;">Alcance del impuesto PAIS</h3>
							</div>
							<div class="widget-content">
								<div class="alert alert-info">
									Especifica a que tipo de moneda aplica el impuesto PAIS para los pagos recibidos.
								</div>

								<div class="form-group">	
									<label class="col-md-3 control-label">Selecciona moneda</label>
								
									<div class="col-md-9 input-group">	
										<select name="alcance_impuesto_pais" class="form-control">
											<option value="ambas" <?=$row->alcance_impuesto_pais=='ambas'?'selected':'';?>>Ambas</option>
											<option value="ars" <?=$row->alcance_impuesto_pais=='ars'?'selected':'';?>>Pesos Argentinos</option>
											<option value="usd" <?=$row->alcance_impuesto_pais=='usd'?'selected':'';?>>Dolares</option>
										</select>
									</div>		
								</div>		
							</div>		
						</div>	
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
	</div>
	
	</form>
	
<div id="cargarPopup" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_grupales']['width'];?>x<?=$this->uploads['imagen_grupales']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/config/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<div id="cargarPopupMobile" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_mobile_grupales']['width'];?>x<?=$this->uploads['imagen_mobile_grupales']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/config/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<iframe src="<?=site_url('admin/config/iframe');?>" id="uploader" name="uploader" style="position:absolute; left:-10000px;"></iframe>

<script type="text/javascript">
var current_field = '';
$(document).ready(function(){
	$('#cotizacion_ajuste').change(function(){
		var ajuste = $(this).val();
		var dolar = $('#dolar_oficial').val();
		dolar = dolar*(1+ajuste/100);
		dolar = dolar.toFixed(2);
		$('#cotizacion_dolar').val(dolar);
	});
	
	$('.btnback').click(function(e){
		$(this).text('Generando...').attr('disabled',true);
	});

	$('.btnRemoveImage').click(function(){
		var me = this;
		bootbox.confirm("Esta seguro que desea borrar la imagen?", function(result){
			if (result) {
				$('#' + $(me).data('ref')).val('');
				$('#preview_' + $(me).data('ref')).attr('src', $('#preview_' + $(me).data('ref')).data('placeholder'));
			}
		});
	});

	  $('.btnPopupImage').click(function(e){
		e.preventDefault();
		current_field = $(this).data('ref');
		$('#cargarPopup #field').val(current_field);

		bootbox.dialog({
		  message: $('#cargarPopup').html(),
		  title: 'Cargar imagen',
		  buttons: {
			success: {
			  label: "Cerrar",
			  className: "btn-primary"
			}
		  }
		});
	  });

	  $('.btnPopupImageMobile').click(function(e){
		e.preventDefault();
		current_field = $(this).data('ref');
		$('#cargarPopupMobile #field').val(current_field);

		bootbox.dialog({
		  message: $('#cargarPopupMobile').html(),
		  title: 'Cargar imagen',
		  buttons: {
			success: {
			  label: "Cerrar",
			  className: "btn-primary"
			}
		  }
		});
	  });

	  $('body').on('change', '.modal-dialog input', function(){
		var bar = $('.bar');
		var percent = $('.percent');
			var percentVal = '0%';
			bar.width(percentVal)
			percent.html(percentVal);

		$('.modal-dialog form').ajaxForm({
		  url: '<?=site_url('admin/config/upload');?>',
			beforeSend: function() {
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);

				$('.modal-dialog input').hide();
				$('.modal-dialog .progress').show();
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal)
				percent.html(percentVal);
			//console.log(percentVal, position, total);
			},
			success: function() {
				var percentVal = '100%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
		  complete: function(xhr) {
			var response = $.parseJSON(xhr.responseText);
			if (response.success) {
			  $('#' + current_field).val(response.filename);
			  $('#preview_' + current_field).attr('src', response.url);
			  bootbox.hideAll();
			}
			else {
			  $('.modal-dialog .cartel').removeClass('alert-info').addClass('alert-danger');
			  $('.modal-dialog .cartel span').text(response.error).show();
			  $('.modal-dialog input').show();
			  $('.modal-dialog .progress').hide();          
			}
		  }
		});

		$('.modal-dialog form').submit();
	});
	
	$('body').on('keyup, keydown, input','.mp_id',function(){
		var me = this; 
		Remplaza(me,$(me).val());
	});
});  

function Remplaza(obj,entry) {
	out = ","; // reemplazar el ,
	add = "."; // por .
	temp = "" + entry;
	while (temp.indexOf(out)>-1) {
		pos= temp.indexOf(out);
		temp = "" + (temp.substring(0, pos) + add + 
		temp.substring((pos + out.length), temp.length));
	}
	//document.subform.texto.value = temp;
	$(obj).val(temp);
}
</script>
		
<?php echo $footer;	?>