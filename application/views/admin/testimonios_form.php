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
                      <?php echo $this->admin->input('nombre', 'Nombre', '', $row, false); ?>
                      <?php echo $this->admin->input('lugar', 'Lugar', '', $row, false); ?>
                      <?php echo $this->admin->input('provincia', 'Provincia', '', $row, false); ?>
                      <?php echo $this->admin->input('pais', 'País', '', $row, false); ?>
                     

                      <?php echo $this->admin->textarea('testimonio', 'Testimonio<br>', '', $row,7,50,false,350,''); ?>
                    </div>
                  </div>
                </div>
                 <div class="widget-header">
                  <h3 style="margin-bottom:20px;">Imagen</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 

                      <div class="alert alert-info">
                        <b>Tamaño de imagen</b>: Las medidas recomendadas para la carga de esta imagen es de <b>500x500px</b>. Los formatos admitidos son jpg ó png.
                        <? $resizes = $this->config->item('resizes_destinos'); ?>
                        <? if(count($resizes)): ?>
                        <br><b>Resizes de imagen</b>: El sistema genererá automáticamente <?=count($resizes);?> imagenes adicionales las cuales se usarán para la Home.
                        <? endif; ?>
                      </div>

                      <?php echo $this->admin->file('imagen', 'Seleccionar', '', @$row->imagen, '/uploads/'.$this->page.'/'.@$row->id.'/', $type = 'view',$attributes="", 'image/*'); ?>
                      
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
    
<script src="<?=base_url();?>media/admin/ckeditor/ckeditor.js?v=2"></script>

<?php echo $footer; ?>
  
<script>
$(document).ready(function(){

  if ($('#testimonio').length) {
    //editor de html para campo "testimonio
    CKEDITOR.config.height = '100px';
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.language = 'es';
    CKEDITOR.config.removePlugins = 'elementspath';
    CKEDITOR.config.resize_enabled = false;
    CKEDITOR.config.entities  = false;
    CKEDITOR.config.toolbar = [
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold' ] },
    ];
    CKEDITOR.replace( 'testimonio' );
  }

  CKEDITOR.instances.testimonio.on('change', function() { 
      var str = strip_html_tags(CKEDITOR.instances.testimonio.getData());
      if(str){

        str = str.replace(/&nbsp;/g,' ') ; 
        console.log(str);
        $('#testimonio').val(str);
        var length =str.length;
        var limit = $('#testimonio').data('limit');
        console.log(length);
        console.log(limit);

         if( str.length >= limit ) {
           return false;
       }

      }
      else{
         var length = 0;
        var limit = $('#testimonio').data('limit');

      }

      update_chars('#testimonio',length,limit);
  });

});

function strip_html_tags(str)
{
   if ((str===null) || (str===''))
       return false;
  else
   str = str.toString();
  return str.replace(/<[^>]*>/g, '');
}
</script>