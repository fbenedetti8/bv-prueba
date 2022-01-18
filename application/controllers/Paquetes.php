<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paquetes extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Orden_model','Orden');
		$this->load->model('Orden_pasajero_model','Orden_pasajero');
		$this->load->model('Orden_facturacion_model','Orden_facturacion');
		$this->load->model('Habitacion_model','Habitacion');
		$this->load->model("MP_model","MP");
		$this->load_phone_site();
	}

	public function index(){
	}
	
	function onBeforeRender(){
		$this->data['medios_de_pago'] = $this->load->view('medios_de_pago',$this->data,true);
	}
	
	//carga paquete
	function ver($slug){
		

		$this->data['detalle_visible'] = 0;
		
		//data del paquete
		$paquete = $this->Paquete->getBySlug($slug);

		if($paquete->cupo_paquete_personalizado): 
			$paquete->cupo_disponible = $paquete->cupo_paquete_disponible;
			$paquete->cupo_total = $paquete->cupo_paquete_total;
		endif; 

		$force_le = false;
		if(date('Y-m-d') > $paquete->fecha_inicio){
				$paquete->cupo_disponible = 0;	
				$force_le = true;

		}

		$paquete->cat_estacionales = $this->Paquete->getDataEstacionales($paquete->id);

		$this->data['paquete'] = $paquete;

		//caracteristicas del destino
		$this->data['caracteristicas'] = $this->Paquete->getDataCaracteristicas($this->data['paquete']->id);
		
		//medios de pago del destino
		$this->data['medios'] = $this->Paquete->getDataMedios($this->data['paquete']->id);
		
		//promociones del destino
		$this->data['promociones'] = $this->Paquete->getDataPromociones($this->data['paquete']->id);

		//documentacion requerida del destino
		$this->data['documentaciones'] = $this->Paquete->getDataDocumentaciones($this->data['paquete']->id);
		
		//excursiones del destino
		$this->data['excursiones'] = $this->Paquete->getDataExcursiones($this->data['paquete']->id);
		
		//documentaciones del paquete
		$this->data['documentaciones'] = $this->Paquete->getDataDocumentaciones($this->data['paquete']->id);
		
		//Otros destinos, de la misma categoria, que no sean el destino actual
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Estacionales_model', 'Estacional');
		//muestro hasta 10 recomendados
		$destinos = $this->Destino->getRecomendados($this->data['paquete']->categoria_id,$this->data['paquete']->destino_id,10);
		foreach ($destinos as $d) {
			//data de las estacioneles de dicho destino
			$d->cat_estacionales = [];
			$d->cat_estacionales = $this->Estacional->getListDelDestino($d->id);
		}
		$this->data['otros_destinos'] = $destinos;

		$this->data['destinos_recomendados'] = $this->load->view('destinos_recomendados',$this->data,true);

		//obtengo combinacion con menor precio
		$combinaciones = $this->Combinacion->getByPaquete($this->data['paquete']->id,$limit=50);

		//busco la primer combinacion que no este agotada
		$combinacion = FALSE;
		foreach ($combinaciones as $c) {
			//la primera q no este agorada o que sea compartida
			if (!$c->agotada || $c->habitacion_id == 99) {
				$combinacion = $c;
				break;
			}
		}
		if (!$combinacion) {
			$combinacion = isset($combinaciones[0]) ? $combinaciones[0] : FALSE;
		}

		$this->data['combinacion'] = $combinacion;

		//cargo las fotos del destino
		$this->load->model('Destino_foto_model','Destino_foto');
		$this->data['fotos'] = $this->Destino_foto->getWhere(array('destino_id'=>$this->data['paquete']->destino_id))->result();	
			
		$this->data['destino_galeria'] = $this->load->view('destino_galeria',$this->data,true);

		//si hay combinacion cargo interna paquete ó no está forzada la lista de espera
		if(!$force_le && isset($this->data['combinacion']->id) && $this->data['combinacion']->id){
		
			$filters = array();
			$filters['field'] = '';
			$filters['paquete_id'] = $this->data['combinacion']->paquete_id;
			$filters['pax'] = $this->data['combinacion']->pax;
			$filters['lugar_salida'] = $this->data['combinacion']->lugar_id;
			//fecha_id ahroa se usa como rango
			//$filters['fecha_id'] = $this->data['combinacion']->fecha_alojamiento_id;
			$filters['fecha_id'] = $this->data['combinacion']->fecha_checkin.'|'.$this->data['combinacion']->fecha_checkout;
			$filters['fecha'] = '';
			$filters['alojamiento'] = $this->data['combinacion']->alojamiento_id;
			$filters['habitacion'] = $this->data['combinacion']->habitacion_id;
			$filters['pension'] = $this->data['combinacion']->paquete_regimen_id;
			$filters['transporte'] = $this->data['combinacion']->fecha_transporte_id;
			
			$data = $this->cargar_forms($filters);
			
			$this->data['form_salidas'] = $data['form_salidas'];
			$this->data['form_alojamientos'] = $data['form_alojamientos'];
			$this->data['form_transportes'] = $data['form_transportes'];
			$this->data['form_adicionales'] = $data['form_adicionales'];
			$this->data['detalle_calculador'] = $data['detalle_calculador'];
			
			$this->data['destino'] = $this->Destino->get($this->data['paquete']->destino_id)->row();
			
			//SEO
			$this->seo_title = $this->data['paquete']->nombre;
			$this->seo_description = strip_tags($this->data['paquete']->descripcion);
			$this->seo_keywords = '';
			$this->seo_image = base_url().'media/assets/images/img/share-web.png';
			if(@$this->data['destino']->imagen && file_exists('./uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen))
				$this->seo_image = base_url().'uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen;
			
			$this->data['body_id'] = 'interna_paquete';
			$this->data['body_class'] = '';
			
			$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
			$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
			$this->carabiner->css('../fancybox/jquery.fancybox.min.css');
			$this->carabiner->css('../selectric/selectric.css');
			$this->carabiner->css('app.css');

			$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
			$this->carabiner->js('../selectric/selectric.js');
			$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
			$this->carabiner->js('../fancybox/jquery.fancybox.min.js');
			$this->carabiner->js('sliders.js');
			$this->carabiner->js('sticky.js');
			$this->carabiner->js('swipes.js');
			$this->carabiner->js('main.js');
		
			$this->render('paquete');
		}
		else{
			/*$this->data['body_id'] = 'checkout';
			$this->data['body_class'] = 'lista_de_espera_proximamente';*/

			$this->data['body_id'] = 'interna_paquete';
			$this->data['body_class'] = 'lista_espera';
			
			$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
			$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
			$this->carabiner->css('../fancybox/jquery.fancybox.min.css');
			$this->carabiner->css('../selectric/selectric.css');
			$this->carabiner->css('app.css');

			$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
			$this->carabiner->js('../selectric/selectric.js');
			$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
			$this->carabiner->js('../fancybox/jquery.fancybox.min.js');
			$this->carabiner->js('sliders.js');
			$this->carabiner->js('swipes.js');
			$this->carabiner->js('main.js');
			
			$this->data['destino'] = $this->Destino->get($this->data['paquete']->destino_id)->row();
						
			//SEO
			$this->seo_title = $this->data['paquete']->nombre;
			$this->seo_description = strip_tags($this->data['paquete']->descripcion);
			$this->seo_keywords = '';
			$this->seo_image = base_url().'media/assets/images/img/share-web.png';
			if(isset($this->data['destino']->imagen) && $this->data['destino']->imagen != '' && file_exists('./uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen))
				$this->seo_image = base_url().'uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen;
			
			
			//si no hay combinacion cargo lista_de_espera_proximamente
			$this->render('lista_de_espera_proximamente');
		}
	}

	//metodo ajax que devuelve combinacion con todos los parametros establecidos
	function obtener_combinacion(){
		extract($_POST);
		
		/*
		paquete_id: 41
		field: habitacion
		detalle_visible: 0
		aside_mobile: 0
		zocalo_mobile: 0
		pasajeros: 2
		lugar_salida: 5
		fecha_id: 2018-05-24|2018-05-31
		fecha_aloj_id: 2018-05-25|2018-05-31
		alojamiento: 4
		habitacion: 2
		pension: 69
		transporte: 30
		forma_pago: 
		metodo_pago: 
		cuotas: 
		tipo_moneda: ARS
		forma_pago: 
		metodo_pago: 
		cuotas: 
		tipo_moneda_m: ARS
		combinacion_id: 367
		paquete_id: 41
		paquete_precio: 38975.20
		impuestos: 624.80
		adicionales_precio: 0
		*/

		if(isset($_POST['fecha_aloj_id']) && $_POST['fecha_aloj_id']){
			$_POST['fecha_id'] = $_POST['fecha_aloj_id'];
			unset($_POST['fecha_aloj_id']);
		}

		$filters = array();
		//esta campo indica cuál fue el selector que acaba de elegir
		$filters['field'] = $_POST['field'];
		$filters['paquete_id'] = $_POST['paquete_id'];
		$filters['pax'] = $_POST['pasajeros'];
		$filters['zocalo_mobile'] = $_POST['zocalo_mobile'];
	
		//obtengo data de paquete
		$paquete = $this->Paquete->get($filters['paquete_id'])->row();
		
		if($paquete->cupo_paquete_personalizado): 
			$paquete->cupo_disponible = $paquete->cupo_paquete_disponible;
			$paquete->cupo_total = $paquete->cupo_paquete_total;
		endif; 

		if(date('Y-m-d') > $paquete->fecha_inicio):
			$paquete->cupo_disponible = 0;	
		endif;

		$this->data['paquete'] = $paquete;

		//solo si no elegí pasajeros uso los otros filtros
		if($_POST['field'] != 'pasajeros'){
			$filters['lugar_salida'] = $_POST['lugar_salida'];
			$filters['fecha_id'] = @$_POST['fecha_id'];
			$filters['fecha'] = @$_POST['fecha'];
			$filters['alojamiento'] = $_POST['alojamiento'];
			$filters['habitacion'] = $_POST['habitacion'];
			$filters['pension'] = $_POST['pension'];
			$filters['transporte'] = @$_POST['transporte'];
		}
		
		//si es paquete grupal, le saco pax para que no lo use al filtrar
		if($this->data['paquete']->grupal){
			unset($filters['pax']);
		}
		
		//segun el campo que acabo de filtrar, "reseteo" los siguientes
		switch($_POST['field']){
			case "pasajeros":
				unset($filters['habitacion']);
			break;
			case "lugar_salida":
				unset($filters['fecha_id']);
				unset($filters['fecha']);
				unset($filters['alojamiento']);
				unset($filters['habitacion']);
				unset($filters['pension']);
				unset($filters['transporte']);
			break;
			case "fecha_id":
			case "fecha":
				unset($filters['alojamiento']);
				unset($filters['habitacion']);
				unset($filters['pension']);
				unset($filters['transporte']);
			break;
			case "alojamiento":
				unset($filters['habitacion']);
				unset($filters['pension']);
				unset($filters['transporte']);
			break;
			case "habitacion":
				unset($filters['pension']);
				unset($filters['transporte']);
				if(false && $_POST['habitacion']!=0){
					$hab = $this->Habitacion->get($_POST['habitacion'])->row();
					$_POST['pasajeros'] = $hab->pax;
					$_POST['pax'] = $hab->pax;
					$filters['pax'] = $_POST['pax'];
				}
			break;
			case "pension":
				unset($filters['transporte']);
			break;
		}
		
		//detalle abierto?
		$this->data['detalle_visible'] = $detalle_visible;

		//con los filtros aplicados, obtengo la combinacion con menor precio
		//estos valores son los que deben estar preseleccionados en el form
		$this->data['combinacion'] = $this->Combinacion->getByPaquete($this->data['paquete']->id,$limit=1,$filters);
		
		/*if($_SERVER['REMOTE_ADDR'] == '152.171.134.7' ){
			echo $this->db->last_query();
			pre($this->data['combinacion']);
		}*/

		/*if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
			echo $this->db->last_query();
			pre($this->data['combinacion']);
		}*/

		$data = $this->cargar_forms($filters);
		
		$data['combinacion'] = $this->data['combinacion'];
		
		echo json_encode($data);
	}
	
	function cargar_forms($filtros=array()){
		//obtengo data para precargar los forms

		//chequeo si seleccionó habitacion COMPARTIDA 
		$this->data['compartida'] = false;
		if(false && @$_POST['habitacion']==0 && @$filtros['field'] == 'habitacion'){
			//si la acaba de seleccionar
			$filtros['compartida'] = true;
			$this->data['compartida'] = true;
			$data['compartida'] = $this->data['compartida'];
		}
		elseif(false && @$_POST['habitacion']==0 && @$filtros['field'] == 'pasajeros'){
			//si cambio cantida de pax y la hab compartida estaba elegida -> solo actualizo detalle aside
			$data['no_action'] = 'ok';
			
			$this->data['combinacion'] = $this->Combinacion->get($_POST['combinacion_id'])->row();
			$this->data['combinacion']->pax = $_POST['pasajeros'];
			
			//metodos de pago que se usa en el paquete_detalle
			$origen = $this->data['paquete']->grupal ? 'mercadopago' : 'backend';
			$this->data['metodos_pago'] = $this->MP->getMetodosPago($origen);
			$data['detalle_calculador'] = $this->load->view('paquete_detalle',$this->data,true);
		}
		else{
			//cantidad de pasajeros disponibles
			$this->data['pasajeros'] = $this->Habitacion->getCombinacionPaquete($this->data['paquete']->id);
			
			$cantidad_pasajeros = [];
			foreach ($this->data['pasajeros'] as $pasajeros) {
				//Si es la compartida va con libre cantidad de 1 a 10
				if ($pasajeros->id == 99) {
					$cantidad_pasajeros = [];
					for ($i=1; $i<=10; $i++) {
						$cantidad_pasajeros[] = $i;
					}
					break;
				}
				else {
					$cantidad_pasajeros[] = $pasajeros->pax;
				}
			}
			
			$this->data['cantidad_pasajeros'] = $cantidad_pasajeros;

			//lugares de salidas disponibles
			$this->load->model('Lugar_salida_model','Lugar_salida');
			//lleva filtro de pasajeros
			$this->data['lugares_salida'] = $this->Lugar_salida->getCombinacionPaquete($this->data['paquete']->id,$filtros);
			
			//fechas de salida disponibles
			//lleva filtro de lugar de salida
			$this->load->model('Fecha_alojamiento_model','Fecha_alojamiento');
			$this->data['fechas_disponibles'] = $this->Fecha_alojamiento->getCombinacionPaquete($this->data['paquete']->id,$filtros);
			
			/*if($_SERVER['REMOTE_ADDR'] == '181.171.24.39' && @$habitacion == 2){
				echo $this->db->last_query();
			}*/

			//alojamientos disponibles
			//lleva filtro de fecha
			$this->load->model('Alojamiento_model','Alojamiento');
			$this->data['alojamientos'] = $this->Fecha_alojamiento->getAlojCombinacionPaquete($this->data['paquete']->id,$filtros);
			#echo $this->db->last_query();
			foreach($this->data['alojamientos'] as $al){
				$al->servicios = $this->Alojamiento->getDataServicios($al->id);
			}
			
			//si es paquete grupal
			if($this->data['paquete']->grupal){
				$this->data['pax_elegidos'] = (isset($_POST['pasajeros']) && $_POST['pasajeros']) ? $_POST['pasajeros'] : $cantidad_pasajeros[0];
				
				//piso este dato con las habitaciones que haya para la cantidad de pax elegidos
				$filtros['pax_elegidos'] = $this->data['pax_elegidos'];
				//$filtros['pax'] = $this->data['pax_elegidos'];
				//unset($filtros['habitacion']);
			}
			else{
				if($_SERVER['REMOTE_ADDR'] == '152.171.134.7' ){
					$this->data['pax_elegidos'] = (isset($_POST['pasajeros']) && $_POST['pasajeros']) ? $_POST['pasajeros'] : $cantidad_pasajeros[0];
					$filtros['pax_elegidos'] = $this->data['pax_elegidos'];
				}
			}

			//tipos de habitacion disponibles
			$tipos_habitacion = $this->Habitacion->getTiposCombinacionPaquete($this->data['paquete']->id,$filtros);

			/*if($_SERVER['REMOTE_ADDR'] == '152.171.134.7' ){
				echo $this->db->last_query();
				pre($this->data['tipos_habitacion']);
			}*/
			
			$this->data['tipos_habitacion'] = $tipos_habitacion;
			
			//aca defino si la habitacion tiene cupo o no segun la cantidad de pasajeros elegida y lo que queda
			$habitacion_sin_cupo = false;
			if(@$_POST['habitacion'] != 99){
				foreach($tipos_habitacion as $th){
					if($th->id == @$_POST['habitacion'] && $th->completo){
						$habitacion_sin_cupo=true;
					}
				}
			}
			
			//si es habitacion compartida y la cantidad de pax elegida es mayor al cupo disponible
			if(@$_POST['habitacion'] == 99 && $_POST['pasajeros'] > $this->data['paquete']->cupo_disponible){
				$habitacion_sin_cupo = true;
			}
			
			$this->data['habitacion_sin_cupo'] = $habitacion_sin_cupo;
			
			//regimenes de comida disponibles
			//lleva filtro de hotel
			$this->load->model('Regimen_model','Regimen');
			if(isset($_POST['alojamiento']) && $_POST['alojamiento']){
				if($this->data['combinacion']->alojamiento_id != $_POST['alojamiento']){
					$filtros['alojamiento'] = $this->data['combinacion']->alojamiento_id;
				}
				else{
					//tomo el del post
					$filtros['alojamiento'] = $_POST['alojamiento'];
				}
			}
			$this->data['regimenes'] = $this->Regimen->getCombinacionPaquete($this->data['paquete']->id,$filtros);
			
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				//pre($filtros);
				//echo $this->db->last_query();		
			}

			//transportes disponibles
			$this->load->model('Transporte_model','Transporte');
			$transportes = $this->Transporte->getCombinacionPaquete($this->data['paquete']->id,$filtros);
			$this->data['transportes'] = $transportes;
			//echo $this->db->last_query();
			
			//aca defino si el transporte tiene cupo o no segun la cantidad de pasajeros elegida y lo que queda
			$transporte_sin_cupo = false;
			foreach($transportes as $tf){
				if($tf->id == @$_POST['transporte'] && $tf->cupo < $_POST['pasajeros']){
					$transporte_sin_cupo=true;
				}
			}
			
			$this->data['transporte_sin_cupo'] = $transporte_sin_cupo;
			
		//para estos adicionales tener en cuenta la cantidad de pasajeros para ver si 
		//el transporte tiene el cupo para el total de pasajeros
			//adicionales disponibles del paquete
			$this->load->model('Adicional_model','Adicional');
			$this->data['adicionales'] = $this->Adicional->getCombinacionPaquete($this->data['paquete']->id,$filtros);
			$this->data['filtros'] = $filtros;

			if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
			/*	pre($this->data['combinacion']);
				pre($tipos_habitacion);
				pre($filtros);*/
			}

			//metodos de pago que se usa en el paquete_detalle
			$origen = $this->data['paquete']->grupal ? 'mercadopago' : 'backend';
			$this->data['metodos_pago'] = $this->MP->getMetodosPago($origen);
		

			//precargo los forms
			$data['form_salidas'] = $this->load->view('paquete_form_salidas',$this->data,true);
			$data['form_alojamientos'] = $this->load->view('paquete_form_alojamientos',$this->data,true);
			$data['form_transportes'] = $this->load->view('paquete_form_transportes',$this->data,true);
			$data['form_adicionales'] = $this->load->view('paquete_form_adicionales',$this->data,true);
			$data['detalle_calculador'] = $this->load->view('paquete_detalle',$this->data,true);
			//segun qué conversor esté mostrando
			$data['forzar_moneda'] = isset($_POST['aside_mobile']) && $_POST['aside_mobile'] ? @$_POST['tipo_moneda_m'] : @$_POST['tipo_moneda'];
			
		}
		
		return $data;
	}
	
	//obtiene la cotizacion del dolar
	function get_cotizacion(){
		echo $this->settings->cotizacion_dolar;		
	}
	
	//calcula totales de precios segun los adicionales elegidos
	function get_total_precio(){
		extract($_POST);
		
		$pasajeros = $this->input->post('pasajeros');
		$adicionales = $this->input->post('adicionales');
		$adicionales_precio = $this->input->post('adicionales_precio');
		$paquete = $this->Paquete->get($paquete_id)->row();		
		$combinacion = $this->Combinacion->get($combinacion_id)->row();
		
		if(!isset($adicionales) || !$adicionales){
			$adicionales_precio = 0.00;
		}

		//segun qué conversor esté mostrando
		$tipo_moneda = isset($aside_mobile) && $aside_mobile ? @$tipo_moneda_m : @$tipo_moneda;
		
		$combinacion->pax = $pasajeros;
		$ret = calcular_precios_totales($combinacion,$adicionales,@$tipo_moneda,false);

		foreach($ret as $k=>&$val){
			if(is_array($val)){
				foreach($val as $kk=>&$vv){
					$vv = strip_tags($vv);
				}
			}
			else{
				$val = strip_tags($val);
			}
		}

		$mon = $tipo_moneda == 'USD' ? 1 : 0;
		$precio_usd = $mon;
		$ret['precio_bruto'] = precio_redondeado($ret['num']['precio_bruto'],$precio_usd);
		$ret['precio_total'] = precio_redondeado($ret['num']['precio_total'],$precio_usd);
		$ret['precio_impuestos'] = precio_redondeado($ret['num']['precio_impuestos'],$precio_usd);
		$ret['precio_bruto_persona'] = precio_redondeado($ret['num']['precio_bruto_persona'],$precio_usd);
		$ret['precio_final_persona'] = precio_redondeado($ret['num']['precio_final_persona'],$precio_usd);
		$ret['precio_impuestos_persona'] = precio_redondeado($ret['num']['precio_impuestos_persona'],$precio_usd);
		$ret['monto_minimo_reserva_persona'] = precio_redondeado($ret['num']['monto_minimo_reserva_persona'],$precio_usd);
		
		$ret['combinacion_id'] = $combinacion->id;

		$prices = calcular_precios_totales($combinacion,array(),@$tipo_moneda,false);

		$ret['paquete_precio'] = $prices['num']['precio_bruto'];
		$ret['impuestos'] = $prices['num']['precio_impuestos'];
		$ret['adicionales_precio'] = $adicionales_precio;


		echo json_encode($ret);
	}
	
	//valida solicitud de reserva de paquete
	function solicitar_reservar(){
		extract($_POST);
		
		/*
		[paquete_id] => 12
		[field] => pasajeros
		[pasajeros] => 2
		[lugar_salida] => 1
		[fecha] => 10/09/2017
		[alojamiento] => 9
		[habitacion] => 2
		[pension] => 17
		[transporte] => 19
		[adicionales] => Array
			(
				[39] => on
				[33] => on
			)

		[forma_pago] => 
		[metodo_pago] => 
		[cuotas] => 
		[combinacion_id] => 1672
		[paquete_precio] => 37423.56
		[impuestos] => 2797.94
		[adicionales_precio] => 5695.25
		*/

		/*
		if(isset($tipo_moneda) && $tipo_moneda){
			$this->session->set_userdata('user_tipo_moneda',$tipo_moneda);
		}
		*/

		//valido datos obligatorios
		if(!isset($paquete_id) || !$paquete_id || !isset($combinacion_id) || !$combinacion_id){
			$ret['status'] = 'error';
		}
		else{
			$paquete = $this->Paquete->get($paquete_id)->row();		
			$combinacion = $this->Combinacion->get($combinacion_id)->row();
			
			//genero orden de prereserva
			$orden = array();
			$orden['paquete_id'] = $paquete_id;

			$orden['vendedor_id'] = 0;
			if(esVendedor()){
				if(perfil()=='VEN'){
					//si es un ADMIN de tipo VENDEDOR, obtengo el vendedor externo asociado, y se lo pongo a la orden
					$vend_id = vendedor_asociado();
					$orden['vendedor_id'] = $vend_id;
				}
				else{
					$orden['vendedor_id'] = userloggedId();
				}
			}

			$orden['combinacion_id'] = $combinacion_id;
			$orden['pasajeros'] = $pasajeros;
			$orden['lugar_id'] = $lugar_salida;
			//$orden['fecha_alojamiento_id'] = @$fecha_id;
			//ahora tomo el ID de la combinacion
			$orden['fecha_alojamiento_id'] = $combinacion->fecha_alojamiento_id;
			if(isset($fecha) && $fecha != '')
				$orden['fecha'] = formato_fecha($fecha,true);
			else
				$orden['fecha'] = '0000-00-00';
			$orden['alojamiento_id'] = $alojamiento;
			$orden['habitacion_id'] = $habitacion;
			$orden['paquete_regimen_id'] = $pension;
			$orden['transporte_fecha_id'] = $transporte;
			/*$orden['paquete_precio'] = $paquete_precio;
			$orden['impuestos'] = $impuestos;*/

			//18-10-2018 paquete_orecio e impuestos los calculo desde el row de combinacion
			$precio = precio_bruto($combinacion,true,$paquete->grupal?$pasajeros:false);
			$imp = precio_impuestos($combinacion,true,$paquete->grupal?$pasajeros:false);
			$orden['paquete_precio'] = $precio;
			$orden['impuestos'] = $imp;

			$orden['adicionales_precio'] = $adicionales_precio;
			$orden['fecha_orden'] = date('Y-m-d H:i:s');
			$orden['ip'] = $_SERVER['REMOTE_ADDR'];
			$orden['code'] = genRandomString();//codigo random de 10 chars
			
			$orden['paso_actual'] = 1; //seteo el paso actual a completar (para el checkout)
			//seteo el estado de confirmacion de la orden nueva segun el paquete tenga confirmacion inmediata o no
			$orden['confirmacion_inmediata'] = ($combinacion->confirmacion_inmediata)?1:0; 
			$orden['cotizacion'] = $this->settings->cotizacion_dolar; 
			
			if(esVendedor()){
				if(perfil()=='VEN'){
					//si es un ADMIN de tipo VENDEDOR, obtengo el vendedor externo asociado, y se lo pongo a la orden
					$vend_id = vendedor_asociado();
				}
				else{
					$vend_id = userloggedId();
				}

				//si la reserva la hizo un vendedor, ingresa con SU sucursal
				$vend = $this->db->query("select * from bv_vendedores where id = ".$vend_id)->row();
				$orden['sucursal_id'] = $vend->sucursal_id ? $vend->sucursal_id : 1;			
			}
			else{
				//sino por defecto ingresa con la sucursal BA
				$orden['sucursal_id'] = 1;
			}
		
			$orden_id = $this->Orden->insert($orden);
			
			//guardo registro de cada adicional elegido
			if(isset($adicionales) && count($adicionales)){
				foreach($adicionales as $adicional_id=>$valor){
					/*
					pasajeros_adicional[54]: 2
					adicionales[54]: 110.00
					*/
					$cant = isset($pasajeros_adicional[$adicional_id]) ? $pasajeros_adicional[$adicional_id] : 0;
					$this->Orden->addAdicional($orden_id,$adicional_id,$valor,$cant);
				}
			}
			
			//por cada pasajero genero registros en tablas de pasajeros
			for($i=1;$i<=$pasajeros;$i++){
				$pax = array();
				$pax['numero_pax'] = $i;
				$pax['orden_id'] = $orden_id;
				$pax['responsable'] = $i==1?1:0; //el primero es el responsable
				$pax['timestamp'] = date('Y-m-d H:i:s');
				$pax['ip'] = $_SERVER['REMOTE_ADDR'];
				$this->Orden_pasajero->insert($pax);
			}
			
			//tambien genero registro en tabla de datos de facturacion
			$fact = array();
			$fact['orden_id'] = $orden_id;
			$fact['timestamp'] = date('Y-m-d H:i:s');
			$fact['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->Orden_facturacion->insert($fact);
			
			$hash = encriptar($orden['code']);

		/*if( $_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo $orden['code'];
			echo "<br>";
			echo $hash;
		}*/
		
			$ret['redirect'] = site_url('checkout/orden/'.$hash);
			$ret['status'] = 'success';
		}
		
		echo json_encode($ret);
	}
	
}
