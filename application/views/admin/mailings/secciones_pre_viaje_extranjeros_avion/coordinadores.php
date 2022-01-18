 <!-- coordinadores -->
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
		<th colspan="4" width="601" style="background:#dcab0a; padding:2px 15px 2px 15px;">
		<font color="#fff" face="Trebuchet MS" style="font-size:16px; line-height:35px; font-weight:bold; text-align:left; display:block;">
		<img src="<?=base_url();?>media/frontend/mailings/images/ic_coordina.png" border="0" style="display:block; float:left; margin-right:10px;">COORDINATORS OF BUENAS VIBRAS
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
			Móvil:&nbsp;
			<? foreach ($paquete->celulares as $c) { ?>
					<?=$c->telefono.' - ';?>
				<? } ?>
			</font>
			<br>
			<font color="#343434" face="Trebuchet MS" style="font-size:12px; font-weight:100; text-align:left; line-height:23px; display:block;">
			Do not send SMS before the departure, cause its highly likely that they wont be able to answer to all of you doing the same. In case you need, call them.<br> 
			This mobiles are gonna be available only the day of departure since our coordinators are doing other trips or activities and dont have all the info that you may need.
			</font>
		</th>
	  </tr>
	</table>
	<!-- end coordinadores -->