<?php
include "AdminController.php";

class Servicios extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Servicio_model', 'Servicio');
		$this->model = $this->Servicio;
		$this->page = "servicios";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "servicios";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Servicios de Alojamientos";
		$this->limit = 50;
		$this->init();
    $this->validate = FALSE;
	}
	
}