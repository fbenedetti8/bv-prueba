<?php echo $header; ?>

<style>
tr.ui-sortable-helper { display:table; border:dashed 1px #CCC; }
.modal { z-index: 10000; }
</style>

        <?php if ($saved): ?>
          <br/>
          <div class="alert alert-success fade in"> 
            <i class="icon-remove close" data-dismiss="alert"></i> 
            <strong>&iexcl;Operación completada!</strong> Los datos fueron guardados con éxito.
          </div>
        <?php endif; ?>
        <?php if (isset($_GET['duplicado']) && $_GET['duplicado']): ?>
          <br/>
          <div class="alert alert-success fade in"> 
            <i class="icon-remove close" data-dismiss="alert"></i> 
            <strong>&iexcl;Paquete duplicado!</strong> El nuevo paquete fue generado correctamente. Puedes editarlo haciendo <a href="<?=$route;?>/edit/<?=$_GET['duplicado'];?>" class="btn btn-xs btn-primary">click aquí</a>
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
            <span>Listado de paquetes grupales e individuales.</span>
          </div>
          
          <form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:600px">
            
            <div class="form-group">
              <label class="col-md-2 control-label">Buscar:</label>
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
						<label class="col-md-4 control-label">Visibilidad:</label>
						<div class="input-group col-md-8"> 
							<select name="visibilidad" class="form-control" onchange="javascript: location.href = '<?=$route;?>?visibilidad='+this.value;" style=" margin: 0 5px">
								<option value="" <?=@$visibilidad==''?'selected':'';?>>Activos e Inactivos</option>
								<option value="activos" <?=@$visibilidad=='activos'?'selected':'';?>>Activos</option>
								<option value="inactivos" <?=@$visibilidad=='inactivos'?'selected':'';?>>No Activos</option>
							</select>
						</div>
					</div>
				
					<div class="col-sm-6"> 
						<label class="col-md-4 control-label">Tipo Viaje:</label>
						<div class="input-group col-md-8"> 
							<select name="tipoviaje" class="form-control" onchange="javascript: location.href = '<?=$route;?>?tipoviaje='+this.value;">
								<option value="" <?=@$tipoviaje==''?'selected':'';?>>Grupales e Individuales</option>
								<option value="grupales" <?=@$tipoviaje=='grupales'?'selected':'';?>>Grupales</option>
								<option value="individuales" <?=@$tipoviaje=='individuales'?'selected':'';?>>Individuales</option>
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
              </div>
              
              <div class="widget-content" style="display: block;">
                <form id="formList">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped">
                  <thead>
                    <tr>
                      <?php echo $this->admin->th('codigo', 'Código', true);?>
                      <?php echo $this->admin->th('nombre', 'Nombre', true);?>
                      <?php echo $this->admin->th('fecha_inicio', 'Fecha Inicio', true);?>
                      <?php echo $this->admin->th('fecha_fin', 'Fecha Fin', true);?>
                      <?php echo $this->admin->th('v_total', 'Precio Venta', true);?>
					            <?php echo $this->admin->th('activo', 'Activo', true, array('text-align' => 'center'));?>
					            <?php echo $this->admin->th('lista_espera', 'Interesados', true, array('text-align' => 'center'));?>
                      <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
                    </tr>
                  </thead>
                  <tbody id="">
                    <?php foreach ($data->result() as $row): ?>
                    <tr class="<?php echo alternator('odd', 'even');?>">
                      <td>
                        <a href="<?=site_url($row->slug);?>" target="_blank" title="Abrir en el sitio"><?=$row->codigo;?> <i class="glyphicon glyphicon-new-window"></i></a>
                        <? if(@$row->alerta_cupos_revisar){ ?>
                          <button style="margin-left:10px;" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="El paquete tiene cupos pendiente de revisión"><i class="glyphicon glyphicon-bell"></i></button>
                        <? } ?>
                      </td>
                      <?php echo $this->admin->td('<a href="'.site_url($row->slug).'" target="_blank" title="Abrir en el sitio">'.$row->nombre.'</a><br/><small>'.$row->destino.'</small>');?>
                      <?php echo $this->admin->td(formato_fecha($row->fecha_inicio));?>
                      <?php echo $this->admin->td(formato_fecha($row->fecha_fin));?>
                      <?php echo $this->admin->td(($row->precio_usd?'USD':'ARS').' '.number_format($row->v_total,2,',','.'));?>
                      <td class="text-center"><?=$row->activo?'<i class="glyphicon glyphicon-ok-sign" style="color:green;font-size:14px;"></i>':'<i class="glyphicon glyphicon-minus-sign" style="color:red;font-size:14px;"></i>';?></td>
                      <td class="text-center">
                        <?=$row->lista_espera;?> <? if($row->lista_espera>0): ?><a href="<?=$route.'/exportar_lista_espera/'.$row->id;?>" title="Descargar Listado"><i class="glyphicon glyphicon-download-alt"></i></a><? endif; ?>
                      </td>
                      <td>
                        <input type="hidden" name="id[]" value="<?=$row->id;?>" />
                        <? if (perfil() == 'ADM'): ?>
                        <a href="<?php echo $route;?>/edit/<?php echo $row->id;?>" class="icon-edit">&nbsp;Editar</a>
                        <? else: ?>                          
                        <a data-paquete="<?php echo $row->id;?>" href="<?php echo $route;?>/duplicar/<?php echo $row->id;?>" class="fa fa-files-o lnk-duplicar">&nbsp;Duplicar</a><br>
                        <a href="<?php echo $route;?>/edit/<?php echo $row->id;?>" class="icon-edit">&nbsp;Editar</a> | 
                        <a href="<?php echo $route;?>/delete/<?php echo $row->id;?>" class="icon-remove delete">&nbsp;Borrar</a>
                        <? endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($data->result()) == 0): ?>
                    <tr>
                      <td colspan="9" align="center" style="padding:30px 0;">
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
                      <? if (perfil() != 'ADM'): ?>
                      <a class="btn btn-primary add-paquete" href="#" data-href="<?php echo $route;?>/quickadd">Agregar Nuevo</a>
                      <? endif; ?>
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
    
