<?php
include "AdminController.php";

class Contenidos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Contenido_model', 'Contenido');
		$this->model = $this->Contenido;
		$this->page = "contenidos";
		$this->data['currentModule'] = "contenidos";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Contenidos";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;

		$this->load->model('Plantilla_model', 'Plantilla');        
		$this->load->model('Promocion_model', 'Promocion');        
	}
	
	function onEditReady($id='') {
		$this->data['regiones'] = $this->Region->getAll(100, 0, 'pais', 'asc')->result();
		$this->data['idiomas'] = $this->Idioma->getAll(100, 0, 'nombre', 'asc')->result();
		$this->data['plantillas'] = $this->Plantilla->getAll(100, 0, 'nombre', 'asc')->result();
		$this->data['promociones'] = $this->Promocion->getPromocionesDisponibles();

		$this->data['estados'] = array(
			array('id' => 2, 'nombre' => 'Borrador'),
			array('id' => 1, 'nombre' => 'Publicado'),
			array('id' => 0, 'nombre' => 'Oculto'),
		);
		$this->data['modos'] = array(array('id' => 'VIAJEROS', 'titulo' => 'Viajeros'), array('id' => 'AGENCIAS', 'titulo' => 'Agencias'));
		
		if ($id) {
			$this->breadcrumbs[] = ($id!='')?$this->data['row']->titulo:'';

			$plantilla = $this->Plantilla->get( $this->data['row']->plantilla_id )->row();
			if ($plantilla) {

				$contenido = html_entity_decode($plantilla->html);
				$fields = array();
				$regex = '#\[\[(.*?)\]\]#';
				if (preg_match_all($regex, $contenido ,$matches)) {
					foreach ($matches[1] as $field) {
						parse_str($field);
						$fields[] = $field;
					}
				}

				$this->data['fields'] = $fields;

				$traducciones = $this->model->getTranslations($id);
				foreach ($traducciones as $lang=>$langrow) {
					foreach ($langrow as $row) {
						$this->data['row']->{$row->campo.'_'.$lang} = $row->valor;
					}
				}

				$this->Segmentacion->filters = "objeto_id = ".$id." and tipo_id = ".TYPE_CONTENIDO;
				$this->data['segmentaciones'] = $this->Segmentacion->getAll()->result();				

			}
			else {
				$this->data['segmentaciones'] = array();

				$this->data['fields'] = array();
			}
		}
	}	

	function onBeforeSave() {
		$id = $this->input->post('id');
		if (!$id) {
			$id = entityid(TYPE_CONTENIDO);
			$_POST['id'] = $id;
		}

		if (!isset($_POST['desktop'])) $_POST['desktop'] = 0;
		if (!isset($_POST['mobile'])) $_POST['mobile'] = 0;
		if (!isset($_POST['menu'])) $_POST['menu'] = 0;
		if (!isset($_POST['minifooter'])) $_POST['minifooter'] = 0;
		if (!isset($_POST['setting_volver'])) $_POST['setting_volver'] = 0;
		if (!isset($_POST['widget_chat'])) $_POST['widget_chat'] = 0;
		if (!isset($_POST['widget_email'])) $_POST['widget_email'] = 0;
		if (!isset($_POST['widget_telefono'])) $_POST['widget_telefono'] = 0;	
	}

	function onAfterSave($id) {
		$this->Contenido->clearFields($id);

		foreach ($_POST as $key=>$value) {
			$field = explode("_", $key);

			if (count($field) == 2) {
				$this->Contenido->saveField($id, $field[0], $field[1], $value);
			}
		}

		//guardar segmentacion
		$this->Segmentacion->saveSegmentacion($id, $_POST, TYPE_CONTENIDO);
	}

	function onAfterDelete($id) {
		deleteEntity(TYPE_CONTENIDO, $id);
	}

	function duplicate($id) {
		$this->model->duplicate($id);

		redirect($this->data['route']);
	}

		
}