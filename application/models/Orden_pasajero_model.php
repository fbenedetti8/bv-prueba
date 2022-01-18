<?php
class Orden_pasajero_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_ordenes_pasajeros";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

	/*
	Chequea si existe otra orden de pasajero con esa combinacion de EMAIL+DNI para esa combinacion_id
	que no sea el PAX_ID actual (el que esta actualizando sus datos)
	*/
	function existeOrdenPasajero($email,$dni,$combinacion_id,$pax_id,$pasaporte=false){
		$this->db->join('bv_ordenes o','o.id = op.orden_id and o.combinacion_id = '.$combinacion_id);
		$this->db->where('op.id != '.$pax_id);
		
		if($email){
			$this->db->where('op.email = "'.$email.'"');
		}
		if($dni){
			$this->db->where('op.dni = '.$dni);
		}
		if($pasaporte){
			$this->db->where('op.pasaporte = "'.$pasaporte.'"');
		}

		$this->db->where('(o.vencida = 0 or o.vencida is null)');

		//que busque en las ordenes que no están efectivizadas como reservas
		$this->db->where('o.id not in (select orden_id from bv_reservas)');

		return $this->db->get_where($this->table.' op',array('op.responsable' => 1))->result();
	}
	
	function onGet(){
		$this->db->select('bv_ordenes_pasajeros.*, ifnull(pa.nombre,"") as nacionalidad, ifnull(pa2.nombre,"") as pais_emision');
		$this->db->join('bv_paises pa','pa.id = bv_ordenes_pasajeros.nacionalidad_id','left');
		$this->db->join('bv_paises pa2','pa2.id = bv_ordenes_pasajeros.pais_emision_id','left');
	}

	function getWhere($arr_fields){
		$this->onGet();
		
		$this->db->where($arr_fields);
		return $this->db->get($this->table);
	}
	
}