<?php
class Pasajero_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_pasajeros";
		$this->indexable = array('nombre','apellido','email','dni');
		$this->fk = "id";
		$this->pk = "id";
	}
		
}