<?php
include "AdminController.php";

class Alojamientos extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Alojamiento_model', 'Alojamiento');
    $this->model = $this->Alojamiento;
    $this->page = "alojamientos";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "alojamientos";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Alojamientos";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
  
  function onEditReady($id='') {
    $this->load->model('Servicio_model', 'Servicio');
    $this->data['servicios'] = $this->Servicio->getList('', 'nombre asc');

    if ($id) {
        $this->data['mis_servicios'] = $this->model->getServicios($id);
    }
    else {
        $this->data['mis_servicios'] = [];
    }
	
    $this->load->model('Destino_model', 'Destino');
    $this->data['destinos'] = $this->Destino->getList('', 'nombre asc');

    if ($id) {
        $this->data['mis_destinos'] = $this->model->getDestinos($id);
    }
    else {
        $this->data['mis_destinos'] = [];
    }
	
	$this->load->model('Fecha_alojamiento_model', 'Fecha');
    if ($id) {
        $this->data['mis_fechas'] = $this->Fecha->getByAlojamiento($id);
    }
    else {
        $this->data['mis_fechas'] = [];
    }
	
	$this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento');
	if ($id) {
        $mis_fechas_cupo = $this->Fecha_alojamiento->getByAlojamiento($id);		
		
		foreach($mis_fechas_cupo as $m){			
			$m->{'cantidad_'.$m->id} = $m->cantidad;
			$m->{'cupo_total_'.$m->id} = $m->cupo_total;
		}
		$this->data['mis_fechas_cupo'] = $mis_fechas_cupo;
    }
    else {
        $this->data['mis_fechas_cupo'] = [];
    }
	
    $this->load->model('Habitacion_model', 'Habitacion');
    $this->data['habitaciones'] = $this->Habitacion->getList('', 'nombre asc');
  }

  function onAfterSave($id) {
	  //servicios del alojamiento
		$this->model->clearServicios($id);

		$servicios = $this->input->post('servicios');
    if ($servicios) {
  		foreach ($servicios as $servicio_id) {
  			$this->model->addServicio($id, $servicio_id);
  		}
    }

		//destinos asociados
		$this->model->clearDestinos($id);

		$dd = $this->input->post('destino_id[]');
    if ($dd) {
  		foreach ($dd as $d) {
  			$this->model->addDestino($id, $d);
  		}
    }
  }
  
  //genera duplicacion de registro de fecha y habitaciones
  function duplicar_fecha(){
	extract($_POST);

	if(!isset($fecha_id) || !$fecha_id 
		|| !isset($f_checkin) || $f_checkin == '' || $f_checkin == '00/00/0000' || 
		!isset($f_checkout) || $f_checkout == '' || $f_checkout == '00/00/0000'){
			echo json_encode(array('status'=>'ERROR'));
	}
	else{

		//fecha_id es la cual voy a duplicar
		$f_checkin = explode('/',$f_checkin);
		$f_checkin = $f_checkin[2].'-'.$f_checkin[1].'-'.$f_checkin[0];
		$f_checkout = explode('/',$f_checkout);
		$f_checkout = $f_checkout[2].'-'.$f_checkout[1].'-'.$f_checkout[0];
		

		//obtengo data de fecha que voy a duplica
		$this->load->model('Fecha_alojamiento_model', 'Fecha');
	    $fecha = $this->Fecha->get($fecha_id)->row();
	    $fecha = json_decode(json_encode($fecha), True);
	    unset($fecha['id']);

	    $fecha['fecha_checkin'] = $f_checkin;
	    $fecha['fecha_checkout'] = $f_checkout;

	    //genero nueva fecha
	    $nf_id = $this->Fecha->insert($fecha);

	    $this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento');
	    $this->Fecha_alojamiento->filters = "fecha_id = ".$fecha_id;
		$cupos = $this->Fecha_alojamiento->getAll()->result();		
		foreach($cupos as $c){
			$c->fecha_id = $nf_id;
			$c = json_decode(json_encode($c), True);
	    	unset($c['id']);

	    	//le reseteo el cupo
	    	$c['cupo'] = $c['cupo_total'];

			//duplico el cupo con el nuevo id de fecha
	    	$this->Fecha_alojamiento->insert($c);
		}

		echo json_encode(array('id'=>$nf_id,'status'=>'OK'));
	}

  }

  function grabar_descripcion_fecha($fecha_id) {
  		$descripcion = trim($this->input->post('descripcion'));

  		$this->load->model('Fecha_alojamiento_model', 'Fecha_alojamiento');
  		$this->Fecha_alojamiento->saveDescripcion($fecha_id, $descripcion);
  }

  //genera registro para nueva asociacion del alojamiento con la fecha y devuelve el row
  function agregar_fecha(){
		extract($_POST);

		$fecha_checkin = explode('/',$fecha_checkin);
		$fecha_checkin = $fecha_checkin[2].'-'.$fecha_checkin[1].'-'.$fecha_checkin[0];
		$fecha_checkout = explode('/',$fecha_checkout);
		$fecha_checkout = $fecha_checkout[2].'-'.$fecha_checkout[1].'-'.$fecha_checkout[0];
		$descripcion = trim($this->input->post('descripcion_fecha'));

		$this->load->model('Fecha_alojamiento_model', 'Fecha');		
		$r_id = $this->Fecha->addAlojamiento($id,$fecha_checkin,$fecha_checkout, $descripcion);
		
		$row = $this->Fecha->getAsociacionAlojamiento($r_id);		
		$row->fecha_checkin = $fecha_checkin;
		$row->fecha_checkout = $fecha_checkout;
		$ret['row'] = fechas_alojamiento_row($row);
		
		//cargo las opciones a mostrar en fechas de tab habitaciones
		$mis_fechas = $this->Fecha->getByAlojamiento($id);
		$ret['options'] = $mis_fechas;
		
		echo json_encode($ret);
	}
	
  function borrar_fecha($id){
		$this->load->model('Fecha_alojamiento_model', 'Fecha');	
		if ($this->Fecha->deleteAsociacionAlojamiento($id)) {
			echo TRUE;
		}
		else {
			echo FALSE;
		}
  }
	
  //genera registro para nueva asociacion de las fechas con las habitaciones y devuelve el row
  function agregar_habitacion(){
		extract($_POST);

		$this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento');		
		$r_id = $this->Fecha_alojamiento->addHabitacion($fecha_id,$habitacion_id,$cupo_total,$cantidad);
		
		$ret = [];
		if ($r_id) {
			$row = $this->Fecha_alojamiento->getAsociacionHabitacion($r_id);		
			
			$row->{'cantidad_'.$row->id} = $row->cantidad;
			$row->{'cupo_total_'.$row->id} = $row->cupo_total;
			
			$ret['success'] = TRUE;
			$ret['row'] = fechas_alojamiento_cupo_row($row);
		}
		else {
			$ret['success'] = FALSE;
		}
		
		//23-08-18 al agregar cupo de alojamiento, llamo a funcion para actualizar cupos de paquetes
		actualizar_cupos();

		echo json_encode($ret);
	}
	
	function borrar_habitacion($id){
		$this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento');	
		$this->Fecha_alojamiento->deleteAsociacionHabitacion($id);	
		echo true;
	}

	function update_habitacion($id){
		
		
		$upd = array();
		$upd['cantidad'] = $_POST['cantidad_'.$id];
		$upd['cupo_total'] = $_POST['cupo_total_'.$id];
		
		$this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento');
		//para el cupo vigente, veo la diferencia que pueda haber con el cupo total actualizado
		$row_cupo = $this->Fecha_alojamiento->get($id)->row();
		
		$cupo_actual = $row_cupo->cupo;

		if($row_cupo->habitacion_id!=99){//si no es la compartida

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
		}
			
		$upd['cupo'] = $cupo_actual;

		$this->Fecha_alojamiento->updateAsociacionHabitacion($id,$upd);	

		//23-08-18 al editar cupo de alojamiento, llamo a funcion para actualizar cupos de paquetes
		actualizar_cupos();		
	}
	
	//popup de creacion rapida de alojamiento
	function quickadd() {
		$this->onEditReady('');
		$ret['view'] = $this->load->view('admin/alojamientos_simple_form', $this->data, true);
		echo json_encode($ret);
	}

	//grabar datos en creacion rapida
	function quicksave() {
		extract($_POST);
		
		$data = array();
		$data['nombre'] = $nombre;
		$id = $this->Alojamiento->insert($data);
		$ret['redirect'] = $this->data['route']."/edit/".$id;
		echo json_encode($ret);
	}

}