<?php
class Tipotransporte_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_tipos_transportes";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}