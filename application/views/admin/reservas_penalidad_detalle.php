	<p style="margin-top:10px;">
		<label for="motivo">Cálculo de penalidades</label>
	</p>

	<p>La fecha límite de pago completo fue el <strong><?=date('d/m/Y',strtotime($paquete->fecha_limite_pago_completo));?></strong>.
	</p>

	<input type="hidden" id="penalidad_paquete" value="<?=$penalidad_paquete?'1':'0';?>"/>

	<p>
		Porcentaje penalidad del viaje: <strong><?=$porc=number_format($porcentaje_penalidad,2,',','.').' %';?></strong>
	</p>
	<p>
		Monto penalidad del viaje: <?=precio($penalidad_paquete,$paquete->precio_usd,true,false,false);?> (<?=$porc;?> de <?=$precio_total;?>)
	</p>
	<p>Monto abonado reserva: <?=$monto_abonado;?></p>
	<p>Penalidad a aplicar: <?=precio($penalidad_tope,$paquete->precio_usd,true,false,false);?></p>
	<p>
		<? if($penalidad_tope > 0): ?>
			<label for="aplica_penalidad" style="width:240px;display:inline-block;">Confirmar aplicación de penalidad</label>
			<select name="aplica_penalidad" id="aplica_penalidad" class="form-control" style="width:60px; display:inline-block;">
				<option value="NO">NO</option>
				<option value="SI">SI</option>
			</select>
		<? else: ?>
			<label for="aplica_penalidad" style="width:240px;display:inline-block;">No se aplicará penalidad</label>
		<? endif; ?>
	</p>