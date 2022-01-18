<?php
class Newsletter_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_suscriptos";
		$this->indexable = array('email');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}