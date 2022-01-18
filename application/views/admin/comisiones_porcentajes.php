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
            <!-- <span>Los widgets son bloques de contenido que se pueden incluir en la página principal.</span> -->
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
                <h4><i class="icon-user"></i> Ingresa según el tipo de rol de usuario.</h4>
              </div>
              <div class="widget-content align-center">

                <div class="row">
                  
                  <div class="col-md-4">
                    <a href="<?=base_url();?>admin/comisiones_porcentajes/edit/VEN" class="btn btn-icon input-block-level">
                      <i class="icon-group"></i>
                      <div>Vendedor</div>
                      <!-- <span class="label label-danger">2</span> -->
                    </a>
                  </div>
                  <div class="col-md-4">
                    <a href="<?=base_url();?>admin/comisiones_porcentajes/edit/LID" class="btn btn-icon input-block-level">
                      <i class="icon-group"></i>
                      <div>Lider</div>
                      <!-- <span class="label label-danger">2</span> -->
                    </a>
                  </div>
                  <div class="col-md-4">
                    <a href="<?=base_url();?>admin/comisiones_porcentajes/edit/GER" class="btn btn-icon input-block-level">
                      <i class="icon-group"></i>
                      <div>Gerente</div>
                      <!-- <span class="label label-danger">2</span> -->
                    </a>
                  </div>
                
                </div>

              </div>
            </div>

          
          </div> <!-- /.col-md-12 -->
          <!-- /Example Box -->
        </div> <!-- /.row -->
        <!-- /Page Content -->

<?php echo $footer;?>