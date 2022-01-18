<?php

class WSFE {

  const CUIT = "30711604932";                 # CUIT del emisor de las facturas
  //cuit dami 20932799511
  const TA =    "xmlgenerados/TA.xml";        # Archivo con el Token y Sign
  const WSDL = "wsfe.wsdl";                   # The WSDL corresponding to WSFE
  const CERT = "keys/ghf.crt";                # The X.509 certificate in PEM format
  const PRIVATEKEY = "keys/ghf.key";          # The private key correspoding to CERT (PEM)
  const PASSPHRASE = "";                      # The passphrase (if any) to sign
  const PROXY_ENABLE = false;
  const LOG_XMLS = true;                     # For debugging purposes
  #const WSFEURL = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx"; // testing
  const WSFEURL = "https://servicios1.afip.gov.ar/wsfev1/service.asmx"; // produccion

  
  /*
   * el path relativo, terminado en /
   */
  private $path = './';
  
  /*
   * manejo de errores
   */
  public $error = '';
  
  /**
   * Cliente SOAP
   */
  private $client;
  
  /**
   * objeto que va a contener el xml de TA
   */
  private $TA;
  
  /**
   * tipo_cbte defije si es factura A = 1 o B = 6
   */
  private $tipo_cbte = '1';
  
  /*
   * Constructor
   */
  public function __construct($path = './') 
  {
    $this->path = '../application/libraries/afip/';
    
    // seteos en php
    ini_set("soap.wsdl_cache_enabled", "0");    
    
    // validar archivos necesarios
    if (!file_exists($this->path.self::WSDL)) $this->error .= " Failed to open ".self::WSDL;

    if(!empty($this->error)) {
      throw new Exception('WSFE class. Faltan archivos necesarios para el funcionamiento');
    }        

    $opts = array(
      'ssl' => array('ciphers'=>'RC4-SHA')
    );
    ini_set( "soap.wsdl_cache_enabled", "0" );

    $this->client = new SoapClient($this->path.self::WSDL, array( 
              'soap_version' => SOAP_1_2,
              'location'     => self::WSFEURL,
              'exceptions'   => 0,
              'trace'        => 1,
             // 'stream_context' => stream_context_create($opts),
              "encoding"=>"ISO-8859-1",
              "connection_timeout"=>2000 )
    );
    
    /*
    $this->client = new SoapClient($this->path.self::WSDL, array( 
              'soap_version' => SOAP_1_2,
              'location'     => self::WSFEURL,
              'exceptions'   => 0,
              'trace'        => 1)
    );
    */ 
  }
  
  
  /******* BEGIN DAM *********/
  /**
   * Metodo dummy para verificacion de funcionamiento
   */ 
  public function Test() {
    $results = $this->client->FEDummy();
    return $results;  	
  }
  
  /**
   * Recupera el listado de monedas utilizables en servicio de autorización
   */
  public function GetTiposMonedas() {
  	$results = $this->client->FEParamGetTiposMonedas(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }
  
  /**
   * Recupera la cotizacion de la moneda consultada y su fecha
   */
  public function GetCotizacion($moneda_id) {
  	$results = $this->client->FEParamGetCotizacion(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
		'MonId'		=>	$moneda_id
	));
	return $results;
  }

