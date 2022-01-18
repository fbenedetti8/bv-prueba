<?php
include "AdminController.php";

class Medios extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Medio_model', 'Medio');
    $this->model = $this->Medio;
    $this->page = "medios";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->pageSegment = 4;
    $this->data['page_title'] = "Medios de Pago";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
    
}