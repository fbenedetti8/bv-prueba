	<tr class="tr_row cupo_row_<?=$row->fecha_id;?> <?php echo alternator('odd', 'even');?>" data-hab="<?=$row->habitacion_id;?>" data-pax="<?=$row->pax;?>">
		<!-- <? $paqs = explode(',',$row->paquetes);?>
	  <td align="center"><?=implode(', ',$paqs);?></td> -->
	  <td align="center"><?=$row->fecha;?></td>
	  <td align="center"><?=$row->habitacion;?></td>
	  <? if($row->habitacion_id == 99): 
		//si es compartida no muestro este dato ?>
		<td align="center" colspan="2"></td>
	  <? else: ?>
		<td align="center " >
			<?php echo $this->admin->input('cantidad_'.$row->id, '', '', $row, false); ?>
		</td>
		<td align="center">
			<?php echo $this->admin->input('cupo_total_'.$row->id, '', '', $row, false); ?>
		</td>
	  <? endif; ?>
	  <td>
		<button data-href="<?=base_url();?>admin/alojamientos/update_habitacion/<?php echo $row->id;?>" class="bs-tooltip btn btn-update-habitacion" title="Actualizar cupo"><i class="icos-refresh"></i> </button>
		<button data-href="<?=base_url();?>admin/alojamientos/borrar_habitacion/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-habitacion" title="Borrar cupo" style="margin-top:5px;"><i class="icol-cross" ></i> Borrar</button>
	  </td>
	</tr>
	
<script>
$(document).ready(function(){
  //cantidad
  $('body').on('change','#cantidad_<?=$row->id;?>', function(e){
	  var me = $(this);
	  var hab = me.closest('.tr_row').data('hab');
	  var pax = me.closest('.tr_row').data('pax');
	console.log(me.closest('.tr_row'));
	console.log(hab);
	console.log(pax);
		me.removeAttr('readonly');
		$('#cupo_total_<?=$row->id;?>').removeAttr('readonly');
			
		if(hab == 99){
			me.val(1).attr('readonly','readonly');
			$('#cupo_total_<?=$row->id;?>').val(1).attr('readonly','readonly');
		}
		else{			
			var cantidad = me.val();
		  
			if(cantidad > 0 && pax > 0){
				$('#cupo_total_<?=$row->id;?>').val(cantidad*pax);
			}
			else{
				$('#cupo_total').val('');
			}
		}
  });
  
});

</script>  