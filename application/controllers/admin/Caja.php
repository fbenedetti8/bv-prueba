<?php
include "AdminController.php";

class Caja extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Caja_model', 'Caja');
		$this->model = $this->Caja;
		$this->page = "caja";
		$this->data['currentModule'] = "caja";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Sistema de Caja";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Sucursal_model','Sucursal');
		$this->data['sucursales'] = $this->Sucursal->getAll(999,0,'nombre','asc')->result();
		
	}

	function filtro_por_mes(){
		/*
		$_POST['fecha_desde'] = '01-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-'.$_POST['anio'];
		$_POST['fecha_hasta'] = '31-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-'.$_POST['anio'];
		*/
		
		$_POST['fecha_desde'] = $_POST['anio'].'-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-01';
		$_POST['fecha_hasta'] = $_POST['anio'].'-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-31';
		
		$this->data['anio'] = $_POST['anio'];
		$this->data['mes'] = $_POST['mes'];
		$this->data['filtro_mes'] = true;
		$this->session->set_userdata('filtro_mes',true);
		$this->session->set_userdata('anio',$_POST['anio']);
		$this->session->set_userdata('mes',$_POST['mes']);
		
		$this->filtrar_fechas();
	}
	
	function filtrar_fechas(){
		if(isset($_POST['fecha_desde']) && isset($_POST['fecha_hasta']) && $_POST['fecha_desde'] != '' && $_POST['fecha_hasta'] != ''){
			$this->model->filters .= ' and bv_caja.fecha >= "'.$_POST['fecha_desde'].' 00:00:00'.'"';
			$this->model->filters .= ' and bv_caja.fecha <= "'.$_POST['fecha_hasta'].' 23:59:59'.'"';
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else if(isset($_POST['fecha_hasta']) && $_POST['fecha_hasta'] != ''){
			$this->model->filters .= ' and bv_caja.fecha <= "'.$_POST['fecha_hasta'].' 23:59:59'.'"';
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
		}
		else if(isset($_POST['fecha_desde']) && $_POST['fecha_desde'] != ''){
			$this->model->filters .= ' and bv_caja.fecha >= "'.$_POST['fecha_desde'].' 00:00:00'.'"';
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else{
			$this->session->unset_userdata('fecha_desde');
			$this->session->unset_userdata('fecha_hasta');
		}
		
	}
	
	function index(){
		//si reseteo
		if(false && isset($_POST['btnReset'])){
			$this->session->unset_userdata('fecha_desde');
			$this->session->unset_userdata('fecha_hasta');
			$this->session->unset_userdata('filtro_mes');
			$this->session->unset_userdata('sucursal_id');
			
			redirect($this->data['route']);
		}
		else{
			if( !$this->session->userdata('fecha_desde') && !$this->session->userdata('fecha_hasta')
				&& (!isset($_POST['fecha_desde']) or !isset($_POST['fecha_hasta']) )){
				$_POST['anio'] = date('Y');
				$_POST['mes'] = date('m');
				$this->filtro_por_mes();
			}
		}
		
		$this->model->filters = '(1=1)';
		
		if(isset($_POST['valor_buscar']) && $_POST['valor_buscar'] != '' && isset($_POST['clave_buscar']) && $_POST['clave_buscar'] != ''){
			$this->model->filters = 'bv_caja.'.$_POST['clave_buscar'].' LIKE "%'.$_POST['valor_buscar'].'%"';
			$this->session->set_userdata('clave_buscar',$_POST['clave_buscar']);
			$this->session->set_userdata('valor_buscar',$_POST['valor_buscar']);
			$this->data['valor_buscar'] = $_POST['valor_buscar'];
			$this->data['clave_buscar'] = $_POST['clave_buscar'];
		}
		else{
			$this->session->unset_userdata('valor_buscar');
			$this->session->unset_userdata('clave_buscar');			
		}
		
		//added 19-06-2014
		if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] != ''){
			$this->model->filters .= ' and bv_caja.sucursal_id = '.$_POST['sucursal_id'];
			$this->session->set_userdata('sucursal_id',$_POST['sucursal_id']);
			$this->data['sucursal_id'] = $_POST['sucursal_id'];
			$sucursal = $this->Sucursal->get($_POST['sucursal_id'])->row();	
			$this->data['page_title'] = "Sistema de Caja - ".$sucursal->nombre;
		}
		else if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] == ''){			
			$this->session->unset_userdata('sucursal_id');
		}
		
		//si esta seteado filtro_fechas
		if( isset($_POST['filtro_fechas']) && $_POST['filtro_fechas'] != ''){
			$this->data['filtro_fechas'] = $_POST['filtro_fechas'];
			
			if($_POST['filtro_fechas'] == 'mes'){
				//por mes
				if( (isset($_POST['mes']) && $_POST['mes'] != '' && isset($_POST['anio']) && $_POST['anio'] != '' ) 
				   ){
					$this->filtro_por_mes();
				}
			}
			else{
				//por rango
				$this->session->unset_userdata('filtro_mes');
				unset($this->data['filtro_mes']);

				$this->filtrar_fechas();
			}
		}
		else{
			//si hay algo en sesion -> lo tomo
			if($this->session->userdata('fecha_desde') or $this->session->userdata('fecha_hasta')){
				if($this->session->userdata('filtro_mes')){
					$_POST['mes'] = $this->session->userdata('mes');
					$_POST['anio'] = $this->session->userdata('anio');
					$this->filtro_por_mes();
					$this->data['filtro_fechas'] = 'mes';
				}
				else{
					$_POST['fecha_desde'] = ($this->session->userdata('fecha_desde')) ? $this->session->userdata('fecha_desde') : '';
					$_POST['fecha_hasta'] = ($this->session->userdata('fecha_hasta')) ? $this->session->userdata('fecha_hasta') : '';
					unset($this->data['filtro_mes']);
					$this->filtrar_fechas();
					$this->data['filtro_fechas'] = 'rango';
				}
			}
			else{
				//si no setea nada -> filtro por mes actual
				$_POST['mes'] = date('m');
				$_POST['anio'] = date('Y');
				$this->filtro_por_mes();
			}
		}
		
		$this->data['sort'] = 'fecha';
		$this->data['sortType'] = 'desc';
		parent::index();
	}
	
	function onBeforeEdit($id=''){
			$this->Concepto->filters = "sistema_caja = 1";
			$this->data['conceptos'] = $this->Concepto->getAll();
		
	}

	function onBeforeSave(){
		if(!isset($_POST['ingreso'])){
			$_POST['ingreso'] = 0;
		}
		else{
			//le cambio el formato al que admite la DB
			$_POST['ingreso'] = str_replace(array('.',','),array('','.'),$_POST['ingreso']);
		}

		if(!isset($_POST['egreso'])){
			$_POST['egreso'] = 0;
		}
		else{
			$_POST['egreso'] = str_replace(array('.',','),array('','.'),$_POST['egreso']);
		}
		
		$_POST['fecha'] = date('Y-m-d H:i:s');
		
		if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] != ''){
			$this->Caja->filters = "bv_caja.sucursal_id = ".$_POST['sucursal_id'];
		}
				
		//obtengo ultimo movimiento de caja
		$mov = $this->Caja->getAll(1,0,'fecha','desc')->row();
		
		if(isset($mov->id))
			$_POST['saldo'] = $mov->saldo+$_POST['ingreso']-$_POST['egreso'];
		else
			$_POST['saldo'] = $_POST['ingreso']-$_POST['egreso'];
			
		$_POST['admin_id'] = $this->session->userdata('admin_id');
	}

	function ver($id){
		$this->data['row'] = $this->model->get($id)->row();
		echo $this->load->view('admin/caja_observaciones',$this->data,true);
	}
	
}