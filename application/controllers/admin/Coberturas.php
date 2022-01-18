<?php
include "AdminController.php";

class Coberturas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Cobertura_model', 'Cobertura');
		$this->model = $this->Cobertura;
		$this->page = "coberturas";
		$this->data['currentModule'] = "servicios";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['uploadFolder'] = "coberturas";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Coberturas";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;

		$this->uploadsFolder = './uploads/';
		$this->uploads = array(
			array(
				'name' => 'archivo',
				'prefix' => '',
				'keep' => true,
				'allowed_types' => 'pdf',
				'maxsize' => '10000000',
				'folder' => '/uploads/coberturas/'
			),
		);
	}
	
	function onEditReady($id='') {
		$this->data['tipos'] = array(
			array('tipo' => 'Cobertura'),
			array('tipo' => 'Detalle de Coberturas'),
			array('tipo' => 'Tarifario Completo'),
		);

		$this->load->model('Servicio_model', 'Servicio');
		$this->data['servicios'] = $this->Servicio->getList('', 'titulo asc');
	}	

	function onBeforeSave() {
		$id = $this->input->post('id');
		if (!$id) {
			$id = entityid(TYPE_COBERTURA);
			$_POST['id'] = $id;
		}
	}

	function onAfterDelete($id) {
		deleteEntity(TYPE_COBERTURA, $id);
	}

}