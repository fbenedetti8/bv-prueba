<?php
include "AdminController.php";

class Celulares_contacto extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Celular_contacto_model', 'Celular_contacto');
		$this->model = $this->Celular_contacto;
		$this->page = "celulares_contacto";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "celulares_contacto";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Celulares de contacto";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
}