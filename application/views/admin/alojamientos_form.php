<?php echo $header;?>

<style>
.seleccion .col-md-3 { width:100%; text-align:left; }
.seleccion .col-md-9 { width:100%; text-align:left; margin-top:5px; }
.seleccion .col-md-9 span.select2 { width:100% !important; }
.btn-add-fecha { margin-top: 35px; }
.btn-add-habitacion { margin-top: 35px; }

	.tr_row input[type="text"] { width:70px; }
	.tr_row .form-group label { display:none; }
	.tr_row .form-group .col-md-9 {     margin: 0 auto; float: none; }
.datepicker.dropdown-menu { z-index: 10000 !important; }
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
				<li><a id="lnkFechas" href="#fechas" data-toggle="tab">Fechas</a></li>
				<li class="itemHabs"><a href="#habitaciones" data-toggle="tab">Habitaciones</a></li>
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
							  <?php echo $this->admin->input('nombre', 'Nombre de alojamiento', '', $row, false); ?>
							  <?php echo $this->admin->input('link', 'Página web', '', $row, false); ?>
							  <?php echo $this->admin->textarea('introduccion', 'Introducción', '', $row, false); ?>
							  <?php echo $this->admin->textarea('descripcion', 'Descripción', '', $row, false); ?>
							  <?php echo $this->admin->combo('destino_id[]', 'Destinos asociados', 's2 comboDestinos', $row, $destinos, 'id', 'nombre',false,true); ?>
							  <?php //echo $this->admin->input('cupo_alojamiento', 'Cupo total', '', $row, false); ?>
							</div>
						  </div>
						</div>
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Servicios</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12"> 
							  <? foreach ($servicios as $servicio): ?>
							  <label class="col-sm-4" style="margin:10px 0;">
								<input type="checkbox" name="servicios[]" value="<?=$servicio->id;?>" <?=in_array($servicio->id, $mis_servicios) ? 'checked' : '';?> />
								&nbsp;&nbsp;<?=$servicio->nombre;?>
							  </label>
							  <? endforeach; ?>
							</div>
						  </div>
						</div>
					  </div>
				  
				</div>
            
				<div class="tab-pane" id="fechas">
				
					  <div class="widget box">
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Fechas al Alojamiento</h3>
						</div>
						<div class="widget-content">
						  <div class="row row-picker">
							<div class="col-md-3 col-lg-3 seleccion"> 
								<?php echo $this->admin->datepicker('fecha_checkin', 'Fecha Checkin', '', '', true, false, false); ?>
							</div>
							<div class="col-md-3 col-lg-3 seleccion"> 
								<?php echo $this->admin->datepicker('fecha_checkout', 'Fecha Checkout', '', '', true, false, false); ?>
							</div>
							<div class="col-md-3 col-lg-3 seleccion"> 
								<?php echo $this->admin->input('descripcion_fecha', 'Descripción', '', $row, false); ?>
							</div>
							<div class="col-md-2 col-lg-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-fecha">
							</div>
					  	  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <?php echo $this->admin->th('fecha_checkin', 'Fecha Checkin', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('fecha_checkout', 'Fecha Checkout', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('descripcion', 'Descripción', false, array('text-align'=>'center', 'width'=>'200px'));?>
									  <?php echo $this->admin->th('', 'Habitaciones', false, array('text-align'=>'center'));?>
								  	  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'95px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblFechas">
									<?php foreach ($mis_fechas as $r): ?>
										<?=fechas_alojamiento_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_fechas) == 0): ?>
									<tr>
									  <td colspan="4" align="center" style="padding:30px 0;">
										No hay asociaciones de fechas para este alojamiento.
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
				 
				 <div class="tab-pane" id="habitaciones">
				
					  <div class="widget box">
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociar Habitaciones al Alojamiento</h3>
						</div>
						<div class="widget-content">
					   	  <div class="row">
							<div class="col-md-5 col-lg-3 seleccion"> 
								<?php echo $this->admin->combo('fecha_id', 'Fecha', 's2', '', $mis_fechas, 'id', 'fecha'); ?>
							</div>
							<div class="col-md-5 col-lg-3 seleccion"> 
								<div class="form-group">
									<label class="col-md-3 control-label">Tipo de Habitación:  </label>
									<div class="col-md-9 clearfix">
										<select id="habitacion_id" name="habitacion_id" class="col-md-12 form-control select2 full-width-fix s2 ">
											<option>&nbsp;</option>
											<? foreach($habitaciones as $h): ?>
												<option value="<?=$h->id;?>" rel="<?=$h->pax;?>"><?=$h->nombre;?></option>
											<? endforeach; ?>
										</select>										
									</div>
								</div>
							</div>
							<div class="col-md-3 col-lg-offset-0 col-lg-2 seleccion vuelo_regreso ocultar_compartida"> 
								<?php echo $this->admin->input('cantidad', 'Cantidad', 'onlynum', $row, false); ?>
							</div>
							<div class="col-md-3 col-lg-2 seleccion vuelo_regreso ocultar_compartida"> 
								<?php echo $this->admin->input('cupo_total', 'Cupo total', '', $row, false); ?>
							</div>
							<div class="col-md-2 col-lg-2 seleccion"> 
								<input type="button" value="Agregar" class="btn btn-sm btn-success btn-add-habitacion">
							</div>
						  </div>
					    </div>
						
						<div class="widget-header">
						  <h3 style="margin-bottom:20px;">Asociaciones realizadas</h3>
						</div>
						<div class="widget-content">
						  <div class="row">
							<div class="col-md-12">
								<table id="tblHabs" cellpadding="0" cellspacing="0" border="0" class="table table-hover ">
								  <thead>
									<tr>
									  <!-- <?php echo $this->admin->th('paquetes', 'Paquetes', false, array('text-align'=>'center'));?> -->
									  <?php echo $this->admin->th('fecha', 'Fecha', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('habitacion', 'Tipo Habitación', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('cantidad', 'Cantidad', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('cupo_total', 'Cupo', false, array('text-align'=>'center'));?>
									  <?php echo $this->admin->th('opciones', 'Opciones', false, array('width'=>'110px','text-align'=>'center'));?>
									</tr>
								  </thead>
								  <tbody id="tblCupos">
									<?php foreach ($mis_fechas_cupo as $r): ?>
										<?=fechas_alojamiento_cupo_row($r);?>
									<?php endforeach; ?>
									<?php if (count($mis_fechas_cupo) == 0): ?>
									<tr>
									  <td colspan="5" align="center" style="padding:30px 0;">
										No hay asociaciones para este alojamiento.
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

	<? if(isset($_GET['tab']) && $_GET['tab'] == 'fechas'): ?>
		$('#lnkFechas').trigger('click');
	<? endif; ?>

	//fecha regreso y salida, minimo, hoy
  //$('#fecha_checkin').pickadate('picker').set('min',true);
  //$('#fecha_checkout').pickadate('picker').set('min',true);
  $('#fecha_checkin').datepicker('setStartDate','<?=date("d/m/Y")?>')
  $('#fecha_checkout').datepicker('setStartDate','<?=date("d/m/Y")?>')
  
  //al cambiar, actualizar los mi y max
  //$('.datepicker-fullscreen').on('change', function () {
  $('.row-picker .datepicker').on('change', function () {
	  console.log($(this).val());
		if ($(this).attr('id') === 'fecha_checkin') {
			var arr = $(this).val();
			//arr = arr.split('/');
			//$('#fecha_checkout').pickadate('picker').set('min',[arr[2],arr[1]-1,arr[0]]);
			$('#fecha_checkout').datepicker('setStartDate',arr);
		}
		if ($(this).attr('id') === 'fecha_checkout') {
			var arr = $(this).val();
			//arr = arr.split('/');
			console.log(arr);
			//$('#fecha_checkin').pickadate('picker').set('max',[arr[2],arr[1]-1,arr[0]]);
			$('#fecha_checkin').datepicker('setEndDate',arr);
		}
	});
	
	
	//solo lectura el cupo total
	$('#cupo_total').attr('readonly','readonly');
	
  CKEDITOR.config.height = '100px';
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.language = 'es';
  CKEDITOR.config.removePlugins = 'elementspath';
  CKEDITOR.config.resize_enabled = false;
  CKEDITOR.config.toolbar = [
      { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Link', '-', 'RemoveFormat' ] },
  ];
  CKEDITOR.replace( 'descripcion' );
  
  $('.comboDestinos').val([<?=implode(',',$mis_destinos);?>]).trigger("change");
  
  
   //agrega nuevo fecha en tabla
  $('body').on('click','.btn-add-fecha', function(e){
	  e.preventDefault();
	  
	  if($('#fecha_checkin').val() == '' || $('#fecha_checkout').val() == ''){
		  bootbox.alert('Debes seleccionar las fechas de checkin y checkout.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_fecha";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if(data.row){
			  $('#tblFechas').prepend(data.row);
		  }
		  
		  //cargo dropdown de dechas en tab habitaciones
		  //fecha_id
		  if(data.options){
			  $('#fecha_id').select2('destroy');
			  $('#fecha_id').html('');
			  $('#fecha_id').select2();
			  
			  $.each(data.options,function(i,el){
				  var newOption = new Option(el.fecha, el.id, false, false);
				  $('#fecha_id').append(newOption).trigger('change');
			  });
			  
		  }

		  //$('#fecha_checkin').val('');
		  //$('#fecha_checkout').val('');
		  bootbox.alert('Fechas asociadas correctamente.');
	  },'json');
  });
  
  //update habitacion 
  $('body').on('click','.btn-update-habitacion', function(e){
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
  
  //duplicar fecha 
  $('body').on('click','.btn-duplicar-fecha', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	  var rel = me.attr('data-rel');
	  var txt = $('#fecha_id option[value="'+rel+'"]').text();

		var dialog = bootbox.dialog({
			title: 'Duplicación de fecha',
			message: 'Vas a duplicar los cupos de habitaciones de la fecha <b>'+txt+'</b>.'
						+'<br>Elige las nuevas fechas de Checkin y Checkout.<br><br>'
						+'<form id="formDuplicar" class="bootbox-form">'
								+'<input type="hidden" name="fecha_id" value="'+rel+'"/>'
								+'<div class="form-group"><label for="f_checkin" style="width: 15%;display: inline-block;">Checkin: </label><input type="text" id="f_checkin" name="f_checkin" class="form-control datepicker required" value="" readonly="readonly" style="width: 75%;display: inline-block;"></div>'
								+'<div class="form-group"><label for="f_checkout" style="width: 15%;display: inline-block;">Checkout: </label><input type="text" id="f_checkout" name="f_checkout" class="form-control datepicker required" value="" readonly="readonly" style="width: 75%;display: inline-block;"></div>'
								+'</form>'
							+'</div><script>$("#f_checkin.datepicker").datepicker({language: "es",format:"dd/mm/yyyy",startDate:new Date()});$("#f_checkout.datepicker").datepicker({language: "es",format:"dd/mm/yyyy",startDate:new Date()});$("#f_checkin.datepicker").on("changeDate",function(e){'
								+'var start = $("#f_checkin.datepicker").datepicker("getDate");console.log(start);'
								+'$("#f_checkout.datepicker").datepicker("setStartDate",start);'
								+'$("#f_checkin.datepicker").datepicker("hide");'
								+'$("#f_checkout.datepicker").datepicker("show").focus();'
						    +'});<\/script>',
			buttons: {
			    cancel: {
			        label: "Cancelar",
			        className: 'btn-primary'
			    },
			    noclose: {
			        label: "Duplicar",
			        className: 'btn-success',
			        callback: function(){
			        	if($('#f_checkin').val() == '' || $('#f_checkout').val() == ''){
			        		bootbox.alert('Debes completar la fecha de checkin y checkout.');
						  	return false;		
			        	}
			        	else{
				            $.post(url,$('#formDuplicar').serialize(),function(data){
								if(data.status == 'OK'){
									bootbox.alert('La duplicación de la fecha se ha realizado correctamente', function(){
										location.href = '<?=site_url("admin/alojamientos/edit/".$row->id."?tab=fechas");?>';
									});
								}
								else {
								  	
								}
							},'json');	
			        	}
			        }
			    }
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
  
   //agrega nuevo cupo de alojamiento para fecha en tabla
  $('body').on('click','.btn-add-habitacion', function(e){
	  e.preventDefault();
	  
	  if($('#fecha_id').val() == '' || $('#habitacion_id').val() == '' 
			|| $('#cantidad').val() == '' || $('#cupo_total').val() == ''){
		  bootbox.alert('Debes seleccionar todos los campos.');
		  return false;
	  }
	  
	  var url = "<?=$route;?>/agregar_habitacion";
	  $.post(url,$('#formEdit').serialize(),function(data){
		  if (data.success) {
			  $('#tblCupos').prepend(data.row);
		  }
		  else {
		  	  bootbox.alert('No puedes volver a agregar esta habitación.');
		  }
	  },'json');
  });
  
  //borrar fecha 
  $('body').on('click','.btn-delete-habitacion', function(e){
	  e.preventDefault();
	  var me = $(this);
	  var url = me.attr('data-href');
	   bootbox.confirm("Esta seguro que desea borrar esta asociación?", function(result){
		if (result) {
			$.post(url,function(data){
				  if(data){
					  me.closest('tr').slideUp();
				  }
			});
		}
	  });
  });
  
  //habitacion_id
  $('body').on('change','#habitacion_id', function(e){
	  update_cupo_total();
  });
  
  //cantidad
  $('body').on('change','#cantidad', function(e){
	  update_cupo_total();
  });
  
  //fecha_id
  $('body').on('change','#fecha_id', function(e){
	  var me = $(this);
	  show_hide_fechas(me.val());
  });
  
  //eleccion de fecha para ver habitaciones
  //agrega nuevo fecha en tabla
  $('body').on('click','.lnkVerHabs', function(e){
	  e.preventDefault();
	  
	  var valor = $(this).attr('rel');
	  if(valor){
	  	//preselecciono fecha elegida
    	$('#fecha_id').val(valor).trigger('change');
    	show_hide_fechas(valor);
	  }
	});

  $('.descripcion_fechas').change(function(){
  	$.fancybox.showLoading();
  	$.post('<?=site_url('admin/alojamientos/grabar_descripcion_fecha');?>/' + $(this).data('ref'), { descripcion: $(this).val() }, function(){
  		$.fancybox.hideLoading();
  	});
  });

});

function show_hide_fechas(valor){
	//habilito tab 
    $('#habitaciones').tab('show');
    $('.nav.nav-tabs.tabs-left li').removeClass('active');
    $('.nav.nav-tabs.tabs-left li.itemHabs').addClass('active');

    //muestro y oculto filas
    $('#tblHabs .tr_row').hide();
    $('#tblHabs .cupo_row_'+valor).show();
}

function update_cupo_total(){
	var opt = $('#habitacion_id').find('option:selected');
	
	$('#cantidad').removeAttr('readonly');
	$('#cupo_total').removeAttr('readonly');
		
	if(opt.val() == 99){
		//si la habitacion elegida es la COMPARTIDA
		$('.ocultar_compartida').hide();
		$('#cantidad').val(1).attr('readonly','readonly');
		$('#cupo_total').val(1).attr('readonly','readonly');
	}
	else{
		$('.ocultar_compartida').show();
		
		var pax = $(opt).attr('rel');
		var cantidad = $('#cantidad').val();
	  
		if(cantidad > 0 && pax > 0){
			$('#cupo_total').val(cantidad*pax);
		}
		else{
			$('#cupo_total').val('');
		}
	}
}
</script>  
