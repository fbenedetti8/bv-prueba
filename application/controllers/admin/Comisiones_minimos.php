<?php
include "AdminController.php";

class Comisiones_minimos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Comisiones_minimos_model', 'Minimo');
		$this->model = $this->Minimo;
		$this->page = "comisiones_minimos";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Minimos no Comisionables";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}

	//redefino porque el ID corresponde al codigo de ROL
	function edit($rol,$anio=false) {
		$this->data['rol'] = $rol;
		$this->data['anio'] = $anio;

		if(!$anio){
			$anio = date('Y');
			$this->data['anio'] = $anio;
		}
		//Get data
		$this->model->filters = "admin_id is null and vendedor_id is null and rol = '".$rol."' and anio = '".$anio."'";
		$results = $this->model->getAll(999,0,'id','asc')->result();

		$datos = [];
		//armo array de valor minimo por mes año
		foreach ($results as $r) {
			$datos[$r->mes]['valor_mnc'] = $r->valor_mnc;
		}

		$this->data['datos'] = $datos;
		
		$this->onEditReady($rol);
		
		$this->load->view('admin/' . $this->page . '_form', $this->data);
	}

	function onEditReady($rol='') {
		if ($rol) {
			$this->breadcrumbs[] = ($rol!='') ? rol($rol) : '';
		}

		$this->data['roles'] = [
			['id' => 'VEN', 'nombre' => 'Vendedor'],
			['id' => 'LID', 'nombre' => 'Lider'],
			['id' => 'GER', 'nombre' => 'Gerente'],
		];
	}

	function onBeforeSave() {
		extract($_POST);

		if(isset($rol) && $rol && isset($anio) && $anio){
			//borro las asocianoes previas de este rol
			//$this->model->deleteWhere(['rol'=>$rol,'anio'=>$anio]);
			$this->db->query("delete from bv_comisiones_minimos where admin_id is null and vendedor_id is null and rol = '".$rol."' and anio = '".$anio."'");

			$valores = (isset($valor_mnc) && $valor_mnc) ? $valor_mnc : []; 
			foreach($valores as $mes => $num){
				$datains = [];
				$datains['rol'] = $rol;
				$datains['mes'] = $mes;
				$datains['anio'] = $anio;
				$datains['valor_mnc'] = $num;
				$this->model->insert($datains);
			}
		}
		
		redirect(site_url('admin/comisiones_minimos/edit/'.$rol.'/'.$anio));
	}

	function validar() {
		/*$this->form_validation->set_rules('valor_mnc','Valor','required', 
			array('required' => 'Por favor ingresá el valor minimo no comisionable')
		);*/
		$this->form_validation->set_rules('rol','Rol','required', 
			array('required' => 'Por favor selecciona el rol')
		);
		/*$this->form_validation->set_rules('fecha_desde','Fecha de inicio','required', 
			array('required' => 'Por favor selecciona la fecha de inicio')
		);
		$this->form_validation->set_rules('fecha_hasta','Fecha de finalizacion','required', 
			array('required' => 'Por favor selecciona la fecha de finalizacion')
		);*/
		
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