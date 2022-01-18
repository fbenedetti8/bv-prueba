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
                      <div class="form-group">
						<label class="col-md-3 control-label">Cód. Referencia<br><small>máximo 3 caracteres</small> </label>
						<div class="col-md-9">
							<input type="text" id="referencia" name="referencia" class="form-control  limited" data-limit="3" value="<?=@$row->referencia;?>" placeholder="" style="">
							<span><small><b></b></small></span>
							<label for="referencia" generated="true" class="has-error help-block" style="display:none;"></label>
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
    
<?php echo $footer; ?>