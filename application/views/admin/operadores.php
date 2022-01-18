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
            <span>Listado de operadores.</span>
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
                      <?php echo $this->admin->th('razonsocial', 'Razón social', true);?>
                      <?php echo $this->admin->th('cuit', 'CUIT', true);?>
                      <?php echo $this->admin->th('legajo', 'Legajo', true);?>
                      <?php echo $this->admin->th('saldoAPagar', 'Saldo en ARS', true, array('text-align'=>'center'));?>
                      <?php echo $this->admin->th('saldoAPagar_usd', 'Saldo en USD', true, array('text-align'=>'center'));?>
                      <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'150px'));?>
                    </tr>
                  </thead>
                  <tbody id="sortable">
                    <?php foreach ($data->result() as $row): ?>
                    <tr class="<?php echo alternator('odd', 'even');?>">
                      <?php echo $this->admin->td($row->nombre);?>
                      <?php echo $this->admin->td($row->razonsocial);?>
                      <?php echo $this->admin->td($row->cuit);?>
                      <?php echo $this->admin->td($row->legajo);?>
                      <td align="center">
						<? if(@$row->id != 1): 
							echo 'ARS '.number_format(@$row->saldoAPagar?$row->saldoAPagar:'0.00',2,',','.');
						endif; ?>
					  </td>
                      <td align="center">
						<? if(@$row->id != 1): 
							echo 'USD '.number_format(@$row->saldoAPagar_usd?$row->saldoAPagar_usd:'0.00',2,',','.');
						endif; ?>
					  </td>
                      <td>
						<? if(@$row->id != 1): //solo lo muestro para los q no sean Buenas Vibras ?>
						<a class="btn btn-sm" style="margin-bottom:5px;" href="<?=$route;?>/paquetes/<?=$row->id;?>"><i class="glyphicon glyphicon-list" style="font-size:12px;"></i> Ver Paquetes</a>
						
						<div class="btn-group">
						  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Opciones <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" style="right:0;left:inherit;text-align:right;">
							<li><a href="<?=$route;?>/edit/<?=$row->id;?>" class="edit">Editar</a></li>
							<li><a href="<?=$route;?>/delete/<?=$row->id;?>" class="delete">Borrar</a></li>
						  </ul>
						</div>
						<? endif; ?>
						
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($data->result()) == 0): ?>
                    <tr>
                      <td colspan="7" align="center" style="padding:30px 0;">
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