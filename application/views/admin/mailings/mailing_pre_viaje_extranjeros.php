
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	 <tbody><tr>
				 <th colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: left; ">
				  <tr>
					<td height="100"><table width="100%" border="0" cellspacing="8" cellpadding="0">
					  <tr>
						<td>
						<font  style="font-size:28px; text-transform:uppercase; line-height:32px; display:block; margin-left:0; margin-top:15px; text-align:left;"> <b>pre-trip</b><br>
						</font>
						<font   style="font-size:14px; text-transform:uppercase; line-height:18px; display:block; margin-left:0; text-align:left;"><b> ALL THE INFO YOU NEED TO KNOW</b></font></b><br>
						</font>     
						</td>
					  </tr>
					</table></td>
					<td width="10%" align="right" valign="bottom"></td>
				  </tr>
				</table>
			  </th>
		</tr></tbody> 
	</table> 
	</td> 			  
  </tr>
 
  <tr>
    <th colspan="4" width="601" >&nbsp;</th>
  </tr>
  
  <tr>
	<td>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	 <tbody><tr>
				<th colspan="4"><table width="100%" border="0" cellspacing="8" cellpadding="0" style="text-align: left;">
				  <tr>     
					<td>
					<font   style="font-size:16px; line-height:18px; text-align:left;">
					First of all... Thanks for choosing us!<br>
					</font>
					<font   style="font-size:12px; line-height:18px; text-align:left;">
					We are almost on the bus, so all the info you may need, will be send it over here.</font><br>
					<font   style="font-size:12px; font-weight:bold; line-height:18px;">
					**** Please, <a style="  text-decoration:underline; text-align:left;" href="#">read everything</a> confirm that your friends have recieved it and are on the same bus. ****
					</font>                 
					</td>         
				  </tr>      
				</table>
			  </th>
		</tr></tbody> 
	</table> 
	</td> 
  </tr> 
  
  <tr>
    <th colspan="4" width="601" >&nbsp;</th>
  </tr>
 
  <tr>
    <td>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	 <tbody><tr>
		 <th colspan="4" width="601" style="background:#ffc119; padding:15px 15px 15px 15px;">
		<font   style="font-size:16px; line-height:21px; font-weight:bold; text-align:left; display:block;">
		<img src="<?=base_url();?>media/frontend/mailings/images/ic_banderita.png" border="0" style="display:block; float:left; margin-right:10px; text-align:left;">DEPARTURE <?=isset($paquete->fechaSalida)?strtoupper($paquete->fechaSalida):'';?>
		</font><br>
		<font   style="font-size:16px; text-align:left; display:block; font-weight:100;">
			<? for($i=1;$i<=6;$i++){
				if(isset($paquete->{'lugarSalida'.$i}) && $paquete->{'lugarSalida'.$i} != ''){ ?>
					<font style="font-weight:bold; text-align:left;"><?=$paquete->{'lugarSalida'.$i.'_horario'};?> hs.</font> on <?=$paquete->{'lugarSalida'.$i};?>, <?=$paquete->{'sucursal'.$i};?> <br>
				<? } 
			} ?>
			<font style="font-weight:bold; font-size:12px; text-align:left;">* IMPORTANT!:</font> <font style="font-size:12px;">You have to be half an hour earlier so we depart on time.</font>
		</font>
		</th>
	  </tr>
	  </tbody>
	  </table>
	</td>
  </tr>
  
  <tr>
    <th colspan="4" width="601" style="background:#fff; height:2px;">
    </th>
  </tr>
  
  
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($sobre_lugares_salida) && $sobre_lugares_salida != ''){ ?>		
		<?=$sobre_lugares_salida;?> 
  <? } ?>
	</th>
	</tr>
  
  <tr>
    <th colspan="4" width="601" style="background:#fff; height:15px;">
    </th>
  </tr>
  
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($que_hay_que_llevar) && $que_hay_que_llevar != ''){ ?>
	 <?=$que_hay_que_llevar;?>  
  <? } ?>
	</th>
	</tr>
  
  <tr>
    <th colspan="4" width="601" style="background:#fff; height:15px;">
    </th>
  </tr>
  
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($en_el_micro) && $en_el_micro != ''){ ?>
	  <?=$en_el_micro;?>  
  <? } ?>
	</th>
	</tr>
  
  
  <tr>
    <th colspan="4" width="601" style="background:#fff; height:15px;">
    </th>
  </tr>
  
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($sobre_el_viaje) && $sobre_el_viaje != ''){ ?>
	  <?=$sobre_el_viaje;?>  
  <? } ?>
	</th>
	</tr>
  
  
   <tr>
    <th colspan="4" width="601" style="background:#fff; height:15px;">
    </th>
  </tr>
  
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($coordinadores) && $coordinadores != ''){ ?>
		<?=$coordinadores;?> 
  <? } ?>
	</th>
	</tr>
  
  <tr>
    <th colspan="4" width="601" style="background:#fff; height:15px;">
    </th>
  </tr>
  
 
  <tr>
    <th colspan="4" style="font-weight: initial;">
  <? if(isset($info_adicional) && $info_adicional != ''){ ?>
	<?=$info_adicional;?>  
  <? } ?>
	</th>
	</tr>
  
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<th colspan="4" width="601" style="padding:10px 15px 2px 15px;">
			<font  style="font-size:16px; font-weight:bold; text-align:left; line-height:23px; display:block;">
			See you on the next days!!!
			</font>
			<font  style="font-size:12px; font-weight:100; text-align:left; line-height:23px; display:block;">
			The Staff of BUENAS VIBRAS VIAJES
			</font>
		</th>
	  </tr>
	  
	</table>
</table>