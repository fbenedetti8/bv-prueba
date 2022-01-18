	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <td align="center"><?=formato_fecha($row->fecha_checkin);?></td>
	  <td align="center"><?=formato_fecha($row->fecha_checkout);?></td>
	  <td align="center"><input type="text" class="form-control descripcion_fechas" style="width:100%;" data-ref="<?=$row->id;?>" value="<?=$row->descripcion;?>" /></td>
	  <td style="text-align: center;"><a href="#habitaciones" class="lnkVerHabs" data-toggle="tab" rel="<?php echo $row->id;?>">Ver cupos</a></td>
	  <td>
		<button data-href="<?=base_url();?>admin/alojamientos/duplicar_fecha/<?php echo $row->id;?>" class="bs-tooltip btn btn-duplicar-fecha" title="Duplicar fecha" data-rel="<?php echo $row->id;?>"><i class="icol-page-copy"></i> </button>
		
		<button data-href="<?=base_url();?>admin/alojamientos/borrar_fecha/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-fecha" title="Borrar fecha" style="margin-top:5px;"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>