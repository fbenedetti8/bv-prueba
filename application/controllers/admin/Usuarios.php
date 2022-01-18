<?php
include "AdminController.php";

class Usuarios extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Usuario_model', 'Usuario');
		$this->model = $this->Usuario;
		$this->page = "usuarios";
		$this->data['currentModule'] = "reservas";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Usuarios";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();
		
		$this->load->model('Concepto_model','Concepto');
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();
				
		$this->data['dietas'] = array(
									array('nombre' => 'Vegetariano'),
									array('nombre' => 'Celíaco'),
									array('nombre' => 'Diabético'),
									array('nombre' => 'Ninguno'),
								);
		$this->data['sexos'] = array(
									array('id' => 'femenino','nombre' => 'Femenino'),
									array('id' => 'masculino','nombre' => 'Masculino'),
								);
		
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Factura_model', 'Factura');
	}

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?($this->data['row']->nombre.' '.$this->data['row']->apellido):'';
		
		if($id){
			$this->cargar_cuenta_corriente($id,'U');
		}
	}
		
	function onBeforeSave($id='') {
		if(isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']){
			$aux = explode('/',$_POST['fecha_nacimiento']);
			$_POST['fecha_nacimiento'] = $aux[2].'-'.$aux[1].'-'.$aux[0];
		}
		if(isset($_POST['fecha_emision']) && $_POST['fecha_emision']){
			$aux = explode('/',$_POST['fecha_emision']);
			$_POST['fecha_emision'] = $aux[2].'-'.$aux[1].'-'.$aux[0];
		}
		if(isset($_POST['fecha_vencimiento']) && $_POST['fecha_vencimiento']){
			$aux = explode('/',$_POST['fecha_vencimiento']);
			$_POST['fecha_vencimiento'] = $aux[2].'-'.$aux[1].'-'.$aux[0];
		}
	}
	
	function onAfterSave($id) {
		
		if($id && isset($_POST['btnvolver']) && $_POST['btnvolver']){
			redirect($this->data['route'].'/edit/'.$id.'?saved=1');
		}
	}

	function delete($id) {
		redirect(site_url('admin/usuarios'));
	}
		
}