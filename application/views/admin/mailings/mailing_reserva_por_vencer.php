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
    <th colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#92c665">
      <tr>
        <td height="100"><table width="100%" border="0" cellspacing="8" cellpadding="0">
          <tr>
            <td>
            <font color="#FFFFFF" face="Trebuchet MS" style="font-size:28px; text-transform:uppercase; line-height:32px; display:block; margin-left:15px; margin-top:15px; text-align:left;"> <b>tu reserva est&#225; por vencer</b><br>
            </font>
            <font color="#262626" face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; line-height:18px; display:block; margin-left:15px; text-align:left;"><b> Contrato de servicio tur&#237;stico <?=$reserva->codigo;?></b></font></b><br>
            </font>     
            </td>
          </tr>
        </table></td>
        <td width="10%" align="right" valign="bottom"><img src="<?=base_url();?>media/frontend/mailings/images/eme_b_v.png" alt="Pancho Buenas Vibras" width="140" height="95" border="0" title="Buenas Vibras"></td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th colspan="4" valign="top"><font color="#262626" face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; line-height:0px;"><img src="<?=base_url();?>media/frontend/mailings/images/separa_colores.jpg" width="601" height="3" alt="Div Header"></font></th>
  </tr>
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="8" cellpadding="0" style="text-align: left;">
      <tr>
        <td width="7%" rowspan="3">&nbsp;</td>        
        <td>
        <font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:18px; text-align:left;">
        <b>Pasajero <?=$reserva->nombre.' '.$reserva->apellido;?>:</b><br><br>
        </font>
		<font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:18px; text-align:left;">
        <b>
        La reserva <?=$reserva->codigo;?> del viaje <?=$reserva->titulo.' - '.$reserva->subtitulo;?>, est&#225;  POR VENCER, debido a que a&#250;n no se registraron pagos. <br><br>
        </b>
        </font>
        <font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:18px; font-weight:bold; text-align:left;">
        Todav&#237;a estas a tiempo&#33; <br>
		Si no la misma</font><font color="#e14318" face="Trebuchet MS" style="font-size:14px; line-height:18px; font-weight:bold; text-align:left;"> se cancelar&#225; el <?=date('d-m',strtotime($vencimiento_dia));?> a las <?=date('H',strtotime($vencimiento_hora));?> hs.
        </font>                  
        </td>
        <td width="7%" rowspan="3">&nbsp;</td>            
      </tr>
      <tr>
        <td width="70%" height="47px" style="background:#195d9d;">         
          <font face="Trebuchet MS" ><a style="font:'Trebuchet MS'; color:#fff; text-decoration:none; display:block; text-align:center; text-transform:uppercase; font-weight:bold;" href="<?=isset($lnk_pago)?$lnk_pago:'';?>">Hac&#233; click aqu&#237; para generar tu pago</a> </font>        
        </td>
      </tr> 
    </table></th>
  </tr>
  
  <tr>
    <th colspan="4">
      <table width="100%" border="0" cellspacing="8" cellpadding="0" style="text-align: left;">
      <tr>
        <td width="8%" rowspan="3">&nbsp;</td>        
        <td>        
		<font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:18px; text-align:left;">
        Si ya realizaste el pago y todav&#237;a no se acredit&#243; comun&#237;cate con nosotros. 

		Ante cualquier duda, consulta o diferencia, pod&#233;s comunicarte por los medios indicados m&#225;s abajo o respondiendo directamente este mail.
        </font>                    
        </td>
        <td width="8%" rowspan="3">&nbsp;</td>            
      </tr>      
    </table>
    </th>
  </tr>
  
  <tr>
    <th colspan="4" width="601" >&nbsp;</th>
  </tr>
  
  <? if(isset($reserva->en_avion) && $reserva->en_avion){ ?>
  <tr>
  	<th width="4%" style="background:#ffc119; padding:20px;"><img src="<?=base_url();?>media/frontend/mailings/images/icono_avion_pago.jpg" width="39" height="26" alt="Avion"></th>
    <th colspan="425" width="301" style="background:#ffc119;">
    <font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:20px; font-weight:bold; text-align:left; display:block;">&#161;Pod&#233;s pagar una se&#241;a del 30% y el saldo completo del viaje pagarlo hasta 
	<br>
	30 d&#237;as 
	antes de la fecha de salida&#33;</font>
    </th>
  </tr>
  <? } else { ?>
  <tr>
  	<th width="4%" style="background:#ffc119; padding:20px;"><img src="<?=base_url();?>media/frontend/mailings/images/icono_micro_pago.jpg" width="39" height="26" alt="Avion"></th>
    <th colspan="425" width="301" style="background:#ffc119;">
    <font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:20px; font-weight:bold; text-align:left; display:block;">&#161;Pod&#233;s pagar una se&#241;a del 30% y el saldo completo del viaje pagarlo hasta 
	<br>
	30 d&#237;as 
	antes de la fecha de salida&#33;</font>
    </th>
  </tr>
  <? } ?>
  
  <tr>
    <th colspan="4">
      <table width="100%" border="0" cellspacing="8" cellpadding="0">
      <tr>
        <td width="30%" rowspan="3">
        <font color="#333333" face="Trebuchet MS" style="font-size:18px; text-transform:uppercase; font-weight:bold; text-align:center; display:block;">
        formas de pago
        </font>
        </td>        
        <td>        
		<img src="<?=base_url();?>media/frontend/mailings/images/formas_de_pago.jpg" border="0" style="display:block;">                   
        </td>           
      </tr>      
    </table>
    </th>
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
    <th colspan="4" valign="top"><img src="<?=base_url();?>media/frontend/mailings/images/separa_colores.jpg" width="601" height="3" style="margin-top:25px;"></th>
  </tr>
  
  <tr>
    <th colspan="4"><table width="100%" border="0" cellspacing="15" cellpadding="0" bgcolor="#F7F7F7" style="text-align: left;">
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
            <td><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> <?=$reserva->sucursal;?></b></font><font color="#FD7247" face="Trebuchet MS" style="font-size:14px; line-height:16px;"> <b> <br>
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
