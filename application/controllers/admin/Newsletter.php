<?php
include "AdminController.php";

class Newsletter extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Newsletter_model', 'Newsletter');
		$this->model = $this->Newsletter;
		$this->page = "newsletter";
		$this->data['currentModule'] = "consultas";
		$this->data['page'] = $this->page;
    	$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Suscriptos a Newsletter";
		$this->limit = 50;
		$this->init();
    	$this->validate = FALSE;
	}

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?$this->data['row']->email:'';
	}
		
}