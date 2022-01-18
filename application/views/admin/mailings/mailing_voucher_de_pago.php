<style type="text/css">
body {
	margin-top: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>

<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="8" cellpadding="0">
      <tr>
        <? if( isset($agencia) and isset($agencia->id) and $agencia->id > 1 and file_exists('./uploads/agencias/'.$agencia->id.'/'.$agencia->logo) ){ ?>
			<td>
				<img src="<?=base_url();?>uploads/agencias/<?=$agencia->id;?>/<?=$agencia->logo;?>" border="0px">
			</td>
		<? } else {?>
			<td>
				<a href="http://www.buenas-vibras.com.ar/" target="_blank">
					<img src="<?=base_url();?>media/frontend/mailings/images/logo_buenas_vibras_viajes.jpg" alt="Buenas Vibras Viajes" width="230" height="56" border="0" title="Buenas Vibras Viajes" style="display:block;">
				</a>
			</td>
			<td align="right"><a href="http://www.buenas-vibras.com.ar/" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/web_buenas_vibras_viajes.jpg" alt="www.buenas-vibras.com.ar" width="219" height="15" border="0" title="Ingres&aacute; a nuestra web" style="display:block;"></a></td>
		<? } ?>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#d86344">
      <tr>
        <td height="100"><table width="100%" border="0" cellspacing="8" cellpadding="0">
          <tr>
            <td>
            <font color="#FFFFFF" face="Trebuchet MS" style="font-size:32px; text-transform:uppercase; line-height:32px; display:block; margin-left:15px; margin-top:15px; text-align:left;"> <b>voucher de viaje</b><br>
            </font>
            <font color="#262626" face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; line-height:18px; display:block; margin-left:15px; text-align:left;"><b> Contrato de servicio tur&#237;stico <?=$reserva->codigo;?></b></font></b><br>
            </font>     
            </td>
          </tr>
        </table></td>
        <td width="10%" align="right" valign="bottom"><img src="<?=base_url();?>media/frontend/mailings/images/eme_b_v.png" alt="Pancho Buenas Vibras" width="140" height="95" border="0" style="display:block;" title="Buenas Vibras"></td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th colspan="4" valign="top"><font color="#262626" face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; line-height:0px;"><img src="<?=base_url();?>media/frontend/mailings/images/separa_colores.jpg" width="601" height="3" alt="Div Header" style="display:block; margin-bottom:15px;"></font></th>
  </tr>
  
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="8" cellpadding="0">
      <tr>
        <td width="7%" rowspan="3">&nbsp;</td>        
        <td>        
		<font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:18px; text-align:left;">
         Te informamos que ya registramos la totalidad del pago correspondiente a la reserva del viaje que detallamos a continuaci&#243;n.
		 <br><br>
         <? if(isset($reserva->en_avion) && $reserva->en_avion){ ?>
			El día de la salida deberás presentarle al Coordinador en el Aeropuerto: este voucher de viaje (no es necesario que lo imprimas, lo podés mostrar desde tu celu) y el último DNI o Pasaporte. Una semana antes, te va a llegar un mail pre viaje con todas las recomendaciones.
		 <? } else { ?>
			El d&#237;a de la salida deber&#225;s presentar este voucher acompa&#241;ado con tu dni para poder subir al micro, <font color="#d86344">podés mostrarlo directamente desde tu celular a los coordinadores a cargo.</font>
		 <? } ?>
        </font>                         
        </td>
        <td width="7%" rowspan="3">&nbsp;</td>            
      </tr>      
    </table></th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Pasajero:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$usuario->apellido.", ".$usuario->nombre;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS"><?=$usuario->dniTipo;?>:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$usuario->dni;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">N&#250;mero de Reserva:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->codigo;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Viaje:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->titulo.' - '.$reserva->subtitulo;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Salida:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=change_format($reserva->fechaSalida);?> (<?=date('H:i',strtotime($reserva->horaSalida));?>)</font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Regreso:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=change_format($reserva->fechaRegreso);?> (<?=date('H:i',strtotime($reserva->horaRegreso));?>)</font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Lugar de salida y regreso:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$sucursal->nombre.' - '.$reserva->lugarSalida;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Hora:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=date('H:i',strtotime($reserva->horaSalida));?>hs</font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Adicionales:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <? $adicionales = "";
			for($a=1;$a<=6;$a++){ 
				if($reserva->{'adicional'.$a} != ""){
					$adicionales .= $reserva->{'adicional'.$a}.", ";
				} 
			}
			if($adicionales!=""){
				$adicionales[strlen($adicionales)-2] = "."; 
				$adicionales[strlen($adicionales)-1] = ""; ?>
				<font face="Trebuchet MS"><?=$adicionales;?></font>
			<? }
			else { ?>
				<font face="Trebuchet MS">Ninguno</font>
			<? } ?>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>  
	<table width="100%" border="0" cellspacing="2" cellpadding="0">
       <tr>
         <td width="74" rowspan="4">&nbsp;</td>
         <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;"><font face="Trebuchet MS">Transporte:</font></td>
         
         <td width="290" rowspan="4" style="color:#262626; font-size:14px; font-weight:bold; text-align:left;"><font face="Trebuchet MS">
			<? if(isset($reserva->en_avion) && $reserva->en_avion){ ?>
				<img src="<?=base_url();?>media/frontend/mailings/images/icono_avion.jpg" alt="Avion" width="25" height="16" align="top"> AVIÓN
			<? } else { ?>
				<img src="<?=base_url();?>media/frontend/mailings/images/icono_micro.jpg" alt="Micro" width="25" height="16" align="top"> MICRO
			<? } ?>
			</font>
		 </td>
         <td width="74" rowspan="4">&nbsp;</td>
       </tr>
    </table>	
   </th>
   
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Tipo de comida:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->menu;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="4" width="601" style="background:#6a6b6b; height:1px;" ></th>
  </tr>
 
  <!--
  <tr>
    <th colspan="4" width="601" style="padding:15px 0px 15px 250px;">    
    <a style="color:#262626; text-decoration:none;" href="#" onclick="javascript: window.print();"><img src="<?=base_url();?>media/frontend/mailings/images/ic_print.jpg" border="0" style="display:block; float:left;"><font face="Trebuchet MS" style="font-size:18px; line-height:18px; font-weight:bold; text-align:center; display:block; text-transform:uppercase; float:left; margin-left:10px;">imprimir</font></a> 
    </th>    
  </tr>
  -->
  
  <tr>    
    <th colspan="4" width="601">
    <font face="Trebuchet MS" style="color:#262626; font-size:12px; line-height:20px; text-align:left; display:block; text-align:left;">
    Ante cualquier duda, consulta o diferencia, podes comunicarte con nosotros por los medios indicados m&#225;s abajo o respondiendo directamente este mail. 
    <br>
    <font face="Trebuchet MS" style="color:#262626; font-size:14px; line-height:20px; font-weight:bold; text-align:left; display:block;">
    Gracias!!
    </font> 
    </font>
    </th>
  </tr>
  
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>  
  
  <tr>
    <th colspan="4" width="601" style="background:#195d9d; height:2px;"></th>
  </tr>
  
  <tr>
   <th width="4%"></th>
   <th><font face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; font-weight:bold; line-height:25px; text-align:left; display:block; margin-top:15px;"><a style="color:#195d9d; padding-left:10px; text-decoration:underline;" href="<?=base_url();?>destinos/terminos_y_condiciones/<?=$reserva->paquete_id;?>"><img src="<?=base_url();?>media/frontend/mailings/images/ic_atencion.jpg" border="0" style="display:block; float:left;">NO DEJES DE LEER T&#233;RMINOS Y CONDICIONES (Click aqu&#205;)</a></font></th>
   <th width="4%"></th>
  </tr>
  
  <tr>
    <th colspan="4" valign="top"><img src="<?=base_url();?>media/frontend/mailings/images/separa_colores.jpg" width="601" height="3" style="margin-top:25px; display:block;"></th>
  </tr>
  
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="15" cellpadding="0" bgcolor="#F7F7F7">
      <tr>
        <td height="19"><table width="100%" border="0" cellspacing="8" cellpadding="0" >
          <tr>
            <td width="9%"><a href="mailto:info@buenas-vibras.com.ar" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/info_buenasvibras.jpg" alt="Mail Buenas Vibras" width="37" height="33" border="0"></a></td>
            <td width="39%"><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> ESCRIBINOS</b></font><font color="#00DA7B" face="Trebuchet MS" style="font-size:14px; line-height:16px;"> <b> <br>
              <a style="color:#00DA7B; text-decoration:none; text-align:left;" href="mailto:info@buenas-vibras.com.ar" target="_blank">info@buenas-vibras.com.ar</a></b></font></td>
            <td width="9%"><a href="http://www.facebook.com/buenas.vibras" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/icono_facebook.jpg" alt="Mail Buenas Vibras" width="33" height="32" border="0"></a></td>
            <td width="58%"><font color="#21579F" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> FACEBOOK</b></font><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px;"> <b> <br>
              <a style="color:#262626; text-decoration:none; text-align:left;" href="http://www.facebook.com/buenas.vibras" target="_blank">www.facebook.com/buenas.vibras</a></b></font></td>
          </tr>
          <tr>
            <td><img src="<?=base_url();?>media/frontend/mailings/images/icono_telefono.jpg" alt="Mail Buenas Vibras" width="40" height="27" border="0"></td>
            <td><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> <?=$reserva->sucursal_nombre;?></b></font><font color="#FD7247" face="Trebuchet MS" style="font-size:14px; line-height:16px;"> <b> <br>
              <?=$reserva->sucursal_telefono;?> </b></font></td>
            <td><a href="http://www.twitter.com/buenas_vibras_v" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/icono_twitter.jpg" alt="Mail Buenas Vibras" width="33" height="33" border="0"></a></td>
            <td><font color="#00ABE3" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> TWITTER</b></font><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px;"> <b> <br>
              <a style="color:#262626; text-decoration:none; text-align:left;" href="http://www.twitter.com/buenas_vibras_v" target="_blank">www.twitter.com/buenas_vibras_v</a></b></font></td>
          </tr>
          <tr>
            <td><img src="<?=base_url();?>media/frontend/mailings/images/ic_ubicacion.jpg" alt="Mail Buenas Vibras" width="33" height="34" border="0"></td>
            <td><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b>
              <?=$reserva->sucursal_direccion;?></b></font></td>
            <td><a href="http://www.instagram.com/buenasvibrasviajes" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/icono_instagram.jpg" alt="Mail Buenas Vibras" width="32" height="32" border="0"></a></td>
            <td><font color="#A5694F" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> INSTAGRAM</b></font><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px;"> <b> <br>
              <a style="color:#262626; text-decoration:none; text-align:left;" href="http://www.instagram.com/buenasvibrasviajes" target="_blank">www.instagram.com/buenasvibrasviajes</a></b></font></td>
          </tr>
        </table></td>
        </tr>
    </table></th>
  </tr>
  <tr>
    <th colspan="4" bgcolor="#E6E6E6"><font color="#797979" face="Trebuchet MS" style="font-size:16px; line-height:25px;"> <b>Buenas Vibras Viajes</b> - EVT Leg 14.641</font></th>
  </tr>
  <tr>
    <th height="50" colspan="4" bgcolor="#FFFFFF"><font color="#333333" face="Trebuchet MS" style="font-size:11px; text-transform:uppercase;">SI NO QUER&Eacute;S RECIBIR M&Aacute;S INFORMACI&Oacute;N DE NUESTRA EMPRESA, HAC&Eacute; <a href="http://web.mailseguros.com.ar/remover.asp?il=buenasvibras" style="color:#0199d7;">CLICK AQU&#237;</a><br /> 
          Y SER&Aacute;S REMOVIDO EN FORMA AUTOM&Aacute;TICA DE LA LISTA DE ENV&Iacute;OS.</font></th>
  </tr>
</table>
</body>
</html>
