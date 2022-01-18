<?php
class Operador_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_operadores";
    $this->indexable = array('nombre','razonsocial','cuit','legajo');
    $this->fk = "id";
    $this->pk = "id";
  }

}