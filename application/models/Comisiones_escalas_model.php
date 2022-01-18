<?php
class Comisiones_escalas_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_comisiones_escalas";
		$this->indexable = array('rol');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}