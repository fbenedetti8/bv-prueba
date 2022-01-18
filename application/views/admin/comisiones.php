<?php echo $header;	?>
	
				<!--=== Page Header ===-->
				<div class="page-header">
					<div class="page-title">
						<h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
						<span>Genere las liquidaciones de comisiones desde este modulo.</span>
					</div>
				</div>
				<!-- /Page Header -->

				<!--=== Page Content ===-->
				<div class="row">
					<!--=== Example Box ===-->
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4>Liquidacion de comisiones</h4>
							</div>
							
							<div class="widget-content" style="display: block;">
								<? if ($mensaje_exito): ?>
								<div class="alert alert-success"><?=$mensaje_exito;?></div>
								<? elseif ($mensaje_error): ?>
								<div class="alert alert-danger"><?=$mensaje_error;?></div>
								<? endif; ?>
								<? if (isset($mensaje_confirmadas) && $mensaje_confirmadas): ?>
								<div class="alert alert-info"><?=$mensaje_confirmadas?></div>
								<? endif; ?>

								<div class="alert alert-info"><b>Atención</b>: Recuerda que si no se encuentran cargados los importes para un determinado mes en el módulo <a href="<?=site_url('admin/comisiones_minimos');?>"><b>Mínimos no Comisionables</b></a>, dichos valores se tomarán como valor 0 (cero).</div>

								<form id="fComisiones" method="post" action="<?=site_url('admin/comisiones');?>" class="text-center">
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
									<input type="hidden" name="action" value="1" />
									<input type="hidden" name="liquidar" value="1" />
									<button type="submit" class="btn btn-primary">GENERAR</button>
									<? if (isset($liquidaciones) && isset($log) && !isset($cant_confirmadas)): ?>
										<button type="submit" n id="btnConfirmar" class="btn btn-success">CONFIRMAR</button>
									<? endif; ?>
								</form>
								<br/>
								<? if (isset($liquidaciones) && isset($log)): ?>
								<h2 class="text-center">Comisiones a liquidar para <?=$periodo;?></h2>
								<?=$log;?>
								<? endif; ?>
							</div> <!-- /.col-md-12 -->
							
						</div>
					
					</div> <!-- /.col-md-12 -->
					<!-- /Example Box -->
				</div> <!-- /.row -->
				<!-- /Page Content -->

<script>
$(document).ready(function(){
  $('#btnConfirmar').click(function(e){
    e.preventDefault();
     bootbox.confirm("Al confirmar esta acción, se procederá a generar los movimientos en las cuentas corrientes de los vendedores.<br>Está seguro que desea confirmar la operación?", function(result){
		if (result) {
			 $('#fComisiones').append('<input type="hidden" value="1" name="btnConfirmar">');
		 	 $('#fComisiones').submit();
		}
	  });

  });

  $('.btnDetalleVentas').click(function(e){
    e.preventDefault();
    $.post('<?=site_url('admin/comisiones_reportes/detalle_ventas');?>/' + $(this).data('liquidacion') + '/' + $(this).data('tipo'), function(data){
      bootbox.alert(data);
    });
  });
});
</script>

<?php echo $footer;?>