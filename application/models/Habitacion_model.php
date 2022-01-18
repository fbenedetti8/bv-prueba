<?php
class Habitacion_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_habitaciones";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//devuelve las habitaciones de alojamientos asociados al paquete
 	 function getByPaquete($paquete_id) {
	    $this->db->distinct();
	    $this->db->select('tt.fecha_alojamiento_cupo_id, a.nombre as alojamiento, afc.habitacion_id, 
							CONCAT(a.nombre," (",(case when afc.habitacion_id != 99 then afc.cupo_total else 0 end)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto, 
							h.nombre as habitacion, h.pax, afc.id, afc.cupo, afc.cantidad, afc.cupo_total',false);
	    $this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
	    $this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('bv_alojamientos_fechas_cupos afc','afc.fecha_id = af.id');
	    $this->db->join('bv_habitaciones h','h.id = afc.habitacion_id');
	    $this->db->join('(select paquete_id, fecha_alojamiento_cupo_id from bv_paquetes_combinaciones where paquete_id = '.$paquete_id.' group by fecha_alojamiento_cupo_id) tt','tt.paquete_id = ad.paquete_id');
	    $this->db->where('tt.fecha_alojamiento_cupo_id = afc.id');
	    $this->db->order_by('2 asc,h.pax asc');
		$servicios = $this->db->get_where('bv_paquetes_alojamientos ad', array('ad.paquete_id' => $paquete_id))->result();

	    return $servicios;
 	 }

	/*
	//devuelve las habitaciones asociadas al paquete
	function getByPaquete($paquete_id) {
		$this->db->select('ad.*, a.nombre as habitacion, ad.cantidad');
		$this->db->join('bv_habitaciones a','a.id = ad.habitacion_id');
		$servicios = $this->db->get_where('bv_paquetes_habitaciones ad', array('ad.paquete_id' => $paquete_id))->result();

		return $servicios;
	}

	function addPaquete($paquete_id, $habitacion_id, $cantidad) {
		$this->db->insert('bv_paquetes_habitaciones', array(
		  'habitacion_id' => $habitacion_id,
		  'cantidad' => $cantidad,
		  'paquete_id' => $paquete_id
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionPaquete($id){
		$this->db->select('a.*, a.nombre as habitacion, ad.cantidad');
		$this->db->join('bv_habitaciones a','a.id = ad.habitacion_id');
		return $this->db->get_where('bv_paquetes_habitaciones ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionPaquete($id){
		$this->db->where('id = '.$id);
		$this->db->delete('bv_paquetes_habitaciones');
		return true;
	}  
	*/
	
	//devuelve las habitaciones asociadas a las combinaciones del paquete
	function getCombinacionPaquete($paquete_id) {
		$this->db->select('h.*');
		$this->db->join('bv_paquetes_combinaciones pc','h.id = pc.habitacion_id and pc.paquete_id = '.$paquete_id);
		//$this->db->group_by('h.id');
		$this->db->group_by('h.pax');
		$this->db->order_by('h.pax asc');
		$res = $this->db->get('bv_habitaciones h')->result();
		return $res;
	}
	
	//devuelve los tipos de habitaciones asociadas a las combinaciones del paquete
	function getTiposCombinacionPaquete($paquete_id,$filtros=array()) {
		$this->db->select('h.*, p.cupo_disponible, (case when p.cupo_disponible<h.pax then true else false end) as completo');
		$this->db->join('bv_paquetes_combinaciones pc','h.id = pc.habitacion_id and pc.paquete_id = '.$paquete_id);
		$this->db->join('bv_paquetes p','p.id = pc.paquete_id');
		
		if(isset($filtros['pax']) && $filtros['pax']){
			$this->db->where('h.pax = '.$filtros['pax']);
		}
		
		//esta marca es solo para los grupales
		if(isset($filtros['pax_elegidos']) && $filtros['pax_elegidos']){
			$this->db->where('(h.id = 99 or h.pax = '.$filtros['pax_elegidos'].')');
		}
		
		if(isset($filtros['lugar_salida']) && $filtros['lugar_salida']){
			$this->db->where('pc.lugar_id = '.$filtros['lugar_salida']);
		}
		
		/*
		if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
			$this->db->where('pc.fecha_alojamiento_id = '.$filtros['fecha_id']);
		}
		*/
		//fecha_id ahroa se usa como rango
		if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
			$rango = explode('|',$filtros['fecha_id']);
			
			$this->db->join('bv_alojamientos_fechas af','pc.fecha_alojamiento_id and af.fecha_checkin = "'.$rango[0].'" and af.fecha_checkout = "'.$rango[1].'"');
		}
		
		if(isset($filtros['alojamiento']) && $filtros['alojamiento']){
			$this->db->where('pc.alojamiento_id = '.$filtros['alojamiento']);
		}
		
		$this->db->group_by('h.id');
		$this->db->order_by('h.orden asc, h.pax asc');
		$res = $this->db->get('bv_habitaciones h')->result();
		return $res;
	}
	
	function get_dispo_habitacion($id){
		$this->db->select('(case when p.cupo_disponible<h.pax then true else false end) as completo');
		$this->db->join('bv_paquetes_combinaciones pc','o.habitacion_id = pc.habitacion_id and pc.id = '.$id);
		$this->db->join('bv_paquetes p','p.id = pc.paquete_id');
		$this->db->join('bv_habitaciones h','h.id = o.habitacion_id');
		$res = $this->db->get('bv_ordenes o')->result();
		return $res;
	}
	
}