<?php echo $header;?>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
    </div>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    <div class="col-md-12"> 
              <div class="widget box">
                <div class="widget-header">
                  <h3 style="margin-bottom:20px;">Propiedades</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 
                      <?php echo $this->admin->input('nombre', 'Nombre de categoría', '', $row, false); ?>
                      <?php echo $this->admin->input('slug', 'Slug', '', $row, false); ?>
                      <?php echo $this->admin->checkbox('visible', 'Visible', 'chk', $row); ?>
                      <?php echo $this->admin->checkbox('destacada_home', 'Destacada home', 'chk', $row); ?>
                      <?php echo $this->admin->checkbox('otros', 'Mostrar como "otros"', 'chk', $row); ?>
                    </div>
                  </div>
                </div>
                <div class="widget-header">
                  <h3 class="pull-left" style="margin-bottom:20px;">Imagen interna Región</h3>
                  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
                </div>
                <div class="widget-content" style="background:#CCC; text-align:center;">
                  <a href="#" class="btnPopupImage" data-ref="imagen">
                    <img id="preview_imagen" src="<?=isset($row->id) && $row->imagen && file_exists('./uploads/categorias/'.$row->id.'/'.$row->imagen) ? static_url('uploads/categorias/'.$row->id.'/'.$row->imagen) : 'https://via.placeholder.com/'.$this->uploads['imagen']['width'].'x'.$this->uploads['imagen']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen']['width'];?>x<?=$this->uploads['imagen']['height'];?>" class="img-responsive" style="margin:0 auto;" />
                  </a>
                  <input type="hidden" id="imagen" name="imagen" value="<?=@$row->imagen;?>" />
                </div>
                <div class="widget-header">
                  <h3 class="pull-left" style="margin-bottom:20px;">Imagen de Portada</h3>
                  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen_mobile">Quitar</button>
                </div>
                <div class="widget-content" style="background:#CCC; text-align:center;">
                  <a href="#" class="btnPopupImage" data-ref="imagen_mobile">
                    <img id="preview_imagen_mobile" src="<?=isset($row->id) && $row->imagen_mobile && file_exists('./uploads/categorias/'.$row->id.'/'.$row->imagen_mobile) ? static_url('uploads/categorias/'.$row->id.'/'.$row->imagen_mobile) : 'https://via.placeholder.com/'.$this->uploads['imagen_mobile']['width'].'x'.$this->uploads['imagen_mobile']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen_mobile']['width'];?>x<?=$this->uploads['imagen_mobile']['height'];?>" class="img-responsive" style="margin:0 auto;" />
                  </a>
                  <input type="hidden" id="imagen_mobile" name="imagen_mobile" value="<?=@$row->imagen_mobile;?>" />
                </div>
                <div class="widget-header">
                  <h3 style="margin-bottom:20px;">SEO</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 
                      <?php echo $this->admin->input('titulo', 'Título', '', $row, false); ?>
					  <?php echo $this->admin->input('subtitulo', 'Subtítulo', '', $row, false, FALSE, "", "", '', 0,'',$max_subtitulo=90); ?>
                      <?php echo $this->admin->textarea('descripcion', 'Descripción', '', $row, false); ?>
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
                <div class="form-actions">
                  <input type="hidden" name="id" value="<?=@$row->id;?>" />
                  <input type="submit" value="Grabar" class="btn btn-primary">
                  <input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
                </div>
              </div>
            
    </div>
  </div>

  </form>


<div id="cargarPopup" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen']['width'];?>x<?=$this->uploads['imagen']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/categorias/upload');?>" enctype="multipart/form-data" target="uploader">
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
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_mobile']['width'];?>x<?=$this->uploads['imagen_mobile']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/categorias/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<iframe src="<?=site_url('admin/categorias/iframe');?>" id="uploader" name="uploader" style="position:absolute; left:-10000px;"></iframe>

<script src="<?=base_url();?>media/admin/ckeditor/ckeditor.js?v=2"></script>

<script>
var current_field = '';

$(document).ready(function(){
	
	init_max_length('subtitulo',90);

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
      url: '<?=site_url('admin/categorias/upload');?>',
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

  CKEDITOR.config.height = '100px';
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.language = 'es';
  CKEDITOR.config.removePlugins = 'elementspath';
  CKEDITOR.config.resize_enabled = false;
  CKEDITOR.config.toolbar = [
      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'TextColor', '-', 'RemoveFormat' ] },
  ];
  CKEDITOR.replace( 'descripcion' );
});
</script>  
    
<?php echo $footer; ?>