<?php echo $header;?>

<style>
.chk_name {     
	display: inline-block;
    width: 80%;
    vertical-align: middle;
	padding-left: 10px; 
}
</style>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
    </div>
  </div>
  <?php if (isset($_GET['error'])&&$_GET['error']): ?>
	  <br/>
	  <div class="alert alert-warning fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		<strong>&iexcl;Operación incompleta!</strong> Debes completar y verificar todos los datos obligatorios (*).
	  </div>
	<?php endif; ?>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    
	<div class="col-md-12"> 
	
		<!-- Tabs-->
		<div class="tabbable tabbable-custom tabs-left">
			<ul class="nav nav-tabs tabs-left">
				<li class="active"><a href="#basicos" data-toggle="tab">Datos generales</a></li>
				<li><a href="#imagenes" data-toggle="tab">Imagenes</a></li>
				<li><a href="#galeria" data-toggle="tab">Galería</a></li>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="basicos">
				
					  <div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Propiedades</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
								 <div class="alert alert-info fade in"> 
									<i class="icon-remove close" data-dismiss="alert"></i> 
									<strong>Código de destino</strong> La longitud debe ser de al menos 3 caracteres.
								  </div>

							  <?php echo $this->admin->input('nombre', 'Nombre de destino', '', $row, true); ?>
							  <?php echo $this->admin->input('slug', 'Slug', '', $row, true); ?>
							  <?php echo $this->admin->input('descripcion', 'Descripción', '', $row, false); ?>
							  <?php echo $this->admin->input('codigo', 'Código Destino', '', $row, true); ?>
							  <?php if(false) echo $this->admin->input('codigo_pais_afip', 'Cód. País AFIP', '', $row, false); ?>
							  <div class="row form-group chkb"> 
								<label class="col-sm-3 col-md-3 col-lg-3 text-center">
									<div class="chk_name">Publicado:</div>
									<input type="checkbox" name="publicado" value="1" <?=@$row->publicado ? 'checked' : '';?> />
							    </label>					
								<label class="col-sm-3 col-md-3 col-lg-3 text-center">
									<div class="chk_name">Destacado:</div>
									<input type="checkbox" name="destacado" value="1" <?=@$row->destacado ? 'checked' : '';?> />
							    </label>					
								<label class="col-sm-3 col-md-3 col-lg-3 text-center">
									<div class="chk_name">Descargar Manifiesto:</div>
									<input type="checkbox" name="manifiesto" value="1" <?=@$row->manifiesto ? 'checked' : '';?> />
							    </label>					
								<label class="col-sm-3 col-md-3 col-lg-3 text-center">
									<div class="chk_name">Próximamente:</div>
									<input type="checkbox" name="proximamente" value="1" <?=@$row->proximamente ? 'checked' : '';?> />
							    </label>					
							  </div>
							</div>
						  </div>
						</div>
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Categoría Regional</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->combo('categoria_id', 'Seleccionar Categoría', 's2', $row, $categorias, 'id', 'nombre'); ?>
							</div>
						  </div>
						</div>
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Sub Categorías Estacionales</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->combo('estacionales[]', 'Seleccionar Subcategorías', 's2 comboEstacionales', $row, $estacionales, 'id', 'nombre',false,true); ?>
							</div>
						  </div>
						</div>
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Código de Conversión</h3>
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
				
				<div class="tab-pane" id="imagenes">
					  <? $resizes = $this->config->item('resizes_destinos'); ?>
                      <? if(count($resizes)): ?>
					  <div class="alert alert-info">
						<b>Resizes de imagen</b>: El sistema genererá automáticamente <?=count($resizes);?> imagenes adicionales las cuales se usarán para la Home en el módulo de Destacados.
                      </div>
                  	  <? endif; ?>

					  <div class="widget box">
						<div class="widget-header">
						  <h3 class="pull-left" style="margin-bottom:20px;">Imagen Desktop</h3>
						  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
						</div>
						<div class="widget-content" style="background:#CCC; text-align:center;">
						  <a href="#" class="btnPopupImage" data-ref="imagen">
							<img id="preview_imagen" src="<?=isset($row->id) && $row->imagen && file_exists('./uploads/destinos/'.$row->id.'/'.$row->imagen) ? static_url('uploads/destinos/'.$row->id.'/'.$row->imagen) : 'https://via.placeholder.com/'.$this->uploads['imagen']['width'].'x'.$this->uploads['imagen']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen']['width'];?>x<?=$this->uploads['imagen']['height'];?>" class="img-responsive" style="margin:0 auto;" />
						  </a>
						  <input type="hidden" id="imagen" name="imagen" value="<?=@$row->imagen;?>" />
						</div>
						
						
					  </div>
				</div>
				
				<div id="galeria" class="tab-pane">
								
					<? if (isset($row->id)): ?>
						<h3>
							Usa la siguiente herramienta para subir la galeria de imagenes del destino.<br>
							No hace falta que presiones GRABAR para enviarlas al servidor.
						</h3>
						 <div class="alert alert-info">
	                        <b>Tamaño de imagen</b>: Las medidas recomendadas para la carga de estas imagenes es de <b>1300x706px</b>. Los formatos admitidos son jpg ó png.
	                      </div>

						<div class="dropzone" id="pics-dropzone">
						</div>
						
						<br>
						<?if(count($fotos)>0): ?>
						<h3>Fotos subidas</h3>
						<br>
						<? endif; ?>
						<ul class="galeriafotos">
							<? foreach ($fotos as $f) : ?>
								
								<li id="li_<?=$f->id;?>" class="col-md-2" style="">
									<img src="<?=base_url();?>uploads/destinos/<?=$row->id;?>/<?=$f->foto;?>" alt=""/>

									<a href="#" class="delFoto" data-rel="<?=$f->id;?>">X</a>
								</li>
							
							<? endforeach; ?>
						</ul>

					<? else: ?>
						<p>Debes guardar los datos antes de subir fotos del destino.</p>
					<? endif; ?>

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

  
<div id="cargarPopup" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen']['width'];?>x<?=$this->uploads['imagen']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG, JPG ó GIF.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/destinos/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>


