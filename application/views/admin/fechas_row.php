
<? $row->{'fecha_salida_'.$row->ft_id} = formato_fecha($row->{'fecha_salida_'.$row->ft_id}); ?>
<? $row->{'fecha_regreso_'.$row->ft_id} = formato_fecha($row->{'fecha_regreso_'.$row->ft_id}); ?>
<? $row->{'fecha_vencimiento_'.$row->ft_id} = formato_fecha($row->{'fecha_vencimiento_'.$row->ft_id}); ?>

	<tr class="tr_row <?php echo alternator('odd', 'even');?>">
	  <? if(true):?>
		<td align="center" colspan="8">
			<div class="row">
				<div class="col-xs-12">
					<div class="col-md-3">
						<?php echo $this->admin->datepicker('fecha_salida_'.$row->ft_id, 'Fecha salida', '', $row, true, false, false); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->admin->input('vuelo_ida_'.$row->ft_id, 'Vuelo Ida N°', '', $row, false); ?>
					</div>
					<div class="col-md-4">
						<?php echo $this->admin->input('vuelo_aeropuerto_'.$row->ft_id, 'Aeropuerto Destino', '', $row, false); ?>
					</div>
					<div class="col-md-2">
						<div class="form-group" style="    padding-top: 40px;text-align: right;padding-right: 15px;">
						<button data-href="<?=base_url();?>admin/transportes/update_fecha/<?php echo $row->ft_id;?>" class="bs-tooltip btn btn-update-fecha" title="Actualizar fecha"><i class="icos-refresh"></i> </button>
						<button data-href="<?=base_url();?>admin/transportes/borrar_fecha/<?php echo $row->ft_id;?>" class="bs-tooltip btn btn-delete-fecha" title="Borrar fecha"><i class="icol-cross"></i> </button>
						</div>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-3">
						<?php echo $this->admin->datepicker('fecha_regreso_'.$row->ft_id, 'Fecha Regreso', '', $row, true, false, false); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->admin->input('vuelo_regreso_'.$row->ft_id, 'Vuelo Regreso N°', '', $row, false); ?>
					</div>
					<div class="col-md-1">
						<?php echo $this->admin->input('cupo_total_'.$row->ft_id, 'Cupo', '', $row, false); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->admin->datepicker('fecha_vencimiento_'.$row->ft_id, 'Fecha Vencimiento', '', $row, true, false, false); ?>
					</div>
				</div>
			</div>
		</td>
	  <? else: ?>
	  <td align="center">
		<?php echo $this->admin->datepicker('fecha_salida_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->input('vuelo_ida_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->input('vuelo_aeropuerto_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->datepicker('fecha_regreso_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->input('vuelo_regreso_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->input('cupo_total_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td align="center">
		<?php echo $this->admin->datepicker('fecha_vencimiento_'.$row->ft_id, '', '', $row, false); ?>
	  </td>
	  <td>
		<button data-href="<?=base_url();?>admin/transportes/update_fecha/<?php echo $row->ft_id;?>" class="bs-tooltip btn btn-update-fecha" title="Actualizar fecha"><i class="icos-refresh"></i> </button>
		<button data-href="<?=base_url();?>admin/transportes/borrar_fecha/<?php echo $row->ft_id;?>" class="bs-tooltip btn btn-delete-fecha" title="Borrar fecha"><i class="icol-cross"></i> </button>
	  </td>
	  <? endif; ?>
	</tr>