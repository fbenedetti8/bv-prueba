<?php echo $header;?>

<style>
.chkb .form-group { padding:0; }
.seleccion .col-md-3 { width:100%; text-align:left; }
.seleccion .col-md-9 { width:100%; text-align:left; margin-top:5px; }
.seleccion .col-md-9 span.select2 { width:100% !important; }
.btn-add-alojamiento { margin-top: 35px; }
.btn-add-regimen { margin-top: 35px; }
.btn-add-habitacion { margin-top: 35px; }
.btn-add-adicional { margin-top: 35px; }
.btn-add-combinaciones { margin-top: 35px; }
.btn-add-parada { margin-top: 35px; }
#loading { display:none; margin:0 auto; width:440px; padding: 20px; font-size:14px; font-weight:bold; }
.chk_name {     
	display: inline-block;
    width: 60%;
    vertical-align: middle;
	padding-left: 10px; 
}
#basicos .chk_name {     
	display: inline-block;
    width: 100%;
    vertical-align: middle;
	padding: 0; 
	text-align:center;
}
#basicos .bootstrap-switch { margin: 5px auto 0; display: block; }
.bootstrap-duallistbox-container label { height:40px; }
.bootstrap-duallistbox-container .filter { 
    height: 40px;
    margin: 20px 0 0;
    width: 95%;
    left: 15px;
}
.select2-container{ max-width:none; }
.addon-porcentaje { width: 25px; display: inline-block; padding: 6px 5px 7px; }
.monto_minimo { height: 28px !important; margin-left: 10px; display: inline-block; width: 100px; text-align: right; }
.con-borde li { border-right: 1px solid #ccc; }
</style>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
      <h5><?='Desde el '.formato_fecha($this->data['row']->fecha_inicio).' al '.formato_fecha($this->data['row']->fecha_fin);?></h5>
    </div>
  </div>
  <? if(@$this->data['row']->alerta_cupos_revisar){ ?>
 	 <div class="alert alert-warning fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		<strong>Revisión de cupos</strong> Este paquete tiene cupos pendientes de revisión. Revisar y actualizar el Catálogo de Transportes y Alojamientos para dicho paquete.
		<a href="<?=site_url('admin/paquetes/borrar_alerta_cupo/'.$this->data['row']->id);?>" class="btn btn-sm btn-info">Eliminar alerta</a>
	  </div>
  <? } ?>

  <? if (!count($combinaciones)): ?>
	<div class="alert alert-warning fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		<strong>Atención: </strong>No olvide generar las combinaciones de precios para que el paquete sea correctamente visualizado.
	</div>
  <? endif; ?>

  <?php if ($saved): ?>
	  <br/>
	  <div class="alert alert-success fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		<strong>&iexcl;Operación completada!</strong> Los datos fueron guardados con éxito.
	  </div>
	<?php endif; ?>
  <?php if (isset($_GET['error'])&&$_GET['error']): ?>
	  <br/>
	  <div class="alert alert-warning fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		<strong>&iexcl;Operación incompleta!</strong> Debes completar los datos obligatorios (*).
	  </div>
	<?php endif; ?>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">
  
	<div class="row">	
	
	<div class="col-md-12">
		<!-- Tabs-->
		<div class="tabbable tabbable-custom tabs-left">
			<ul class="nav nav-tabs tabs-left">
				<? if (perfil() == 'ADM'): ?>
				<li><a href="#adicionales" data-toggle="tab">Adicionales</a></li>
				<li class="<?=isset($_GET['tab']) && $_GET['tab'] =='precios'?'active':'';?>"><a href="#precios" data-toggle="tab">Precios</a></li>
				<? else: ?>					
				<li class="<?=!isset($_GET['tab'])?'active':'';?>"><a href="#basicos" data-toggle="tab">Datos</a></li>
				<li><a href="#documentaciones" data-toggle="tab">Documentaciones</a></li>
				<li><a href="#imagenes" data-toggle="tab">Imagen y Video</a></li>
				<li><a href="#caracteristicas" data-toggle="tab">Características</a></li>
				<li><a href="#excursiones" data-toggle="tab">Excursiones</a></li>
				<li><a href="#medios" data-toggle="tab">Pagos</a></li>
				<li><a href="#promociones" data-toggle="tab">Promociones</a></li>
				<li><a href="#lugares" data-toggle="tab">Salidas</a></li>
				<li><a href="#paradas" data-toggle="tab">Paradas</a></li>
				<li><a href="#alojamientos" data-toggle="tab">Alojam. y Transp.</a></li>
				<li><a href="#adicionales" data-toggle="tab">Adicionales</a></li>
				<li><a href="#regimenes" data-toggle="tab">Comidas</a></li>
				<li class="<?=isset($_GET['tab']) && $_GET['tab'] =='precios'?'active':'';?>"><a href="#precios" data-toggle="tab">Precios</a></li>
				<? endif; ?>
			</ul>
			<div class="tab-content">
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>
				<div class="tab-pane" id="basicos">
											 
					  <div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Propiedades</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							   <?php echo $this->admin->input('nombre', 'Nombre de paquete (interno)', '', $row, true); ?>

							  <?php echo $this->admin->input('nombre_visible', 'Nombre de paquete (frontend)', '', $row, true, false, '', '','',35,'',false,'text'); ?>
							  
							  <div class="form-group">
								<label class="col-md-3 control-label">Slug <span class="required">*</span></label>
								<div class="col-md-9">
									<span class="input-group-addon form-control" style="display: inline-block;width: auto;float: left;line-height: 18px;">/<?=@$row->destino_slug;?>/</span>
									<input type="text" id="slug" name="slug" class="form-control  required " style="    display: inline-block;width: inherit;" value="<?=@$row->slug;?>" placeholder="" style="">
									<span><small><b></b></small></span>
									<label for="slug" generated="true" class="has-error help-block" style="display:none;"></label>
								</div>
							</div>
							  
							  <?php echo $this->admin->input('codigo', 'Código Paquete', '', $row, true, true); ?>
							  <?php echo $this->admin->combo('destino_id', 'Destino', 's2', $row, $destinos, 'id', 'nombre', true); ?>
							  
							  <div class="form-group">
								<label class="col-md-3 control-label">Creado por</label>
								<div class="col-md-9">
									<?=$row->autor;?>
									<br/>
									<small><?=date('d/m/Y h:i A', strtotime($row->fecha_creacion));?></small>
								</div>
							  </div>
							  
							  <?php $fecha_inicio = explode('-',$row->fecha_inicio);
							  $row->fecha_inicio = $fecha_inicio[2].'/'.$fecha_inicio[1].'/'.$fecha_inicio[0];
							  echo $this->admin->datepicker('fecha_inicio', 'Fecha Inicio', '', $row, true, false, false); ?>
							  
							  <?php $fecha_fin= explode('-',$row->fecha_fin);
							  $row->fecha_fin = $fecha_fin[2].'/'.$fecha_fin[1].'/'.$fecha_fin[0];
							  echo $this->admin->datepicker('fecha_fin', 'Fecha Fin', '', $row, true, false, false); ?>
							</div>
						  </div>
						  
						  <div class="row form-group chkb"> 
									<label class="col-sm-2 col-md-2 col-lg-2">
											<div class="chk_name">Activo</div>
											<input type="checkbox" name="activo" value="1" <?=$row->activo ? 'checked' : '';?> />
									</label>
									<label class="col-sm-2 col-md-2 col-lg-2">
											<div class="chk_name">Visible</div>
											<input type="checkbox" name="visible" value="1" <?=$row->visible ? 'checked' : '';?> />
									</label>		
									<label class="col-sm-2 col-md-2 col-lg-2">
											<div class="chk_name">Grupal</div>
											<input type="checkbox" name="grupal" value="1" <?=$row->grupal ? 'checked' : '';?> />
									</label>						
									<label class="col-sm-3 col-md-3 col-lg-3">
											<div class="chk_name">Fecha indefinida</div>
											<input type="checkbox" name="fecha_indefinida" value="1" <?=$row->fecha_indefinida ? 'checked' : '';?> />
									</label>					
									<label class="col-sm-3 col-md-3 col-lg-3">
											<div class="chk_name">Mostrar calendario</div>
											<input type="checkbox" name="mostrar_calendario" value="1" <?=$row->mostrar_calendario ? 'checked' : '';?> />
									</label>		
						  </div>	
						  <div class="row form-group chkb"> 
								<label class="col-sm-6 col-md-4 col-lg-3 col-lg-offset-1">
									<div class="chk_name">Precio en USD</div>
									<input type="checkbox" name="precio_usd" value="1" <?=$row->precio_usd ? 'checked' : '';?> />
							    </label>
								<label class="col-sm-6 col-md-4 col-lg-3">
									<div class="chk_name">Exterior</div>
									<input type="checkbox" name="exterior" id="exterior" value="1" <?=$row->exterior ? 'checked' : '';?> />
							    </label>
								<label class="col-sm-6 col-md-4 col-lg-3">
									<div class="chk_name">Confirmación inmediata</div>
									<input type="checkbox" name="confirmacion_inmediata" value="1" <?=$row->confirmacion_inmediata ? 'checked' : '';?> />
							    </label>
						  </div>	
						  <div class="row">
							<div class="col-md-12">
							  
							  <?php echo $this->admin->textarea('descripcion', 'Descripción del paquete', '', $row, false); ?>
							  
							  <?php echo $this->admin->file('itinerario', 'Archivo del Itinerario', '', @$row->itinerario, '/uploads/'.$currentModule.'/'.@$row->id.'/', $type = 'view',$attributes=""); ?>

							</div>
						  </div>
						  						
						</div>
						
						<div class="widget-header impuesto30 <?=@$row->exterior?'':'hidden';?>" style="border-top:solid 1px #CCC;">
							<h3 style="margin-bottom:20px;" class="pull-left">Percepción Impuesto PAIS<br/><small style="font-size:12px">Calculado sobre servicios en el exterior.</small></h3>
						</div>
						
						<div class="widget-content impuesto30 <?=@$row->exterior?'':'hidden';?>">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-md-2 control-label">Base imponible <span class="required">*</span></label>
										<div class="col-md-2 input-group">	
											<input type="text" id="base_imponible_pais" name="base_imponible_pais" class="form-control onlydecimal  required " value="<?=@$row->base_imponible_pais;?>" placeholder="Ej 1320.00" style="">
											<span><small><b></b></small></span>
											<label for="base_imponible_pais" generated="true" class="has-error help-block" style="display:none;"></label>
										</div>
										<label class="col-md-1 control-label">Tasa <span class="required">*</span></label>
										<div class="col-md-2 input-group">	
											<input type="text" id="tasa_impuesto_pais" name="tasa_impuesto_pais" class="form-control onlydecimal required " value="<?=@$row->tasa_impuesto_pais;?>" placeholder="Ej 30.00" style="">
											<span class="input-group-addon">%</span>
											<span><small><b></b></small></span>
											<label for="tasa_impuesto_pais" generated="true" class="has-error help-block" style="display:none;"></label>
										</div>
										<label class="col-md-2 control-label">Impuesto <span class="required">*</span></label>
										<div class="col-md-2 input-group">	
											<input type="text" id="impuesto_pais" name="impuesto_pais" class="form-control onlydecimal required " readonly value="<?=@$row->impuesto_pais;?>" placeholder="Ej 30.00" style="">
											<span><small><b></b></small></span>
											<label for="impuesto_pais" generated="true" class="has-error help-block" style="display:none;"></label>
										</div>
										
									</div>	
								</div>
							</div>
						</div>

						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;" class="pull-left">Disponibilidad del Paquete
							<br><small style="font-size:12px">Indique la cantidad de lugares totales y disponibles del paquete que se mostrarán de cara al usuario en el sitio.</small>
						  </h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<div class="alert alert-info">
								El cupo disponible real surge de restar al cupo total del paquete, la cantidad de reservas recibidas.<br>
								El cupo disponible personalizado, solo será visible configurando la opción "mostrar cupo personalizado". Este valor no podrá ser mayor al cupo disponible real.
								</div>
							</div>
							<div class="col-md-12"> 
								<div class="form-group">
									<label class="col-sm-6 col-md-4 col-lg-3">
										<div class="chk_name">Mostrar cupo personalizado</div>
										<input type="checkbox" name="cupo_paquete_personalizado" value="1" <?=$row->cupo_paquete_personalizado ? 'checked' : '';?> />
									</label>
									<label class="col-sm-6 col-md-4 col-lg-3">
										<div class="chk_name">Cupo disponible real</div>
										<input readonly type="number" id="cupo_paquete_disponible_real" name="cupo_paquete_disponible_real" class="form-control   text-center" style="width:100px;margin:5px auto;background: #eeeeee;" value="<?=$row->cupo_paquete_disponible_real>=0?$row->cupo_paquete_disponible_real:$row->cupo_disponible;?>" placeholder="" style="">
									</label>
									<label class="col-sm-6 col-md-4 col-lg-3">
										<div class="chk_name">Cupo disponible</div>
										<input type="number" id="cupo_paquete_disponible" name="cupo_paquete_disponible" class="form-control   text-center" style="width:100px;margin:5px auto;" value="<?=$row->cupo_paquete_disponible;?>" placeholder="" style="">
									</label>
									<label class="col-sm-6 col-md-4 col-lg-3">
										<div class="chk_name">Cupo total </div>
										<input type="number" id="cupo_paquete_total" name="cupo_paquete_total" class="form-control   text-center" style="width:100px;margin:5px auto;" value="<?=$row->cupo_paquete_total;?>" placeholder="" style="">
									</label>
									
								</div>
							</div>
						  </div>
						</div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Sub Categorías Estacionales</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->combo('estacionales[]', 'Seleccionar Subcategorías', 's3 comboEstacionales', $row, $estacionales, 'id', 'nombre',false,true); ?>
							</div>
						  </div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
							<h3 style="margin-bottom:20px;" class="pull-left">Calculador de cuotas<br/>
							<small style="font-size:12px">Indique si deseas habiltar o deshabilitar el calculador de cuotas de Mercado Pago y el pago en oficina para el paquete.</small></h3>
						</div>
						
						<div class="widget-content">
							<div class="row">
								<div class="col-sm-12">
									
									<div class="row form-group chkb"> 
										<label class="col-sm-3 col-lg-offset-2">
											<div class="chk_name">Cuotas de Mercado Pago</div>
											<input type="checkbox" name="calculador_cuotas" value="1" <?=$row->calculador_cuotas ? 'checked' : '';?> />
										</label>
										<label class="col-sm-3">
											<div class="chk_name">Pago en oficina</div>
											<input type="checkbox" name="pago_oficina" value="1" <?=$row->pago_oficina ? 'checked' : '';?> />
										</label>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
							<h3 style="margin-bottom:20px;" class="pull-left">Operador y vouchers<br/>
							<small style="font-size:12px">Indique en qué fecha se debe mostrar el alerta de vouchers pendientes de carga.</small></h3>
						</div>
						
						<div class="widget-content">
							<div class="row">
								<div class="col-sm-12">
									
									<?php echo $this->admin->combo('operador_id', 'Operador', 's2', $row, $operadores, 'id', 'nombre', true); ?>
									<?php echo $this->admin->input('cantidad_vouchers', 'Cantidad de Vouchers a cargar', 'onlynum', $row, true, FALSE, "", "", '', 0,'',false,'number'); ?>
							  
									<?php $aux = $row->fecha_limite_vouchers; 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_limite_vouchers = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
											echo $this->admin->datepicker('fecha_limite_vouchers', 'Fecha Alerta Vouchers', '', $row, true, false, false); ?>

									<?php $tv = array(array('id'=>'0','valor'=>'Envío Manual'),array('id'=>'1','valor'=>'Envío Automático del sistema'));
									echo $this->admin->combo('voucher_automatico', 'Tipo de voucher', 's2', $row, $tv, 'id', 'valor', true); ?>

								</div>
							</div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
							<h3 style="margin-bottom:20px;" class="pull-left">Datos de pasajeros completos<br/>
							<small style="font-size:12px">Indique en qué fecha deben estar todos los datos de los pasajeros completados.</small></h3>
						</div>
						
						<div class="widget-content">
							<div class="row">
								<div class="col-sm-12">
									<?php $aux = $row->fecha_limite_completar_datos; 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_limite_completar_datos = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
											echo $this->admin->datepicker('fecha_limite_completar_datos', 'Fecha Alerta Datos Completos', '', $row, true, false, false); ?>
								</div>
							</div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
							<h3 style="margin-bottom:20px;" class="pull-left">Pago del viaje completo<br/>
							<small style="font-size:12px">Indique en qué fecha debe estar saldada la totalidad del viaje.</small></h3>
						</div>
						
						<div class="widget-content">
							<div class="row">
								<div class="col-sm-12">
									<?php $aux = $row->fecha_limite_pago_completo; 
										if($aux != ''){
											$aux = explode('-',$aux);
											$row->fecha_limite_pago_completo = $aux[2].'/'.$aux[1].'/'.$aux[0];
										}
									echo $this->admin->datepicker('fecha_limite_pago_completo', 'Fecha Alerta Pago Completo', '', $row, true, false, false); ?>
								</div>
							</div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Información adicional</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->textarea('aclaraciones', 'Comentarios especiales para el usuario', '', $row, false); ?>
							</div>
						  </div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Celulares asociados</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->combo('celulares[]', 'Seleccionar teléfono', 's2 comboCelulares', $row, $celulares, 'id', 'nombreCompleto',false,true); ?>
							</div>
						  </div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Coordinadores asociados</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->combo('coordinadores[]', 'Seleccionar coordinador', 's2 comboCoordinadores', $row, $coordinadores, 'id', 'nombreCompleto',false,true); ?>
							</div>
						  </div>
						</div>
						
						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Código de Conversión</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <textarea name="script_conversion" style="width:100%; height:150px;"><?=@$row->script_conversion;?></textarea>
							</div>
						  </div>
						</div>	

						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Inicio del viaje</h3>
						</div>
						<div class="widget-content">
						  <div class="alert alert-info"><b>Nota</b>: Este texto es el que se visualizará en el listado de viajes próximos del sitio, a la derecha de la fecha de inicio y fin del viaje</div>
	
						  <div class="row">						  	 
							<div class="col-md-12">			  
							 
							  <?php echo $this->admin->textarea('detalle_inicio', 'Ingresar el texto', '', $row, false); ?>

							</div>
						  </div>
						</div>	
								
						

					</div>
				
				</div>
				<? endif; ?>

				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>		
				<div class="tab-pane" id="imagenes">
						
					<div class="alert alert-info">
						<b>Imagen del paquete</b>: Esta imagen se utilizará para mostrar en los listados de la <b>página Intermedia</b> y la de <b>Próximos Viajes</b>. Si no está cargada, se tomará por defecto la imagen del Destino asociado.
					</div>

					  <div class="widget box">
						<div class="widget-header">
						  <h3 class="pull-left" style="margin-bottom:20px;">Imagen de paquete</h3>
						  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen_listado">Quitar</button>
						</div>
						<div class="widget-content" style="background:#CCC; text-align:center;">
						  <a href="#" class="btnPopupImage" data-ref="imagen_listado">
								<?php if(isset($row->id) && $row->imagen_listado){ ?>
									<img id="preview_imagen_listado" src="<?php echo 'https://media-buenasvibras.s3.amazonaws.com/'.$row->imagen_listado ?>" class="img-responsive" style="margin:0 auto;" />
								<?php }else{ ?>
									<img id="preview_imagen_listado" src="<?php echo 'https://via.placeholder.com/'.$this->uploads['imagen_listado']['width'].'x'.$this->uploads['imagen_listado']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen_listado']['width'];?>x<?=$this->uploads['imagen_listado']['height']; ?>" alt="">
								<?php } ?>
							</a>
						  <input type="hidden" id="imagen_listado" name="imagen_listado" value="<?=@$row->imagen_listado;?>" />
						</div>
						

						<div class="widget-header" style="border-top:solid 1px #CCC;">
						  <h3 style="margin-bottom:20px;">Video de paquete</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->input('url_video', 'URL de video<br><small>Fuente Youtube</small>', '', $row, false); ?>
							</div>
						  </div>
						</div>
						
					  </div>
				</div>

				<div class="tab-pane" id="documentaciones">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Documentaciones</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <? foreach ($documentaciones as $dd): ?>
							  <label class="col-sm-6" style="margin:10px 0;">
								<input type="checkbox" name="documentaciones[]" value="<?=$dd->id;?>" <?=in_array($dd->id, $mis_documentaciones) ? 'checked' : '';?> />
								<div class="chk_name"><?=$dd->nombre;?></div>
							  </label>
							  <? endforeach; ?>
							  </div>
						  </div>
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->textarea('documentacion_requerida', 'Documentación requerida', '', $row, false); ?>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>

				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="caracteristicas">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Características que incluye el paquete</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							
							<? if(FALSE): ?>
							<div class="col-md-12"> 
							  <? foreach ($caracteristicas as $dd): ?>
							  <label class="col-sm-6" style="margin:10px 0;">
								<input type="checkbox" name="caracteristicas[]" value="<?=$dd->id;?>" <?=in_array($dd->id, $mis_caracteristicas) ? 'checked' : '';?> />
								<div class="chk_name"><?=$dd->nombre;?></div>
							  </label>
							  <? endforeach; ?>
							</div><!-- implementacion anterior sin criterio de orden -->
							<? endif; ?>

							<div class="col-sm-5">
								
									<select id="carac_multiselect" multiple="" name="from_caracteristicas[]" class="form-control" style="height:310px;">
										<? foreach ($caracteristicas as $dd): ?>
											<option value="<?=$dd->id;?>"><?=str_replace('<br>',' ',$dd->nombre);?></option>
										<? endforeach; ?>
									</select>
									
								</div>

								 <div class="col-sm-2">
							        <button type="button" id="carac_multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
							        <button type="button" id="carac_multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
							        <button type="button" id="carac_multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
							        <button type="button" id="carac_multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
							    </div>
							    
							    <div class="col-sm-5">
							    	<!-- aca voy a ir guardando los elementos que agrego para mandar por POST -->
							    	<input type="hidden" id="caracteristicas_arr" name="caracteristicas_arr" value="">

							        <select name="caracteristicas[]" id="carac_multiselect_to" class="form-control" size="8" multiple="multiple" style="height:310px;">
							        	<? foreach($mis_caracteristicas as $mc): ?>
											<option value="<?=$mc['id'];?>"><?=str_replace('<br>',' ',$mc['nombre']);?></option>
										<? endforeach; ?>
							        </select>
							 
							        <div class="row">
							            <div class="col-sm-6">
							                <button type="button" id="carac_multiselect_move_up" class="btn btn-block"><i class="glyphicon glyphicon-arrow-up"></i></button>
							            </div>
							            <div class="col-sm-6">
							                <button type="button" id="carac_multiselect_move_down" class="btn btn-block col-sm-6"><i class="glyphicon glyphicon-arrow-down"></i></button>
							            </div>
							        </div>
							    </div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="excursiones">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Excursiones</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
								
							    <div class="col-sm-5">
							
									<select id="multiselect" multiple="" name="from_excursiones[]" class="form-control" style="height:310px;">
										<? foreach($excursiones as $e): ?>
											<option value="<?=$e->excursion_id;?>"><?=$e->nombre;?></option>
										<? endforeach; ?>
									</select>
								
								</div>

								 <div class="col-sm-2">
							        <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
							        <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
							        <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
							        <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
							    </div>
							    
							    <div class="col-sm-5">
							    	<!-- aca voy a ir guardando los elementos que agrego para mandar por POST -->
							    	<input type="hidden" id="excursiones_arr" name="excursiones_arr" value="">

							        <select name="excursiones[]" id="multiselect_to" class="form-control" size="8" multiple="multiple" style="height:310px;">
							        	<? foreach($mis_excursiones as $e): ?>
											<option value="<?=$e['id'];?>"><?=$e['nombre'];?></option>
										<? endforeach; ?>
							        </select>
							 
							        <div class="row">
							            <div class="col-sm-6">
							                <button type="button" id="multiselect_move_up" class="btn btn-block"><i class="glyphicon glyphicon-arrow-up"></i></button>
							            </div>
							            <div class="col-sm-6">
							                <button type="button" id="multiselect_move_down" class="btn btn-block col-sm-6"><i class="glyphicon glyphicon-arrow-down"></i></button>
							            </div>
							        </div>
							    </div>



							</div>
						  </div>
						</div>
						
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="medios">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Medios de pago</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <? foreach ($medios as $dd): ?>
							  <label class="col-sm-6" style="margin:10px 0;">
								<input type="checkbox" name="medios[]" value="<?=$dd->id;?>" <?=in_array($dd->id, $mis_medios) ? 'checked' : '';?> />
								<div class="chk_name"><?=$dd->nombre;?></div>
							  </label>
							  <? endforeach; ?>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="promociones">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Promociones</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <? foreach ($promociones as $dd): ?>
							  <label class="col-sm-6" style="margin:10px 0;">
								<input type="checkbox" name="promociones[]" value="<?=$dd->id;?>" <?=in_array($dd->id, $mis_promociones) ? 'checked' : '';?> />
								<div class="chk_name"><?=$dd->nombre;?></div>
							  </label>
							  <? endforeach; ?>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="lugares">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Lugares de salida</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <? foreach ($lugares as $dd): ?>
							  <label class="col-sm-6" style="margin:10px 0;">
								<input type="checkbox" name="lugares[]" class="selSalida" value="<?=$dd->id;?>" <?=in_array($dd->id, $mis_lugares) ? 'checked' : '';?> />
								<div class="chk_name"><?=$dd->nombre;?></div>
							  </label>
							  <? endforeach; ?>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="paradas">
					
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Paradas del transporte</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-5 seleccion"> 
								<?php echo $this->admin->combo('parada_id', 'Seleccionar Parada', 's2', $row, $paradas, 'id', 'nombrecompleto'); ?>
							</div>
							<div class="col-md-2 seleccion"> 
								<?php echo $this->admin->input('hora', 'Hora', '', $row, false, false, '', '', '', 0, 'HH:mm'); ?>
							</div>
							<div class="col-md-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-parada">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <?php echo $this->admin->th('parada', 'Parada', false);?>
									  <?php echo $this->admin->th('hora', 'Hora', false);?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblParadas">
									<?php foreach ($mis_paradas as $r): ?>
										<?=paradas_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_paradas) == 0): ?>
									<tr>
									  <td colspan="3" align="center" style="padding:30px 0;">
										No hay asociaciones para este paquete.
									  </td>
									</tr>
									<?php endif; ?>
								  </tbody>
								</table>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="alojamientos">
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Alojamientos y Transportes</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-10 seleccion"> 
								<?php echo $this->admin->combo('fecha_transporte_id', 'Seleccionar Transporte', 's2', $row, $transportes, 'fecha_id', 'nombrecompleto'); ?>
							</div>
							<div class="col-md-10 seleccion"> 
								<?php echo $this->admin->combo('fecha_alojamiento_id', 'Seleccionar Alojamiento', 's2', $row, $alojamientos, 'fecha_id', 'nombrecompleto'); ?>
							</div>
							<div class="col-md-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-alojamiento">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <?php echo $this->admin->th('transporte', 'Transporte', false);?>
									  <?php echo $this->admin->th('alojamiento', 'Alojamiento', false);?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblAlojamientos">
									<?php foreach ($mis_alojamientos as $r): ?>
										<?=alojamiento_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_alojamientos) == 0): ?>
									<tr>
									  <td colspan="3" align="center" style="padding:30px 0;">
										No hay asociaciones para este paquete.
									  </td>
									</tr>
									<?php endif; ?>
								  </tbody>
								</table>
							</div>
						  </div>
						</div>
					</div>
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE' || perfil() == 'ADM'): ?>				
				<div class="tab-pane" id="adicionales">
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Adicionales</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-5 col-lg-3 seleccion"> 
								<?php echo $this->admin->combo('adicional_id', 'Seleccionar Adicional', 's2', $row, $adicionales, 'id', 'nombre'); ?>
							</div>
							<div class="col-md-3 seleccion seleccion-transporte" style="display:none;"> 
								<?php echo $this->admin->combo('transporte_fecha_id', 'Seleccionar Transporte', 's2', $row, $data_transportes, 'transporte_fecha_id', 'nombrecompleto'); ?>
							</div>
							<div class="col-md-2 seleccion"> 
								<?php echo $this->admin->input('cantidad', 'Cantidad', '', $row, false); ?>
							</div>
							<div class="col-md-2 seleccion custom-check"> 
								<label class="col-sm-6 col-md-6 col-lg-3">
									<div class="chk_name" style="padding:10px 0 5px; display:block;">Obligatorio</div>
									<input type="checkbox" name="obligatorio" value="1"/>
							    </label>
							</div>
							<div class="col-md-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-adicional">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <?php echo $this->admin->th('adicional', 'Adicional', false);?>
									  <?php echo $this->admin->th('transporte', 'Transporte', false);?>
									  <?php echo $this->admin->th('cantidad', 'Cantidad', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('obligatorio', 'Obligatorio', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('v_total', 'Precio', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblAdicionales">
									<?php foreach ($mis_adicionales as $r): ?>
										<?=adicional_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_adicionales) == 0): ?>
									<tr>
									  <td colspan="5" align="center" style="padding:30px 0;">
										No hay asociaciones para este paquete.
									  </td>
									</tr>
									<?php endif; ?>
								  </tbody>
								</table>
							</div>
						  </div>
						</div>
					</div>
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>				
				<div class="tab-pane" id="regimenes">
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Regímenes de comidas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-7 col-lg-4 seleccion"> 
								<?php echo $this->admin->combo('fecha_alojamiento_id2', 'Seleccionar Alojamiento', 's2', $row, $alojamientos, 'fecha_id', 'nombrecompleto'); ?>
							</div>
							<div class="col-md-7 col-lg-4 seleccion"> 
								<?php echo $this->admin->combo('regimen_id', 'Seleccionar Regimen de comidas', 's2', $row, $regimenes, 'id', 'nombre'); ?>
							</div>
							<div class="col-md-3 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-regimen">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <?php echo $this->admin->th('aloja', 'Alojamiento', false);?>
									  <?php echo $this->admin->th('regimen', 'Regimen de comidas', false);?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblRegimenes">
									<?php foreach ($mis_regimenes as $r): ?>
										<?=regimen_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_regimenes) == 0): ?>
									<tr>
									  <td colspan="3" align="center" style="padding:30px 0;">
										No hay asociaciones para este paquete.
									  </td>
									</tr>
									<?php endif; ?>
								  </tbody>
								</table>
							</div>
						  </div>
						</div>
					</div>
					
				</div>
				<? endif; ?>
								
				<? if (perfil() == 'SUP' || perfil() == 'OPE' || perfil() == 'ADM'): ?>				
				<div class="tab-pane <?=isset($_GET['tab']) && $_GET['tab'] =='precios'?'active':'';?>" id="precios">
					<div class="widget box">
						<div class="list_precios">
							<div class="widget-header" style="border-top:solid 1px #CCC;">
								<h3 style="margin-bottom:20px;" class="pull-left">Composición de Costo<br/><small style="font-size:12px">Indique cómo se compone el costo del paquete.</small></h3>
							</div>
							<div class="widget-content">
									<div class="row">
										<div class="col-sm-12">
											<table width="100%" class="table table-bordered table-precios-c">
												<tr>
													<th class="text-center">Exento</th>
													<th class="text-center">No Gravado</th>
													<th class="text-center">Gravado 21%</th>
													<th class="text-center">IVA 21%</th>
													<th class="text-center">Gravado 10.5%</th>
													<th class="text-center">IVA 10.5%</th>
													<th class="text-center">Otros Imp.</th>
													<th class="text-center">Costo Operador</th>
												</tr>
												<tr>
													<td><input type="text" name="c_exento" value="<?=@$row->c_exento;?>" class="form-control onlydecimal text-right c_exento" /></td>
													<td><input type="text" name="c_nogravado" value="<?=@$row->c_nogravado;?>" class="form-control onlydecimal text-right c_nogravado" /></td>
													<td><input type="text" name="c_gravado21" value="<?=@$row->c_gravado21;?>" class="form-control onlydecimal text-right c_gravado21" /></td>
													<td><input type="text" name="c_iva21" value="<?=@$row->c_iva21;?>" class="form-control onlydecimal text-right c_iva21" /></td>
													<td><input type="text" name="c_gravado10" value="<?=@$row->c_gravado10;?>" class="form-control onlydecimal text-right c_gravado10" /></td>
													<td><input type="text" name="c_iva10" value="<?=@$row->c_iva10;?>" class="form-control onlydecimal text-right c_iva10" /></td>
													<td><input type="text" name="c_otros_imp" value="<?=@$row->c_otros_imp;?>" class="form-control onlydecimal text-right c_otros_imp" /></td>
													<td><input type="text" name="c_costo_operador" value="<?=@$row->c_costo_operador;?>" readonly class="form-control onlydecimal text-right c_costo_operador" /></td>
												</tr>
											</table>
										</div>
									</div>
							</div>					
							<div class="widget-header" style="border-top:solid 1px #CCC;display: inline-block;">
								<h3 style="margin-bottom:20px;" class="pull-left">Composición de Venta<br/><small style="font-size:12px">Indique cómo se compone el valor de venta del paquete.</small></h3>
								
								<div class="pull-right text-right" style="width:30%; margin-top: 15px;">
									<label class="control-label">FEE </label>
									<div class="input-group " style="width: 40%;display: inline-table;">
										<input name="fee" type="text" class="form-control onlydecimal text-right fee precio-paquete" placeholder="" value="<?=@$row->fee;?>" >
										<span class="input-group-addon">%</span>
									</div>
									<input type="button" value="Calcular" class="btn btn-success btn-calcular-fee " style="vertical-align: inherit;">
								</div>
							</div>
							<div class="widget-content">
									<div class="row">
										<div class="col-sm-12">
											<table width="100%" class="table table-bordered table-precios-v">
												<tr>
													<th class="text-center">Exento</th>
													<th class="text-center">No Gravado</th>
													<th class="text-center">Comisión/Utilidad</th>
													<th class="text-center">Gravado 21%</th>
													<th class="text-center">IVA 21%</th>
													<th class="text-center">Gravado 10.5%</th>
													<th class="text-center">IVA 10.5%</th>
													<th class="text-center">Gastos Admin.</th>
													<th class="text-center">RG.AFIP</th>
													<th class="text-center">Otros Imp.</th>
													<th class="text-center">Total</th>
												</tr>
												<tr>
													<td><input type="text" name="v_exento" value="<?=@$row->v_exento;?>" class="form-control onlydecimal text-right v_exento" /></td>
													<td><input type="text" name="v_nogravado" value="<?=@$row->v_nogravado;?>" class="form-control onlydecimal text-right v_nogravado" /></td>
													<td><input type="text" name="v_comision" value="<?=@$row->v_comision;?>" class="form-control onlydecimal text-right v_comision" /></td>
													<td><input type="text" name="v_gravado21" value="<?=@$row->v_gravado21;?>" class="form-control onlydecimal text-right v_gravado21" /></td>
													<td><input type="text" name="v_iva21" value="<?=@$row->v_iva21;?>" class="form-control onlydecimal text-right v_iva21" /></td>
													<td><input type="text" name="v_gravado10" value="<?=@$row->v_gravado10;?>" class="form-control onlydecimal text-right v_gravado10" /></td>
													<td><input type="text" name="v_iva10" value="<?=@$row->v_iva10;?>" class="form-control onlydecimal text-right v_iva10" /></td>
													<td><input type="text" name="v_gastos_admin" value="<?=@$row->v_gastos_admin;?>" class="form-control onlydecimal text-right v_gastos_admin" /></td>
													<td><input type="text" name="v_rgafip" value="<?=@$row->v_rgafip;?>" class="form-control onlydecimal text-right v_rgafip" /></td>
													<td><input type="text" name="v_otros_imp" value="<?=@$row->v_otros_imp;?>" class="form-control onlydecimal text-right v_otros_imp" /></td>
													<td><input type="text" name="v_total" value="<?=@$row->v_total;?>" readonly class="form-control onlydecimal text-right v_total" /></td>
												</tr>
											</table>

										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-right">
											<input type="button" value="Actualizar precios" class="btn btn-success btn-update-precios">
										</div>
									</div>
							</div>
							
							<div class="widget-header" style="border-top:solid 1px #CCC;">
								<h3 style="margin-bottom:20px;" class="pull-left">Monto comisionable<br/><small style="font-size:12px">Indique cuál será el monto comisionable para los vendedores.</small></h3>
							</div>
							
							<div class="widget-content">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-md-4 control-label">Ingresa el monto comisionable <span class="required">*</span></label>
											<div class="col-md-2 input-group">	
												<input type="text" id="porcentaje_comisionable" name="porcentaje_comisionable" class="form-control onlydecimal required " value="<?=@$row->porcentaje_comisionable;?>" placeholder="Ej 20.00" style="">
												<span class="input-group-addon">%</span>
												<span><small><b></b></small></span>
												<label for="porcentaje_comisionable" generated="true" class="has-error help-block" style="display:none;"></label>
											</div>
											<div class="col-md-2">
												<input type="button" value="Calcular" class="btn btn-primary btn-comisionable">											
											</div>
											<div class="col-md-2">	
												<input type="text" id="monto_comisionable" name="monto_comisionable" class="form-control onlydecimal required " value="<?=@$row->monto_comisionable;?>" placeholder="Ej 1520.00" style="">
												<span><small><b></b></small></span>
												<label for="monto_comisionable" generated="true" class="has-error help-block" style="display:none;"></label>
											</div>
											
										</div>									
									</div>
								</div>
							</div>
							
							<div class="widget-header" style="border-top:solid 1px #CCC;">
								<h3 style="margin-bottom:20px;" class="pull-left">Monto mínimo de Reserva<br/><small style="font-size:12px">Indique cuál será el monto mínimo de reserva del paquete.</small></h3>
							</div>
							
							<div class="widget-content">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-md-4 control-label">Ingresa el monto mínimo <span class="required">*</span></label>
											<div class="col-md-2 input-group">	
												<input type="text" id="porcentaje_minimo_reserva" name="porcentaje_minimo_reserva" class="form-control  required " value="<?=@$row->porcentaje_minimo_reserva;?>" placeholder="Ej 20.00" style="">
												<span class="input-group-addon">%</span>
												<span><small><b></b></small></span>
												<label for="porcentaje_minimo_reserva" generated="true" class="has-error help-block" style="display:none;"></label>
											</div>
											<div class="col-md-2">
												<input type="button" value="Calcular" class="btn btn-primary btn-minimo">											
											</div>
											<div class="col-md-2">	
												<input type="text" id="monto_minimo_reserva" name="monto_minimo_reserva" class="form-control  required " value="<?=@$row->monto_minimo_reserva;?>" placeholder="Ej 1520.00" style="">
												<span><small><b></b></small></span>
												<label for="monto_minimo_reserva" generated="true" class="has-error help-block" style="display:none;"></label>
											</div>
											
										</div>	
									</div>
								</div>
							</div>

							<div class="widget-header" style="border-top:solid 1px #CCC;">
								<h3 style="margin-bottom:20px;" class="pull-left">Precio anterior<br/><small style="font-size:12px">Indique, si corresponde, cuál será el precio anterior del paquete. Este valor se usa a modo informativo en el sitio y deberá ser un importe mayor al precio real (actual) del paquete, para mostrar una rebaja en el precio del mismo.</small></h3>
							</div>
								
							<div class="widget-content">
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<?php echo $this->admin->input('precio_anterior_neto', 'Valor neto', '', $row, true); ?>
										</div>
										<div class="col-sm-6">
										 	 <?php echo $this->admin->input('precio_anterior_impuestos', 'Impuestos', '', $row, true); ?>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
						
				
					<div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Variaciones de precios</h3>
						</div>
						<div class="widget-content">
							<div class="seleccion"> 
								<? if (!$puede_borrar_combinaciones): ?>
									<div class="alert alert-warning">Ya existen reservas y/o ordenes de reserva generadas para algunas de las combinaciones de este paquete, por lo que sólo es posible generar nuevas combinaciones.</div>
								<? else: ?>
								<p style="margin: 10px 0;">
									Para poder definir los precios de <b>todas</b> las diferentes combinaciones de transportes, alojamientos, tipos de habitaciones y regímenes de comidas, borrando previamente las existentes,  
									hacé click en <input type="button" value="Generar combinaciones" class="btn btn-sm btn-success btn-add-combinaciones" style="margin:0"/>
								</p>
								<? endif; ?>
								<p style="margin: 10px 0;">
									Para poder generar <b>sólo las nuevas</b> combinaciones hacé click en <input type="button" value="Generar nuevas combinaciones" class="btn btn-sm btn-success btn-add-combinaciones nuevas" style="margin:0"/>
								</p>
								<div id="loading" class="alert alert-info fade in">
									Se están generando las combinaciones para el paquete...<br>
									Aguarda unos instantes.
								</div>
							</div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Combinaciones disponibles del paquete</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table id="tblComb" cellpadding="0" cellspacing="0" border="0" class="table table-hover datatable">
								  <thead>
									<tr>
									  <th><input type="checkbox" class="default" name="select_all" value="1" id="select-all"></th>
									  <?php echo $this->admin->th('lugar', 'Salida y transporte', false);?>
									  <?php echo $this->admin->th('alojamiento', 'Alojamiento', false);?>
									  <?php echo $this->admin->th('habitacion', 'Habitación y Regimen', false);?>
									  <?php echo $this->admin->th('v_total', 'Precio total', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblCombinaciones">
									<?php foreach ($combinaciones as $r): ?>
										<?=combinacion_row($r);?>
									<?php endforeach; ?>
								  </tbody>
								</table>
							</div>
							<div class="col-md-12">
								<button id="btnBorrarSeleccionados" class="btn btn-sm btn-primary" type="button" disabled>Borrar Seleccionados</button>
							</div>
						  </div>
						</div>
					</div>
				</div>
				<? endif; ?>

			</div>
		</div>
		<!--END TABS-->
				
		<div class="form-actions" style="border: 1px solid #ccc;margin: -20px 0 0;">
		  <input type="hidden" name="id" id="id" value="<?=@$row->id;?>" />
		  <input type="submit" value="Grabar" class="btn btn-success" name="btnvolver">
		  <? if (perfil() == 'SUP' || perfil() == 'OPE'): ?>
		  <input type="button" value="Grabar y Permanecer" class="btn btn-info" id="btnSalvar">
		  <input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
		  <? else: ?>
		  <input type="button" value="Volver" class="btn btn-default" onclick="history.back();">
		  <? endif; ?>
		</div>
					
	</div>
	
  </div>

  </form>

<script src="<?=base_url();?>media/admin/ckeditor/ckeditor.js?v=2"></script>
<script src="<?=base_url();?>media/admin/assets/js/jquery.timeMask.js"></script>
<script src="<?=base_url();?>media/admin/assets/js/multiselect.min.js"></script>

<iframe src="<?=site_url('admin/paquetes/iframe');?>" id="uploader" name="uploader" style="position:absolute; left:-10000px;"></iframe>

<div id="cargarPopup" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_listado']['width'];?>x<?=$this->uploads['imagen_listado']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/paquetes/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>
	

	
	<script>
	$(document).ready(function(){
		$("#hora").timeMask();

		$('#cantidad_vouchers').attr('min','0');
	});
	</script>
	
<script>
var current_field = '';
var ids = [];
$(document).ready(function(){
	 $('.btnRemoveImage').click(function(){
	    $('#' + $(this).data('ref')).val('');
	    $('#preview_' + $(this).data('ref')).attr('src', $('#preview_' + $(this).data('ref')).data('placeholder'));
	  });


	<? if (!isset($_GET['tab'])): ?>
	$('.nav-tabs li').first().addClass('active');
	$('.tab-pane').first().addClass('active');
	<? endif; ?>

	<? if (perfil() == 'ADM'): ?>
	$('#formEdit').on('submit', function(){
		refresh_excursiones_arr();
		refresh_caracteristicas_arr();

		$('#formEdit').submit();
	});
	<? else: ?>

	$('#btnSalvar').click(function(){
		$.fancybox.showLoading();

		refresh_excursiones_arr();
		refresh_caracteristicas_arr();

		$.post('<?=site_url('admin/paquetes/validar');?>', $('#formEdit').serialize(), function(result){
			if (result.success) {
				$.post('<?=site_url('admin/paquetes/save?silent=1');?>', $('#formEdit').serialize(), function(result){
					$.fancybox.hideLoading();
				});
			}
			else {
				$.fancybox.hideLoading();
				bootbox.alert('<h3 style="margin:0 0 15px">No fue posible grabar, se detectaron errores:</h3><div class="alert alert-danger">' + result.error + '</div>');
			}
		}, "json");
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


	$('#formEdit').on('submit', function(){
		if ($(this).hasClass('validado')) {
			return true;
		}

		refresh_excursiones_arr();
		refresh_caracteristicas_arr();

		$.post('<?=site_url('admin/paquetes/validar');?>', $(this).serialize(), function(result){
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
	
	<? endif; ?>


	$('#fecha_limite_vouchers.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: new Date()
	});
	$('#fecha_limite_vouchers.datepicker').attr('readonly',true);
	
	$('#fecha_limite_completar_datos.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: new Date()
	});
	$('#fecha_limite_completar_datos.datepicker').attr('readonly',true);
	
	$('#fecha_limite_pago_completo.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: new Date()
	});
	$('#fecha_limite_pago_completo.datepicker').attr('readonly',true);
	
  $('#nombre').on('keyup', function(){
    $('#slug').val( string_to_slug($(this).val())+'-'+$('#codigo').val() );
  });
  
  if ($('#descripcion').length) {
	  //editor de html para campo "descripcion
	  CKEDITOR.config.height = '100px';
	  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	  CKEDITOR.config.language = 'es';
	  CKEDITOR.config.removePlugins = 'elementspath';
	  CKEDITOR.config.resize_enabled = false;
	  CKEDITOR.config.toolbar = [
	      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Link', '-', 'RemoveFormat' ] },
	  ];
	  CKEDITOR.replace( 'descripcion' );
  }

  if ($('#aclaraciones').length) {
	  //editor de html para campo "aclaraciones
	  CKEDITOR.config.height = '100px';
	  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	  CKEDITOR.config.language = 'es';
	  CKEDITOR.config.removePlugins = 'elementspath';
	  CKEDITOR.config.resize_enabled = false;
	  CKEDITOR.config.toolbar = [
	      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Link', '-', 'RemoveFormat' ] },
	  ];
	  CKEDITOR.replace( 'aclaraciones' );
  }

  if ($('#documentacion_requerida').length) {
	  //editor de html para campo "documentacion_requerida
	  CKEDITOR.config.height = '100px';
	  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	  CKEDITOR.config.language = 'es';
	  CKEDITOR.config.removePlugins = 'elementspath';
	  CKEDITOR.config.resize_enabled = false;
	  CKEDITOR.config.toolbar = [
	      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Link', '-', 'RemoveFormat' ] },
	  ];
	  CKEDITOR.replace( 'documentacion_requerida' );
  }

  //el dropdown de destino lo pongo readonly
  $('#destino_id').select2().enable(false);

  
  //agrega nuevo alojamiento en tabla
  $('body').on('click','.btn-add-alojamiento', function(e){
	  e.preventDefault();
	  
	  if($('#fecha_alojamiento_id').val() == '' || $('#fecha_transporte_id').val() == ''){
		  bootbox.alert('Debes seleccionar el transporte y el alojamiento.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_alojamiento";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblAlojamientos').prepend(data.row);
		  }
	  },'json');
  });
  
  //agrega nuevo habitacion en tabla
  $('body').on('click','.btn-add-habitacion', function(e){
	  e.preventDefault();
	  
	  if($('#habitacion_id').val() == ''){
		  bootbox.alert('Debes seleccionar el tipo de habitación.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_habitacion";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblHabitaciones').prepend(data.row);
		  }
	  },'json');
  });
  
  //agrega nuevo regimen en tabla
  $('body').on('click','.btn-add-regimen', function(e){
	e.preventDefault();
  
	  if($('#regimen_id').val() == ''){
		  bootbox.alert('Debes seleccionar el regimen de comidas.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_regimen";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblRegimenes').prepend(data.row);
		  }
	  },'json');
  });
  
  //agrega nuevo adicional en tabla
  $('body').on('click','.btn-add-adicional', function(e){
	e.preventDefault();
  
	  if($('#adicional_id').val() == ''){
		  bootbox.alert('Debes seleccionar el adicional.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_adicional";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblAdicionales').prepend(data.row);
		  }
	  },'json');
  });
  
  //agrega nuevo parada en tabla
  $('body').on('click','.btn-add-parada', function(e){
	e.preventDefault();
	  if($('#parada_id').val().trim() == '' || $('#hora').val().trim() == ''){
		  bootbox.alert('Debes seleccionar la parada y el horario.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_parada";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblParadas').prepend(data.row);
			  
			  if(data.options){
				  $('#parada_id').select2('destroy');
				  $('#parada_id').html('');
				  $('#parada_id').select2();
				  
				  $.each(data.options,function(i,el){
					  var newOption = new Option(el.nombrecompleto, el.id, false, false);
					  $('#parada_id').append(newOption).trigger('change');
				  });
				  
		      }
		  }
	  },'json');
  });
  
  //generar combinaciones para paquete y mostrarlos en tabla
  $('body').on('click','.btn-add-combinaciones', function(e){
	  e.preventDefault();
	  
	  var me = $(this);
	  var nuevas = $(this).hasClass('nuevas');
	  var url = "<?=$route;?>/generar_combinaciones/"+(nuevas?1:0);
	  me.attr('disabled','disabled');
	  $('#loading').show();
	  $.post(url,$('#formEdit').serialize(),function(data){
		  me.removeAttr('disabled');
		  $('#loading').hide();
		  if(data.row){
			    $('#tblCombinaciones').html('');
				$('#tblCombinaciones').html(data.row);
		  }
		  if(data.status == 'error'){
			  bootbox.alert(data.msg);
		  }
	  },'json');
  });
  
  //borrar alojamiento 
  $('body').on('click','.btn-delete-alojamiento', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	  bootbox.confirm("Esta seguro que desea borrar la asociación?", function(result){
		if (result) {
			  $.post(url,function(data){
				  if(data){
					me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta asociación porque posee ordenes de reserva / reservas');
				  }
			  });
		}
	  });
  });
  
  //borrar regimen 
  $('body').on('click','.btn-delete-regimen', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	  bootbox.confirm("Esta seguro que desea borrar la asociación?", function(result){
		if (result) {
			$.post(url,function(data){
				  if(data){
					  me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta asociación porque posee ordenes de reserva / reservas');
				  }
			  });
		}
	  });
  });
  
  //borrar habitacion 
  $('body').on('click','.btn-delete-habitacion', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	   bootbox.confirm("Esta seguro que desea borrar la asociación?", function(result){
		if (result) {
			$.post(url,function(data){
				  if(data){
					  me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta asociación porque posee ordenes de reserva / reservas');
				  }
			  });
		}
	  });
  });
  
  //borrar adicional 
  $('body').on('click','.btn-delete-adicional', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	   bootbox.confirm("Esta seguro que desea borrar la asociación?", function(result){
		if (result) {
			$.post(url,function(data){
				  if(data){
					  me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta asociación porque posee ordenes de reserva / reservas');
				  }
			  });
		}
	  });
  });
  
  //borrar parada 
  $('body').on('click','.btn-delete-parada', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	   bootbox.confirm("Esta seguro que desea borrar la asociación?", function(result){
		if (result) {
			$.post(url,function(data){
			  if(data.status == 'success'){
				  me.closest('tr').slideUp();
				  
				   if(data.options){
					  $('#parada_id').select2('destroy');
					  $('#parada_id').html('');
					  $('#parada_id').select2();
					  
					  $.each(data.options,function(i,el){
						  var newOption = new Option(el.nombrecompleto, el.id, false, false);
						  $('#parada_id').append(newOption).trigger('change');
					  });
					  
				  }
			  }
			  else {
			  	bootbox.alert('No es posible borrar esta asociación porque posee ordenes de reserva / reservas');
			  }
		  },'json');
	   }
     });
  });
  
  //borrar combinacion 
  $('body').on('click','.btn-delete-combinacion', function(e){
	  e.preventDefault();
	  var me = $(this);
	  
		bootbox.confirm("Esta seguro que desea borrar la combinación?", function(result){
			if (result) {
				
			  var url = me.attr('data-href');
			  $.post(url,function(data){
				  if(data){
					  me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta combinación porque posee ordenes de reserva / reservas');
				  }
			  });
	  
			}
		});
			
  });
  
  //abre popup para editar precios
  $("body").on('click','.btn-ver-precios',function(e) {
		e.preventDefault();
		var me = $(this);
		var url = me.attr('data-href');
		$.post(url,function(data){
			if(data.view){
				var dialog = bootbox.dialog({
					title: 'Composición de precios del paquete',
					message: data.view,
					buttons: {
						cancel: {
							label: "Cancelar",
							className: 'btn-danger',
							callback: function(){
							}
						},
						ok: {
							label: "Actualizar",
							className: 'btn-success',
							callback: function(){
								var furl = $('#fCombinacionPrecios').attr('action');
								$.post(furl,$('#fCombinacionPrecios').serialize(),function(d){
									if(d.v_total){
										me.closest('.tr_row').find('.v_total').text(d.v_total);
									}
								},'json');
							}
						}
					}
				});
			}
		},"json");
	});
	
  //abre popup para editar precios del adicional
  $("body").on('click','.btn-ver-precios-adicional',function(e) {
		e.preventDefault();
		var me = $(this);
		var url = me.attr('data-href');
		$.post(url,function(data){
			if(data.view){
				var dialog = bootbox.dialog({
					title: 'Composición de precios del adicional',
					message: data.view,
					buttons: {
						cancel: {
							label: "Cancelar",
							className: 'btn-danger',
							callback: function(){
							}
						},
						ok: {
							label: "Actualizar",
							className: 'btn-success',
							callback: function(){
								
								var furl = $('#fAdicionalPrecios').attr('action');
								var form = $('#fAdicionalPrecios').serialize();
								var mee = me;
								bootbox.confirm("Confirma que desea actualizar el precio y enviar los mails de ajuste de precio a los pasajeros que tengan reservas no anuladas con saldo pendiente?", function(result){
										if (result) {
											
											$.post(furl,form,function(d){
												if(d.v_total){
													mee.closest('.tr_row').find('.v_total').text(d.v_total);
												}
											},'json');
								  
										}
									});

							}
						}
					}
				});
			}
		},"json");
	});
	
	//autocompletar con 0.00 cuando no ponen nada
	$( "body" ).on('change','.table-precios-c input[type="text"], .table-precios-v input[type="text"]', function() {
		var me = $(this);
		var num = me.val();
		if(num == ''){
			me.val('0.00');
		}
	});
	
	//calculo monto comisionable en base al porcentaje establecido y el precio de venta
	$( "body" ).on('change','#porcentaje_comisionable', function() {
		calcular_comisionable();
	});
	//calculo monto comisionable en base al porcentaje establecido y el precio de venta
	$( "body" ).on('click','.btn-comisionable', function() {
		calcular_comisionable();
	});
	
	//calculo monto minimo en base al porcentaje establecido
	$( "body" ).on('change','#porcentaje_minimo_reserva', function() {
		calcular_minimo_reserva();
	});
	$( "body" ).on('click','.btn-minimo', function() {
		calcular_minimo_reserva();
	});
	
	
	//calculos automaticos de otros impuestos
	$( "body" ).on('change','.c_otros_imp', function() {
		var me = $(this);
		me = me.closest( ".list_precios" ).find('.fee');
		calcular_fee(me);
		total_venta(me);
	});
	
	//calculos automaticos de 10.5% y 21% de ivas
	$( "body" ).on('change','.c_gravado21', function() {
		var me = $(this);
		var num = me.val();
		num = num*0.21;
		num = num.toFixed(2);
		me.closest( "tr" ).find( ".c_iva21" ).val(num);
	});
	$( "body" ).on('change','.c_gravado10', function() {
		var me = $(this);
		var num = me.val();
		num = num*0.105;
		num = num.toFixed(2);
		me.closest( "tr" ).find( ".c_iva10" ).val(num);
	});
	$( "body" ).on('change','.v_gravado21', function() {
		var me = $(this);
		var num = me.val();
		num = num*0.21;
		num = num.toFixed(2);
		me.closest( "tr" ).find( ".v_iva21" ).val(num);
	});
	$( "body" ).on('change','.v_gravado10', function() {
		var me = $(this);
		var num = me.val();
		num = num*0.105;
		num = num.toFixed(2);
		me.closest( "tr" ).find( ".v_iva10" ).val(num);
	});
	$( "body" ).on('click','.btn-calcular-fee', function() {
		var me = $(this);
		me = me.closest('div').find('.fee');		
		calcular_fee(me);
		total_venta(me);
	});
	$( "body" ).on('change','.fee', function() {
		var me = $(this);
		
		calcular_fee(me);
		total_venta(me);
	});
	$( "body" ).on('click','.btn-update-precios', function() {		
		var me = $(this);
		var furl = '<?=$route;?>/updatePrecios';
		
		bootbox.confirm("Esta acción replicará el precio base en las combinaciones y generará movimientos en las cuentas corrientes de los pasajeros con reserva en estado <b>NUEVA y POR VENCER</b> y que tengan <b>SALDOS PENDIENTES DE PAGO</b>.<br>Estás seguro?", function(result){
			if (result) {				
				$.post(furl,$('#formEdit').serialize(),function(d){
					if(d.v_total){
						$.each(d.ids,function(i,el){
							$('#tblCombinaciones .v_total.v_total_'+el).text(d.v_total);
						});
						
						//var msg = 'Los precios fueron actualizados correctamente y replicados en las combinaciones del paquete.<br>Adicionalmente, se han notificado los pasajeros cuyas reservas están en estado NUEVA y POR VENCER y tengan SALDOS PENDIENTES DE PAGO.';
						var msg = 'Los precios fueron actualizados correctamente y replicados en las combinaciones del paquete.<br>Los pasajeros no fueron notificados por mail de dicho cambio de precio.';
						bootbox.alert(msg);
					}
				},'json');
			}
		});
			
	});
	
	//auto calcular el total de costo
	$( "body" ).on('change','.table-precios-c tr td input[type="text"]:not(.c_costo_operador)', function() {
		var me = $(this);
		total_costo(me);
	});
	
	//auto calcular el total de venta
	$( "body" ).on('change','.table-precios-v tr td input[type="text"]:not(.v_total)', function() {
		var me = $(this);
		total_venta(me);
	});
	
	//adicional_id
	$( "body" ).on('change','#adicional_id', function() {
		var me = $(this);
		if(me.find(':selected').attr('data-tipo') == 'transporte'){
			$('.seleccion-transporte').show();
			$('#transporte_id').val('');
		}		
		else{
			$('.seleccion-transporte').hide();
		}
		$('#transporte_id').val('');
		$('#transporte_id').trigger('change');
	});
	
	var demo2 = $('.demo2').bootstrapDualListbox({
	  nonSelectedListLabel: 'Excursiones Sin Asociar',
	  selectedListLabel: 'Excursiones Asociadas al Paquete',
	  preserveSelectionOnMove: 'moved',
	  moveOnSelect: false,
	  sortByInputOrder: true
	});
	
	$('#tblParadas').sortable();
	
		//sort de excursiones
	 $('#multiselect').multiselect({keepRenderingSort: true});
	 $('#carac_multiselect').multiselect({keepRenderingSort: true});

  //seleccion de lugar de salida
  $('.selSalida').on('switchChange.bootstrapSwitch', function (event, state) {
		var me = $(this);console.log(me.is(':checked'));
		
	  var url = "<?=$route;?>/agregar_lugar_salida";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
		  }
	  });
	});
  
	$('.comboEstacionales').val([<?=implode(',',$mis_estacionales);?>]).trigger("change");
	
	$('.comboCelulares').val([<?=implode(',',$mis_celulares);?>]).trigger("change");
	
	$('.comboCoordinadores').val([<?=implode(',',$mis_coordinadores);?>]).trigger("change");
	
	
	//init data table combinaciones
	
   	// Handle click on "Select all" control
   	$('#select-all').on('click', function(){
      	// Get all rows with search applied
      	//var rows = table.rows({ 'search': 'applied' }).nodes();
      	// Check/uncheck checkboxes for all rows in the table
      	//$.each($('input[type="checkbox"]').prop('checked', this.checked);
		var me = this;
		console.log(me.checked);
		console.log($(this).prop('checked'));
      	//ids.splice();
		ids = ids.unique();
		if ($(this).prop('checked')) {
      		$('.id_cell').each(function(index, obj){
      			console.log(obj);
				$(obj).find('input[type=checkbox]').prop('checked', me.checked);
				ids.push($(obj).data('ref'));
      		});
      	}
		else{
			$('.id_cell').each(function(index, obj){
				$(obj).find('input[type=checkbox]').prop('checked', me.checked);
      		});
			ids = ids.splice();
		}
		
      	if (ids.length) {
      		$('#btnBorrarSeleccionados').removeAttr('disabled');
      	}
      	else {
      		$('#btnBorrarSeleccionados').attr('disabled', 'disabled');	
      	}
		
		ids = ids.unique();
   	});

   	// Handle click on checkbox to set state of "Select all" control
   	$('#tblComb tbody').on('change', 'input[type="checkbox"]', function(){
      	var id = $(this).parent().data('ref');
console.log(id);
      	// If checkbox is not checked
		console.log(this.checked);
      	if(!this.checked){
        	var el = $('#select-all').get(0);
        	// If "Select all" control is checked and has 'indeterminate' property
        	if(el && el.checked && ('indeterminate' in el)){
            	// Set visual state of "Select all" control
            	// as 'indeterminate'
            	el.indeterminate = true;
         	}

      		index = ids.indexOf(id);
			console.log(index);
      		if (index > -1) {
      			ids.splice(index, 1);
      		}
      	}
      	else {
      		ids.push(id);
      	}

      	if (ids.length) {
      		$('#btnBorrarSeleccionados').removeAttr('disabled');
      	}
      	else {
      		$('#btnBorrarSeleccionados').attr('disabled', 'disabled');	
      	}
		
		ids = ids.unique();
   	});

   	$('#btnBorrarSeleccionados').click(function(e){
   		if (!ids.length) {
   			bootbox.alert('No hay Combinaciones seleccionadas.');
   		}
   		else {
   			bootbox.confirm('¿Esta seguro de borrar ' + ids.length + ' Combinaciones?', function(res){
   				if (res) {   					
		   			
		   			$.post('<?=site_url('admin/paquetes/del_combinaciones');?>', {ids:ids}, function(response){
		   				if (response.success){
		   					location.href = location.href+'?tab=precios';
		   				}
		   				else {
		   					bootbox.alert(response.error);
		   				}
		   			}, "json");
   				}
   			});
   		}
   	});
	
	$('body').on('change', '.modal-dialog input', function(){

    $('.modal-dialog form').ajaxForm({
      	url: '<?=site_url('admin/paquetes/upload');?>',
        beforeSend: function() {
						App.blockUI();
            $('.modal-dialog input').hide();
            $('.modal-dialog .progress').show();
        },
        success: function(res) {
					if (res.status == "200") {
						console.log(res.filename);
						console.log(res.url);
						console.log('#preview_' + current_field);
						$('#' + current_field).val(res.filename);
						$('#preview_' + current_field).attr('src', res.url);
						bootbox.hideAll();
					}else{
						$('.modal-dialog .cartel').removeClass('alert-info').addClass('alert-danger');
						$('.modal-dialog .cartel span').text(res.error).show();
						$('.modal-dialog input').show();
						$('.modal-dialog .progress').hide();          
					}
					App.unblockUI();
        },
				error: function(){
					console.log("error");
					App.unblockUI();
				}
    });

    $('.modal-dialog form').submit();
  });

	$('.s3').select2({
		maximumSelectionLength: 3
	});
	


	  $('#exterior').on('switchChange.bootstrapSwitch', function (event, state) {
		var me = $(this);
		console.log(me.is(':checked'));
			
		if(me.is(':checked')){
			//si activo
			$('.impuesto30').removeClass('hidden');
		}
		else{
			//si desactivo
			$('.impuesto30').addClass('hidden');
		}
	});

	$( "body" ).on('change','#base_imponible_pais', function() {
		calcular_impuesto_pais();
	});
	$( "body" ).on('click','#tasa_impuesto_pais', function() {
		calcular_impuesto_pais();
	});

});

