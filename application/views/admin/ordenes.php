<?php echo $header; ?>

<style>
tr.ui-sortable-helper { display:table; border:dashed 1px #CCC; }
</style>

        <?php if ($saved): ?>
          <br/>
          <div class="alert alert-success fade in"> 
            <i class="icon-remove close" data-dismiss="alert"></i> 
            <strong>&iexcl;Operación completada!</strong> Los datos fueron guardados con éxito.
          </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <br/>
          <div class="alert alert-danger fade in"> 
            <i class="icon-remove close" data-dismiss="alert"></i> 
            <strong>Error!</strong> <?=$this->session->flashdata('error');?>
          </div>
        <?php endif; ?>
        <?php if (!empty($warning)): ?>
          <br/>
          <div class="alert alert-warning fade in"> 
            <i class="icon-remove close" data-dismiss="alert"></i> 
            <strong>&iexcl;Atención!</strong> <?=$this->session->flashdata('error');?>
          </div>
        <?php endif; ?>
  
        <!--=== Page Header ===-->
        <div class="page-header">
          <div class="page-title">
            <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
            <span>Listado de ordenes de reserva existentes.</span>
          </div>
          
          <form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:600px">
            
            <div class="form-group">
              <label class="col-md-2 control-label">Código:</label>
              <div class="input-group col-md-10"> 
                <input type="text" name="keywords" class="form-control" value="<?php echo @$keywords;?>"> 
                <input type="hidden" id="sort" name="sort" value="<?php echo isset($sort)?$sort:"";?>"/>
                <input type="hidden" id="sortType" name="sortType" value="<?php echo isset($sortType)?$sortType:"ASC";?>"/>
                <span class="input-group-btn"> 
                  <button class="btn btn-default" type="submit">Buscar</button> 
                </span> 
              </div>
		    </div>
			<div class="form-group"> 
				<label class="col-md-2 control-label">Destino:</label>
				<div class="input-group col-md-10"> 
					<select name="destino_id" class="form-control" onchange="javascript: location.href = '<?=$route;?>?destino_id='+this.value;">
						<option value="">Todos</option>
						<? foreach($destinos as $d): ?>
						<option value="<?=$d->id;?>" <?=@$destino_id==$d->id?'selected':'';?>><?=$d->nombre;?></option>
						<? endforeach; ?>
					</select>
				</div>
            </div>
			
			<div class="form-group"> 
				<div class="row"> 
					<div class="col-sm-6"> 
						<label class="col-md-4 control-label">Estado:</label>
						<div class="input-group col-md-8"> 
							<select name="visibilidad" class="form-control" onchange="javascript: location.href = '<?=$route;?>?visibilidad='+this.value;" style=" margin: 0 5px">
								<option value="" <?=@$visibilidad==''?'selected':'';?>>Vigentes y Vencidas</option>
								<option value="vigentes" <?=@$visibilidad=='vigentes'?'selected':'';?>>Vigentes</option>
								<option value="vencidas" <?=@$visibilidad=='vencidas'?'selected':'';?>>Vencidas</option>
							</select>
						</div>
					</div>
				
				</div>
            </div>
			
          </form>         
        </div>
        <!-- /Page Header -->

        <!--=== Page Content ===-->
        <div class="row">
          <!--=== Example Box ===-->
          <div class="col-md-12">
            <div class="widget box">
              <div class="widget-header">
                <h4><i class="icon-reorder"></i> <?=$totalRows;?> registro<?=$totalRows == 1 ? '' : 's';?> disponible<?=$totalRows == 1 ? '' : 's';?></h4>
                <div class="btn-group pull-right" style="margin:5px;">
                  <button class="btn btn-sm" id="lnkExport" data-rel="<?=$route;?>/exportar"><i class="icol-doc-excel-table"></i> Exportar</button>
              </div>
              
              <div class="widget-content" style="display: block;">
                <form id="formList">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
                  <thead>
                    <tr>
                      <?php echo $this->admin->th('code', 'Código', true);?>
                      <?php echo $this->admin->th('paquete_codigo', 'Paquete', true);?>
                      <?php echo $this->admin->th('pasajero', 'Pasajero', true);?>
                      <?php echo $this->admin->th('fecha_orden', 'Fecha', true);?>
					            <?php echo $this->admin->th('vencida', 'Estado', true, array('text-align' => 'center'));?>
                      <?php echo $this->admin->th('opciones', 'Opciones', false, array('text-align' => 'center','width'=>'150px'));?>
                    </tr>
                  </thead>
                  <tbody id="">
                    <?php foreach ($data->result() as $row): ?>
                    <tr class="<?php echo alternator('odd', 'even');?>">
                      <?php echo $this->admin->td($row->code);?>
                      <?php echo $this->admin->td('Cód: '.$row->paquete_codigo.' '.$row->nombre);?>
                      <?php echo $this->admin->td($row->pasajero);?>
                      <?php echo $this->admin->td(date('d/m/Y H:i',strtotime($row->fecha_orden)).' hs');?>
                      <td class="text-center"><?=$row->vencida?'<span class="label label-danger" style="font-size: 12px;float: unset;line-height: 18px;">Vencida</span>':'<span class="label label-success" style="font-size: 12px;float: unset;line-height: 18px;">Vigente</span>';?></td>
                      
                      <td>
                        <input type="hidden" name="id[]" value="<?=$row->id;?>" />

                        <div class="btn-group">
                          <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="glyphicon glyphicon-cog" style="font-size:12px;"></i> Opciones <span class="caret"></span>
                          </button>

                          <ul class="dropdown-menu">
                            <li>
                              <a href="<?php echo $route;?>/delete/<?php echo $row->id;?>" class="delete">Borrar</a>
                            </li>
                            <li>
                              <a href="<?=site_url('checkout/orden/'.encriptar($row->code));?>" target="_blank" title="Abrir en el sitio" class="">Abrir en sitio</a>
                            </li>
                            <? if($row->vencida): ?>
                              <li>
                                <a href="#" data-href="<?=site_url('admin/ordenes/restablecer/'.$row->id);?>" data-code="<?=$row->code;?>" class=" btnReestablecer" >Reestablecer</a>
                              </li>
                            <? endif; ?>
                          </ul>
                        </div>



                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($data->result()) == 0): ?>
                    <tr>
                      <td colspan="5" align="center" style="padding:30px 0;">
                        No se encontraron resultados.
                      </td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
                </form>
                
                <div class="row">
                  <div class="table-footer">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                      <?php echo $pages; ?>
                    </div>
                  </div>
                </div>
                
              </div> <!-- /.col-md-12 -->
              
            </div>
          
          </div> <!-- /.col-md-12 -->
          <!-- /Example Box -->
        </div> <!-- /.row -->
        <!-- /Page Content -->
    
