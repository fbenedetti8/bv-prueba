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
            <span>Los teléfonos de contacto son aquellos que se pueden asociar a los diferentes países y que estarán visibles en el header del sitio.</span>
          </div>
          
          <form id="frmSearch" method="post" action="<?php echo $route;?>" class="form-horizontal page-stats" style="width:300px; padding: 25px 0 0;">
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
              <label class="col-md-2 control-label">País:</label>
              <div class="input-group col-md-10"> 
                <select name="id_pais" class="form-control" onchange="javascript: location.href = '<?=$route;?>?id_pais='+this.value;">
                  <option value="">Todos</option>
                  <? foreach($paises as $d): ?>
                  <option value="<?=$d->id;?>" <?=@$id_pais==$d->id?'selected':'';?>><?=$d->nombre;?></option>
                  <? endforeach; ?>
                </select>
              </div>
            </div>
          </form>         
        </div>
        <!-- /Page Header -->

        <div class="alert alert-info">
          <b>Orden y agrupación en frontend</b>: El listado de números de teléfono visibles en frontend será de 1 por país en forma aleatoria y cada uno tomará el número de orden propio. El criterio de ordenamiento es por número de orden ascendente.
        </div>

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
                      <?php echo $this->admin->th('telefono', 'Teléfono', true);?>
                      <?php echo $this->admin->th('pais', 'País', true);?>
                      <?php echo $this->admin->th_orden('orden', 'Orden', $sort=true, $route.'/ordenar', $css="", $formId="formList");?>
                    <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
                    </tr>
                  </thead>
                  <tbody id="sortable">
                    <?php foreach ($data->result() as $row): ?>
                    <tr class="<?php echo alternator('odd', 'even');?>">
                      <?php echo $this->admin->td($row->nombre);?>
                      <?php echo $this->admin->td($row->telefono);?>
                      <?php echo $this->admin->td($row->pais);?>
                      <?php echo $this->admin->td_input('orden', $row->orden, '');?>
                      <td>
                      <input type="hidden" name="id[]" value="<?=$row->id;?>" />
                        <a href="<?php echo $route;?>/edit/<?php echo $row->id;?>" class="icon-edit">&nbsp;Editar</a> | 
                        <a href="<?php echo $route;?>/delete/<?php echo $row->id;?>" class="icon-remove delete">&nbsp;Borrar</a>
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
                      <a class="btn btn-primary" href="<?php echo $route;?>/add">Agregar Nuevo</a>
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