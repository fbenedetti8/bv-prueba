<?php
class Comisiones_minimos_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_comisiones_minimos";
		$this->indexable = array('rol');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}