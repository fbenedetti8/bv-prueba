	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <?php echo $this->admin->td($row->lugar.'<br>'.$row->transporte);?>
	  <?php echo $this->admin->td($row->alojamiento);?>
	  <?php echo $this->admin->td($row->habitacion.'<br>'.$row->regimen);?>
	  <td class="text-right">
		<?=$row->precio_usd?'USD':'$';?> <span class="v_total v_total_<?=$row->id;?>"><?=number_format($row->v_total,2,'.',',');?></span>
	  </td>
	  <td class="text-center">
		<input type="radio" name="combinacion_id" class="form-control comb_opcion" value="<?=$row->id;?>"/>
	  </td>
	</tr>