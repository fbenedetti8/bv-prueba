<?php
include "AdminController.php";

class Preguntas_categorias extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Preguntas_categorias_model', 'Categorias');
		$this->model = $this->Categorias;
		$this->page = "preguntas_categorias";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Categorías de Preguntas";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}

}