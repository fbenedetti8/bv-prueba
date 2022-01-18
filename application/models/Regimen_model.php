<?php
class Regimen_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_regimenes";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}

	//devuelve los regimenes asociadas al paquete
	function getByPaquete($paquete_id) {
		$this->db->select('ad.*, r.nombre as regimen,
							CONCAT(a.nombre," (",ifnull(ac.total,0)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto');
		$this->db->join('bv_regimenes r','r.id = ad.regimen_id');
		$this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
		$this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('(select fecha_id, sum(case when habitacion_id != 99 then cupo_total else 0 end) as total from bv_alojamientos_fechas_cupos group by fecha_id) ac','ac.fecha_id = af.id','left');
	
		$res = $this->db->get_where('bv_paquetes_regimenes ad', array('ad.paquete_id' => $paquete_id))->result();

		return $res;
	}
	
	function addPaquete($paquete_id,$regimen_id,$fecha_alojamiento_id) {
		$this->db->insert('bv_paquetes_regimenes', array(
		  'regimen_id' => $regimen_id,
		  'fecha_alojamiento_id' => $fecha_alojamiento_id,
		  'paquete_id' => $paquete_id
		));
		
		return $this->db->insert_id();
	}
	
	function getAsociacionPaquete($id){
		$this->db->select('r.*, r.nombre as regimen,
							CONCAT(a.nombre," (",ifnull(ac.total,0)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto');
		$this->db->join('bv_regimenes r','r.id = ad.regimen_id');
		$this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
		$this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('(select fecha_id, sum(case when habitacion_id != 99 then cupo_total else 0 end) as total from bv_alojamientos_fechas_cupos group by fecha_id) ac','ac.fecha_id = af.id','left');
	
		return $this->db->get_where('bv_paquetes_regimenes ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionPaquete($id){
		$row = $this->db->get_where('bv_paquetes_regimenes', array('id' => $id))->row();

	    $ordenes = $this->db->get_where('bv_ordenes', array('paquete_id' => $row->paquete_id, 'paquete_regimen_id' => $id, 'vencida' => 0))->result();
	    $reservas = $this->db->get_where('bv_reservas', array('paquete_id' => $row->paquete_id, 'paquete_regimen_id' => $id))->result();

	    if (count($ordenes) || count($reservas)) {
	      return FALSE;
	    }
	    else {
			$this->db->where('id = '.$id);
			$this->db->delete('bv_paquetes_regimenes');

			return TRUE;
		}
	} 
	
	
	//devuelve los regimenes asociados a las combinaciones del paquete
	function getCombinacionPaquete($paquete_id,$filtros=array()) {
		$this->db->select('ad.*, r.nombre as regimen',FALSE);
		$this->db->join('bv_paquetes_combinaciones pc','ad.id = pc.paquete_regimen_id and pc.paquete_id = '.$paquete_id);
		$this->db->join('bv_regimenes r','r.id = pc.regimen_id');
		
		if(isset($filtros['pax']) && $filtros['pax']){
			$this->db->join('bv_habitaciones h','h.id = pc.habitacion_id and h.pax = '.$filtros['pax']);
		}
		
		if(isset($filtros['lugar_salida']) && $filtros['lugar_salida']){
			$this->db->where('pc.lugar_id = '.$filtros['lugar_salida']);
		}
		
		/*
		if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
			$this->db->where('pc.fecha_alojamiento_id = '.$filtros['fecha_id'].' and ad.fecha_alojamiento_id = '.$filtros['fecha_id']);
		}
		*/
		//fecha_id ahroa se usa como rango
		if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
			$rango = explode('|',$filtros['fecha_id']);
			//and af.id = pc.fecha_alojamiento_id
			$this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id  and af.fecha_checkin = "'.$rango[0].'" and af.fecha_checkout = "'.$rango[1].'"');
		}
		
		if(isset($filtros['alojamiento']) && $filtros['alojamiento']){
			$this->db->where('pc.alojamiento_id = '.$filtros['alojamiento']);
		}
		
		if(isset($filtros['habitacion']) && $filtros['habitacion']){
			$this->db->where('pc.habitacion_id = '.$filtros['habitacion']);
		}
		
		$this->db->group_by('r.id');
		$this->db->order_by('r.orden asc, r.nombre asc');
		$res = $this->db->get_where('bv_paquetes_regimenes ad', array('ad.paquete_id' => $paquete_id))->result();

		return $res;
	}
	
}