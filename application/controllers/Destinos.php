<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Destinos extends MY_Controller {

	public function __construct() {
		parent::__construct();	

	}

	public function index(){
	}
	
	function onBeforeRender(){
		$this->data['medios_de_pago'] = $this->load->view('medios_de_pago',$this->data,true);
	}
	
	//carga destino
	function ver($slug){
		
		$this->load->model('Paquete_model', 'Paquete');
		
		//data destino
		$this->load->model('Destino_model', 'Destino');
		$destino = $this->Destino->getBySlug($slug);
		$this->data['destino'] = $destino;

		//precio minimo
		$this->data['precio_minimo'] = $this->Destino->getPrecioDesde($destino->id);

		//SEO
		$this->seo_title = $this->data['destino']->nombre;
		$this->seo_description = $this->data['destino']->descripcion;
		$this->seo_keywords = '';
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';
		if($this->data['destino']->imagen && file_exists('./uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen))
			$this->seo_image = base_url().'uploads/destinos/'.$this->data['destino']->id.'/'.$this->data['destino']->imagen;
		
		//categoria actual del destino
		$this->load->model('Categoria_model', 'Categoria');
		$this->data['categoria'] = $this->Categoria->get($this->data['destino']->categoria_id)->row();
		
		//caracteristicas del destino
		$this->data['caracteristicas'] = $this->Destino->getCaracteristicas($this->data['destino']->id);
		
		//paquetes del destino agrupados por categoria estacional
		$this->data['estacionales'] = $this->Destino->getEstacionalesById($this->data['destino']->id);
		
		//cargo las fotos del destino
		$this->load->model('Destino_foto_model','Destino_foto');
		$this->data['fotos'] = $this->Destino_foto->getWhere(array('destino_id'=>$this->data['destino']->id))->result();	
			
		$this->data['destino_galeria'] = $this->load->view('destino_galeria',$this->data,true);

		$cant_paquetes = 0;
		$arr_paquetes = array();

		foreach($this->data['estacionales'] as $e){
			$e->paquetes = $this->Destino->getPaquetesPorEstacional($this->data['destino']->id,$e->id);

			//si quiere mostrar cupo personalizado, piso los datos reales
			foreach ($e->paquetes as $p) {
				$p->cat_estacionales = [];
				$p->cat_estacionales = $this->Paquete->getDataEstacionales($p->id);

				if($p->cupo_paquete_personalizado){
					$p->disponibles = $p->cupo_paquete_disponible;
					$p->cupo_disponible = $p->cupo_paquete_disponible;
					$p->cupo_total = $p->cupo_paquete_total;
				}
				
				//genero array con los ids de paquetes que hay
				$arr_paquetes[$p->id] = $p->id;
			}

			#$cant_paquetes += count($e->paquetes);
		}

		$cant_paquetes = count($arr_paquetes);

		$this->load->model('Estacionales_model','Estacional');
		//Otros destinos, de la misma categoria, que no sean el destino actual

		$destinos = $this->Destino->getRecomendados($this->data['destino']->categoria_id,$this->data['destino']->id);
		foreach ($destinos as $d) {
			//data de las estacioneles de dicho destino
			$d->cat_estacionales = [];
			$d->cat_estacionales = $this->Estacional->getListDelDestino($d->id);
		}
		$this->data['otros_destinos'] = $destinos;
		
		if($cant_paquetes > 1){
			$this->data['body_id'] = 'intermedia';
			$this->data['body_class'] = '';
			
			$this->carabiner->css('../owl-carousel/owl.carousel.min.css');
			$this->carabiner->css('../owl-carousel/owl.theme.default.min.css');
			$this->carabiner->css('../selectric/selectric.css');
			$this->carabiner->css('app.css');

			$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
			$this->carabiner->js('../selectric/selectric.js');
			$this->carabiner->js('../owl-carousel/owl.carousel.min.js');
			$this->carabiner->js('main.js');
			$this->carabiner->js('sliders.js');

			$this->data['destinos_recomendados'] = $this->load->view('destinos_recomendados',$this->data,true);

			$this->render('destino');
		}			
		else if($cant_paquetes == 1){		
			//redirijo a interna del paquete
			$slug = '';
			foreach($this->data['estacionales'] as $e){
				if(isset($e->paquetes[0]) && $e->paquetes[0] && isset($e->paquetes[0]->slug) && $e->paquetes[0]->slug){
					$slug = $e->paquetes[0]->slug;
					break;
				}
			}
				
			redirect(site_url($slug));
		}
		else{
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

			$this->load->model('Estacionales_model', 'Estacional');	
			$this->data['destino']->cat_estacionales = $this->Estacional->getListDelDestino($this->data['destino']->id);

			$this->data['destinos_recomendados'] = $this->load->view('destinos_recomendados',$this->data,true);

			//si no hay combinacion cargo lista_de_espera_proximamente
			$this->render('lista_de_espera_proximamente_destino');
		}
	}

}