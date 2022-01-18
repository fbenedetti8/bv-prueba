<?php
include "AdminController.php";

class Estacionales extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Estacionales_model', 'Estacionales');
    $this->model = $this->Estacionales;
    $this->page = "estacionales";
    $this->data['currentModule'] = "categorias";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->pageSegment = 4;
    $this->data['page_title'] = "Categorias Estacionales";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;

      $this->uploads = array(
            array(
              'name' => 'imagen',
              'allowed_types' => '*',
              'maxsize' => 204800,
              'folder' => '/uploads/estacionales/',
              'keep' => true,
              'prefix' => '',
            )
          );

  }

  function onEditReady($id='') {
    $this->data['categorias'] = $this->model->getList('', 'nombre asc');
  }
  
  function onBeforeSave($id='') {
		//le defino un slug si es que no lo puso
		$_POST['slug'] = $_POST['slug'] ? $_POST['slug'] : url_title($_POST['nombre']);
		
	}
	
    
}