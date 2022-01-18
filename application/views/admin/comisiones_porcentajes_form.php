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
                  <h3 style="margin-bottom:20px;">
                    Cargar los porcentajes de comisión
                  </h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12">

                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
                        <thead>
                          <tr>
                            <? foreach ($escalas as $escala): ?>
                              <?php echo $this->admin->th('escala', $escala->nombre, false,array('text-align'=>'center'));?>
                            <? endforeach; ?>
                          </tr>
                        </thead>
                        <tbody>
                          <? $i = 0; ?>
                          <tr class="<?php echo alternator('odd', 'even');?>">
                            <? foreach ($escalas as $escala): //por cada uno de las escalas ?>
                            <td>
                                <div class="form-group" style="border:0;padding: 5px 0;">
                                  <label class="col-md-6 control-label">Comisión</label>
                                  <div class="col-md-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control" name="comision[<?=$escala->id;?>][<?=$i;?>]" value="<?=@$datos[$i][$escala->id]['comision'];?>">
                                      <span class="input-group-addon">%</span>
                                    </div>    
                                  </div>
                                </div>
                                <div class="form-group" style="border:0;padding: 5px 0;">
                                  <label class="col-md-6 control-label">Com. equipo</label>
                                  <div class="col-md-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control" name="comision_eq[<?=$escala->id;?>][<?=$i;?>]" value="<?=@$datos[$i][$escala->id]['comision_eq'];?>">
                                      <span class="input-group-addon">%</span>
                                    </div>    
                                  </div>
                                </div>
                            </td>
                            <? endforeach;?>
                            
                            <input type="hidden" name="rol" value="<?=$rol;?>" />

                            
                          </tr>
                          
                        </tbody>
                      </table>


                    </div>  
                      
                  </div>
                </div>
                <div class="form-actions">
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

    $.post('<?=site_url('admin/comisiones_porcentajes/validar');?>', $(this).serialize(), function(result){
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