<iframe src="<?=site_url('admin/destinos/iframe');?>" id="uploader" name="uploader" style="position:absolute; left:-10000px;"></iframe>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>media/admin/assets/css/dropzone.css"></link>
<script src="<?=base_url();?>media/admin/assets/js/dropzone.js"></script>
	
<script>
var current_field = '';
$(document).ready(function(){

	$('#codigo').attr('minlength','3');

	<? if (isset($row->id)): ?>
		$("div#pics-dropzone").dropzone({ 
									url: "<?=$route;?>/upload_fotos/<?=$row->id;?>",
									dictDefaultMessage: "Haz click o arrastra archivos aquí"
								});

		$('body').on('click','.delFoto',function(e){
			e.preventDefault();
			var id = $(this).attr('data-rel');
			bootbox.confirm("Esta seguro que desea borrar la imagen?", function(result){
				if (result) {
					$.post("<?=$route;?>/borrar_foto/"+id,function(data){
						if(data){
							$('#li_'+id).remove();

							if(!$('.galeriafotos li').length){
								$('.galeriafotos').hide();
							}
						}
					});
				}
			});
			
		});
	<? endif; ?>
			
	$('.btnRemoveImage').click(function(e){
		e.preventDefault();
		
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

	  

	  $('body').on('change', '.modal-dialog input', function(){
		var bar = $('.bar');
		var percent = $('.percent');
			var percentVal = '0%';
			bar.width(percentVal)
			percent.html(percentVal);

		$('.modal-dialog form').ajaxForm({
		  url: '<?=site_url('admin/destinos/upload');?>',
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
  
	$('#nombre').on('keyup', function(){
		$('#slug').val( string_to_slug($(this).val()) );
	});
	
	$('#formEdit').on('submit', function(e){
		if( !validar() ){
			e.preventDefault();
		}
	});
	
	$('.comboEstacionales').val([<?=implode(',',$mis_estacionales);?>]).trigger("change");

	/*$('.s3').select2({
		maximumSelectionLength: 3
	});*/

});
</script>
    
<?php echo $footer; ?>