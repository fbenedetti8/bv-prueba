<?php
class Oficina_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_oficina";
    $this->indexable = array('imagen');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'orden';
  }

}