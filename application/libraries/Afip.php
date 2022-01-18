<?php
require 'afip/exceptionhandler.php';
require 'afip/wsaa.class.php';
require 'afip/wsfe.class.php';

class afip {

	function test() {
		$wsaa = new WSAA('./');

		echo $esta = date("Y-m-d H:i:s",strtotime($wsaa->get_expiration()));
		echo "<br>";
		echo date("Y-m-d H:i:s");	

		if($esta < date("Y-m-d H:i:s")) {
			echo "aca";
		} 
		else {
		  	echo "aca2";
		}		
	}

	function auth() {
		$wsaa = new WSAA('./');

		if(date("Y-m-d H:i:s",strtotime($wsaa->get_expiration())) < date("Y-m-d H:i:s")) {
			if ($wsaa->generar_TA()) {
		   		return TRUE;
		  } 
		  else {
		  		return FALSE;
		  }
		} 
		else {
		  	return TRUE;
		}
	}

	function consultar($factura) {
		$wsfe = new WSFE('./');
 
		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->ConsultarComprobante($factura);
	}

	function GetTiposDoc() {
		
		$wsfe = new WSFE('./');
 
		// Carga el archivo TA.xml
		$wsfe->openTA();
		
		return $wsfe->GetTiposDoc();
	}
	
	function autorizar($factura) {
	
		$wsfe = new WSFE('./');
 
		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->GetCAE($factura);
	}
	
	function autorizar_test($factura) {
	
		$wsfe = new WSFE('./');
 
		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->GetCAE_test($factura);
	}
	
	function resync() {
		unlink('./application/libraries/afip/xmlgenerados/request-loginCms.xml');
		unlink('./application/libraries/afip/xmlgenerados/response-loginCms.xml');
		unlink('./application/libraries/afip/xmlgenerados/TA.xml');
		unlink('./application/libraries/afip/xmlgenerados/TRA.xml');
		
		$this->auth();
	}

	function ultimo_comprobante($pto_venta=2, $tipo_cbte='006'){
		$wsfe = new WSFE('./');
 
 		$this->auth();

		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->GetCompUltimoAutorizado($pto_venta,$tipo_cbte);
	}
	
	function obtener_cae($factura){
		$wsfe = new WSFE('./');
 
		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->GetCAE($factura);
	}
	function GetTiposIva(){
		$wsfe = new WSFE('./');
		
		// Carga el archivo TA.xml
		$wsfe->openTA();

		return $wsfe->GetTiposIva();
	}
	
}