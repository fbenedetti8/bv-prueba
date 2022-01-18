<?php
include "AdminController.php";

class Panel extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['currentModule'] = "panel";
		$this->page = "panel";
		$this->data['page'] = $this->page;
		$this->data['route'] = base_url() . 'admin/' . $this->page;
		$this->init();
	}

	function index(){
		if(perfil() == 'VENEXT'){
			redirect(site_url('admin/reservas_vendedor'));
		}
		else{
			$this->load->view('admin/panel', $this->data);
		}
	}
	
}