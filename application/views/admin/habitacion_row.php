	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->habitacion);?>
	  <td align="center"><?=$row->cantidad;?></td>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_habitacion/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-habitacion" title="Borrar habitación"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>