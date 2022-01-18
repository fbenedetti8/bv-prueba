<?php
include "AdminController.php";

class Adicionales extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Adicional_model', 'Adicional');
    $this->model = $this->Adicional;
    $this->page = "adicionales";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "adicionales";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Adicionales";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
	
	$this->data['tipos'] = array(
								array('key'=>'alojamiento','value'=>'Alojamiento'),
								array('key'=>'paquete','value'=>'Paquete'),
								array('key'=>'transporte','value'=>'Transporte'),
								array('key'=>'seguros','value'=>'Seguro Médico'),
							);
							
	$this->load->model('Operador_model', 'Operador');
	$this->data['operadores'] = $this->Operador->getList();
  }
  
}