<?php
include "AdminController.php";

class Testimonios extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Testimonio_model', 'Testimonio');
		$this->model = $this->Testimonio;
		$this->page = "testimonios";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Testimonios";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->uploadsFolder = 'testimonios';
		$this->uploads = array(
			array(
				'name' => 'imagen',
				'allowed_types' => 'jpg|jpeg|png', 
				'maxsize' => '204800', 
				'folder' => '/uploads/testimonios/',
				'keep' => 'true', 
				'prefix' => '',
				'resizes' => $this->config->item('resizes_testimonios')
			)
		);
	}
	
	function onEditReady($id='') {
	}

}