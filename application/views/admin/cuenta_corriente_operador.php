						<div class="widget box">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">
								Cuenta Corriente 
								<div style="display:inline-block;">
									<select class="form-control" name="moneda_activa" id="moneda_activa" onchange="javascript: cambiar_moneda(this);">
										<? if(isset($paquete->precio_usd)): ?>
											<? if(!$paquete->precio_usd): 
												//Las reservas que están en dólares no deberian tener cta cte en pesos ?>
												<option value="ARS" <?=@$moneda_activa=='ARS'?'selected':'';?>>Pesos</option>
											<? else: 
												//Las reservas que están en pesos no deberian tener cta cte en dolares ?>
												<option value="USD" <?=@$moneda_activa=='USD'?'selected':'';?>>Dólares</option>
											<? endif; ?>
										<? else: ?>
											<option value="ARS" <?=@$moneda_activa=='ARS'?'selected':'';?>>Pesos</option>
											<option value="USD" <?=@$moneda_activa=='USD'?'selected':'';?>>Dólares</option>
										<? endif; ?>
									</select>
								</div>
								<a href="#" class="btn btn-sm btn-primary pull-right btnToggleCta">Agregar movimiento <i class="glyphicon glyphicon-chevron-down"></i></a>
							  </h3>
							</div>
							<div class="widget-content">
								
							<? if (isset($_GET['comprobante'])): ?>	
								<div class="alert alert-success fade in">
									<i class="icon-remove close" data-dismiss="alert"></i>
									<strong>Movimiento agregado con éxito!</strong> 
									<? if ($_GET['comprobante'] != ''): ?>
									Se generó el comprobante <a href="<?=base_url();?>data/facturas/<?=$_GET['comprobante'];?>.pdf" target="_blank"><?=$_GET['comprobante'];?></a>
									<? endif; ?>
								</div>
							<? endif; ?>
				
								<div class="" id="agregar-mov" style="display:none;">
									<div class="col-md-12"><p>Completa los siguientes campos para agregar un movimiento</p></div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Concepto:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" name="concepto" id="concepto">
																<option value=""></option>
																<? foreach($conceptos as $c): ?>
																<option value3="<?=$c->incluye_gastos;?>" value2="<?=$c->aplica_a;?>" value="<?=$c->nombre;?>"><?=$c->nombre;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Moneda:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" name="moneda" id="moneda" onchange="javascript: tipocambio(this);">
																<option value="ARS">Pesos</option>
																<option value="USD">Dólares</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<input type="hidden" id="precio_usd" value="<?=isset($paquete->precio_usd)?$paquete->precio_usd:($moneda_activa=='USD'?1:0);?>"/>
											<div class="col-md-4" id="div_tipo_cambio" style="display:none;">
												<div class="form-group">
													<label class="control-label">Tipo de cambio:</label>
													<input type="text" name="tipo_cambio" id="tipo_cambio" class="form-control money" value="<?=number_format($this->settings->cotizacion_dolar,2,',','.');?>" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Debe:</label>
													<input type="text" name="debe" id="debe" class="form-control money" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Haber:</label>
													<input type="text" name="haber" id="haber" class="form-control money" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
											<div class="col-md-4 hidden">
												<div class="form-group">
													<label class="control-label">Gastos Administrativos:</label>
													<input type="text" name="gastos_administrativos" id="gastos_administrativos" class="form-control money" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Paquete Asociado:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" id="paquete_id" name="paquete_id" <?=(isset($paquete_id) && $paquete_id)?'readonly':'';?>>
																<option value=""></option>
																<? foreach($paquetes as $c): ?>
																<option value="<?=$c->id;?>" <?=@$paquete_id==$c->id?'selected':'';?>><?=$c->nombre.' - Cod. '.$c->codigo;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Comentarios:</label>
													<textarea name="comentarios_mov" id="comentarios_mov" class="form-control"></textarea>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><br><br></label>
													<button id="btnMov" class="btn btn-sm btn-success">Guardar movimiento</a>
													<button id="btnMov-loading" class="btn btn-success disabled hidden" data-loading-text="Guardando..." disabled="disabled">Guardando...</button>
													
													<input type="hidden" name="tipo_id" id="tipo_id" value="<?=$usuario_id;?>"/>
													<input type="hidden" name="tipo" id="tipo" value="<?=$tipo;?>"/>
													<input type="hidden" name="referer" id="referer" value="<?=@$page;?>"/>
												</div>
											</div>
										</div>
									</div>
									<hr/>
								</div>
								
								<table cellpadding="0" cellspacing="0" border="0" class="table table-hover  table-striped datatable">
									<thead>
										<tr>
											<?php echo $this->admin->th('fecha', 'Fecha', true);?>
											<?php echo $this->admin->th('concepto', 'Concepto', true);?>
											<?php echo $this->admin->th('paquete_titulo', 'Paquete', true);?>
											<?php echo $this->admin->th('reserva_codigo', 'Reserva', true);?>
											<?php if(@$moneda_activa == 'USD') echo $this->admin->th('tipo_cambio', 'Tipo de Cambio', true);?>
											<?php echo $this->admin->th('debe', 'Debe', true, array('text-align'=>'right'));?>
											<?php echo $this->admin->th('haber', 'Haber', true, array('text-align'=>'right'));?>
											<?php echo $this->admin->th('parcial', 'Saldo', true, array('text-align'=>'right'));?>
										</tr>
									</thead>
									<tbody>
										<?php foreach (@$movimientos->result() as $m): ?>
										<tr class="<?php echo alternator('odd', 'even');?>">
											<?php echo $this->admin->td("<span class='hidden'>".$m->fecha."</span>".date('d/m/Y H:i',strtotime($m->fecha)).'hs');?>
											<?php echo $this->admin->td($m->concepto);?>
											<?php echo $this->admin->td(($m->op_paquete_titulo!='')?($m->op_paquete_codigo.' '.$m->op_paquete_titulo):($m->paquete_codigo.' '.$m->paquete_titulo));?>
											<?php echo $this->admin->td($m->reserva_codigo);?>
											<? if(@$moneda_activa == 'USD'): ?>
												<td align="right"><?=number_format($m->tipo_cambio,2,',','.');?></td>
											<? endif; ?>
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->debe:$m->debe_usd,2,',','.');?></td>
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->haber:$m->haber_usd,2,',','.');?></td>
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->parcial:$m->parcial_usd,2,',','.');?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						
	<input type="hidden" id="mp_gastos" value="<?=$this->settings->mp_gastos_admin;?>"/>
	
