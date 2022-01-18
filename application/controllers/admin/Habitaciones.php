<?php
include "AdminController.php";

class Habitaciones extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Habitacion_model', 'Habitacion');
		$this->model = $this->Habitacion;
		$this->page = "habitaciones";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "habitaciones";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Tipos de Habitaciones";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
	
}