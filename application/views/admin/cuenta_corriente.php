						<div class="widget box" id="form_cta_cte">
							<div class="widget-header">
							  <h3 style="margin-bottom:20px;">
								Cuenta Corriente 
								<div style="display:inline-block;">
									<select class="form-control" name="moneda_activa" id="moneda_activa" onchange="javascript: cambiar_moneda(this);">
										<option value="ARS" <?=@$moneda_activa=='ARS'?'selected':'';?>>Pesos</option>
										<option value="USD" <?=@$moneda_activa=='USD'?'selected':'';?>>Dólares</option>
									</select>
								</div>
								<? if( perfil()=='ADM' || perfil()=='SUP' ): ?>
									<a href="#" class="btn btn-sm btn-primary pull-right btnToggleCta">Agregar movimiento <i class="glyphicon glyphicon-chevron-down"></i></a>
								<? endif; ?>
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
													<label class="control-label">Concepto *:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control required" name="concepto" id="concepto">
																<option value=""></option>
																<? foreach($conceptos as $c): ?>
																<option value3="<?=$c->incluye_gastos;?>" value2="<?=$c->aplica_a;?>" value="<?=$c->nombre;?>"><?=$c->nombre;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-2 box_cuotas hidden">
												<div class="form-group">
													<label class="control-label">Cuotas *:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" name="cuotas" id="cuotas" onchange="javascript: elegircuota(this);">
																<? foreach($metodos_pago as $b): 
																	$cuotas = @$b->tarjetas[0]->cuotas; ?>
																	<optgroup label="<?=$b->banco;?>">
																	<? foreach($cuotas as $t){ ?>
																		<option data-coeficiente="<?=$t->coeficiente;?>" value="<?=$t->cuotas;?>"><?=$t->cuotas;?> <?=$t->cuotas>1?'cuotas':'cuota';?></option>
																	<? } ?>
																	</optgroup>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4 box_moneda">
												<div class="form-group">
													<label class="control-label">Moneda *:</label>
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
											<input type="hidden" id="precio_usd" value="<?=(isset($paquete) && $paquete->precio_usd)?$paquete->precio_usd:0;?>"/>
											<div class="col-md-4" id="div_tipo_cambio" style="display:none;">
												<div class="form-group">
													<label class="control-label">Tipo de cambio *:</label>
													<input type="text" name="tipo_cambio" id="tipo_cambio" class="form-control money required" value="<?=number_format($this->settings->cotizacion_dolar,2,',','.');?>" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Debe *:</label>
													<input type="text" name="debe" id="debe" class="form-control money required" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Haber *:</label>
													<input type="text" name="haber" id="haber" class="form-control money required" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
											<div class="col-md-4 box_intereses hidden">
												<div class="form-group">
													<label class="control-label">Intereses:</label>
													<input type="text" name="intereses" id="intereses" class="form-control money" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
											<div class="col-md-4 box_gastos">
												<div class="form-group">
													<label class="control-label">Gastos Administrativos:</label>
													<input type="text" name="gastos_administrativos" id="gastos_administrativos" class="form-control money" value="" placeholder="Ej 1.234,56"/>
												</div>
											</div>
										</div>
										<div class="row">
											<? if(isset($facturas_generadas) && $facturas_generadas): ?>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">Factura Asociada:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" id="factura_asociada" name="factura_asociada">
																<option value=""></option>
																<? foreach($facturas_generadas as $f): 
																	if($f->haber > 0): ?>
																		<option value="<?=$f->factura_id.'|'.$f->talonario;?>"><?=$f->comprobante;?></option>
																	<? endif; ?>
																<? endforeach; ?>
															</select>
															<span class="help-block">Sólo para Notas de Crédito</span>
														</div>
													</div>
												</div>
											</div>
											<? endif; ?>
											<div class="col-md-4">
												<? if(@$reserva_id>0): //si ya se cual es la reserva, muestro informes de pago ?>
												<div class="form-group">
													<label class="control-label">Informes de Pago:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" id="informe_id" name="informe_id" >
																<option value=""></option>
																<? foreach($informes_pago as $c): ?>
																<option value="<?=$c->id;?>" data-concepto="<?=@$c->concepto->nombre;?>" data-monto="<?=$c->monto_pago;?>" data-moneda="<?=$c->tipo_moneda;?>" <?=@$informe_id==$c->id?'selected':'';?>><?=$c->banco.' - '.$c->fecha_pago.' '.$c->hora_pago.' - '.$c->tipo_pago.' - '.$c->monto_pago;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
												<input type="hidden" name="reserva_id" value="<?=$reserva_id;?>"/>
												<? else: //no se cual es, puedo elegirla ?>
												<div class="form-group">
													<label class="control-label">Reserva Asociada *:</label>
													<div class="row">
														<div class="col-md-12">
															<select class="form-control required" id="reserva_id" name="reserva_id" <?=(isset($reserva_id) && $reserva_id)?'readonly':'';?>>
																<option value=""></option>
																<? foreach($reservas as $c): ?>
																<option value="<?=$c->id;?>" <?=@$reserva_id==$c->id?'selected':'';?>><?=$c->nombre.' - Cod. '.$c->code;?></option>
																<? endforeach; ?>
															</select>
														</div>
													</div>
												</div>
												<? endif; ?>
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
											<?php echo $this->admin->th('comprobante', 'Comprobante', true);?>
											<?php echo $this->admin->th('afip', 'AFIP', true);?>
											<?php if(@$moneda_activa == 'USD') echo $this->admin->th('tipo_cambio', 'Tipo de Cambio', true);?>
											<?php echo $this->admin->th('debe', 'Debe', true, array('text-align'=>'right'));?>
											<?php echo $this->admin->th('haber', 'Haber', true, array('text-align'=>'right'));?>
											<?php echo $this->admin->th('parcial', 'Saldo', true, array('text-align'=>'right'));?>
										</tr>
									</thead>
									<tbody>
										<?php $saldo = 0.00; $saldo_usd = 0.00;
										foreach (@$movimientos->result() as $m): ?>
										<tr class="<?php echo alternator('odd', 'even');?>" data-id="<?=$m->id;?>">
											<?php echo $this->admin->td("<span class='hidden'>".$m->fecha."</span>".date('d/m/Y H:i',strtotime($m->fecha)).'hs');?>
											<?php echo $this->admin->td($m->concepto.'<br>'.$m->paquete_codigo.' | '.$m->paquete_titulo);?>
											<td relb="<?=@$m->factura_reserva_id;?>" rela="<?=@$m->comprobante;?>" id="cell_comprobante_<?=$m->factura_id;?>" data-rowid="<?=@$row->id;?>" data-rid="<?=$m->factura_reserva_id;?>">
												<? if (file_exists('./data/facturas/'.$m->comprobante.'.pdf')): ?>
													<? if ((@$m->factura_reserva_id == @$row->id) || ($page == 'usuarios')): ?>
														<a href="<?=base_url();?>data/facturas/<?=$m->comprobante;?>.pdf" target="_blank"><?=$m->comprobante;?></a>
													<? endif; ?>
												<? else: ?>
													<?=$m->comprobante;?>
												<? endif; ?>
												
												<? //si el comprobante es un RECIBO y está el costo cargado en paquete 
												   //=> pongo boton para facturar
												if(substr($m->comprobante,0,2) == 'RE' && @$row->c_costo_operador): ?>
													<button class="btn btn-info btnFacturar" data-href="<?=base_url().'admin/reservas/facturarRecibo/'.$m->id;?>">Facturar recibo</button>
												<? endif; ?>
												
												<? if(false && $m->factura_id && $m->tipo && $m->sucursal_id){ ?>
													<br><a class="btn btn-small btn-orange lnkReenviarFactura lnk-resend" href="#" data-href="<?=base_url().'admin/facturacion/reenviar_factura/'.$m->factura_id.'/'.$m->tipo.'/'.$m->sucursal_id;?>">Factura</a>
												<? } ?>
											</td>
											<td>
												<? if ($m->factura_id): ?>
													<? if ($m->cae == ''): ?>
														<? if ($m->talonario == 'FA_B'): ?>
															<? if (@$m->factura_reserva_id == @$row->id): ?>
																<a href="#" class="lnkInformarFactura btn btn-small btn-orange" data-factura="<?=$m->factura_id;?>" data-comprobante="<?=$m->talonario;?>" data-sucursal="<?=$m->sucursal_id;?>" data-reserva="<?=$m->reserva_id;?>" data-movimiento="">INFORMAR</a>
															<? else: ?>
																<a href="#" class="lnkInformarFactura btn btn-small btn-orange hidden" data-factura="" data-comprobante="<?=$m->talonario;?>" data-sucursal="<?=$m->sucursal_id;?>" data-reserva="<?=$m->reserva_id;?>" data-movimiento="<?=$m->id;?>">INFORMAR</a>
															<? endif; ?>
														<? else: ?>
															<? if(substr($m->comprobante,0,2) != 'RE'): ?>
																<a style="width: 100px;" href="#" class="lnkGenerarFactura btn btn-small btn-orange" data-factura="<?=$m->factura_id;?>" data-comprobante="<?=$m->talonario;?>" data-sucursal="<?=$m->sucursal_id;?>" data-reserva="<?=$m->reserva_id;?>">GENERAR</a>
															<? endif; ?>
														<? endif; ?>
													<? endif; ?>
													<img src="<?=base_url();?>media/admin/assets/img/yes.png" title="Factura Informada" id="factura_<?=$m->factura_id;?>" class="<?=$m->cae == '' ? 'hidden' : '';?>" />
												<? endif; ?>
											</td>
											<? if(@$moneda_activa == 'USD'): ?>
												<td align="right"><?=number_format($m->tipo_cambio,2,',','.');?></td>
											<? endif; ?>
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->debe:$m->debe_usd,2,',','.');?></td>
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->haber:$m->haber_usd,2,',','.');?></td>
											<!--<td align="right"><?=number_format(@$moneda_activa=='ARS'?$m->parcial:$m->parcial_usd,2,',','.');?></td>-->
											<td align="right"><?=number_format(@$moneda_activa=='ARS'?($saldo+=$m->debe-$m->haber):($saldo_usd+=$m->debe_usd-$m->haber_usd),2,',','.');?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						
	<input type="hidden" id="mp_gastos" value="<?=$this->settings->mp_gastos_admin;?>"/>
	<input type="hidden" id="pp_gastos" value="<?=$this->settings->pp_gastos_admin;?>"/>
	<input type="hidden" id="pp_gastos_fijos" value="<?=$this->settings->pp_gastos_admin_fijos;?>"/>
	
