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
                      <?php echo $this->admin->input('telefono', 'Teléfono', '', $row, false); ?>

                      <?php echo $this->admin->input('relevancia', 'Número de Relevancia', '', $row, false,$readonly=FALSE, $style="", $hint="", $note = '', $limit=0,$placeholder='',$max_length=false,$input_type='number'); ?>

                        <div class="alert alert-info">
                          <b>Número de relevancia</b>: Debe ser un valor comprendido entre 1 y 10.
                          <br>
                          <b>Número a mostrar en el sitio</b>: El sistema mostrará en el sitio un número de celular de forma aleatoria, teniendo en cuenta el valor de relevancia de cada uno. Cuanto mayor es el número, mayor probabilidad de aparición.
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

    <script type="text/javascript">
      $(document).ready(function(){
        $('#relevancia').attr('max',10);
        $('#relevancia').attr('min',1);
      });
    </script>
