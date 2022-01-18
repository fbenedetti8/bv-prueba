	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->alojamientocompleto);?>
	  <?php echo $this->admin->td($row->regimen);?>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_regimen/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-regimen" title="Borrar regimen"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>