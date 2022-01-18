<?php
class Caja_descuento_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_caja_descuentos";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
}