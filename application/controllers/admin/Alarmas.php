<?php
include "AdminController.php";

class Alarmas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Reserva_model', 'Reserva');
		$this->load->model('Usuario_model', 'Usuario');
		$this->load->model('Admin_model', 'Admin');
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->load->model('Operador_model', 'Operador');
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Mailing_model','Mailing');
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$this->load->model('Pasajero_model','Pasajero');
		$this->load->model('Reserva_facturacion_model','Reserva_facturacion');
		$this->load->model('Reserva_estado_model','Reserva_estado');
		$this->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$this->load->model('Reserva_voucher_model', 'Reserva_voucher');	
		$this->load->model('Concepto_model', 'Concepto');	
		$this->load->model('Reserva_comentario_model','Comentario');
		$this->load->model('Comentario_tipo_accion_model','Comentario_tipo');
		$this->load->model('Adicional_model','Adicional');
		$this->load->model('Pais_model','Pais');
		$this->load->model('Habitacion_model','Habitacion');
		$this->load->model('Paquete_rooming_model','Paquete_rooming');
		$this->model = $this->Reserva;
		$this->page = "alarmas";
		$this->data['currentModule'] = "reservas";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Alarmas del sistema";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();
		
		$this->load->model('Lugar_salida_model','Lugar_salida');
		$this->data['lugares'] = $this->Lugar_salida->getAll(999,0,'nombre','asc')->result();
		
		//no mostrar estos
		/*
		ANULACIÓN RESERVA, COSTO PAQUETE ANULACION, COSTO PAQUETE REGISTRO
		PENALIDAD POR ANULACION
		*/
		$this->Concepto->filters = "id not in(2,56,57,10) and sistema_caja = 0";
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();
				
		$this->data['dietas'] = array(
									array('nombre' => 'Vegetariano'),
									array('nombre' => 'Celíaco'),
									array('nombre' => 'Diabético'),
									array('nombre' => 'Ninguno'),
								);
		$this->data['sexos'] = array(
									array('id' => 'femenino','nombre' => 'Femenino'),
									array('id' => 'masculino','nombre' => 'Masculino'),
								);
								
		$this->data['estados'] = $this->Reserva_estado->getAll(999,0,'nombre','asc')->result();
		$this->data['vendedores'] = $this->Vendedor->getAll(999,0,'nombreCompleto','asc')->result();

		/*
		ADMINISTRACION
			1) Usuarios que registran deudas pasado el plazo de pago.
			2) Hay pagos sin cargar (informes de pago)
			3) Confirmar tipo de cambio (mercadopago) *NUEVO
			4) Rendicion de gastos vencida
			5) Etc.
		VENTAS

			6) Usuarios en lista de espera
			7) Hay llamados por hacer (anuladas, no pagadas)
			8) Cupo liberado
			9) Anulado por falta de pago
			10) Etc.
		OPERACIONES
			11) Faltan completar datos de pasajeros
			12) Enviar el mail pre viaje
			13) Cupo vencido
			14) Falta enviar voucher
			15) Falta asignar el coordinador a un paquete
			16) Rendicion de gastos vencida
			17) Etc.
		*/

		/* carga las alarmas permitidas para el tipo de perfil logueado*/
		$this->data['alarmas'] = cargar_alarmas_perfil();

		ini_set('max_execution_time', 360);

		if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			$this->output->enable_profiler(TRUE);
			ini_set('memory_limit','256M');
			ini_set('max_execution_time','3600');
		}
	}
	
				
	
	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?('Cód. '.$this->data['row']->code):'';
		
		if($id){
			$reserva = $this->data['row'];
			$reserva->adicionales = adicionales_reserva($reserva);
			$adicionales_reservados = array();
			foreach($reserva->adicionales as $a){
				$adicionales_reservados[] = $a->paquete_adicional_id;
			}
			$reserva->adicionales_reservados = $adicionales_reservados; 
			
			$this->data['row'] = $reserva; 
			
			$this->data['paquete_id'] = $reserva->paquete_id;
			$this->data['paquete'] = $this->Paquete->get($reserva->paquete_id)->row();
			$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
		
			$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

			//datos facturacion (para paso 2)
			$this->Reserva_facturacion->filters = "reserva_id = ".$reserva->id;
			$this->data['facturacion'] = $this->Reserva_facturacion->getAll(1,0,'id','asc')->row();	
			
			//responsable y acompañantes, ordenado por numero de pax asc
			$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.reserva_id = ".$reserva->id;
			$this->data['pasajeros'] = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
			
			//acompañantes (para ver cuantos incompletos hay)
			$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 0 and bv_reservas_pasajeros.reserva_id = ".$reserva->id;
			$this->data['acompanantes'] = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
		
			//informes de pago
			$this->Reserva_informe_pago->filters = "bv_reservas_informes_pago.reserva_id = ".$reserva->id;
			$informes_pago = $this->Reserva_informe_pago->getAll(999,0,'bv_reservas_informes_pago.fecha_pago','desc')->result();
		
			foreach($informes_pago as $inf){
				/* por cada informe
				trato de definir qué Concepto le corresponde a este informe,
				según los campos completados
				$banco
				$tipo_pago
				$tipo_moneda
				*/
				//armo array de filtros con los datos que machean
				$filtros = 'aplica_a = "haber"';
				
				//FILTRO BANCO
				$banco = $inf->banco;
				$banco = explode(' ',$banco);
				if(isset($banco[1]) && $banco[1]){
					$filtros .= ' and nombre like "%'.$banco[1].'%"';
				}
				
				//FILTRO TIPO PAGO
				$tipo_pago = $inf->tipo_pago;
				if(strpos($tipo_pago,'Depósito') !== false){
					$filtros .= ' and nombre like "%deposito%"';
					$filtros .= ' and nombre not like "%transferencia%"';
				}
				if(strpos($tipo_pago,'Transferencia') !== false){
					$filtros .= ' and nombre like "%transferencia%"';
					$filtros .= ' and nombre not like "%deposito%"';
				}
				
				//FILTRO TIPO MONEDA
				if($inf->tipo_moneda == 'ARS'){
					$filtros .= ' and nombre not like "%USD%"';
				}
				if($inf->tipo_moneda == 'USD'){
					$filtros .= ' and nombre like "%USD%"';
				}
				
				$concepto = $this->Concepto->getWhere($filtros)->result();
				
				$concepto = isset($concepto[0]) ? $concepto[0] : false;
				$inf->concepto = $concepto;
			}
			
			$this->data['informes_pago'] = $informes_pago;
				
			//cargo adicionales asociados a la reserva
			$this->data['paquete_adicionales'] = $this->Adicional->getByPaquete($reserva->paquete_id);
			
			$this->cargar_cuenta_corriente($this->data['row']->usuario_id,'U',$reserva->id);
			
			$this->cargar_historial($reserva->id);
			
			//para cambio de paquete (combiancion)
			$this->data['paquete_combinaciones'] = $this->Combinacion->getByPaquete($this->data['paquete']->id);
			
			//cargo los vouchers asociados a la reserva
			$this->data['mis_vouchers'] = $this->Reserva_voucher->getWhere(array('reserva_id' => $id))->result();
		}
	}
		
	function onAfterSave($id) {
		//actualizo datos de pasajeros
		$reserva_row = $this->model->get($id)->row();

		//array para guardar los datos modificados de cada pasajero
		$data_updated = array();

		//por cada pasajero tomo los datos del form
		for ($i=1;$i<=$reserva_row->pasajeros;$i++) {
			$str_pax = @$_POST['pasajero_'.$i];

			if(isset($str_pax) && $str_pax){
				parse_str($str_pax,$data_pax);

				if(isset($data_pax['fecha_nacimiento']) && $data_pax['fecha_nacimiento'] != ''){
					$nac = explode('/', $data_pax['fecha_nacimiento']);
					$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
					$data_pax['fecha_nacimiento'] = $nac;
				}
				if(isset($data_pax['fecha_emision']) && $data_pax['fecha_emision'] != ''){
					$nac = explode('/', $data_pax['fecha_emision']);
					$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
					$data_pax['fecha_emision'] = $nac;
				}
				if(isset($data_pax['fecha_vencimiento']) && $data_pax['fecha_vencimiento'] != ''){
					$nac = explode('/', $data_pax['fecha_vencimiento']);
					$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
					$data_pax['fecha_vencimiento'] = $nac;
				}

				if(isset($data_pax['pasajero_id']) && $data_pax['pasajero_id']){
					$pax_id = $data_pax['pasajero_id'];
					unset($data_pax['pasajero_id']);//no lo necesito al grabar
					unset($data_pax['pasajero_'.$i]);//no lo necesito al grabar
					
					//si modifico algun dato
					if(count($data_pax)){
						$row_pax = $this->Reserva_pasajero->getWhere(array('reserva_id'=>$id,'pasajero_id'=>$pax_id))->row();

						//de cada pasajero por actualizar, comparo con los datos actuales para saber cuáles modificó y lo guardo en los comentarios
						foreach($data_pax as $kk=>$vv){
							if($row_pax->{$kk} != $vv){
								$data_updated[$i][$kk] = $vv;

								if($kk == 'pais_emision_id' && $vv){
									$p = $this->Pais->get($vv)->row();
									$data_updated[$i]['pais_emision'] = $p->nombre;
								}
								if($kk == 'nacionalidad_id' && $vv){
									$p = $this->Pais->get($vv)->row();
									$data_updated[$i]['nacionalidad'] = $p->nombre;
								}
							}
						}
						
					}

					foreach($data_pax as $k=>$v){
						if($v == ''){
							unset($data_pax[$k]);
						}
					}

					//actualizo cambios nuevos
					$this->Pasajero->update($pax_id,$data_pax);						
				}
			}
		}

		//si hay datos actualizados
		if(count($data_updated) > 0){
			//me fijo si es un admin o un vendedor
			if(esVendedor()){
				$user_logged = $this->Vendedor->get(admin_id())->row();
				$texto_historial = 'El vendedor '.$user_logged->nombre.' '.$user_logged->apellido.' actualizó datos de los pasajeros.';
			}
			else{
				$user_logged = $this->Admin->get(admin_id())->row();
				$texto_historial = 'El usuario '.$user_logged->nombre.' actualizó datos de los pasajeros.';
			}

			$data_updated = json_encode($data_updated);

			//registro el update de datos de pax, ($data_updated lo guardo como array)
			registrar_comentario_reserva($id,admin_id(),'update_datos_pax',$texto_historial,false,false,false,$data_updated);		
		}

		if($id && isset($_POST['btnvolver']) && $_POST['btnvolver']){
			redirect($this->data['route'].'/edit/'.$id.'?saved=1');
		}
	}
	
	function set_alarma($alarma_id=''){
		//filtro por tipo de alarma
		if($alarma_id){
			$this->session->set_userdata('alarma_id',$alarma_id);
		}
		else{
			$this->session->unset_userdata('alarma_id');	
		}
		redirect(site_url('admin/alarmas'));
	}

	// function index_old(){
		
	// 	//$this->output->enable_profiler(TRUE);		

	// 	//si esta logueado un vendedor 
	// 	if ($this->session->userdata('es_vendedor')){
	// 		$admin_id = $this->session->userdata('admin_id');
	// 	}
	// 	else
	// 		$admin_id = '';
		
	// 	//si eligio alarma
	// 	if ($this->session->userdata('alarma_id')){
	// 		$alarma_id = $this->session->userdata('alarma_id');
	// 	}
	// 	else
	// 		$alarma_id = '';
	// 	$this->data['alarma_id'] = $alarma_id;
		
	// 	//listado de reservas ordenado por fecha de reserva -> las mas nuevas arriba
	// 	if($this->data['sort'] == "id"){
	// 		$this->data['sort'] = "R.code";
	// 		$this->data['sortType'] = "desc";
	// 	}
		
	// 	$data_primeros = array();
	// 	$data_restantes = array();
	// 	$data = $this->model->getAllAlarmas($this->data['sort'],$this->data['sortType'],'', '', '', 9999, 0,TRUE);
	
	// 	if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
	// 		#echo $this->db->last_query();
	// 	}

	// 	$this->data['totalRows'] = count($data->result());
		
	// 	foreach($data->result() as $row){			
	// 		$row->tipo_id = $row->usuario_id;
	// 		$row->tipo = 'U';			
			
	// 		$row->fecha_original = $row->fecha.' hs';
	// 		$fecha_hora = $row->fecha;
	// 		$fecha_hora = explode(" ",$fecha_hora);
	// 		$row->hora = substr($fecha_hora[1],0,5);
	// 		$row->fecha = date('d/m/Y',strtotime($row->fecha));
			
	// 		//alarmas de cada reserva
	// 		/*if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
	// 			$row->alarmas = false;
	// 			$row->tiene_alarmas = true;
	// 		}
	// 		else{
	// 			$row->alarmas = $this->cargar_alarmas($row);
	// 		}
	// 		*/
				
	// 		$row->alarmas = $this->cargar_alarmas($row);

	// 		//las reservas q no tienen alarmas no las considero
	// 		//if(){
			
	// 		//debug, mi ip que muestre todas, incluso las q no tienen alarmas
	// 		if($row->tiene_alarmas){

	// 			//si no hay alarma elegida ó si la alarma elegida la posee la reserva en cuestion
	// 			if($this->data['alarma_id'] == '' or (isset($row->alarmas->{$this->data['alarma_id']}) && $row->alarmas->{$this->data['alarma_id']}>0)){
					
	// 				$tiene_alarma = false;
	// 				if($this->data['alarma_id'] == ''){
	// 					//si no está elegida ninguna alarma, que solo muestre las admitidas por el perfil
	// 					$al_pe = get_alarmas();
						
	// 					foreach($al_pe as $a){
	// 						//solo las admitidas muestro
	// 						if( isset($row->alarmas->{$a['id']}) && $row->alarmas->{$a['id']}>0 ){
	// 							$tiene_alarma = true;
	// 							break;
	// 						}
	// 					}
	// 				}
	// 				else{
	// 					$tiene_alarma = true;
	// 				}
					
	// 				if($tiene_alarma){
	// 					//ordeno primero las que tienen estado POR ACREDITAR
	// 					if($row->estado_id == 14){
	// 						$data_primeros[] = $row;
	// 					}
	// 					else{
	// 						$data_restantes[] = $row;	
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}

	// 	$data = array_merge($data_primeros,$data_restantes);
		
	// 	$estados = $this->Reserva_estado->getAll('','');
	// 	$this->data['estados'] = $estados->result();
		
	// 	$this->data['data'] = $data;
		
	// 	$this->load->view('admin/alarmas',$this->data);
	// }

	function index(){
		$export = isset($_GET['export']) && $_GET['export'] == 1 ? true : false;
		
		//$this->output->enable_profiler(TRUE);		

		//si esta logueado un vendedor 
		if ($this->session->userdata('es_vendedor')){
			$admin_id = $this->session->userdata('admin_id');
		}
		else
			$admin_id = '';
		
		//si eligio alarma
		if ($this->session->userdata('alarma_id')){
			$alarma_id = $this->session->userdata('alarma_id');
		}
		else
			$alarma_id = '';
		$this->data['alarma_id'] = $alarma_id;
		
		//listado de reservas ordenado por fecha de reserva -> las mas nuevas arriba
		if($this->data['sort'] == "id"){
			$this->data['sort'] = "R.code";
			$this->data['sortType'] = "desc";
		}
		
		$data_primeros = array();
		$data_restantes = array();
		
		if($export){
			$data = $this->model->getAllAlarmasExport($this->data['sort'],$this->data['sortType']);
		}
		else{
			$data = $this->model->getAllAlarmas($this->data['sort'],$this->data['sortType']);
		}	
		
		$this->data['totalRows'] = count($data->result());
		
		foreach($data->result() as $row){			
			$row->tipo_id = $row->usuario_id;
			$row->tipo = 'U';			
			
			$row->fecha_original = $row->fecha.' hs';
			$fecha_hora = $row->fecha;
			$fecha_hora = explode(" ",$fecha_hora);
			$row->hora = substr($fecha_hora[1],0,5);
			$row->fecha = date('d/m/Y',strtotime($row->fecha));
			
			$row->alarmas = $this->cargar_alarmas_optim($row);
			// $row->alarmas = $this->cargar_alarmas($row);

			
			if($row->tiene_alarmas){

				//si no hay alarma elegida ó si la alarma elegida la posee la reserva en cuestion
				if($this->data['alarma_id'] == '' or (isset($row->alarmas->{$this->data['alarma_id']}) && $row->alarmas->{$this->data['alarma_id']}>0)){

					//ordeno primero las que tienen estado POR ACREDITAR
					if($row->estado_id == 14){
						$data_primeros[] = $row;
					}
					else{
						$data_restantes[] = $row;	
					}
				}
			}
			
			$row->alarmas_vigentes = '';
			if($export){
				unset($row->informes);
				unset($row->reserva_id);
				unset($row->completar_datos_pax);
				unset($row->alerta_no_llamar);
				unset($row->alerta_llamar_pax);
				unset($row->alerta_reestablecida);
				unset($row->alerta_contestador);
				unset($row->falta_factura_proveedor);
				unset($row->faltan_cargar_vouchers);
				unset($row->alerta_cupos_vencidos);
				unset($row->fecha_limite_pago_completo);
				unset($row->diferencias_rooming);
				unset($row->tiene_adicinales);
				unset($row->timestamp);
				unset($row->tipo_id);
				unset($row->tipo);
				unset($row->fecha_original);
				unset($row->fecha);
				unset($row->hora);
				unset($row->tiene_alarmas);
				unset($row->tiene_adicionales);

				$row->alarmas_vigentes = nombres_alarmas($row->alarmas);

				unset($row->alarmas);
			}
			
		}

		$data = array_merge($data_primeros,$data_restantes);
		
		$estados = $this->Reserva_estado->getAll('','');
		$this->data['estados'] = $estados->result();
		
		$this->data['data'] = $data;
		
		if($export){
			exportExcel($data,$filename=$this->page);
		}
		else{
			$this->load->view('admin/alarmas',$this->data);
		}
	}
	
	function cargar_alarmas($row){
		$alarmas = cargar_alarmas($row, true);	

		$row->tiene_alarmas = false;
		foreach ($alarmas as $key => $value) {
			if($value){
				$row->tiene_alarmas = true;
				break;
			}
		}
		return $alarmas;
	}

	function cargar_alarmas_optim(&$row){
		$alarmas = cargar_alarmas_optim($row);

		$row->tiene_alarmas = false;
		foreach ($alarmas as $key => $value) {
			if($value){
				$row->tiene_alarmas = true;
				break;
			}
		}
		
		return $alarmas;
	}
	
	//para exportar csv que Marce necesita informar en AFIP
	//sería solo para pasajeros que viajan al exterior
	function export_csv($id){
		$paquete = $this->Paquete->get($id)->row();
		
		$query = $this->model->getAllExterior_export($id);
		
		query_to_csv($query, TRUE, 'paquete-'.$paquete->codigo.'.csv',$separation_line=true);
	}
	
	//descargar formato MANIFIESTO
	function manifiesto($id){
		$p = $this->Paquete->get($id)->row();
		$this->data['destino'] = $this->Destino->get($p->destino_id)->row();	
		
		$this->data['paquete'] = $p;	
		
		//solo las confirmadas
		$this->db->where('R.estado_id = 4');
		$this->data['pasajeros'] = $this->model->getListaPasajeros($id)->result();		
		
		$this->data['coordinadores'] = $this->Paquete->getCoordinadores($id)->row();	
		
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=manifiesto.xls");
		$this->load->view('admin/manifiesto',$this->data);
	}

	//descarga XLS
	function export($id){
		if( $this->session->userdata('es_vendedor') ){
			$results = $this->model->getAllByPaqueteAndVendedor_export($id,$this->session->userdata('admin_id'))->result();
		}
		else{
			if(isset($_POST) && count($_POST) > 0){
				foreach($_POST as $k=>$v){
					$download_fields[] = $v;
				}
			}
		
			$results = $this->model->getAllByPaquete_export($id, isset($download_fields)?$download_fields:'')->result();
		}
		
		$fem = 0;
		$masc = 0;
		foreach($results as $res){
			if(isset($res->sexo) && $res->sexo == 'femenino'){
				$fem+=1;
			}
			if(isset($res->sexo) && $res->sexo == 'masculino'){
				$masc+=1;
			}
		}
		
		if($fem > 0 && $masc > 0){
			//agrego nueva fila para total de hombres y mujeres
			$results[count($results)] = (object) array (
												   'id_reserva' => ' '
												);
												
			$results[count($results)+1] = (object) array (
												   'id_reserva' => 'Total mujeres ',
												   'fecha' => $fem
												);
					 
			$results[count($results)+2] = (object) $tot = array (
														   'id_reserva' => 'Total hombres ',
														   'fecha' => $masc
														);
		}
		
		return parent::exportar($results);
	}
	
	function generarCupon($reserva_id){
		$reserva = $this->Reserva->get($reserva_id)->row();
		
		//genero registro en el historial para que luego se le envie mail de reserva
		$mail = true;
		$template = 'datos_reserva';
		registrar_comentario_reserva($reserva_id,7,'envio_mail','Envio de email por datos de reserva al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
		
		echo "El envío del mail al pasajero ha sido agendado. En breve será enviado.";
	}
	
	/*Envia mail de voucher de pago completo manualmente*/
	function generar_voucher_pago($reserva_id){		
		$reserva = $this->Reserva->get($reserva_id)->row();
		
		//genero registro en el historial para que luego se le envie mail de reserva
		$mail = true;
		$template = 'pago_completo';
		registrar_comentario_reserva($reserva_id,7,'envio_mail','Envio de email por pago completo de reserva. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
		
		echo "El envío del mail al pasajero ha sido agendado. En breve será enviado.";
	}
	
	function form_cambiar_estado($reserva_id,$id){
		$this->data['reserva'] = $this->Reserva->get($reserva_id)->row();
		$this->data['paquete'] = $this->Paquete->get($id)->row();
		
		//solo traigo estados nueva y anulada para cambiar manualmente (sin el estado actual)
		if($this->data['reserva']->estado_id == 1){
			$this->Reserva_estado->filters = "id in (5)";
		}
		else if($this->data['reserva']->estado_id == 5){
			$this->Reserva_estado->filters = "id in (1)";	
		}
		else{
			$this->Reserva_estado->filters = "id in (1,5)";	
		}

		$this->data['estados'] = $this->Reserva_estado->getAll(999,0,'nombre','asc')->result();

		$ret['view'] = $this->load->view('admin/reservas_cambiar_estado',$this->data,true);
		echo json_encode($ret);
	}
	
	function cambiar_estado($res_id,$estado_id,$return=false){
		$data = array("estado_id"=>$estado_id);
		
		//obtengo estado de reserva antes de update
		$reserva = $this->Reserva->get($res_id)->row();
		
		$this->Reserva->update($res_id,$data);
		
		$this->actualizarEstado($res_id,$estado_id,$reserva->estado_id);
		
		if($return)
			return $estado_id;
		else
			echo $estado_id;
	}
	
	function actualizarEstado($id,$estado_id,$estado_ant){
		$estado = $this->Reserva_estado->get($estado_id)->row();
		
		//registro el cambio de estado
		registrar_comentario_reserva($id,admin_id(),'cambio_estado','Cambio de estado de reserva manualmente. Estado '.$estado->nombre);
		
		$reserva = $this->model->get($id)->row();
		
		$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
			
		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		$usuario = $this->Usuario->get($reserva->usuario_id)->row();
		
		//si la reserva pasa a estado ANULADA manualmente
		if($estado_ant!= 5 && $estado_id == 5){
			$mov5 = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();
			$nuevo_parcial5 = isset($mov5->parcial) ? $mov5->parcial : 0.00;
			
			//si la reserva estaba en A CONFIRMAR y se ANULA -> no le genero el movimiento en cuenta corriente ya que la reserva
			//cuando se dio de alta desde el sitio NO se le registro el movimiento en su cuenta
			if($estado_ant != 13){
				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,date('Y-m-d H:i:s'),"ANULACION RESERVA",0.00,$monto_reserva,$nuevo_parcial5,'',isset($comprobante)?$comprobante:'','','','','',$reserva->precio_usd?'USD':'ARS',$reserva->cotizacion?$reserva->cotizacion:$this->settings->cotizacion_dolar,false,false,false);
			}
			
			//Registrar el envio de mail de reserva anulada manualmente
			$mail = true;
			$template = 'reserva_anulada_manual';
			registrar_comentario_reserva($id,admin_id(),'anulacion',isset($_POST['motivo'])?$_POST['motivo']:'Reserva anulada manualmente',$mail,$template,$ref_id=false);
			
			verificar_costo_operador($reserva);
			
			//se agrega envio de mail al usuario cuando se cancela manualmente
			$this->data['reserva'] = $reserva;			


			//genero movimientos de quita de adicionales si corresponde 
			$find = array('m.cta_usd'=>($reserva->precio_usd?1:0),'m.usuario_id'=>$reserva->usuario_id,'m.tipoUsuario' => 'U','m.reserva_id' => $reserva->id);
			$movs = $this->Movimiento->getAdicionalesSinAnular($find);
			foreach ($movs as $m) {
				//por cada movimiento le genero el de anulacion de dicho adicional
				//3er parametro para que me devuelva el dato acá
				$this->eliminar_adicional($m->reserva_id,$m->paquete_adicional_id,$return=true);
			}
		}
		else if( 
				( ($estado_ant == 13 || $estado_ant == 5) && $estado_id == 1 )
				|| ($estado_ant == 5 && $estado_id == 4) 
			){
			//si estaba ANULADA o POR ACREDITAR y pasa a estado nueva genero los mov correspondientes
			
			//ó si pasa a CONFIRMADA manualmente estando antes ANULADA, le genero el movimiento de reserva nueva

			//genero movimiento en cta cte de ese usuario
			$reserva_id = $reserva->id;
			$usuario_id = $reserva->usuario_id;
			$fecha_reserva = date('Y-m-d H:i:s');
			
			$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($usuario_id,"U",$reserva->precio_usd)->row();		
			$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;
			
			//globales
			$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
			$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
			$tipo_cambio = $this->settings->cotizacion_dolar;
			
			registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
			
			//tambien seteo marcas en el historial para que luego se le envie mail de reserva
			$mail = true;
			$template = 'datos_reserva';

			//si la reserva estaba anulada y la pasa a NUEVA
			$tipo_accion = 'movimiento_cta_cte';
			if($estado_ant == 5 && $estado_id == 1){
				$tipo_accion = 'nueva_reserva';
			}

			registrar_comentario_reserva($reserva_id,7,$tipo_accion,'Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);
				
			//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
			$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();			
			$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
			registrar_movimiento_cta_cte(1,"A",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
			registrar_comentario_reserva($reserva_id,7,$tipo_accion,'Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
			//-------------------------------------------------------------------------------------------------------------------	

			//por pedido de Juan, si luego de pasarla de ANULADA a NUEVA y detecto que hay pagos hechos sobre la reserva, la paso a CONFIRMADA
			$mov = $this->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
			$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
			if($pagos_hechos > 0){
				$this->Reserva->update($reserva->id,array('estado_id' => 4));
			}
		}
		
		return 1;
	}
	

	//metodo que se ejecuta cuando desde el form de reservas ó listado de reserva de paquetes
	//se intenta pasar a NUEVA una reserva
	function chequear_disponibilidad_paquete($paquete_id,$reserva_id=''){
		//echo chequear_disponibilidad_paquete($paquete_id,$reserva_id);
		echo true;
	}
	
	//03-09-2014 se usa esta que es generica para todos los adicionales en lugar de la de arriba
	function chequear_disponibilidad_adicionales($paq_id,$res_id=''){
		//echo chequear_disponibilidad_adicionales($paq_id,$res_id, isset($_POST) ? $_POST : '');
		echo true;
	}
	
	
	function saldo(){
		echo enviar_informe_pago_recibido($reserva_id=3,$informe_id=5);
	}

	//Poner la reserva en estado de POR ACREDITAR, para que no la tome el proceso de update de reservas
	//en realidad ahora solo se cambia la fecha extendida pero no cambia de estado
	function por_acreditar($reserva_id) {
		extract($_POST);
				
		$fecha = isset($fecha) ? $fecha : date('Y-m-d');
		$hora = isset($hora) ? $hora : date('H:i');
		
		$fecha = explode('/',$fecha);
		$fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
		$fecha = $fecha.' '.$hora.':00';

		$reserva = $this->Reserva_model->get($reserva_id)->row();
		if (!$reserva) {
			return;
		}
		
		//cambio estado de reserva
		//'estado_id' => 14,
		$this->Reserva->update($reserva_id,array('fecha_extendida' => $fecha));
		
		//registrar_comentario_reserva($reserva_id,7,'cambio_estado','Cambio de estado. La reserva está a POR ACREDITAR: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | FECHA EXTENDIDA: '.$fecha);

		registrar_comentario_reserva($reserva_id,7,'comentario','Reserva con fecha extendida. | FECHA EXTENDIDA: '.$fecha);
		
		echo 1;
	}
	
	//Confirma una reserva en estado "a confirmar" generando los movimientos correspondientes
	function confirmar_disponibilidad($reserva_id) {
		$reserva = $this->Reserva_model->get($reserva_id)->row();
		if (!$reserva) {
			return;
		}

		//Calcular la fecha de limite de pago minimo
		$fecha_limite_pago_min = new DateTime(date('Y-m-d H:i:s'));
		$fecha_limite_pago_min->add(new DateInterval('PT'.$this->settings->horas_pago_min.'H'));

		//Pongo el estado en NUEVA
		$data = array("estado_id" => 1, "fecha_limite_pago_min" => $fecha_limite_pago_min->format('Y-m-d H:i:s'));
		$this->Reserva->update($reserva_id, $data);

		//genero movimiento en cta cte de USUARIO por monto de reserva
		$this->load->model('Movimiento_model', 'Movimiento');
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();		
		
		$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
		$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;
		
		//globales
		$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
		$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
		$tipo_cambio = $this->settings->cotizacion_dolar;
			
		registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id, date('Y-m-d H:i:s'), $reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
			
		//tambien seteo marcas en el historial para que luego se le envie mail de reserva
		$mail = true;
		$template = 'datos_reserva';
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);
				
		//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();			
		$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
		registrar_movimiento_cta_cte(1,"A",$reserva_id, date('Y-m-d H:i:s'), $reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda='ARS',$tipo_cambio);

		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
		//-------------------------------------------------------------------------------------------------------------------

		redirect(site_url('admin/reservas/edit/'.$reserva_id));
	}
	
	function enviar_vouchers($id){
		$reserva = $this->Reserva->get($id)->row();
		$mail = true;
		$template = 'voucher';
		registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
		echo true;
	}
	
	function borrar_voucher($id){
		$vou = $this->Reserva_voucher->get($id)->row();
		
		if($vou->archivo && file_exists('./uploads/reservas/'.$vou->reserva_id.'/'.$vou->archivo)){
			unlink('./uploads/reservas/'.$vou->reserva_id.'/'.$vou->archivo);
		}
		
		$this->Reserva_voucher->delete($id);	
		echo true;
	}
	
	function grabar_voucher(){
		extract($_POST);
		
		$this->uploadsFolder = './uploads/';
		$this->uploadsMIME = 'doc|docx|gif|jpg|jpeg|png|swf|rar|zip|pdf';

		$ret['status'] = 'error';
		$ret['msg'] = 'No se pudos subir el archivo.<br>Formatos admitidos: '.str_replace('|',', ',$this->uploadsMIME);

		//File uploads
		if (count($_FILES)>0) {
			$this->uploads = array(
								array(
									'name' => 'file_voucher',
									'allowed_types' => $this->uploadsMIME,
									'maxsize' => '10000000',
									'folder' => '/uploads/reservas/'
								)
							);
				
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			//carpeta uploads
			if(!file_exists($this->uploadsFolder))
				mkdir($this->uploadsFolder,0777);
			
			$name_comprobante = false;
			$timestamp = date('Y-m-d H:i:s');
			$strtime = strtotime($timestamp);
			
			foreach ($this->uploads as $upload) {
				$config['allowed_types'] = $upload['allowed_types'];
				$config['max_size']	= $upload['maxsize'];
				$config['upload_path'] = "." . $upload['folder'].$id.'/';
			
				//1er nivel dentro de carpeta uploads
				if(!file_exists(".".$upload['folder']))
					mkdir("." . $upload['folder'],0777);
					
				//1er nivel dentro de carpeta uploads
				if(!file_exists(".".$upload['folder'].'/'.$id))
					mkdir("." . $upload['folder'].'/'.$id,0777);
								
				$this->upload->initialize($config);
			
				if ($this->upload->do_upload($upload['name'])) {
					$data = $this->upload->data();
					//$data['file_name']			
					
					$upd = array();
					$upd['reserva_id'] = $id;
					$upd['archivo'] = $data['file_name'];
					$upd['timestamp'] = date('Y-m-d H:i:s');
					$upd['ip'] = $_SERVER['REMOTE_ADDR'];
					$v_id = $this->Reserva_voucher->insert($upd);
					
					$vou = $this->Reserva_voucher->get($v_id)->row();
					
					$ret['status'] = 'success';
					$ret['row'] = vouchers_row($vou);
				}
				else{
					$ret['msg'] = 'No se pudos subir el archivo.<br>Formatos admitidos: '.str_replace('|',', ',$this->uploadsMIME);
				}
			}
		
		}
					
		echo json_encode($ret);
	}
	
	/* historial de reservas */
	function cargar_historial($reserva_id){
		//datos de la reserva
		$reserva = $this->Reserva->get($reserva_id)->row();
		$this->data['reserva'] = $reserva;
		
		//cargo comentarios de la reserva
		//le saco los registros que tengan buenas vibras en comentario para que no figuren en el historial de la reserva del usuario
		$this->Comentario->filters = "comentarios not like '%buenas vibras%' and reserva_id = ".$reserva_id." and tipo != 'registro_costo_operador'";
		
		//si filtra por tipo_id -> aplico en busqueda
		if(isset($_POST['tipo_id']) && $_POST['tipo_id'] != ''){
			$this->Comentario->filters .= " and tipo_id = '".$_POST['tipo_id']."'";
		}
		
		$historial = $this->Comentario->getAll(9999,0,'fecha','desc','');		
		//echo $this->db->last_query();
		$this->data['historial'] = $historial;		
		
		$this->data['tipos_acciones'] = $this->Comentario_tipo->getAll(99,0,'nombre','asc')->result();
		
		$this->Comentario_tipo->filters = "editable = 1";
		$this->data['tipos_acciones_editables'] = $this->Comentario_tipo->getAll(99,0,'nombre','asc')->result();
		
		$this->data['historial_reserva'] = $this->load->view('admin/reservas_historial',$this->data,true);
	}
	
	//ok
	function grabar_historial(){
		extract($_POST);
		
		$reserva_id = $id;
		$tipo_id = $tipo_accion_id;
		
		$reserva = $this->Reserva->get($reserva_id)->row();
		
		$fecha = date("Y-m-d H:i:s");
		
		$data = array(
					"reserva_id" => $reserva_id,
					"admin_id" => $this->session->userdata('admin_id'),
					"fecha" => $fecha,
					"tipo_id" => $tipo_id,
					"comentarios" => $comentarios
			);			
		$this->Comentario->insert($data);
		
		$ret = array();
		$ret['status'] = 'success';
		$ret['redirect'] = base_url().'admin/reservas/edit/'.$reserva_id.'?tab=historial&nuevoregistro=1';
		echo json_encode($ret);
	}	
	
	/*
	Devuelve las combinaciones de paquetes disponibles para la reserva
	*/
	function combinaciones_disponibles($id,$reserva_id){
		$this->data['reserva'] = $this->Reserva->get($reserva_id)->row();
		$this->data['paquete'] = $this->Paquete->get($id)->row();
		
		//filtros los q tengan la misma cantidad de pasajeros q no esten agotadas (disponibles = 1)
		$filtros = array();
		$filtros['pax'] = $this->data['reserva']->pasajeros;
		$filtros['disponibles'] = '1';
		$this->data['combinaciones'] = $this->Combinacion->getByPaquete($id,9999,$filtros);

		$ret['view'] = $this->load->view('admin/paquetes-cambiar',$this->data,true);
		echo json_encode($ret);
	}
	
	/*
	Determina si es posible hacer el cambio de combinacion para la reserva, chequeando cupos
	*/
	function elegirCombinacion(){
		extract($_POST);
		
		if($reserva_id > 0 && $combinacion_id > 0){
		
			//datos de reserva actual
			$reserva = $this->model->get($reserva_id)->row();
			$combinacion = $this->Combinacion->get($combinacion_id)->row();
			$paquete = $this->Paquete->get($combinacion->paquete_id)->row();
				
			$upd = array();
			$upd['combinacion_id'] = $combinacion->id;
			$upd['fecha_alojamiento_id'] = $combinacion->fecha_alojamiento_id;
			$upd['lugar_id'] = $combinacion->lugar_id;
			$upd['alojamiento_id'] = $combinacion->alojamiento_id;
			$upd['habitacion_id'] = $combinacion->habitacion_id;
			$upd['paquete_regimen_id'] = $combinacion->paquete_regimen_id;
			$nueva_reserva_id = $reserva->id;
			$this->Reserva->update($nueva_reserva_id,$upd);
			
			$ret['status'] = 'success';
			//$ret['msg'] = 'El cambio de paquete se ha efectuado correctamente<br>Por favor revisa la <a target="_blank" href="'.base_url().'admin/reservas/paquete/'.$paquete->id.'">nueva reserva</a> generada, junto con la <a href="'.base_url().'admin/reservas/edit/'.$nueva_reserva_id.'?tab=cta_cte" target="_blank">cuenta corriente</a> del pasajero.';
			$ret['msg'] = 'El cambio de paquete se ha efectuado correctamente.';

			//si corresponde un ajuste en la cuenta corriente, registrarlo
			$ajuste_precio = $combinacion->v_total - ($reserva->paquete_precio+$reserva->impuestos);
			if($ajuste_precio > 0){
				//data de reserva
				$r = $this->Reserva->get($nueva_reserva_id)->row();

				//genero registro en el historial para que luego se le envie mail de ajuste de precio
				$mail = true;
				$template = 'ajuste_precio';
				registrar_comentario_reserva($r->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo,$mail,$template);
			
				//genero movimiento en cta cte de USUARIO por diferencia en el adicional
				$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($r->usuario_id,"U",$r->precio_usd)->row();		
				
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $ajuste_precio;
				
				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $r->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $this->settings->cotizacion_dolar;
					
				registrar_movimiento_cta_cte($r->usuario_id,"U",$r->id, date('Y-m-d H:i:s'), 'AJUSTE DE PRECIO - '.$r->nombre." - ".$r->paquete_codigo,$ajuste_precio,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
					
				//registro en historial el movimiento por ajuste de precio
				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario por AJUSTE DE PRECIO. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | DEBE '.$ajuste_precio);
						
				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$r->precio_usd)->row();			
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $ajuste_precio;
				registrar_movimiento_cta_cte(1,"A",$r->id, date('Y-m-d H:i:s'), $r->nombre." - ".$r->paquete_codigo,$ajuste_precio,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS por AJUSTE DE PRECIO. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | DEBE '.$ajuste_precio);
				//-------------------------------------------------------------------------------------------------------------------

			}

			echo json_encode($ret);
		}
		else{
			$ret['status'] = 'error';
			$ret['msg'] = 'Hubo un problema. Intente mas tarde.';
			
			echo json_encode($ret);
			exit();
		}
		
	}
	
	/*
	Borra el informe de pago, x si se generó duplicado
	*/
	function borrar_informe_pago($id){
		$vou = $this->Reserva_informe_pago->get($id)->row();
		
		if($vou->archivo && file_exists('./uploads/informes_pago/'.$vou->comprobante)){
			unlink('./uploads/informes_pago/'.$vou->comprobante);
		}
		
		$this->Reserva_informe_pago->delete($id);	
		echo true;
	}
	
	/*
	elimina el adicional de la reserva
	*/
	function eliminar_adicional($reserva_id,$paquete_adicional_id,$return=false){
		if($reserva_id && $paquete_adicional_id){
			//obtengo data del adicional
			$adicional = $this->Adicional->getAsociacionPaquete($paquete_adicional_id);
			
			//data de reserva
			$reserva = $this->Reserva->get($reserva_id)->row();
			
			$valor_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
			$valor_adicional = $valor_adicional*$reserva->pasajeros;
			
			//obtengo data del adicional/reserva
			$reserva_adicional = $this->Reserva->getAdicional($reserva_id,$paquete_adicional_id);

			//con este ID de reserva_adicional, lo busco en MOVIMIENTOS para obtener cual es el MOV ID asociado
			$find = array('cta_usd'=>($reserva->precio_usd?1:0),'reserva_adicional_id'=>$reserva_adicional->id,'usuario_id'=>$reserva->usuario_id,'tipoUsuario' => 'U','reserva_id' => $reserva_id);
			$mov_asociado = $this->Movimiento->getMovAsociado($find)->row();
			$mov_asociado_id = (isset($mov_asociado->id) && $mov_asociado->id) ? $mov_asociado->id : 0;

			//elimino el adicional
			$this->Reserva->eliminarAdicional($reserva_id,$paquete_adicional_id);
			
			$fecha_reserva = date('Y-m-d H:i:s');
			
			//registro movimiento en cta cte del usuario por el importe del adicional
			$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();		
			$nuevo_parcial = ( isset($mov->parcial) ? $mov->parcial : 0.00 ) - $valor_adicional;
			
			/*
			//12-11-15 si el nuevo parcial es <0 por la quita del adicional, entonces le genero el mov de debe para que quede saldada la cta
			if($nuevo_parcial < 0){
				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$paquete->titulo." - ".$paquete->codigo,$valor_adicional,0.00,$nuevo_parcial,'','');
				registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$paquete->titulo." - ".$paquete->codigo.' | DEBE '.$valor_adicional);
				
				//actualizo el nuevo parcial
				$nuevo_parcial += $valor_adicional;
			}
			*/
			
			$factura_id='';
			$talonario='';
			$factura_asociada_id='';
			$talonario_asociado='';
			$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
			$tipo_cambio = $this->settings->cotizacion_dolar;
			$movimiento_id = registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado',0.00,$valor_adicional,$nuevo_parcial,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,false,false,false,$reserva_adicional->id,$mov_asociado_id);
			registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado | HABER '.$valor_adicional);
			//-----------------------------------------------------------------------------------------------------------------
			
			if($mov_asociado_id){
				//al movimiento padre le pongo como asociado el actual de la eliminacion de adicional
				$this->Movimiento->update($mov_asociado_id,array('mov_asoc_id'=>$movimiento_id));
			}
			
			//idem genero movimiento en cta cte de BBV 
			$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();		
			$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) - $valor_adicional;
			
			registrar_movimiento_cta_cte(1,"A",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado',0.00,$valor_adicional,$nuevo_parcial2,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio);
			registrar_comentario_reserva($reserva->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado | HABER '.$valor_adicional);
			//-----------------------------------------------------------------------------------------------------------------
			
				$mail = true;
				$template = 'ajuste_precio';
				registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
			
			
			//envio de mail avisando el BORRADO del adicional?
			//$asunto = "Adicional ".$_POST['adicional'.$j]." eliminado de reserva - ".$paquete->titulo." - ".$paquete->subtitulo;
			//$this->data['asiento_cama_msg'] = "Tu reserva ha registrado modificaciones debido a la eliminación del adicional ".$_POST['adicional'.$j].".<br>Hemos quitado de tu cuenta el importe del adicional correspondiente de $ ".$valor_adicional.".";
					
			$ret['status'] = 'SUCCESS';
			$ret['redirect'] = site_url('admin/reservas/edit/'.$reserva->id.'?tab=adicionales');
			$ret['msg'] = 'Se ha eliminado el adicional correctamente';
			if($return){
				return $ret;
			}
			else{
				echo json_encode($ret);
			}
		}
		else{
			$ret['status'] = 'ERROR';
			$ret['msg'] = 'No se pudo eliminado el adicional.';
			if($return){
				return $ret;
			}
			else{
				echo json_encode($ret);
			}
		}
	}
	
	/*
	agrega el adicional a la reserva
	*/
	function agregar_adicional($reserva_id,$paquete_adicional_id){
		if($reserva_id && $paquete_adicional_id){			
			//obtengo data del adicional
			$adicional = $this->Adicional->getAsociacionPaquete($paquete_adicional_id);
			
			//data de reserva
			$reserva = $this->Reserva->get($reserva_id)->row();
				
			//si el adicional tiene cupo, tengo que chequear que haya disponibilidad para la cantidad de pasajeros de la reserva
			//si la cantidad es 0 es porque es un adicional que NO CONSIDERA EL CUPO (ej seguro viaje)
			if( ($adicional->cantidad > 0 && $adicional->usados < $adicional->cantidad && 
				($adicional->usados+$reserva->pasajeros <= $adicional->cantidad))
				|| $adicional->cantidad == 0 ){
					
				//el adicional es para todos los pasajeros
				$valor_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
				$valor_adicional = $valor_adicional*$reserva->pasajeros;
				
				//agrego el adicional, devuelvo el ID de RESERVA/ADICIONAL para asociarselo al movimiento
				$reserva_adicional_id = $this->Reserva->agregarAdicional($reserva_id,$paquete_adicional_id,$valor_adicional);
				
				$fecha_reserva = date('Y-m-d H:i:s');
				
				//registrar en cta cte los movimientos
				//registro movimiento en cta cte del usuario por el importe del adicional
				$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();		
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : 0.00 ) + $valor_adicional;
				
				
				$factura_id='';
				$talonario='';
				$factura_asociada_id='';
				$talonario_asociado='';
				$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $this->settings->cotizacion_dolar;
				$movimiento_id = registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado',$valor_adicional,0.00,$nuevo_parcial,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,false,false,false,$reserva_adicional_id);
				registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado | DEBE '.$valor_adicional);
				//-----------------------------------------------------------------------------------------------------------------
				
				//idem genero movimiento en cta cte de BBV 
				$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();		
				$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $valor_adicional;
								
				registrar_movimiento_cta_cte(1,"A",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado',$valor_adicional,0.00,$nuevo_parcial2,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio);
				registrar_comentario_reserva($reserva->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado | DEBE '.$valor_adicional);
				//-----------------------------------------------------------------------------------------------------------------
				
				$mail = true;
				$template = 'ajuste_precio';
				registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
			

				//envio de mail avisando el agregado del adicional?
				//$asunto = "Adicional ".$_POST['adicional'.$j]." agregado a la reserva - ".$paquete->titulo." - ".$paquete->subtitulo;
				//$this->data['asiento_cama_msg'] = "Tu reserva ha registrado modificaciones debido al agregado del Adicional ".$_POST['adicional'.$j].".<br>El importe del adicional correspondiente es de $ ".$_POST['adicional'.$j.'Valor'].", el cual se adicionó a tu cuenta.";
							
				$ret['status'] = 'SUCCESS';
				$ret['redirect'] = site_url('admin/reservas/edit/'.$reserva->id.'?tab=adicionales');
				$ret['msg'] = 'Se ha agregado el adicional <b>'.$adicional->adicional.'</b> correctamente';
				echo json_encode($ret);
			}
			else{
				$ret['status'] = 'ERROR';
				$ret['msg'] = 'No hay disponibilidad de <b>'.$adicional->adicional.'</b>.<br><br>Cantidad total del adicional: '.$adicional->cantidad.'<br>Cantidad disponible: '.($adicional->cantidad-$adicional->usados).'<br>Cantidad solicitada: '.($reserva->pasajeros);
				echo json_encode($ret);				
			}
			
		}
		else{
			$ret['status'] = 'ERROR';
			$ret['msg'] = 'No se pudo agregar el adicional.';
			echo json_encode($ret);
		}
	}

	function ver_datos_comentario($h_id){
		$this->data['row'] = $this->Comentario->get($h_id)->row();
		$this->load->view('admin/reservas_historial_datos',$this->data);
	}
	
	function probarmaxi(){
		$reserva_row = $this->Reserva->get(11)->row();

		$existe_costo = $this->Movimiento->getWhere(
											array(
													'tipoUsuario'=>'A',
													'usuario_id'=>$reserva_row->operador_id,
													'reserva_id'=>$reserva_row->id
												 )
										 )->result();
		pre($reserva_row);
		pre($existe_costo);

	}

	function rooming($id){
		$this->data['page_title'] = 'Rooming del viaje';
		$this->init();
		$this->data['paquete'] = $this->Paquete->get($id)->row();

		$rooming = $this->Paquete_rooming->getRooming($id);

		//si no tiene rooming creado, se lo genero
		if(count($rooming) == 0){
			$habs = $this->Habitacion->getByPaquete($id);
			//echo $this->db->last_query();

			foreach ($habs as $r){
				for($i=1;$i<=$r->cantidad;$i++){
					$room = array();
					$room['paquete_id'] = $id;
					$room['nro_habitacion'] = '';
					$room['alojamiento_fecha_cupo_id'] = $r->id;
					$room['observaciones'] = '';

					//guardo rooming
					$this->Paquete_rooming->insert($room);
				}
			}

			$rooming = $this->Paquete_rooming->getRooming($id);
			//echo $this->db->last_query();
		}
		else{
			//como ya hay rooming creado para el paquete
			//se lo genero nuevamenta por si hubo cambio en cupos y le mantengo la data de rooming previamente creada
			
		}

		$this->data['mi_rooming'] = $rooming;

		//obtengo las reservas del paquete para luego intentar asignarla a la habitacion que corresponda
		//$data = $this->model->getAllByPaquete($id,'',$this->data['sort'],$this->data['sortType'],$admin_id,'')->result();
		
		//pre($this->data['mi_rooming']);
		//pre($data);

		$this->load->view('admin/reservas_rooming', $this->data);
	}

	function save_rooming(){
		extract($_POST);

		//habitacion y observaciones
		$this->Paquete_rooming->clearPaquete($paquete_id);
		foreach ($habitacion as $id => $valor) {
			$room = array();
			$room['paquete_id'] = $paquete_id;
			$room['nro_habitacion'] = @$valor[0];
			$room['alojamiento_fecha_cupo_id'] = @$alojamiento_fecha_cupo_id[$id][0];
			$room['nro_habitacion'] = @$valor[0];
			$room['observaciones'] = @$observaciones[$id][0];

			//guardo rooming
			$this->Paquete_rooming->insert($room);
		}

		$ret = array('status' => 'OK');
		echo json_encode($ret);
	}

	function download_rooming($id){
		$this->data['paquete'] = $this->Paquete->get($id)->row();
		$this->data['rooming'] = $this->Paquete_rooming->getRooming($id);


		//descarga
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=rooming_".$this->data['paquete']->codigo.".xls");
		$this->load->view('admin/reservas_rooming_xls',$this->data);
	}

	function get_iconos(){
		extract($_POST);
		$ids = isset($ids) && $ids ? $ids : []; 

		$data_primeros = [];
		$data_restantes = [];

		foreach($ids as $k=>$id){			
			$row = $this->model->get($id)->row();

			$row->tipo_id = $row->usuario_id;
			$row->tipo = 'U';			
			
			$row->fecha_original = $row->fecha.' hs';
			$fecha_hora = $row->fecha;
			$fecha_hora = explode(" ",$fecha_hora);
			$row->hora = substr($fecha_hora[1],0,5);
			$row->fecha = date('d/m/Y',strtotime($row->fecha));
			
			//alarmas de cada reserva
			$row->alarmas = $this->cargar_alarmas($row);

			//las reservas q no tienen alarmas no las considero
			//if(){
			
			//debug, mi ip que muestre todas, incluso las q no tienen alarmas
			if($row->tiene_alarmas){

				//si no hay alarma elegida ó si la alarma elegida la posee la reserva en cuestion
				if($this->data['alarma_id'] == '' or (isset($row->alarmas->{$this->data['alarma_id']}) && $row->alarmas->{$this->data['alarma_id']}>0)){

					//ordeno primero las que tienen estado POR ACREDITAR
					if($row->estado_id == 14){
						$data_primeros[] = $row;
					}
					else{
						$data_restantes[] = $row;	
					}
				}
			}
		}

		$rows = array_merge($data_primeros,$data_restantes);

		foreach ($rows as $r) {
			$data = [];
			$data['id'] = $r->id;
			$data['row'] = $r;
			$r->html_alarmas = $this->load->view('admin/row_alarmas',$data,true);
		}

		$ret = [];
		$ret['rows'] = $rows;

		echo json_encode($ret);
	}
	
	function verp(){
		echo $this->session->userdata('perfil');
	}
	
}
