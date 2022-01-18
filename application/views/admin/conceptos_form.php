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
                      <?php echo $this->admin->input('nombre', 'Nombre del concepto', '', $row, false); ?>
                      <?php $aplica = array(array('id'=>'debe'),array('id'=>'haber'),array('id'=>'ambos'));
					  echo $this->admin->combo('aplica_a', 'Aplica a', 's2', $row, $aplica, 'id', 'id'); ?>
                    </div>
                  </div>
				  <div class="row form-group chkb"> 
					<label class="col-xs-3 text-right">
						<div class="chk_name">Pasa a Confirmada:</div>
					</label>
					<div class="col-xs-9">
						<input type="checkbox" name="pasa_a_confirmada" value="1" <?=@$row->pasa_a_confirmada ? 'checked' : '';?> />
					</div>
				  </div>
				  <div class="row form-group chkb"> 
					<label class="col-xs-3 text-right">
						<div class="chk_name">Facturación:</div>
					</label>
					<div class="col-xs-9">
						<input type="checkbox" name="facturacion" value="1" <?=@$row->facturacion ? 'checked' : '';?> />
					</div>
				  </div>
				  <div class="row form-group chkb"> 
					<label class="col-xs-3 text-right">
						<div class="chk_name">Envía mail:</div>
					</label>
					<div class="col-xs-9">
						<input type="checkbox" name="envia_mail" value="1" <?=@$row->envia_mail ? 'checked' : '';?> />
					</div>
				  </div>
				  <div class="row form-group chkb"> 
					<label class="col-xs-3 text-right">
						<div class="chk_name">Incluye gastos administrativos:</div>
					</label>
					<div class="col-xs-9">
						<input type="checkbox" name="incluye_gastos" value="1" <?=@$row->incluye_gastos ? 'checked' : '';?> />
					</div>
				  </div>
				  <div class="row form-group chkb"> 
					<label class="col-xs-3 text-right">
						<div class="chk_name">Movimiento de sistema de caja:</div>
					</label>
					<div class="col-xs-9">
						<input type="checkbox" name="sistema_caja" value="1" <?=@$row->sistema_caja ? 'checked' : '';?> />
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