<?php
class Comisiones_porcentajes_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_comisiones_porcentajes";
		$this->indexable = array('rol');
		$this->fk = "id";
		$this->pk = "id";
	}

	function onGetAll() {
		$this->db->select('bv_comisiones_porcentajes.*, esc.nombre as escala');
		$this->db->join('bv_comisiones_escalas esc', 'esc.id = bv_comisiones_porcentajes.escala_id');
	}
	
}