<?php
class Fecha_alojamiento_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_alojamientos_fechas";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//devuelve las fechas asociadas al alojamiento
	function getByAlojamiento($alojamiento_id) {
		$this->db->select('ad.*, concat(DATE_FORMAT(ad.fecha_checkin, "%d/%m/%Y")," al ",DATE_FORMAT(ad.fecha_checkout, "%d/%m/%Y")) as fecha');
		$this->db->order_by('ad.fecha_checkin asc');
		$servicios = $this->db->get_where('bv_alojamientos_fechas ad', array('ad.alojamiento_id' => $alojamiento_id))->result();

		return $servicios;
	}

	function addAlojamiento($alojamiento_id, $fecha_checkin,$fecha_checkout, $descripcion='') {
		$this->db->insert('bv_alojamientos_fechas', array(
		  'alojamiento_id' => $alojamiento_id,
		  'fecha_checkin' => $fecha_checkin,
		  'fecha_checkout' => $fecha_checkout,
		  'descripcion' => $descripcion
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionAlojamiento($id){
		$this->db->select('a.*, a.nombre as alojamiento, 
							DATE_FORMAT(ad.fecha_checkin, "%d/%m/%Y") as fecha_checkin, 
							DATE_FORMAT(ad.fecha_checkout, "%d/%m/%Y") as fecha_checkout,
							ad.descripcion');
		$this->db->join('bv_alojamientos a','a.id = ad.alojamiento_id');
		return $this->db->get_where('bv_alojamientos_fechas ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionAlojamiento($id){
		//Verificar que no haya reservas con esta asociacion
		$ordenes = $this->db->get_where('bv_ordenes', array('fecha_alojamiento_id' => $id, 'vencida' => 0))->result();
		if (count($ordenes)) {
			return false;
		}

		//Verificar que no haya reservas con esta asociacion
		$reservas = $this->db->get_where('bv_reservas', array('fecha_alojamiento_id' => $id))->result();
		if (count($reservas)) {
			return false;
		}

		$this->db->where('id', $id);
		$this->db->delete('bv_alojamientos_fechas');
		return true;
	}  
	
	function getCombinacionPaquete($id,$filtros=array()){
		$this->db->select('a.nombre, af.id, af.fecha_checkin as fecha_in, af.fecha_checkout as fecha_out,
							DATE_FORMAT(af.fecha_checkin, "%d/%m/%Y") as fecha_checkin, 
							DATE_FORMAT(af.fecha_checkout, "%d/%m/%Y") as fecha_checkout');
		$this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		
		if(isset($filtros['lugar_salida']) && $filtros['lugar_salida']){
			$this->db->join('bv_paquetes_combinaciones pc','pc.fecha_alojamiento_id = af.id and pc.paquete_id = '.$id.' and pc.lugar_id = '.$filtros['lugar_salida']);
		}
		else{
			$this->db->join('bv_paquetes_combinaciones pc','pc.fecha_alojamiento_id = af.id and pc.paquete_id = '.$id);
		}
		
		if(isset($filtros['pax']) && $filtros['pax']){
			$this->db->join('bv_habitaciones h','h.id = pc.habitacion_id and h.pax = '.$filtros['pax']);
		}
		
		//agrupo por rango de fecha, no por ID
		#$this->db->group_by('af.id');
		$this->db->group_by('3,4');
		return $this->db->get('bv_alojamientos_fechas af')->result();
	}  
	
	function getAlojCombinacionPaquete($id,$filtros=array()){
		$this->db->select('a.*');
		$this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('bv_paquetes_combinaciones pc','pc.fecha_alojamiento_id = af.id and pc.paquete_id = '.$id);
		
		
		if(isset($filtros['pax']) && $filtros['pax']){
			$this->db->join('bv_habitaciones h','h.id = pc.habitacion_id and h.pax = '.$filtros['pax']);
		}
		
		if(isset($filtros['lugar_salida']) && $filtros['lugar_salida']){
			$this->db->where('pc.lugar_id = '.$filtros['lugar_salida']);
		}
		
		//fecha_id ahroa se usa como rango
		if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
			$rango = explode('|',$filtros['fecha_id']);
			//$this->db->where('af.id = '.$filtros['fecha_id']);
			$this->db->where('af.fecha_checkin = "'.$rango[0].'" and af.fecha_checkout = "'.$rango[1].'"');
		}
		
		$this->db->group_by('a.id');
		return $this->db->get('bv_alojamientos_fechas af')->result();
	}  

	function saveDescripcion($fecha_id, $descripcion) {
		$this->db->where('id', $fecha_id);
		$this->db->update('bv_alojamientos_fechas', array('descripcion' => $descripcion));
	}
}