<script type="text/javascript">
	$(document).ready(function(){
		$('.money').mask("#.##0,00", {reverse: true});
  
		
		
		$('body').on('click','.lnkGenerarFactura',function(e){
			e.preventDefault();
			var btn = $(this);
			if (btn.hasClass('btn-blue')) {
				bootbox.alert('Por favor espere a que concluya la operacion');
				return;
			}
			
			if (confirm('Generar factura?')) {
				//$.fancybox.showActivity();
				btn.html('GENERANDO...').removeClass('btn-orange').addClass('btn-blue');
				
				$.post('<?=$route;?>/informar_factura', {factura_id:$(btn).attr('data-factura'), comprobante:$(btn).attr('data-comprobante'), sucursal_id:$(btn).attr('data-sucursal'), reserva_id:$(btn).attr('data-reserva'), movimiento_id:$(btn).attr('data-movimiento')}, function(data){
					//$.fancybox.hideActivity();
					data = $.parseJSON(data);
					if (data.status == 'ok') {
						btn.remove();
						$('#factura_' + btn.attr('data-factura')).show();
						$('#cell_comprobante_' + btn.attr('data-factura')).html('<a href="<?=base_url();?>data/facturas/' + data.comprobante + '.pdf" target="_blank">' + data.comprobante + '</a>');
					}
					else {
						btn.removeClass('btn-blue').addClass('btn-orange').html('GENERAR');
						bootbox.alert('No se pudo generar el comprobante. ' + data.error);
					}
				});
			}
		});

		$('body').on('click','.lnkInformarFactura',function(e){
			e.preventDefault();
			var btn = $(this);
			if (btn.hasClass('btn-blue')) {
				bootbox.alert('Por favor espere a que concluya la operacion');
				return;
			}
			
			bootbox.confirm('Esta seguro que desea informar la factura a AFIP?', function(result){
				if (result){
					//$.fancybox.showActivity();
					btn.html('INFORMANDO...').removeClass('btn-orange').addClass('btn-blue');
					
					$.post('<?=$route;?>/informar_factura', {factura_id:$(btn).attr('data-factura'), comprobante:$(btn).attr('data-comprobante'), sucursal_id:$(btn).attr('data-sucursal'), reserva_id:$(btn).attr('data-reserva'), movimiento_id:$(btn).attr('data-movimiento')}, function(data){
						//$.fancybox.hideActivity();
						data = $.parseJSON(data);
						if (data.status == 'ok') {
							btn.remove();
							$('#factura_' + btn.attr('data-factura')).show();
							$('#cell_comprobante_' + btn.attr('data-factura')).html('<a href="<?=base_url();?>data/facturas/' + data.comprobante + '.pdf" target="_blank">' + data.comprobante + '</a>');
						}
						else {
							btn.removeClass('btn-blue').addClass('btn-orange').html('INFORMAR');
							bootbox.alert('No se pudo informar la factura. ' + data.error);
						}
					});
				}
			});
		});
		

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
			
			var error = false;
			$.each($('#form_cta_cte input:enabled.required, #form_cta_cte select:enabled.required'),function(i,el){
				if($(el).val() == ''){
					error = true;
					return;
				}
			});

			if(error){
				me.removeClass("hidden");
				$("#btnMov-loading").addClass("hidden");
				bootbox.alert('Los campos marcados con * son obligatorios.');
				return false;
			}

			$.post("<?=$route;?>/grabarMovimiento",me.closest('form').serialize(),function(data){				
				if(data.status == 'success'){
					location.href = data.redirect;
				}
				else{
					$("#btnMov-loading").addClass("hidden");
					me.removeClass("hidden");

					if(data.msg){
						bootbox.alert(data.msg);
					}
				}
			},'json');
		});
		
		$('body').on('click','.btnFacturar',function(e){
			e.preventDefault();
			var me = $(this);
			var url = me.attr('data-href');
			  bootbox.confirm("Esta seguro que deseas generar la factura para este recibo?", function(result){
				if (result) {
					location.href = url;
				}
			  });
		});
		
		$('body').on('change','#concepto',function(e){
			habilitar(this);
		});
		
		$('body').on('change','#cuotas',function(e){
			calcular_interes();
		});
		
		
		$('body').on('keydown keyup','#haber',function(e){
			var concepto = $('#concepto option:selected').attr('value');
			console.log('concepto '+concepto);
			var me = $(this);

			var aplica_a = $('#concepto option:selected').attr('value2');

			if(concepto == 'MERCADO PAGO'){
				calcular_mp();
			}
			if(concepto == 'MERCADO PAGO - NOTA DE CREDITO'){
				calcular_mp_nc();
			}

			if(concepto == 'PAYPAL'){
				calcular_pp();
			}

			if(concepto == 'TARJETA DE CREDITO Y DEBITO'){
				calcular_interes();
			}
			if(aplica_a == 'ambos'){
				var h = me.val();
				h = h.replace('.','');
				h = h.replace(',','.');

				if(h > 0){
					$("#debe").val(0).attr("disabled",true);
					$("#haber").attr("disabled",false);
				}
				else{
					$("#debe").attr("disabled",false);
					$("#haber").attr("disabled",false);
				}
			}
		});
		
		$('body').on('keydown keyup','#debe',function(e){
			var concepto = $('#concepto option:selected').attr('value');
			var me = $(this);

			var aplica_a = $('#concepto option:selected').attr('value2');

			if(concepto == 'TARJETA DE CREDITO Y DEBITO - NOTA DE CREDITO'){
				calcular_interes();
			}
			
			if(concepto == 'MERCADO PAGO - NOTA DE CREDITO'){
                calcular_mp_nc();
            }
            
			if(concepto == 'PAYPAL - NOTA DE CREDITO'){
                calcular_pp_nc();
            }
            
			if(aplica_a == 'ambos'){
				var h = me.val();
				h = h.replace('.','');
				h = h.replace(',','.');

				if(h > 0){
					$("#debe").attr("disabled",false);
					$("#haber").val(0).attr("disabled",true);
				}
				else{
					$("#debe").attr("disabled",false);
					$("#haber").attr("disabled",false);
				}
			}
		});
		
		
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
		$('#gastos_administrativos').val("");
		
		$('.box_cuotas').addClass('hidden');
		$('.box_intereses').addClass('hidden');
		$('.box_gastos').removeClass('hidden');
		$('.box_moneda').addClass('col-md-4').removeClass('col-md-2');

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
			break;
			case "MERCADO PAGO":
				calcular_mp();
			break;
			case "MERCADO PAGO - NOTA DE CREDITO":
				calcular_mp_nc();
			break;
			case "PAYPAL":
				calcular_pp();
			break;
			case "PAYPAL - NOTA DE CREDITO":
				calcular_pp_nc();
			break;
			case "TARJETA DE CREDITO Y DEBITO":
			case "TARJETA DE CREDITO Y DEBITO - NOTA DE CREDITO":
				$('.box_cuotas').removeClass('hidden');
				$('.box_intereses').removeClass('hidden');
				$('.box_gastos').addClass('hidden');
				$('.box_moneda').removeClass('col-md-4').addClass('col-md-2');
			break;
			default:
				$('#factura_asociada').removeClass('required');
			break;
		}
		
		switch(aplica_a){
			case "debe":
				$("#debe").attr("disabled",false).focus();
				$("#haber").attr("disabled",true);

				$("#debe").addClass('required').closest('.form-group').find('label').text('Debe *:');
				$("#haber").addClass('required').closest('.form-group').find('label').text('Haber:');
			break;
			case "haber":
				$("#haber").attr("disabled",false).focus();
				$("#debe").attr("disabled",true);

				$("#haber").addClass('required').closest('.form-group').find('label').text('Haber *:');
				$("#debe").addClass('required').closest('.form-group').find('label').text('Debe:');
			break;
			case "ambos":
				$("#debe").attr("disabled",false).val('0,00').focus();
				$("#haber").attr("disabled",false).val('0,00');
				
				$("#debe").addClass('required').closest('.form-group').find('label').text('Debe *:');
				$("#haber").addClass('required').closest('.form-group').find('label').text('Haber *:');
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
	
	function calcular_interes(){
		var g = $('#cuotas option:selected').attr('data-coeficiente');
		g = g-1;

		if($('#debe').is(':disabled')){
			var h = $('#haber').val();
		}
		else{
			var h = $('#debe').val();
		}
		
		h = h.replace('.','');
		h = h.replace(',','.');
		//g = (g > 0) ? parseFloat(g).toFixed(2) : 0.00;
		//h = (h > 0) ? parseFloat(h).toFixed(2) : 0.00;
		var t = parseFloat(g*h).toFixed(2);
		console.log(g);
		console.log(h);
		console.log(t);
		t = t.replace('.',',');
		$('#intereses').val(t);
	}

	function calcular_mp(){
		//le pre cargo el valor de los gastos admin
		var g = $('#mp_gastos').val();
		g = g-1;
		var h = $('#haber').val();
		h = h.replace('.','');
		h = h.replace(',','.');
		//g = (g > 0) ? parseFloat(g).toFixed(2) : 0.00;
		//h = (h > 0) ? parseFloat(h).toFixed(2) : 0.00;
		var t = parseFloat(g*h).toFixed(2);
		console.log(g);
		console.log(h);
		console.log(t);
		t = t.replace('.',',');
		$('#gastos_administrativos').val(t);
	}
	
	function calcular_mp_nc(){
        //le pre cargo el valor de los gastos admin
        var g = $('#mp_gastos').val();
        g = g-1;
        var h = $('#debe').val();
        h = h.replace('.','');
        h = h.replace(',','.');
        //g = (g > 0) ? parseFloat(g).toFixed(2) : 0.00;
        //h = (h > 0) ? parseFloat(h).toFixed(2) : 0.00;
        var t = parseFloat(g*h).toFixed(2);
        console.log('A: '+g);
        console.log('B: '+h);
        console.log('c: '+t);
        t = t.replace('.',',');
        $('#gastos_administrativos').val(t);
    }	

	function calcular_pp(){
		//le pre cargo el valor de los gastos admin
		var g = $('#pp_gastos').val();
		var gf = $('#pp_gastos_fijos').val();
		if($('#moneda').val() == 'ARS'){
			var tc = $('#tipo_cambio').val();
			tc = tc.replace('.','');
			tc = tc.replace(',','.');
			gf = gf*tc;
		}

		var h = $('#haber').val();
		h = h.replace('.','');
        h = h.replace(',','.');

		var t = parseFloat(g*h).toFixed(2);
		gf = parseFloat(gf).toFixed(2);

        var suma = parseFloat(t) + parseFloat(gf);
        suma = suma.toFixed(2);
		suma = suma.replace('.',',');
		$('#gastos_administrativos').val(suma);
	}
	
	function calcular_pp_nc(){
        //le pre cargo el valor de los gastos admin
        var g = $('#pp_gastos').val();
		var gf = $('#pp_gastos_fijos').val();
		if($('#moneda').val() == 'ARS'){
			var tc = $('#tipo_cambio').val();
			tc = tc.replace('.','');
			tc = tc.replace(',','.');
			gf = gf*tc;
		}

        var h = $('#debe').val();
        h = h.replace('.','');
        h = h.replace(',','.');

		var t = parseFloat(g*h).toFixed(2);
		gf = parseFloat(gf).toFixed(2);

        var suma = parseFloat(t) + parseFloat(gf);
        suma = suma.toFixed(2);
		suma = suma.replace('.',',');
		
        $('#gastos_administrativos').val(suma);
    }	
</script>