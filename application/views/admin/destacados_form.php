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
                       <?php echo $this->admin->input('nombre', 'Nombre de destacado', '', $row, false); ?>
                       <?php echo $this->admin->checkbox('visible', 'Visible', '', $row, false); ?>

                       <?php $tipos = [
                                        ['id' => 'imagen', 'nombre' => 'Imagen'],
                                        ['id' => 'video', 'nombre' => 'Video']
                                      ];
                       echo $this->admin->combo('tipo', 'Tipo destacado', 's2', $row, $tipos, 'id', 'nombre', true); ?>

                       <div class="box_tipo box_video" style="display:none;">
                         <div class="alert alert-info cartel">
                          <span></span>
                          El video debe tener un <strong>tamaño no mayor a 3 Mb.</strong> y una <strong>dimensión recomendada de <?=$this->uploads['video_mp4']['width'];?>x<?=$this->uploads['video_mp4']['height'];?> pixels ó proporcional</strong>.
                        </div>


                        <?php echo $this->admin->file('video_mp4', 'Archivo de video MP4', '', @$row->video_mp4, '/uploads/'.$this->page.'/'.@$row->id.'/', $type = 'viewvid',$attributes="", '.mp4'); ?>
                        <?php echo $this->admin->file('video_webm', 'Archivo de video WebM', '', @$row->video_webm, '/uploads/'.$this->page.'/'.@$row->id.'/', $type = 'viewvid',$attributes="", '.webm'); ?>
                        <?php echo $this->admin->file('video_ogg', 'Archivo de video OGG', '', @$row->video_ogg, '/uploads/'.$this->page.'/'.@$row->id.'/', $type = 'viewvid',$attributes="", '.ogg'); ?>


                        <div class="alert alert-info cartel">
                          <span></span>
                          El video contendrá una imagen a modo de previsualización la cual se mostrará mientras se carga el video completo en el sitio.</strong>.
                        </div>

                        <div class="widget-header box_tipo box_video" style="display:none;">
                          <h3 class="pull-left" style="margin-bottom:20px;">Imagen de fondo (desktop)</h3>
                          <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
                        </div>
                        <div class="widget-content box_tipo box_video" style="background:#CCC; text-align:center; display: none;">
                          <a href="#" class="btnPopupImageBg" data-ref="imagen_bg">
                            <img id="preview_imagen_bg" src="<?=isset($row->id) && $row->imagen_bg && file_exists('./uploads/destacados/'.$row->id.'/'.$row->imagen_bg) ? static_url('uploads/destacados/'.$row->id.'/'.$row->imagen_bg) : 'https://via.placeholder.com/'.$this->uploads['imagen_bg']['width'].'x'.$this->uploads['imagen_bg']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen_bg']['width'];?>x<?=$this->uploads['imagen_bg']['height'];?>" class="img-responsive" style="margin:0 auto;" />
                          </a>
                          <input type="hidden" id="imagen_bg" name="imagen_bg" value="<?=@$row->imagen_bg;?>" />
                        </div>

                        <div class="widget-header box_tipo box_video" style="display:none;">
                          <h3 class="pull-left" style="margin-bottom:20px;">Imagen de fondo (mobile)</h3>
                          <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
                        </div>
                        <div class="widget-content box_tipo box_video" style="background:#CCC; text-align:center; display: none;">
                          <a href="#" class="btnPopupImageBgMobile" data-ref="imagen_bg_mobile">
                            <img id="preview_imagen_bg_mobile" src="<?=isset($row->id) && $row->imagen_bg_mobile && file_exists('./uploads/destacados/'.$row->id.'/'.$row->imagen_bg_mobile) ? static_url('uploads/destacados/'.$row->id.'/'.$row->imagen_bg_mobile) : 'https://via.placeholder.com/'.$this->uploads['imagen_bg_mobile']['width'].'x'.$this->uploads['imagen_bg_mobile']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen_bg_mobile']['width'];?>x<?=$this->uploads['imagen_bg_mobile']['height'];?>" class="img-responsive" style="margin:0 auto;" />
                          </a>
                          <input type="hidden" id="imagen_bg_mobile" name="imagen_bg_mobile" value="<?=@$row->imagen_bg_mobile;?>" />
                        </div>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="widget-header box_tipo box_imagen" style="display:none;">
                  <h3 class="pull-left" style="margin-bottom:20px;">Imagen Desktop</h3>
                  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
                </div>
                <div class="widget-content box_tipo box_imagen" style="background:#CCC; text-align:center; display: none;">
                  <a href="#" class="btnPopupImage" data-ref="imagen">
                    <img id="preview_imagen" src="<?=isset($row->id) && $row->imagen && file_exists('./uploads/destacados/'.$row->id.'/'.$row->imagen) ? static_url('uploads/destacados/'.$row->id.'/'.$row->imagen) : 'https://via.placeholder.com/'.$this->uploads['imagen']['width'].'x'.$this->uploads['imagen']['height']
                    ;?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen']['width'];?>x<?=$this->uploads['imagen']['height'];?>
                    " class="img-responsive" style="margin:0 auto;" />
                  </a>
                  <input type="hidden" id="imagen" name="imagen" value="<?=@$row->imagen;?>" />
                </div>
                
                <div class="widget-header box_tipo box_imagen" style="display:none;">
                  <h3 class="pull-left" style="margin-bottom:20px;">Imagen Mobile</h3>
                  <button type="button" class="btn btn-danger btnRemoveImage pull-right" style="margin-top:15px;" data-ref="imagen">Quitar</button>
                </div>
                <div class="widget-content box_tipo box_imagen" style="background:#CCC; text-align:center; display: none;">
                  <a href="#" class="btnPopupImageMobile" data-ref="imagen_mobile">
                    <img id="preview_imagen_mobile" src="<?=isset($row->id) && $row->imagen_mobile && file_exists('./uploads/destacados/'.$row->id.'/'.$row->imagen_mobile) ? static_url('uploads/destacados/'.$row->id.'/'.$row->imagen_mobile) : 'https://via.placeholder.com/'.$this->uploads['imagen_mobile']['width'].'x'.$this->uploads['imagen_mobile']['height'];?>" data-placeholder="https://via.placeholder.com/<?=$this->uploads['imagen_mobile']['width'];?>x<?=$this->uploads['imagen_mobile']['height'];?>" class="img-responsive" style="margin:0 auto;" />
                  </a>
                  <input type="hidden" id="imagen_mobile" name="imagen_mobile" value="<?=@$row->imagen_mobile;?>" />
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
    <form id="fUpload" method="post" action="<?=site_url('admin/destacados/upload');?>" enctype="multipart/form-data" target="uploader">
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
    <form id="fUpload" method="post" action="<?=site_url('admin/destacados/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<div id="cargarPopupBg" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_bg']['width'];?>x<?=$this->uploads['imagen_bg']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/destacados/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<div id="cargarPopupBgMobile" class="hidden">
  <div class="text-center">
    <div class="alert alert-info cartel">
      <span></span>
      La imagen debe tener un <strong>tamaño no mayor a 2 Mb.</strong> y una <strong>dimensión de <?=$this->uploads['imagen_bg_mobile']['width'];?>x<?=$this->uploads['imagen_bg_mobile']['height'];?> pixels ó proporcional</strong>. Sólo se aceptan los formatos PNG o JPG.
    </div>
    <br/>
    <form id="fUpload" method="post" action="<?=site_url('admin/destacados/upload');?>" enctype="multipart/form-data" target="uploader">
      <input type="file" name="foto" style="border:solid 1px #DDD; padding:10px; width:100%;" />
      <input type="hidden" name="field" id="field" />
      <div class="progress" style="display:none">
            <div class="bar"></div >
            <div class="percent">0%</div >
        </div>      
    </form>
  </div>
