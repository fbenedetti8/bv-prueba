<?php echo $header; ?>

<style>
tr.ui-sortable-helper { display:table; border:dashed 1px #CCC; }
.modal-dialog { width:1000px !important; }
</style>

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
              <div class="widget box">
                <div class="widget-header">
                  <h4>Estado de Ventas de Vendedores</h4>
                </div>
                
                <div class="widget-content" style="display: block;">

                  <? if (!$rows): ?>
                  <div class="alert alert-info">No hay datos disponibles para mostrar.</div>
                  <? else: ?>

                  <table class="table table-striped">
                    <thead>
                      <tr>
	                    <th style="width:5%" class="text-right">#</th>
                        <th style="width:35%">Vendedor</th>
                        <th style="width:20%" class="text-right">Ventas</th>
                        <th style="width:20%" class="text-right">Total Vendido</th>
                        <th style="width:20%" class="text-right">Total Comisionable</th>
                      </tr>
                    </thead>
                    <tbody>
                      <? $pos=0; foreach ($rows as $row): $pos+=1; ?>
                      <tr>
	                    <td class="text-right"><?=$pos;?></td>
                        <td><?=$row->nombre.' '.$row->apellido;?></td>
                        <td class="text-right"><?=$row->paquetes_directos;?></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_directa, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->total_comisionable_directo, 2, ',', '.');?></td>
                      </tr>
                      <? endforeach; ?>
                    </tbody>
                  </table>

                  <? endif; ?>
                </div> <!-- /.col-md-12 -->
                <div class="widget-header" style="border-top:solid 1px #CCC;">
                  <h4>Estado de Ventas de Equipos</h4>
                </div>
                
                <div class="widget-content" style="display: block;">

                  <? if (!$rows_equipos): ?>
                  <div class="alert alert-info">No hay datos disponibles para mostrar.</div>
                  <? else: ?>

                  <table class="table table-striped">
                    <thead>
                      <tr>
	                    <th style="width:5%" class="text-right">#</th>
                        <th style="width:35%">Equipo</th>
                        <th style="width:20%" class="text-right">Ventas</th>
                        <th style="width:20%" class="text-right">Total Vendido</th>
                        <th style="width:20%" class="text-right">Total Comisionable</th>
                      </tr>
                    </thead>
                    <tbody>
                      <? $pos=0; foreach ($rows_equipos as $row): $pos+=1; ?>
                      <tr>
	                    <td class="text-right"><?=$pos;?></td>
                        <td><?=$row->nombre;?></td>
                        <td class="text-right"><?=$row->paquetes_directos;?></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_directa, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->total_comisionable_directo, 2, ',', '.');?></td>
                      </tr>
                      <? endforeach; ?>
                    </tbody>
                  </table>

                  <? endif; ?>
                </div> <!-- /.col-md-12 -->
                <div class="form-actions">
                  <a href="<?=site_url('admin/comisiones_reportes');?>" class="btn btn-primary">Volver</a>
                </div>
              </div>

          </div> <!-- /.col-md-12 -->
          <!-- /Example Box -->
        </div> <!-- /.row -->
        <!-- /Page Content -->

<script>
$(document).ready(function(){
  $('.btnDetalleVentas').click(function(e){
    e.preventDefault();
    $.post('<?=site_url('admin/comisiones_reportes/detalle_ventas');?>/' + $(this).data('liquidacion') + '/' + $(this).data('tipo'), function(data){
      bootbox.alert(data);
    });
  });
});
</script>

<?php echo $footer;?>