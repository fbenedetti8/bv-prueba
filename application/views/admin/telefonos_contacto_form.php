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
                      <?php echo $this->admin->input('telefono', 'Teléfono', '', $row, false); ?>
                      <?php echo $this->admin->combo('id_pais', 'Seleccionar País', 's2', $row, $paises, 'id', 'nombre'); ?>
                      <?php echo $this->admin->file('imagen', 'Imagen<br><small>Tamaño sugerido: 50x50px</small>', '', @$row->imagen, '/uploads/'.$this->page.'/'.@$row->id.'/', $type = 'view',$attributes="", 'image/*'); ?>
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