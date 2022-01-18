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

                      <div class="form-group">
                        <label class="col-md-3 control-label">Nombre</label>
                        <div class="col-md-4">
                          <div class="input-group">
                            <input type="text" class="form-control" name="nombre" value="<?=@$row->nombre;?>">
                          </div>    
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-md-3 control-label">Valor Desde</label>
                        <div class="col-md-2">
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" name="desde" value="<?=@$row->desde;?>">
                          </div>    
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-md-3 control-label">Valor Hasta</label>
                        <div class="col-md-2">
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" name="hasta" value="<?=@$row->hasta;?>">
                          </div>    
                        </div>
                      </div>

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
    
<script>
  $('#formEdit').on('submit', function(){
    if ($(this).hasClass('validado')) {
      return true;
    }

    $.post('<?=site_url('admin/comisiones_escalas/validar');?>', $(this).serialize(), function(result){
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
</script>

<?php echo $footer; ?>