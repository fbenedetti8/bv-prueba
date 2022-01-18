<?php
include "AdminController.php";

class Comisiones_porcentajes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Comisiones_porcentajes_model', 'Comision');
		$this->model = $this->Comision;
		$this->page = "comisiones_porcentajes";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Porcentajes de Comisiones";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}

	//redefino porque el ID corresponde al codigo de ROL
	function edit($rol,$anio=false) {
		$this->data['rol'] = $rol;
		
		//Get data
		$this->model->filters = "rol = '".$rol."'";
		$results = $this->model->getAll(999,0,'id','asc')->result();

		$datos = [];
		//armo array de comisiones por mes año
		foreach ($results as $r) {
			$datos[$r->mes][$r->escala_id]['comision'] = $r->comision;
			$datos[$r->mes][$r->escala_id]['comision_eq'] = $r->comision_eq;
		}

		$this->data['datos'] = $datos;
		
		$this->onEditReady($rol);
		
		$this->load->view('admin/' . $this->page . '_form', $this->data);
	}

	function onEditReady($rol='') {
		if ($rol) {
			$this->breadcrumbs[] = ($rol!='') ? rol($rol) : '';
		}

		$this->load->model('Comisiones_escalas_model', 'Escala');
		$this->data['escalas'] = $this->Escala->getList('', 'id asc');
	}

	function onBeforeSave() {
		extract($_POST);

		if(isset($rol) && $rol){
			//borro las asocianoes previas de este rol
			$this->model->deleteWhere(['rol'=>$rol]);

			$comisiones = (isset($comision) && $comision) ? $comision : []; 
			$comisiones_eq = (isset($comision_eq) && $comision_eq) ? $comision_eq : []; 
			foreach($comisiones as $escala_id=>$valores){
				foreach($valores as $mes => $porcentaje){
					$val_comision = $porcentaje;
					$val_comision_eq = @$comisiones_eq[$escala_id][$mes];

					$datains = [];
					$datains['rol'] = $rol;
					$datains['mes'] = $mes;
					$datains['escala_id'] = $escala_id;
					$datains['comision'] = $val_comision;
					$datains['comision_eq'] = $val_comision_eq;
					$this->model->insert($datains);
				}
			}
		}
		
		redirect(site_url('admin/comisiones_porcentajes/edit/'.$rol));
	}

	function validar() {
		$this->form_validation->set_rules('rol','Rol','required', 
			array('required' => 'Por favor selecciona el rol')
		);
		/*$this->form_validation->set_rules('escala_id','Escala','required', 
			array('required' => 'Por favor selecciona la escala')
		);
		$this->form_validation->set_rules('comision','Comision','required', 
			array('required' => 'Por favor ingresá el valor de la comision')
		);
		$this->form_validation->set_rules('comision_eq','Comision de equipo','required', 
			array('required' => 'Por favor ingresá el valor de la comision de equipo')
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