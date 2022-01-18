<?php
include "AdminController.php";

class Asuntos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Asunto_model', 'Asunto');
		$this->model = $this->Asunto;
		$this->page = "asuntos";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Asuntos";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}
	
	function onEditReady($id='') {
		if ($id) {
			$traducciones = $this->model->getTranslations($id);
			foreach ($traducciones as $lang=>$langrow) {
				foreach ($langrow as $key=>$value) {
					$this->data['row']->{$key.'_'.$lang} = $value;
				}
			}
		}
	}	

	function onBeforeSave() {
		$id = $this->input->post('id');
		if (!$id) {
			$id = entityid(TYPE_ASUNTO);
			$_POST['id'] = $id;
		}
	}

	function onAfterDelete($id) {
		deleteEntity(TYPE_ASUNTO, $id);
	}

		
}