<?php echo $footer;?>

  <script src="<?=base_url();?>media/admin/assets/js/jquery.timeMask.js"></script>
  <style>.datepicker{z-index: 10000 !important;}</style>

<script>
$(document).ready(function(){
    
    $('body').on('click','.btnReestablecer',function(e){
      e.preventDefault();

      var codigo = $(this).attr('data-code');
      var url = $(this).attr('data-href');

      bootbox.confirm("El reestablecimiento de la orden Código <b>"+codigo+"</b> dará 1 hora de vigencia, a partir de este momento.<br>Está seguro que desea reestablecerla?", function(result){
        if (result) {
          $.post(url,function(data){
              if(data.status=='SUCCESS'){
                bootbox.alert(data.msg,function(){
                  location.href = location.href;
                });               
              }
            },'json');
        }
        });

    });

    $("#lnkExport").click(function(e){
            e.preventDefault();
            var me = this;
            
            bootbox.confirm("Esto exportará el listado de ordenes. <br>Está seguro?", function(result){
              if (result) {
                location.href = '<?=base_url();?>admin/ordenes/export';
              }
            });
          });
		
	
    $("#lnkExport").click(function(e){
            e.preventDefault();
            var me = this;
            
            bootbox.confirm("Esto exportará el listado de ordenes. <br>Está seguro?", function(result){
              if (result) {
                location.href = '<?=base_url();?>admin/ordenes/export';
              }
            });
          });
});
</script>
