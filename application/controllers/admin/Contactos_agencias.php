<?php
include "AdminController.php";

class Contactos_agencias extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Contacto_model', 'Contacto');
		$this->model = $this->Contacto;
		$this->page = "contactos_agencias";
		$this->data['currentModule'] = "consultas";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Contactos de Agencias";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?$this->data['row']->nombre:'';
	}
		
	function index(){
		$this->model->filters = "agencia = 1";
		parent::index();
	}

	function onBeforeExport(){
		$this->model->filters = "agencia = 1";
	}
	
}