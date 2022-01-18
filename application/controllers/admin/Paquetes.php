<?php
include "AdminController.php";

class Paquetes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Paquete_model', 'Paquete');
		$this->model = $this->Paquete;
		$this->page = "paquetes";
		$this->data['currentModule'] = "paquetes";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Paquetes";
		$this->limit = 20;
		$this->init();
		$this->validate = FALSE;
		
		$this->uploadsFolder = 'paquetes';
		$this->uploads = array(
							array(
								'name' => 'itinerario',
								'allowed_types' => '*',
								'maxsize' => 204800,
								'folder' => '/uploads/paquetes/',
								'keep' => true,
								'prefix' => '',
							),
							'imagen_listado' => ['type' => 'image', 'width' => 1140, 'height' => 633],
						);
						
		$this->load->model('Lista_espera_model', 'Lista_espera');
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Combinacion_model', 'Combinacion');
		$this->load->model('Parada_model', 'Parada');	
		$this->load->model('Reserva_model', 'Reserva');
		$this->data['destinos'] = $this->Destino->getList('', 'nombre asc');
	}
		
	function index(){
		$this->model->filters = '1=1';
		
		$this->data['destino_id'] = '';
		//filtro de destino
		if(isset($_GET['destino_id']) && $_GET['destino_id'] != ''){
			$this->data['destino_id'] = $_GET['destino_id'];
			$this->model->filters .= ' and destino_id = '.$_GET['destino_id'];
			$this->session->set_userdata('destino_id',$_GET['destino_id']);
		}
		else{
			if(isset($_GET['destino_id']) && $_GET['destino_id'] == ''){
				$this->session->unset_userdata('destino_id');
			}
			
			if($this->session->userdata('destino_id')){
				$this->data['destino_id'] = $this->session->userdata('destino_id');
				$this->model->filters .= ' and destino_id = '.$this->session->userdata('destino_id');
			}
		}
		
		$this->data['visibilidad'] = '';
		//filtro de activo
		if(isset($_GET['visibilidad']) && $_GET['visibilidad'] != ''){
			$this->data['visibilidad'] = $_GET['visibilidad'];
			$this->model->filters .= ' and activo = '.($_GET['visibilidad']=='activos'?1:0);
			$this->session->set_userdata('visibilidad',$_GET['visibilidad']);
		}
		else{
			if(isset($_GET['visibilidad']) && $_GET['visibilidad'] == ''){
				$this->session->unset_userdata('visibilidad');
			}
			
			if($this->session->userdata('visibilidad')){
				$this->data['visibilidad'] = $this->session->userdata('visibilidad');
				$this->model->filters .= ' and activo = '.($this->session->userdata('visibilidad')=='activos'?1:0);
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
	
	function onEditReady($id='') {
		
		$this->load->model('Operador_model', 'Operador');
		$this->data['operadores'] = $this->Operador->getList('', 'nombre asc');
		
		$this->load->model('Documentacion_model', 'Documentacion');
		$this->data['documentaciones'] = $this->Documentacion->getList('', 'id asc');
		
		$this->data['mis_documentaciones'] = [];
		if ($id) {
			$this->data['mis_documentaciones'] = $this->model->getDocumentaciones($id);
			
			$this->breadcrumbs[] = $this->data['row']->codigo.' - '.$this->data['row']->nombre;
		}
		
		$this->load->model('Caracteristica_model', 'Caracteristica');
		$this->data['caracteristicas'] = $this->Caracteristica->getList('', 'orden asc');
		
		$this->data['mis_caracteristicas'] = [];
		$destino_id = '';
		if ($id) {
			$destino_id = $this->data['row']->destino_id;
			$this->data['mis_caracteristicas'] = $this->model->getCaracteristicasData($id);
		}
		
		//excursiones generales y particulares
		$this->load->model('Excursion_model', 'Excursion');
		$arr = array(0,"",NULL);
		$arr2 = array();
		if($destino_id){
			$arr2 = array($destino_id);
		}
		
		$this->data['excursiones'] = $this->Excursion->getAllDestinos($arr,$arr2);
		
		$this->data['mis_excursiones'] = [];
		if ($id) {
			$this->data['mis_excursiones'] = $this->model->getExcursionesData($id);
		}


		$this->load->model('Medio_model', 'Medio');
		$this->data['medios'] = $this->Medio->getList('', 'nombre asc');
		
		$this->data['mis_medios'] = [];
		if ($id) {
			$this->data['mis_medios'] = $this->model->getMedios($id);
		}
		
		$this->load->model('Lugar_salida_model', 'Lugar');
		$this->data['lugares'] = $this->Lugar->getList('', 'nombre asc');
		
		$this->data['mis_lugares'] = [];
		if ($id) {
			$this->data['mis_lugares'] = $this->model->getLugares($id);
		}
		
		$this->load->model('Promocion_model', 'Promocion');
		$this->data['promociones'] = $this->Promocion->getList('', 'nombre asc');
		
		$this->data['mis_promociones'] = [];
		if ($id) {
			$this->data['mis_promociones'] = $this->model->getPromociones($id);
		}
		
		$this->load->model('Alojamiento_model', 'Alojamiento');
		$this->load->model('Transporte_model', 'Transporte');
		$this->load->model('Regimen_model', 'Regimen');
		$this->load->model('Habitacion_model', 'Habitacion');
		$this->load->model('Adicional_model', 'Adicional');
		$this->load->model('Combinacion_model', 'Combinacion');
		
		
		if ($id) {
			$this->data['alojamientos'] = $this->Alojamiento->getByDestino($destino_id,$this->data['row']->fecha_inicio,$this->data['row']->fecha_fin,$this->data['row']->fecha_indefinida);
		
			
			$this->data['transportes'] = $this->Transporte->getByDestino($destino_id,$this->data['row']->fecha_inicio,$this->data['row']->fecha_indefinida);
			
			$this->data['data_transportes'] = $this->Transporte->getTransportesDestino($destino_id,$this->data['row']->fecha_inicio,$this->data['row']->fecha_indefinida);
			$this->data['mis_alojamientos'] = $this->Alojamiento->getByPaquete($id);
			
			$this->data['mis_regimenes'] = $this->Regimen->getByPaquete($id);
			$this->data['mis_adicionales'] = $this->Adicional->getByPaquete($id);
			$this->data['mis_paradas'] = $this->Parada->getByPaquete($id);
			
			$this->data['combinaciones'] = $this->Combinacion->getByPaquete($id);
			if($_SERVER['REMOTE_ADDR'] == '190.18.8.47'){
				//echo $this->db->last_query();
			}
			//echo $this->db->last_query();
		}
		
		$this->data['puede_borrar_combinaciones'] = false;
		if ($id) {
			$this->data['puede_borrar_combinaciones'] = $this->Paquete->puedeBorrarCombinaciones($id);
		}

		$this->data['regimenes'] = $this->Regimen->getList('', 'id asc');
		
		
		//$this->data['habitaciones'] = $this->Habitacion->getList('', 'id asc');
		
		$this->data['adicionales'] = $this->Adicional->getList('', 'id asc');
		
		//cargo las opciones de paradas a mostrar SIN las que ya agregué
		$mis_paradas = array();
		if ($id) {
			$mis_paradas = $this->Parada->getByPaquete($id);
		}
		$mis = array();
		foreach($mis_paradas as $p){
			$mis[] = $p->parada_id;
		}
		if(count($mis) > 0)
			$this->Parada->filters = "bv_paradas.id not in (".implode(',',$mis).")";
		$this->data['paradas'] = $this->Parada->getAll();
		
		//subcategorias estacionales para el paquete
		$this->load->model('Estacionales_model', 'Estacionales');
		$this->data['estacionales'] = $this->Estacionales->getListDelDestino(@$this->data['row']->destino_id);
		
		$this->data['mis_estacionales'] = [];
		if ($id) {
			$this->data['mis_estacionales'] = $this->model->getEstacionales($id);
		}
		
		// 22-02-19 traigo los celulares que no estén asignados en viajes en mismo rango de fechas
		$this->load->model('Celular_model', 'Celular');
		$this->data['celulares'] = array();
		if($id){
			$this->data['celulares'] = $this->Celular->getCelularesDisponibles($id);
		}
		
		$this->data['mis_celulares'] = [];
		if ($id) {
			$this->data['mis_celulares'] = $this->model->getCelulares($id);
		}
		
		// 22-02-19 traigo los celulares que no estén asignados en viajes en mismo rango de fechas
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->data['coordinadores'] = array();
		if($id){
			$this->data['coordinadores'] = $this->Vendedor->getCoordinadoresDisponibles($id);
		}

		$this->data['mis_coordinadores'] = [];
		if ($id) {
			$this->data['mis_coordinadores'] = $this->model->getCoordinadores($id);
		}
		
		$this->data['saved'] = false;
		if(isset($_GET['saved']) && $_GET['saved']){
			$this->data['saved'] = true;
		}
	}
	
	function validar() {
		$this->form_validation->set_rules('nombre','Nombre del Paquete','required', 
			array('required' => 'Por favor ingresá el nombre del paquete')
		);
		$this->form_validation->set_rules('slug','Slug','required', 
			array('required' => 'Por favor ingresá el slug')
		);
		$this->form_validation->set_rules('cantidad_vouchers','Cantidad de Vouchers a cargar','required', 
			array('required' => 'Por favor ingresá la cantidad de vouchers')
		);
		$this->form_validation->set_rules('monto_minimo_reserva','Monto mínimo de reserva','required', 
			array('required' => 'Por favor ingresá el monto mínimo de reserva')
		);
		$this->form_validation->set_rules('operador_id','Operador','required', 
			array('required' => 'Por favor ingresá el operador del paquete')
		);
		$this->form_validation->set_rules('fecha_limite_vouchers','Fecha alerta de vouchers','required', 
			array('required' => 'Por favor ingresá la fecha límite de carga de vouchers')
		);
		$this->form_validation->set_rules('fecha_limite_completar_datos','Fecha alerta de datos completos','required', 
			array('required' => 'Por favor ingresá la fecha límite de datos completos')
		);
		$this->form_validation->set_rules('fecha_limite_pago_completo','Fecha alerta de pago completo','required', 
			array('required' => 'Por favor ingresá la fecha límite de pago completo')
		);
			
		$_POST['cupo_paquete_personalizado'] = @$_POST['cupo_paquete_personalizado'] ? $_POST['cupo_paquete_personalizado'] : 0;
		if ($_POST['cupo_paquete_personalizado'] == 1) {
			$this->form_validation->set_rules('cupo_paquete_disponible','Cupo disponible','required', 
				array('required' => 'Por favor ingresá el cupo disponible')
			);
			$this->form_validation->set_rules('cupo_paquete_total','Cupo total','required', 
				array('required' => 'Por favor ingresá el cupo total')
			);
			
			//el cupo personalizado disponible nunca puede ser mayor que el real
			$_POST['cupo_paquete_disponible'] = @$_POST['cupo_paquete_disponible'] ? $_POST['cupo_paquete_disponible'] : 0;
			$_POST['cupo_paquete_disponible_real'] = @$_POST['cupo_paquete_disponible_real'] ? $_POST['cupo_paquete_disponible_real'] : 0;
			
			if( $_POST['cupo_paquete_disponible'] > $_POST['cupo_paquete_disponible_real'] ){
				$this->form_validation->set_rules('cupo_paquete_disponible','Cupo disponible','less_than['.$_POST['cupo_paquete_disponible_real'].']', 
					array('less_than' => 'El cupo personalizado disponible no puede ser mayor que el real')
				);
			}
		}
		
		//chequeo la cantidad de lugares de salida seleccionados
		$mis_lugares = $this->model->getLugares($_POST['id']);
		if (!count($mis_lugares)) {
			$this->form_validation->set_rules('lugares','Lugares de Salida','required', 
				array('required' => 'Por favor seleccioná al menos un lugar de salida')
			);
		}
		
		//si sólo seleccionó lugar de salida EN DESTINO
		if(count($mis_lugares) == 1 && $mis_lugares[0] == 4){
			//no valido paradas
		}
		else{
			//chequeo la cantidad de paradas cargadas
			$mis_paradas = $this->Parada->getByPaquete($_POST['id']);
			if (!count($mis_paradas)) {
				$this->form_validation->set_rules('paradas','Paradas del transporte','required', 
					array('required' => 'Por favor seleccioná al menos una parada para el/los lugares de salida')
				);
			}
		}
		
		$_POST['estacionales'] = @$_POST['estacionales'] ? $_POST['estacionales'] : [];
		if(!count($_POST['estacionales'])){
			$this->form_validation->set_rules('estacionales','Subcategorías Estacionales','required', 
					array('required' => 'Por favor seleccioná al menos una subcategoría estacional')
				);
		}
		
		if ($this->form_validation->run() == FALSE) {
			$data['success'] = FALSE;
			$data['error'] = validation_errors();
		}
		else {
			$data['success'] = TRUE;
		}

		echo json_encode($data);
	}

	function onBeforeSave($id='') {
		if (perfil() == 'ADM') {
			return;
		}
		
		if($_POST['id']>0){
			
			$this->form_validation->set_rules('nombre','Nombre del Paquete','required');
			$this->form_validation->set_rules('slug','Slug','required');
			$this->form_validation->set_rules('cantidad_vouchers','Cantidad de Vouchers a cargar','required');
			$this->form_validation->set_rules('monto_minimo_reserva','Monto mínimo de reserva','required');
			$this->form_validation->set_rules('operador_id','Operador','required');
			$this->form_validation->set_rules('fecha_inicio','Fecha inicio','required');
			$this->form_validation->set_rules('fecha_fin','Fecha fin','required');
			$this->form_validation->set_rules('fecha_limite_vouchers','Fecha alerta de vouchers','required');
			$this->form_validation->set_rules('fecha_limite_completar_datos','Fecha alerta de datos completos','required');
			$this->form_validation->set_rules('fecha_limite_pago_completo','Fecha alerta de pago completo','required');
			
			$_POST['cupo_paquete_personalizado'] = @$_POST['cupo_paquete_personalizado'] ? $_POST['cupo_paquete_personalizado'] : 0;
			if ($_POST['cupo_paquete_personalizado'] == 1) {
				$this->form_validation->set_rules('cupo_paquete_disponible','Cupo disponible','required');
				$this->form_validation->set_rules('cupo_paquete_total','Cupo total','required');
			}
			
			//chequeo la cantidad de lugares de salida seleccionados
			$mis_lugares = $this->model->getLugares($_POST['id']);
			if (!count($mis_lugares)) {
				$this->form_validation->set_rules('lugares','Lugares de Salida','required', 
					array('required' => 'Por favor seleccioná al menos un lugar de salida')
				);
			}
			
			$_POST['estacionales'] = @$_POST['estacionales'] ? $_POST['estacionales'] : [];
			if(!count($_POST['estacionales'])){
				$this->form_validation->set_rules('estacionales','Subcategorías Estacionales','required', 
						array('required' => 'Por favor seleccioná al menos una subcategoría estacional')
					);
			}
		
			if ($this->form_validation->run() == FALSE) {
				redirect(site_url('admin/paquetes/edit/'.$_POST['id'].'?error=1'));
			}
		}
		
		$_POST['fecha_indefinida'] = @$_POST['fecha_indefinida'] ? $_POST['fecha_indefinida'] : 0;
		$_POST['mostrar_calendario'] = @$_POST['mostrar_calendario'] ? $_POST['mostrar_calendario'] : 0;
		$_POST['grupal'] = @$_POST['grupal'] ? $_POST['grupal'] : 0;
		$_POST['activo'] = @$_POST['activo'] ? $_POST['activo'] : 0;
		$_POST['visible'] = @$_POST['visible'] ? $_POST['visible'] : 0;
		$_POST['precio_usd'] = @$_POST['precio_usd'] ? $_POST['precio_usd'] : 0;
		$_POST['exterior'] = @$_POST['exterior'] ? $_POST['exterior'] : 0;
		$_POST['confirmacion_inmediata'] = @$_POST['confirmacion_inmediata'] ? $_POST['confirmacion_inmediata'] : 0;
		$_POST['calculador_cuotas'] = @$_POST['calculador_cuotas'] ? $_POST['calculador_cuotas'] : 0;
		$_POST['pago_oficina'] = @$_POST['pago_oficina'] ? $_POST['pago_oficina'] : 0;
		$_POST['cupo_paquete_personalizado'] = @$_POST['cupo_paquete_personalizado'] ? $_POST['cupo_paquete_personalizado'] : 0;
				
		//fecha inicio
		$fecha_inicio = $_POST['fecha_inicio'];
		$aux2 = explode('/',$fecha_inicio);
		$fecha_inicio = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
		$_POST['fecha_inicio'] = $fecha_inicio;
			
		//fecha fin
		$fecha_fin = $_POST['fecha_fin'];
		$aux2 = explode('/',$fecha_fin);
		$fecha_fin = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
		$_POST['fecha_fin'] = $fecha_fin;
			
		$fecha_limite_vouchers = $_POST['fecha_limite_vouchers'];
		$aux2 = explode('/',$fecha_limite_vouchers);
		$fecha_limite_vouchers = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
		$_POST['fecha_limite_vouchers'] = $fecha_limite_vouchers;
		
		$fecha_limite_completar_datos = $_POST['fecha_limite_completar_datos'];
		$aux2 = explode('/',$fecha_limite_completar_datos);
		$fecha_limite_completar_datos = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
		$_POST['fecha_limite_completar_datos'] = $fecha_limite_completar_datos;
		
		$fecha_limite_pago_completo = $_POST['fecha_limite_pago_completo'];
		$aux2 = explode('/',$fecha_limite_pago_completo);
		$fecha_limite_pago_completo = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
		$_POST['fecha_limite_pago_completo'] = $fecha_limite_pago_completo;

		if(isset($_POST['fecha_limite_vouchers']) && $_POST['fecha_limite_vouchers'] > '0000-00-00'){
			if($_POST['id']>0){
				//actualizar la nueva fecha en las reservas de este viaje
				$this->Reserva->updateFechaLimiteVoucher($_POST['id'],$_POST['fecha_limite_vouchers']);
			}
		}
		if(isset($_POST['fecha_limite_pago_completo']) && $_POST['fecha_limite_pago_completo'] > '0000-00-00'){
			if($_POST['id']>0){
				//actualizar la nueva fecha en las reservas de este viaje
				$this->Reserva->updateFechaLimitePagoCompleto($_POST['id'],$_POST['fecha_limite_pago_completo']);
			}
		}
	}
	
	function __save() {
		extract($_POST);
		
		$this->onBeforeSave();		

		if ($this->validate && $this->form_validation->run() == FALSE) {
			$this->data['errorsFound'] = TRUE;

			if ($_POST['id'] != '')
				$this->edit($_POST['id']);
			else
				$this->add();
			return;
		}	

		foreach ($_POST as $key=>$value){			
			$data[$key] = $value;
		}

		if (isset($id) && $id != '')
			$this->model->update($id, $data); 
		else
			$id = $this->model->insert($data);


		//File uploads
		if ($this->uploads && count($_FILES)>0) {
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			//carpeta uploads
			if(!is_dir($this->uploadsFolder))
				mkdir($this->uploadsFolder,0777);
			foreach ($this->uploads as $upload) {


				$config['allowed_types'] = $upload['allowed_types'];
				$config['max_size']	= $upload['maxsize'];
				$config['upload_path'] = "." . $upload['folder'] . $id. '/';
			
				//1er nivel dentro de carpeta uploads
				if(!is_dir("." . $upload['folder']))
					mkdir("." . $upload['folder']);
			
				//2do nivel dentro de carpeta uploads
				if(!is_dir($config['upload_path'])) {
					mkdir($config['upload_path'], 0777);
				}
			
				$this->upload->initialize($config);
			
                $this->onBeforeUpload($config['upload_path']);
				if ($this->upload->do_upload($upload['name'])) {
					
                    $data = $this->upload->data();
					
					//Image resizes
					if (isset($upload['resizes'])) {
						
						$this->load->library('image_lib');
						foreach ($upload['resizes'] as $resize) {
							$config['image_library'] = 'gd2';
							$config['maintain_ratio'] = TRUE;
							$config['source_image']	= $config['upload_path'] . $data['file_name'];
							$config['width'] = $resize['width'];
							$config['height'] = $resize['height'];
							$config['new_image'] = $resize['prefix'] . $id . $resize['suffix'] . '.'.$data['file_ext'];
							$newname = $config['new_image'];
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();							
						}
					}
				
					//Keep original?
					if ($upload['keep']) {
						$newname = $upload['prefix'] . $data['file_name'] . (isset($upload['suffix'])?$upload['suffix']:'' );
						rename($config['upload_path'] . $data['file_name'], $config['upload_path'] . $newname);
					}
					else{
						#unlink($config['upload_path'] . $data['file_name']);
					}
					
					//Save filenames in database
					$uploadsdata[$upload['name']] = $newname;
					$this->model->update($id, $uploadsdata); 
					$this->uploadsdata = $uploadsdata;
					
				} else {
					
					#echo $this->upload->display_errors();
					#exit();
				}
				
			}
		}

		$this->onAfterSave($id);
        
		//header("location:" . $this->data['route']);
        header("location:" . $this->data['route'].'/index/?saved=1');
	}
	
	function onAfterSave($id) {
		if($id){

			if ($this->uploads) {
				if (!file_exists('./uploads/'.$this->uploadsFolder)) {
					mkdir('./uploads/'.$this->uploadsFolder);
				}

				if (!file_exists('./uploads/'.$this->uploadsFolder.'/'.$id)) {
					mkdir('./uploads/'.$this->uploadsFolder.'/'.$id);
				}

				foreach ($this->uploads as $file=>$upload) {
					
					 $file = $this->input->post($file);
					if ($file && file_exists('./uploads/temp/'.$file)) {
						rename('./uploads/temp/'.$file, './uploads/'.$this->uploadsFolder.'/'.$id.'/'.$file);
					}
					else{
					}
				}
			}

			//asociacion de documentaciones con el paquete
			$this->model->clearDocumentaciones($id);
			$_POST['documentaciones'] = isset($_POST['documentaciones']) ? $_POST['documentaciones'] : array();
			foreach($_POST['documentaciones'] as $d){
				if($d){
					$this->model->addDocumentacion($id,$d);
				}
			}
			
			//asociacion de caracteristicas con el paquete
			$this->model->clearCaracteristicas($id);
			$_POST['caracteristicas_arr'] = isset($_POST['caracteristicas_arr']) ? explode(',',$_POST['caracteristicas_arr']) : [];
			foreach($_POST['caracteristicas_arr'] as $d){
				if($d){
					$this->model->addCaracteristica($id,$d);
				}
			}
			
			//asociacion de excursiones con el paquete
			$this->model->clearExcursiones($id);
			$_POST['excursiones_arr'] = isset($_POST['excursiones_arr']) ? explode(',',$_POST['excursiones_arr']) : [];
			
			foreach($_POST['excursiones_arr'] as $d){
				if($d){
					$this->model->addExcursion($id,$d);
				}
			}
			
			//asociacion de medios de pago con el paquete
			$this->model->clearMedios($id);
			$_POST['medios'] = isset($_POST['medios']) ? $_POST['medios'] : array();
			foreach($_POST['medios'] as $d){
				if($d){
					$this->model->addMedio($id,$d);
				}
			}
			
			//asociacion de lugares de salida con el paquete
			$this->model->clearLugares($id);
			$_POST['lugares'] = isset($_POST['lugares']) ? $_POST['lugares'] : array();
			foreach($_POST['lugares'] as $d){
				if($d){
					$this->model->addLugar($id,$d);
				}
			}
			
			//asociacion de promociones con el paquete
			$this->model->clearPromociones($id);
			$_POST['promociones'] = isset($_POST['promociones']) ? $_POST['promociones'] : array();
			foreach($_POST['promociones'] as $d){
				if($d){
					$this->model->addPromocion($id,$d);
				}
			}
			
			//asociacion de categorias estacionales con el destino
			$this->model->clearEstacionales($id);
			$_POST['estacionales'] = isset($_POST['estacionales']) ? $_POST['estacionales'] : array();
			foreach($_POST['estacionales'] as $d){
				if($d){
					$this->model->addEstacional($id,$d);
				}
			}
			
			//asociacion de celulares con el viaje
			$this->model->clearCelulares($id);
			$_POST['celulares'] = isset($_POST['celulares']) ? $_POST['celulares'] : array();
			foreach($_POST['celulares'] as $d){
				if($d){
					$this->model->addCelular($id,$d);
				}
			}
			
			//asociacion de coordinadores con el viaje
			$this->model->clearCoordinadores($id);
			$_POST['coordinadores'] = isset($_POST['coordinadores']) ? $_POST['coordinadores'] : array();
			foreach($_POST['coordinadores'] as $d){
				if($d){
					$this->model->addCoordinador($id,$d);
				}
			}


		}

		$this->generate_dynamic_routes();
		
		if($id && isset($_POST['btnvolver']) && $_POST['btnvolver']){
			redirect($this->data['route'].'/edit/'.$id.'?saved=1');
		}

		if (isset($_GET['silent'])) {
			exit();
		}
	}
	
	//popup de creacion rapida de paquete
	function quickadd() {
		$this->onEditReady('');
		$ret['view'] = $this->load->view('admin/paquetes_simple_form', $this->data, true);
		echo json_encode($ret);
	}
	
	//grabar datos en creacion rapida
	function quicksave() {
		$nombre = trim($this->input->post('nombre'));
		$destino_id = trim($this->input->post('destino_id'));
		$fecha_inicio = trim($this->input->post('fecha_inicio'));
		$fecha_fin = trim($this->input->post('fecha_fin'));
		$ret = [];
		
		if (empty($nombre) || empty($destino_id) || empty($fecha_inicio) || empty($fecha_fin)) {
			$ret['success'] = FALSE;
			$ret['error'] = 'Por favor complete todos los datos.';	
		}
		else {
			
			$aux = explode('/',$fecha_inicio);
			$fecha_inicio = $aux[2].'-'.$aux[1].'-'.$aux[0];
			
			$aux2 = explode('/',$fecha_fin);
			$fecha_fin = $aux2[2].'-'.$aux2[1].'-'.$aux2[0];
			
			if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
				$ret['success'] = FALSE;
				$ret['error'] = 'La fecha de inicio debe ser anterior a la de finalización.';
				echo json_encode($ret);
				return;
			}
			
			$data = array();
			$data['nombre'] = $nombre;
			$data['destino_id'] = $destino_id;
			$data['fecha_inicio'] = $fecha_inicio;
			$data['fecha_fin'] = $fecha_fin;
			$data['slug'] = url_title($nombre);
			
			//GENERO EL CODIGO AUTOMATICO PARA EL PAQUETE QUE SE COMPONE
			//DE LOS 3 CARACTERES DEL DESTINO + 4 NUMEROS CORRELATIVOS AUTOMATICOS
			$destino = $this->Destino->get($destino_id)->row();
			$correlativo = $this->Paquete->getLastCode($destino_id);
			//sino existe, arranco desde el 100
			$nro = isset($correlativo->codigo) && $correlativo->codigo ? substr($correlativo->codigo,3,4) : 1000;
			$nro = (int)$nro+1;
			$nro = zerofill($nro,4);//4 digitos el numero (relleno con 0 a la izquierda)
			$data['codigo'] = $destino->codigo.$nro;
			
			$data['c_exento'] = 0.00;
			$data['c_nogravado'] = 0.00;
			$data['c_gravado21'] = 0.00;
			$data['c_gravado10'] = 0.00;
			$data['c_iva21'] = 0.00;
			$data['c_iva10'] = 0.00;
			$data['c_otros_imp'] = 0.00;
			$data['c_costo_operador'] = 0.00;
			$data['v_exento'] = 0.00;
			$data['v_nogravado'] = 0.00;
			$data['v_gravado21'] = 0.00;
			$data['v_gravado10'] = 0.00;
			$data['v_comision'] = 0.00;
			$data['v_iva21'] = 0.00;
			$data['v_iva10'] = 0.00;
			$data['v_gastos_admin'] = 0.00;
			$data['v_rgafip'] = 0.00;
			$data['v_otros_imp'] = 0.00;
			$data['v_total'] = 0.00;
			$data['fee'] = 0.00;
			$data['fecha_creacion'] = date('Y-m-d H:i:s');
			$data['autor'] = $this->session->userdata('usuario');
			
			$id = $this->Paquete->insert($data);
			
			$new_slug = $data['slug'].'-'.$data['codigo'];
			$this->Paquete->update($id,array('slug'=>$new_slug));

			$ret['success'] = TRUE;
			$ret['redirect'] = $this->data['route']."/edit/".$id;
		}
		
		echo json_encode($ret);
	}
	
	//genera registro para nueva asociacion del paquete con el alojamiento y devuelve el row
	function agregar_alojamiento(){
		extract($_POST);

		$this->load->model('Alojamiento_model', 'Alojamiento');		
		$this->load->model('Fecha_alojamiento_model', 'Alojamiento_fecha');		
		$this->load->model('Fecha_model', 'Transporte_fecha');	
		
		$tra = $this->Transporte_fecha->get($fecha_transporte_id)->row();
		$alo = $this->Alojamiento_fecha->get($fecha_alojamiento_id)->row();
		
		$r_id = $this->Alojamiento->addPaquete($id,$fecha_transporte_id,$fecha_alojamiento_id,$tra->transporte_id,$alo->alojamiento_id);
		
		$row = $this->Alojamiento->getAsociacionPaquete($r_id);		
		$ret['row'] = alojamiento_row($row);
		
		echo json_encode($ret);
	}
	
	//genera registro para nueva asociacion del paquete con la habitacion y devuelve el row
	function agregar_habitacion(){
		extract($_POST);

		$this->load->model('Habitacion_model', 'Habitacion');		
		$r_id = $this->Habitacion->addPaquete($id,$habitacion_id,$cantidad);
		
		$row = $this->Habitacion->getAsociacionPaquete($r_id);		
		$ret['row'] = habitacion_row($row);
		
		echo json_encode($ret);
	}
	
	//genera registro para nueva asociacion del paquete con el regimen de comidas y devuelve el row
	function agregar_regimen(){
		extract($_POST);

		$this->load->model('Regimen_model', 'Regimen');		
		$r_id = $this->Regimen->addPaquete($id,$regimen_id,$fecha_alojamiento_id2);
		
		$row = $this->Regimen->getAsociacionPaquete($r_id);		
		$ret['row'] = regimen_row($row);
		
		echo json_encode($ret);
	}
	
	//genera registro para nueva asociacion del paquete con el adicional y devuelve el row
	function agregar_adicional(){
		extract($_POST);

		$this->load->model('Adicional_model', 'Adicional');		
		$cantidad = isset($cantidad) ? $cantidad : 0;
		$obligatorio = isset($obligatorio) ? $obligatorio : 0;
		$transporte_fecha_id = isset($transporte_fecha_id) ? $transporte_fecha_id : 0;
		$transporte_id = isset($transporte_id) ? $transporte_id : 0;
		if($transporte_fecha_id > 0){
			$this->load->model('Fecha_model', 'Fecha');		
			$tr = $this->Fecha->get($transporte_fecha_id)->row();
			$transporte_id = $tr->transporte_id;
		}
		$r_id = $this->Adicional->addPaquete($id,$adicional_id,$cantidad,$obligatorio,$transporte_fecha_id,$transporte_id);
		
		$row = $this->Adicional->getAsociacionPaquete($r_id);		
		$ret['row'] = adicional_row($row);
		
		echo json_encode($ret);
	}
	
	//genera registro para nueva asociacion del paquete con la parada y devuelve el row
	function agregar_parada(){
		extract($_POST);
	
		$hora = isset($hora) ? $hora : 0;
		$parada_id = isset($parada_id) ? $parada_id : 0;
		$r_id = $this->Parada->addPaquete($id,$parada_id,$hora);
		
		$row = $this->Parada->getAsociacionPaquete($r_id);		
		$ret['row'] = paradas_row($row);
		
		//cargo las opciones a mostrar SIN esta nueva que acabo de agregar
		$mis_paradas = $this->Parada->getByPaquete($id);
		$mis = array();
		foreach($mis_paradas as $p){
			$mis[] = $p->parada_id;
		}
		if(count($mis) > 0)
			$this->Parada->filters = "bv_paradas.id not in (".implode(',',$mis).")";
		$options = $this->Parada->getAll()->result();
		$ret['options'] = $options;
		
		echo json_encode($ret);
	}
	
	//genera registros para las combinaciones del paquete y devuelve el row
	function generar_combinaciones($nuevas=0){
		extract($_POST);
		
		//obtengo los alojamientos, transportes, regimenes y habitaciones asociados al paquete
		$this->load->model('Alojamiento_model', 'Alojamiento');
		$this->load->model('Habitacion_model', 'Habitacion');
		$this->load->model('Lugar_salida_model', 'Lugar');
		
		$html = '';
		
		if ($id) {
			//si hubo cambio de precios actualizo, por las dudas
			$upd_precios = array();
			$upd_precios['c_exento'] = isset($c_exento)?$c_exento:0.00;
			$upd_precios['c_nogravado'] = isset($c_nogravado)?$c_nogravado:0.00;
			$upd_precios['c_gravado21'] = isset($c_gravado21)?$c_gravado21:0.00;
			$upd_precios['c_gravado10'] = isset($c_gravado10)?$c_gravado10:0.00;
			$upd_precios['c_iva21'] = isset($c_iva21)?$c_iva21:0.00;
			$upd_precios['c_iva10'] = isset($c_iva10)?$c_iva10:0.00;
			$upd_precios['c_otros_imp'] = isset($c_otros_imp)?$c_otros_imp:0.00;
			$upd_precios['c_costo_operador'] = isset($c_costo_operador)?$c_costo_operador:0.00;
			$upd_precios['v_exento'] = isset($v_exento)?$v_exento:0.00;
			$upd_precios['v_nogravado'] = isset($v_nogravado)?$v_nogravado:0.00;
			$upd_precios['v_gravado21'] = isset($v_gravado21)?$v_gravado21:0.00;
			$upd_precios['v_gravado10'] = isset($v_gravado10)?$v_gravado10:0.00;
			$upd_precios['v_comision'] = isset($v_comision)?$v_comision:0.00;
			$upd_precios['v_iva21'] = isset($v_iva21)?$v_iva21:0.00;
			$upd_precios['v_iva10'] = isset($v_iva10)?$v_iva10:0.00;
			$upd_precios['v_gastos_admin'] = isset($v_gastos_admin)?$v_gastos_admin:0.00;
			$upd_precios['v_rgafip'] = isset($v_rgafip)?$v_rgafip:0.00;
			$upd_precios['v_otros_imp'] = isset($v_otros_imp)?$v_otros_imp:0.00;
			$upd_precios['v_total'] = isset($v_total)?$v_total:0.00;
			$upd_precios['fee'] = isset($fee)?$fee:0.00;
			$upd_precios['monto_comisionable'] = isset($monto_comisionable)?$monto_comisionable:0.00;
			$this->model->update($id,$upd_precios);
			
			$arrays = array();
			$aloj = $this->model->getAlojamientos($id);
			$habs = $this->model->getHabitaciones($id);
			$regs = $this->model->getRegimenes($id);//echo $this->db->last_query();
			$luga = $this->model->getLugares($id);

			/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190' ){
				pre($luga);
			}*/
			
			$paquete = $this->model->get($id)->row();
/*
			pre($aloj);
			pre($habs);
			pre($regs);
			pre($luga);
			*/
			
			if(!count($aloj)){
				$ret['status'] = 'error';
				$ret['msg'] = 'Debes elegir al menos un alojamiento y transporte.';
				echo json_encode($ret);
				exit();
			}	
			
			if(!count($habs)){
				$ret['status'] = 'error';
				$ret['msg'] = 'El alojamiento elegido no posee habitaciones cargadas.';
				echo json_encode($ret);
				exit();
			}	
			
			if(!count($regs)){
				$ret['status'] = 'error';
				$ret['msg'] = 'Debes elegir al menos un regimen de comidas.';
				echo json_encode($ret);
				exit();
			}	
			
			if(!count($luga)){
				$ret['status'] = 'error';
				$ret['msg'] = 'Debes elegir al menos un lugar de salida.';
				echo json_encode($ret);
				exit();
			}	
			
			//genero combinaciones de alojamientos, transportes, regimenes, habitaciones y lugares de salida
			$arr = combinations($aloj,$habs,$regs,$luga);
			
			
			/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190' ){
				
				pre($aloj);
				pre($habs);
				pre($regs);
				pre($luga);
				pre($arr);
			}*/
			
			
			$comb = array();
			$comb['paquete_id'] = $id;
				
			//si clickeo en GENERAR NUEVAS COMBINACIONES
			if($nuevas){				
				//chequeo de las combinaciones generadas, las que NO existen todavía para solo generar esas
				$inexistentes = $this->Combinacion->getInexistentesPaquete($id,$arr);
				$arr = $inexistentes;
			}
			else{
				//borro las combinaciones existentes del paquete
				$this->Combinacion->deleteWhere($comb);
			}
		
		
			/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190' ){
				pre($luga);
				pre($arr);
			}*/

			$arr = array_map("unserialize", array_unique(array_map("serialize", $arr)));
			
			$this->load->model('Combinacion_model', 'Combinacion');	
			
						
			foreach($arr as $a){
				//genero nuevas combinaciones para paquete
				$comb['alojamiento_id'] = $a[0];
				$comb['transporte_id'] = $a[1];
				$comb['fecha_alojamiento_id'] = $a[2];
				$comb['fecha_transporte_id'] = $a[3];
				$comb['fecha_alojamiento_cupo_id'] = $a[4];
				$comb['habitacion_id'] = $a[5];
				$comb['paquete_regimen_id'] = $a[6];
				$comb['regimen_id'] = $a[7];
				$comb['lugar_id'] = $a[8];
				
				//importo tambien los precios base del paquete para la combinacion
				$comb['c_exento'] = $paquete->c_exento;
				$comb['c_nogravado'] = $paquete->c_nogravado;
				$comb['c_gravado21'] = $paquete->c_gravado21;
				$comb['c_gravado10'] = $paquete->c_gravado10;
				$comb['c_iva21'] = $paquete->c_iva21;
				$comb['c_iva10'] = $paquete->c_iva10;
				$comb['c_otros_imp'] = $paquete->c_otros_imp;
				$comb['c_costo_operador'] = $paquete->c_costo_operador;
				$comb['v_exento'] = $paquete->v_exento;
				$comb['v_nogravado'] = $paquete->v_nogravado;
				$comb['v_gravado21'] = $paquete->v_gravado21;
				$comb['v_gravado10'] = $paquete->v_gravado10;
				$comb['v_comision'] = $paquete->v_comision;
				$comb['v_iva21'] = $paquete->v_iva21;
				$comb['v_iva10'] = $paquete->v_iva10;
				$comb['v_gastos_admin'] = $paquete->v_gastos_admin;
				$comb['v_rgafip'] = $paquete->v_rgafip;
				$comb['v_otros_imp'] = $paquete->v_otros_imp;
				$comb['v_total'] = $paquete->v_total;
				$comb['fee'] = $paquete->fee;
					
				$r_id = $this->Combinacion->insert($comb);
				
				$row = $this->Combinacion->get($r_id)->row();		
				//$html .= combinacion_row($row);
			}
			
			//cargo las filas de todas las combianciones del paquete
			$cs = $this->Combinacion->getByPaquete($id);
			foreach($cs as $c){	
				$html .= combinacion_row($c);
			}
			
			/*
			actualizo cupo total del paquete segun el menor valor entre 
			el cupo total de los alojamientos y el cupo total de los transportes
			*/
			$cupo_h = $this->model->getCupoHabitaciones($id);
			$cupo_t = $this->model->getCupoTransportes($id);
			$this->model->update($id,array('cupo_total' => ($cupo_h>$cupo_t)?$cupo_h:$cupo_t));
		}
		
		$ret['row'] = $html;
		
		echo json_encode($ret);
	}
	
	function ver_precios($id){
		$this->load->model('Combinacion_model', 'Combinacion');	
		$this->data['row'] = $this->Combinacion->get($id)->row();		
		$ret['view'] = $this->load->view('admin/paquetes_combinacion_precios',$this->data,true);
		echo json_encode($ret);
	}
	
	function ver_precios_adicional($id){
		$this->load->model('Adicional_model', 'Adicional');	
		$this->data['row'] = $this->Adicional->getPaqueteAdicional($id)->row();		
		$ret['view'] = $this->load->view('admin/paquetes_adicional_precios',$this->data,true);
		echo json_encode($ret);
	}
	
	function borrar_combinacion($id){
		$this->load->model('Combinacion_model', 'Combinacion');	
		if ($this->Combinacion->delete($id) === FALSE) {
			echo FALSE;
		}
		else {
			echo TRUE;
		}
	}
	
	function borrar_alojamiento($id){
		$this->load->model('Alojamiento_model', 'Alojamiento');	
		if ($this->Alojamiento->deleteAsociacionPaquete($id)) {
			echo TRUE;
		}
		else {
			echo FALSE;
		}
	}
	
	function borrar_habitacion($id){
		$this->load->model('Habitacion_model', 'Habitacion');	
		if ($this->Habitacion->deleteAsociacionPaquete($id)) {
			echo TRUE;
		}
		else {
			echo FALSE;
		}
	}
	
	function borrar_regimen($id){
		$this->load->model('Regimen_model', 'Regimen');	
		if ($this->Regimen->deleteAsociacionPaquete($id) === FALSE) {
			echo FALSE;
		}
		else {
			echo TRUE;
		}
	}
	
	function borrar_adicional($id){
		$this->load->model('Adicional_model', 'Adicional');	
		if ($this->Adicional->deleteAsociacionPaquete($id)) {
			echo TRUE;
		}
		else {
			echo FALSE;
		}
	}
	
	function borrar_parada($id){
		$asoc = $this->Parada->getAsociacionPaquete($id);	
		
		if (!$this->Parada->deleteAsociacionPaquete($id)) {
			echo json_encode([
				'status' => 'error'
			]);
			return;
		}
		
		//cargo las opciones a mostrar SIN esta nueva que acabo de agregar
		$mis_paradas = $this->Parada->getByPaquete($asoc->paquete_id);
		$mis = array();
		foreach($mis_paradas as $p){
			$mis[] = $p->parada_id;
		}
		if(count($mis) > 0)
			$this->Parada->filters = "bv_paradas.id not in (".implode(',',$mis).")";
		$options = $this->Parada->getAll()->result();
		$ret = array();
		$ret['options'] = $options;
		$ret['status'] = 'success';
		
		echo json_encode($ret);
	}
	
	function grabarCombinacion(){
		extract($_POST);
		$this->load->model('Combinacion_model', 'Combinacion');	
		$data = $_POST;
		$data['precio_actualizado'] = '1';
		$this->Combinacion->update($combinacion_id,$data);	
		$row = $this->Combinacion->get($combinacion_id)->row();		
		$row->v_total = number_format($row->v_total,2,'.',',');
		echo json_encode($row);
	}
	
	function grabarPrecioAdicional(){
		extract($_POST);
		
		$this->load->model('Adicional_model', 'Adicional');	
		//obtengo precio actual del adicional para luego compararlo con el nuevo
		$row_orig = $this->Adicional->getPaqueteAdicional($paq_adicional_id)->row();		
		
		$data = $_POST;
		unset($data['paq_adicional_id']);
		$this->Adicional->updatePaqueteAdicional($paq_adicional_id,$data);	
		
		$row = $this->Adicional->getPaqueteAdicional($paq_adicional_id)->row();		
		
		//si el nuevo precio del adicional es mayor que el anterior
		if($row_orig->v_total < $row->v_total){

			//preparo envio de mail de ajuste de precio por cambio del valor del adicional
			$reservas = $this->Reserva->getConAdicionalSaldo($paq_adicional_id);

			foreach($reservas as $r){
				//a cada reserva le actualizo el valor de ladicional en la tabla
				$this->Reserva->updateAdicional($r->id,$paq_adicional_id,array('valor'=>$row->v_total*$r->pasajeros));

				//genero registro en el historial para que luego se le envie mail de ajuste de precio
				$mail = true;
				$template = 'ajuste_precio';
				registrar_comentario_reserva($r->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$r->paquete_nombre." - ".$r->paquete_codigo,$mail,$template);
			
				$ajuste_precio = ($row->v_total-$row_orig->v_total)*$r->pasajeros;
				
				//genero movimiento en cta cte de USUARIO por diferencia en el adicional
				$this->load->model('Movimiento_model', 'Movimiento');
				$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($r->usuario_id,"U",$r->precio_usd)->row();		
				
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $ajuste_precio;
				
				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $r->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $this->settings->cotizacion_dolar;
					
				registrar_movimiento_cta_cte($r->usuario_id,"U",$r->id, date('Y-m-d H:i:s'), 'AJUSTE DE PRECIO - '.$r->paquete_nombre." - ".$r->paquete_codigo,$ajuste_precio,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
					
				//registro en historial el movimiento por ajuste de precio
				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario por AJUSTE DE PRECIO. CONCEPTO: '.$r->paquete_nombre." - ".$r->paquete_codigo.' | DEBE '.$ajuste_precio);
						
				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$r->precio_usd)->row();			
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $ajuste_precio;
				registrar_movimiento_cta_cte(1,"A",$r->id, date('Y-m-d H:i:s'), $r->paquete_nombre." - ".$r->paquete_codigo,$ajuste_precio,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS por AJUSTE DE PRECIO. CONCEPTO: '.$r->paquete_nombre." - ".$r->paquete_codigo.' | DEBE '.$ajuste_precio);
				//-------------------------------------------------------------------------------------------------------------------

			}
		}
	
		$row->v_total = number_format($row->v_total,2,'.',',');

		echo json_encode($row);
	}
	
	//actualiza precios del paquete y los replica en las combinaciones
	function updatePrecios(){
		extract($_POST);
		
		$paq = array();
		$paq['c_exento'] = $c_exento;
		$paq['c_nogravado'] = $c_nogravado;
		$paq['c_gravado21'] = $c_gravado21;
		$paq['c_gravado10'] = $c_gravado10;
		$paq['c_iva21'] = $c_iva21;
		$paq['c_iva10'] = $c_iva10;
		$paq['c_otros_imp'] = $c_otros_imp;
		$paq['c_costo_operador'] = $c_costo_operador;
		$paq['v_exento'] = $v_exento;
		$paq['v_nogravado'] = $v_nogravado;
		$paq['v_gravado21'] = $v_gravado21;
		$paq['v_gravado10'] = $v_gravado10;
		$paq['v_comision'] = $v_comision;
		$paq['v_iva21'] = $v_iva21;
		$paq['v_iva10'] = $v_iva10;
		$paq['v_gastos_admin'] = $v_gastos_admin;
		$paq['v_rgafip'] = $v_rgafip;
		$paq['v_otros_imp'] = $v_otros_imp;
		$paq['v_total'] = $v_total;
		$paq['fee'] = $fee;
				
		$this->model->update($id,$paq);
		
		$this->load->model('Combinacion_model', 'Combinacion');	
		$this->Combinacion->updatePrecios($id,$paq);
		
		$this->db->where('pc.precio_actualizado', '0');
		$precios = $this->Combinacion->getByPaquete($id);
		
		$ids = array();
		foreach($precios as $p){
			$ids[] = $p->id;
		}
		$paq['ids'] = $ids;
		
		
		//$ids son todas las combinaciones afectadas por el ajuste de precio
		//obtengo las reservas de todas estas combinaciones que ESTEN EN ESTAD NUEVA O POR VENCER CON SALDO PENDIENTE 
		//para generar registro de que se les envie el mail avisando el ajuste de precio
		$reservas = $this->Reserva->getConfirmadasConSaldoCombinacion($ids);
		
		foreach($reservas as $r){
			//genero registro en el historial para que luego se le envie mail de ajuste de precio
			$mail = true;
			$template = 'ajuste_precio';
			//25-06-18
			//cuando se habilite de nuevo esta acción hay que cambiar el mensaje en paquetes_form (linea 1449)
			//registrar_comentario_reserva($r->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$r->nombre." - ".$r->codigo,$mail,$template);
			
			//si el nuevo precio de venta v_total es payor que lo que contrató el pasajero
			if($v_total > ($r->paquete_precio+$r->impuestos)/$r->pasajeros && $r->saldo > 0){
				$ajuste_precio = $v_total-($r->paquete_precio+$r->impuestos)/$r->pasajeros;
				
				//genero movimiento en cta cte de USUARIO por monto de reserva
				$this->load->model('Movimiento_model', 'Movimiento');
				$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($r->usuario_id,"U",$r->precio_usd)->row();		
				
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $ajuste_precio;
				
				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $r->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $this->settings->cotizacion_dolar;
					
				registrar_movimiento_cta_cte($r->usuario_id,"U",$r->id, date('Y-m-d H:i:s'), 'AJUSTE DE PRECIO - '.$r->nombre." - ".$r->codigo,$ajuste_precio,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
					
				//registro en historial el movimiento por ajuste de precio
				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario por AJUSTE DE PRECIO. CONCEPTO: '.$r->nombre." - ".$r->codigo.' | DEBE '.$ajuste_precio);
						
				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$r->precio_usd)->row();			
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $ajuste_precio;
				registrar_movimiento_cta_cte(1,"A",$r->id, date('Y-m-d H:i:s'), $r->nombre." - ".$r->codigo,$ajuste_precio,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS por AJUSTE DE PRECIO. CONCEPTO: '.$r->nombre." - ".$r->codigo.' | DEBE '.$ajuste_precio);
				//-------------------------------------------------------------------------------------------------------------------

			}
			
		}
		
		echo json_encode($paq);
	}
	
	function get_excursiones($destino_id){		
		//excursiones generales y particulares
		$this->load->model('Excursion_model', 'Excursion');
		$arr = array(0,"",NULL);
		$arr2 = array($destino_id);
		$exc = $this->Excursion->getAllDestinos($arr,$arr2);
		$ret = array();
		foreach($exc as $e){
			$ret[] = array(
						'id' => $e->excursion_id,
						'nombre' => $e->nombre
					);
		}
		
		echo json_encode($ret);
	}

	function agregar_lugar_salida(){
		extract($_POST);
		
		$this->load->model('Lugar_salida_model', 'Lugar_salida');		
				
		$this->Lugar_salida->deleteByPaquete($id);
		
		if(@$lugares){
			foreach($lugares as $lugar){
				$this->Lugar_salida->addPaquete($id,$lugar);
			}
		}
		
		$ret['row'] = '';
		
		echo json_encode($ret);
	}
	
	function probar($id=2){
		$cupo_habs = $this->model->getCupoTransportes($id);
		echo $this->db->last_query();
	}
	
	/* Exporta la lista de Interesados para el paquete */
	function exportar_lista_espera($id){
		$results = $this->Lista_espera->getAllExport($id,'paquete');
		$this->page = "lista-de-interesados-paquete-".$id;
		parent::exportar($results);
	}
	
	//VALIDA CODIGO DEL DESTINO SI EXISTE O NO
	function validar_codigo($id){
		if(validar_codigo($id)){
			$ret['status'] = 'ok';
		}
		else{
			$ret['status'] = 'error';
		}
		echo json_encode($ret);
	}
	
	function duplicar($id){
		$nuevo_id = duplicar_paquete($id);
		
		redirect(site_url('admin/paquetes?duplicado='.$nuevo_id));
	}
	
	function del_combinaciones(){
		$ids = isset($_POST['ids']) ? $_POST['ids'] : false;
		
		foreach($ids as $id){
			$this->load->model('Combinacion_model', 'Combinacion');	
			$this->Combinacion->delete($id);
		}
		
		$ret['success'] = true;
		echo json_encode($ret);
	}
	
	function borrar_alerta_cupo($id){
		$this->Paquete->update($id,array('alerta_cupos_revisar' => '0'));
		redirect(site_url('admin/paquetes/edit/'.$id));
	}

	//prueba generacion de combinaciones
	function probar_comb($id=0){
		
		//obtengo los alojamientos, transportes, regimenes y habitaciones asociados al paquete
		$this->load->model('Alojamiento_model', 'Alojamiento');
		$this->load->model('Habitacion_model', 'Habitacion');
		$this->load->model('Lugar_salida_model', 'Lugar');
		
		$html = '';
		
		if ($id) {
			
			$arrays = array();
			$aloj = $this->model->getAlojamientos($id);
			$habs = $this->model->getHabitaciones($id);
			$regs = $this->model->getRegimenes($id);//echo $this->db->last_query();
			$luga = $this->model->getLugares($id);
			$paquete = $this->model->get($id)->row();
			
			pre($aloj);
			pre($habs);
			pre($regs);
			pre($luga);
			
			//genero combinaciones de alojamientos, transportes, regimenes, habitaciones y lugares de salida
			$arr = combinations($aloj,$habs,$regs,$luga);
	
			pre($arr);
			exit();
			
		}
		
	}
	
}
