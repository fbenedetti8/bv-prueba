<?php
include "AdminController.php";

class Categorias extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Categoria_model', 'Categoria');
		$this->model = $this->Categoria;
		$this->page = "categorias";
		$this->data['currentModule'] = "categorias";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Categorias Regionales";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->uploadsFolder = 'categorias';
		$this->uploads = array(
			'imagen' => ['type' => 'image', 'width' => 1905, 'height' => 350],
			'imagen_mobile' => ['type' => 'image', 'width' => 744, 'height' => 419]
		);
		
		$this->load->model('Lista_espera_model', 'Lista_espera');
	}

	function onEditReady($id='') {
		$this->data['categorias'] = $this->model->getList('', 'nombre asc');
	}

	/* Exporta la lista de Interesados para la categoria */
	function exportar_lista_espera($id){
		$results = $this->Lista_espera->getAllExport($id,'categoria');
		$this->page = "lista-de-interesados-categoria-".$id;
		parent::exportar($results);
	}
	
	function onBeforeSave($id='') {
		$_POST['otros'] = @$_POST['otros'] ? $_POST['otros'] : 0;	
	}
	
}