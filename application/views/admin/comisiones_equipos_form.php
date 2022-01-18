<?php echo $header;?>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
    </div>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    <div class="col-md-12"> 
              <div class="widget box">
                <div class="widget-header">
                  <h3 style="margin-bottom:20px;">Propiedades</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 

                      <?php echo $this->admin->input('nombre', 'Nombre del equipo', '', $row, false); ?>

                      <?php echo $this->admin->combo('coordinador_id', 'Lider', 's2', $row, $vendedores_ext, 'id', 'nombreCompleto', false); ?>

                      <?php echo $this->admin->combo('gerente_id', 'Gerente', 's2', $row, $vendedores_ext, 'id', 'nombreCompleto', false); ?>

                    </div>
                  </div>
                </div>
                <? if (isset($row->id)): ?>
                <div class="widget-header" style="border-top:solid 1px #CCC;">
                  <h3 class="pull-left" style="margin-bottom:20px;">Miembros</h3>
                  <div class="pull-left" style="padding-top:10px; padding-left:20px;">
                    <select size="1" id="miembro" class="s2">
                      <option value=""></option>
                      <? foreach ($vendedores_ext as $vendedor): ?>
                      <option value="<?=$vendedor->id;?>"><?=$vendedor->nombreCompleto;?></option>
                      <? endforeach; ?>                    
                    </select>
                    <button id="btnAgregarMiembro" type="button" class="btn btn-primary">Agregar</button>
                  </div>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12" id="miembros_tabla"> 
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Eliminar</th>
                          </tr>
                        </thead>
                        <tbody>
                          <? if (count($miembros)): ?>
                          <? foreach ($miembros as $miembro): ?>
                          <tr>
                            <td><?=$miembro->nombre;?></td>
                            <td><?=$miembro->apellido;?></td>
                            <td><button type="button" class="btnEliminar btn btn-danger" data-ref="<?=$miembro->id;?>">Eliminar</button></td>
                          </tr>
                          <? endforeach; ?>
                          <? else: ?>
                          <tr id="empty" style="padding:25px 0;">
                            <td colspan="3">No hay miembros en este equipo todavía.</td>
                          </tr>
                          <? endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>               
                <div class="form-actions">
                  <input type="hidden" id="id" name="id" value="<?=@$row->id;?>" />
                  <input type="submit" value="Grabar" class="btn btn-primary">
                  <a href="<?=site_url('admin/comisiones_equipos');?>" class="btn btn-default">Volver</a>
                </div>
                <? else: ?>
                <div class="form-actions">
                  <input type="hidden" id="id" name="id" value="" />
                  <input type="submit" value="Continuar" class="btn btn-primary">
                  <a href="<?=site_url('admin/comisiones_equipos');?>" class="btn btn-default">Volver</a>
                </div>
                <? endif; ?>
              </div>
            
    </div>
  </div>

  </form>
    
<script>
  $('#btnAgregarMiembro').click(function(){
    if ($('#miembro').val() == '') {
      bootbox.alert('Por favor elegi un vendedor');
      return false;
    }

    $.post('<?=site_url('admin/comisiones_equipos/agregar_miembro');?>', { equipo: $('#id').val(), vendedor: $('#miembro').val() }, function(result){
      if (result.success) {
        $('#miembros_tabla').html(result.html);
      }
      else {
        bootbox.alert(result.error);
      }
    }, "json");
  });

  $('body').on('click', '.btnEliminar', function(){
    $.post('<?=site_url('admin/comisiones_equipos/quitar_miembro');?>', { id: $(this).data('ref'), equipo_id: $('#id').val() }, function(result){
      if (result.success) {
        $('#miembros_tabla').html(result.html);
      }
      else {
        bootbox.alert(result.error);
      }
    }, "json");
  });

  $('#formEdit').on('submit', function(){
    if ($(this).hasClass('validado')) {
      return true;
    }

    $.post('<?=site_url('admin/comisiones_equipos/validar');?>', $(this).serialize(), function(result){
      if (result.success) {
        $('#formEdit').addClass('validado');
        $('#formEdit').submit();
      }
      else {
        bootbox.alert('<h3 style="margin:0 0 15px">No fue posible grabar, se detectaron errores:</h3><div class="alert alert-danger">' + result.error + '</div>');
      }
    }, "json");

    return false;
  }); 
</script>

<?php echo $footer; ?>