	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->nombrecompleto);?>
	  <?php echo $this->admin->td($row->hora);?>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_parada/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-parada" title="Borrar parada"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>