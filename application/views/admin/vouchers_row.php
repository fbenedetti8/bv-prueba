	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <td><a href="<?=base_url().'uploads/reservas/'.$row->reserva_id.'/'.$row->archivo;?>" target="_blank"><?=$row->archivo;?> <i class="glyphicon glyphicon-new-window"></i></a></td>
	  <?php echo $this->admin->td(date('d/m/Y H:i',strtotime($row->timestamp)));?>
	  <td>
		<button data-href="<?=base_url();?>admin/reservas/borrar_voucher/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-voucher" title="Borrar voucher"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>