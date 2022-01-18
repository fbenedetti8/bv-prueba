<?php
include "AdminController.php";

class Documentaciones extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Documentacion_model', 'Documentacion');
    $this->model = $this->Documentacion;
    $this->page = "documentaciones";
    $this->data['currentModule'] = "catalogos";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "documentaciones";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Documentaciones";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
  
}