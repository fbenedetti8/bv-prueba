<?php
class Orden_pasajero_adicional_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_ordenes_pasajeros_adicionales";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

/*	function onGetAll(){
		$this->db->select('p.*, bv_reservas_pasajeros.responsable, bv_reservas_pasajeros.numero_pax, 
								bv_reservas_pasajeros.completo, bv_reservas_pasajeros.salteo, bv_reservas_pasajeros.pasajero_id, r.estado_id, re.nombre as estado, 
								ifnull(pa.nombre,"") as nacionalidad, ifnull(pa2.nombre,"") as pais_emision');
		$this->db->join('bv_reservas r','r.id = bv_reservas_pasajeros.reserva_id');
		$this->db->join('bv_pasajeros p','p.id = bv_reservas_pasajeros.pasajero_id');
		$this->db->join('bv_paises pa','pa.id = p.nacionalidad_id','left');
		$this->db->join('bv_paises pa2','pa2.id = p.pais_emision_id','left');
		$this->db->join('bv_reservas_estados re','re.id = r.estado_id','left');
	}
	
	function onGet(){
		$this->db->select('p.*, bv_reservas_pasajeros.responsable, bv_reservas_pasajeros.numero_pax, 
								bv_reservas_pasajeros.completo, bv_reservas_pasajeros.salteo, bv_reservas_pasajeros.pasajero_id, r.estado_id, 
								ifnull(pa.nombre,"") as nacionalidad, ifnull(pa2.nombre,"") as pais_emision');
		$this->db->join('bv_reservas r','r.id = bv_reservas_pasajeros.reserva_id');
		$this->db->join('bv_pasajeros p','p.id = bv_reservas_pasajeros.pasajero_id');
		$this->db->join('bv_paises pa','pa.id = p.nacionalidad_id','left');
		$this->db->join('bv_paises pa2','pa2.id = p.pais_emision_id','left');
	}
	*/
	function getWhere($arr_fields){
		$this->onGet();
		
		$this->db->where($arr_fields);
		return $this->db->get($this->table);
	}
	
}