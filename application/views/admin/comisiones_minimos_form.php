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
                    Seleccionar año
                    <select name="anio" onchange="javascript:location.href='<?=site_url("admin/comisiones_minimos/edit/".$rol."/");?>'+this.value;" class="form-control" style="width: 100px;    display: inline-block;">
                      <option <?=@$anio==date('Y')?'selected':'';?> value="<?=date('Y');?>"><?=date('Y');?></option>
                      <option <?=@$anio==date('Y', strtotime('+1 years'))?'selected':'';?> value="<?=date('Y', strtotime('+1 years'));?>"><?=date('Y', strtotime('+1 years'));?></option>
                    </select>
                  </h3>
                </div>
                <div class="widget-content">
                  <div class="row">

                    <div class="col-md-6">

                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped table-border" style="    width: 230px;">
                        <thead>
                          <tr>
                            <?php echo $this->admin->th('mes', 'Mes', false,array('text-align'=>'center'));?>
                            <?php echo $this->admin->th('valor_mnc', 'Valor minimo', false,array('text-align'=>'center'));?>
                          </tr>
                        </thead>
                        <tbody>
                          <? for($i=1;$i<=6;$i++): ?>
                          <tr class="<?php echo alternator('odd', 'even');?>">
                            <td align="center"><?=$i;?></td>

                            <td>
                                <div class="form-group" style="border:0;padding: 5px 0;">
                                  <div class="col-md-10" style="margin: 0 auto;float: none;">
                                    <div class="input-group">
                                      <span class="input-group-addon">$</span>
                                      <input type="text" class="form-control" name="valor_mnc[<?=$i;?>]" value="<?=@$datos[$i]['valor_mnc'];?>">
                                    </div>    
                                  </div>
                                </div>
                            </td>
                            
                            

                          </tr>
                          <?php endfor; ?>
                          
                        </tbody>
                      </table>
                    </div> 

                    <input type="hidden" name="rol" value="<?=$rol;?>" />

                    <div class="col-md-6">

                      <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped table-border" style="    width: 230px;">
                        <thead>
                          <tr>
                            <?php echo $this->admin->th('mes', 'Mes', false,array('text-align'=>'center'));?>
                            <?php echo $this->admin->th('valor_mnc', 'Valor minimo', false,array('text-align'=>'center'));?>
                          </tr>
                        </thead>
                        <tbody>
                          <? for($i=7;$i<=12;$i++): ?>
                          <tr class="<?php echo alternator('odd', 'even');?>">
                            <td align="center"><?=$i;?></td>

                            <td>
                                <div class="form-group" style="border:0;padding: 5px 0;">
                                  <div class="col-md-10" style="margin: 0 auto;float: none;">
                                    <div class="input-group">
                                      <span class="input-group-addon">$</span>
                                      <input type="text" class="form-control" name="valor_mnc[<?=$i;?>]" value="<?=@$datos[$i]['valor_mnc'];?>">
                                    </div>    
                                  </div>
                                </div>
                            </td>
                            
                            

                          </tr>
                          <?php endfor; ?>
                          
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

    $.post('<?=site_url('admin/comisiones_minimos/validar');?>', $(this).serialize(), function(result){
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