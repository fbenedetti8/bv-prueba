<?php
include "AdminController.php";

class Vendedores extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->model = $this->Vendedor;
		$this->page = "vendedores";
		$this->data['currentModule'] = "vendedores";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Vendedores";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Sucursal_model','Sucursal');
		$this->data['sucursales'] = $this->Sucursal->getAll(999,0,'nombre','asc')->result();
		
		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();

		//no mostrar estos
		/*
		ANULACIÓN RESERVA, COSTO PAQUETE ANULACION, COSTO PAQUETE REGISTRO
		PENALIDAD POR ANULACION
		*/
		$this->load->model('Concepto_model','Concepto');
		$this->Concepto->filters = "id not in(2,56,57,10) and sistema_caja = 0";
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();

		$this->load->model('Comisiones_minimos_model','Comisiones_minimos');
	}

	function index(){
		$this->model->filters = '1=1';
		
		$this->data['es_coordinador'] = '';
		//filtro de activo
		if(isset($_GET['es_coordinador']) && $_GET['es_coordinador'] != ''){
			$this->data['es_coordinador'] = $_GET['es_coordinador'];
			$this->model->filters .= ' and es_coordinador = '.($_GET['es_coordinador']=='1'?1:0);
			$this->session->set_userdata('es_coordinador',$_GET['es_coordinador']);
		}
		else{
			if(isset($_GET['es_coordinador']) && $_GET['es_coordinador'] == ''){
				$this->session->unset_userdata('es_coordinador');
			}
			
			if($this->session->userdata('es_coordinador')){
				$this->data['es_coordinador'] = $this->session->userdata('es_coordinador');
				$this->model->filters .= ' and es_coordinador = '.($this->session->userdata('es_coordinador')=='1'?1:0);
			}
		}
				
		$this->data['tipoviaje'] = '';
		//filtro de grupal
		if(isset($_GET['tipoviaje']) && $_GET['tipoviaje'] != ''){
			$this->data['tipoviaje'] = $_GET['tipoviaje'];
			$this->model->filters .= ' and grupal = '.($_GET['tipoviaje']=='grupales'?1:0);
			$this->session->set_userdata('tipoviaje',$_GET['tipoviaje']);
		}
		else{
			if(isset($_GET['tipoviaje']) && $_GET['tipoviaje'] == ''){
				$this->session->unset_userdata('tipoviaje');
			}
			
			if($this->session->userdata('tipoviaje')){
				$this->data['tipoviaje'] = $this->session->userdata('tipoviaje');
				$this->model->filters .= ' and grupal = '.($this->session->userdata('tipoviaje')=='grupales'?1:0);
			}
		}
		
		parent::index();
	}

	function validar() {
		$this->form_validation->set_rules('nombre','Nombre del Vendedor','required', 
			array('required' => 'Por favor ingresá el nombre del vendedor')
		);
		$this->form_validation->set_rules('apellido','Apellido del Vendedor','required', 
			array('required' => 'Por favor ingresá el nombre del vendedor')
		);
		$this->form_validation->set_rules('email','E-mail del Vendedor','required|valid_email', 
			array(
				'required' => 'Por favor ingresá el e-mail del vendedor',
				'valid_email' => 'Por favor ingresá un e-mail válido',
			)
		);
		$this->form_validation->set_rules('telefono','Teléfono del Vendedor','required', 
			array('required' => 'Por favor ingresá el teléfono del vendedor')
		);
		$this->form_validation->set_rules('cuil','CUIL del Vendedor','required', 
			array('required' => 'Por favor ingresá el CUIL del vendedor')
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

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?($this->data['row']->nombre.' '.$this->data['row']->apellido):'';
		
		$this->data['cuenta_corriente'] = false;
		if(isset($this->data['row']->id) && $this->data['row']->id){
			//$this->cargar_cuenta_corriente($this->data['row']->id,'V',false);
			$this->cargar_cuenta_corriente_vendedor($this->data['row']->id,'V');
		}			

		$this->load->model('Comisiones_escalas_model', 'Escala');
		$this->data['escalas'] = $this->Escala->getList('', 'nombre asc');

		//DATA COMISIONES MINIMO
		$this->Comisiones_minimos->filters = "vendedor_id = '".$id."'";
		$results = $this->Comisiones_minimos->getAll(999,0,'id','asc')->result();

		$datos = [];
		//armo array de valor minimo por mes año
		foreach ($results as $r) {
			$datos[$r->anio][$r->mes]['valor_mnc'] = $r->valor_mnc;
		}

		$this->data['datos'] = $datos;
	}
		
	function onBeforeSave($id='') {
		$_POST['es_coordinador'] = isset($_POST['es_coordinador']) ? $_POST['es_coordinador'] : 0;

		if (strlen($_POST['password']) > 0 && strlen($_POST['password']) < 30) {
			$_POST['password'] = md5($_POST['password']);
			$_POST['password2'] = md5($_POST['password2']);
		}
	
		$_POST['comisiones_personalizadas'] = isset($_POST['comisiones_personalizadas']) ? $_POST['comisiones_personalizadas'] : 0;
		$_POST['minimos_personalizados'] = isset($_POST['minimos_personalizados']) ? $_POST['minimos_personalizados'] : 0;

		if (!empty($_POST['fecha_emision'])) {
			$fecha_emision = $_POST['fecha_emision'];
			$aux2 = explode('/',$fecha_emision);
			$fecha_emision = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
			$_POST['fecha_emision'] = $fecha_emision;
		}

		if (!empty($_POST['fecha_vencimiento'])) {
			$fecha_vencimiento = $_POST['fecha_vencimiento'];
			$aux2 = explode('/',$fecha_vencimiento);
			$fecha_vencimiento = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
			$_POST['fecha_vencimiento'] = $fecha_vencimiento;
		}

		if (!empty($_POST['fechaNacimiento'])) {
			$fechaNacimiento = $_POST['fechaNacimiento'];
			$aux2 = explode('/',$fechaNacimiento);
			$fechaNacimiento = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
			$_POST['fechaNacimiento'] = $fechaNacimiento;
		}
	}
	
	function onAfterSave($id){
		$this->Comisiones_minimos->deleteWhere(['vendedor_id'=>$id]);

		if($id){
			$valores = (isset($_POST['valor_mnc']) && $_POST['valor_mnc']) ? $_POST['valor_mnc'] : []; 
			
			foreach($valores as $anio => $data_valores){
				foreach($data_valores as $mes => $num){
					$datains = [];
					$datains['vendedor_id'] = $id;
					$datains['mes'] = $mes;
					$datains['anio'] = $anio;
					$datains['valor_mnc'] = $num;
			
					$this->Comisiones_minimos->insert($datains);
				}
			}
		}
	}

		
}