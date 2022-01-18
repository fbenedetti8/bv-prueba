<?php
class Sucursal_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_sucursales";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}

}