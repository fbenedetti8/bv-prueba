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
                      <?php echo $this->admin->input('nombre', 'Nombre de medio de pago', '', $row, false); ?>
					  
					  <?php echo $this->admin->textarea('descripcion', 'Descripción', '', $row, false); ?>
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
    
<script src="<?=base_url();?>media/admin/ckeditor/ckeditor.js?v=2"></script>
<script>
$(document).ready(function(){
  
  //editor de html para campo descripcion
  CKEDITOR.config.height = '100px';
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.language = 'es';
  CKEDITOR.config.removePlugins = 'elementspath';
  CKEDITOR.config.resize_enabled = false;
  CKEDITOR.config.toolbar = [
      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Link', '-', 'RemoveFormat' ] },
  ];
  CKEDITOR.replace( 'descripcion' );

});
</script>

<?php echo $footer; ?>