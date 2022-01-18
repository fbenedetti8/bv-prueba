<?php
include "AdminController.php";

class Admins extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['defaultSort'] = "id";
		$this->load->model('Admin_model', 'Admin');
		$this->model = $this->Admin;
		$this->page = "admins";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Administradores";
		$this->limit = 50;
		$this->init();
        $this->validate = true;		

        $this->load->model('Comisiones_minimos_model','Comisiones_minimos');
	}
	
	function onAfterSave($id) {
		$this->Comisiones_minimos->deleteWhere(['admin_id'=>$id]);

		$rol = isset($_POST['perfil']) ? $_POST['perfil'] : false;

		if($rol && $id){
			$valores = (isset($_POST['valor_mnc']) && $_POST['valor_mnc']) ? $_POST['valor_mnc'] : []; 
			
			foreach($valores as $anio => $data_valores){
				foreach($data_valores as $mes => $num){
					$datains = [];
					$datains['rol'] = $rol;
					$datains['admin_id'] = $id;
					$datains['mes'] = $mes;
					$datains['anio'] = $anio;
					$datains['valor_mnc'] = $num;
			
					$this->Comisiones_minimos->insert($datains);
				}
			}
		}		
	}

	function onBeforeSave() {



		if (strlen($_POST['password']) > 0 && strlen($_POST['password']) < 30) {
			$_POST['password'] = md5($_POST['password']);
			$_POST['password2'] = md5($_POST['password2']);
		}

        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('usuario', 'Usuario', 'required');
		//$this->form_validation->set_rules('password', 'Password', 'required|matches[password2]');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($_POST['activo'] == 1) {
			$_POST['intentos'] = 0;
		}

		if (!isset($_POST['minimos_personalizados'])) $_POST['minimos_personalizados'] = 0;
		if (!isset($_POST['comisiones_personalizadas'])) $_POST['comisiones_personalizadas'] = 0;

		if (isset($_POST['perfil']) && $_POST['perfil'] == 'VEN') {
			$this->form_validation->set_rules('vendedor_id', 'Vendedor asociado', 'required');
		}

	}
    	
	function onEditReady($id='') {
		if ($id) {
			$this->breadcrumbs[] = ($id!='')?$this->data['row']->nombre:'';
		}

		$this->data['perfiles'] = [
			['id' => 'SUP', 'perfil' => 'Superadmin'],
			['id' => 'ADM', 'perfil' => 'Administración'],
			['id' => 'OPE', 'perfil' => 'Operaciones'],
			['id' => 'VEN', 'perfil' => 'Ventas'],
		];

		$this->load->model('Comisiones_escalas_model', 'Escala');
		$this->data['escalas'] = $this->Escala->getList('', 'nombre asc');		
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->data['vendedores'] = $this->Vendedor->getListado();		

		//DATA COMISIONES MINIMO
		$this->Comisiones_minimos->filters = "admin_id = '".$id."'";
		$results = $this->Comisiones_minimos->getAll(999,0,'id','asc')->result();

		$datos = [];
		//armo array de valor minimo por mes año
		foreach ($results as $r) {
			$datos[$r->anio][$r->mes]['valor_mnc'] = $r->valor_mnc;
		}

		$this->data['datos'] = $datos;
	}	
		
}