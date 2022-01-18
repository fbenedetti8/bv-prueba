<?php
include "AdminController.php";

class Paradas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Parada_model', 'Parada');
		$this->model = $this->Parada;
		$this->page = "paradas";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "paradas";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Paradas";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
	function onEditReady($id=''){
		
		$this->load->model('Lugar_salida_model', 'Lugar');
		$this->data['lugares'] = $this->Lugar->getList('', 'nombre asc');
		
	}
	
}