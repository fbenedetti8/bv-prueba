<?php
include "AdminController.php";

class Interesados_destinos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Destino_foto_model', 'Destino_foto');
		$this->model = $this->Destino;
		$this->page = "interesados_destinos";
		$this->data['currentModule'] = "consultas";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Interesados a Destinos";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;		
		$this->load->model('Lista_espera_model', 'Lista_espera');
	}

	/* Exporta la lista de Interesados para la categoria */
	function exportar_lista_espera($id){
		$results = $this->Lista_espera->getAllExport($id,'destino');
		$this->page = "lista-de-interesados-destino-".$id;
		parent::exportar($results);
	}
	
}