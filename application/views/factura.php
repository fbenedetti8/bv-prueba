<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
html { line-height:19px; }
body * { line-height:16px; }
table tr td { border:solid 1px #000; padding:10px; position:relative; font-family:sans-serif; font-size:11px; }
table.sub tr td { border:none; padding:0; }
small { font-family:sans-serif; }
span { font-family:sans-serif; font-weight:bold; font-size:28px; }
.tipo { font-family:sans-serif; position:absolute; top:0px; right:-50px; width:60px; border:solid 1px #000; text-align:center; padding:20px; background:#FFF; z-index:100; }
.nro { font-family:sans-serif; font-weight:bold; font-size:18px; }
table.detalle { border:none; }
table.detalle tr td { border:solid 0px #000; padding:5px; }
.bl { border-left:solid 1px #000 !important; }
.bt { border-top:solid 1px #000 !important; }
.bb { border-bottom:solid 1px #000 !important; }
.br { border-right:solid 1px #000 !important; }
<? if (isset($gastos_administrativos) && $gastos_administrativos > 0){ ?>
.condiciones { height:250px; overflow:hidden; font-size:8px; line-height:11px; padding-top:0; padding-bottom:0; }
	<? if (isset($pie_operador) && $pie_operador){ ?>
		.condiciones { height:230px;}
	<? } ?>
<? } else { ?>
.condiciones { height:270px; overflow:hidden; font-size:8px; line-height:11px; padding-top:0; padding-bottom:0; }
<? } ?>
.defensa { text-align:center; font-size:10px; font-family:sans-serif; padding:0; margin:0; }
.logo { width:120px; margin-left:10px; }
</style>
</head>

<body>

<table width="100%" cellspacing="0">
	<tr>
		<td width="50%">
			<img src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" width="120" class="logo" />
			<br/>
			<br/>
			Buenas Vibras SRL<br/>
			<?=isset($sucursal_direccion)?$sucursal_direccion:'';?><br/>
			<?=isset($sucursal_nombre)?$sucursal_nombre:'';?><br/>
			<br/>
			IVA Responsable Inscripto - EVT Leg. 14641
			
			<div class="tipo">
				<span>B<?//=$tipo_factura;?></span>
				<br/>
				<small>COD. <?=$codigo_factura;?></small>			
			</div>
		</td>
		<td width="50%" valign="bottom">
			<div align="right" class="nro"><?=$codigo_factura == '006' ? 'Factura' : 'Nota de Crédito';?> <?=str_pad($punto_venta, 4, '0', STR_PAD_LEFT);?>-<?=str_pad($numero_factura, 8, '0', STR_PAD_LEFT);?></div>
			<br/>
			<br/>
			<br/>
			<table width="99%" class="sub">
				<tr>
					<td>
						CUIT: 30-71160493-2<br/>
						IIBB: 901-619836-9<br/>
						Inicio de Actividad: 01/11/2010
					</td>
					<td align="right">
						Fecha <?=isset($fecha_factura)?$fecha_factura:date('d/m/Y', time());?><br/>
						<br/>
						<small>Original</small>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<strong>Datos del Pasajero:</strong> <?=$pasajero;?> <?=(isset($direccion)&&$direccion)?(' - Dirección: '.$direccion):'';?>
			<br/><br/>
			<strong>Forma de Pago:</strong> <?=$forma_pago;?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding:0">
			<table class="detalle" width="100%" cellspacing="0">
				<tr>
					<td width="90%" colspan="2" align="center" class="br bb">Concepto</td>
					<td width="10%" align="center" class="bb">Importe</td>
				</tr>
				<tr>
					<td class="br" colspan="2">
						<?=$concepto;?>
						<br/>
						<?=isset($pie_operador)?$pie_operador:'';?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="br" colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<? if ($total_iva_exento != 0): ?>
				<tr>
					<td class="br" colspan="2">Conceptos Exentos</td>
					<td align="right"><?=number_format($total_iva_exento, 2);?></td>
				</tr>
				<? endif; ?>
				<? if ($neto_nogravado != 0): ?>
				<tr>
					<td class="br" colspan="2">No Gravado</td>
					<td align="right"><?=number_format($neto_nogravado, 2);?></td>
				</tr>
				<? endif; ?>
				<? if ($total_iva_21 != 0): ?>
				<tr>
					<td class="br" colspan="2">Conceptos Gravados al 21%</td>
					<td align="right"><?=number_format($total_iva_21, 2);?></td>
				</tr>
				<? endif; ?>
				<? if ($total_iva_10 != 0): ?>
				<tr>
					<td class="br" colspan="2">Conceptos Gravados al 10,5%</td>
					<td align="right"><?=number_format($total_iva_10, 2);?></td>
				</tr>
				<? endif; ?>
				<? if (isset($otros_impuestos_impuesto) && $otros_impuestos_impuesto != 0): ?>
				<tr>
					<td class="br" colspan="2">Otros impuestos</td>
					<td align="right"><?=number_format($otros_impuestos_impuesto, 2);?></td>
				</tr>
				<? endif; ?>
				<? if (isset($gastos_administrativos) && $gastos_administrativos > 0): ?>
				<tr>
					<td class="br" colspan="2">Gastos administrativos</td>
					<td align="right"><?=number_format($gastos_administrativos, 2);?></td>
				</tr>
				<? endif; ?>
				<? if (isset($intereses) && $intereses > 0): ?>
				<tr>
					<td class="br" colspan="2">Cargos financieros</td>
					<td align="right"><?=number_format($intereses, 2);?></td>
				</tr>
				<? endif; ?>
				<? if(isset($impuesto_pais) && $impuesto_pais): ?>
				<tr>
					<td class="br" colspan="2">Percepción Impuesto PAIS (calculado sobre servicios en el exterior)</td>
					<td align="right"><?=number_format($impuesto_pais, 2);?></td>
				</tr>
				<? endif; ?>
				<tr>
					<td class="br" colspan="2"></td>
					<td></td>
				</tr>
				<tr>
					<td class="br <?=(isset($tipo_cambio) && $tipo_cambio)?'':'bb';?> condiciones" colspan="2" valign="top">
						<strong>Condiciones:</strong><br/>
						<?=strip_tags($condiciones,'<br>');?>
						<br><br>Para mayor información consultar los términos y condiciones completos en nuestra web.
					</td>
					<td class="<?=(isset($tipo_cambio) && $tipo_cambio)?'':'bb';?>"></td>
				</tr>
				<? if(isset($tipo_cambio) && $tipo_cambio): ?>
				<tr>
					<td class="br bb" colspan="2" valign="top" style="font-size:8px;">
						A efectos contables e impositivos el tipo de cambio de esta factura es $ <?=$tipo_cambio;?>.
						El valor en dólares de esta factura es de USD <?=number_format($total/$tipo_cambio,2,'.',',');?>
					</td>
					<td class="bb"></td>
				</tr>						
				<? endif; ?>

				<?/* if (isset($intereses) && $intereses > 0){ 
					$total = $total+$intereses;
				} */?>
				<tr>
					<!--<td>Recibimos la suma de $<?=number_format($total, 2);?></td>
					cambiamos la forma en que se muestra este total debido al a percepcion 15-11-17-->
					<td>Recibimos la suma de $<?=number_format($total+((isset($percepcion_3825) && $percepcion_3825)?$percepcion_3825:0), 2);?></td>
					<td align="right" class="br" width="10%"><strong>Subtotal:</strong></td>
					<!--
					<td align="right"><?=number_format($total-((isset($percepcion_3825) && $percepcion_3825)?$percepcion_3825:@$percepcion_3450), 2);?></td>
					cambiamos la forma en que se muestra este total debido al a percepcion 15-11-17-->
					<td align="right"><?=number_format($total, 2);?></td>
				</tr>
				<tr>
					<td>Firma:
						<span style="padding-left:170px; font-size:11px; font-weight:normal;">Aclaración:</span>
					</td>
					<td align="right" class="br">
						<? if(isset($percepcion_3825) && $percepcion_3825){ ?>
							<strong>Percep. RG 3825 5%</strong>
						<? } ?>
					</td>
					<td align="right">
						<? if(isset($percepcion_3825) && $percepcion_3825){ ?>
							<?=number_format($percepcion_3825, 2);?>
						<? } ?>
					</td>
				</tr>
				<tr>
					<td>
						<? if (!empty($cae)): ?>
						<strong>C.A.E. Nº <?=$cae;?> Fecha Vto. CAE:<?=$fvto_cae;?></strong>
						<? endif; ?>
					</td>
					<td align="right" class="br"><strong>Total:</strong></td>
					<!--<td align="right"><?=number_format($total, 2);?></td>
					cambiamos la forma en que se muestra este total debido al a percepcion 15-11-17-->
					<td align="right"><?=number_format($total+((isset($percepcion_3825) && $percepcion_3825)?$percepcion_3825:$percepcion_3450), 2);?></td>
				</tr>				
			</table>
		</td>
	</tr>
</table>
<div class="defensa"><?=isset($pie_factura)?$pie_factura:'';?></div>

<img alt="QR AFIP" width="120" src="data:image/png;base64,<?= base64_encode($qr) ?>" />

</body>

</html>