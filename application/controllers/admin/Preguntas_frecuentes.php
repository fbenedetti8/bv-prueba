<?php
include "AdminController.php";

class Preguntas_frecuentes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Preguntas_frecuentes_model', 'PreguntasFrecuentes');
		$this->model = $this->PreguntasFrecuentes;
		$this->page = "preguntas_frecuentes";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Preguntas Frecuentes";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->load->model('Preguntas_categorias_model', 'Cat');
		$this->data['categorias'] = $this->Cat->getAll(999,0,'orden','asc')->result();
	}

	function index(){
		$this->model->filters = '1=1';
		
		$this->data['categoria_id'] = '';
		//filtro de destino
		if(isset($_GET['categoria_id']) && $_GET['categoria_id'] != ''){
			$this->data['categoria_id'] = $_GET['categoria_id'];
			$this->model->filters .= ' and categoria_id = '.$_GET['categoria_id'];
			$this->session->set_userdata('categoria_id',$_GET['categoria_id']);
		}
		else{
			if(isset($_GET['categoria_id']) && $_GET['categoria_id'] == ''){
				$this->session->unset_userdata('categoria_id');
			}
			
			if($this->session->userdata('categoria_id')){
				$this->data['categoria_id'] = $this->session->userdata('categoria_id');
				$this->model->filters .= ' and categoria_id = '.$this->session->userdata('categoria_id');
			}
		}
		
		parent::index();
	}

}