function refresh_excursiones_arr(){
	var ids = [];
	
	$.each($('#multiselect_to option'),function(i,el){
		ids.push($(el).attr('value'));
		
	});
	
	$("#excursiones_arr").val(ids.join(','));
}

function refresh_caracteristicas_arr(){
	var ids = [];
	
	$.each($('#carac_multiselect_to option'),function(i,el){
		ids.push($(el).attr('value'));
		
	});
	
	$("#caracteristicas_arr").val(ids.join(','));
}

function calcular_fee(me){
	//calculo comision de venta segun el FEE
	var fee = me.val()/100;
	var costo = me.closest( ".list_precios" ).find( ".c_costo_operador" ).val();
	var utilidad = costo*fee;
	var com_neta = utilidad/1.21;
	var com_iva = com_neta*0.21;
	com_neta = com_neta.toFixed(2);
	com_iva = com_iva.toFixed(2);
			
	//traspaso los costos al precio de venta
	me.closest( ".list_precios" ).find( ".v_exento" ).val(me.closest( ".list_precios" ).find( ".c_exento" ).val());
	me.closest( ".list_precios" ).find( ".v_nogravado" ).val(me.closest( ".list_precios" ).find( ".c_nogravado" ).val());
	me.closest( ".list_precios" ).find( ".v_gravado21" ).val(me.closest( ".list_precios" ).find( ".c_gravado21" ).val());
	me.closest( ".list_precios" ).find( ".v_comision" ).val(com_neta);
	me.closest( ".list_precios" ).find( ".v_gravado21" ).val(me.closest( ".list_precios" ).find( ".c_gravado21" ).val());
	me.closest( ".list_precios" ).find( ".v_otros_imp" ).val(me.closest( ".list_precios" ).find( ".c_otros_imp" ).val());
	var v_iva = me.closest( ".list_precios" ).find( ".c_iva21" ).val();
	v_iva = parseFloat(v_iva);
	v_iva = v_iva.toFixed(2);
	var valor = parseFloat(com_iva)+parseFloat(v_iva);
	valor = valor.toFixed(2);
	me.closest( ".list_precios" ).find( ".v_iva21" ).val(valor);
	me.closest( ".list_precios" ).find( ".v_gravado10" ).val(me.closest( ".list_precios" ).find( ".c_gravado10" ).val());
	me.closest( ".list_precios" ).find( ".v_iva10" ).val(me.closest( ".list_precios" ).find( ".c_iva10" ).val());
	
	//calculo monto comisionable
	if(me.hasClass('precio-paquete')){
		calcular_comisionable();
	}
}
function calcular_minimo_reserva(){
	var me = $('#porcentaje_minimo_reserva');
	var min = 0.00;
	var v = me.val();
	var v_total = me.closest( ".list_precios" ).find('.v_total').val();
	v = parseFloat(v);
	v_total = parseFloat(v_total);
	min = (v*v_total)/100;
	min = min.toFixed(2);
	$('#monto_minimo_reserva').val(min);	
}

