<?php
include "AdminController.php";

class Ordenes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Orden_model', 'Orden');
		$this->model = $this->Orden;
		$this->page = "ordenes";
		$this->data['currentModule'] = "reservas";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Ordenes";
		$this->limit = 30;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Destino_model', 'Destino');
		$this->data['destinos'] = $this->Destino->getList('', 'nombre asc');

		ini_set('display_errors',true);
		error_reporting(E_ALL);
	}
		
	function filtros(){

		$this->model->filters = '1=1';
		
		$this->data['destino_id'] = '';
		//filtro de destino
		if(isset($_GET['destino_id']) && $_GET['destino_id'] != ''){
			$this->data['destino_id'] = $_GET['destino_id'];
			$this->model->filters .= ' and p.destino_id = '.$_GET['destino_id'];
			$this->session->set_userdata('destino_id',$_GET['destino_id']);
		}
		else{
			if(isset($_GET['destino_id']) && $_GET['destino_id'] == ''){
				$this->session->unset_userdata('destino_id');
			}
			
			if($this->session->userdata('destino_id')){
				$this->data['destino_id'] = $this->session->userdata('destino_id');
				$this->model->filters .= ' and p.destino_id = '.$this->session->userdata('destino_id');
			}
		}
		
		$this->data['visibilidad'] = '';
		//filtro de vencida
		if(isset($_GET['visibilidad']) && $_GET['visibilidad'] != ''){
			$this->data['visibilidad'] = $_GET['visibilidad'];
			if($_GET['visibilidad']=='vencidas'){
				$this->model->filters .= ' and bv_ordenes.vencida = 1';
			}
			else{
				$this->model->filters .= ' and (bv_ordenes.vencida = 0 or bv_ordenes.vencida is null)';	
			}
			
			$this->session->set_userdata('visibilidad',$_GET['visibilidad']);
		}
		else{
			if(isset($_GET['visibilidad']) && $_GET['visibilidad'] == ''){
				$this->session->unset_userdata('visibilidad');
			}
			
			if($this->session->userdata('visibilidad')){
				$this->data['visibilidad'] = $this->session->userdata('visibilidad');
				
				if($this->session->userdata('visibilidad')=='vencidas'){
					$this->model->filters .= ' and bv_ordenes.vencida = 1';
				}
				else{
					$this->model->filters .= ' and (bv_ordenes.vencida = 0 or bv_ordenes.vencida is null)';	
				}
			}
		}

		if (!$this->session->userdata('sort')) {
			$this->data['sort'] = "bv_ordenes.fecha_orden";
			$this->data['sortType'] = "DESC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}

	}

	function export(){
		$this->filtros();
		
		$data = $this->model->getAllExport(99999,0,'bv_ordenes.id','desc')->result();
		
		exportExcel($data,$filename=$this->page);
	}

	function index(){
		$this->filtros();
		
		parent::index();
	}
	
	function restablecer($id){
		//marco la orden como vigente y le pongo la fecha actual para que tome 1 hora más de vigencia
		$this->model->update($id,['vencida'=>0,
								  'mail_prereserva'=>'0',
								  'fecha_orden'=> date('Y-m-d H:i:s')
								]);

		$arr = [];
		$arr['status']='SUCCESS';
		$arr['msg']='El reestablecimiento se realizó correctamente.';
		echo json_encode($arr);
	}

}