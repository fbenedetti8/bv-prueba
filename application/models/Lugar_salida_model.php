<?php
class Lugar_salida_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_lugares_salida";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//devuelve los lugares de salida asociados al paquete
	function getByPaquete($paquete_id) {
		$this->db->select('ad.*, a.nombre as lugar');
		$this->db->join('bv_lugares_salida a','a.id = ad.lugar_id');
		$servicios = $this->db->get_where('bv_paquetes_lugares ad', array('ad.paquete_id' => $paquete_id))->result();

		return $servicios;
	}

	function addPaquete($paquete_id, $lugar_id) {
		$this->db->insert('bv_paquetes_lugares', array(
		  'lugar_id' => $lugar_id,
		  'paquete_id' => $paquete_id
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionPaquete($id){
		$this->db->select('a.*, a.nombre as lugar');
		$this->db->join('bv_lugares_salida a','a.id = ad.lugar_id');
		return $this->db->get_where('bv_paquetes_lugares ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionPaquete($id){
		$this->db->where('id = '.$id);
		$this->db->delete('bv_paquetes_lugares');
		return true;
	}  
	
	function deleteByPaquete($id){
		$this->db->where('paquete_id = '.$id);
		$this->db->delete('bv_paquetes_lugares');
		return true;
	}  
	
	
	//devuelve los lugares de salida asociadas a las combinaciones del paquete
	//para micro: con menor y mayor hora de las paradas que tenga
	//avion y crucero, muestra unico horario
	function getCombinacionPaquete($paquete_id,$filtros=array()) {
		$this->db->select('ls.id, ls.nombre as lugar, 
							min(pp.hora) as hora_min_full, 
							max(pp.hora) as hora_max_full, 
							substr(min(pp.hora),1,2) as hora_min,
							substr(max(pp.hora),1,2) as hora_max');
		$this->db->join('bv_paquetes_combinaciones pc','ls.id = pc.lugar_id and pc.paquete_id = '.$paquete_id);
		$this->db->join('bv_paradas p','p.lugar_id = ls.id','left');
		$this->db->join('bv_paquetes_paradas pp','p.id = pp.parada_id and pp.paquete_id = '.$paquete_id,'left');
		
		if(isset($filtros['pax']) && $filtros['pax']){
			$this->db->join('bv_habitaciones h','h.id = pc.habitacion_id and h.pax = '.$filtros['pax']);
		}
		
		$this->db->group_by('ls.id');
		$this->db->order_by('p.lugar_id asc');
		
		$res = $this->db->get('bv_lugares_salida ls')->result();
		return $res;
	}
}