<script type="text/javascript">
	$(document).ready(function(){
		$('.money').mask("#.##0,00", {reverse: true});
  
		$('body').on('click','.btnToggleCta',function(e){
			e.preventDefault();
			var me = $(this);
			$('#agregar-mov').slideToggle(); 
			
			if($(this).find('i').hasClass('glyphicon-chevron-down')){
				$(this).find('i').removeClass('glyphicon-chevron-down');
				$(this).find('i').addClass('glyphicon-chevron-up');
			}
			else if($(this).find('i').hasClass('glyphicon-chevron-up')){
				$(this).find('i').removeClass('glyphicon-chevron-up');
				$(this).find('i').addClass('glyphicon-chevron-down');
			}
		});
		
		$('body').on('click','#btnMov',function(e){
			e.preventDefault();
			var me = $(this);
			me.addClass("hidden");
			$("#btnMov-loading").removeClass("hidden");
			
			if(!$('#paquete_id').val()){
				bootbox.alert('Debes elegir el paquete asociado al movimiento.');
				$("#btnMov-loading").addClass("hidden");
				me.removeClass("hidden");
				return false;
			}
			
			$.post("<?=$route;?>/grabarMovimientoOperador",me.closest('form').serialize(),function(data){				
				if(data.status == 'success'){
					location.href = data.redirect;
				}
				else{
					$("#btnMov-loading").addClass("hidden");
					me.removeClass("hidden");
				}
			},'json');
		});
		
		$('body').on('change','#concepto',function(e){
			habilitar(this);
		});
		
		/*
		$('body').on('keydown keyup','#haber',function(e){
			calcular_mp();
		});
		*/
		
		var d = document.getElementById('moneda');
		tipocambio(d);
	});
		
	function tipocambio(obj){
		console.log(obj.value);
		var precio_usd = $('#precio_usd').val();
		if(obj.value == 'USD' || (obj.value == 'ARS' && precio_usd == 1)){
			$('#div_tipo_cambio').show();
		}
		else{
			$('#div_tipo_cambio').hide();
		}
	}
	
	function cambiar_moneda(obj){
		var text = '<?=current_url()?>';
		var url = text.replace(/(src=).*?(&)/,'$1' + obj.value + '$2');
		location.href = url+"?tab=cta_cte&moneda="+obj.value;
	}
	
	function habilitar(obj){
		var concepto = $('#'+obj.id+' option:selected').attr('value');
		var aplica_a = $('#'+obj.id+' option:selected').attr('value2');
		var incluye_gastos = $('#'+obj.id+' option:selected').attr('value3');
		$("#debe").val("");
		$("#haber").val("");
		
		switch(concepto){
			case "NOTA DE CREDITO":
			case "NOTA DE CREDITO - EFECTIVO":
			case "NOTA DE CREDITO / DINERO MAIL":
			case "HSBC - NOTA DE CREDITO - DEPOSITO / TRANSFERENCIA":
			case "NOTA DE CREDITO / MERCADO PAGO":
			case "NOTA DE CREDITO - TARJETA DE CREDITO":
			case "NOTA DE CREDITO - TARJETA DE DEBITO":
			case "GALICIA - NOTA DE CREDITO - DEPOSITO / TRANSFERENCIA":
				$('#factura_asociada').addClass('required');
			break;/*
			case "MERCADO PAGO":
				calcular_mp();
			break;*/
			default:
				$('#factura_asociada').removeClass('required');
			break;
		}
		
		switch(aplica_a){
			case "debe":
				$("#debe").attr("disabled",false).focus();
				$("#haber").attr("disabled",true);
			break;
			case "haber":
				$("#haber").attr("disabled",false).focus();
				$("#debe").attr("disabled",true);
			break;
			case "ambos":
				$("#debe").attr("disabled",false).focus();
				$("#haber").attr("disabled",false);
			break;						
		}			
		
		switch(incluye_gastos){
			case "1":
				//$("#gastos_administrativos").attr("disabled",true);
				$("#gastos_administrativos").attr("disabled",false);
			break;
			case "0":
				$("#gastos_administrativos").attr("disabled",true);
			break;						
		}
		
		<? if(isset($importe_ajuste) && $importe_ajuste){ ?>
			//03-12 le calculo el valor de saldo del viaje con ajuste de
			//percepcion del 35%
			$("#div_saldo_anterior").hide();
			if(concepto == 'AJUSTE PERCEPCION AFIP'){
				$("#saldo_anterior").val('<?=$saldo_anterior;?>');
				$("#div_saldo_anterior").show();
				$("#div_saldo_anterior_msg").show();
				$("#debe").val('<?=$importe_ajuste;?>');
				//jAlert('El ajuste correspondiente sobre dicho viaje es de <b>$ <?=$importe_ajuste;?></b>.<br>Por favor verificar si es correcto y corregirlo si es necesario.','Atencion');
			}
		<? } ?>
	}
	/*
	function calcular_mp(){
		//le pre cargo el valor de los gastos admin
		var g = $('#mp_gastos').val();
		var h = $('#haber').val();
		h = h.replace('.','');
		h = h.replace(',','.');
		g = (g > 0) ? parseFloat(g).toFixed(2) : 0.00;
		h = (h > 0) ? parseFloat(h).toFixed(2) : 0.00;
		var t = parseFloat(g*h).toFixed(2);
		console.log(g);
		console.log(h);
		console.log(t);
		$('#gastos_administrativos').val(t);
	}*/
</script>