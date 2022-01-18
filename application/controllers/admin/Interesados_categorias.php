<?php
include "AdminController.php";

class Interesados_categorias extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Categoria_model', 'Categoria');
		$this->model = $this->Categoria;
		$this->page = "interesados_categorias";
		$this->data['currentModule'] = "consultas";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Interesados a Categorias";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->load->model('Lista_espera_model', 'Lista_espera');
	}

	/* Exporta la lista de Interesados para la categoria */
	function exportar_lista_espera($id){
		$results = $this->Lista_espera->getAllExport($id,'categoria');
		$this->page = "lista-de-interesados-categoria-".$id;
		parent::exportar($results);
	}
	
}