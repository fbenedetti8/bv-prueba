<?php
class Fecha_alojamiento_cupo_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_alojamientos_fechas_cupos";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//devuelve los cupos de las fechas asociadas al alojamiento
	function getByAlojamiento($alojamiento_id) {
		$this->db->select('ad.*, concat(DATE_FORMAT(f.fecha_checkin, "%d/%m/%Y")," al ",DATE_FORMAT(f.fecha_checkout, "%d/%m/%Y")) as fecha, 
			h.nombre as habitacion, h.pax');
		/*,
			ifnull(t.paquetes,"") as paquetes*/
		$this->db->join('bv_habitaciones h','h.id = ad.habitacion_id');
		$this->db->join('bv_alojamientos_fechas f','f.id = ad.fecha_id and f.alojamiento_id = '.$alojamiento_id);
		/*$this->db->join('(select group_concat(distinct p.codigo) as paquetes, fc.id
							from bv_alojamientos_fechas_cupos fc
							join bv_alojamientos_fechas af on af.id = fc.fecha_id
							join bv_paquetes_alojamientos pa on pa.fecha_alojamiento_id = af.id
							join bv_paquetes p on p.id = pa.paquete_id
							group by fc.id) t','t.id = ad.id','left');*/
		$servicios = $this->db->get('bv_alojamientos_fechas_cupos ad')->result();

		return $servicios;
	}

	function addHabitacion($fecha_id,$habitacion_id,$cupo_total,$cantidad) {
		$rows = $this->db->get_where('bv_alojamientos_fechas_cupos', array(
		  'fecha_id' => $fecha_id,
		  'habitacion_id' => $habitacion_id,
		))->result();

		if ($rows) {
			return FALSE;
		}
		else {
			$this->db->insert('bv_alojamientos_fechas_cupos', array(
			  'fecha_id' => $fecha_id,
			  'habitacion_id' => $habitacion_id,
			  'cantidad' => $cantidad,
			  'cupo' => $cupo_total,
			  'cupo_total' => $cupo_total,
			));
			
			return $this->db->insert_id();
		}
	}

	function getAsociacionHabitacion($id){
		$this->db->select('ad.*, concat(DATE_FORMAT(f.fecha_checkin, "%d/%m/%Y")," al ",DATE_FORMAT(f.fecha_checkout, "%d/%m/%Y")) as fecha, 
							h.nombre as habitacion');
		$this->db->join('bv_habitaciones h','h.id = ad.habitacion_id');
		$this->db->join('bv_alojamientos_fechas f','f.id = ad.fecha_id');
		return $this->db->get_where('bv_alojamientos_fechas_cupos ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionHabitacion($id){
		$this->db->where('id = '.$id);
		$this->db->delete('bv_alojamientos_fechas_cupos');
		return true;
	}  
	
	function updateAsociacionHabitacion($id,$upd){
		$this->db->where('id = '.$id);
		$this->db->update('bv_alojamientos_fechas_cupos',$upd);
		return true;
	}  
	
}