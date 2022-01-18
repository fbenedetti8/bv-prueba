<?php
include "AdminController.php";

class Reportes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['defaultSort'] = "id";
		$this->load->model('Reserva_model', 'Reserva');
		$this->model = $this->Reserva;
		$this->page = "reportes";
		$this->data['currentModule'] = "reportes";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Reportes";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->load->model('Stats_model','Stats');
		$this->load->model('Reserva_estado_model','Estado');
		$this->data['estados'] = $this->Estado->getAll(999,0,'nombre','asc')->result();
	}
	
	/*
	Devuelve excel con reportes
	*/
	function index($tipo='ventas',$export=false){
		if (false && isset($_POST['btnReset'])){
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

		switch($tipo){
			case "ventas":
				$results = $this->Stats->getReporteVentas($this->data['keywords'])->result();
				
				foreach($results as $res){
					$res->{"Exento"} = number_format($res->{"Exento"},2,',','.');
					$res->{"Gravado 21"} = number_format($res->{"Gravado 21"},2,',','.');
					$res->{"Gravado 10.5"} = number_format($res->{"Gravado 10.5"},2,',','.');
					$res->{"Comision"} = number_format($res->{"Comision"},2,',','.');
					$res->{"IVA 21"} = number_format($res->{"IVA 21"},2,',','.');
					$res->{"IVA 10.5"} = number_format($res->{"IVA 10.5"},2,',','.');
					$res->{"Gastos administrativos"} = number_format($res->{"Gastos administrativos"},2,',','.');
					$res->{"Total en Pesos"} = number_format($res->{"Total en Pesos"},2,',','.');
				}
		
			break;
			case "utilidades":
				if(isset($_POST['estado_id']) && $_POST['estado_id'] != ''){
					$this->data['estado_id'] = $_POST['estado_id'];
					$this->db->where('r.estado_id = '.$_POST['estado_id']);
					$this->model->filters = 'r.estado_id = '.$_POST['estado_id'];
				}
		
				$results = $this->Stats->getReporteCostos($this->data['keywords'], $_POST['fecha_desde'], $_POST['fecha_hasta'])->result();
				
				
			if($_SERVER['REMOTE_ADDR'] == '181.171.24.39'){
				//echo $this->db->last_query();exit();
			}	
			
				foreach($results as $res){
					$res->{"Venta Total"} = number_format($res->{"Venta Total"},2,',','.');
					$res->{"Costo Total"} = number_format($res->{"Costo Total"},2,',','.');
					$res->{"Utilidad"} = number_format($res->{"Utilidad"},2,',','.');
					$res->{"Venta Neta"} = number_format($res->{"Venta Neta"},2,',','.');
					$res->{"Costo Neto"} = number_format($res->{"Costo Neto"},2,',','.');
					$res->{"Utilidad Neta"} = number_format($res->{"Utilidad Neta"},2,',','.');

					if($res->{'Tipo Factura'} == 'NC_B' or $res->{'Tipo Factura'} == 'NC_X'){
						$res->{"Venta Total"} = '-'.$res->{"Venta Total"};
						$res->{"Costo Total"} = '-'.$res->{"Costo Total"};
						$res->{"Utilidad"} = '-'.$res->{"Utilidad"};
						$res->{"Venta Neta"} = '-'.$res->{"Venta Neta"};
						$res->{"Costo Neto"} = '-'.$res->{"Costo Neto"};
						$res->{"Utilidad Neta"} = '-'.$res->{"Utilidad Neta"};
					}
				}
			break;
			case "ingresos":
				$results = $this->Stats->getReporteIngresos($this->data['keywords'], $_POST['fecha_desde'], $_POST['fecha_hasta'])->result();

				foreach($results as $res){
					$res->{"Total"} = number_format($res->{"Total"},2,',','.');

					if($res->{'tipo'} == 'NC_B' or $res->{'tipo'} == 'NC_X'){
						$res->{"Total"} = '-'.$res->{"Total"};
					}
				}
			break;			
		}
		
		$this->data['data'] = $results;
		$this->data['totalRows'] = count($results);
		$this->data['route'] = site_url('admin/' . $this->page.'/index/'.$tipo);	
		
		if($export)
			exportExcel($results,'reporte-'.$tipo.'_'.date('Y-m-d'));
		else
			$this->load->view('admin/reportes-'.$tipo, $this->data);
			
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
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else if(isset($_POST['fecha_hasta']) && $_POST['fecha_hasta'] != ''){
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
		}
		else if(isset($_POST['fecha_desde']) && $_POST['fecha_desde'] != ''){
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else{
			$this->session->unset_userdata('fecha_desde');
			$this->session->unset_userdata('fecha_hasta');
		}
		
	}	
}