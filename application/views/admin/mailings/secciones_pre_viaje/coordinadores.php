 <!-- coordinadores -->
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
		<th colspan="4" width="601" style="background:#dcab0a; padding:2px 15px 2px 15px;">
		<font color="#fff" face="Trebuchet MS" style="font-size:16px; line-height:35px; font-weight:bold; text-align:left; display:block;">
		<img src="<?=base_url();?>media/frontend/mailings/images/ic_coordina.png" border="0" style="display:block; float:left; margin-right:10px;">COORDINADORES DE BUENAS VIBRAS
		</font>
		</th>
	  </tr>
	  <tr>
		<th colspan="4" width="601" style="padding:10px 15px 2px 15px;">
			<font color="#343434" face="Trebuchet MS" style="font-size:14px; font-weight:bold; text-align:left; display:block; text-transform:uppercase;">
				<? foreach ($paquete->coordinadores as $c) { ?>
					<?=$c->apellido.', '.$c->nombre.' - ';?>
				<? } ?>

			<br>  
			Celular:&nbsp;
			<? foreach ($paquete->celulares as $c) { ?>
					<?=$c->telefono.' - ';?>
				<? } ?>
			</font>
			<br>
			<font color="#343434" face="Trebuchet MS" style="font-size:12px; font-weight:100; text-align:left; line-height:23px; display:block;">
			AGENDEN NUESTRO CELULAR:<br> 
			No env&#237;es sms en el momento de la salida, es muy probable que no se pueda responder si todos hacen lo mismo al mismo tiempo. En caso de ser necesario, llama directamente.<br> 
			Estos tel&#233;fonos van a estar disponibles solo a partir del d&#237;a de salida ya que los coordinadores se encuentran viajando con otros grupos y ellos no cuentan con informaci&#243;n de tipo administrativa, si necesitas consultarnos algo antes podes llamar a nuestra oficina.
			</font>
		</th>
	  </tr>
	</table>
	<!-- end coordinadores -->