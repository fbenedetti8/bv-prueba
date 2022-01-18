<?php echo $header; ?>

<style>
tr.ui-sortable-helper { display:table; border:dashed 1px #CCC; }
</style>
  
        <!--=== Page Header ===-->
        <div class="page-header">
          <div class="page-title">
            <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
            <span>Los reportes permiten analizar la informacion de comisiones.</span>
          </div>
        </div>
        <!-- /Page Header -->

        <!--=== Page Content ===-->
        <div class="row">
          <!--=== Example Box ===-->
          <div class="col-md-12">
            <form id="fReporte" method="post" action="<?=site_url('admin/comisiones_reportes/generar');?>">
              <div class="widget box">
                <div class="widget-header">
                  <h4>Generación de Reportes</h4>
                </div>
                
                <div class="widget-content" style="display: block;">

                  <div class="text-center">
	                	<input type="radio" name="tipo" value="mes" id="porMes" <?=$tipo=='mes' ? 'checked' : '';?> /> Por mes
	                	&nbsp;&nbsp;&nbsp;
	                	<input type="radio" name="tipo" value="fecha" id="porFecha" <?=$tipo=='fecha' ? 'checked' : '';?> /> Por rango de fechas
						<br/><br/>
	                	<div id="porMes_block" style="<?=$tipo=='fecha' ? 'display:none;' : '';?>">
	                        <select size="1" name="mes" class="s2" style="width:100px;">
	                          <option value="">Mes</option>
	                          <option value="1" <?=$mes==1 ? 'selected' : '';?>>Enero</option>
	                          <option value="2" <?=$mes==2 ? 'selected' : '';?>>Febrero</option>
	                          <option value="3" <?=$mes==3 ? 'selected' : '';?>>Marzo</option>
	                          <option value="4" <?=$mes==4 ? 'selected' : '';?>>Abril</option>
	                          <option value="5" <?=$mes==5 ? 'selected' : '';?>>Mayo</option>
	                          <option value="6" <?=$mes==6 ? 'selected' : '';?>>Junio</option>
	                          <option value="7" <?=$mes==7 ? 'selected' : '';?>>Julio</option>
	                          <option value="8" <?=$mes==8 ? 'selected' : '';?>>Agosto</option>
	                          <option value="9" <?=$mes==9 ? 'selected' : '';?>>Septiembre</option>
	                          <option value="10" <?=$mes==10 ? 'selected' : '';?>>Octubre</option>
	                          <option value="11" <?=$mes==11 ? 'selected' : '';?>>Noviembre</option>
	                          <option value="12" <?=$mes==12 ? 'selected' : '';?>>Diciembre</option>
	                        </select>
	                        <select size="1" name="anio" class="s2" style="width:100px;">
	                          <option value="">Año</option>
	                          <? for ($i=(date('Y')-1); $i<(date('Y')+1); $i++): ?>
	                          <option value="<?=$i;?>" <?=$i==$anio ? 'selected' : '';?>><?=$i;?></option>
	                          <? endfor; ?>
	                        </select>
	                	</div>
	                	<div id="porFecha_block" style="<?=$tipo=='mes' ? 'display:none;' : '';?>">
							<div class="row">
		                      <div class="col-md-8 col-md-offset-2"> 
		
		                        <div class="row">
		                          <div class="col-md-6 col-md-offset-2"> 
		                            <div class="form-group">
		                              <label class="col-md-3 control-label" style="padding-top:5px;">Desde</label>
		                              <div class="col-md-9">
		                                <input type="text" id="desde" name="desde" class="form-control datepicker" value="<?=$desde;?>" autocomplete="off">
		                              </div>
		                            </div>
		                          </div>
		                        </div>
		
		                        <br/>
		       
		                        <div class="row">
		                          <div class="col-md-6 col-md-offset-2"> 
		                            <div class="form-group">
		                              <label class="col-md-3 control-label" style="padding-top:5px;">Hasta</label>
		                              <div class="col-md-9">
		                                <input type="text" id="hasta" name="hasta" class="form-control datepicker" value="<?=$hasta;?>" autocomplete="off">
		                              </div>
		                            </div>
		                          </div>
		                        </div>
		                        
		                      </div>
		                    </div>
		                </div>	                	
                  </div>
                </div> <!-- /.col-md-12 -->
                <div class="form-actions">
                  <input type="submit" name="ventas" value="Estado de Ventas" class="btn btn-primary">
                  <input type="submit" name="comisiones" value="Reporte de Comisiones" class="btn btn-primary">
                  <input type="submit" name="ranking" value="Ranking de Vendedores" class="btn btn-primary">
                </div>
                
              </div>
            </form>
          </div> <!-- /.col-md-12 -->
          <!-- /Example Box -->
        </div> <!-- /.row -->
        <!-- /Page Content -->

<script>
  $(document).ready(function(){
	$('#porMes').click(function(){
		if ($(this).is(':checked')) {
			$('#porMes_block').show();
			$('#porFecha_block').hide()
		}	
	});
	
	$('#porFecha').click(function(){
		if ($(this).is(':checked')) {
			$('#porMes_block').hide();
			$('#porFecha_block').show()
		}	
	});
	
    $('#fReporte').on('submit', function(){
      if ($('porFecha').is(':checked') && $('#desde').val() == '') {
        bootbox.alert('Seleccione la fecha desde');
        return false;
      }

      if ($('porFecha').is(':checked') && $('#hasta').val() == '') {
        bootbox.alert('Seleccione la fecha hasta');
        return false;
      }
    });
  });
</script>

<?php echo $footer;?>