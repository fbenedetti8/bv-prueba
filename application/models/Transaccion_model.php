<?php
class Transaccion_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_transacciones";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

}