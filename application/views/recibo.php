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
<? } else { ?>
.condiciones { height:270px; overflow:hidden; font-size:8px; line-height:11px; padding-top:0; padding-bottom:0; }
<? } ?>
.defensa { text-align:center; font-size:10px; font-family:sans-serif; padding:0; margin:0; }
.logo { width:270px; margin-left:10px; }
</style>
</head>

<body>

<table width="100%" cellspacing="0">
	<tr>
		<td width="50%">
			<img src="<?=base_url();?>media/assets/imgs/imagenes/logo_factura.jpg" width="270" class="logo" />
			<br/>
			<br/>
			Buenas Vibras SRL<br/>
			<?=isset($sucursal_direccion)?$sucursal_direccion:'';?><br/>
			<?=isset($sucursal_nombre)?$sucursal_nombre:'';?><br/>
			<br/>
			IVA Responsable Inscripto - EVT Leg. 14641
			
		</td>
		<td width="50%" valign="bottom">
			<div align="right" class="nro">Recibo N° <?=str_pad($numero_factura, 8, '0', STR_PAD_LEFT);?></div>
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
		<td colspan="2">
			<strong>Recibimos la suma de</strong> <?=$pago_usd?'USD':'ARS';?> <?=number_format($total+((isset($impuesto_pais) && $impuesto_pais)?$impuesto_pais:0), 2);?>
			<br><br><strong>En concepto de</strong> <?=$concepto;?>
			
			<? if(isset($tipo_cambio) && $tipo_cambio): ?>
				<br><br><span style="font-size:10px;">A efectos contables e impositivos el tipo de cambio de este recibo es $ <?=$tipo_cambio;?></span>
			<? endif; ?>
		</td>
	</tr>
	<tr>
		<td width="70%">
			<strong>Firma y Aclaración:</strong>
		</td>
		<td width="30%">
			<strong>Total:</strong>
			<?=$pago_usd?'USD':'ARS';?> <?=number_format($total+((isset($impuesto_pais) && $impuesto_pais)?$impuesto_pais:0), 2);?>
		</td>
	</tr>
</table>
<div class="defensa"><?=isset($pie_factura)?$pie_factura:'';?></div>

<img alt="QR AFIP" width="120" src="data:image/png;base64,<?= base64_encode($qr) ?>" />

</body>

</html>