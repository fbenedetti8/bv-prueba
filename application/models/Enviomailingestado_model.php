<?php
class Enviomailingestado_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_mailings_envios_estados";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
		
}