function calcular_impuesto_pais(){
	var t = $('#tasa_impuesto_pais').val();
	var b = $('#base_imponible_pais').val();
	t = parseFloat(t);
	b = parseFloat(b);
	min = (t*b)/100;
	min = min.toFixed(2);
	$('#impuesto_pais').val(min);	
}

function calcular_comisionable(){
	var me = $('#porcentaje_comisionable');
	var comisionable = 0.00;
	var v = me.val();
	var v_total = me.closest( ".list_precios" ).find('.v_total').val();
	v = parseFloat(v);
	v_total = parseFloat(v_total);
	comisionable = (v*v_total)/100;
	comisionable = comisionable.toFixed(2);
	$('#monto_comisionable').val(comisionable);		
}

function total_costo(me){
	var suma = 0.00;
	$.each(me.closest( ".list_precios" ).find('.table-precios-c tr td input[type="text"]:not(.c_costo_operador)'),function(i,el){
		suma += parseFloat(el.value);
	});
	
	me.closest( ".list_precios" ).find('.table-precios-c tr td .c_costo_operador').val(suma.toFixed(2));
}
function total_venta(me){
	var suma = 0.00;
	$.each(me.closest( ".list_precios" ).find('.table-precios-v tr td input[type="text"]:not(.v_total)'),function(i,el){
		suma += parseFloat(el.value);
	});
	
	me.closest( ".list_precios" ).find('.table-precios-v tr td .v_total').val(suma.toFixed(2));
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});
</script>
    
<?php echo $footer; ?>
