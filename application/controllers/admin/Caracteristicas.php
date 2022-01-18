<?php
include "AdminController.php";

class Caracteristicas extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Caracteristica_model', 'Caracteristica');
    $this->model = $this->Caracteristica;
    $this->page = "caracteristicas";
    $this->data['currentModule'] = "config";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "caracteristicas";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Caracteristicas";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
  }
  
}