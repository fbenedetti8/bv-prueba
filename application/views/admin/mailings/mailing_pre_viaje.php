

<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  
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
					<font  style="font-size:16px; line-height:18px; text-align:left;">
					Antes que nada... &#161;Gracias por habernos elegido&#33;  :)<br>
					</font>
					<font  style="font-size:12px; line-height:18px; text-align:left;">
					Te contamos que est&#225; llegando el momento del viaje as&#237; que vamos a ir comunic&#225;ndonos por este medio, 
					envi&#225;ndote toda la informaci&#243;n a tener en cuenta.</font><br>
					<font  style="font-size:12px; font-weight:bold; line-height:18px;">
					**** Le&#233; todo y confirm&#225; entre tus amigos que todos lo hayan recibido y le&#237;do ****
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
		 <th colspan="4" width="601" style="background:#fff; padding:0;">
		<font  style="font-size:16px; line-height:21px; font-weight:bold; text-align:left; display:block;">
		<img src="<?=base_url();?>media/frontend/mailings/images/ic_banderita.png" border="0" style="display:block; float:left; margin-right:10px; text-align:left;">SALIDA <?=isset($paquete->fecha_inicio)?strtoupper($paquete->fecha_inicio):'';?>
		</font><br>
		<font  style="font-size:16px; text-align:left; display:block; font-weight:100;">
			<? for($i=1;$i<=6;$i++){
				if(isset($paquete->{'lugarSalida'.$i}) && $paquete->{'lugarSalida'.$i} != ''){ ?>
					<font style="font-weight:bold; text-align:left;"><?=$paquete->{'lugarSalida'.$i.'_horario'};?> hs.</font> en <?=$paquete->{'lugarSalida'.$i};?>, <?=$paquete->{'sucursal'.$i};?> <br>
				<? } 
			} ?>
			<font style="font-weight:bold; font-size:12px; text-align:left;">* IMPORTANTE!:</font> <font style="color:#262626; font-size:12px;">Hay que llegar con media hora de anticipaci&#243;n as&#237; podemos salir a horario.</font>
		</font>
		</th>
	  </tr>
	  </tbody>
	  </table>
	</td>
  </tr>
  
  <tr>
    <th colspan="4" width="601" >&nbsp;</th>
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
			Nos vemos en unos d&#237;as !!!
			</font>
			<font  style="font-size:12px; font-weight:100; text-align:left; line-height:23px; display:block;">
			El Staff de BUENAS VIBRAS VIAJES
			</font>
		</th>
	  </tr>
	</table>
</table>
