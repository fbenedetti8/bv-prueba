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
</style>
</head>

<body>

<table cellspacing="0" style="width:800px; border-collapse:collapse;">
	<tr>
		<td colspan="5" style="text-align:center;font-size:16px;font-weight:bold;">Rooming del viaje</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" style="text-align:center;font-size:16px;font-weight:bold;"><?=$paquete->nombre;?></td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" style="text-align:center;font-size:16px;font-weight:bold;">Código <?=$paquete->codigo;?></td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<? if(isset($coordinadores) && count($coordinadores)): ?>
		<? $i=0;
		foreach ($coordinadores as $c): $i++; ?>
			<tr>
				<td colspan="5" style="text-align:center;font-size:16px;font-weight:bold;">Coordinador: <?=$i;?> <?=$c->nombre.' '.$c->apellido.' - '.$c->telefono;?></td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
		<? endforeach; ?>
	<? endif; ?>

	<? foreach($room as $k=>$v) { 
		$rooming = $v['rooming'];
		$aloj_ant = ''; $total_pasajeros = 0;
		
		foreach($rooming as $pr) { 
			if($aloj_ant != $pr->alojamiento){ ?>
			
				<? if($aloj_ant != ''){ ?>
					<tr>
						<td style="text-align:center;font-size:16px;font-weight:bold;">Total</td>
						<td>&nbsp;</td>
						<td style="text-align:center;font-size:16px;font-weight:bold;"><?=$total_pasajeros;?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr style="height:40px;">
						<td colspan="5">&nbsp;</td>
					</tr>
				<? $total_pasajeros = 0;
				} 
				
				$aloj_ant = $pr->alojamiento; ?>
			
				<tr>
					<td colspan="5" style="text-align:left;font-size:16px;font-weight:bold;"><?=$pr->alojamiento;?></td>
				</tr>
				<tr>
					<td colspan="5" >&nbsp;</td>
				</tr>
				<tr>
					<td style="font-weight:bold;text-align:center;font-size:14px; width:80px;">Nro.</td>
					<td style="font-weight:bold;text-align:center;font-size:14px; width:100px;">Habitación</td>
					<td style="font-weight:bold;text-align:center;font-size:14px; width:80px;">Cant Pax.</td>
					<td style="font-weight:bold;text-align:center;font-size:14px; width:260px;">Pasajeros</td>
					<td style="font-weight:bold;text-align:center;font-size:14px; width:150px;">Observaciones</td>
				</tr>
			
			<? } ?>
			
				<tr style="height:80px; vertical-align:middle;">
					<td style="text-align:center;font-size:14px;"><?=$pr->nro_habitacion;?></td>
					<td style="text-align:center;font-size:14px;"><?=$pr->habitacion;?></td>
					<td style="text-align:center;font-size:14px;"><?=($pr->habitacion_id != 99)?$pr->pax:'-';?></td>
					<td style="width:300px;"><?=@$pr->lista_pax;?></td>
					<td style="width:150px;"><?=$pr->observaciones;?></td>
				</tr>
			
				<? if($pr->habitacion_id != 99){
					$total_pasajeros += $pr->pax; 
				} ?>
			
		<? } ?>

		
	<tr>
		<td style="text-align:center;font-size:16px;font-weight:bold;">Total</td>
		<td>&nbsp;</td>
		<td style="text-align:center;font-size:16px;font-weight:bold;"><?=$total_pasajeros;?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr style="height:40px;">
		<td colspan="5">&nbsp;</td>
	</tr>
	
	<? } ?>
	
</table>

</body>

</html>