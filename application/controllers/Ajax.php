<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		
	}

	public function index()
	{

	}
	
	public function auth() {
		$this->load->library('afip');
		$this->afip->auth();
	}

	function ultimo_afip() {
		echo date('Y-m-d H:i:s');
		echo date("Y-m-d h:m:i");
		
		$this->load->library('afip');
		$aa = $this->afip->ultimo_comprobante();
		echo "<pre>";
		
		print_r($aa);		
	}

	function ultimo_afip_nc() {
		echo date('Y-m-d H:i:s');
		echo date("Y-m-d h:m:i");
		
		$this->load->library('afip');
		$aa = $this->afip->ultimo_comprobante('0002', '008');
		echo "<pre>";
		
		print_r($aa);		
	}
	
	function consultar_afip($factura_id=25609, $tipo='006'){
		$factura['punto_venta'] = '0002'; //bs as 0002 rosario 0005
		$factura['codigo_factura'] = $tipo; //factura 006 NC 008
		$factura['numero_factura'] = $factura_id;
							
		$this->load->library('afip');
		$result = $this->afip->consultar($factura);
		
		echo "<pre>";		
		print_r($result);
		exit();
	}

	function ord(){
		$this->load->model('Orden_model','Orden');
		$ords = $this->Orden->getAll(9999,0,'id','asc')->result();
		foreach($ords as $o){
			echo "<br>";
			echo $o->id.'  '.site_url('checkout/orden/'.encriptar($o->code));
		}
	}
	
	function vres(){
		$this->load->model('Reserva_model','Reserva');
		$reserva = $this->Reserva->get($id=195)->row();
		pre($reserva);
	}
	
	function test() {
		ini_set('display_errors',true);
		error_reporting(E_ALL);
		$this->load->model('Factura_model','Factura');
		$datos = $this->Factura->obtenerDatos(28924, 'FA_B', 1, 2693, true, true);
		pre($datos);
	}
}