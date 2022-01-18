<?php
class Parada_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_paradas";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGet(){
		$this->db->select('bv_paradas.*, l.nombre as lugar, concat(l.nombre," - ",bv_paradas.nombre) as nombrecompleto');
		$this->db->join('bv_lugares_salida l','l.id = bv_paradas.lugar_id');
	}
	
	function onGetAll(){
		$this->db->select('bv_paradas.*, l.nombre as lugar, concat(l.nombre," - ",bv_paradas.nombre) as nombrecompleto');
		$this->db->join('bv_lugares_salida l','l.id = bv_paradas.lugar_id');
	}
	
	//devuelve las paradas asociadas al paquete
	function getByPaquete($paquete_id,$lugar_id=false) {
		$this->db->select('ad.*, a.nombre, concat(l.nombre," - ",a.nombre) as nombrecompleto, concat(l.nombre," - ",a.nombre," - ",ad.hora,"hs") as nombrefull');
		if($lugar_id)
			$this->db->join('bv_paradas a','a.id = ad.parada_id and a.lugar_id = '.$lugar_id);
		else
			$this->db->join('bv_paradas a','a.id = ad.parada_id');
		
		$this->db->join('bv_lugares_salida l','l.id = a.lugar_id');
		$this->db->order_by('ad.hora asc');
		$servicios = $this->db->get_where('bv_paquetes_paradas ad', array('ad.paquete_id' => $paquete_id))->result();

		return $servicios;
	}

	function addPaquete($paquete_id, $parada_id, $hora) {
		$this->db->insert('bv_paquetes_paradas', array(
		  'parada_id' => $parada_id,
		  'paquete_id' => $paquete_id,
		  'hora' => $hora
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionPaquete($id){
		$this->db->select('a.*, ad.paquete_id, ad.hora, a.nombre as parada, concat(l.nombre," - ",a.nombre) as nombrecompleto');
		$this->db->join('bv_paradas a','a.id = ad.parada_id');
		$this->db->join('bv_lugares_salida l','l.id = a.lugar_id');
		return $this->db->get_where('bv_paquetes_paradas ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionPaquete($id){
	    $ordenes = $this->db->get_where('bv_ordenes', array('paquete_parada_id' => $id, 'vencida' => 0))->result();

	    $reservas = $this->db->get_where('bv_reservas', array('paquete_parada_id' => $id))->result();

	    if (count($ordenes) || count($reservas)) {
	      return FALSE;
	    }
	    else {
			$this->db->where('id = '.$id);
			$this->db->delete('bv_paquetes_paradas');
			return TRUE;
		}
	}  
	
}