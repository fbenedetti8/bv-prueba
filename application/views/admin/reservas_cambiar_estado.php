<style>
@media screen and (min-width: 768px){
	.modal-dialog .widget-content { max-height:400px; overflow-x:hidden; overflow-y:scroll; }
}
.penalidad_inactiva { display: none !important; }
.penalidad_activa { display: block !important; }
</style>
						
	<form id="fCambioEstado" method="post" action="<?=$route;?>/cambiar_estado/<?=$reserva->id;?>" >
		<input type="hidden" name="reserva_id" id="reserva_id" value="<?=$reserva->id;?>"/>
		<div class="widget-header">
		  <h2 style="margin:0 0 10px;"><?=$paquete->nombre;?> - <p style="margin: 0;display: inline-block;font-size: 16px;"><?='Desde el '.formato_fecha($paquete->fecha_inicio).' al '.formato_fecha($paquete->fecha_fin);?></p></h2>
		</div>
		<div class="widget-content">
		  <div class="row">
			<div class="col-md-12">
				<label for="estado_id" style="width:200px;display:inline-block;">Seleccionar nuevo estado</label>
				<select name="estado_id" id="estado_id" class="form-control" style="width:300px; display:inline-block;">
				<option value="">Seleccionar </option>
				<? foreach($estados as $estado): ?>
					<option value="<?=$estado->id;?>"><?=$estado->nombre;?></option>
				<? endforeach; ?>
				</select>
			</div>
			
			<div class="alert alert-danger alert-error" style="display:none;">Selecciona el nuevo estado a aplicar.</div>

			<div class="col-md-12" id="anulacion" style="display:none;">
				<br><label for="motivo">Ingresa el motivo de la anulación</label>
				<textarea name="motivo" id="motivo" class="form-control" rows="2"></textarea>
			</div>

			<div class="col-md-12" id="penalidad" style="margin:10px 0 0; display:none;">
				<label style="display: inline-block;">Ingrese la fecha de cancelación:</label> <input style="display: inline-block;width: 140px;margin: 0 0 0 10px;vertical-align: middle;" type="text" id="fecha_baja" name="fecha_baja" class="form-control datepicker2 " value="">
			</div>

			<div class="col-md-12" id="box-penalidades" style="display:none;">
				
			</div>
		  </div>
		</div>
	</form>
	<script>
	$(document).ready(function(){
		$('#estado_id').change(function(){
			var me = $(this);
			if(me.val() == 5){
				$('#anulacion').show();
				$('#penalidad').show();
			}
			else{
				$('#anulacion').hide();
				$('#penalidad').hide();
			}
		});

		$('.datepicker2').datepicker({
			format: "dd/mm/yyyy",
			language: "es"
		}).on("change", function() {
		    var fecha_baja = $('#fecha_baja').val();
		    var reserva_id = $('#reserva_id').val();
		    
		    if(!fecha_baja){
		    	bootbox.alert("Debes elegir la fecha de cancelación de la reserva");
		    	return false;
		    }

		    $.post('<?=base_url();?>admin/reservas/verificar_penalidad',{reserva_id: reserva_id, fecha_baja: fecha_baja},function(data){
		    	if(data.view){
		    		$('#box-penalidades').html(data.view).show();
		    	}
		    },'json');

		    $(this).datepicker("hide");

		  });
		
	});
	</script>