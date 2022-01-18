<?php
class Reserva_estado_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas_estados";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
}