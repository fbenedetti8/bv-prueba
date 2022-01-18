<?php echo $header;?>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
			<span>Esta sección le permite configurar parámetros generales de administración de los viajes grupales del sitio.</span>
		</div>
	</div>
	
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data" onsubmit="return validar();">
			
	<div class="row">
		<div class="col-md-12">	
			
			<div class="widget box">
				<div class="widget-header">
				  <h3 class="pull-left" style="margin-bottom:20px;">Propiedades</h3>
				</div>
				<div class="widget-content">							
					<?php $this->admin->showErrors(); ?>
					
					<?php echo $this->admin->input('titulo_grupales', 'Título', '', $row, $required=true);?>
					<?php echo $this->admin->input('subtitulo_grupales', 'Subtítulo', '', $row, $required=true);?>
					<?php echo $this->admin->input('descripcion_grupales', 'Descripción', '', $row, $required=true);?>
				</div>	
				<div class="widget-header">
				  <h3 class="pull-left" style="margin-bottom:20px;">Imagen Desktop</h3>
				  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen_grupales">Quitar</button>
				</div>
				<div class="widget-content" style="background:#CCC; text-align:center;">
				  <a href="#" class="btnPopupImage" data-ref="imagen_grupales">
					<img id="preview_imagen_grupales" src="<?=isset($row->id) && file_exists('./uploads/config/'.$row->id.'/'.$row->imagen_grupales) ? static_url('uploads/config/'.$row->id.'/'.$row->imagen_grupales) : 'https://placeholdit.imgix.net/~text?txtsize=33&txt='.$this->uploads['imagen_grupales']['width'].'%C3%97'.$this->uploads['imagen_grupales']['height'].'&w=600&h=85';?>" data-placeholder="https://placeholdit.imgix.net/~text?txtsize=33&txt=<?=$this->uploads['imagen_grupales']['width'];?>%C3%97<?=$this->uploads['imagen_grupales']['height'];?>&w=600&h=85" class="img-responsive" style="margin:0 auto;" />
				  </a>
				  <input type="hidden" id="imagen_grupales" name="imagen_grupales" value="<?=@$row->imagen_grupales;?>" />
				</div>
				<div class="widget-header">
				  <h3 class="pull-left" style="margin-bottom:20px;">Imagen Mobile</h3>
				  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen_mobile">Quitar</button>
				</div>
				<div class="widget-content" style="background:#CCC; text-align:center;">
				  <a href="#" class="btnPopupImage" data-ref="imagen_mobile_grupales">
					<img id="preview_imagen_mobile_grupales" src="<?=isset($row->id) && file_exists('./uploads/config/'.$row->id.'/'.$row->imagen_mobile_grupales) ? static_url('uploads/config/'.$row->id.'/'.$row->imagen_mobile_grupales) : 'https://placeholdit.imgix.net/~text?txtsize=33&txt='.$this->uploads['imagen_mobile_grupales']['width'].'%C3%97'.$this->uploads['imagen_mobile_grupales']['height'].'&w=300&h=168';?>" data-placeholder="https://placeholdit.imgix.net/~text?txtsize=33&txt=<?=$this->uploads['imagen_mobile_grupales']['width'];?>%C3%97<?=$this->uploads['imagen_mobile_grupales']['height'];?>&w=300&h=168" class="img-responsive" style="margin:0 auto;" />
				  </a>
				  <input type="hidden" id="imagen_mobile_grupales" name="imagen_mobile_grupales" value="<?=@$row->imagen_mobile_grupales;?>" />
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
});  
</script>
		
<?php echo $footer;	?>