<?php
class Config_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_config";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
}