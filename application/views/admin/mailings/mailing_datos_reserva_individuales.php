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
            <font color="#FFFFFF" face="Trebuchet MS" style="font-size:28px; text-transform:uppercase; line-height:32px; display:block; margin-left:15px; margin-top:15px; text-align:left;"> <b>datos de reserva</b><br>
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
    <th colspan="4" valign="top"><font color="#262626" face="Trebuchet MS" style="font-size:14px; text-transform:uppercase; line-height:18px;"><img src="<?=base_url();?>media/frontend/mailings/images/separa_colores.jpg" width="601" height="3" alt="Div Header" style="display:block; margin-bottom:15px;"></font></th>
  </tr>
  
   <? if(isset($mostrar_vendedor) && $mostrar_vendedor) { ?>
	   <? if(isset($reserva->nombre_vendedor) && $reserva->nombre_vendedor && isset($reserva->apellido_vendedor) && $reserva->apellido_vendedor) { ?>
	  <tr>
	   <th colspan="4">
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
		  <tr>
			<td width="74" rowspan="4">&nbsp;</td>        
			<td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
				<font face="Trebuchet MS">Vendedor:</font>
			</td>        
			<td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
			  <font face="Trebuchet MS"><?=$reserva->nombre_vendedor.' '.$reserva->apellido_vendedor;?></font>
			</td>
			<td width="74" rowspan="4">&nbsp;</td>              
		  </tr>      
		</table>    
	   </th>
	  </tr>
	   <? } ?>
   <? } ?>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Pasajero:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->apellido.', '.$reserva->nombre;?></font>
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
        	<font face="Trebuchet MS"><?=$reserva->dniTipo;?>:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->dni;?></font>
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
        	<font face="Trebuchet MS">Mail:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=$reserva->email;?></font>
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
        	<font face="Trebuchet MS">Celular:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS">0 <?=$reserva->celular_codigo;?> 15 <?=$reserva->celular_numero;?></font>
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
          <font face="Trebuchet MS"><?=$reserva->titulo." - ".$reserva->subtitulo;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  
  <? if($paquete->salidas != ''){ ?>
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Salidas:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=nl2br($paquete->salidas);?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
  <? } ?>
  
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="4" width="601" style="background:#6a6b6b; height:1px;" ></th>
  </tr>
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>
  
  <? if(isset($reserva_acompanantes) && count($reserva_acompanantes) > 0){ ?>
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Acompañantes:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
			<? foreach($reserva_acompanantes as $ac){ ?>
				<font face="Trebuchet MS"><?=$ac->nombre;?> <?=$ac->apellido;?> - DNI: <?=$ac->dni;?></font>
				<br>
			<? } ?>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table>    
   </th>
  </tr>
   <? } ?>
   
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Alojamiento en:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <? $hoteles = "";
			for($h=1;$h<=3;$h++){ 
				if($reserva->{'alojamiento'.$h} != ""){
					$hoteles .= $reserva->{'alojamiento'.$h}.", ";
				} 
			} 
			$hoteles[strlen($hoteles)-2] = "."; 
			$hoteles[strlen($hoteles)-1] = ""; ?>
			<font face="Trebuchet MS"><?=$hoteles;?></font>
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
        	<font face="Trebuchet MS">Excursiones y actividades:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><a href="<?=base_url().'destinos/paquete/'.$reserva->titulo_url;?>" style="color:#195d9d; text-decoration:underline;">Ver online</a></font>
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
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>
  
  <tr>
   <th colspan="4">
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>        
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;">
        	<font face="Trebuchet MS">Valor:</font>
        </td>        
        <td width="290" rowspan="4" style="color:#262626; font-size:12px; font-weight:bold; text-align:left;">
          <font face="Trebuchet MS"><?=($paquete->exterior && $paquete->viaje_individual)?'USD':'$'?> <?=$reserva->monto_total;?></font>
        </td>
        <td width="74" rowspan="4">&nbsp;</td>              
      </tr>      
    </table> 
	<table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="74" rowspan="4">&nbsp;</td>
        <td width="162" rowspan="4" style="color:#6a6b6b; font-size:12px; font-weight:bold; text-align:left;"><font face="Trebuchet MS">Transporte:</font></td>
        <td width="290" rowspan="4" valign="middle" style="color:#262626; font-size:14px; font-weight:bold; text-align:left;">
			<? if(false){
				if(isset($reserva->en_avion) && $reserva->en_avion){ ?>
					<img src="<?=base_url();?>media/frontend/mailings/images/icono_avion.jpg" alt="Avion" width="24" height="18" align="top"> <font face="Trebuchet MS">AVI&Oacute;N</font>
				<? } else { ?>
					<img src="<?=base_url();?>media/frontend/mailings/images/icono_micro.jpg" alt="Micro" width="24" height="18" align="top"> <font face="Trebuchet MS">MICRO</font>
				<? } 
			} ?>
			
			<? if(isset($reserva->icono_transporte) && $reserva->icono_transporte != ''){
				$transporte_icono = str_replace('_','',$reserva->icono_transporte); ?>
				<img src="<?=base_url();?>media/frontend/img/ic_<?=$transporte_icono;?>.png" width="24" height="18" align="top"> <font face="Trebuchet MS"><?=strtoupper($transporte_icono);?></font>
			<? } ?>
			
		</td>
        <td width="74" rowspan="4">&nbsp;</td>
      </tr>
    </table>		
   </th>
  </tr>
  
  <tr>
    <th colspan="4" width="601">&nbsp;</th>
  </tr>  
  
  <? //si es por agencia BVV o particular 
  if($reserva->agencia_id <= 1 || $reserva->destino_id == 35){ ?>
	  <tr>
	   <th colspan="4">
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
		  <tr>
			<td height="47px" style="background: #FFC118;padding: 10px 10px 0;">
				<p style="font-weight:bold; clear: both; margin: 0 0 10px;">
					Muy bien! 
					<br>A la brevedad un representante comercial se pondrá en contacto contigo para acordar las formas y condiciones de pago. 
					<br>Esta solicitud de reserva no implica bloqueo de lugares hasta que recibas la confirmación vía email. (Lugares sujetos a disponibilidad al momento de reservar). No incluye servicios que no sean debidamente especificados.
					<br>La documentación es responsabilidad de los pasajeros tenerla en regla, orden y excelente estado para poder salir del país. Caso contrario, los gastos que existan corren por cuenta de los pasajeros.
					<br>Las cotizaciones internacionales son en dólares estadounidenses, y se expresan en pesos argentinos considerando el cambio del día informado por la agencia. 
					<br>Precios sujetos a la variación del cambio del dólar y disponibilidad de los servicios cotizados.
				</p>
			</td>
		  </tr>
		</table>    
	   </th>
	  </tr>
	  
	  <tr>
		<th colspan="4" width="601">&nbsp;</th>
	  </tr>
  <? } ?>
  
  <? if($reserva->agencia_id <= 1 || $reserva->destino_id == 35){
		/*
		if(isset($reserva->en_avion) && $reserva->en_avion){ ?>
		   <tr>
			<th width="4%" style="background:#ffc119; padding:20px;"><img src="<?=base_url();?>media/frontend/mailings/images/icono_avion_pago.jpg" width="41" height="24" alt="Avion"></th>
			<th colspan="425" width="301" style="background:#ffc119;">
			<font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:20px; font-weight:bold; text-align:left; display:block;">&#161;Pod&#233;s pagar una se&#241;a del 30% y el saldo completo del viaje pagarlo hasta 
			<br>
			30 d&#237;as 
			antes de la fecha de salida&#33;</font>
			</th>
		  </tr>
	  <? } else { ?>
		  <tr>
			<th width="4%" style="background:#ffc119; padding:20px;"><img src="<?=base_url();?>media/frontend/mailings/images/icono_micro_pago.jpg" width="39" height="26" alt="Micro"></th>
			<th colspan="425" width="301" style="background:#ffc119;">
			<font color="#262626" face="Trebuchet MS" style="font-size:14px; line-height:20px; font-weight:bold; text-align:left; display:block;">&#161;Pod&#233;s pagar una se&#241;a del 30% y el saldo completo del viaje pagarlo hasta 
			<br>
			30 d&#237;as 
			antes de la fecha de salida&#33;</font>
			</th>
		  </tr>
	  <? } 
	  */ ?>
	  
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
  
  <? } ?>
  
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
        <td height="19"><table width="100%" border="0" cellspacing="8" cellpadding="0" style="text-align: left;">
          <tr>
            <td width="9%"><a href="mailto:info@buenas-vibras.com.ar" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/info_buenasvibras.jpg" alt="Mail Buenas Vibras" width="37" height="33" border="0" style="display:block;"></a></td>
            <td width="39%"><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> ESCRIBINOS</b></font><font color="#00DA7B" face="Trebuchet MS" style="font-size:14px; line-height:16px;"> <b> <br>
              <a style="color:#00DA7B; text-decoration:none; text-align:left;" href="mailto:info@buenas-vibras.com.ar" target="_blank">info@buenas-vibras.com.ar</a></b></font></td>
            <td width="9%"><a href="http://www.facebook.com/buenas.vibras" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/icono_facebook.jpg" alt="Mail Buenas Vibras" width="33" height="32" border="0" style="display:block;"></a></td>
            <td width="58%"><font color="#21579F" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> FACEBOOK</b></font><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px;"> <b> <br>
              <a style="color:#262626; text-decoration:none; text-align:left;" href="http://www.facebook.com/buenas.vibras" target="_blank">www.facebook.com/buenas.vibras</a></b></font></td>
          </tr>
          <tr>
            <td><img src="<?=base_url();?>media/frontend/mailings/images/icono_telefono.jpg" alt="Mail Buenas Vibras" width="40" height="27" border="0"></td>
            <td><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> BUENOS AIRES</b></font><font color="#FD7247" face="Trebuchet MS" style="font-size:14px; line-height:16px;"> <b> <br>
              (011) 5235-3810 </b></font></td>
            <td><a href="http://www.twitter.com/buenas_vibras_v" target="_blank"><img src="<?=base_url();?>media/frontend/mailings/images/icono_twitter.jpg" alt="Mail Buenas Vibras" width="33" height="33" border="0"></a></td>
            <td><font color="#00ABE3" face="Trebuchet MS" style="font-size:12px; line-height:16px; text-align:left;"> <b> TWITTER</b></font><font color="#262626" face="Trebuchet MS" style="font-size:12px; line-height:16px;"> <b> <br>
              <a style="color:#262626; text-decoration:none; text-align:left;" href="http://www.twitter.com/buenas_vibras_v" target="_blank">www.twitter.com/buenas_vibras_v</a></b></font></td>
          </tr>
          <tr>
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
