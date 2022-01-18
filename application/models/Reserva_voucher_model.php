<?php
class Reserva_voucher_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas_vouchers";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

	
}