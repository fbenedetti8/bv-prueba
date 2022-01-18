<?php
include "AdminController.php";

class Excursiones extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Excursion_model', 'Excursion');
    $this->model = $this->Excursion;
    $this->page = "excursiones";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "excursiones";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Excursiones";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
  
  function onEditReady($id='') {
	$this->load->model('Destino_model', 'Destino');
	$this->data['destinos'] = $this->Destino->getList('', 'nombre asc');
	
    if ($id) {
        $this->data['mis_destinos'] = $this->model->getDestinos($id);
    }
    else {
        $this->data['mis_destinos'] = [];
    }
	
  }
  
  function onAfterSave($id) {
	 
		//destinos asociados
		$this->model->clearDestinos($id);

		$dd = $this->input->post('destino_id[]');
		foreach ($dd as $d) {
			$this->model->addDestino($id, $d);
		}
  }
  
}