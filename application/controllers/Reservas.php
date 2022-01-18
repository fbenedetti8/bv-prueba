<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservas extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Pasajero_model','Pasajero');
		$this->load->model('Usuario_model','Usuario');
		$this->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$this->load->model('Reserva_facturacion_model','Reserva_facturacion');
		$this->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$this->load->model('Reserva_voucher_model','Reserva_voucher');
		$this->data['body_id'] = 'checkout';
		$this->data['body_class'] = 'checkout';		
		
			$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
			$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
			$this->carabiner->css('../fancybox/jquery.fancybox.min.css');
			$this->carabiner->css('../selectric/selectric.css');
			$this->carabiner->css('estilos_old.css');
			$this->carabiner->css('app.css');

			$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
			$this->carabiner->js('../selectric/selectric.js');
			$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
			$this->carabiner->js('../fancybox/jquery.fancybox.min.js');
			$this->carabiner->js('jquery.timeMask.js');
			$this->carabiner->js('jquery.mask.js');
			$this->carabiner->js('../bootstrap/js/bootstrap.min.js');
			$this->carabiner->js('../bootstrap/js/bootstrap-datepicker.js');
			$this->carabiner->js('../bootstrap/js/bootstrap-datepicker.es.js');
			$this->carabiner->js('functions.js');
			$this->carabiner->js('main.js');
			$this->carabiner->js('../../assets_old/js/jquery.scrollTo.min.js');

	}

	public function index(){
	}
	
	public function resumen($hash){
		/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo $hash;
		}*/

		$this->data['hash'] = $hash;
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo "<br>";
			pre($data);
			//exit();
		}*/

		$this->data['success'] = $this->input->get('success');

		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			
			if($reserva->id){
				$reserva = $this->Reserva->get($reserva->id)->row();
				$reserva->adicionales = adicionales_reserva($reserva);
			
				$this->cargar_data_reserva($reserva);
				
				//reserva valida
				$this->render('resumen_viaje');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
			
	//aca clickeó en boton de Mercado Pago, pero sin saber si pagó o no
	function validar_pago(){
		extract($_POST);
		
		$reserva = $this->Reserva->get($reserva_id)->row();
		$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();		
		$reserva->adicionales = $this->Reserva->getAdicionales($reserva->id);
		$adicionales_valores = array();
		foreach($reserva->adicionales as $a){
			$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
		}
		
		$moneda = $combinacion->precio_usd ? 'USD' : 'ARS';
		
		//$precios_totales = calcular_precios_totales($combinacion,$adicionales_valores,false,$reserva_id);
		$precios = montos_numericos($combinacion,$adicionales_valores,$moneda,$reserva_id);				

		$user_mediopago = isset($user_mediopago) && $user_mediopago ? $user_mediopago : 'MP';
		//aca tengo que recalcular el precio del viaje segun el medio de pago que haya elegido y la moneda del viaje
		$precios_pagar = recalcular_precios($precios,$moneda,$user_mediopago);

		#pre($precios);
		#pre($precios_pagar);

		$ret['hash'] = $hash;
		$ret['reserva_id'] = $reserva->id;
		
		#$saldo_pendiente = $precios['num']['saldo_pendiente'];
		$saldo_pendiente = $precios['saldo_pendiente'];

		//pago total
		if( $pago == "total" ){
			$monto = $precios['precio_total'];
			
			if($monto == "" || $monto <= 0){
				$ret['msg'] = "El monto deber ser mayor que 0 (cero).";
				$ret['status'] = "error";
				echo json_encode($ret);
			}
			else{
				
				if($saldo_pendiente <= 0){
					$ret['msg'] = "El monto deber ser mayor que 0 (cero).";
					$ret['status'] = "error";
					echo json_encode($ret);
				}
				else{
					$monto = $saldo_pendiente;

					//si la moneda del viaje es ARS y va a pagar PAYAPL, lo convierto
					/*if($user_mediopago == 'PP' && $moneda == 'ARS'){
						$monto = number_format($monto/$this->settings->cotizacion_dolar,2,'.','');
					}
					if($user_mediopago == 'MP' && $moneda == 'USD'){
						$monto = number_format($monto*$this->settings->cotizacion_dolar,2,'.','');
					}*/

					$ret['monto'] = $monto;
					$ret['status'] = "success";
					
					//todo ok con pago total
					// 25-02-19 user_mediopago puede ser MP ó PP
					if($user_mediopago == 'MP'){
						$this->getLinkMercadoPago($ret);
					}
					else{
						$this->getLinkPaypal($ret);
					}
				}
			}
		}
		else{
			#$monto = $precios['precio_total'];
			//monto viene por POST
			$minimo = $precios['monto_minimo_reserva'];
			$monto_abonado = $precios['monto_abonado'];

			//pago parcial
			if($monto == "" || $monto <= 0){
				$ret['msg'] = "El monto deber ser mayor que 0 (cero).";
				$ret['status'] = "error";
				echo json_encode($ret);
			}
			else if($monto < $minimo && $monto_abonado == 0){
				//si no tiene ningun pago todavia, valido el monto minimo a pagar
				$ret['msg'] = "El monto mínimo de pago es ".$minimo;
				$ret['status'] = "error";
				echo json_encode($ret);
			}
			else{
				#$moneda = 'ARS';
				
				
				//no puede querer pagar mas que lo que le queda
				if( $monto > $saldo_pendiente ){
					$ret['msg'] = "El saldo pendiente del viaje es de ".$saldo_pendiente;
					$ret['status'] = "error";
					echo json_encode($ret);
				}
				else{
					//si la moneda del viaje es ARS y va a pagar PAYAPL, lo convierto
					/*if($user_mediopago == 'PP' && $moneda == 'ARS'){
						$monto = number_format($monto/$this->settings->cotizacion_dolar,2,'.','');
					}
					if($user_mediopago == 'MP' && $moneda == 'USD'){
						$monto = number_format($monto*$this->settings->cotizacion_dolar,2,'.','');
					}*/

					$ret['monto'] = $monto;
					$ret['status'] = "success";
					
					// 25-02-19 user_mediopago puede ser MP ó PP
					if($user_mediopago == 'MP'){
						$this->getLinkMercadoPago($ret);
					}
					else{
						$this->getLinkPaypal($ret);
					}
				}
			}
		}	
		
	}
	
	//obtengo link a mercado pago con la data necesaria
	function getLinkMercadoPago($data){
		if($data['status'] == 'success'){			
			$data['redirect'] = get_link_mp($data);
			
			echo json_encode($data);
		}
		else{
			echo json_encode($data);
		}		
	}
	
	//obtengo link a paypal con la data necesaria
	function getLinkPaypal($data){
		if($data['status'] == 'success'){			
			$data['redirect'] = get_link_pp($data);
			
			echo json_encode($data);
		}
		else{
			echo json_encode($data);
		}		
	}
	
	function registrar_transaccion_MP($hash=false,$reserva_id=false,$monto=false){
		if($hash && $reserva_id && $monto && isset($_GET['collection_id'])){
			
			$_POST['collection_id'] = $_GET['collection_id'];
			$_POST['reserva_id'] = $reserva_id;
			$_POST['monto'] = $monto;
			$_POST['hash'] = $hash;

			$data['redirect'] = registrar_transaccion_mp($_POST);
			redirect($data['redirect']);
		}
		else{
			if(isset($_POST['collection_id']) && $_POST['collection_id'] && isset($_POST['reserva_id']) && $_POST['reserva_id']){		
				
				//obtengo url redrieccion a pagina final con mensaje
				$data['redirect'] = registrar_transaccion_mp($_POST);
				echo json_encode($data);
			}
		}
	}
	
	//carga pagina con mensaje final de pago recientemente realizado con MP
	public function pago_realizado($hash){
		$this->data['hash'] = $hash;
		
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva = $this->Reserva->get($reserva->id)->row();
			$reserva->adicionales = adicionales_reserva($reserva);
			
			if($reserva->id){
				$this->data['reserva'] = $reserva;
				$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
				
				$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS');
				
				//reserva valida
				$html = $this->render('checkout_pago_realizado', TRUE);
				$this->session->set_userdata('contenido', $html);
				redirect(site_url('reservas/gracias'));
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}

	//carga pagina con mensaje final de reserva completa con paga despues
	public function confirmacion($hash){
		$this->data['hash'] = $hash;
		
		/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo $hash;
		}*/

		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo "<br>";
			pre($data);
		}*/

		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva->adicionales = adicionales_reserva($reserva);
			
			if($reserva->id){
				$reserva = $this->Reserva->get($reserva->id)->row();
				$reserva->adicionales = adicionales_reserva($reserva);
				$this->data['reserva'] = $reserva;
				
				$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
				
				$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

				//para las reservas grupales me fijo que cantidad de pasajeros son, para cambiar el mensaje en la pagina final
				if($reserva->grupal && $reserva->codigo_grupo){
					$gr = $this->Reserva->getReservasActivasGrupo($reserva->codigo_grupo,true);
						
					if($gr->cantidad > 1){
						$this->data['pasajeros_grupo'] = $gr->cantidad;
					}
				}

				//reserva valida
				$html = $this->render('checkout_pago_despues', TRUE);
				$this->session->set_userdata('contenido', $html);
				redirect(site_url('reservas/gracias'));
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	function gracias() {
		$html = $this->session->userdata('contenido');
		echo $html;
	}

	/* clickeó en INFORMAR TRANSFERENCIA desde el RESUMEN DEL VIAJE */
	function informar_transferencia($hash){
		$this->data['hash'] = $hash;
		
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			
			if($reserva->id){
				$this->data['reserva'] = $reserva;
				
				$this->data['hash_reserva'] = encriptar($reserva->code);
			
				$this->render('informar_transferencia');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	/*
	Graba informe de pago sobre la RESERVA 
	*/
	function grabar_informe(){
		extract($_POST);
		
		ini_set('max_execution_time','360');

		$this->form_validation->set_rules('banco', 'Banco', 'required');
		$this->form_validation->set_rules('tipo_pago', 'Tipo de pago', 'required');
		$this->form_validation->set_rules('fecha_pago', 'Fecha de pago', 'required');
		$this->form_validation->set_rules('hora_pago', 'Hora de pago', 'required');
		$this->form_validation->set_rules('monto_pago', 'Monto de pago', 'required');
		$this->form_validation->set_rules('tipo_moneda', 'Tipo de moneda', 'required');
		
		if ($this->form_validation->run() == FALSE){
			$ret['status'] = 'error';
			$ret['fields'] = array_keys($this->form_validation->error_array());
		}
		else if(!isset($_FILES['comprobante']) || !$_FILES['comprobante'] || $_FILES['comprobante']['name'] == ''){
			$ret['status'] = 'error';
			$ret['fields'] = array('comprobante');
		}
		else{
			//DESENCRIPTO HASH DE CODIGO DE RESERVA
			$data = desencriptar($hash);
		
			if(is_array($data) && count($data) == 2){
				$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
						
				if($reserva->id){
					//File uploads
					if (count($_FILES)>0) {
						$this->uploadsFolder = './uploads/';
						$this->uploadsMIME = 'jpg|jpeg|png|pdf';
						$this->uploads = array(
											array(
												'name' => 'comprobante',
												'prefix' => 'comprobante_',
												'keep' => true,
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
							$config['upload_path'] = "." . $upload['folder'].$reserva->id.'/';
						
							//1er nivel dentro de carpeta uploads
							if(!file_exists(".".$upload['folder']))
								mkdir("." . $upload['folder'],0777);
								
							//1er nivel dentro de carpeta uploads
							if(!file_exists(".".$upload['folder'].'/'.$reserva->id))
								mkdir("." . $upload['folder'].'/'.$reserva->id,0777);
											
							$this->upload->initialize($config);
						
							if ($this->upload->do_upload($upload['name'])) {
								$data = $this->upload->data();
								
								//Keep original?
								if ($upload['keep']) {
									$name_comprobante = $upload['prefix'] . $strtime . $data['file_ext'];						
									rename($config['upload_path'] . $data['file_name'], $config['upload_path'] . $name_comprobante);
								}
								else{
									unlink($config['upload_path'] . $data['file_name']);
								}								
							}
							else{
								$ret['status'] = 'error';
								$ret['msg'] = 'No se pudo guardar el comprobante. Verifica el tamaño y formato del archivo.';
								echo json_encode($ret);
								exit();
							}
						}
					
					}
				
					$fecha_pago = explode('/',$fecha_pago);
					$fecha_pago = $fecha_pago[2].'-'.$fecha_pago[1].'-'.$fecha_pago[0];
					
					$monto_pago = str_replace(array('.',','),array('','.'),$monto_pago);

					//guardo los datos del paso 1
					$pax = array();
					$pax['reserva_id'] = $reserva->id;
					$pax['banco'] = $banco;
					$pax['tipo_pago'] = $tipo_pago;
					$pax['fecha_pago'] = $fecha_pago;
					$pax['hora_pago'] = $hora_pago;
					$pax['monto_pago'] = $monto_pago;
					$pax['tipo_moneda'] = $tipo_moneda;
					$pax['comprobante'] = $name_comprobante;
					$pax['timestamp'] = $timestamp;
					$pax['ip'] = $_SERVER['REMOTE_ADDR'];
					
					$informe_id = $this->Reserva_informe_pago->insert($pax);
					
					//actualizo el estado de la reserva a X ACREDITAR , si es que no esta anulada
					//27-09-18 si es estado NUEVA o POR VENCER, le cambio el estado a POR ACREDITAR
					//cualquier otro estado, la deja como está
					if( in_array($reserva->estado_id,array(1,2) ) ){
						$this->Reserva->update($reserva->id,array('estado_id' => 14));
					}

					//genero registro en historial por nuevo informe de pago generado
					$mail = true;
					$template = 'informe_pago_recibido';
					registrar_comentario_reserva($reserva->id,7,'informe_pago_recibido','Se recibió un informe de pago por parte del usuario',$mail,$template,$informe_id);
					
					$ret['status'] = 'success';			
				}
				else{
					$ret['status'] = 'error';
					$ret['msg'] = 'No se pudo registrar la operación';
				}
			}
			else{
				$ret['status'] = 'error';
				$ret['msg'] = 'No se pudo registrar la operación';
			}
		}
		
		echo json_encode($ret);
	}
	
	/*
	Este metodo valida los datos de los pasajeros
	*/
	function validar_pasajeros(){
		extract($_POST);
		
		//obtengo el ID de pasajero que voy a actualizar
		$pax_id = isset($grabar_pax) && $grabar_pax ? $grabar_pax : 0;
		
		if($pax_id > 0){		
			$this->form_validation->set_rules('nombre_'.$pax_id, 'Nombre', 'required');
			$this->form_validation->set_rules('apellido_'.$pax_id, 'Apellido', 'required');
			
			$this->form_validation->set_rules('nacimiento_dia_'.$pax_id, 'Dia de nacimiento', 'required');
			$this->form_validation->set_rules('nacimiento_mes_'.$pax_id, 'Mes de nacimiento', 'required');
			$this->form_validation->set_rules('nacimiento_ano_'.$pax_id, 'Año de nacimiento', 'required');
			
			//$this->form_validation->set_rules('nacimiento_'.$pax_id, 'Fecha de nacimiento', 'required');
			$this->form_validation->set_rules('sexo_'.$pax_id, 'Sexo', 'required');
			if(isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1){
				//solo dni para argentina
				$this->form_validation->set_rules('dni_'.$pax_id, 'DNI', 'required');
			}
			$this->form_validation->set_rules('nacionalidad_id_'.$pax_id, 'Nacionalidad', 'required');
			$this->form_validation->set_rules('email_'.$pax_id, 'Email', 'required|valid_email');
			$this->form_validation->set_rules('celular_codigo_'.$pax_id, 'Celular', 'required');
			$this->form_validation->set_rules('celular_numero_'.$pax_id, 'Celular', 'required');
			$this->form_validation->set_rules('emergencia_nombre_'.$pax_id, 'Nombre', 'required');
			$this->form_validation->set_rules('emergencia_telefono_codigo_'.$pax_id, 'Teléfono', 'required');
			$this->form_validation->set_rules('emergencia_telefono_numero_'.$pax_id, 'Teléfono', 'required');
			$this->form_validation->set_rules('dieta_'.$pax_id, 'Dieta', 'required');
			//$this->form_validation->set_rules('direccion_'.$pax_id, 'Dirección/Ciudad/Provincia', 'required');
			
			//me fijo si el paquete requiere pasaporte obligatorio por backend
			//2 es el id de pasaporte
			$reserva = $this->Reserva->get($reserva_id)->row();
			$documentaciones = $this->Paquete->getDocumentaciones($reserva->paquete_id);
			$pasaporte_obligatorio = in_array(2,$documentaciones);
			
			//si es nacionalidad extranjero => pido pasaporte obligatorio
			//si es nacionalidad argentina y pasaporte obligatorio por backend => pido pasaporte obligatorio
			//otro caso => NO pido pasaporte			
			$condicion_pasaporte = (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] > 1)
				|| (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1 && $pasaporte_obligatorio);
			if( $condicion_pasaporte ){
				//si la nacionalidad elegida es Argentina y tiene pasaporte obligatorio por back => campo obligatorio
				//si la nacionalidad es otra que no sea argentina => campo obligatorio
			
				$this->form_validation->set_rules('pasaporte_'.$pax_id, 'Pasaporte', 'required');
				$this->form_validation->set_rules('pais_emision_id_'.$pax_id, 'País de emisión', 'required');
				$this->form_validation->set_rules('fecha_emision_'.$pax_id, 'Fecha de emisión del pasaporte', 'required');
				$this->form_validation->set_rules('fecha_vencimiento_'.$pax_id, 'Fecha de vencimiento del pasaporte', 'required');
			}
		
			if ($this->form_validation->run() == FALSE){
				$valida = false;
				
				$ret['status'] = 'error';
				$ret['fields'] = array_keys($this->form_validation->error_array());
			}
			else{	
				$str_fecha = $_POST['nacimiento_ano_'.$pax_id].'-'.$_POST['nacimiento_mes_'.$pax_id].'-'.$_POST['nacimiento_dia_'.$pax_id];
				
				//valido fecha nacimiento
				if(!validateDate($str_fecha)){
					$valida = false;
					//retorno fecha invalida
					$ret['status'] = 'error';
					$ret['invalid_nacimiento'] = true;
					$ret['msg'] = 'La fecha de nacimiento no es correcta';
					$ret['fields'] = array('nacimiento_ano_'.$pax_id);
				}
				else{
					
					if(!isValidEmail($_POST['email_'.$pax_id])){
						$valida = false;
						//retorno email invalida
						$ret['status'] = 'error';
						$ret['pax_invalid_email'] = true;
						$ret['msg'] = 'El formato del email no es válido.';
						$ret['fields'] = array('email_'.$pax_id);
					}
					else{
						//si es un ACOMPAÑANTE, chequeo que no haya cargado el mismo DNI, EMAIL y CUIT que el RESPONSABLE o los demas acompañantes
						$paxajero = $this->Reserva_pasajero->getWhere(array('reserva_id'=>$reserva_id,'pasajero_id'=>$pax_id))->row();
						if(!$paxajero->responsable){
							$existe = $this->Reserva_pasajero->getWhere(array('pasajero_id != ' => $pax_id, 'p.email'=>$_POST['email_'.$pax_id],'reserva_id'=>$reserva_id))->result();
							if(count($existe)>0){
								//retorno dato invalido
								$ret['status'] = 'error';
								$ret['pax_invalid_email'] = true;
								$ret['msg'] = 'No puedes usar el mismo Email del responsable o acompañantes.';
								$ret['fields'] = array('email_'.$pax_id);
								echo json_encode($ret);
								exit();
							}


							//dni se pide si es argentino
							if(isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1){
								$existe = $this->Reserva_pasajero->getWhere(array('pasajero_id != ' => $pax_id, 'p.dni'=>$_POST['dni_'.$pax_id],'reserva_id'=>$reserva_id))->result();
								
								if(count($existe)>0){
									//retorno dato invalido
									$ret['status'] = 'error';
									$ret['pax_invalid_dni'] = true;
									$ret['msg'] = 'No puedes usar el mismo DNI del responsable o acompañantes.';
									$ret['fields'] = array('dni_'.$pax_id);
									echo json_encode($ret);
									exit();
								}
							}
							
							if( $condicion_pasaporte ){
								$existe = $this->Reserva_pasajero->getWhere(array('pasajero_id != ' => $pax_id, 'p.pasaporte'=>$_POST['pasaporte_'.$pax_id],'reserva_id'=>$reserva_id))->result();
								
								if(count($existe)>0){
									//retorno dato invalido
									$ret['status'] = 'error';
									$ret['pax_invalid_pasaporte'] = true;
									$ret['msg'] = 'No puedes usar el mismo Pasaporte del responsable o acompañantes.';
									$ret['fields'] = array('pasaporte_'.$pax_id);
									echo json_encode($ret);
									exit();
								}
							}
						}				
						
						$pax = array();		
						$pax['nombre'] = @$_POST['nombre_'.$pax_id];
						$pax['apellido'] = @$_POST['apellido_'.$pax_id];
						
						$pax['fecha_nacimiento'] = @$_POST['nacimiento_ano_'.$pax_id].'-'.@$_POST['nacimiento_mes_'.$pax_id].'-'.@$_POST['nacimiento_dia_'.$pax_id];
						
						/*
						if($_POST['nacimiento_'.$pax_id] != ''){
							$nacimiento = explode('/',$_POST['nacimiento_'.$pax_id]);
							$pax['fecha_nacimiento'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
						}
						else{
							$nacimiento = '';
							$pax['fecha_nacimiento'] = '';
						}
						*/
						
						$pax['sexo'] = @$_POST['sexo_'.$pax_id];
						$pax['nacionalidad_id'] = @$_POST['nacionalidad_id_'.$pax_id];
						
						if($pax['nacionalidad_id'] == 1){
							// si es otro que argentina, el dni no es obligatorio
							$pax['dni'] = @$_POST['dni_'.$pax_id];
						}
						
						/*
						if (isset($_POST['pasaporte_'.$pax_id])) {
							$pax['pasaporte'] = @$_POST['pasaporte_'.$pax_id];
							$pax['pais_emision_id'] = @$_POST['pais_emision_id_'.$pax_id];
							$pax['fecha_emision'] = @$_POST['fecha_emision_ano_'.$pax_id].'-'.@$_POST['fecha_emision_mes_'.$pax_id].'-'.@$_POST['fecha_emision_dia_'.$pax_id];
							$pax['fecha_vencimiento'] = @$_POST['fecha_vencimiento_ano_'.$pax_id].'-'.@$_POST['fecha_vencimiento_mes_'.$pax_id].'-'.@$_POST['fecha_vencimiento_dia_'.$pax_id];
						}
						*/
						
						if( (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1 && $pasaporte_obligatorio) 
							|| (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] > 1) ){
							//si la nacionalidad elegida es Argentina y tiene pasaporte obligatorio por back => campo obligatorio
							//si la nacionalidad es otra que no sea argentina => campo obligatorio
						
							$pax['pasaporte'] = @$_POST['pasaporte_'.$pax_id];
							$pax['pais_emision_id'] = @$_POST['pais_emision_id_'.$pax_id];
							if($_POST['fecha_emision_'.$pax_id] != ''){
								$fecha_emision = explode('/',$_POST['fecha_emision_'.$pax_id]);
								$pax['fecha_emision'] = $fecha_emision[2].'-'.$fecha_emision[1].'-'.$fecha_emision[0];
							}
							else{
								$fecha_emision = '';
								$pax['fecha_emision'] = '';
							}
							if($_POST['fecha_vencimiento_'.$pax_id] != ''){
								$fecha_vencimiento = explode('/',$_POST['fecha_vencimiento_'.$pax_id]);
								$pax['fecha_vencimiento'] = $fecha_vencimiento[2].'-'.$fecha_vencimiento[1].'-'.$fecha_vencimiento[0];
							}
							else{
								$fecha_vencimiento = '';
								$pax['fecha_vencimiento'] = '';
							}
						}
						
						$pax['email'] = @$_POST['email_'.$pax_id];
						$pax['celular_codigo'] = @$_POST['celular_codigo_'.$pax_id];
						$pax['celular_numero'] = @$_POST['celular_numero_'.$pax_id];
						$pax['emergencia_nombre'] = @$_POST['emergencia_nombre_'.$pax_id];
						$pax['emergencia_telefono_codigo'] = @$_POST['emergencia_telefono_codigo_'.$pax_id];
						$pax['emergencia_telefono_numero'] = @$_POST['emergencia_telefono_numero_'.$pax_id];
						$pax['dieta'] = @$_POST['dieta_'.$pax_id];
						//$pax['direccion'] = @$_POST['direccion_'.$pax_id];
						$pax['timestamp'] = date('Y-m-d H:i:s');
						$pax['ip'] = $_SERVER['REMOTE_ADDR'];
						
						//actualizo data de pasajero
						$this->Pasajero->update($pax_id,$pax);
						
						//actualizo registro de reserva pasajero
						$repax = array();
						$repax['salteo'] = '0';
						
						//chequeo si está TODO completo
						$repax['completo'] = 1;
						
						foreach ($pax as $key=>$value) {
							if (empty($pax[$key])) {
								$repax['completo'] = 0;
								break;
							}
						}

						$this->Reserva_pasajero->updateWhere(array('reserva_id'=>$reserva_id,'pasajero_id'=>$pax_id),$repax);
						
						$pasajero = $this->Reserva_pasajero->getWhere(array('reserva_id'=>$reserva_id,'pasajero_id'=>$pax_id))->row();
						
						//si es el pasajero responsable, actualizo registro en Usuarios
						if($pasajero->responsable){
							$reserva = $this->Reserva->get($reserva_id)->row();
							$this->Usuario->update($reserva->usuario_id,$pax);
						}
						
						//chequeo si todavia hay alguno incompleto no salteado
						$this->Reserva_pasajero->filters = "completo = 0 and reserva_id = ".$reserva_id;
						$incompletos = $this->Reserva_pasajero->getAll(999,0,'numero_pax','asc')->result();
					
						//si todavia queda alguno por completar que no haya salteado, dejo el mismo form
						if(count($incompletos)){
							$ret['pax'] = $pasajero;
							$ret['status'] = 'success';	
						}
						else{
							//ahora deben aparecer los terminos y condiciones
							$ret['aceptar_terminos'] = true;
							$ret['status'] = 'success';	
							$ret['pax'] = $pasajero;
						}
					}
				}
			}
			
		}
		else{
			//click en boton confirmar datos
			//valido que haya tildado los terminos
			$this->form_validation->set_rules('terminos_pax', 'Términos y condiciones', 'required');
			if ($this->form_validation->run() == FALSE){
				
				$ret['status'] = 'error';
				$ret['fields'] = array_keys($this->form_validation->error_array());
			}
			else{
				// Maxi | 11-01-19 ahora al actualizar los datos de pasajeros, el paso que se completa es el 2
				//actualizo reserva
				$fecha_completo_paso2 = date('Y-m-d H:i:s');
				$this->Reserva->update($reserva_id,array('fecha_completo_paso2' => $fecha_completo_paso2, 'completo_paso2' => 1, 'salteo_paso2' => 0, 'paso_actual' => 4));
				$reserva = $this->Reserva->get($reserva_id)->row();
				$this->data['reserva'] = $reserva;
		
				$hash = encriptar($this->data['reserva']->code);
				
				//enviar mail al usuario ya que completó los datos de todos los pasajeros
				$mail = true;
				$template = 'datos_completos';
				registrar_comentario_reserva($reserva_id,7,'envio_mail','Envio de email por datos completos de psajeros. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo, $mail, $template);

				$ret['status'] = 'success';
				$ret['redirect'] = site_url('reservas/resumen/'.$hash.'?success=1');
			}
		}
			
		echo json_encode($ret);
	}
	
	/*
	Metodo que se accede desde el mail de datos_Reserva, para completar los datos de pasajeros
	*/
	public function completar_datos($hash){
		$this->data['hash'] = $hash;
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva = $this->Reserva->get($reserva->id)->row();
			$reserva->adicionales = adicionales_reserva($reserva);
			
			if($reserva->id){
				$this->cargar_data_reserva($reserva);

				//reserva valida
				$this->render('completar_datos');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	/*
	Metodo que se accede desde el mail de datos_Reserva, para generar pagos
	*/
	public function generar_pago($hash){
		$this->data['hash'] = $hash;
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva = $this->Reserva->get($reserva->id)->row();
			$reserva->adicionales = adicionales_reserva($reserva);
			
			if($reserva->id){
				$this->cargar_data_reserva($reserva);
				
				//reserva valida
				$this->render('generar_pago');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	/*
	Metodo que se accede desde el mail de vouchers_viaje, para ver y descargar el listado de vouchers
	*/
	public function vouchers_viaje($hash){
		$this->data['hash'] = $hash;
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva = $this->Reserva->get($reserva->id)->row();
			$reserva->adicionales = adicionales_reserva($reserva);
			
			if($reserva->id){
				$this->cargar_data_reserva($reserva);
				
				//cargo vouchers de viaje si corresponden
				$this->data['mis_vouchers'] = $this->Reserva_voucher->getWhere(array('reserva_id' => $reserva->id))->result();
				$this->data['vouchers_viaje'] = $this->load->view('vouchers_viaje',$this->data,true);

				//reserva valida
				$this->render('vouchers_viaje');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	/*
	Carga todos los datos de la reserva para mostrarlos en las diferentes vistas de resumen de viaje 
	y las que se acceden por mail
	El segundo parametro se usa para cuando se genera y descarga el voucher de Buenas Vibras
	*/
	function cargar_data_reserva($reserva,$descarga_voucher=false){
		$this->load->model('Paquete_model', 'Paquete');
		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		$reserva->exterior = $paquete->exterior;

		$this->data['paquete'] = $paquete;
		$this->data['reserva'] = $reserva;
		$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
		
		$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);
		$this->data['montos_numericos'] = montos_numericos($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);
							
		/*pre($this->data['precios']);
		pre($this->data['montos_numericos']);*/

		//datos facturacion (para paso 2)
		$this->Reserva_facturacion->filters = "reserva_id = ".$this->data['reserva']->id;
		$this->data['facturacion'] = $this->Reserva_facturacion->getAll(1,0,'id','asc')->row();	
		
		//responsable y acompañantes, ordenado por numero de pax asc
		$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.reserva_id = ".$this->data['reserva']->id;
		$pasajeros = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
		$this->data['pasajeros'] = $pasajeros;
		
		//acompañantes (para ver cuantos incompletos hay)
		$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 0 and bv_reservas_pasajeros.reserva_id = ".$this->data['reserva']->id;
		$this->data['acompanantes'] = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
		
		//me fijo si hay algun pasajero con datos incompletos
		$incompletos = array();
		$completos = array();
		foreach($this->data['pasajeros'] as $a){
			if(!$a->completo){
				$incompletos[] = $a->numero_pax;
			}
			else{
				$completos[] = $a->numero_pax;
			}
		}
		$this->data['completos'] = $completos;
		$this->data['incompletos'] = $incompletos;
		
		
		if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
			//pre($incompletos);
			//pre($completos);
		}
		
		/*
		determinar aca si todavía no completó los datos de los pasajeros y si tiene tiempo
		para todavia completarlos
		*/
		$this->data['completar_datos'] = false;
		
		if( (puede_completar_datos($reserva) && !$reserva->completo_paso2 && ($reserva->fecha_completo_paso2 == '0000-00-00' || !$reserva->fecha_completo_paso2 ) )
			|| (puede_completar_datos($reserva) && count($pasajeros) == 1 && count($incompletos)>0) ){
			$this->data['completar_datos'] = true;
		}
		
		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();

		//esta marca oculta algunas cosas del view
		$this->data['descarga_voucher'] = $descarga_voucher;
				
		//me fijo si el paquete requiere pasaporte obligatorio por backend
		//2 es el id de pasaporte
		$documentaciones = $this->Paquete->getDocumentaciones($reserva->paquete_id);
		$this->data['pasaporte_obligatorio'] = in_array(2,$documentaciones);
				
		//17-09-18
		//si es una reserva de grupo, muestro tambien los datos de los demás pasajeros del grupo como si fueran acompañantes, pero que no se puedan cambiar sus datos
		if($reserva->grupal){
			$reservas_grupo = $this->Reserva->getReservasActivasGrupo($reserva->codigo_grupo);
			if(count($reservas_grupo) > 1){
				$ids = [];
				foreach ($reservas_grupo as $r) {
					if($reserva->id != $r->reserva_id){
						//si no es la reserva del responsable
						$ids[] = $r->reserva_id;
					}
				}

				if(count($ids)){
					$ids = implode(',',$ids);
					//traigo los demás pasajeros del grupo que no sea el responsable
					$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.reserva_id IN (".$ids.")";
					$pasajeros = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
					$this->data['pasajeros_grupo'] = $pasajeros;
				}
			}
		}

		//cargo modulo de completar datos de pasajeros
		$this->data['completar_datos_form'] = $this->load->view('completar_datos_form',$this->data,true);
		
		//cargo modulo de generar pago
		$this->data['generar_pago_form'] = $this->load->view('generar_pago_form',$this->data,true);		

	}

	/*
	Metodo que genera el zip y descarga los vouchers de reserva
	*/
	function vouchers($hash){
		$this->data['hash'] = $hash;
		//devuelve codigo de reserva + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$res = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			$reserva = $this->Reserva->get($res->id)->row();
			
			$paquete = $this->Paquete->get($reserva->paquete_id)->row();
			
			$this->load->library('zip');
				
			$file = false;
			//si es viaje producido por Buenas vibras genero un pdf con los datos del viaje
			#if($reserva->operador_id == 1){
			//si el viaje tiene configurado envio automatico
			if( $paquete->voucher_automatico ){
				$reserva->adicionales = adicionales_reserva($reserva);	

				//este flag es para descargar voucher con los datos que haya al momento
				$this->data['download_voucher'] = true;			
				$this->cargar_data_reserva($reserva,true);
				$html = $this->load->view('voucher-viaje-bv', $this->data, true);
				
				$this->load->helper(array('dompdf','file'));
				$pdf = pdf_create($html, '', false);
				$comprobante = $reserva->code;
				$path = '/data/vouchers-bv/';
				$rel_path = '.'.$path;
				if(!is_dir($rel_path)){
					mkdir($rel_path,0777);
				}
				$file = $rel_path.$comprobante.'.pdf';
				write_file($file, $pdf);
				
				//$this->zip->read_file($file);

				//si es vaoucher de BV el pdf se descarega como pdf, no zip
				header("Content-type:application/pdf");
				header("Content-Disposition:attachment;filename='".$comprobante.".pdf");				
				readfile($file);
			}
			else{
				//ahora desde aca redirijo a la url donde muestro el listado

				redirect(site_url('reservas/vouchers_viaje/'.encriptar($reserva->code)));
				/*
				//cargo los vouchers asociados a la reserva
				$mis_vouchers = $this->Reserva_voucher->getWhere(array('reserva_id' => $reserva->id))->result();
				
				foreach($mis_vouchers as $v){
					if($v->archivo && file_exists('./uploads/reservas/'.$v->reserva_id.'/'.$v->archivo)){
						$path = './uploads/reservas/'.$v->reserva_id.'/'.$v->archivo;
						$this->zip->read_file($path);
					}
				}

				$this->zip->download('vouchers-'.$reserva->code.'.zip');
				*/
			}
			
			
		}
	}
	
//de test
	function vermail(){
		$CI =& get_instance();
		
		//enviar mail de pre reserva
		//enviar_datos_reserva($reserva_id=1);
		
		//enviar mail de pago recibido
		//pago_id es el ID de la tabla movimientos
		//enviar_recepcion_pago($reserva_id=1,$pago_id=27);
		
		//enviar mail vouchers
		enviar_mail_vouchers($reserva_id=1);
	}
	
	
	function vercod(){
		echo $code = 'BV-CUZ897-'.str_pad($id=3, 5, '0', STR_PAD_LEFT);
	}
	
	function create_test_user(){
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$preference_data['site_id'] = 'MLA';
		$preference = $mp->create_test_user($preference_data);    
		pre($preference);
	}

	/* se da de baja de la lista de espera */
	function baja($hash){
		$data = desencriptar($hash);

		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
			
			if($reserva->id){
				$this->Reserva->update($reserva->id,array('estado_id' => 5));

				//Registrar el envio de mail de reserva anulada manualmente, por decision del usuario
				$mail = true;
				$template = 'reserva_anulada_manual';
				registrar_comentario_reserva($reserva->id,7,'anulacion','El usuario se dio de baja de la Lista de Espera',$mail,$template,$ref_id=false);
				
				redirect(site_url('reservas/resumen/'.$hash));
			}			
		}

	}

}