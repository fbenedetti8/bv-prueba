<?php
include "AdminController.php";

class Plantillas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['defaultSort'] = "orden";
		$this->load->model('Plantilla_model', 'Plantilla');
		$this->model = $this->Plantilla;
		$this->page = "plantillas";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Plantillas";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}
	
	function onEditReady($id='') {
		if ($id) {
			$this->breadcrumbs[] = ($id!='')?$this->data['row']->nombre:'';
		}
	}	
		
}