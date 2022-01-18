<?php
class Reserva_facturacion_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas_facturacion";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

	
}