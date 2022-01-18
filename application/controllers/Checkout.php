<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Orden_model','Orden');
		$this->load->model('Orden_pasajero_model','Orden_pasajero');
		$this->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$this->load->model('Orden_facturacion_model','Orden_facturacion');
		$this->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$this->load->model('Habitacion_model','Habitacion');
		
		$this->data['body_id'] = 'checkout';
		$this->data['body_class'] = 'checkout';

		//AGREGADOS DE PAGINA INTERMEDIA
		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('../bootstrap/js/bootstrap.min.js');
		$this->carabiner->js('main.js');
		$this->carabiner->js('sticky.js');
		$this->carabiner->js('sliders.js');
		$this->carabiner->js('../../assets_old/js/jquery.scrollTo.min.js');
		
		$this->load_phone_site();
	}

	public function index(){
	}
	
	public function gen($hash){
		echo encriptar($hash);
	}
	
	public function orden($hash){
		$this->data['hash'] = $hash;
		//devuelve codigo de orden + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$orden = $this->Orden->getWhere(array('code' => $data[0]))->row();
			if (!$orden) {
				redirect(site_url());
			}

			$combinacion = $this->Combinacion->get($orden->combinacion_id)->row();
			$orden->adicionales = $this->adicionales_orden($orden);
			//$this->data['adicionales'] = $orden->adicionales;

		/*if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
			print_r($orden->adicionales);
		}*/
		
			if($orden->id){
				$this->data['orden'] = $orden;
				$this->data['combinacion'] = $combinacion;
				
				//Si la orden vencio debe realizarla de nuevo
				if ($orden->vencida) {
					redirect(site_url($combinacion->slug.'?vencida=1'));
				}

				//pre($this->data['combinacion']);
				$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva_id=false,$orden->id);
				
				$precio_usd = $combinacion->precio_usd ? 1 : 0;
				$this->data['precios']['precio_bruto'] = precio_redondeado($this->data['precios']['num']['precio_bruto'],$precio_usd);
				$this->data['precios']['precio_impuestos'] = precio_redondeado($this->data['precios']['num']['precio_impuestos'],$precio_usd);
				$this->data['precios']['precio_bruto_persona'] = precio_redondeado($this->data['precios']['num']['precio_bruto_persona'],$precio_usd);
				$this->data['precios']['precio_impuestos_persona'] = precio_redondeado($this->data['precios']['num']['precio_impuestos_persona'],$precio_usd);
				$this->data['precios']['precio_total'] = precio_redondeado($this->data['precios']['num']['precio_total'],$precio_usd);
		
				$this->cargar_pasos();
				
				$this->seo_title = $this->data['paquete']->nombre;
				$this->seo_description = strip_tags($this->data['paquete']->descripcion);
				$this->seo_keywords = '';
			
				//orden valida
				$this->render('checkout');
			}
			else{
				redirect(base_url());
			}
		}
		else{
			redirect(base_url());
		}
		
	}
	
	function adicionales_orden($orden){
		$orden->adicionales = $this->Orden->getAdicionales($orden->id);
		
		if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
			//print_r($orden->adicionales);
		}
		
		$adicionales_nombre = array();
		$adicionales_valores = array();
		foreach($orden->adicionales as $a){
			$adicionales_nombre[] = $a->nombre;
			$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
		}
		$orden->nombre_adicionales = implode(', ',$adicionales_nombre);
		
		$this->data['adicionales_valores'] = $adicionales_valores;
		
		return $orden->adicionales;
	}
			
	function cargar_paso($nro){
		$paquete_id = $this->data['orden']->paquete_id;
		$paquete = $this->Paquete->get($paquete_id)->row();
		$this->data['paquete'] = $paquete;
		$combinacion_id = $this->data['orden']->combinacion_id;
		$combinacion = $this->Combinacion->get($combinacion_id)->row();
		$this->data['combinacion'] = $combinacion;
		
		switch($nro){
			case 1:
				//$paquete_id = $this->data['orden']->paquete_id;
				//$paquete = $this->Paquete->get($paquete_id)->row();
				$this->data['exterior'] = $paquete->exterior;
				
				//me fijo si el paquete requiere pasaporte obligatorio por backend
				//2 es el id de pasaporte
				$documentaciones = $this->Paquete->getDocumentaciones($paquete_id);
				$this->data['pasaporte_obligatorio'] = in_array(2,$documentaciones);
				//17-08-18 si se alcanzÃ³ la fecha limite de carga de datos basicos, los pido
				$forzar_datos_basicos = false;
				
				if($this->data['paquete']->fecha_limite_completar_datos <= date('Y-m-d')){
					$forzar_datos_basicos = true;
				}
				$this->data['forzar_datos_basicos'] = $forzar_datos_basicos;									
				$this->load->model('Parada_model','Parada');
				$this->data['paradas'] = $this->Parada->getByPaquete($this->data['orden']->paquete_id,$this->data['orden']->lugar_id);
				
				$this->load->model('Pais_model','Pais');
				$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();
				
				//pasajero responsable (el primero) (para paso 1)
				$this->Orden_pasajero->filters = "responsable = 1 and orden_id = ".$this->data['orden']->id;
				$this->data['responsable'] = $this->Orden_pasajero->getAll(1,0,'numero_pax','asc')->row();
				
				//cantidad total de pasajeros
				$this->Orden_pasajero->filters = "orden_id = ".$this->data['orden']->id;
				$this->data['pasajeros'] = $this->Orden_pasajero->getAll(999,0,'numero_pax','asc')->result();
				
			break;
			case 2:
				$this->load->model('Pais_model','Pais');
				$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();
				
				//datos facturacion (para paso 2)
				$this->Orden_facturacion->filters = "orden_id = ".$this->data['orden']->id;
				$this->data['facturacion'] = $this->Orden_facturacion->getAll(1,0,'id','asc')->row();	

				//$this->verificar_cupo();				
				verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
			break;
			case 3:
				$this->load->model('Pais_model','Pais');
				$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();
				
				//acompañantes (para paso 3), ordenado por numero de pax
				$this->Orden_pasajero->filters = "responsable = 0 and orden_id = ".$this->data['orden']->id;
				$this->data['acompanantes'] = $this->Orden_pasajero->getAll(999,0,'numero_pax','asc')->result();
				
				//me fijo si el paquete requiere pasaporte obligatorio por backend
				//2 es el id de pasaporte
				$documentaciones = $this->Paquete->getDocumentaciones($this->data['orden']->paquete_id);
				$this->data['pasaporte_obligatorio'] = in_array(2,$documentaciones);
				
				//17-08-18 si se alcanzÃ³ la fecha limite de carga de datos basicos, los pido
				$forzar_datos_basicos = false;
				
				if($this->data['paquete']->fecha_limite_completar_datos <= date('Y-m-d')){
					$forzar_datos_basicos = true;
				}
				$this->data['forzar_datos_basicos'] = $forzar_datos_basicos;	
				//me fijo si hay algun acompañante con datos incompletos
				$incompletos = array();
				$completos = array();
				foreach($this->data['acompanantes'] as $a){
					if(!$a->completo){
						$incompletos[] = $a->numero_pax;
					}
					else{
						$completos[] = $a->numero_pax;
					}
				}
				$this->data['completos'] = $completos;
				$this->data['incompletos'] = $incompletos;
			break;
			case 4:
				//$this->verificar_cupo();
				verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
			break;
			
		}
		
	}
	
	function verificar_cupo(){
		verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
	}
	
	//carga los diferentes pasos del checkout 
	function cargar_pasos(){
		$this->cargar_paso($step=1);
		$this->cargar_paso($step=2);
		$this->cargar_paso($step=3);
		$this->cargar_paso($step=4);
		
		$this->data['checkout_paso1'] = $this->load->view('checkout_paso1',$this->data,true);
		$this->data['checkout_paso2'] = $this->load->view('checkout_paso2',$this->data,true);
		$this->data['checkout_paso3'] = $this->load->view('checkout_paso3',$this->data,true);
		$this->data['checkout_paso4'] = $this->load->view('checkout_paso4',$this->data,true);
	}
	
	//valida cada uno de los pasos del checkout
	function validar_form(){
		
		switch($_POST['numero_paso']){
			case 1:
				$ret = $this->validar_paso1();
			break;
			case 2:
				$ret = $this->validar_paso2();
			break;
			case 3:
				$ret = $this->validar_paso3();
			break;
			case 4:
				$ret = $this->validar_paso4();
			break;			
		}
		
		echo json_encode($ret);
	}
	
	function validar_paso1(){
		extract($_POST);
		
		$orden = $this->Orden->get($orden_id)->row();
		$documentaciones = $this->Paquete->getDocumentaciones($orden->paquete_id);
		$pasaporte_obligatorio = in_array(2,$documentaciones);
			
		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('apellido', 'Apellido', 'required');
		
		//con estos 3 rmo fecha y valido si esta ok o no
		
		$this->form_validation->set_rules('nacimiento_dia', 'Dia de nacimiento', 'required');
		$this->form_validation->set_rules('nacimiento_mes', 'Mes de nacimiento', 'required');
		$this->form_validation->set_rules('nacimiento_ano', 'Año de nacimiento', 'required');
		
		//$this->form_validation->set_rules('nacimiento', 'Fecha de nacimiento', 'required');
		
		//22-08-19 tomo los datos de dni y pasaporte para ver si los completó
		$pax_dni = $this->input->post('dni');
		$pax_pasaporte = $this->input->post('pasaporte');
		$es_extranjero = (isset($_POST['nacionalidad_id']) && $_POST['nacionalidad_id'] > 1) ? true : false;

		$this->form_validation->set_rules('sexo', 'Sexo', 'required');
		if(isset($nacionalidad_id) && $nacionalidad_id == 1){
			//solo dni para argentina
			$this->form_validation->set_rules('dni', 'DNI', 'required');
		}
		$this->form_validation->set_rules('email', 'Email', 'required');
		if($orden->lugar_id != 4){
			$this->form_validation->set_rules('paquete_parada_id', 'Lugar de salida', 'required');
		}
		$this->form_validation->set_rules('dieta', 'Dieta', 'required');
		$this->form_validation->set_rules('nacionalidad_id', 'Nacionalidad', 'required');
		
		$combinacion = $this->Combinacion->get($orden->combinacion_id)->row();

		//17-08-18
		if($orden->fecha_limite_completar_datos <= date('Y-m-d') || $combinacion->grupal){
			//contacto de emergencia
			$this->form_validation->set_rules('emergencia_nombre', 'Contacto de emergencia - Nombre', 'required');
			$this->form_validation->set_rules('emergencia_telefono_codigo', 'Contacto de emergencia - Teléfono Cod.', 'required');
			$this->form_validation->set_rules('emergencia_telefono_numero', 'Contacto de emergencia - Teléfono Num.', 'required');
		}
		/*
		$this->form_validation->set_rules('nacionalidad', 'Nacionalidad', 'required');
		$this->form_validation->set_rules('pasaporte', 'Pasaporte', 'required');
		$this->form_validation->set_rules('pais_emision', 'País Emisión', 'required');			
		$this->form_validation->set_rules('fecha_emision_dia', 'Dia de Fecha de Emisión', 'required');
		$this->form_validation->set_rules('fecha_emision_mes', 'Mes de Fecha de Emisión', 'required');
		$this->form_validation->set_rules('fecha_emision_ano', 'Año de Fecha de Emisión', 'required');			
		$this->form_validation->set_rules('fecha_vencimiento_dia', 'Dia de Fecha de Vencimiento', 'required');
		$this->form_validation->set_rules('fecha_vencimiento_mes', 'Mes de Fecha de Vencimiento', 'required');
		$this->form_validation->set_rules('fecha_vencimiento_ano', 'Año de Fecha de Vencimiento', 'required');
		//contacto de emergencia
		$this->form_validation->set_rules('emergencia_nombre', 'Contacto de emergencia - Nombre', 'required');
		$this->form_validation->set_rules('emergencia_telefono_codigo', 'Contacto de emergencia - Teléfono Cod.', 'required');
		$this->form_validation->set_rules('emergencia_telefono_numero', 'Contacto de emergencia - Teléfono Num.', 'required');
		*/
		
		$this->form_validation->set_rules('celular_codigo', 'Codigo de Celular', 'required');
		$this->form_validation->set_rules('celular_numero', 'Numero de Celular', 'required');
		
		//17-08-18 si se alcanzo la fecha limite de datos, verifico si tengo q pedirle docs obligatorios
		if($orden->fecha_limite_completar_datos <= date('Y-m-d')){
			$condicion_pasaporte = (isset($_POST['nacionalidad_id']) && $_POST['nacionalidad_id'] > 1)
					|| (isset($_POST['nacionalidad_id']) && $_POST['nacionalidad_id'] == 1 && $pasaporte_obligatorio);

			if( $condicion_pasaporte ){
				//si la nacionalidad elegida es Argentina y tiene pasaporte obligatorio por back => campo obligatorio
				//si la nacionalidad es otra que no sea argentina => campo obligatorio
			
				if($pasaporte_obligatorio){
					$this->form_validation->set_rules('pasaporte', 'Pasaporte', 'required');
				}
				else{
					if($es_extranjero && $pax_dni){
						//no le pido pasaporte
					}
					else{
						if($es_extranjero){
							if(!$pax_dni && !$pax_pasaporte){
								$this->form_validation->set_rules('pasaporte', 'Pasaporte', 'required');
								$this->form_validation->set_rules('dni', 'DNI', 'required');

								$ret['error_pasaporte'] = 'Debe completar Documento y/o Pasaporte';
								$ret['error_dni'] = 'Debe completar Documento y/o Pasaporte';
							}
							
						}
					}	
				}
				

				$this->form_validation->set_rules('pais_emision_id', 'País de emisión', 'required');
				/*
				$this->form_validation->set_rules('fecha_emision', 'Fecha de emisión del pasaporte', 'required');
				$this->form_validation->set_rules('fecha_vencimiento', 'Fecha de vencimiento del pasaporte', 'required');
				*/

				$this->form_validation->set_rules('fecha_emision_dia', 'Dia de Fecha de Emisión', 'required');
				$this->form_validation->set_rules('fecha_emision_mes', 'Mes de Fecha de Emisión', 'required');
				$this->form_validation->set_rules('fecha_emision_ano', 'Año de Fecha de Emisión', 'required');			
				$this->form_validation->set_rules('fecha_vencimiento_dia', 'Dia de Fecha de Vencimiento', 'required');
				$this->form_validation->set_rules('fecha_vencimiento_mes', 'Mes de Fecha de Vencimiento', 'required');
				$this->form_validation->set_rules('fecha_vencimiento_ano', 'Año de Fecha de Vencimiento', 'required');
			}
		}
		/*
		//No son obligatorios al reservar
		//me fijo si el paquete requiere pasaporte obligatorio por backend
		//2 es el id de pasaporte
		$documentaciones = $this->Paquete->getDocumentaciones($orden->paquete_id);
		$pasaporte_obligatorio = in_array(2,$documentaciones);
		if( (isset($nacionalidad_id) && $nacionalidad_id == 1 && $pasaporte_obligatorio) 
		|| (isset($nacionalidad_id) && $nacionalidad_id > 1) ){
			//si la nacionalidad elegida es Argentina y tiene pasaporte obligatorio por back => campo obligatorio
			//si la nacionalidad es otra que no sea argentina => campo obligatorio
			$this->form_validation->set_rules('pasaporte', 'Pasaporte N°', 'required');
			$this->form_validation->set_rules('pais_emision_id', 'País de emisión', 'required');
			$this->form_validation->set_rules('fecha_emision', 'Fecha de emisión del pasaporte', 'required');
			$this->form_validation->set_rules('fecha_vencimiento', 'Fecha de vencimiento del pasaporte', 'required');
		}
		*/
		
		if ($this->form_validation->run() == FALSE){
			$valida = false;
			
			$ret['status'] = 'error';
			$ret['fields'] = array_keys($this->form_validation->error_array());
		}
		else{
			//solo para argentina valido longitud de dni
			if(isset($nacionalidad_id) && $nacionalidad_id == 1 && isset($dni) && strlen($dni)!=8) {
				$valida = false;
				//retorno dni invalido por extension
				$ret['status'] = 'error';
				$ret['invalid_dni'] = true;
				$ret['msg'] = 'La longitud del DNI debe ser de 8 dígitos';
				$ret['fields'] = array('dni');
			}
			else{
				
				$str_fecha = $nacimiento_ano.'-'.$nacimiento_mes.'-'.$nacimiento_dia;
				
				//valido fecha nacimiento
				if(!validateDate($str_fecha)){
					$valida = false;
					//retorno fecha invalida
					$ret['status'] = 'error';
					$ret['invalid_nacimiento'] = true;
					$ret['msg'] = 'La fecha de nacimiento no es correcta';
					$ret['fields'] = array('nacimiento_ano');
				}
				else{
					if(!isValidEmail($_POST['email'])){
						$valida = false;
						//retorno email invalida
						$ret['status'] = 'error';
						$ret['invalid_email'] = true;
						$ret['msg'] = 'El formato del email no es válido.';
						$ret['fields'] = array('email');
					}
					else{
						if(isset($nacionalidad_id) && $nacionalidad_id == 1 ) {
							//chequeo si existe reserva para ese EMAIL
							$existe = $this->Orden_pasajero->existeOrdenPasajero($email,false,$orden->combinacion_id,$pax_id);
							
							if(count($existe)){
								$valida = false;
								//retorno usuario invalido para ese email
								$ret['status'] = 'error';
								$ret['invalid_user'] = true;
								$ret['msg'] = 'Ya existe una reserva para este Email.';
								$ret['fields'] = array('email');
								return $ret;
							}

							//chequeo si existe reserva para ese DNI
							$existe = $this->Orden_pasajero->existeOrdenPasajero(false,$dni,$orden->combinacion_id,$pax_id);
							
							if(count($existe)){
								$valida = false;
								//retorno usuario invalido para ese dni
								$ret['status'] = 'error';
								$ret['invalid_user'] = true;
								$ret['msg'] = 'Ya existe una reserva para este DNI.';
								$ret['fields'] = array('dni');
								return $ret;
							}

							//chequeo si existe reserva de ese pasajero EMAIL para esta combinacion
							$wh = array();
							$wh['p.email'] = $email;
							$wh['r.paquete_id'] = $orden->paquete_id;
							$existe = $this->Reserva_pasajero->getWhere($wh)->result();
							
							if(count($existe)){
								$valida = false;
								//retorno usuario invalido para ese email
								$ret['status'] = 'error';
								$ret['invalid_user'] = true;
								$ret['msg'] = 'Ya existe una reserva para este Email';
								$ret['fields'] = array('email');
								return $ret;
							}

							//chequeo si existe reserva de ese pasajero DNI para esta combinacion
							$wh = array();
							$wh['p.dni'] = $dni;
							$wh['r.paquete_id'] = $orden->paquete_id;
							$existe = $this->Reserva_pasajero->getWhere($wh)->result();
							
							if(count($existe)){
								$valida = false;
								//retorno usuario invalido para ese email+dni
								$ret['status'] = 'error';
								$ret['invalid_user'] = true;
								$ret['msg'] = 'Ya existe una reserva para este DNI.';
								$ret['fields'] = array('dni');
								return $ret;
							}
						}
						else if(isset($nacionalidad_id) && $nacionalidad_id > 1){
							//extranjero valido con email +pasaporte
						 	if(isset($pasaporte) && $pasaporte) {
								//chequeo si existe reserva de ese pasajero EMAIL para esta combinacion
								$existe = $this->Orden_pasajero->existeOrdenPasajero($email,false,$orden->combinacion_id,$pax_id);
							
								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese email
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Email';
									$ret['fields'] = array('email');
									return $ret;
								}
	
								//chequeo si existe reserva de ese pasajero PASAPORTE para esta combinacion
								$existe = $this->Orden_pasajero->existeOrdenPasajero(false,false,$orden->combinacion_id,$pax_id,$pasaporte);
							
								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese pasaporte
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Pasaporte.';
									$ret['fields'] = array('pasaporte');
									return $ret;
								}

								//chequeo si existe reserva de ese pasajero EMAIL para esta combinacion
								$wh = array();
								$wh['p.email'] = $email;
								$wh['r.paquete_id'] = $orden->paquete_id;
								$existe = $this->Reserva_pasajero->getWhere($wh)->result();

								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese email+dni
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Email.';
									$ret['fields'] = array('email');
									return $ret;
								}

								//chequeo si existe reserva de ese pasajero PASAPORTE para esta combinacion
								$wh = array();
								$wh['p.pasaporte'] = $pasaporte;
								$wh['r.paquete_id'] = $orden->paquete_id;
								$existe = $this->Reserva_pasajero->getWhere($wh)->result();

								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese pasaporte
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Pasaporte.';
									$ret['fields'] = array('pasaporte');
									return $ret;
								}
							}
							else{
								//chequeo si existe reserva de ese pasajero EMAIL SÓLO para esta combinacion
								$existe = $this->Orden_pasajero->existeOrdenPasajero($email,false,$orden->combinacion_id,$pax_id,false);
							
								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese email+dni
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Email.';
									$ret['fields'] = array('email');
									return $ret;
								}

								//chequeo si existe reserva de ese pasajero EMAIL SÓLO para esta combinacion
								$wh = array();
								$wh['p.email'] = $email;
								$wh['r.combinacion_id'] = $orden->combinacion_id;
								$existe = $this->Reserva_pasajero->getWhere($wh)->result();

								if(count($existe)){
									$valida = false;
									//retorno usuario invalido para ese email+dni
									$ret['status'] = 'error';
									$ret['invalid_user'] = true;
									$ret['msg'] = 'Ya existe una reserva para este Email.';
									$ret['fields'] = array('email');
									return $ret;
								}
							}
						}

							/*if($_SERVER['REMOTE_ADDR'] == '181.171.24.39'){
								echo $this->db->last_query();
							}*/

							
							//guardo los datos del paso 1
							$pax = array();
							$pax['orden_id'] = $orden_id;
							$pax['responsable'] = '1';
							$pax['nombre'] = $nombre;
							$pax['apellido'] = $apellido;
							$pax['fecha_nacimiento'] = $nacimiento_ano.'-'.$nacimiento_mes.'-'.$nacimiento_dia;
							//$nacimiento = explode('/',$nacimiento);
							//$pax['fecha_nacimiento'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
							$pax['sexo'] = $sexo;
							
							//el dni solo lo pide para nacionalidad argentina
							//22-08-19 ó si es extranjero y ya completó el dni: puede que haya completado pasaporte
							if( (isset($nacionalidad_id) && $nacionalidad_id == 1) 
								|| ($es_extranjero && $pax_dni) ){
								$pax['dni'] = $dni;
							}
							
							$pax['email'] = $email;
							$pax['dieta'] = $dieta;
							$pax['nacionalidad_id'] = @$nacionalidad_id;
							
							//el pasaporte solo puede venir si el pasaporte esta marcado como obligatorio en backend
							if($pasaporte_obligatorio){							
								$pax['pasaporte'] = @$pasaporte;
								$pax['pais_emision_id'] = @$pais_emision_id;
								//$pax['fecha_emision'] = @$fecha_emision_ano.'-'.@$fecha_emision_mes.'-'.@$fecha_emision_dia;
								
								//ahora viene por post cada campo de le fecha por separado
								$fecha_emision = @$fecha_emision_dia.'/'.@$fecha_emision_mes.'/'.@$fecha_emision_ano;

								if(isset($fecha_emision) && $fecha_emision != ''){
									$fecha_emision = explode('/',$fecha_emision);
									$pax['fecha_emision'] = $fecha_emision[2].'-'.$fecha_emision[1].'-'.$fecha_emision[0];
								}
								else{
									$fecha_emision = '';
									$pax['fecha_emision'] = '';
								}
								//$pax['fecha_vencimiento'] = @$fecha_vencimiento_ano.'-'.@$fecha_vencimiento_mes.'-'.@$fecha_vencimiento_dia;
								$fecha_vencimiento = @$fecha_vencimiento_dia.'/'.@$fecha_vencimiento_mes.'/'.@$fecha_vencimiento_ano;

								if(isset($fecha_vencimiento) && $fecha_vencimiento != ''){
									$fecha_vencimiento = explode('/',$fecha_vencimiento);
									$pax['fecha_vencimiento'] = $fecha_vencimiento[2].'-'.$fecha_vencimiento[1].'-'.$fecha_vencimiento[0];
								}
								else{
									$fecha_vencimiento = '';
									$pax['fecha_vencimiento'] = '';
								}
							}
							
							$pax['celular_codigo'] = @$celular_codigo;
							$pax['celular_numero'] = @$celular_numero;
							$pax['emergencia_nombre'] = @$emergencia_nombre;
							$pax['emergencia_telefono_codigo'] = @$emergencia_telefono_codigo;
							$pax['emergencia_telefono_numero'] = @$emergencia_telefono_numero;
							$pax['timestamp'] = date('Y-m-d H:i:s');
							$pax['ip'] = $_SERVER['REMOTE_ADDR'];
							
							/*
							//chequeo si está todo completo o no (todos o los obligatorios) ?
							if(	@$pax['nombre'] != '' && @$pax['apellido'] != '' && @$pax['fecha_nacimiento'] != '' && 
								@$pax['sexo'] != '' && @$pax['dni'] != '' && @$pax['email'] != '' && @$pax['nacionalidad_id'] != '' && @$pax['pasaporte'] != '' && 
								@$pax['pais_emision_id'] != '' && @$pax['fecha_emision'] != '' && 
								@$pax['fecha_vencimiento'] != '' && @$pax['celular_codigo'] != '' && @$pax['celular_numero'] != '' && 
								@$pax['emergencia_nombre'] != '' && @$pax['emergencia_telefono_codigo'] != '' && @$pax['emergencia_telefono_numero'] != '' && 
								@$pax['paquete_parada_id'] != '' && @$pax['dieta'] != '' 
							){
								$pax['completo'] = '1';
							}
							else{
								$pax['completo'] = '0';
							}
							*/
							
							$pax['completo'] = 1;						
							foreach ($pax as $key=>$value) {
								if (empty($pax[$key])) {
									$pax['completo'] = 0;
									break;
								}
							}

							//02-09-19 si el pasaporte no era obligatorio, igualmente si los completó lo actualizao
							if(!$pasaporte_obligatorio){							
								$pax['pasaporte'] = @$pasaporte;
								$pax['pais_emision_id'] = @$pais_emision_id;
								//$pax['fecha_emision'] = @$fecha_emision_ano.'-'.@$fecha_emision_mes.'-'.@$fecha_emision_dia;
								
								//ahora viene por post cada campo de le fecha por separado
								$fecha_emision = @$fecha_emision_dia.'/'.@$fecha_emision_mes.'/'.@$fecha_emision_ano;

								if(isset($fecha_emision) && $fecha_emision != ''){
									$fecha_emision = explode('/',$fecha_emision);
									$pax['fecha_emision'] = $fecha_emision[2].'-'.$fecha_emision[1].'-'.$fecha_emision[0];
								}
								else{
									$fecha_emision = '';
									$pax['fecha_emision'] = '';
								}
								//$pax['fecha_vencimiento'] = @$fecha_vencimiento_ano.'-'.@$fecha_vencimiento_mes.'-'.@$fecha_vencimiento_dia;
								$fecha_vencimiento = @$fecha_vencimiento_dia.'/'.@$fecha_vencimiento_mes.'/'.@$fecha_vencimiento_ano;

								if(isset($fecha_vencimiento) && $fecha_vencimiento != ''){
									$fecha_vencimiento = explode('/',$fecha_vencimiento);
									$pax['fecha_vencimiento'] = $fecha_vencimiento[2].'-'.$fecha_vencimiento[1].'-'.$fecha_vencimiento[0];
								}
								else{
									$fecha_vencimiento = '';
									$pax['fecha_vencimiento'] = '';
								}
							}
							
						
							$this->Orden_pasajero->update($pax_id,$pax);
							
							//paquete_parada_id: este dato lo pongo en el registro de reserva, es propio de la reserva
							$this->Orden->update($orden_id,array('completo_paso1' => 1, 'paso_actual' => 2, 'paquete_parada_id' => @$paquete_parada_id));
							$this->data['orden'] = $this->Orden->get($orden_id)->row();
							
							$this->cargar_paso($step=2);
							
							$ret['nombre'] = $nombre;
							$ret['apellido'] = $apellido;
							$ret['paso2'] = $this->load->view('checkout_paso2',$this->data,true);
							$ret['status'] = 'success';			
						
					}
				}
			}
		}
		
		return $ret;
	}
	
	function validar_paso2(){
		extract($_POST);
		
		if(isset($saltear_facturacion) && $saltear_facturacion){
			//si saltea este paso es porque entro con reserva en lista de espera
			$this->data['orden'] = $this->Orden->get($orden_id)->row();
			
			//si la orden es por 1 solo pasajero (no hay acompañantes) voy directo al paso de pagar
			if($this->data['orden']->pasajeros == 1){
				$fecha_completo_paso3 = date('Y-m-d H:i:s');
				$this->Orden->update($orden_id,array('completo_paso2' => 1, 'completo_paso3' => 1, 'fecha_completo_paso3' => $fecha_completo_paso3, 'paso_actual' => 4));
			}
			else{
				$this->Orden->update($orden_id,array('completo_paso2' => 1, 'paso_actual' => 3));
			}
			
			$this->data['orden'] = $this->Orden->get($orden_id)->row();
						
			//si la orden es por 1 solo pasajero (no hay acompañantes) voy directo al paso de pagar
			if($this->data['orden']->pasajeros == 1){
				$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
				$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
				$this->data['hash'] = encriptar($this->data['orden']->code);
				$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
				$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS');
				
				$this->cargar_paso($step=4);
				
				//si no es de confirmacion inmediata, tengo que generar la reserva en estado A CONFIRMAR
				if(!$this->data['orden']->confirmacion_inmediata){
					$reserva = $this->efectivizar_orden($orden_id);
				}
						
				$this->load->model('Paquete_model', 'Paquete');
				$paquete = $this->Paquete->get($this->data['orden']->paquete_id)->row();
				$this->data['paquete'] = $paquete;
				verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
				
				if($paquete->cupo_paquete_personalizado){
					$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
				}
	
				//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
				if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] 
					|| ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
					$this->efectivizar_orden($orden_id);
				}
			
				$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
			}
			else{
				$this->cargar_paso($step=3);
				
				$ret['paso3'] = $this->load->view('checkout_paso3',$this->data,true);
			}
			$ret['status'] = 'success';		
		}
		else{
			$this->form_validation->set_rules('f_nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('f_apellido', 'Apellido', 'required');
			
			//con estos 3 rmo fecha y valido si esta ok o no
			/*
			$this->form_validation->set_rules('f_nacimiento_dia', 'Dia de nacimiento', 'required');
			$this->form_validation->set_rules('f_nacimiento_mes', 'Mes de nacimiento', 'required');
			$this->form_validation->set_rules('f_nacimiento_ano', 'Año de nacimiento', 'required');
			*/
			
			$this->form_validation->set_rules('f_cuit_prefijo', 'CUIT - prefijo', 'required');
			$this->form_validation->set_rules('f_cuit_numero', 'CUIT - número', 'required');
			$this->form_validation->set_rules('f_cuit_sufijo', 'CUIT - sufijo', 'required');
					
			$this->form_validation->set_rules('f_nacionalidad_id', 'Nacionalidad', 'required');
			$this->form_validation->set_rules('f_residencia_id', 'País de Residencia', 'required');
			$this->form_validation->set_rules('f_provincia', 'Provincia de Residencia', 'required');
			$this->form_validation->set_rules('f_ciudad', 'Ciudad de Residencia', 'required');
			$this->form_validation->set_rules('f_domicilio', 'Domicilio - Calle', 'required');
			$this->form_validation->set_rules('f_numero', 'Número', 'required');
			$this->form_validation->set_rules('f_cp', 'Código Postal', 'required');
			
			//terminos y condiciones
			$this->form_validation->set_rules('terminos', 'Términos y condiciones', 'required');
			
			if ($this->form_validation->run() == FALSE){
				$valida = false;
				
				$ret['status'] = 'error';
				$ret['fields'] = array_keys($this->form_validation->error_array());
			}
			else{
				$cuitcompleto = $f_cuit_prefijo.'-'.$f_cuit_numero.'-'.$f_cuit_sufijo;
				if(!isValidCuit($cuitcompleto)){
					$valida = false;
					//retorno cuit invalido
					$ret['status'] = 'error';
					$ret['invalid_cuit'] = true;
					$ret['msg'] = 'El Cuit/Cuil no tiene un formato válido.';
					$ret['fields'] = array('f_cuit_numero');
				}
				else{
					//guardo los datos del paso 2
					$fact = array();
					$fact['orden_id'] = $orden_id;
					$fact['f_nombre'] = $f_nombre;
					$fact['f_apellido'] = $f_apellido;
					//$fact['f_fecha_nacimiento'] = $f_nacimiento_ano.'-'.$f_nacimiento_mes.'-'.$f_nacimiento_dia;
					//este dato ahora no se pide
					//$f_nacimiento = explode('/',$f_fecha_nacimiento);
					//$fact['f_fecha_nacimiento'] = $f_nacimiento[2].'-'.$f_nacimiento[1].'-'.$f_nacimiento[0]; 
					$fact['f_cuit_prefijo'] = $f_cuit_prefijo;
					$fact['f_cuit_numero'] = $f_cuit_numero;
					$fact['f_cuit_sufijo'] = $f_cuit_sufijo;
					$fact['f_cuit_prefijo'] = $f_cuit_prefijo;
					$fact['f_nacionalidad_id'] = $f_nacionalidad_id;
					$fact['f_residencia_id'] = $f_residencia_id;
					$fact['f_provincia'] = $f_provincia;
					$fact['f_ciudad'] = $f_ciudad;
					$fact['f_domicilio'] = $f_domicilio;
					$fact['f_numero'] = $f_numero;
					$fact['f_depto'] = @$f_depto;
					$fact['f_cp'] = $f_cp;
					$fact['timestamp'] = date('Y-m-d H:i:s');
					$fact['ip'] = $_SERVER['REMOTE_ADDR'];
					
					$this->Orden_facturacion->update($fact_id,$fact);
					
					$this->data['orden'] = $this->Orden->get($orden_id)->row();
					
					//si la orden es por 1 solo pasajero (no hay acompañantes) voy directo al paso de pagar
					if($this->data['orden']->pasajeros == 1){
						$fecha_completo_paso3 = date('Y-m-d H:i:s');
						$this->Orden->update($orden_id,array('completo_paso2' => 1, 'completo_paso3' => 1, 'fecha_completo_paso3' => $fecha_completo_paso3, 'paso_actual' => 4));
					}
					else{
						$this->Orden->update($orden_id,array('completo_paso2' => 1, 'paso_actual' => 3));
					}
					
					$this->data['orden'] = $this->Orden->get($orden_id)->row();
					
					$ret['f_nombre'] = $f_nombre;
					$ret['f_apellido'] = $f_apellido;
					
					//si la orden es por 1 solo pasajero (no hay acompañantes) voy directo al paso de pagar
					if($this->data['orden']->pasajeros == 1){
						$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
						$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
						$this->data['hash'] = encriptar($this->data['orden']->code);
						$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
						$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS');
						
						$this->cargar_paso($step=4);
						
						//si no es de confirmacion inmediata, tengo que generar la reserva en estado A CONFIRMAR
						if(!$this->data['orden']->confirmacion_inmediata){
							$reserva = $this->efectivizar_orden($orden_id);
						}
						
						$this->load->model('Paquete_model', 'Paquete');
						$paquete = $this->Paquete->get($this->data['orden']->paquete_id)->row();
						$this->data['paquete'] = $paquete;
						verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
						
						if($paquete->cupo_paquete_personalizado){
							$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
						}

						//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
						if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo']
							|| ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
							$this->efectivizar_orden($orden_id);
						}
				
						$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
					}
					else{
						$this->cargar_paso($step=3);
						
						$ret['paso3'] = $this->load->view('checkout_paso3',$this->data,true);
					}
					$ret['status'] = 'success';			
				}
			}
		}
		
		return $ret;
	}
	
	function validar_paso3(){
		extract($_POST);
		
		$orden = $this->Orden->get($orden_id)->row();

		$this->load->model('Paquete_model', 'Paquete');
		$paquete = $this->Paquete->get($orden->paquete_id)->row();
		$this->data['paquete'] = $paquete;
		

		//si saltea el paso 3 y completa luego, no valido datos
		if(isset($completa_luego) && $completa_luego){
			//actualizo marcas en acompañantes de la orden
			$where = array('orden_id' => $orden_id, 'responsable' => '0');
			$pax = array();
			$pax['salteo'] = '1'; //saltea el paso
			$pax['completo'] = '0'; //falta completar datos
			$pax['timestamp'] = date('Y-m-d H:i:s');
			$pax['ip'] = $_SERVER['REMOTE_ADDR'];
			
			$this->Orden_pasajero->updateWhere($where,$pax);
			
			//update de marca de que salteó el paso 3
			$this->Orden->update($orden_id,array('paso_actual'=>4, 'salteo_paso3'=>1));
			$orden->paso_actual = 4;
			$orden->salteo_paso3 = 1;
			
			$this->data['orden'] = $orden;
			
			$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
			$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
			$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',false,$this->data['orden']->id);
			$this->data['hash'] = encriptar($this->data['orden']->code);
			
			//Si el paquete no tiene confirmacion inmediata se genera la reserva a confirmar
			if (!$paquete->confirmacion_inmediata) {
				$this->efectivizar_orden($orden_id);
			}

			verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
			//$this->verificar_cupo();

			if($paquete->cupo_paquete_personalizado){
				$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
			}

			//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
			if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] || ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
				$this->efectivizar_orden($orden_id);
			}
			
			$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
			$ret['completa_luego'] = true;		
			$ret['status'] = 'success';
		}
		else if(isset($saltear_pax) && $saltear_pax){ //es el ID del acompañante
			//si saltea algun pasajero
			//actualizo marcas en ESE acompañante de la orden
			$pax = array();
			$pax['salteo'] = '1'; //saltea el paso
			$pax['completo'] = '0'; //falta completar datos
			$pax['timestamp'] = date('Y-m-d H:i:s');
			$pax['ip'] = $_SERVER['REMOTE_ADDR'];
			
			$this->Orden_pasajero->update($saltear_pax,$pax);
			
			$pasajero = $this->Orden_pasajero->get($saltear_pax)->row();
			
			//chequeo si todavia hay algun pasajero sin saltear incompleto
			$this->Orden_pasajero->filters = "completo = 0 and salteo = 0 and responsable = 0 and orden_id = ".$orden_id;
			$incompletos = $this->Orden_pasajero->getAll(999,0,'numero_pax','asc')->result();
			
			//chequeo si hay pasajeros salteados sin completar
			$this->Orden_pasajero->filters = "completo = 0 and salteo = 1 and responsable = 0 and orden_id = ".$orden_id;
			$incompletos_salteados = $this->Orden_pasajero->getAll(999,0,'numero_pax','asc')->result();
			
			$this->data['orden'] = $this->Orden->get($orden_id)->row();
			
			//si todavia queda alguno por completar que no haya salteado, cargo el mismo form
			if(count($incompletos)){
				if($orden->pasajeros == $pasajero->numero_pax){
					$this->Orden->update($orden_id,array('paso_actual'=>4, 'completo_paso3'=>0, 'salteo_paso3'=>1));

					$this->data['orden'] = $this->Orden->get($orden_id)->row();
					$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
					$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
					$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',false,$this->data['orden']->id);
					$this->data['hash'] = encriptar($this->data['orden']->code);
					
					//$this->verificar_cupo();
					verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
	
					if($paquete->cupo_paquete_personalizado){
						$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
					}
					
					//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
					if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] || ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
						$this->efectivizar_orden($orden_id);
					}

					$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
				}
				else{
					$this->data['incompletos'] = $incompletos;
					$this->cargar_paso($step=3);
					$ret['paso3'] = $this->load->view('checkout_paso3',$this->data,true);	
				}		
			}
			else{
				//si TODOS los pasajeros (o al menos 1) estan con marca de salteados (no quiso completar) => paso 4 con salteo de paso3
				if(count($incompletos_salteados) <= ($this->data['orden']->pasajeros-1)){
					$this->Orden->update($orden_id,array('paso_actual'=>4, 'completo_paso3'=>0, 'salteo_paso3'=>1));
				}
				else{
					//update de marca de que completo paso 3
					$this->Orden->update($orden_id,array('paso_actual'=>4, 'completo_paso3'=>1, 'salteo_paso3'=>0));
				}
				
				$this->data['orden'] = $this->Orden->get($orden_id)->row();
				$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
				$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
				$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS');
				$this->data['hash'] = encriptar($this->data['orden']->code);
			
				//Si el paquete no tiene confirmacion inmediata se genera la reserva a confirmar
				if (!$paquete->confirmacion_inmediata) {
					$this->efectivizar_orden($orden_id);
				}

				//$this->verificar_cupo();
				verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
				
				if($paquete->cupo_paquete_personalizado){
					$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
				}
					
				//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
				if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] || ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
					$this->efectivizar_orden($orden_id);
				}

				$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
			}
			
			$ret['status'] = 'success';
		}
		else{
			//obtengo el ID de pasajero que voy a actualizar
			$pax_id = isset($grabar_pax) && $grabar_pax ? $grabar_pax : 0;
			
		// error_reporting(E_ALL);
		// ini_set('display_errors', 1);

			if($pax_id > 0){
				$documentaciones = $this->Paquete->getDocumentaciones($orden->paquete_id);
				$pasaporte_obligatorio = in_array(2,$documentaciones);
				//si es nacionalidad extranjero => pido pasaporte obligatorio
				//si es nacionalidad argentina y pasaporte obligatorio por backend => pido pasaporte obligatorio
				//otro caso => NO pido pasaporte			
				$condicion_pasaporte = (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] > 1) 
					|| (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1 && $pasaporte_obligatorio);
		
				//22-08-19 tomo los datos de dni y pasaporte para ver si los completó
				$pax_dni = @$_POST['dni_'.$pax_id];
				$pax_pasaporte = @$_POST['pasaporte_'.$pax_id];
				$es_extranjero = (isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] > 1) ? true : false;

				//no hay ningun dato obligatorio para los acompañantes, pero verificar si el e-mail es e-mail
				if (!empty($_POST['email_'.$pax_id])) {
					$this->form_validation->set_rules('email_'.$pax_id, 'Email', 'valid_email');
					if ($this->form_validation->run() == FALSE){
						$valida = false;
				
						$ret['status'] = 'error';
						$ret['pax_invalid_email'] = true;
						$ret['msg'] = 'El formato del email no es válido.';
						$ret['fields'] = array_keys($this->form_validation->error_array());

						return $ret;
					}
				}
				
				//si es un ACOMPAÑANTE, chequeo que no haya cargado el mismo DNI, EMAIL y CUIT que el RESPONSABLE o los demas acompañantes
				$existe = $this->Orden_pasajero->getWhere(array('bv_ordenes_pasajeros.id != ' => $pax_id, 'bv_ordenes_pasajeros.email'=>$_POST['email_'.$pax_id],'orden_id'=>$orden_id))->result();
				if(count($existe)>0){
					//retorno dato invalido
					$ret['status'] = 'error';
					$ret['pax_invalid_email'] = true;
					$ret['msg'] = 'No puedes usar el mismo Email del responsable o acompañantes.';
					$ret['fields'] = array('email_'.$pax_id);
					echo json_encode($ret);
					exit();
				}


				//dni se pide si es argentino ó extranjero (22-08-19)
				#if(isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id] == 1){
				if(isset($_POST['nacionalidad_id_'.$pax_id]) && $_POST['nacionalidad_id_'.$pax_id]){

					$existe = $this->Orden_pasajero->getWhere(array('bv_ordenes_pasajeros.id != ' => $pax_id, 'bv_ordenes_pasajeros.dni'=>$_POST['dni_'.$pax_id],'orden_id'=>$orden_id))->result();
					
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
				
				//si el pasaporte es obligatorio y se alcanzo la fecha de datos completos, los tengo q validar
				if( $condicion_pasaporte && $orden->fecha_limite_completar_datos <= date('Y-m-d')){

					//22-08-19 si es extranjero y ya completó el dni, no le pido pasaporte
					if(!$pasaporte_obligatorio && $es_extranjero && $pax_dni){
						//no le pido pasaporte
					}
					else{
						if(!$_POST['pasaporte_'.$pax_id]){
							$ret['status'] = 'error';
							if($pasaporte_obligatorio){
								$ret['pax_invalid_pasaporte'] = true;
								$ret['msg'] = 'Debes completar el Pasaporte.';
								$ret['fields'] = array('pasaporte_'.$pax_id);
							}
							else{
								#$ret['msg'] = 'Debe completar Documento y/o Pasaporte.';	
								$ret['error_pasaporte_pax'] = 'Debe completar Documento y/o Pasaporte';
								$ret['error_dni_pax'] = 'Debe completar Documento y/o Pasaporte';
								$ret['pax_id'] = $pax_id;
								$ret['fields'] = array('pasaporte_'.$pax_id, 'dni_'.$pax_id);
							}
							
							echo json_encode($ret);
							exit();
						}
						else{
							$existe = $this->Orden_pasajero->getWhere(array('bv_ordenes_pasajeros.id != ' => $pax_id, 'bv_ordenes_pasajeros.pasaporte'=>$_POST['pasaporte_'.$pax_id],'orden_id'=>$orden_id))->result();
							
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
				}

			  //17-08-18
				//si alcanzÃ³ la fecha limite de carga de datos, TODOS los datos de pax son obligatorios
				if($orden->fecha_limite_completar_datos <= date('Y-m-d ') || $paquete->grupal){
					$this->form_validation->set_rules('nombre_'.$pax_id, 'Nombre', 'required');
					$this->form_validation->set_rules('apellido_'.$pax_id, 'Apellido', 'required');
					
					$this->form_validation->set_rules('nacimiento_dia_'.$pax_id, 'Dia de nacimiento', 'required');
					$this->form_validation->set_rules('nacimiento_mes_'.$pax_id, 'Mes de nacimiento', 'required');
					$this->form_validation->set_rules('nacimiento_ano_'.$pax_id, 'AÃ±o de nacimiento', 'required');
					
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

					if($orden->fecha_limite_completar_datos <= date('Y-m-d ') || $paquete->grupal){
						$this->form_validation->set_rules('emergencia_nombre_'.$pax_id, 'Nombre', 'required');
						$this->form_validation->set_rules('emergencia_telefono_codigo_'.$pax_id, 'Teléfono', 'required');
						$this->form_validation->set_rules('emergencia_telefono_numero_'.$pax_id, 'Teléfono', 'required');
					}

					if( $condicion_pasaporte && $orden->fecha_limite_completar_datos <= date('Y-m-d')){
						//si la nacionalidad elegida es Argentina y tiene pasaporte obligatorio por back => campo obligatorio
						//si la nacionalidad es otra que no sea argentina => campo obligatorio
						
						if($pasaporte_obligatorio){
							$this->form_validation->set_rules('pasaporte_'.$pax_id, 'Pasaporte', 'required');
						}
						else{
							if($es_extranjero && $pax_dni){
								//no le pido pasaporte
							}
							else{
								if($es_extranjero){
									if(!$pax_dni && !$pax_pasaporte){
										$this->form_validation->set_rules('pasaporte_'.$pax_id, 'Pasaporte', 'required');
										$this->form_validation->set_rules('dni_'.$pax_id, 'DNI', 'required');

										$ret['error_pasaporte'] = 'Debe completar Documento y/o Pasaporte';
										$ret['error_dni'] = 'Debe completar Documento y/o Pasaporte';
									}
									
								}
							}	
						}

						//22-08-19 si es extranjero y ya completó el dni, no le pido pasaporte
						if(!$pasaporte_obligatorio && $es_extranjero && $pax_dni){
							//no le pido pasaporte
						}
						else{
							if($es_extranjero){
								$this->form_validation->set_rules('pasaporte_'.$pax_id, 'Pasaporte', 'required');
							}
						}

						$this->form_validation->set_rules('pais_emision_id_'.$pax_id, 'País de emisión', 'required');
						$this->form_validation->set_rules('fecha_emision_dia_'.$pax_id, 'Día de emisión del pasaporte', 'required');
						$this->form_validation->set_rules('fecha_emision_mes_'.$pax_id, 'Mes de emisión del pasaporte', 'required');
						$this->form_validation->set_rules('fecha_emision_ano_'.$pax_id, 'Año de emisión del pasaporte', 'required');
						$this->form_validation->set_rules('fecha_vencimiento_dia_'.$pax_id, 'Día de vencimiento del pasaporte', 'required');
						$this->form_validation->set_rules('fecha_vencimiento_mes_'.$pax_id, 'Mes de vencimiento del pasaporte', 'required');
						$this->form_validation->set_rules('fecha_vencimiento_ano_'.$pax_id, 'Año de vencimiento del pasaporte', 'required');
					}

					$this->form_validation->set_rules('dieta_'.$pax_id, 'Dieta', 'required');
				
					if ($this->form_validation->run() == FALSE){
						$valida = false;
						
						$ret['status'] = 'error';
						$ret['fields'] = array_keys($this->form_validation->error_array());
						echo json_encode($ret);
						exit();
					}
				}
				$pax = array();		
				$pax['nombre'] = @$_POST['nombre_'.$pax_id];
				$pax['apellido'] = @$_POST['apellido_'.$pax_id];
				$pax['fecha_nacimiento'] = @$_POST['nacimiento_ano_'.$pax_id].'-'.@$_POST['nacimiento_mes_'.$pax_id].'-'.@$_POST['nacimiento_dia_'.$pax_id];
				
				/*
				if(@$_POST['nacimiento_'.$pax_id] != ''){
					$nacimiento = explode('/',$_POST['nacimiento_'.$pax_id]);
					$pax['fecha_nacimiento'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
				}
				else{
					$pax['fecha_nacimiento'] = '';
				}
				*/
				
				$pax['sexo'] = @$_POST['sexo_'.$pax_id];
				$pax['email'] = @$_POST['email_'.$pax_id];
				$pax['nacionalidad_id'] = @$_POST['nacionalidad_id_'.$pax_id];
				$pax['dni'] = '';
				if(isset($pax['nacionalidad_id']) && $pax['nacionalidad_id'] == 1){
					$pax['dni'] = @$_POST['dni_'.$pax_id];
					$validar_dni = true;
				}
				else{
					$validar_dni = false;
				}

				$documentaciones = $this->Paquete->getDocumentaciones($orden->paquete_id);
				$pasaporte_obligatorio = in_array(2,$documentaciones);
		
				if($pasaporte_obligatorio){
					$pax['pasaporte'] = @$_POST['pasaporte_'.$pax_id];
					$pax['pais_emision_id'] = @$_POST['pais_emision_id_'.$pax_id];
					//$pax['fecha_emision'] = @$_POST['fecha_emision_ano_'.$pax_id].'-'.@$_POST['fecha_emision_mes_'.$pax_id].'-'.@$_POST['fecha_emision_dia_'.$pax_id];

					$_POST['fecha_emision_'.$pax_id] = @$_POST['fecha_emision_dia_'.$pax_id].'/'.@$_POST['fecha_emision_mes_'.$pax_id].'/'.@$_POST['fecha_emision_ano_'.$pax_id];
					
					if(@$_POST['fecha_emision_'.$pax_id] != ''){
						$nacimiento = explode('/',$_POST['fecha_emision_'.$pax_id]);
						$pax['fecha_emision'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
					}
					else{
						$pax['fecha_emision'] = '';
					}
					
					//$pax['fecha_vencimiento'] = @$_POST['fecha_vencimiento_ano_'.$pax_id].'-'.@$_POST['fecha_vencimiento_mes_'.$pax_id].'-'.@$_POST['fecha_vencimiento_dia_'.$pax_id];

					$_POST['fecha_vencimiento_'.$pax_id] = @$_POST['fecha_vencimiento_dia_'.$pax_id].'/'.@$_POST['fecha_vencimiento_mes_'.$pax_id].'/'.@$_POST['fecha_vencimiento_ano_'.$pax_id];
					
					if(@$_POST['fecha_vencimiento_'.$pax_id] != ''){
						$nacimiento = explode('/',$_POST['fecha_vencimiento_'.$pax_id]);
						$pax['fecha_vencimiento'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
					}
					else{
						$pax['fecha_vencimiento'] = '';
					}
				}

				$pax['celular_codigo'] = @$_POST['celular_codigo_'.$pax_id];
				$pax['celular_numero'] = @$_POST['celular_numero_'.$pax_id];
				$pax['dieta'] = @$_POST['dieta_'.$pax_id];
				$pax['emergencia_nombre'] = @$_POST['emergencia_nombre_'.$pax_id];
				$pax['emergencia_telefono_codigo'] = @$_POST['emergencia_telefono_codigo_'.$pax_id];
				$pax['emergencia_telefono_numero'] = @$_POST['emergencia_telefono_numero_'.$pax_id];
				$pax['timestamp'] = date('Y-m-d H:i:s');
				$pax['ip'] = $_SERVER['REMOTE_ADDR'];
				
				/*
				//chequeo si está todo completo o no (todos o los obligatorios) ?
				if(	@$pax['nombre'] != '' && @$pax['apellido'] != '' && @$pax['fecha_nacimiento'] != '' && 
					@$pax['sexo'] != '' && (@$pax['dni'] != '' || !$validar_dni) && @$pax['email'] != '' && @$pax['nacionalidad_id'] != '' && @$pax['pasaporte'] != '' && 
					@$pax['pais_emision_id'] != '' && @$pax['fecha_emision'] != '' && 
					@$pax['fecha_vencimiento'] != '' && @$pax['celular_codigo'] != '' && @$pax['celular_numero'] != '' && 
					@$pax['emergencia_nombre'] != '' && @$pax['emergencia_telefono_codigo'] != '' && @$pax['emergencia_telefono_numero'] != '' 
				){
					$pax['completo'] = '1';
				}
				else{
					$pax['completo'] = '0';
				}
				*/

				if( !$validar_dni ){
					unset($pax['dni']);
				}
				$pax['completo'] = 1;						
				foreach ($pax as $key=>$value) {
					if (empty($pax[$key])) {
						$pax['completo'] = 0;
						break;
					}
				}
				$pax['salteo'] = '0';//no saltea el paso, actualiza lo que desea
				
				//02-09-19 si el pasaporte no era obligatorio, igualmente si los completó lo actualizao
				if(!$pasaporte_obligatorio){
					$pax['dni'] = @$_POST['dni_'.$pax_id];
					$pax['pasaporte'] = @$_POST['pasaporte_'.$pax_id];
					$pax['pais_emision_id'] = @$_POST['pais_emision_id_'.$pax_id];
					//$pax['fecha_emision'] = @$_POST['fecha_emision_ano_'.$pax_id].'-'.@$_POST['fecha_emision_mes_'.$pax_id].'-'.@$_POST['fecha_emision_dia_'.$pax_id];

					$_POST['fecha_emision_'.$pax_id] = @$_POST['fecha_emision_dia_'.$pax_id].'/'.@$_POST['fecha_emision_mes_'.$pax_id].'/'.@$_POST['fecha_emision_ano_'.$pax_id];
					
					if(@$_POST['fecha_emision_'.$pax_id] != ''){
						$nacimiento = explode('/',$_POST['fecha_emision_'.$pax_id]);
						$pax['fecha_emision'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
					}
					else{
						$pax['fecha_emision'] = '';
					}
					
					//$pax['fecha_vencimiento'] = @$_POST['fecha_vencimiento_ano_'.$pax_id].'-'.@$_POST['fecha_vencimiento_mes_'.$pax_id].'-'.@$_POST['fecha_vencimiento_dia_'.$pax_id];

					$_POST['fecha_vencimiento_'.$pax_id] = @$_POST['fecha_vencimiento_dia_'.$pax_id].'/'.@$_POST['fecha_vencimiento_mes_'.$pax_id].'/'.@$_POST['fecha_vencimiento_ano_'.$pax_id];
					
					if(@$_POST['fecha_vencimiento_'.$pax_id] != ''){
						$nacimiento = explode('/',$_POST['fecha_vencimiento_'.$pax_id]);
						$pax['fecha_vencimiento'] = $nacimiento[2].'-'.$nacimiento[1].'-'.$nacimiento[0];
					}
					else{
						$pax['fecha_vencimiento'] = '';
					}
				}

				$this->Orden_pasajero->update($pax_id,$pax);
				
				$pasajero = $this->Orden_pasajero->get($pax_id)->row();
				
				//chequeo si todavia hay alguno incompleto no salteado
				$this->Orden_pasajero->filters = "completo = 0 and salteo = 0 and responsable = 0 and orden_id = ".$orden_id;
				$incompletos = $this->Orden_pasajero->getAll(999,0,'numero_pax','asc')->result();
				
				//si todavia queda alguno por completar que no haya salteado, dejo el mismo form
				if(count($incompletos)){
					
					//si acabo de guardar datos del ultimo pasajero y hay incompletos todavia, simulo que salteo paso 3

					if($orden->pasajeros == $pasajero->numero_pax){
						//sólo salteo al paso 4 si la reserva no es grupal o si no hay limite con la fecha de completitud de datos
						
						if($orden->fecha_limite_completar_datos <= date('Y-m-d ') || $paquete->grupal){
							$this->data['orden'] = $this->Orden->get($orden->id)->row();
							$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
							$this->cargar_paso($step=3);
							$ret['completar_paso3'] = true;	
							$ret['paso3'] = $this->load->view('checkout_paso3',$this->data,true);
						}
						else{

							$this->Orden->update($orden_id,array('paso_actual'=>4, 'completo_paso3'=>0, 'salteo_paso3'=>1));

							$this->data['orden'] = $this->Orden->get($orden_id)->row();
							$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);
							$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
							$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',false,$this->data['orden']->id);
							$this->data['hash'] = encriptar($this->data['orden']->code);
							
							//Si el paquete no tiene confirmacion inmediata se genera la reserva a confirmar
							if (!$paquete->confirmacion_inmediata) {
								$this->efectivizar_orden($orden_id);
							}

							//$this->verificar_cupo();
							verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
							
							if($paquete->cupo_paquete_personalizado){
								$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
							}
							
							//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
							if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] || ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
								$this->efectivizar_orden($orden_id);
							}

							$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
						}
					}

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
			else{
				//click en boton confirmar datos
				//valido que haya tildado los terminos
				$this->form_validation->set_rules('terminos_pax', 'Términos y condiciones', 'required');
				if ($this->form_validation->run() == FALSE){
					
					$ret['status'] = 'error';
					$ret['fields'] = array_keys($this->form_validation->error_array());
				}
				else{
					//actualizo orden
					$fecha_completo_paso3 = date('Y-m-d H:i:s');
					$this->Orden->update($orden_id,array('fecha_completo_paso3' => $fecha_completo_paso3, 'completo_paso3' => 1, 'paso_actual' => 4));
					$this->data['orden'] = $this->Orden->get($orden_id)->row();
			
					$this->data['combinacion'] = $this->Combinacion->get($this->data['orden']->combinacion_id)->row();
					$this->data['hash'] = encriptar($this->data['orden']->code);
					$this->data['orden']->adicionales = $this->adicionales_orden($this->data['orden']);			
					$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS');
					
					//Si el paquete no tiene confirmacion inmediata se genera la reserva a confirmar
					if (!$paquete->confirmacion_inmediata) {
						$this->efectivizar_orden($orden_id);
					}

					//$this->verificar_cupo();
					verificar_cupo($this->data['combinacion'],$this->data['orden'],$this->data['paquete']);
					
					if($paquete->cupo_paquete_personalizado){
						$paquete->cupo_disponible = $paquete->cupo_paquete_disponible_real;
					}
					
					//ó si entro como lista de espera tambien la efectivizo ahora porqe en paso 4 no hay nada por hacer
					if (($this->data['combinacion']->agotada && $this->data['combinacion']->habitacion_id!=99) || !$paquete->cupo_disponible || @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo'] || ($paquete->cupo_paquete_disponible < $this->data['orden']->pasajeros)) {
						$this->efectivizar_orden($orden_id);
					}

					$ret['status'] = 'success';
					$ret['paso4'] = $this->load->view('checkout_paso4',$this->data,true);
				}
			}
		}
		
		return $ret;
	}
	
	//aca clickeó en boton de Mercado Pago, pero sin saber si pagó o no
	//le decremento el cupo al paquete y la transformo en reserva efectiva
	function validar_paso4(){
		extract($_POST);
		
		$orden = $this->Orden->get($orden_id)->row();
		$combinacion = $this->Combinacion->get($orden->combinacion_id)->row();		
		$orden->adicionales = $this->Orden->getAdicionales($orden->id);
		$adicionales_valores = array();
		foreach($orden->adicionales as $a){
			$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
		}
		$precios = montos_numericos($combinacion,$adicionales_valores,false);
		$ret['hash'] = $hash;
		$ret['orden_id'] = $orden_id;
		
		
		//aca clickeó en boton de Mercado Pago, pero sin saber si pagó o no
		//si todo esta ok, le decremento el cupo al paquete y la transformo en reserva efectiva
			
		//transformo la orden en reserva efectiva
		$reserva = $this->efectivizar_orden($orden_id);
		
		//piso nuevo hash por el de la reserva
		$ret['hash'] = encriptar($reserva->code);
		$reserva_id = $reserva->id;
		
		$ret['reserva_id'] = $reserva_id;
		
		//redefino los precios
		$precios = montos_numericos($combinacion,$adicionales_valores,false, $reserva_id);
		
		$user_mediopago = isset($user_mediopago) && $user_mediopago ? $user_mediopago : 'MP';
		//aca tengo que recalcular el precio del viaje segun el medio de pago que haya elegido y la moneda del viaje
		$precios_pagar = recalcular_precios($precios,$moneda,$user_mediopago);

		//pago total
		if( $pago == "total" ){
			$monto = $precios['precio_total'];
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
		else{
			$minimo = $precios['monto_minimo_reserva'];

			//pago parcial
			if($monto == "" || $monto <= 0){
				$ret['msg'] = "El monto deber ser mayor que 0 (cero).";
				$ret['status'] = "error";
				echo json_encode($ret);
			}
			else if($monto < $minimo){
				$ret['msg'] = "El monto mínimo de pago es ".$minimo;
				$ret['status'] = "error";
				echo json_encode($ret);
			}
			else{
				$saldo_pendiente = $precios['saldo_pendiente'];
				//no puede querer pagar mas que lo que le queda
				if( $monto > $saldo_pendiente ){
					$ret['msg'] = "El saldo pendiente del viaje es de ".$saldo_pendiente;
					$ret['status'] = "error";
					echo json_encode($ret);
				}
				else{
					$ret['monto'] = $monto;
					$ret['status'] = "success";
					//todo ok con pago parcial
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
		
		//return $ret;
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
	
	
	//obtengo link a mercado pago con la data necesaria
	function getLinkPaypal($data){
		if($data['status'] == 'success'){			
			$data['redirect'] = get_link_pp($data);
			
			echo json_encode($data);
		}
		else{
			echo json_encode($data);
		}		
	}
	
	/* transforma la orden en reserva y decrementa cupo */
	function efectivizar_orden($orden_id){
		$orden = $this->Orden->get($orden_id)->row();			
		$reserva = generar_reserva($orden);
		
		return $reserva;
	}
		
	/* clickeó en INFORMAR TRANSFERENCIA */
	function informar_transferencia($hash){
		$this->data['hash'] = $hash;
		
		//devuelve codigo de orden + md5 (SI está OK el hash, sino FALSE)
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$orden = $this->Orden->getWhere(array('code' => $data[0]))->row();
			
			if($orden->id){
				$reserva = $this->efectivizar_orden($orden->id);
				$this->data['reserva'] = $reserva;
				
				$this->data['hash_reserva'] = encriptar($reserva->code);
				
				//el pago lo va a hacer desde reservas
				redirect(site_url('reservas/informar_transferencia/'.$this->data['hash_reserva']));
				//$this->render('informar_transferencia');
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
	aca clickeó en boton de PAGAR LUEGO
	*/
	function pagar_luego(){
		extract($_POST);
		
		$orden = $this->Orden->get($orden_id)->row();

		$reserva = $this->efectivizar_orden($orden_id);
		$hash = encriptar($reserva->code);
		
		$ret['status'] = 'success';
		$ret['next'] = site_url('reservas/confirmacion/'.$hash);
		
		echo json_encode($ret);
	}

	function testmax(){
		$q = "select * from bv_ordenes where paquete_id =251 and completo_paso3 =1 and id not in (select orden_id from bv_reservas where paquete_id =251) order by id desc";
		$res = $this->db->query($q)->result();

		pre($res);

		foreach ($res as $r) {
			#$this->efectivizar_orden($r->orden_id);			
		}
	}

	
}
