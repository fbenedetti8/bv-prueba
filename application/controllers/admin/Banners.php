<?php
include "AdminController.php";

class Banners extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Banner_model', 'Banner');
		$this->model = $this->Banner;
		$this->page = "banners";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Banners";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}
	
	function onEditReady($id='') {
		$this->data['regiones'] = $this->Region->getAll(100, 0, 'pais', 'asc')->result();
		$this->data['idiomas'] = $this->Idioma->getAll(100, 0, 'nombre', 'asc')->result();
		$this->data['estados'] = array(
			array('id' => 2, 'nombre' => 'Borrador'),
			array('id' => 1, 'nombre' => 'Publicado'),
			array('id' => 0, 'nombre' => 'Oculto'),
		);

		if ($id) {
			$this->breadcrumbs[] = ($id!='')?$this->data['row']->nombre:'';
		
			$traducciones = $this->model->getTranslations($id);
			foreach ($traducciones as $lang=>$langrow) {
				foreach ($langrow as $key=>$value) {
					$this->data['row']->{$key.'_'.$lang} = $value;
				}
			}

			$this->Segmentacion->filters = "objeto_id = ".$id." and tipo_id = ".TYPE_BANNER;
			$this->data['segmentaciones'] = $this->Segmentacion->getAll()->result();
		}
		else {
			$this->data['segmentaciones'] = array();
		}
	}	

	function onBeforeSave() {
		if (!isset($_POST['desktop'])) $_POST['desktop'] = 0;
		if (!isset($_POST['mobile'])) $_POST['mobile'] = 0;
		if (!isset($_POST['viajeros'])) $_POST['viajeros'] = 0;
		if (!isset($_POST['agencias'])) $_POST['agencias'] = 0;

		$id = $this->input->post('id');
		if (!$id) {
			$id = entityid(TYPE_BANNER);
			$_POST['id'] = $id;
		}

		if (isset($_POST['opciones'])) {
			$_POST['opciones'] = serialize($_POST['opciones']);
		}
	}

	function onAfterDelete($id) {
		deleteEntity(TYPE_BANNER, $id);
	}

	function onAfterSave($id){
		//guardar segmentacion
		$this->Segmentacion->saveSegmentacion($id, $_POST, TYPE_BANNER);
	}

	function duplicate($id) {
		$this->model->duplicate($id);

		redirect($this->data['route']);
	}	
		
}