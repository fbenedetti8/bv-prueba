<?php
class Fecha_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_transportes_fechas";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//devuelve las fechas asociadas al transporte
	function getByTransporte($transporte_id) {
		$this->db->select('ad.id as ft_id, ad.*');
		$this->db->order_by('ad.fecha_salida asc');
		$servicios = $this->db->get_where('bv_transportes_fechas ad', array('ad.transporte_id' => $transporte_id))->result();

		return $servicios;
	}

	function addTransporte($transporte_id, $fecha_salida, $vuelo_ida,$fecha_regreso, $vuelo_regreso, $cupo_total,$vuelo_aeropuerto=false,$fecha_vencimiento=false) {
		$this->db->insert('bv_transportes_fechas', array(
		  'transporte_id' => $transporte_id,
		  'fecha_salida' => $fecha_salida,
		  'vuelo_ida' => $vuelo_ida,
		  'fecha_regreso' => $fecha_regreso,
		  'vuelo_regreso' => $vuelo_regreso,
		  'vuelo_aeropuerto' => $vuelo_aeropuerto?$vuelo_aeropuerto:'',
		  'fecha_vencimiento' => $fecha_vencimiento?$fecha_vencimiento:'0000-00-00',
		  'cupo' => $cupo_total,
		  'cupo_total' => $cupo_total
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionTransporte($id){
		$this->db->select('a.*, a.nombre as transporte, 
							ad.id as ft_id, ad.fecha_salida, ad.vuelo_ida, ad.fecha_regreso, ad.vuelo_regreso, ad.cupo_total, ad.vuelo_aeropuerto, ad.fecha_vencimiento');
		$this->db->join('bv_transportes a','a.id = ad.transporte_id');
		return $this->db->get_where('bv_transportes_fechas ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionTransporte($id){
		//Verificar que no haya reservas con esta asociacion
		$ordenes = $this->db->get_where('bv_ordenes', array('transporte_fecha_id' => $id, 'vencida' => 0))->result();
		if (count($ordenes)) {
			return false;
		}

		//Verificar que no haya reservas con esta asociacion
		$reservas = $this->db->get_where('bv_reservas', array('transporte_fecha_id' => $id))->result();
		if (count($reservas)) {
			return false;
		}

		$this->db->where('id = '.$id);
		$this->db->delete('bv_transportes_fechas');
		return true;
	}  
	function updateAsociacionTransporte($id,$upd){
		$this->db->where('id = '.$id);
		$this->db->update('bv_transportes_fechas',$upd);
		return true;
	}  
	
}