</div>

<iframe src="<?=site_url('admin/destacados/iframe');?>" id="uploader" name="uploader" style="position:absolute; left:-10000px;"></iframe>

<script>
var current_field = '';

$(document).ready(function(){
  $('#tipo option:eq(0)').text('Selecciona una opción').attr('disabled','disabled');

  $('.btnRemoveImage').click(function(){
    $('#' + $(this).data('ref')).val('');
    $('#preview_' + $(this).data('ref')).attr('src', $('#preview_' + $(this).data('ref')).data('placeholder'));
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

  $('.btnPopupImageBg').click(function(e){
    e.preventDefault();
    current_field = $(this).data('ref');
    $('#cargarPopupBg #field').val(current_field);

    bootbox.dialog({
      message: $('#cargarPopupBg').html(),
      title: 'Cargar imagen',
      buttons: {
        success: {
          label: "Cerrar",
          className: "btn-primary"
        }
      }
    });
  });

  $('.btnPopupImageBgMobile').click(function(e){
    e.preventDefault();
    current_field = $(this).data('ref');
    $('#cargarPopupBgMobile #field').val(current_field);

    bootbox.dialog({
      message: $('#cargarPopupBgMobile').html(),
      title: 'Cargar imagen',
      buttons: {
        success: {
          label: "Cerrar",
          className: "btn-primary"
        }
      }
    });
  });

  <? if(isset($row->tipo) && $row->tipo){ ?>
    $('.box_tipo').hide();
    $('.box_<?=$row->tipo?>').show();
  <? } ?>

  $('body').on('change', '#tipo', function(){
    var val = $(this).val();
    $('.box_tipo').hide();
    $('.box_'+val).show();
  });

  $('body').on('change', '.modal-dialog input', function(){
    var bar = $('.bar');
    var percent = $('.percent');
        var percentVal = '0%';
        bar.width(percentVal)
        percent.html(percentVal);

    $('.modal-dialog form').ajaxForm({
      url: '<?=site_url('admin/destacados/upload');?>',
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
    
<?php echo $footer; ?>