  /**
   * Recupera el listado de los diferente paises que pueden ser utilizados en el servicio de autorizacion
   */
  public function GetTiposPaises() {
  	$results = $this->client->FEParamGetTiposPaises(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }

  /**
   * Recupera el listado de los diferente tributos que pueden ser utilizados en el servicio de autorizacion
   */
  public function GetTiposTributos() {
  	$results = $this->client->FEParamGetTiposTributos(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }
  
  /**
   * Recupera el listado de Tipos de Iva utilizables en servicio de autorización.
   */
  public function GetTiposIva() {
  	$results = $this->client->FEParamGetTiposIva(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }
 
  /**
   * Recupera el listado de Tipos de Documentos utilizables en servicio de autorización.
   */
  public function GetTiposDoc() {
  	$results = $this->client->FEParamGetTiposDoc(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }

  /**
   * Recupera el listado de identificadores para el campo Concepto.
   */
  public function GetTiposConcepto() {
  	$results = $this->client->FEParamGetTiposConcepto(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }

  /**
   * Recupera el listado de Tipos de Comprobantes utilizables en servicio de autorización.
   */
  public function GetTiposComprobante() {
  	$results = $this->client->FEParamGetTiposCbte(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }
  
  /**
   * Recupera el listado de puntos de venta registrados y su estado
   */
  public function GetPuntosVenta() {
  	$results = $this->client->FEParamGetPtosVenta(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
	));
	return $results;
  }  

  /**
   * Recupera el listado de identificadores para los campos Opcionales
   */
  public function GetTiposOpcional() {
  	$results = $this->client->FEParamGetTiposOpcional(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
		'MonId'		=>	$moneda_id
	));
	return $results;
  }
  
  /**
   * Retorna el ultimo comprobante autorizado para el tipo de comprobante / cuit / punto de venta ingresado / Tipo de Emisión
   */
  public function GetCompUltimoAutorizado($punto_venta, $tipo_comprobante) {  
  	$results = $this->client->FECompUltimoAutorizado(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
		'PtoVta'		=>	$punto_venta,
		'CbteTipo'		=>	$tipo_comprobante,
	));
	return $results;
  }
  
  /**
   * Consulta Comprobante emitido y su código.
   */
  public function ConsultarComprobante($factura) {
  	$results = $this->client->FECompConsultar(array(
  		'Auth'	=>	array(	'Token' => $this->TA->credentials->token,
                          	'Sign' => $this->TA->credentials->sign,
                            'Cuit' => self::CUIT	),
		'FeCompConsReq' => array(
	  						'PtoVta' => $factura['punto_venta'],
				  			'CbteTipo' => $factura['codigo_factura'],
					  		'CbteNro' => $factura['numero_factura'],
					  	),
	));
	return $results;
  }
  
  /**
   * Solicitud de Código de Autorización Electrónico (CAE)
   */
  public function GetCAE($factura) {
  	//Generar cabecera de solicitud
  	$cabecera = array(
  		'CantReg' => 1,
  		'PtoVta' => $factura['punto_venta'],
  		'CbteTipo' => $factura['codigo_factura'],
  	);
   
   /*
  if(@$factura['reserva_id'] == '2693'){
    print_r($factura);
    
  }*/
  	
		/*
		echo "<pre>";
		print_r($factura);
		*/
		
	$CI =& get_instance();


  
  if($factura['neto_iva_21'] < 0){
    $factura['neto_iva_21'] = 0.00;
  }
  if($factura['total_iva_21'] < 0){
    $factura['total_iva_21'] = 0.00;
  }

//no se usaria------
/*	$q = "SELECT * from alicuotas WHERE id = 4";
	$alic_percepcion = $CI->db->query($q)->row();
	$valor_aicuota = ($alic_percepcion->porcentaje == '35.00') ? 35 : 20;
	*/
//---end no se usaria

  	//Calcular tributos
	//17-12-15 debido a la eliminacion de percepcion del 35% esto ya no se usa
  	/*$tributos = array();
  	if ($factura['percepcion_3450']) {
	  	$tributos[] = array(
  			'Id' => '01', //Impuestos nacionales
  			'Desc' => 'Percep. RG 3450',
	  		'BaseImp' => $factura['neto_percepcion_3450'],
  			'Alic' => $factura['percepcion_tasa'], //antes: $valor_aicuota
  			'Importe' => $factura['percepcion_3450'],
  		);
  	}*/
	//end no se usa
	
    //27/12/19 impuesto pais
    $tributos = array();
    if ($factura['impuesto_pais']) {
      $tributos[] = array(
        'Id' => '01', //Impuestos nacionales
        'Desc' => 'Impuesto PAIS',
        'BaseImp' => $factura['base_imponible_pais'],
        'Alic' => $factura['tasa_impuesto_pais'], //antes: $valor_aicuota
        'Importe' => $factura['impuesto_pais'],
      );
    }

	//28-12-15 ahora se cobra un 5% de percepcion segun resolucion 3825
  	$tributos = array();
  	if (isset($factura['percepcion_3825']) && $factura['percepcion_3825']) {
	  	$tributos[] = array(
  			'Id' => '01', //Impuestos nacionales
  			'Desc' => 'Percep. RG 3825',
	  		'BaseImp' => $factura['neto_percepcion_3825'],
  			'Alic' => 5.00, 
  			'Importe' => $factura['percepcion_3825'],
  		);
  	}
	
  	$suma_tributos = 0;
  	foreach ($tributos as $t) {
  		$suma_tributos += $t['Importe'];
  	}
	
  	//Calcular impuestos
  	$iva = array();
		
  	if ($factura['total_iva_21'] || isset($factura['gastos_administrativos']) || isset($factura['comision'])) {
		
		$gastos_adm_iva = 0;
		$gastos_adm_neto = 0;
		
		if (isset($factura['gastos_administrativos'])) {
			//fix 19-11
			//$gastos_adm_iva = 0.21 * $factura['gastos_administrativos'];
			/*
			$gastos_adm_iva = round($factura['gastos_administrativos'] / 1.21 * .21,2);
			$gastos_adm_neto = $factura['gastos_administrativos']-$gastos_adm_iva;
			*/
			//$gastos_adm_iva = 0;
			$gastos_adm_neto += $factura['gastos_administrativos'];
		}
	
		if (isset($factura['comision'])) {
			$gastos_adm_neto += $factura['comision'];
		}
		
		$factura['neto_gravado'] = number_format($factura['neto_gravado']+$gastos_adm_neto,2,'.','');
	
		$baseImp = 0;
		$baseImp += $factura['total_iva_21'] ? $factura['neto_iva_21'] : 0;
		$baseImp += $gastos_adm_neto;
		
		$importe = 0;
		$importe += $factura['total_iva_21'] ? $factura['iva_21'] : 0;
		$importe += $gastos_adm_iva;
	  	
		if($baseImp>0){
			$iva[] = array(
				'Id' => 5,
				'BaseImp' => number_format($baseImp,2,'.',''),
				'Importe' => number_format($baseImp * 0.21, 2,'.',''),
			);
		}
  	}
  	
	if ($factura['total_iva_10'] || (isset($factura['intereses']) && $factura['intereses']) ) {
	  	$intereses_iva = 0;
		$intereses_neto = 0;
		
		if (isset($factura['intereses'])) {
			$intereses_neto += $factura['intereses'];
		}
	
		$factura['neto_gravado'] = number_format($factura['neto_gravado']+$intereses_neto,2,'.','');
	
		$baseImp = 0;
		$baseImp += $factura['total_iva_10'] ? $factura['neto_iva_10'] : 0;
		$baseImp += $intereses_neto;
		
		$importe = 0;
		$importe += $factura['total_iva_10'] ? $factura['iva_10'] : 0;
		$importe += $intereses_iva;
		
		if($baseImp>0){
			$iva[] = array(
				'Id' => 4,
				'BaseImp' => number_format($baseImp,2,'.',''),
				'Importe' => number_format($baseImp * 0.105, 2,'.',''),
			);
		}
  	}
  	
  	/*
  	if ($factura['total_iva_exento']) {
	  	$iva[] = array(
  			'Id' => 2,
  			'BaseImp' => $factura['neto_iva_exento'],
	  		'Importe' => $factura['total_iva_exento'],
  		);
  	}
  	*/

	//print_r($iva);
	
  	$suma_iva = 0;
  	foreach ($iva as $i) {
  		$suma_iva += $i['Importe'];
  	}
	
	//echo $suma_iva."<br>";
	//echo $suma_tributos."<br>";
	//print_r($factura);
	
	//fix ara factura 11630 del dia 16-06-15
	//$suma_tributos = $suma_tributos - 0.01;
	
	//fix 02-06-15
	if($factura['exterior'] == 1 && $factura['en_avion'] == 1){
		//agregado 26-08-15
		//si existen otros impuestos, los paso como exento para afip
		//luego en factura deberian verse segregados
		
		/*
		if(isset($factura['otros_impuestos_impuesto']) && $factura['otros_impuestos_impuesto'] > 0){
			$factura['neto_exento'] += $factura['otros_impuestos_impuesto'];
		}
		*/
		
		/*
		//fix 27-05-15
		if($factura['neto_nogravado'] > 0){
			$factura['neto_nogravado'] = $factura['neto_nogravado'] - $suma_tributos;
		}
		*/
		
		// $factura['total'] = $factura['neto_nogravado'] + $factura['neto_gravado'] + $factura['neto_exento'] +$suma_tributos +$suma_iva
		/*if($factura['total'] != ($factura['neto_nogravado'] + $factura['neto_gravado'] + $factura['neto_exento'] +$suma_tributos +$suma_iva)){
			//prueba si la diferencia es de 1 centavo
			$suma_tributos = ($suma_tributos - 0.01 > 0) ? ($suma_tributos - 0.01) : 0.00;
			if($factura['total'] != ($factura['neto_nogravado'] + $factura['neto_gravado'] + $factura['neto_exento'] +$suma_tributos +$suma_iva)){
				$factura['neto_nogravado'] = $factura['neto_nogravado'] - $suma_tributos;			
			}
		}*/
	}
	
	/*if($factura['total'] != ($factura['neto_nogravado'] + $factura['neto_gravado'] + $factura['neto_exento'] +$suma_tributos +$suma_iva)){
		$factura['total'] = ($factura['neto_nogravado'] + $factura['neto_gravado'] + $factura['neto_exento'] +$suma_tributos +$suma_iva);
	}*/
		
	/*
	fix marcelo 27-08-2014 si tiene letras las reemplazo por 0
	*/
	$nro = $factura['nro_documento'];
	for($i=0; $i < strlen($nro);$i++){
		if(!is_numeric($nro[$i])){
			$nro[$i] = 0;
		}
	}
	
	$factura['nro_documento'] = $nro;
	
	//ImpTotal = ImpTotConc + ImpNeto + ImpOpEx + ImpTrib + ImpIVA.
	
	/*if($_SERVER['REMOTE_ADDR'] == '181.171.24.39' || $_SERVER['REMOTE_ADDR'] == '190.18.187.8' || $_SERVER['REMOTE_ADDR'] =='201.213.84.51'){
		echo $suma_iva."<br>";
		pre($iva);
		echo $suma_tributos."<br>";
		print_r($factura);
	}*/
	
  if (isset($factura['gastos_administrativos'])) {
    if($factura['concepto_id'] == 18){
      if($factura['neto_exento'] > 0){
        //si es mercadolibre
        $tgas = $factura['gastos_administrativos']*1.21;
        $factura['neto_exento'] += $tgas;
      }
    }
    elseif($factura['concepto_id'] == 17){
      if($factura['neto_exento'] > 0){
        //si es mercadolibre
        $tgas = $factura['gastos_administrativos']*1.21;
        $factura['neto_exento'] += $tgas;
      }
    }
  }

	// El misterio de Oktoberfest Micro 4  
  if (strpos($factura['concepto'], 'Oktoberfest - Micro 4 (con entrada incluida)') !== FALSE || 
	  strpos($factura['concepto'], 'Oktoberfest - Micro 5 (con entrada incluida)') !== FALSE || 
	  strpos($factura['concepto'], 'Oktoberfest - Micro 6 (con entrada incluida)') !== FALSE
  ) {
	$factura['neto_exento'] -= $tgas;  
  }

	$suma_total = $factura['neto_nogravado']+$factura['neto_gravado']+$factura['neto_exento']+$suma_tributos+$suma_iva;
	
	 /*if(@$factura['reserva_id'] == '5755'){
		 echo "max<br>".$suma_total."<br>";
		 print_r($factura);
	 }*/
	
	if($suma_total < $factura['total']){
		$factura['neto_exento'] += ($factura['total']-$suma_total);
	}
	else{
		if($suma_total > $factura['total']){
			$factura['neto_exento'] -= ($suma_total-$factura['total']);
			if($factura['neto_exento'] < 0){
				//si da negativo se lo resto a neto no gravado
				$factura['neto_exento'] = 0;
				
				if($factura['concepto_id'] != 73){
					$factura['neto_nogravado'] -= ($suma_total-$factura['total']);
				}
				
				if($factura['neto_nogravado'] < 0){
					//si da negativo 
					$factura['neto_nogravado'] = 0;
				}
			}
		}
	}

	/*
	 if(@$factura['reserva_id'] == '5755'){
		 echo "max2<br>";
		 print_r($factura);exit();
	 }
	 */
	// Maxi 12-02-19 : fix para corregir error de factura de $0,01 que no informaba. EL msg de error esta especificado abajo por AFIP.
	if($suma_iva == 0){
		//[Msg] => Si ImpIva es igual a 0 el objeto Iva y AlicIva son obligatorios. Id iva = 3 (iva 0)
		if( isset($iva[0]) && isset($iva[0]['Id']) ){
			$iva[0]['Id'] = 3;
		}
	}
  
	//Generar detalle de solicitud
	$detalle = array();

	/*
   if(@$factura['reserva_id'] == '3417'){
   echo "aca";
     echo $suma_total."<br>";
     echo $suma_iva."<br>";
    
     pre($iva);
     echo $suma_tributos."<br>";
     print_r($factura);
     exit();
   }*/
  
  
	$detalle[] = array(
  		'Concepto' => 2, //Servicios
  		'DocTipo' => $factura['tipo_documento'],
  		'DocNro' => $factura['nro_documento'],
  		'CbteDesde' => $factura['idComprobanteAfip'],
  		'CbteHasta' => $factura['idComprobanteAfip'],
  		'CbteFch' => date('Ymd'),
  		'ImpTotal' => $factura['total'],
  		'ImpTotConc' => number_format($factura['neto_nogravado'],2,'.',''),
  		'ImpNeto' => number_format($factura['neto_gravado'],2,'.',''),
  		'ImpOpEx' => number_format($factura['neto_exento'],2,'.',''),
  		'ImpTrib' => number_format($suma_tributos,2,'.',''),
  		'ImpIVA' => number_format($suma_iva,2,'.',''),
  		'MonId' => 'PES',
  		'MonCotiz' => 1,
  		'FchServDesde' => $factura['fecha_desde'],
  		'FchServHasta' => $factura['fecha_hasta'],
  		'FchVtoPago' => date('Ymd'),
  	);
  	if (count($iva)) {
  		$detalle[0]['Iva'] = $iva;
  	}
  	if ($suma_tributos) {
  		$detalle[0]['Tributos'] = $tributos;
  	}
	
  	$params = array(
  		'Auth'		=>	array(	'Token' => $this->TA->credentials->token,
                         	 	'Sign' => $this->TA->credentials->sign,
                            	'Cuit' => self::CUIT	),
                            	
        'FeCAEReq' 	=> 	array(	'FeCabReq'	=>	$cabecera,
								'FeDetReq'	=>	array(	'FECAEDetRequest'	=>	$detalle	),
							),
	);

	//print_r($params);
  	$results = $this->client->FECAESolicitar($params);

	
   /*if(@$factura['reserva_id'] == '5755'){
     echo "aca2";
	 echo $suma_total."<br>";
     echo $suma_iva."<br>";
    
     pre($iva);
     echo $suma_tributos."<br>";
     print_r($factura);
     print_r($detalle);
     print_r($results);
     exit();
   }*/
	
	
	//echo $this->client->__getLastRequest();
	//echo "<br>";
	//echo $this->client->__getLastResponse();
	
	
	return $results;  	
  }
  /******* END DAM *********/
  
  /**
   20-07-15
   * NUEVO: segun ajustes de precios de paquetes, para usar en facturacion
   * Solicitud de Código de Autorización Electrónico (CAE)
   */
  public function GetCAE_test($factura) {
  	//Generar cabecera de solicitud
  	$cabecera = array(
  		'CantReg' => 1,
  		'PtoVta' => $factura['punto_venta'],
  		'CbteTipo' => $factura['codigo_factura'],
  	);
  	
	$CI =& get_instance();

//no se usaria------
	// $q = "SELECT * from alicuotas WHERE id = 4";
	// $alic_percepcion = $CI->db->query($q)->row();
	// $valor_aicuota = ($alic_percepcion->porcentaje == '35.00') ? 35 : 20;
//---end no se usaria

	//en su lugar se toma el valor de $factura['percepcion_tasa']

  	//Calcular tributos
  	$tributos = array();
  	if ($factura['percepcion_3450']) {
	  	$tributos[] = array(
  			'Id' => '01', //Impuestos nacionales
  			'Desc' => 'Percep. RG 3450',
	  		'BaseImp' => $factura['neto_percepcion_3450'],
  			'Alic' => $factura['percepcion_tasa'], //antes: $valor_aicuota
  			'Importe' => $factura['percepcion_3450'],
  		);
  	}
  	$suma_tributos = 0;
  	foreach ($tributos as $t) {
  		$suma_tributos += $t['Importe'];
  	}
	
  	//Calcular impuestos
  	$iva = array();
  	if ($factura['total_iva_21'] || isset($factura['gastos_adm'])) {
		if (isset($factura['gastos_adm'])) {
			//fix 19-11
			//$gastos_adm_iva = 0.21 * $factura['gastos_adm'];
			$gastos_adm_iva = round($factura['gastos_adm']  / 1.21 * .21,2);
			$gastos_adm_neto = $factura['gastos_adm']-$gastos_adm_iva;
		}
		else {
			$gastos_adm_iva = 0;
			$gastos_adm_neto = 0;
		}
	
		$baseImp = 0;
		$baseImp += $factura['total_iva_21'] ? $factura['neto_iva_21'] : 0;
		$baseImp += $gastos_adm_neto;
		
		$importe = 0;
		$importe += $factura['total_iva_21'] ? $factura['iva_21'] : 0;
		$importe += $gastos_adm_iva;
	  	
		$iva[] = array(
  			'Id' => 5,
  			'BaseImp' => $baseImp,
	  		'Importe' => $importe,
  		);
  	}
  	if ($factura['total_iva_10']) {
	  	$iva[] = array(
  			'Id' => 4,
  			'BaseImp' => $factura['neto_iva_10'],
	  		'Importe' => $factura['iva_10'],
  		);
  	}
  	/*
  	if ($factura['total_iva_exento']) {
	  	$iva[] = array(
  			'Id' => 2,
  			'BaseImp' => $factura['neto_iva_exento'],
	  		'Importe' => $factura['total_iva_exento'],
  		);
  	}
  	*/

	//print_r($iva);
	
  	$suma_iva = 0;
  	foreach ($iva as $i) {
  		$suma_iva += $i['Importe'];
  	}
			
	/*
	fix marcelo 27-08-2014 si tiene letras las reemplazo por 0
	*/
	$nro = $factura['nro_documento'];
	for($i=0; $i < strlen($nro);$i++){
		if(!is_numeric($nro[$i])){
			$nro[$i] = 0;
		}
	}
	
	$factura['nro_documento'] = $nro;
	
	//print_r($factura);
	
	/*
	21-07-15
	Si no hago esto falla al informar en esta formula:
	ImpTotal = ImpTotConc + ImpNeto + ImpOpEx + ImpTrib + ImpIVA.
	*/
	// if($datos_factura['neto_nogravado'] > 0 && $datos_factura['neto_exento'] > 0){
	// 	$factura['neto_exento'] = 0;
	// }
	
  	//Generar detalle de solicitud
  	$detalle = array();
	$detalle[] = array(
  		'Concepto' => 2, //Servicios
  		'DocTipo' => $factura['tipo_documento'],
  		'DocNro' => $factura['nro_documento'],
  		'CbteDesde' => $factura['idComprobanteAfip'],
  		'CbteHasta' => $factura['idComprobanteAfip'],
  		'CbteFch' => date('Ymd'),
  		'ImpTotal' => $factura['total'],
  		'ImpTotConc' => $factura['neto_nogravado'],
  		'ImpNeto' => $factura['neto_gravado'],
  		'ImpOpEx' => $factura['neto_exento'],
  		'ImpTrib' => $suma_tributos,
  		'ImpIVA' => $suma_iva,
  		'MonId' => 'PES',
  		'MonCotiz' => 1,
  		'FchServDesde' => $factura['fecha_desde'],
  		'FchServHasta' => $factura['fecha_hasta'],
  		'FchVtoPago' => date('Ymd'),
  	);
  	if (count($iva)) {
  		$detalle[0]['Iva'] = $iva;
  	}
  	if ($suma_tributos) {
  		$detalle[0]['Tributos'] = $tributos;
  	}
  	//print_r($detalle);
  	$params = array(
  		'Auth'		=>	array(	'Token' => $this->TA->credentials->token,
                         	 	'Sign' => $this->TA->credentials->sign,
                            	'Cuit' => self::CUIT	),
                            	
        'FeCAEReq' 	=> 	array(	'FeCabReq'	=>	$cabecera,
								'FeDetReq'	=>	array(	'FECAEDetRequest'	=>	$detalle	),
							),
	);

	#print_r($params);
  	$results = $this->client->FECAESolicitar($params);
  	#print_r($results);
	return $results;  	
  }
  /******* END DAM *********/
  
  
  /**
   * Chequea los errores en la operacion, si encuentra algun error falta lanza una exepcion
   * si encuentra un error no fatal, loguea lo que paso en $this->error
   */
  private function _checkErrors($results, $method)
  {
    if (self::LOG_XMLS) {
      file_put_contents("xmlgenerados/request-".$method.".xml",$this->client->__getLastRequest());
      file_put_contents("xmlgenerados/response-".$method.".xml",$this->client->__getLastResponse());
    }
    
    if (is_soap_fault($results)) {
      throw new Exception('WSFE class. FaultString: ' . $results->faultcode.' '.$results->faultstring);
    }
    
    if ($method == 'FEDummy') {return;}
    
    $XXX=$method.'Result';
    if ($results->$XXX->RError->percode != 0) {
        $this->error = "Method=$method errcode=".$results->$XXX->RError->percode." errmsg=".$results->$XXX->RError->perrmsg;
    }
    
    return $results->$XXX->RError->percode != 0 ? true : false;
  }

  /**
   * Abre el archivo de TA xml,
   * si hay algun problema devuelve false
   */
  public function openTA()
  {
    $this->TA = simplexml_load_file($this->path.self::TA);
    
    return $this->TA == false ? false : true;
  }
  
  /**
   * Retorna la cantidad maxima de registros de detalle que 
   * puede tener una invocacion al FEAutorizarRequest
   */
  public function recuperaQTY()
  {
    $results = $this->client->FERecuperaQTYRequest(
      array('argAuth'=>array('Token' => $this->TA->credentials->token,
                              'Sign' => $this->TA->credentials->sign,
                              'cuit' => self::CUIT)));
    
    $e = $this->_checkErrors($results, 'FERecuperaQTYRequest');
        
    return $e == false ? $results->FERecuperaQTYRequestResult->qty->value : false;
  }

  /*
   * Retorna el ultimo número de Request.
   */ 
  public function ultNro()
  {
    $results = $this->client->FEUltNroRequest(
      array('argAuth'=>array('Token' => $this->TA->credentials->token,
                              'Sign' => $this->TA->credentials->sign,
                              'cuit' => self::CUIT)));
    
    $e = $this->_checkErrors($results, 'FEUltNroRequest');
        
    return $e == false ? $results->FEUltNroRequestResult->nro->value : false;
  }
  
  /*
   * Retorna el ultimo comprobante autorizado para el tipo de comprobante /cuit / punto de venta ingresado.
   */ 
  public function recuperaLastCMP ($ptovta)
  {
    $results = $this->client->FERecuperaLastCMPRequest(
      array('argAuth' =>  array('Token'    => $this->TA->credentials->token,
                                'Sign'     => $this->TA->credentials->sign,
                                'cuit'     => self::CUIT),
             'argTCMP' => array('PtoVta'   => $ptovta,
                                'TipoCbte' => $this->tipo_cbte)));
                                
    $e = $this->_checkErrors($results, 'FERecuperaLastCMPRequest');
    
    return $e == false ? $results->FERecuperaLastCMPRequestResult->cbte_nro : false;
  }
  
    
  /**
   * Setea el tipo de comprobante
   * A = 1
   * B = 6
   */
  public function setTipoCbte($tipo) 
  {
    switch($tipo) {
      case 'a': case 'A': case '1':
        $this->tipo_cbte = 1;
      break;
      
      case 'b': case 'B': case 'c': case 'C': case '6':
        $this->tipo_cbte = 6;
      break;
      
      default:
        return false;
    }

    return true;
  }

  // Dado un lote de comprobantes retorna el mismo autorizado con el CAE otorgado.
  public function aut($ID, $cbte, $ptovta, $regfac)
  {
    $results = $this->client->FEAutRequest(
      array('argAuth' => array(
               'Token' => $this->TA->credentials->token,
               'Sign'  => $this->TA->credentials->sign,
               'cuit'  => self::CUIT),
            'Fer' => array(
               'Fecr' => array(
                  'id' => $ID, 
                  'cantidadreg' => 1, 
                  'presta_serv' => 0
                ),
               'Fedr' => array(
                  'FEDetalleRequest' => array(
                     'tipo_doc' => $regfac['tipo_doc'],
                     'nro_doc' => $regfac['nro_doc'],
                     'tipo_cbte' => $this->tipo_cbte,
                     'punto_vta' => $ptovta,
                     'cbt_desde' => $cbte,
                     'cbt_hasta' => $cbte,
                     'imp_total' => $regfac['imp_total'],
                     'imp_tot_conc' => $regfac['imp_tot_conc'],
                     'imp_neto' => $regfac['imp_neto'],
                     'impto_liq' => $regfac['impto_liq'],
                     'impto_liq_rni' => $regfac['impto_liq_rni'],
                     'imp_op_ex' => $regfac['imp_op_ex'],
                     'fecha_cbte' => date('Ymd'),
                     'fecha_venc_pago' => $regfac['fecha_venc_pago']
                   )
                )
              )
       )
     );
    
    $e = $this->_checkErrors($results, 'FEAutRequest');
        
    return $e == false ? Array( 'cae' => $results->FEAutRequestResult->FedResp->FEDetalleResponse->cae, 'fecha_vencimiento' => $results->FEAutRequestResult->FedResp->FEDetalleResponse->fecha_vto ): false;
  }

} // class

?>
