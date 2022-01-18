<?php

header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

class MY_Controller extends CI_Controller {

	function __construct() {
    parent::__construct();

		$this->vars = array();
		$this->data = array();

		$this->load->library('pagination');

		$this->load->model('Config_model', 'Config');
		$this->settings = $this->Config->get(1)->row();

		//var_dump($this->db->last_query());

		$this->data['settings'] = $this->settings;

		$this->folder = '';

		//Campos SEO genericos
		$this->seo_title = 'Buenas Vibras Viajes';
		$this->seo_description = "GENERAMOS VÍNCULOS VIAJANDO POR EL MUNDO";
		$this->seo_keywords = '';
		$this->seo_image = base_url().'media/assets/images/img/share-web.png';

		$this->data['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();

		$this->data['home'] = FALSE;

		//si no esta forzada la navegacion, lo mando a pag mantenimiento
		/*
		if(!$this->session->userdata('web') && ($this->uri->segment('1') != 'mantenimiento' || $this->uri->segment(1) != 'cron')){
			$this->mantenimiento();
		}
		*/
		if ($_SERVER['REMOTE_ADDR'] == '152.171.77.203') {
			$this->output->enable_profiler(TRUE);
		}
	}

	//metodo para forzar navegar web
	function web(){
		$this->session->set_userdata('web','ok');
		redirect(base_url());
	}

	function mantenimiento(){
		redirect(site_url('mantenimiento'));
	}

	function loadMenu() {
		$this->load->model('Categoria_model', 'Categoria');
		$this->data['categorias_menu'] = $this->Categoria->getMenu();
		$this->data['menu'] = $this->load->view($this->folder.'menu', $this->data, true);
	}

	function onBeforeRender() {

	}

	function setup() {
		#$this->carabiner->js('functions.js');
	}

	function render($view, $return=FALSE) {
		$this->setup();

		$this->loadMenu();

		$this->onBeforeRender();

		//Cargo campos SEO
		$this->data['seo_title'] = $this->seo_title;
		$this->data['seo_description'] = $this->seo_description;
		$this->data['seo_keywords'] = $this->seo_keywords;
		$this->data['seo_image'] = $this->seo_image;

		//se carga  en footer, header, contacto_subseccion y paquete
		$this->load_phone_site();

		//16-04-19 cargo los telefonos asociados a los diferentes paises
		$this->load->model('Telefonos_contacto_model','Tel');
		$this->data['telefonos'] = $this->Tel->getTelefonos();

		//17-04-19 cargo los paises para el form de newsletter
		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();

		$this->data['fb_meta'] = $this->load->view($this->folder.'fb_meta', $this->data, true);
		$this->data['header'] = $this->load->view($this->folder.'header', $this->data, true);

		$this->data['footer'] = $this->load->view($this->folder.'footer', $this->data, true);
		$this->data['floating_help'] = ''; //$this->load->view($this->folder.'floating_help', $this->data, true);	

		if ($return) {
			return $this->load->view($this->folder.$view, $this->data, TRUE);
		}
		else {
			$this->load->view($this->folder.$view, $this->data);
		}
	}

	public function load_phone_site(){
		//si ya está cargado, ej, estaticas/contacto, no hago nada
		if(isset($this->data['footer_celular']) && $this->data['footer_celular']){
			//no hago nada
		}
		else{
			//18-09-18 tomo un celular random cargado para mostrar en footer
			$this->data['footer_celular'] = false;
			$this->load->model('Celular_contacto_model', 'Celular_contacto');
			$footer_celular = $this->Celular_contacto->getRandom();
			$this->data['footer_celular'] = $footer_celular;
		}
	}

	public function loadPagination(){
		//Pagination configuration
		$this->pconfig['base_url'] = base_url().'/index';
		$this->pconfig['per_page'] = $this->limit;
		$this->pconfig['num_links'] = '5';
		$this->pconfig['full_tag_open'] = '<ul class="pagination">';
		$this->pconfig['full_tag_close'] = '</ul>';
		$this->pconfig['first_link'] = 'Primero';
		$this->pconfig['first_tag_open'] = '<li>';
		$this->pconfig['first_tag_close'] = '</li>';
		$this->pconfig['last_link'] = '&Uacute;ltimo';
		$this->pconfig['last_tag_open'] = '<li>';
		$this->pconfig['last_tag_close'] = '</li>';
		$this->pconfig['next_link'] = '>';
		$this->pconfig['next_tag_open'] = '<li class="arrow">';
		$this->pconfig['next_tag_close'] = '</li>';
		$this->pconfig['prev_link'] = '<';
		$this->pconfig['prev_tag_open'] = '<li class="arrow">';
		$this->pconfig['prev_tag_close'] = '</li>';
		$this->pconfig['cur_tag_open'] = '<li class="current"><a href="javascript:void(0);">';
		$this->pconfig['cur_tag_close'] = '</a></li>';
		$this->pconfig['num_tag_open'] = '<li>';
		$this->pconfig['num_tag_close'] = '</li>';

		$this->pconfig['uri_segment'] = $this->pageSegment;
	}

	//inicializa los filtros del buscador de home, tambien se usaran en la pagina de proximos viajes
	function setup_filtros(){
		//traigo filtro de fechas disponibles de viajes
		$this->load->model('Paquete_model','Paquete');
		$fechas = $this->Paquete->fechasDisponibles();
		$this->data['fechas'] = $fechas;

		//cargo los destinos activos
		$destinos_activos = $this->Destino->getDisponibles();
		$this->data['destinos_activos'] = $destinos_activos;

		//cargo las categorias regionales activas
		$this->load->model('Categoria_model', 'Categoria');
		$categorias_activas = $this->Categoria->getDisponibles();

		$this->data['categorias_activas'] = $categorias_activas;
	}

}
