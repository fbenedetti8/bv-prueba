<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proximosviajes extends MY_Controller {

	public function __construct() {
		parent::__construct();	

		$this->data['body_id'] = '';
		$this->data['body_class'] = 'body-proximos';

		/*
		assets/owl-carousel/owl.carousel.min.css
		assets/owl-carousel/owl.theme.default.min.css
		assets/fancybox/jquery.fancybox.min.css
		assets/selectric/selectric.css
		assets/css/app.css" 
		*/

		$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
		$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
		$this->carabiner->css('../fancybox/jquery.fancybox.min.css');
		$this->carabiner->css('../selectric/selectric.css');
		$this->carabiner->css('app.css?v=1');

		/*
		https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js
		assets/owl-carousel/owl.carousel.min.js
		assets/selectric/selectric.js
		assets/js/arrow-slide.js
		assets/js/main.js
		*/

		$this->carabiner->js('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');
		$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
		$this->carabiner->js('../selectric/selectric.js');
		$this->carabiner->js('arrow-slide.js');
		$this->carabiner->js('main.js?v=1');

		//cantidad de resultados del buscador
		$this->limit = 5;

		$this->seo_title = 'Buenas Vibras Viajes - Próximos Viajes';
		$this->seo_description = "GENERAMOS VÍNCULOS VIAJANDO POR EL MUNDO";
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		
	}

	public function index(){
		$this->buscar();
	}

	//este metodo aplica filtros a los destinos, solo 1 filtro de cada tipo a la vez
	/*
	Slug del destino
	Fecha : año-mes con viajes activos
	Categoria: estacional
	*/
	public function buscar($slug='',$fecha='',$categoria=''){
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Categoria_model', 'Categoria');
		$this->load->model('Estacionales_model', 'Estacional');	
		$this->load->model('Paquete_model', 'Paquete');	
		
		//si vienen las uri por defecto, borro esos valores para no tomarlos como filtros
		if($slug == $this->config->item("uri_regiones")){
			$slug = '';
		}
		if($fecha == $this->config->item("uri_fecha")){
			$fecha = '';
		}
		if($categoria == $this->config->item("uri_categoria")){
			$categoria = '';
		}

		$region_id = 0;
		if($slug){
			$d = $this->Categoria->getWhere(['slug' => $slug])->row();
			$region_id = (isset($d->id) && $d->id) ? $d->id : 0;
			$this->data['region'] = $d;
		}
		$categoria_id = 0;
		if($categoria){
			$c = $this->Estacional->getWhere(['slug' => $categoria])->row();
			$categoria_id = (isset($c->id) && $c->id) ? $c->id : 0;
			$this->data['categoria'] = $c;
		}

		$filters = [];
		$filters['region_id'] = $region_id;
		$filters['fecha'] = $fecha;
		$filters['categoria_id'] = $categoria_id;

		$this->data['filtros'] = $filters;

		$offset = $this->input->post('offset');
		$offset_post = $offset;

		//si viene por post, tengo q devolver por ajax
		$json = [];

		//ahora se busca por destino
		$destinos = $this->Paquete->buscarDestinos($filters,5,$offset);
		
		if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
			//echo $this->db->last_query();
		}

		if($offset_post){
			$offset = $offset+$this->limit;
			$json['offset'] = $offset;
		}

		if(!$offset_post){
			$offset = $offset+$this->limit;
			$this->data['offset'] = $offset;
		}
		//reordeno los resultados para agruparlos por categoria
		$results = [];
		$results_destinos = [];
		foreach($destinos as $d){

			//al destino le cargo el precio mas bajo
			$mp = $this->Destino->getPrecioDesde($d->destino_id);

			$d->precio_total = $mp->total;
			$d->precio_impuestos = $mp->impuestos;
			$d->precio_anterior_neto = $mp->precio_anterior_neto;
			$d->precio_anterior_impuestos = $mp->precio_anterior_impuestos;

			$d->paquetes = [];

			//por cada destino traigo todos los paquetes
			$filters['destino_id'] = $d->destino_id;
			$paquetes = $this->Paquete->buscarViajes($filters,999,0);
			foreach($paquetes as $p){

				//actualizo cupos si es personalizado
				if($p->cupo_paquete_personalizado): 
					$p->cupo_disponible = $p->cupo_paquete_disponible;
					$p->cupo_total = $p->cupo_paquete_total;
				endif; 

				//data de las estacioneles de dicho paquete
				$p->cat_estacionales = [];
				$p->cat_estacionales = $this->Paquete->getDataEstacionales($p->id);

				//$results[$p->estacionales][] = $p;
				//$d = $this->Destino->getPrecioDesde($p->destino_id);

				//$results[$p->destino][] = $p;

				$d->paquetes[] = $p;

			}

			$results_destinos[] = $d;
		}


		$this->data['destinos'] = $results_destinos;
		//$this->data['paquetes'] = $results;

		//aca cargo los destinos marcados como PROXIMAMENTE desde el backEND
		$destinos = $this->Destino->getProximamente();	
		foreach ($destinos as $d) {
			//data de las estacioneles de dicho destino
			$d->cat_estacionales = [];
			$d->cat_estacionales = $this->Estacional->getListDelDestino($d->id);
		}
		$this->data['destinos_proximamente'] = $destinos;

		//inicializa los filtros del buscador, tambien se usaran en la pagina de proximos viajes
		$this->setup_filtros();

		$this->load->model('Estacionales_model', 'Estacional');	

		//que traiga las categorias estacionales de paquetes que tiene resultados el buscador: activos y vigentes
		$this->data['categorias'] = $this->Estacional->getConPaquetes();

		if($offset_post){
			$json['cant'] = count($results_destinos);
			//devuelvo por ajax la view
			$json['view'] = $this->load->view('proximos_viajes_list',$this->data,true);
			echo json_encode($json);
		}
		else{
			//cargo view parcial con listado de paquetes
			$this->data['list_paquetes'] = $this->load->view('proximos_viajes_list',$this->data,true);
			$this->render('proximos_viajes');
		}
	}
	
}
