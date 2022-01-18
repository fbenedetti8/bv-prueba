<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
html { line-height:19px; }
body * { line-height:19px; }
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
.condiciones { height:320px; overflow:hidden; font-size:8px; line-height:11px; padding-top:0; padding-bottom:0; }
<? } else { ?>
.condiciones { height:340px; overflow:hidden; font-size:8px; line-height:11px; padding-top:0; padding-bottom:0; }
<? } ?>
.defensa { text-align:center; font-size:10px; font-family:sans-serif; padding:0; margin:0; }
.logo { width:270px; margin-left:10px; }
</style>
</head>

<body>

<table cellspacing="0" style="width:900px; border-collapse:collapse;">
	<tr>
		<td colspan="7" style="text-align:center;font-size:16px;font-weight:bold;">MINISTERIO DEL INTERIOR - DIRECCIÓN NACIONAL DE MIGRACIONES - ANEXO I Resolución 2997/85</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" style="text-align:center;font-size:16px;font-weight:bold;background-color:#bbb;">MANIFIESTO DE TRIPULANTES Y PASAJEROS- EXCLUSIVO CORREDOR TURÍSTICO</td>
		<td colspan="2" style="text-align:center;font-size:16px;font-weight:bold;background-color:#bbb;">N° DE MANIFIESTO:</td>
	</tr>
	<tr>
		<td colspan="3" style="font-weight:bold;">DEL:</td>
		<td colspan="2" style="font-weight:bold;">Matrícula:</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="2" style="font-weight:bold;border-right:0;">MEDIO DE TRANSPORTE:</td>
		<td style="border-left:0;">ómnibus</td>
		<td style="font-weight:bold;border-right:0;">Nación:</td>
		<td style="border-left:0;">Argentino</td>
		<td colspan="2" style="border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;">USO</td>
	</tr>
	<tr>
		<td colspan="2" style="font-weight:bold;border-bottom:0;">CON FECHA</td>
		<td colspan="2" style="font-weight:bold;border-bottom:0;">POR</td>
		<td style="text-align:right;"><strong>CON</strong> <?=count($pasajeros);?> PASAJEROS</td><!-- VARIABLE -->
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;">OFICIAL</td>
	</tr>
	<tr>
		<td colspan="2" style="border-top:0;"></td>
		<td colspan="2" style="font-weight:bold;border-top:0;"><?=isset($destino->lugar_de_paso)?$destino->lugar_de_paso:'';?></td><!-- VARIABLE -->
		<td style="text-align:right;">02 TRIPULANTES</td>
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td colspan="5" style="font-weight:bold;border-bottom:0;">CONSIGNADO A:</td>
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td style="border-top:0;border-right:0;"></td>
		<td colspan="4" style="font-weight:bold;border-left:0;border-top:0;">Buenas Vibras Viajes - Leg 14641 30-71160493-2</td>
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td colspan="5" style="background-color:#bbb;text-align:center;font-weight:bold;">TRIPULANTES</td>
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">Apellido y Nombre - Tipo y N° de Documento</td>
		<td colspan="3" style="text-align:center;font-weight:bold;">Apellido y Nombre - Tipo y N° de Documento</td>
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td><!-- VARIABLE -->
		<td colspan="3">&nbsp;</td><!-- VARIABLE -->
		<td colspan="2" style="border-top:0;border-bottom:0;font-weight:bold;text-align:center;background-color:#bbb;"></td>
	</tr>
	<tr>
		<td colspan="5" style="background-color:#bbb;text-align:center;font-weight:bold;">PASAJEROS</td>
		<td colspan="2" style="border-top:0;font-weight:bold;text-align:center;background-color:#bbb;">CALIFIC. MIGRATORIA</td>
	</tr>
	<tr>
		<td style="font-weight:bold;">N°</td>
		<td style="font-weight:bold;text-align:center;">Apellido y Nombre</td>
		<td style="font-weight:bold;text-align:center;">Fecha de Nacimiento</td>
		<td style="font-weight:bold;text-align:center;">Nacionalidad</td>
		<td style="font-weight:bold;text-align:center;">Tipo y N° de Documento</td>
		<td style="width:100px;font-weight:bold;text-align:center;">*SIN VISA</td>
		<td style="width:100px;font-weight:bold;text-align:center;">*CON VISA</td>
	</tr>
	<? $i=1;
	foreach($pasajeros as $p){ ?>
		<tr>
			<td style="text-align:right;"><?=$i++;?></td>
			<td style="text-align:left;"><?=$p->apellido.', '.$p->nombre;?></td>
			<td style="text-align:center;"><?=date('d/m/Y',strtotime($p->fechaNacimiento));?></td>
			<td style="text-align:center;"><?=$p->nacionalidad;?></td>
			<td style="text-align:center;"><?=$p->dniTipo.' '.$p->dni;?></td>
			<td style="text-align:center;"></td>
			<td style="text-align:center;"></td>
		</tr>
	<? } ?>
	<? $cant_c = 0;
	foreach($coordinadores as $c){ ?>
		<tr>
			<td style="text-align:right;"><?=$i++;?></td>
			<td style="text-align:left;"><?=$c->{'apellido'}.', '.$c->{'nombre'};?></td>
				<td style="text-align:center;"><?=$c->{'fechaNacimiento'}!='0000-00-00'?formato_fecha($c->{'fechaNacimiento'},$hour=false,$div_sep='-',$div_ret='/'):'';?></td>
				<td style="text-align:center;"><?=$c->{'nacionalidad'};?></td>
				<td style="text-align:center;"><?=$c->{'dniTipo'}.' '.$c->{'dni'};?></td>
				<td style="text-align:center;"></td>
				<td style="text-align:center;"></td>
		</tr>
	<? } ?>
	<!-- <? foreach($coordinadores as $c){ ?>
	<tr>
		<td style="text-align:right;"><?=$i++;?></td>
		<td style="text-align:left;"></td>
		<td style="text-align:center;"></td>
		<td style="text-align:center;"></td>
		<td style="text-align:center;"></td>
		<td style="text-align:center;"></td>
		<td style="text-align:center;"></td>
	</tr>
	<? } ?> -->
	<tr>
		<td colspan="2" style="font-weight:bold;">Responsable del Transporte</td>
		<td style="font-weight:bold;text-align:center;">FIRMA SUPERVISOR</td>
		<td style="font-weight:bold;text-align:center;">OPERADOR</td>
		<td style="font-weight:bold;text-align:center;">FIRMA Y SELLO INSPECTOR</td>
		<td colspan="2" style="font-weight:bold;text-align:center;">SELLO DE CONTROL</td>
	</tr>
	<tr style="height:100px;">
		<td colspan="2"></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="2" style="font-weight:bold;">Responsable del Transporte</td>
		<td style="font-weight:bold;text-align:center;">FIRMA SUPERVISOR</td>
		<td style="font-weight:bold;text-align:center;">OPERADOR</td>
		<td style="font-weight:bold;text-align:center;">FIRMA Y SELLO INSPECTOR</td>
		<td colspan="2" style="font-weight:bold;text-align:center;">SELLO DE CONTROL</td>
	</tr>
	<tr style="height:100px;">
		<td colspan="2"></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2"></td>
	</tr>
	
</table>

</body>

</html>