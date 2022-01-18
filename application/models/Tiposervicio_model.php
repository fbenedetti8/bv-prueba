<?php
class Tiposervicio_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "ua_tiposervicios";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
}