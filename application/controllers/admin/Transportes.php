<?php
include "AdminController.php";

class Transportes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Transporte_model', 'Transporte');
		$this->model = $this->Transporte;
		$this->page = "transportes";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);  
		$this->data['uploadFolder'] = "transportes";
		$this->pageSegment = 4;
		$this->data['page_title'] = "Transportes";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
	}
  
	function onEditReady($id='') {
		$this->load->model('Tipotransporte_model', 'Tipo');
		$this->data['tipos'] = $this->Tipo->getList('', 'nombre asc');
		
		$this->load->model('Destino_model', 'Destino');
		$this->data['destinos'] = $this->Destino->getList('', 'nombre asc');
		
		$this->data['destinos_asociados'] = array();
		if($id){
			$this->data['destinos_asociados'] = $this->Transporte->getDestinos($id);
		}
		
		$this->load->model('Fecha_model', 'Fecha');
		$mis_fechas = $this->Fecha->getByTransporte($id);
		foreach($mis_fechas as $m){			
			$m->{'fecha_salida_'.$m->id} = $m->fecha_salida;
			$m->{'vuelo_ida_'.$m->id} = $m->vuelo_ida;
			$m->{'vuelo_aeropuerto_'.$m->id} = $m->vuelo_aeropuerto;
			$m->{'fecha_regreso_'.$m->id} = $m->fecha_regreso;
			$m->{'vuelo_regreso_'.$m->id} = $m->vuelo_regreso;
			$m->{'cupo_total_'.$m->id} = $m->cupo_total;
			$m->{'fecha_vencimiento_'.$m->id} = $m->fecha_vencimiento;
		}
		$this->data['mis_fechas'] = $mis_fechas;
	}
	
	function onAfterSave($id) {
		if($id){
			//asociacion de destinos con el transporte
			$this->Transporte->clearDestinos($id);
			foreach($_POST['destino_id'] as $d){
				if($d){
					$this->Transporte->addDestino($id,$d);
				}
			}
		}
	}
	
	//genera registro para nueva asociacion del transporte con la fecha y devuelve el row
	function agregar_fecha(){
		extract($_POST);

		$f_fecha_salida = explode('/',$f_fecha_salida);
		$f_fecha_salida = $f_fecha_salida[2].'-'.$f_fecha_salida[1].'-'.$f_fecha_salida[0];
		$f_fecha_regreso = explode('/',$f_fecha_regreso);
		$f_fecha_regreso = $f_fecha_regreso[2].'-'.$f_fecha_regreso[1].'-'.$f_fecha_regreso[0];
		$f_fecha_vencimiento = explode('/',$f_fecha_vencimiento);
		$f_fecha_vencimiento = $f_fecha_vencimiento[2].'-'.$f_fecha_vencimiento[1].'-'.$f_fecha_vencimiento[0];
		
		$this->load->model('Fecha_model', 'Fecha');		
		$r_id = $this->Fecha->addTransporte($id,$f_fecha_salida,@$f_vuelo_ida,$f_fecha_regreso,@$f_vuelo_regreso,$f_cupo_total,@$f_vuelo_aeropuerto,@$f_fecha_vencimiento);
		
		$row = $this->Fecha->getAsociacionTransporte($r_id);	
		
		$row->{'fecha_salida_'.$r_id} = $row->fecha_salida;
		$row->{'vuelo_ida_'.$r_id} = $row->vuelo_ida;
		$row->{'vuelo_aeropuerto_'.$r_id} = $row->vuelo_aeropuerto;
		$row->{'fecha_regreso_'.$r_id} = $row->fecha_regreso;
		$row->{'vuelo_regreso_'.$r_id} = $row->vuelo_regreso;
		$row->{'cupo_total_'.$r_id} = $row->cupo_total;
		$row->{'fecha_vencimiento_'.$r_id} = $row->fecha_vencimiento;
		
		$ret['row'] = fechas_row($row);
		
		//23-08-18 al agregar cupo de transporte, llamo a funcion para actualizar cupos de paquetes
		actualizar_cupos();

		echo json_encode($ret);
	}
	
	function borrar_fecha($id){
		$this->load->model('Fecha_model', 'Fecha');	
		if ($this->Fecha->deleteAsociacionTransporte($id)) {
			echo TRUE;
		}
		else {
			echo FALSE;
		}
	}
	
	function update_fecha($id){
		
		$_POST['fecha_salida_'.$id] = explode('/',$_POST['fecha_salida_'.$id]);
		$_POST['fecha_salida_'.$id] = $_POST['fecha_salida_'.$id][2].'-'.$_POST['fecha_salida_'.$id][1].'-'.$_POST['fecha_salida_'.$id][0];
		$_POST['fecha_regreso_'.$id] = explode('/',$_POST['fecha_regreso_'.$id]);
		$_POST['fecha_regreso_'.$id] = $_POST['fecha_regreso_'.$id][2].'-'.$_POST['fecha_regreso_'.$id][1].'-'.$_POST['fecha_regreso_'.$id][0];
		$_POST['fecha_vencimiento_'.$id] = explode('/',$_POST['fecha_vencimiento_'.$id]);
		$_POST['fecha_vencimiento_'.$id] = $_POST['fecha_vencimiento_'.$id][2].'-'.$_POST['fecha_vencimiento_'.$id][1].'-'.$_POST['fecha_vencimiento_'.$id][0];
		
		$upd = array();
		$upd['fecha_salida'] = $_POST['fecha_salida_'.$id];
		$upd['fecha_regreso'] = $_POST['fecha_regreso_'.$id];
		$upd['fecha_vencimiento'] = $_POST['fecha_vencimiento_'.$id];
		$upd['vuelo_ida'] = $_POST['vuelo_ida_'.$id];
		$upd['vuelo_aeropuerto'] = $_POST['vuelo_aeropuerto_'.$id];
		$upd['vuelo_regreso'] = $_POST['vuelo_regreso_'.$id];
		$upd['cupo_total'] = $_POST['cupo_total_'.$id];

		$this->load->model('Fecha_model', 'Fecha');
		//para el cupo vigente, veo la diferencia que pueda haber con el cupo total actualizado
		$row_cupo = $this->Fecha->get($id)->row();
		
		$cupo_actual = $row_cupo->cupo;
		//si el nuevo cupo total es mayor que el anterior
		if($upd['cupo_total'] > $row_cupo->cupo_total){
			//le sumo la diferencia
			$cupo_actual += ($upd['cupo_total']-$row_cupo->cupo_total);
		}
		else{
			//si es menor el nuevo cupo total, le resto la diferencia
			$cupo_actual -= ($row_cupo->cupo_total-$upd['cupo_total']);
			if($cupo_actual < 0){
				$cupo_actual = 0;
			}
		}
			
		$upd['cupo'] = $cupo_actual;

		$this->Fecha->updateAsociacionTransporte($id,$upd);	
		
		//23-08-18 al editar cupo de transporte, llamo a funcion para actualizar cupos de paquetes
		actualizar_cupos();

		echo true;
	}
	
	//popup de creacion rapida de transporte
	function quickadd() {
		$this->onEditReady('');
		$ret['view'] = $this->load->view('admin/transportes_simple_form', $this->data, true);
		echo json_encode($ret);
	}
	
	//grabar datos en creacion rapida
	function quicksave() {
		extract($_POST);
		
		$data = array();
		$data['nombre'] = $nombre;
		$data['tipo_id'] = $tipo_id;
		$id = $this->model->insert($data);
		$ret['redirect'] = $this->data['route']."/edit/".$id;
		echo json_encode($ret);
	}
	
	
}