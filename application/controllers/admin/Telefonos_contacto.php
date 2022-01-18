<?php
include "AdminController.php";

class Telefonos_contacto extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Telefonos_contacto_model', 'TelefonosContacto');
		$this->model = $this->TelefonosContacto;
		$this->page = "telefonos_contacto";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Teléfonos de contacto";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->load->model('Pais_model', 'Paises');
		$this->data['paises'] = $this->Paises->getList('', 'nombre asc');

		$this->uploadsFolder = 'telefonos_contacto';
		$this->uploads = array(
			array(
				'name' => 'imagen',
				'allowed_types' => 'jpg|jpeg|png', 
				'maxsize' => '204800', 
				'folder' => '/uploads/telefonos_contacto/',
				'keep' => 'true', 
				'prefix' => ''
			)
		);
	}
	
	function index(){
		$this->model->filters = '1=1';
		
		$this->data['id_pais'] = '';
		//filtro de destino
		if(isset($_GET['id_pais']) && $_GET['id_pais'] != ''){
			$this->data['id_pais'] = $_GET['id_pais'];
			$this->model->filters .= ' and id_pais = '.$_GET['id_pais'];
			$this->session->set_userdata('id_pais',$_GET['id_pais']);
		}
		else{
			if(isset($_GET['id_pais']) && $_GET['id_pais'] == ''){
				$this->session->unset_userdata('id_pais');
			}
			
			if($this->session->userdata('id_pais')){
				$this->data['id_pais'] = $this->session->userdata('id_pais');
				$this->model->filters .= ' and id_pais = '.$this->session->userdata('id_pais');
			}
		}

		parent::index();
	}

	function onEditReady($id='') {
	}

}