<?php
class Pais_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_paises";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}