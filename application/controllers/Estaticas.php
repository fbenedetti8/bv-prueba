<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estaticas extends MY_Controller{

	public function __construct (){
		parent::__construct();
	}
	
	public function index(){
		$this->contacto();
	}
	
	//nueva seccion solas y solos
	public function viajes_solas_y_solos(){
		$this->seo_title = "Viajes de Solas y Solos - Agencia Buenas Vibras";
		$this->seo_description = "Buenas Vibras es la agencia de Viajes para solo y solas número uno del país. Nuestros viajes de solos/solas en 2016 se extienden por toda la Argentina, Brasil, Perú, y hasta las playas de Ecuador. ¡Aprovecha también las escapadas de fin de semana!";
		$this->seo_keywords = "solas y solos, viaje de solas y solos, viaje de solos, viaje de solas, viajar solo, viajar sola, viajes para solos, viajes para solas.";
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		
		$this->load_phone_site();
		
		$this->data['contacto_subseccion'] = $this->load->view('contacto_subseccion',$this->data,true);
		$this->data['body_id'] = 'viajes_solas_y_solos';		
		
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('sliders.js');
		$this->carabiner->js('main.js');

		$this->load->model('Solasolo_model');
		$this->Solasolo_model->filters = "visible = 1";
		$this->data['imagenes'] = $this->Solasolo_model->getAll(999,0,'orden','asc')->result();

		$this->render('viajes_solas_y_solos');
	}

	public function quienes_somos(){
		$this->data['body_id'] = 'quienes_somos';
		$this->data['body_class'] = '';

		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('estilos.css');
		$this->carabiner->css('media-queries.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('sliders.js');
		$this->carabiner->js('main.js');
		
		$this->seo_title = 'Buenas Vibras Viajes - Quiénes Somos';

		$this->render('quienes_somos');
	}
	
	public function como_reservar(){
		$this->data['body_id'] = 'como_reservar';
		
		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('main.js');

		$this->seo_title = 'Buenas Vibras Viajes - Cómo Reservar';

		$this->render('como_reservar');
	}

	public function faqs(){
		$this->data['body_id'] = 'faq';
		$this->data['body_class'] = '';

		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('main.js');

		$this->load->model('Preguntas_frecuentes_model', 'Preg');
		$preguntas = $this->Preg->getPreguntas();

		$categorias = [];
		foreach ($preguntas as $p) {
			$categorias[$p->categoria]['preguntas'][] = $p;
		}

		$this->data['categorias'] = $categorias;

		$this->load->model('Pais_model');
		$this->data['paises'] = $this->Pais_model->getAll(999,0,'nombre','asc')->result();

		$this->seo_title = 'Buenas Vibras Viajes - Preguntas Frecuentes';

		$this->render('faq');
	}

	public function contacto(){
		$this->data['body_id'] = 'contacto';

		$this->load_phone_site();

		$this->data['contacto_subseccion'] = $this->load->view('contacto_subseccion',$this->data,true);


		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('main.js');

		$this->seo_title = 'Buenas Vibras Viajes - Contacto';

		$this->render('contacto');
	}

	//La usa internamente el metodo validar_datos()
	public function validate_recaptcha(){
		$this->load->library('ReCaptchaLib');
		$rc = $this->recaptchalib->init();
		
		// Obtener el token enviado del form (siempre es el mismo NAME)
		if(isset($_POST['g-recaptcha-response'])){
			$captcha = $_POST['g-recaptcha-response'];
		}
		
		if(isset($captcha)){
			$response = $rc->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$captcha
			);
		}

		// En caso de que "$response" no sea NULL y "success" sea TRUE....
		if($response != null && $response->success) {
			// ACCIONES EN CASO DE ÉXITO
			return true;

		}else{
			// ACCIONES EN CASO DE ERROR
			return false;
		}
	}	

	//valida datos de form de CONTACTO que es el mismo en Seccion Contacto y Solos y Solas
	public function validar_datos(){
		extract($_POST);

		$ret = array();
		
		$ret['status'] = 'ERROR';

		if( !isset($nombre) || $nombre == '' || !isset($email) || $email == '' || !isset($asunto) || $asunto == '' || !isset($sucursal_id) || $sucursal_id == '' || !isset($consulta) || $consulta == ''){ 
			$ret['msg'] = 'Debes completar los campos obligatorios.';
			$ret['errors'] = [];
			if (empty($nombre)) $ret['errors'][] = 'nombre';
			if (empty($email)) $ret['errors'][] = 'email';
			if (empty($asunto)) $ret['errors'][] = 'asunto';
			if (empty($sucursal_id)) $ret['errors'][] = 'sucursal_id';
			if (empty($consulta)) $ret['errors'][] = 'consulta';
		}
		else{
			if(!isValidEmail($email)){
				$ret['msg'] = 'El formato de email ingresado no es valido.';
				$ret['errors'] = [];
				$ret['errors'][] = 'email';
			}
			else{
				//validacion de captcha
				if(!$this->validate_recaptcha()){
					$ret['msg'] = 'Debes verificar que no eres un robot.';
				}
				else{
					//$this->config->item('google_recaptcha_secret');
					if($this->session->userdata('captcha_word') != @$captcha){
						$ret['msg'] = 'El captcha ingresado no es valido.';
					}
					else{
						$data = array();
						$data["fecha"] = date("Y-m-d H:i:s");
						$data["ip"] = $_SERVER['REMOTE_ADDR'];
			
						foreach($_POST as $key=>$val){
							$data[$key] = $val;
						}
						
						$this->load->model('Contacto_model','Contacto');
						$this->Contacto->insert($data);
						$this->load->model('Lugar_salida_model','Sucursal');
						$data['sucursal'] = $this->Sucursal->get($sucursal_id)->row();
						
						//enviar por mail el contacto a BBV
						$email_from = $this->config->item('email_from');
						$emailbbv = $this->settings->email_contacto;
						$mensaje = $this->load->view("mails/contacto",$data,true);
						
						$data['asunto'] = $data['sucursal']->nombre.' - '.$data['asunto'];
						
						//identifico si es contacto de agencia
						if( isset($agencia) && $agencia ){
							$emailbbv = $this->settings->email_agencias;
						}

						enviarMail($data['email'],$emailbbv,'',$data['asunto'],$mensaje,$nombre);
						
						//enviar por mail el contacto al usuario (copia)
						enviarMail($email_from,$data['email'],'',$data['asunto'],$mensaje,"BUENAS VIBRAS VIAJES");
						
						$ret['msg'] = 'Gracias por contactarse con nosotros';
						$ret['status'] = 'OK';
					}
				}
			}
		}
		
		echo json_encode($ret);
	}

	//valida datos de form de reguntas frecuentes
	public function validar_form_faqs(){
		extract($_POST);

		$ret = array();
		
		$ret['status'] = 'ERROR';

		if( !isset($pais) || $pais == '' || !isset($email) || $email == '' || !isset($comentario) || $comentario == ''){ 
			$ret['msg'] = 'Debes completar todos los campos.';
			$ret['errors'] = [];
			if (empty($pais)) $ret['errors'][] = 'pais';
			if (empty($email)) $ret['errors'][] = 'email';
			if (empty($comentario)) $ret['errors'][] = 'comentario';
		}
		else{
			if(!isValidEmail($email)){
				$ret['msg'] = 'El formato de email ingresado no es valido.';
				$ret['errors'] = [];
				$ret['errors'][] = 'email';
			}
			else{
				//validacion de captcha
				if(!$this->validate_recaptcha()){
					$ret['msg'] = 'Debes verificar que no eres un robot.';
				}
				else{
					//$this->config->item('google_recaptcha_secret');
					if($this->session->userdata('captcha_word') != @$captcha){
						$ret['msg'] = 'El captcha ingresado no es valido.';
					}
					else{
						$data = array();
						$data["fecha"] = date("Y-m-d H:i:s");
						$data["ip"] = $_SERVER['REMOTE_ADDR'];
			
						foreach($_POST as $key=>$val){
							$data[$key] = $val;
						}
						
						$data['asunto'] = "Contacto de Preguntas Frecuentes";
						$data['faqs'] = "1";
						$data['pais'] = $pais;
						$data['comentario'] = $comentario;

						$this->load->model('Contacto_model','Contacto');
						$this->Contacto->insert($data);
						
						//enviar por mail el contacto a BBV
						$email_from = $this->config->item('email_from');
						$emailbbv = $this->settings->email_contacto;
						$mensaje = $this->load->view("mails/contacto",$data,true);
						
						enviarMail($data['email'],$emailbbv,'',$data['asunto'],$mensaje,$nombre);
						
						//enviar por mail el contacto al usuario (copia)
						enviarMail($email_from,$data['email'],'',$data['asunto'],$mensaje,"BUENAS VIBRAS VIAJES");
						
						$ret['msg'] = 'Gracias por contactarse con nosotros';
						$ret['status'] = 'OK';
					}
				}
			}
		}
		
		echo json_encode($ret);
	}
	
	public function privacidad(){
		$this->data['body_id'] = 'quienes_somos';
		$this->data['body_class'] = '';

		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('estilos.css');
		$this->carabiner->css('media-queries.css');
		$this->carabiner->css('app.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('sliders.js');
		$this->carabiner->js('main.js');
		
		$this->render('privacidad');
	}

}
