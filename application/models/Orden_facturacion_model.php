<?php
class Orden_facturacion_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_ordenes_facturacion";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

	
}