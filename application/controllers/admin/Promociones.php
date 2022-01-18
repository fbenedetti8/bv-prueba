<?php
include "AdminController.php";

class Promociones extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Promocion_model', 'Promocion');
    $this->model = $this->Promocion;
    $this->page = "promociones";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "promociones";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Promociones";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
  
}