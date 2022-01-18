<?php
include "AdminController.php";

class Oficina extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Oficina_model', 'Oficina');
		$this->model = $this->Oficina;
		$this->page = "oficina";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Oficina Feliz";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->uploadsFolder = 'oficinafeliz';
		$this->uploads = array(
			'imagen' => ['type' => 'image', 'width' => 604, 'height' => 417]
		);
	}

}