<?php echo $header; ?>

<style>
tr.ui-sortable-helper { display:table; border:dashed 1px #CCC; }
#container > #content > .container { width:2050px; }
#content { width:2050px; }
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
        <div class="row" style="wi">
          <!--=== Example Box ===-->
          <div class="col-md-12">
              <div class="widget box" style="width:2020px">
                <div class="widget-header">
                  <h4><?=$title;?> | <a href="<?=$export_url;?>">Exportar</a></h4>
                </div>
                
                <div class="widget-content" style="display: block;">

                  <? if (!$rows): ?>
                  <div class="alert alert-info">No hay datos disponibles para mostrar.</div>
                  <? else: ?>

                  <table class="table table-striped" style="width:2000px;">
                    <thead>
                      <tr>
                        <th>Usuario</th>
                        <th>Jerarquia</th>
                        <th class="text-right">Paquetes Directos</th>
                        <th>Detalle</th>
                        <th class="text-right">Total Venta Directa</th>
                        <th class="text-right">Total Comisionable Directo</th>
                        <th class="text-right">Paquetes Equipo</th>
                        <th>Detalle</th>
                        <th class="text-right">Total Venta Equipo</th>
                        <th class="text-right">Total Comisionable Equipo</th>
                        <th class="text-right">% Comision Directa</th>
                        <th class="text-right">% Comision Equipo</th>
                        <th class="text-right">Comision Directa</th>
                        <th class="text-right">Comision Equipo</th>
                        <th class="text-right">Minimo No Com.</th>
                        <th class="text-right">Total Comision</th>
                      </tr>
                    </thead>
                    <tbody>
                      <? foreach ($rows as $row): ?>
                      <tr>
                        <td><?=$row->usuario;?></td>
                        <td class="text-center"><?=$row->jerarquia;?></td>
                        <td class="text-right"><?=number_format($row->paquetes_directos, 0, ',', '.');?></td>
                        <td class="text-right"><a href="#" class="btn btn-primary btn-xs btnDetalleVentas" data-liquidacion="<?=$row->id;?>" data-tipo="propia"><i class="glyphicon glyphicon-search"></i></a></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_directa, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_directa_comisionable, 2, ',', '.');?></td>
                        <td class="text-right"><?=number_format($row->paquetes_equipo, 0, ',', '.');?></td>
                        <td class="text-right"><a href="#" class="btn btn-primary btn-xs btnDetalleVentas" data-liquidacion="<?=$row->id;?>" data-tipo="equipo"><i class="glyphicon glyphicon-search"></i></a></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_equipo, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->total_venta_equipo_comisionable, 2, ',', '.');?></td>
                        <td class="text-right"><?=number_format($row->porcentaje_comision_directa, 2, ',', '.');?>%</td>
                        <td class="text-right"><?=number_format($row->porcentaje_comision_equipo, 2, ',', '.');?>%</td>
                        <td class="text-right">$ <?=number_format($row->comision_directa, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->comision_equipo, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->minimo_no_comisionable, 2, ',', '.');?></td>
                        <td class="text-right">$ <?=number_format($row->total_comision, 2, ',', '.');?></td>
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