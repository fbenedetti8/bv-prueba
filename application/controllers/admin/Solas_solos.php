<?php
include "AdminController.php";

class Solas_solos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Solasolo_model', 'Solasolo');
		$this->model = $this->Solasolo;
		$this->page = "solas_solos";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Solas y Solos";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->uploadsFolder = 'solas_solos';
		$this->uploads = array(
			'imagen' => ['type' => 'image', 'width' => 680, 'height' => 310]
		);
	}

}