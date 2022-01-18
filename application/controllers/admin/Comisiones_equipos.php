<?php
include "AdminController.php";

class Comisiones_equipos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Comisiones_equipos_model', 'Equipo');
		$this->model = $this->Equipo;
		$this->page = "comisiones_equipos";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Equipos";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}

	function onEditReady($id='') {
		if ($id) {
			$this->breadcrumbs[] = ($id!='') ? $this->data['row']->nombre : '';

			if ($this->data['row']->coordinador_tipo == 'A') {
				$this->data['row']->coordinador_id = -$this->data['row']->coordinador_id;
			}
			
			if ($this->data['row']->gerente_tipo == 'A') {
				$this->data['row']->gerente_id = -$this->data['row']->gerente_id;
			}			

			$this->data['miembros'] = $this->Equipo->getMiembros($id);
		}
		else {
			$this->data['miembros'] = [];
		}

		$this->data['roles'] = [
			['id' => 'VEN', 'nombre' => 'Vendedor'],
			['id' => 'LID', 'nombre' => 'Lider'],
			['id' => 'GER', 'nombre' => 'Gerente'],
		];


		$this->load->model('Vendedor_model', 'Vendedor');
		$this->data['vendedores_ext'] = $this->Vendedor->getListado();
		//este query trae todos los vendedores, tanto externos como internos (admins con perfil vendedor)
		$this->data['vendedores'] = $this->Vendedor->getListadoCompleto();
	}
	
	function onBeforeSave() {
		if ($_POST['coordinador_id'] < 0) {
			$_POST['coordinador_tipo'] = 'A';
		}
		else {
			$_POST['coordinador_tipo'] = 'V';
		}

		if ($_POST['gerente_id'] < 0) {
			$_POST['gerente_tipo'] = 'A';
		}
		else {
			$_POST['gerente_tipo'] = 'V';
		}
		
		$_POST['coordinador_id'] = abs($_POST['coordinador_id']);
		$_POST['gerente_id'] = abs($_POST['gerente_id']);
		
		if ($_POST['id'] == '') {
			$this->continuar = TRUE;
		}
		else {
			$this->continuar = FALSE;
		}
	}
	
	function onAfterSave($id) {
		if ($this->continuar) {
			redirect(site_url('admin/comisiones_equipos/edit/'.$id));
		}
	}

	function validar() {
		$this->form_validation->set_rules('nombre','Nombre','required', 
			array('required' => 'Por favor ingresá el nombre del equipo')
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

	function agregar_miembro() {
		$equipo_id = $this->input->post('equipo');
		$vendedor_id = $this->input->post('vendedor');

		$existe = $this->Equipo->getMiembro($vendedor_id);
		if ($existe) {
			if ($existe->equipo_id == $equipo_id) {
				$error = 'El vendedor ya pertenece a este equipo';
			}
			else {
				$error = 'El vendedor ya pertenece a otro equipo';
			}

			echo json_encode(['success' => FALSE, 'error' => $error]);
		}
		else {
			$this->Equipo->addMiembro($equipo_id, $vendedor_id);

			$miembros = $this->Equipo->getMiembros($equipo_id);
			$html = $this->load->view('admin/comisiones_equipos_miembros', array('miembros' => $miembros), TRUE);

			echo json_encode(['success' => TRUE, 'html' => $html]);
		}
	}

	function quitar_miembro() {
		$equipo_id = $this->input->post('equipo_id');
		$id = $this->input->post('id');

		$this->Equipo->removeMiembro($id);

		$miembros = $this->Equipo->getMiembros($equipo_id);
		$html = $this->load->view('admin/comisiones_equipos_miembros', array('miembros' => $miembros), TRUE);

		echo json_encode(['success' => TRUE, 'html' => $html]);
	}

	function delete($id) {
		$this->Equipo->deleteMiembros($id);
		parent::delete($id);
	}
}