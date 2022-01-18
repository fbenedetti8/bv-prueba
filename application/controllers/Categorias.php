<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

	public function __construct() {
		parent::__construct();	
	}

	public function index(){
	}
	
	function onBeforeRender(){
		$this->data['medios_de_pago'] = $this->load->view('medios_de_pago',$this->data,true);
	}
	
	//carga categoria regional con destinos divididos por categorias estacionales
	function regionales($slug){
		$this->data['body_id'] = 'categoria';

		//de cada categoria regional traigo el menor precio de viaje
		$this->load->model('Categoria_model', 'Categoria');
		$this->Categoria->filters = "slug = '".$slug."'";
		$this->data['categoria'] = $this->Categoria->getAll(1,0)->row();
		
		//de la categoria regional traigo las subcategorias estacionales
		$this->data['estacionales'] = $this->Categoria->getEstacionales($this->data['categoria']->id);
		$cant_destinos = 0;
		foreach($this->data['estacionales'] as $e){		
			$this->load->model('Destino_model', 'Destino');
			$e->destinos = $this->Destino->getPorEstacionalRegional($e->id,$this->data['categoria']->id);
			
			$cant_destinos += count($e->destinos);
		}

		//Precio desde
		$this->data['precio_minimo'] = $this->Categoria->getPrecioDesde($this->data['categoria']->id);
		
		//SEO
		$this->seo_title = $this->data['categoria']->nombre;
		$this->seo_description = $this->data['categoria']->titulo.' '.$this->data['categoria']->subtitulo;
		$this->seo_keywords = '';
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		if($this->data['categoria']->imagen_mobile && file_exists('./uploads/categorias/'.$this->data['categoria']->id.'/'.$this->data['categoria']->imagen_mobile))
			$this->seo_image = base_url().'uploads/categorias/'.$this->data['categoria']->id.'/'.$this->data['categoria']->imagen_mobile;
		
		if($cant_destinos > 0){
			$this->render('categoria');
		}			
		else{
			//$this->data['body_id'] = 'checkout';
			//$this->data['body_class'] = 'lista_de_espera_proximamente';
			
			//si no hay combinacion cargo lista_de_espera_proximamente
			$this->render('lista_de_espera_proximamente_categoria');
		}
	}

	//carga categoria estacional con destinos divididos por destinos
	function estacionales($slug){
		$this->data['body_id'] = 'categoria';
		$this->data['body_class'] = 'estacional';
		
		//de cada categoria regional traigo el menor precio de viaje
		$this->load->model('Estacionales_model', 'Estacional');
		$this->Estacional->filters = "slug = '".$slug."'";
		$this->data['estacional'] = $this->Estacional->getAll(1,0)->row();
		
		//de la categoria estacional traigo las categorias regionales
		$this->data['regionales'] = $this->Estacional->getRegionales($this->data['estacional']->id);
		
		foreach($this->data['regionales'] as $r){
			$this->load->model('Destino_model', 'Destino');
			$r->destinos = $this->Destino->getPorRegionalEstacional($r->id,$this->data['estacional']->id);
		}
			
		//SEO
		$this->seo_title = $this->data['estacional']->nombre;
		$this->seo_description = "GENERAMOS VÍNCULOS VIAJANDO POR EL MUNDO";
		$this->seo_keywords = '';
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		
		$this->render('categoria_estacional');
	}

	//carga destinos grupales divididos por categorias estacionales
	function grupales(){
		$this->data['body_id'] = 'categoria';
		$this->data['body_class'] = 'categoria_viajes_grupales';
		
		//Precio desde
		$this->load->model('Categoria_model', 'Categoria');
		$this->data['precio_minimo'] = $this->Categoria->getPrecioDesde(0, 1);
		
		//de la categoria regional traigo las subcategorias estacionales
		$this->load->model('Estacionales_model', 'Estacional');
		$this->data['estacionales'] = $this->Estacional->getEstacionalesGrupales();
		
		foreach($this->data['estacionales'] as $e){
			$this->load->model('Destino_model', 'Destino');
			$e->destinos = $this->Destino->getPorEstacionalGrupales($e->id);
		}
		
		//SEO
		$this->seo_title = @$this->settings->titulo_grupales;
		$this->seo_description = @$this->settings->descripcion_grupales;
		$this->seo_keywords = '';
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		if(@$this->settings->imagen_mobile_grupales && file_exists('./uploads/config/1/'.$this->settings->imagen_mobile_grupales)){
			$this->seo_image = base_url().'uploads/config/1/'.$this->settings->imagen_mobile_grupales;
		}
		
		$this->render('categoria_viajes_grupales');
	}

}