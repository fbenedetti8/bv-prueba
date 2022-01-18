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
            <span>Los widgets son bloques de contenido que se pueden incluir en la página principal.</span>
          </div>
          
          <form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:300px">
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
                      <?php echo $this->admin->th('nombre', 'Nombre', true);?>
                      <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($data->result() as $row): ?>
                    <tr class="<?php echo alternator('odd', 'even');?>">
                      <?php echo $this->admin->td($row->nombre);?>
                      <td>
                        <input type="hidden" name="id[]" value="<?=$row->id;?>" />
                        <a href="<?php echo $route;?>/edit/<?php echo $row->id;?>" class="icon-edit">&nbsp;Editar</a> | 
                        <a href="<?php echo $route;?>/delete/<?php echo $row->id;?>" class="icon-remove delete">&nbsp;Borrar</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($data->result()) == 0): ?>
                    <tr>
                      <td colspan="2" align="center" style="padding:30px 0;">
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
					  <a class="btn btn-primary add-alojamiento" href="#" data-href="<?php echo $route;?>/quickadd">Agregar Nuevo</a>
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
  
		$("body").on('click','a.add-alojamiento',function(e) {
			e.preventDefault();
			var url = $(this).attr('data-href');
			$.post(url,function(data){
				if(data.view){
					var dialog = bootbox.dialog({
						title: 'Agregar nuevo alojamiento',
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
									var furl = $('#fAlojamientoAdd').attr('action');
									$.post(furl,$('#fAlojamientoAdd').serialize(),function(d){
										if(d.redirect){
											location.href = d.redirect;
										}
									},'json');
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