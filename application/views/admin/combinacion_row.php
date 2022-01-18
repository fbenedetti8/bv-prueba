	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <td class="id_cell" data-ref="<?=$row->id;?>"><input type="checkbox" class="default" name="" value="1"></td>
	  <?php echo $this->admin->td($row->lugar.'<br>'.$row->transportecompleto);?>
	  <?php echo $this->admin->td($row->alojamientocompleto);?>
	  <?php echo $this->admin->td($row->habitacion.'<br>'.$row->regimen);?>
	  <td class="text-right">
		<?=$row->precio_usd?'USD':'$';?> <span class="v_total v_total_<?=$row->id;?>"><?=number_format($row->v_total,2,'.',',');?></span>
		<button data-href="<?=base_url();?>admin/paquetes/ver_precios/<?php echo $row->id;?>" class="bs-tooltip btn btn-ver-precios" title="Ver composicion de precios"><i class="icol-money"></i> Ver composición</button>
	  </td>
	  <td>
		<button data-href="<?=base_url();?>admin/paquetes/borrar_combinacion/<?php echo $row->id;?>" class="bs-tooltip btn btn-delete-combinacion" title="Borrar combinacion"><i class="icol-cross"></i> Borrar</button>
	  </td>
	</tr>