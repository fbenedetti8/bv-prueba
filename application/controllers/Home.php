<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		
		$this->data['page'] = 'home';

	}

	function caca(){
		$str = '[{"id_clients":485},{"id_clients":190}]';

		$arra = json_decode($str);
		pre($arra);

		$arr = [
				['id_clients' => 485],
				['id_clients' => 190]
		];

		pre($arr);

		$json = json_encode($arr);
		echo $json;
	}

	function ver(){
		$array = array(  
			array( 'id' => '0', 'question' => 'preg1', 'explanation' => 'fun1', 'correct' => 1, 'resp' => 'op1' ),  
			array( 'id' => '0', 'question' => 'preg2', 'explanation' => 'fun2', 'correct' => '0', 'resp' => 'op2' ),  
			array( 'id' => '0', 'question' => 'preg3', 'explanation' => 'fun3', 'correct' => '0', 'resp' => 'op3' ),  
			array( 'id' => 1, 'question' => 'preg1', 'explanation' => 'fun1', 'correct' => '0', 'resp' => 'op1' ),  
			array( 'id' => 1, 'question' => 'preg2', 'explanation' => 'fun1', 'correct' => '0', 'resp' => 'op1' ),  
			array( 'id' => 1, 'question' => 'preg3', 'explanation' => 'fun1', 'correct' => 1, 'resp' => 'op1' ) 
		);

		/*$array = array(
					array('id'=> 1, 'pregunta' => 'pregunta 1', 'respuestas'=> 'caca2'),
					array('id'=> 2, 'pregunta' => 'pregunta 2','respuestas'=> 'sorete'),
					array('id'=> 1, 'pregunta' => 'pregunta 1','respuestas'=> 'caca55'),
					array('id'=> 2, 'pregunta' => 'pregunta 2', 'respuestas'=> 'soreteeeee'),
					array('id'=> 1, 'pregunta' => 'pregunta 1', 'respuestas'=> 'caca6665')
				);*/

		$arr_final = array();
		foreach($array as $a){
			$arr_final[$a['id']]['pregunta'][$a['id']] = $a['question'];
			$arr_final[$a['id']]['respuestas'][] = $a['resp'];
		}

		pre($arr_final);
	}

	function onBeforeRender(){
		$this->data['medios_de_pago'] = $this->load->view('medios_de_pago',$this->data,true);
	}

	public function index()
	{
		$this->data['body_id'] = 'home';
		$this->data['body_class'] = 'with-bg';
		
		
		$this->load->model('Destino_model', 'Destino');
		$destinos = $this->Destino->getDestacadosHome();	

		$this->load->model('Estacionales_model', 'Estacional');	
		foreach ($destinos as $d) {
			//data de las estacioneles de dicho destino
			$d->cat_estacionales = [];
			$d->cat_estacionales = $this->Estacional->getListDelDestino($d->id,false,$home=true);
		}
		$this->data['destinos'] = $destinos;

		//MODULO OFICINA FELIZ DINAMICO POR BACKEND
		//SE habilita el viernes 14/12
		$this->data['oficinas'] = [];
		$this->load->model('Oficina_model', 'Oficina');
		$this->Oficina->filters = "visible = 1";
		$this->data['oficinas'] = $this->Oficina->getAll(6,0,'orden','asc')->result();
		
		//16-04-19 carga el video o imagen destacada configurada por backend
		$this->load->model('Destacado_model','Destacado');
		$this->Destacado->filters = "visible = 1";
		$this->data['destacado'] = $this->Destacado->getAll(1,0,'id','desc')->row();

		//inicializa los filtros del buscador, tambien se usaran en la pagina de proximos viajes
		$this->setup_filtros();
		
		$this->data['buscador'] = $this->load->view('buscador',$this->data,true);

		$this->load->model('Testimonio_model','Testimonio');
		$this->data['testimonios'] = $this->Testimonio->getAll(999,0,'orden','asc')->result();

		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('main.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('../bootstrap/js/bootstrap.min.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('main.js');
		$this->carabiner->js('sliders.js');
		$this->carabiner->js('gallery.js');

		$this->render('home');
	}
	
	function privacidad() {
		$this->render('privacidad');		
	}

	function tete() {
		enviarMail("reservas@buenas-vibras.com.ar","dlobalzo@gmail.com","","Test","Prueba","Buenas Vibras","", "reservas@buenas-vibras.com.ar");
	}

	function max(){
		$s = eliminar_tildes("Florianópolis es el Mejor ViajeñÑ");
		echo url_title($s,'-',true);
	}

	function certificado_virtual() {
		header("Content-type:application/pdf");

		$this->load->library('aws_s3');

		$certificado = 'certificado-habilitacion-local-virtual.pdf';
		$data = $this->aws_s3->get_s3_object($certificado);
		echo $data['Body'];
	}

	function terminos_y_condiciones() {
		header("Content-type:application/pdf");

		$this->load->library('aws_s3');

		$terminos_y_condiciones = 'terminos_y_condiciones-202109.pdf';
		$data = $this->aws_s3->get_s3_object($terminos_y_condiciones);
		echo $data['Body'];
	}

}