<script>
$(document).ready(function(){
    
    if($('[data-toggle="tooltip"]').length){
      $('[data-toggle="tooltip"]').tooltip();
    }

		$("body").on('click','a.lnk-duplicar',function(e) {
			e.preventDefault();
      var url = $(this).attr('href');
			var paquete = $(this).attr('data-paquete');
			bootbox.confirm("Esta seguro que deseas duplicar el paquete?", function(result){
				if (result) {
          $.post('<?=base_url();?>admin/paquetes/validar_codigo/'+paquete,function(data){
            if(data.status == 'ok'){
              window.location = url;
            }
            else{
              bootbox.alert("El destino asociado al paquete no tiene Código creado.<br>Por favor, verificar en el catálogo de Destinos.");
              return false;
            }
          },'json');
					
				}
			});
		});
		
		$("body").on('click','a.add-paquete',function(e) {
			e.preventDefault();
			var url = $(this).attr('data-href');
			$.post(url,function(data){
				if(data.view){
					var dialog = bootbox.dialog({
						title: 'Agregar nuevo paquete',
						message: data.view,
						buttons: {
							cancel: {
								label: "Cancelar",
								className: 'btn-danger',
								callback: function(){
								}
							},
							ok: {
								label: "Crear",
								className: 'btn-success',
								callback: function(){
									var furl = $('#fPaqueteAdd').attr('action');
									$('#fPaqueteAdd #errors').hide();
									$.post(furl,$('#fPaqueteAdd').serialize(),function(d){
										if (d.success) {
											location.href = d.redirect;
										}
										else {
											$('#fPaqueteAdd #errors .alert').text(d.error);
											$('#fPaqueteAdd #errors').show();
										}
									},'json');
									
									return false;
								}
							}
						}
					});
				}
			},"json");
		});
		
});
</script>

<?php echo $footer;?>