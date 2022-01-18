<?php
include "AdminController.php";

class Regimenes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Regimen_model', 'Regimen');
		$this->model = $this->Regimen;
		$this->page = "regimenes";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "regimenes";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Regímenes de Comidas";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
}