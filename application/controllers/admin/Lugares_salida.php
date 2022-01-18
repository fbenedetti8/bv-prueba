<?php
include "AdminController.php";

class Lugares_salida extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Lugar_salida_model', 'Lugar');
		$this->model = $this->Lugar;
		$this->page = "lugares_salida";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "lugares_salida";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Lugares de Salida";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
}