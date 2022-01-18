	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->transportecompleto);?>
	  <?php echo $this->admin->td($row->alojamientocompleto);?>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_alojamiento/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-alojamiento" title="Borrar alojamiento"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>