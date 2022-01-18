<?php
class Testimonio_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_testimonios";
		$this->indexable = array('nombre','testimonio');
		$this->fk = "id";
		$this->pk = "id";
	}
	
  
}