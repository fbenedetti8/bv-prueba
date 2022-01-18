<?php
class Lista_espera_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_lista_espera";
		$this->indexable = array('nombre','email');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	
	function getAllExport($id,$tipo){
		$this->db->select("nombre, email, celular_codigo, celular_numero, telefono_codigo, telefono_numero, fecha, ip");
		$this->db->where("tipo = '".$tipo."' and tipo_id = ".$id);
		$this->db->order_by("fecha asc");
		return $this->db->get($this->table)->result();
	}
	
}