<?php
include "AdminController.php";

class Contactos_faqs extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Contacto_model', 'Contacto');
		$this->model = $this->Contacto;
		$this->page = "contactos_faqs";
		$this->data['currentModule'] = "consultas";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Contactos de Preguntas Frecuentes";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?$this->data['row']->email:'';
	}
		
	function index(){
		$this->model->filters = "faqs = 1";
		parent::index();
	}

	function onBeforeExport(){
		$this->model->filters = "faqs = 1";
		$this->model->onBeforeExportFaqs();
	}

}