<?php
include "AdminController.php";

class Reservas_vendedor extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Reserva_model', 'Reserva');	
		$this->load->model('Reserva_estado_model', 'Reserva_estado');	
		$this->model = $this->Reserva;
		$this->page = "reservas_vendedor";
		$this->data['currentModule'] = "reservas_vendedor";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Reservas del vendedor";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		ini_set('display_errors',true);
		error_reporting(E_ALL);

		$estados = $this->Reserva_estado->getAll('','');
		$this->data['estados'] = $estados->result();
	}

	function index(){		
		//filtro estado
		if(isset($_POST['estado'])){
			$estado = $_POST['estado'] ? $_POST['estado'] : '';
		}
		else{
			$estado = '';
		}
		$this->data['estado'] = $estado;
			
		//filtro año
		if(isset($_POST['anio'])){
			$anio = $_POST['anio'] ? $_POST['anio'] : '';
		}
		else{
			$anio = date('Y');
		}
		$this->data['anio'] = $anio;
		
		//filtro mes
		if(isset($_POST['mes'])){
			$mes = $_POST['mes'] ? $_POST['mes'] : '';
		}
		else{
			$mes = date('m');
		}
		$this->data['mes'] = $mes;
		
		$filters = [];
		$filters['anio'] = $anio;
		$filters['mes'] = $mes;
		$filters['estado'] = $estado;

		if (!$this->session->userdata('sort')) {
			$this->data['sort'] = "P.codigo";
			$this->data['sortType'] = "ASC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}
		$this->pconfig['uri_segment'] = 4;
		$this->pconfig['per_page'] = 100;
		
		$this->data['data'] = $this->model->getReservasVendedor($this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']),$this->data['sort'],$this->data['sortType'],$filters);

		if($_SERVER['REMOTE_ADDR'] == '190.19.110.158'){
			#echo $this->db->last_query();
		}

		$this->data['totalRows'] = count($this->data['data']);
		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';
		
		$this->load->view('admin/' . $this->page, $this->data);
	}
	
		
}
