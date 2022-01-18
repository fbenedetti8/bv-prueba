<?php
include "AdminController.php";

class Comisiones_escalas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Comisiones_escalas_model', 'Escala');
		$this->model = $this->Escala;
		$this->page = "comisiones_escalas";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Escalas de Comisiones";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}

	function onEditReady($id='') {
		if ($id) {
			$this->breadcrumbs[] = ($id!='') ? $this->data['row']->nombre : '';
		}
	}

	function validar() {
		$this->form_validation->set_rules('nombre','Nombre','required', 
			array('required' => 'Por favor ingresá el nombre de la escala')
		);
		$this->form_validation->set_rules('desde','Desde','required', 
			array('required' => 'Por favor ingresá el valor inicial de la escala')
		);
		$this->form_validation->set_rules('hasta','Hasta','required', 
			array('required' => 'Por favor ingresá el valor final de la escala')
		);
		
		if ($this->form_validation->run() == FALSE) {
			$data['success'] = FALSE;
			$data['error'] = validation_errors();
		}
		else {
			$data['success'] = TRUE;
		}

		echo json_encode($data);
	}

}