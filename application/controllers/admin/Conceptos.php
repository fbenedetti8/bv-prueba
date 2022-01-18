<?php
include "AdminController.php";

class Conceptos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Concepto_model', 'Concepto');
		$this->model = $this->Concepto;
		$this->page = "conceptos";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Conceptos";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}
	
	function onBeforeSave($id='') {
		$_POST['pasa_a_confirmada'] = @$_POST['pasa_a_confirmada'] ? $_POST['pasa_a_confirmada'] : 0;
		$_POST['facturacion'] = @$_POST['facturacion'] ? $_POST['facturacion'] : 0;
		$_POST['envia_mail'] = @$_POST['envia_mail'] ? $_POST['envia_mail'] : 0;
		$_POST['incluye_gastos'] = @$_POST['incluye_gastos'] ? $_POST['incluye_gastos'] : 0;
		$_POST['sistema_caja'] = @$_POST['sistema_caja'] ? $_POST['sistema_caja'] : 0;
	}
}