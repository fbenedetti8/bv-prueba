<?php
class Promocion_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_promociones";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
  }

}