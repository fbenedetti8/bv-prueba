	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->adicional);?>
	  <?php echo $this->admin->td($row->transporte);?>
	  <td align="center"><?=($row->cantidad - $row->usados).' / '.$row->cantidad;?></td>
	  <td align="center"><?=$row->obligatorio?'SI':'NO';?></td>
	  <td class="text-right">
		<?=$row->precio_usd?'USD':'$';?> <span class="v_total"><?=number_format($row->v_total,2,'.',',');?></span>
		<button data-href="<?=base_url();?>admin/paquetes/ver_precios_adicional/<?php echo $row->id;?>" class="bs-tooltip btn btn-ver-precios-adicional" title="Ver composicion del adicional"><i class="icol-money"></i> Ver composición</button>
	  </td>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_adicional/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-adicional" title="Borrar adicional"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>