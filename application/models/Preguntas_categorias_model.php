<?php
class Preguntas_categorias_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_preguntas_categorias";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'orden';
  }

}