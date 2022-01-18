<?php echo $header;?>

<style>
.seleccion .col-md-3 { width:100%; text-align:left; }
.seleccion .col-md-9 { width:100%; text-align:left; margin-top:5px; }
.seleccion .col-md-9 span.select2 { width:100% !important; }
.btn-add-fecha { margin-top: 35px; }

#tblFechas .tr_row label {     
	width: 90%;
    text-align: left;
    padding-left: 0; 
}
#tblFechas .tr_row .col-md-9 { width:90%;    padding: 0; }
#tblFechas .tr_row .form-group:first-child { padding-top: 15px; }
#tblFechas .tr_row td { padding:0; }
</style>

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
	
		<!-- Tabs-->
		<div class="tabbable tabbable-custom tabs-left">
			<ul class="nav nav-tabs tabs-left">
				<li class="active"><a href="#basicos" data-toggle="tab">Datos generales</a></li>
				<li><a href="#fechas" data-toggle="tab">Fechas</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="basicos">
				
					  <div class="widget box">
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Propiedades</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <?php echo $this->admin->input('nombre', 'Nombre del transporte', '', $row, false); ?>
							  <?php echo $this->admin->input('titulo', 'Título del transporte', '', $row, false); ?>
							  <?php echo $this->admin->textarea('descripcion', 'Descripción', '', $row, false); ?>
							  <?php echo $this->admin->combo('destino_id[]', 'Destinos asociados', 's2 comboDestinos', $row, $destinos, 'id', 'nombre',false,true); ?>
							  <?php echo $this->admin->combo('tipo_id', 'Tipo de transporte', 's2', $row, $tipos, 'id', 'nombre',false); ?>
							  <?php //echo $this->admin->input('cupo_transporte', 'Cupo total', '', $row, false); ?>
							</div>
						  </div>
						</div>
					  </div>
					 
				 </div>
				 
				<div class="tab-pane" id="fechas">
				
					  <div class="widget box">
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Fechas al Transporte</h3>
						</div>
						<div class="widget-content">
						  <div class="row row-picker">
							<div class="col-md-4 col-lg-3 seleccion"> 
								<?php echo $this->admin->datepicker('f_fecha_salida', 'Fecha Salida', '', '', true, false, false); ?>
							</div>
							<div class="col-md-4 col-lg-3 seleccion "> 
								<?php echo $this->admin->datepicker('f_fecha_regreso', 'Fecha Regreso', '', '', true, false, false); ?>
							</div>
						  </div>
						  <div class="row">
							<div class="col-md-4 col-lg-3 seleccion vuelo_ida"> 
								<?php echo $this->admin->input('f_vuelo_ida', 'Vuelo Ida N°', '', $row, false); ?>
							</div>
							<div class="col-md-4 col-lg-3 seleccion vuelo_regreso"> 
								<?php echo $this->admin->input('f_vuelo_regreso', 'Vuelo Regreso N°', '', $row, false); ?>
							</div>
						  </div>
						  <div class="row">
							<div class="col-md-4 col-lg-3 seleccion vuelo_aeropuerto"> 
								<?php echo $this->admin->input('f_vuelo_aeropuerto', 'Aeropuerto Destino', '', $row, false); ?>
							</div>
							<div class="col-md-2 col-lg-3 seleccion "> 
								<?php echo $this->admin->input('f_cupo_total', 'Cupo', '', $row, false); ?>
							</div>
							<div class="col-md-4 col-lg-3 seleccion "> 
								<?php echo $this->admin->datepicker('f_fecha_vencimiento', 'Fecha Vencimiento', '', '', true, false, false); ?>
							</div>
							<div class="col-md-2 col-lg-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-fecha" style="margin-left: 10px;">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped">
								  <!--
								  <thead>
									<tr>
									  <?php echo $this->admin->th('fecha_salida', 'Fecha Salida', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('vuelo_ida', 'Vuelo Ida N°', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('vuelo_aeropuerto', 'Aeropuerto Destino', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('fecha_regreso', 'Fecha Regreso', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('vuelo_regreso', 'Vuelo Regreso N°', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('cupo_total', 'Cupo', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('fecha_vencimiento', 'Fecha Vencimiento', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'110px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  -->
								  <tbody id="tblFechas">
									<?php foreach ($mis_fechas as $r): ?>
										<?=fechas_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_fechas) == 0): ?>
									<tr>
									  <td colspan="8" align="center" style="padding:30px 0;">
										No hay asociaciones para este transporte.
									  </td>
									</tr>
									<?php endif; ?>
								  </tbody>
								</table>
							</div>
						  </div>
						</div>
						
					  </div>
					 
				 </div>
			 </div>
		 </div>
            
			
			<div class="form-actions">
			  <input type="hidden" name="id" value="<?=@$row->id;?>" />
			  <input type="submit" value="Grabar" class="btn btn-primary">
			  <input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
			</div>
						
    </div>
  </div>

  </form>

<script src="<?=base_url();?>media/admin/ckeditor/ckeditor.js?v=2"></script>


    
<?php echo $footer; ?>

<script>
$(document).ready(function(){	
  CKEDITOR.config.height = '100px';
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.language = 'es';
  CKEDITOR.config.removePlugins = 'elementspath';
  CKEDITOR.config.resize_enabled = false;
  CKEDITOR.config.toolbar = [
      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'TextColor', '-', 'RemoveFormat' ] },
  ];
  CKEDITOR.replace( 'descripcion' );
  
  $('.comboDestinos').val([<?=implode(',',$destinos_asociados);?>]).trigger("change");
  
  
   //agrega nuevo fecha en tabla
  $('body').on('click','.btn-add-fecha', function(e){
	  e.preventDefault();
	  
	  if($('#f_fecha_salida').val() == '' || $('#f_fecha_regreso').val() == '' || $('#f_cupo_total').val() == ''){
		  bootbox.alert('Debes seleccionar las fecha de salida, fecha de regreso y el cupo.');
		  return false;
	  }

	  if($('#f_fecha_vencimiento').val() == ''){
		  bootbox.alert('Debes seleccionar la fecha de vencimiento.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_fecha";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblFechas').prepend(data.row);

			  bootbox.alert('Fechas asociadas correctamente.');
		  }
	  },'json');
  });
  
  //update fecha 
  $('body').on('click','.btn-update-fecha', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data){
			  bootbox.alert('Registro actualizado correctamente');
			  return false;
		  }
	  });
  });
  
  //borrar fecha 
  $('body').on('click','.btn-delete-fecha', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	  bootbox.confirm("Esta seguro que desea borrar esta asociación?", function(result){
		if (result) {
			$.post(url,function(data){
				  if(data){
					me.closest('tr').slideUp();
				  }
				  else {
				  	bootbox.alert('No es posible borrar esta asociación porque tiene reservas y/o ordenes de reserva.');
				  }
			  });
		}
	});
  });
  
  $( "body" ).on('change','#tipo_id', function() {
		var me = $(this);
		var tipo_id = me.val();
		show_hide_tipo(tipo_id);
	});
  

  //fecha regreso y salida, minimo, hoy
  //$('#f_fecha_regreso').pickadate('picker').set('min',true);
  //$('#f_fecha_salida').pickadate('picker').set('min',true);
  $('#f_fecha_regreso').datepicker('setStartDate','<?=date("d/m/Y")?>')
  $('#f_fecha_salida').datepicker('setStartDate','<?=date("d/m/Y")?>')
  
  //al cambiar, actualizar los mi y max
  //$('.datepicker-fullscreen').on('change', function () {
  $('.row-picker .datepicker').on('change', function () {
	  console.log($(this).val());
		if ($(this).attr('id') === 'f_fecha_salida') {
			var arr = $(this).val();
			//arr = arr.split('/');
			//$('#f_fecha_regreso').pickadate('picker').set('min',[arr[2],arr[1]-1,arr[0]]);
			$('#f_fecha_regreso').datepicker('setStartDate',arr);
		}
		if ($(this).attr('id') === 'f_fecha_regreso') {
			var arr = $(this).val();
			//arr = arr.split('/');
			console.log(arr);
			//$('#f_fecha_salida').pickadate('picker').set('max',[arr[2],arr[1]-1,arr[0]]);
			$('#f_fecha_salida').datepicker('setEndDate',arr);
		}
	});
	  
  
  show_hide_tipo($('#tipo_id').val());
});

function show_hide_tipo(tipo_id){
	if(tipo_id == 1){
		//muestro numero de vuelo ida y regreso
		$('.vuelo_aeropuerto').show();
		$('.vuelo_ida').show();
		$('.vuelo_regreso').show();
	}
	else{
		//oculto numero de vuelo ida y regreso
		$('.vuelo_aeropuerto').hide();
		$('.vuelo_ida').hide();
		$('.vuelo_regreso').hide();
	}			
}
</script>  