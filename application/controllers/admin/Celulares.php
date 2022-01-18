<?php
include "AdminController.php";

class Celulares extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Celular_model', 'Celular');
		$this->model = $this->Celular;
		$this->page = "celulares";
		$this->data['currentModule'] = "paquetes";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "celulares";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Celulares para Viajes";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
}