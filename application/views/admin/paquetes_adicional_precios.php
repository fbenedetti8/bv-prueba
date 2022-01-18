<style>
.modal-dialog { width: 70%; }
</style>

<form id="fAdicionalPrecios" method="post" action="<?=$route;?>/grabarPrecioAdicional" >
	
	<div class="list_precios">
	<div class="widget-header" style="border-top:solid 1px #CCC;">
		<h3 style="margin-bottom:20px;" class="pull-left">Composición de Costo<br/><small style="font-size:12px">Indique cómo se compone el costo de la combinación.</small></h3>
	</div>
	<div class="widget-content">
			<div class="row">
				<div class="col-sm-12">
					<table width="100%" class="table table-bordered table-precios-c">
						<tr>
							<th class="text-center">Exento</th>
							<th class="text-center">No Gravado</th>
							<th class="text-center">Gravado 21%</th>
							<th class="text-center">IVA 21%</th>
							<th class="text-center">Gravado 10.5%</th>
							<th class="text-center">IVA 10.5%</th>
							<th class="text-center">Otros Imp.</th>
							<th class="text-center">Costo Operador</th>
						</tr>
						<tr>
							<td><input type="text" name="c_exento" value="<?=@$row->c_exento?$row->c_exento:'0.00';?>" class="form-control onlydecimal text-right c_exento" /></td>
							<td><input type="text" name="c_nogravado" value="<?=@$row->c_nogravado?$row->c_nogravado:'0.00';?>" class="form-control onlydecimal text-right c_nogravado" /></td>
							<td><input type="text" name="c_gravado21" value="<?=@$row->c_gravado21?$row->c_gravado21:'0.00';?>" class="form-control onlydecimal text-right c_gravado21" /></td>
							<td><input type="text" name="c_iva21" value="<?=@$row->c_iva21?$row->c_iva21:'0.00';?>" class="form-control onlydecimal text-right c_iva21" /></td>
							<td><input type="text" name="c_gravado10" value="<?=@$row->c_gravado10?$row->c_gravado10:'0.00';?>" class="form-control onlydecimal text-right c_gravado10" /></td>
							<td><input type="text" name="c_iva10" value="<?=@$row->c_iva10?$row->c_iva10:'0.00';?>" class="form-control onlydecimal text-right c_iva10" /></td>
							<td><input type="text" name="c_otros_imp" value="<?=@$row->c_otros_imp?$row->c_otros_imp:'0.00';?>" class="form-control onlydecimal text-right c_otros_imp" /></td>
							<td><input type="text" name="c_costo_operador" value="<?=@$row->c_costo_operador?$row->c_costo_operador:'0.00';?>" class="form-control onlydecimal text-right c_costo_operador" /></td>
						</tr>
					</table>
				</div>
			</div>
	</div>					
	<div class="widget-header" style="border-top:solid 1px #CCC;display: inline-block;">
		<h3 style="margin-bottom:20px;" class="pull-left">Composición de Venta<br/><small style="font-size:12px">Indique cómo se compone el valor de venta de la combinación.</small></h3>

		<div class="pull-right text-right" style="width:30%; margin-top: 15px;">
			<label class="control-label">FEE </label>
			<div class="input-group " style="width: 40%;display: inline-table;">
				<input name="fee" type="text" class="form-control onlydecimal text-right fee" placeholder="" value="<?=@$row->fee;?>" >
				<span class="input-group-addon">%</span>
			</div>
			<input type="button" value="Calcular" class="btn btn-success btn-calcular-fee" style="vertical-align: inherit;">
		</div>
	</div>
	<div class="widget-content">
			<div class="row">
				<div class="col-sm-12">
					<table width="100%" class="table table-bordered table-precios-v">
						<tr>
							<th class="text-center">Exento</th>
							<th class="text-center">No Gravado</th>
							<th class="text-center">Comisión/Utilidad</th>
							<th class="text-center">Gravado 21%</th>
							<th class="text-center">IVA 21%</th>
							<th class="text-center">Gravado 10.5%</th>
							<th class="text-center">IVA 10.5%</th>
							<th class="text-center">Gastos Admin.</th>
							<th class="text-center">RG.AFIP</th>
							<th class="text-center">Otros Imp.</th>
							<th class="text-center">Total</th>
						</tr>
						<tr>
							<td><input type="text" name="v_exento" value="<?=@$row->v_exento?$row->v_exento:'0.00';?>" class="form-control onlydecimal text-right v_exento" /></td>
							<td><input type="text" name="v_nogravado" value="<?=@$row->v_nogravado?$row->v_nogravado:'0.00';?>" class="form-control onlydecimal text-right v_nogravado" /></td>
							<td><input type="text" name="v_comision" value="<?=@$row->v_comision?$row->v_comision:'0.00';?>" class="form-control onlydecimal text-right v_comision" /></td>
							<td><input type="text" name="v_gravado21" value="<?=@$row->v_gravado21?$row->v_gravado21:'0.00';?>" class="form-control onlydecimal text-right v_gravado21" /></td>
							<td><input type="text" name="v_iva21" value="<?=@$row->v_iva21?$row->v_iva21:'0.00';?>" class="form-control onlydecimal text-right v_iva21" /></td>
							<td><input type="text" name="v_gravado10" value="<?=@$row->v_gravado10?$row->v_gravado10:'0.00';?>" class="form-control onlydecimal text-right v_gravado10" /></td>
							<td><input type="text" name="v_iva10" value="<?=@$row->v_iva10?$row->v_iva10:'0.00';?>" class="form-control onlydecimal text-right v_iva10" /></td>
							<td><input type="text" name="v_gastos_admin" value="<?=@$row->v_gastos_admin?$row->v_gastos_admin:'0.00';?>" class="form-control onlydecimal text-right v_gastos_admin" /></td>
							<td><input type="text" name="v_rgafip" value="<?=@$row->v_rgafip?$row->v_rgafip:'0.00';?>" class="form-control onlydecimal text-right v_rgafip" /></td>
							<td><input type="text" name="v_otros_imp" value="<?=@$row->v_otros_imp?$row->v_otros_imp:'0.00';?>" class="form-control onlydecimal text-right v_otros_imp" /></td>
							<td><input type="text" name="v_total" value="<?=@$row->v_total?$row->v_total:'0.00';?>" class="form-control onlydecimal text-right v_total" /></td>
						</tr>
					</table>

				</div>
			</div>
	</div>
	</div>
	
	<input type="hidden" value="<?=@$row->id;?>" name="paq_adicional_id"/>
</form>